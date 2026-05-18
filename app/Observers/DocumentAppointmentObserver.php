<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\DocumentAppointment;

/**
 * Keeps the linked Document record in perfect sync whenever an
 * appointment status changes.
 *
 * The mapping is intentionally 1-to-1: Document now carries the full
 * appointment status set (Confirmed / Ready included) so the Document
 * Issuance blade always shows the live, authoritative status.
 *
 * updateQuietly() is used so that saving the Document does NOT re-fire
 * Eloquent events, preventing any echo / infinite-loop scenario.
 */
class DocumentAppointmentObserver
{
    public function updated(DocumentAppointment $appointment): void
    {
        if (! $appointment->wasChanged('status')) {
            return;
        }

        $document = Document::where('appointment_id', $appointment->id)->first();
        if (! $document) {
            return;
        }

        $updates = ['status' => $appointment->status];

        // Auto-stamp released_at when the document transitions to Released
        if ($appointment->status === 'Released' && ! $document->released_at) {
            $updates['released_at'] = now();
        }

        $document->updateQuietly($updates);
    }
}
