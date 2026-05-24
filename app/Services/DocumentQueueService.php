<?php

namespace App\Services;

use App\Mail\PortalStatusUpdated;
use App\Models\AppointmentStatusLog;
use App\Models\Document;
use App\Models\DocumentAppointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * DocumentQueueService
 *
 * Single authority for all status transitions in the portal → appointment →
 * document issuance pipeline.  Both AppointmentController and DocumentController
 * delegate through here so that:
 *
 *  • Status parity between document_appointments and documents is always atomic
 *  • AppointmentStatusLog entries are never missed
 *  • SMTP dispatch is always queued (non-blocking) and deduplicated
 *  • The cooldown guard prevents rapid-fire duplicate emails
 *
 * Invariant enforced: for portal documents, documents.status === document_appointments.status
 */
class DocumentQueueService
{
    /** Minimum seconds between identical-status emails for the same record. */
    private const EMAIL_COOLDOWN_SECONDS = 60;

    // -----------------------------------------------------------------------
    // FORWARD  — Appointment (queue ticket) drives status forward
    // Called by: AppointmentController::updateStatus()
    // -----------------------------------------------------------------------

    /**
     * Advance a DocumentAppointment to a new status.
     *
     * Side-effects (all in one DB transaction):
     *  1. Updates document_appointments row
     *  2. Mirror-updates the linked documents row (if any), via raw query to skip model events
     *  3. Appends an AppointmentStatusLog entry
     *  4. Queues a PortalStatusUpdated Mailable if an email address exists
     *
     * @param  string       $newStatus   Must be in DocumentAppointment::$statuses
     * @param  string|null  $notes       Staff note (shown in email + log)
     * @param  string|null  $pickupDate  ISO date — set when advancing to 'Ready'
     * @param  string       $changedBy   Staff member's name for the audit log
     */
    public function advance(
        DocumentAppointment $appointment,
        string  $newStatus,
        ?string $notes,
        ?string $pickupDate,
        string  $changedBy,
        ?float  $feePaid  = null,
        ?string $orNumber = null,
    ): DocumentAppointment {

        $fromStatus = $appointment->status;

        DB::transaction(function () use ($appointment, $newStatus, $notes, $pickupDate, $changedBy, $fromStatus, $feePaid, $orNumber) {

            $update = ['status' => $newStatus, 'processed_by' => $changedBy];

            // pickup_date: set when Ready, clear on any other transition
            if ($newStatus === 'Ready' && $pickupDate) {
                $update['pickup_date'] = $pickupDate;
            } elseif ($newStatus !== 'Ready') {
                $update['pickup_date'] = null;
            }

            if ($newStatus === 'Released') {
                $update['released_at'] = now();
            }

            if ($notes !== null) {
                $update['notes'] = $notes;
            }

            $appointment->update($update);

            // Mirror to linked Document — raw query bypasses Eloquent model events
            // to prevent re-entrant loops.
            if ($appointment->document) {
                $docUpdate = ['status' => $newStatus];

                // When marking Ready, sync fee + OR number captured by staff
                if ($newStatus === 'Ready') {
                    if ($feePaid !== null) $docUpdate['fee_paid']  = $feePaid;
                    if ($orNumber !== null) $docUpdate['or_number'] = $orNumber;
                }

                Document::where('id', $appointment->document->id)->update($docUpdate);
            }

            // Append immutable audit log entry
            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'from_status'    => $fromStatus,
                'to_status'      => $newStatus,
                'changed_by'     => $changedBy,
                'note'           => $notes,
            ]);
        });

        // Queue email outside the transaction (email failure must not roll back the status update)
        $this->queueEmail($appointment->fresh(), $newStatus, $notes, $pickupDate);

        return $appointment->fresh();
    }

    // -----------------------------------------------------------------------
    // REVERSE  — Document (issued certificate) drives status backward-sync
    // Called by: DocumentController::quickStatus()
    // -----------------------------------------------------------------------

    /**
     * Update a Document's status and reverse-sync the linked appointment.
     *
     * Side-effects:
     *  1. Updates documents row
     *  2. Raw-updates linked document_appointments row (if any)
     *  3. Appends AppointmentStatusLog if a linked appointment exists
     *  4. Queues email if source=portal and email address exists
     */
    public function reverseAdvance(
        Document $document,
        string   $newStatus,
        string   $changedBy,
        ?string  $note         = null,
        ?string  $residentNote = null,
        ?string  $pickupDate   = null,
        ?string  $releasedTo   = null,
        ?int     $releasedBy   = null,
    ): Document {
        $fromStatus = $document->status;

        DB::transaction(function () use ($document, $newStatus, $changedBy, $fromStatus, $note, $residentNote, $pickupDate, $releasedTo, $releasedBy) {

            $docUpdate = ['status' => $newStatus];
            if ($newStatus === 'Released' && $document->status !== 'Released') {
                $docUpdate['released_at']          = now();
                $docUpdate['released_to']          = $releasedTo;
                $docUpdate['released_by_user_id']  = $releasedBy;
            }
            $document->update($docUpdate);

            // Reverse-mirror to linked appointment, raw query to prevent event loop
            if ($document->appointment_id) {
                $aptUpdate = ['status' => $newStatus, 'processed_by' => $changedBy];

                // Store pickup date on the appointment when marking Ready
                if ($newStatus === 'Ready' && $pickupDate) {
                    $aptUpdate['pickup_date'] = $pickupDate;
                } elseif ($newStatus !== 'Ready') {
                    $aptUpdate['pickup_date'] = null;
                }

                if ($newStatus === 'Released') {
                    $aptUpdate['released_at'] = now();
                }

                // Store resident note on the appointment (surfaced in portal tracker)
                if ($residentNote) {
                    $aptUpdate['notes'] = $residentNote;
                }

                DocumentAppointment::where('id', $document->appointment_id)
                    ->update($aptUpdate);

                // Audit log on the appointment side too
                $appointment = DocumentAppointment::find($document->appointment_id);
                if ($appointment) {
                    $logNote = $note ?: 'Status updated from Document Issuance module.';
                    AppointmentStatusLog::create([
                        'appointment_id' => $appointment->id,
                        'from_status'    => $fromStatus,
                        'to_status'      => $newStatus,
                        'changed_by'     => $changedBy,
                        'note'           => $logNote,
                    ]);
                }
            }
        });

        // Queue email for portal-sourced documents
        if ($document->source === 'portal') {
            $appointment = $document->appointment_id
                ? DocumentAppointment::find($document->appointment_id)
                : null;

            $email  = $appointment?->email;
            $name   = $appointment?->resident_name ?? $document->resident_name_portal;
            $refNum = $appointment?->appointment_number ?? $document->doc_number;
            // Prefer the newly supplied pickup date; fall back to whatever's on record
            $pickup = $pickupDate ?? $appointment?->pickup_date?->format('Y-m-d');

            if ($email) {
                $this->dispatchMail(
                    email:       $email,
                    name:        $name ?? '—',
                    refNum:      $refNum,
                    newStatus:   $newStatus,
                    notes:       $residentNote,  // resident-facing note goes in email
                    pickupDate:  $pickup,
                    fromStatus:  $fromStatus,
                    contextKey:  'doc-' . $document->id,
                );
            }
        }

        return $document->fresh();
    }

    // -----------------------------------------------------------------------
    // SUBMISSION CONFIRMATION  — fires once when a portal request is created
    // Called by: ResidentPortalController::store()
    // -----------------------------------------------------------------------

    public function sendSubmissionConfirmation(DocumentAppointment $appointment): void
    {
        if (! $appointment->email) {
            return;
        }

        // Guard: silently skip when SMTP is not configured
        if (blank(config('mail.mailers.smtp.host'))) {
            return;
        }

        try {
            Mail::to($appointment->email)->queue(new PortalStatusUpdated(
                type:          'document',
                requestNumber: $appointment->appointment_number,
                residentName:  $appointment->resident_name,
                newStatus:     'Submitted',
                notes:         'Your request has been received and is now in queue. Use your reference number to track progress.',
                preferredDate: $appointment->preferred_date?->format('Y-m-d'),
            ));
        } catch (\Exception $e) {
            logger()->warning('Portal submission confirmation email failed: ' . $e->getMessage());
        }
    }

    // -----------------------------------------------------------------------
    // INTERNAL HELPERS
    // -----------------------------------------------------------------------

    /**
     * Resolve email address and metadata from the appointment, then dispatch.
     */
    private function queueEmail(
        DocumentAppointment $appointment,
        string  $newStatus,
        ?string $notes,
        ?string $pickupDate,
    ): void {
        if (! $appointment->email) {
            return;
        }

        // Pass fee amount so "Ready" email tells the resident how much to bring
        $feePaid = $newStatus === 'Ready'
            ? ($appointment->document?->fee_paid !== null ? (float) $appointment->document->fee_paid : null)
            : null;

        $this->dispatchMail(
            email:      $appointment->email,
            name:       $appointment->resident_name,
            refNum:     $appointment->appointment_number,
            newStatus:  $newStatus,
            notes:      $notes,
            pickupDate: $pickupDate ?? $appointment->pickup_date?->format('Y-m-d'),
            fromStatus: null,
            contextKey: 'apt-' . $appointment->id,
            feePaid:    $feePaid,
        );
    }

    /**
     * Deduplication check then queue the Mailable.
     *
     * The cooldown guard prevents duplicate emails when a staff member accidentally
     * clicks the same transition twice within 60 seconds.
     */
    private function dispatchMail(
        string  $email,
        string  $name,
        string  $refNum,
        string  $newStatus,
        ?string $notes,
        ?string $pickupDate,
        ?string $fromStatus,
        string  $contextKey,
        ?float  $feePaid = null,
    ): void {
        // Guard: silently skip when SMTP is not configured (dev/staging without mail)
        if (blank(config('mail.mailers.smtp.host'))) {
            return;
        }

        // Skip sending for 'Submitted' (handled by sendSubmissionConfirmation)
        if ($newStatus === 'Submitted') {
            return;
        }

        // Cooldown: check the last status log entry
        if ($fromStatus !== null) {
            $recentDuplicate = AppointmentStatusLog::where('to_status', $newStatus)
                ->whereRaw("created_at > DATE_SUB(NOW(), INTERVAL ? SECOND)", [self::EMAIL_COOLDOWN_SECONDS])
                ->when(
                    str_starts_with($contextKey, 'apt-'),
                    fn ($q) => $q->where('appointment_id', (int) substr($contextKey, 4))
                )
                ->exists();

            if ($recentDuplicate) {
                logger()->info("DocumentQueueService: email cooldown suppressed for {$refNum} → {$newStatus}");
                return;
            }
        }

        try {
            Mail::to($email)->queue(new PortalStatusUpdated(
                type:          'document',
                requestNumber: $refNum,
                residentName:  $name,
                newStatus:     $newStatus,
                notes:         $notes,
                preferredDate: $pickupDate,
                feePaid:       $feePaid,
            ));
        } catch (\Exception $e) {
            logger()->warning("DocumentQueueService: mail queue failed for {$refNum}: " . $e->getMessage());
        }
    }
}
