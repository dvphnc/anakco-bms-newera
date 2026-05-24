<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\AppointmentStatusLog;
use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Services\DocumentQueueService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class AppointmentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DocumentAppointment::with('document')->select('document_appointments.*')
                ->when($request->status,        fn ($q) => $q->whereIn('status', (array) $request->status))
                ->when($request->document_type, fn ($q) => $q->whereIn('document_type', (array) $request->document_type));

            return DataTables::of($query)
                ->addColumn('number_col', function ($a) {
                    $badge = $a->source === 'portal'
                        ? '<span class="badge badge-blue" style="font-size:10px;margin-left:5px">Portal</span>'
                        : '<span class="badge badge-gray" style="font-size:10px;margin-left:5px">Walk-in</span>';
                    return '<span class="td-mono">'.e($a->appointment_number).'</span>'.$badge;
                })
                ->addColumn('resident_col', function ($a) {
                    return '<div style="font-weight:600;font-size:13px;color:var(--navy)">'.e($a->resident_name).'</div>'
                          .'<div class="td-muted">'.e($a->contact_number).'</div>';
                })
                ->addColumn('document_col', fn ($a) => '<span class="badge badge-navy" style="white-space:normal;line-height:1.4">'.e($a->document_type).'</span>')
                ->addColumn('date_col', function ($a) {
                    if ($a->pickup_date) {
                        return '<div style="font-size:12.5px;font-weight:600;color:#16a34a">'.
                               '<i class="fas fa-calendar-check" style="font-size:10px;margin-right:3px"></i>'.
                               \Carbon\Carbon::parse($a->pickup_date)->format('M d, Y').'</div>'.
                               '<div style="font-size:10.5px;color:#9ca3af;margin-top:1px">Ready for Pick-up</div>';
                    }
                    if ($a->preferred_date) {
                        return '<div style="font-size:12.5px;color:var(--text-muted)">'.
                               \Carbon\Carbon::parse($a->preferred_date)->format('M d, Y').'</div>'.
                               '<div style="font-size:10.5px;color:#9ca3af;margin-top:1px">Preferred</div>';
                    }
                    return '<span class="td-muted">—</span>';
                })
                ->addColumn('submitted_col', fn ($a) => '<span class="td-muted">'.$a->created_at->format('M d, Y').'</span>')
                ->addColumn('status_col', function ($a) {
                    $cls = match ($a->status) {
                        'Pending'    => 'badge-yellow',
                        'Confirmed'  => 'badge-navy',
                        'Processing' => 'badge-blue',
                        'Ready'      => 'badge-green',
                        'Released'   => 'badge-gray',
                        'Cancelled'  => 'badge-red',
                        default      => 'badge-gray',
                    };

                    return '<span class="badge '.$cls.'">'.e($a->status).'</span>';
                })
                ->addColumn('actions', function ($a) {
                    $convertUrl = route('appointments.convert', $a);

                    // ── Already released: show View Record link ──
                    if ($a->status === 'Released') {
                        $viewUrl = $a->document ? route('documents.show', $a->document) : null;
                        return $viewUrl
                            ? '<div style="display:flex;justify-content:flex-end">
                                   <a href="'.e($viewUrl).'" target="_blank"
                                      class="btn btn-secondary btn-sm"
                                      style="font-size:12px;padding:0 10px;height:30px;
                                             display:inline-flex;align-items:center;gap:5px">
                                       <i class="fas fa-file-lines"></i> View Record
                                   </a>
                               </div>'
                            : '';
                    }

                    // ── Cancelled: nothing ──
                    if ($a->status === 'Cancelled') {
                        return '';
                    }

                    // ── Pending / Processing / Ready: single Issue Document button ──
                    return '<div style="display:flex;justify-content:flex-end">
                                <button class="btn btn-sm apt-issue-btn"
                                        style="height:30px;padding:0 12px;font-size:12px;font-weight:600;
                                               background:var(--navy);color:#fff;border:1px solid var(--navy);
                                               border-radius:var(--radius-sm);cursor:pointer;white-space:nowrap;
                                               display:inline-flex;align-items:center;gap:5px"
                                        data-url="'.e($convertUrl).'"
                                        data-num="'.e($a->appointment_number).'"
                                        data-name="'.e($a->resident_name).'"
                                        data-type="'.e($a->document_type).'"
                                        data-purpose="'.e($a->purpose ?? '').'"
                                        data-date="'.($a->preferred_date ? $a->preferred_date->format('M d, Y') : '—').'"
                                        data-fee="'.e($a->document?->fee_paid ?? '').'"
                                        data-or="'.e($a->document?->or_number ?? '').'">
                                    <i class="fas fa-file-circle-check" style="font-size:11px"></i> Issue Document
                                </button>
                            </div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $s = $request->search['value'];
                        $query->where(fn ($q) => $q
                            ->where('resident_name', 'like', "%$s%")
                            ->orWhere('appointment_number', 'like', "%$s%")
                            ->orWhere('contact_number', 'like', "%$s%")
                            ->orWhere('document_type', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col', 'resident_col', 'document_col', 'date_col', 'submitted_col', 'status_col', 'actions'])
                ->make(true);
        }

        $statuses       = DocumentAppointment::$statuses;
        $documentTypes  = DocumentAppointment::$documentTypes;
        $businessTypes  = Business::distinct()->where('source', 'portal')->pluck('business_type')->sort()->values()->toArray();
        $incidentTypes  = BlotterCase::distinct()->where('source', 'portal')->pluck('incident_type')->sort()->values()->toArray();
        $blotterPending = BlotterCase::where('source', 'portal')->where('status', 'Pending')->count();

        return view('appointments.index', compact('statuses', 'documentTypes', 'businessTypes', 'incidentTypes', 'blotterPending'));
    }

    public function updateStatus(Request $request, DocumentAppointment $appointment)
    {
        $validated = $request->validate([
            'status'      => 'required|in:' . implode(',', DocumentAppointment::$statuses),
            'notes'       => 'nullable|string|max:500',
            'pickup_date' => 'nullable|date',
            'fee_paid'    => 'nullable|numeric|min:0',
            'or_number'   => 'nullable|string|max:100',
        ]);

        $old = $appointment->toArray();

        // All sync + log + email delegated to DocumentQueueService
        $appointment = app(DocumentQueueService::class)->advance(
            appointment: $appointment,
            newStatus:   $validated['status'],
            notes:       $validated['notes'] ?? null,
            pickupDate:  $validated['pickup_date'] ?? null,
            changedBy:   auth()->user()->name,
            feePaid:     isset($validated['fee_paid']) ? (float) $validated['fee_paid'] : null,
            orNumber:    $validated['or_number'] ?? null,
        );

        $this->logActivity('updated', $appointment, $old, $appointment->toArray());

        if ($request->expectsJson()) {
            // Return fresh counts so the client can sync stat cards exactly
            $counts = DocumentAppointment::selectRaw(
                "SUM(status='Pending') as pending,
                 SUM(status='Ready')   as ready,
                 SUM(status='Released') as released"
            )->first();

            return response()->json([
                'success' => true,
                'message' => "Status updated to {$appointment->status}.",
                'status'  => $appointment->status,
                'counts'  => [
                    'Pending'  => (int) $counts->pending,
                    'Ready'    => (int) $counts->ready,
                    'Released' => (int) $counts->released,
                ],
            ]);
        }

        return back()->with('success', "Appointment status updated to {$appointment->status}.");
    }

    public function portalPendingCount()
    {
        return response()->json([
            'documents' => DocumentAppointment::where('status', 'Pending')->where('source', 'portal')->count(),
            // Only count blotter cases that have been activated (not still sitting as Pending in Appointments)
            'blotter'   => BlotterCase::where('source', 'portal')
                ->where('status', '!=', 'Pending')
                ->whereNotIn('status', ['Settled', 'Closed'])
                ->count(),
            // Count portal business applications that are still pending (not yet issued)
            'business'  => Business::where('source', 'portal')
                ->whereIn('status', ['Pending', 'For Review'])
                ->count(),
        ]);
    }

    public function convertToDocument(Request $request, DocumentAppointment $appointment)
    {
        $existingDoc = Document::where('appointment_id', $appointment->id)->first();

        // Guard: already released — show the record instead
        if ($existingDoc && $existingDoc->status === 'Released') {
            return response()->json([
                'success'  => false,
                'message'  => 'This document has already been issued.',
                'view_url' => route('documents.show', $existingDoc),
            ], 422);
        }

        $validated = $request->validate([
            'fee_paid'  => 'nullable|numeric|min:0',
            'or_number' => 'nullable|string|max:100',
            'notes'     => 'nullable|string|max:500',
        ]);

        if ($existingDoc) {
            // Portal placeholder exists — upgrade it in-place
            $existingDoc->update([
                'fee_paid'    => $validated['fee_paid'] ?? 0,
                'or_number'   => $validated['or_number'] ?? null,
                'status'      => 'Released',
                'issued_by'   => auth()->id(),
                'released_at' => now(),
            ]);
            $document = $existingDoc->fresh();
            $this->logActivity('updated', $document);
        } else {
            // No pre-existing doc — create one fresh
            $document = Document::create([
                'doc_number'             => Document::generateDocNumber(),
                'appointment_id'         => $appointment->id,
                'source'                 => 'portal',
                'resident_name_portal'   => $appointment->resident_name,
                'requestor_name'         => $appointment->requestor_name,
                'requestor_relationship' => $appointment->requestor_relationship,
                'requestor_contact'      => $appointment->requestor_contact,
                'document_type'          => $appointment->document_type,
                'purpose'                => $appointment->purpose,
                'fee_paid'               => $validated['fee_paid'] ?? 0,
                'or_number'              => $validated['or_number'] ?? null,
                'status'                 => 'Released',
                'issued_by'              => auth()->id(),
                'released_at'            => now(),
            ]);
            $this->logActivity('created', $document);
        }

        // Release the appointment and write audit log
        $fromStatus = $appointment->status;

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status'    => $fromStatus,
            'to_status'      => 'Released',
            'changed_by'     => auth()->user()->name,
            'note'           => $validated['notes'] ?? ('Document '.$document->doc_number.' issued.'),
        ]);

        $appointment->update([
            'status'       => 'Released',
            'released_at'  => now(),
            'processed_by' => auth()->user()->name,
            'notes'        => $validated['notes'] ?? $appointment->notes,
        ]);

        $this->logActivity('updated', $appointment);

        // Send email notification to resident
        if ($appointment->email) {
            try {
                \Illuminate\Support\Facades\Mail::to($appointment->email)
                    ->queue(new \App\Mail\PortalStatusUpdated(
                        type:          'document',
                        requestNumber: $appointment->appointment_number,
                        residentName:  $appointment->resident_name,
                        newStatus:     'Released',
                        notes:         $validated['notes'] ?? null,
                        preferredDate: $appointment->preferred_date?->format('Y-m-d'),
                    ));
            } catch (\Exception $e) {
                logger()->warning('Issue document email failed: '.$e->getMessage());
            }
        }

        // Return fresh counts so stat cards stay current
        $counts = DocumentAppointment::selectRaw(
            "SUM(status='Pending') as pending,
             SUM(status='Ready')   as ready,
             SUM(status='Released') as released"
        )->first();

        return response()->json([
            'success'    => true,
            'message'    => "Document {$document->doc_number} issued successfully.",
            'doc_number' => $document->doc_number,
            'doc_id'     => $document->id,
            'view_url'   => route('documents.show', $document),
            'counts'     => [
                'Pending'  => (int) $counts->pending,
                'Ready'    => (int) $counts->ready,
                'Released' => (int) $counts->released,
            ],
        ]);
    }

    /* ─────────────────────────────────────────────────────────────────────
     |  BUSINESS PERMIT PORTAL APPOINTMENTS  (second table on same page)
     |────────────────────────────────────────────────────────────────────── */

    public function bizAppointments(Request $request)
    {
        $query = Business::where('source', 'portal')
            ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status))
            ->when($request->business_type, fn ($q) => $q->whereIn('business_type', (array) $request->business_type));

        return DataTables::of($query)
            ->addColumn('number_col', function ($b) {
                return '<span class="td-mono">'.e($b->permit_number).'</span>
                        <span class="badge badge-blue" style="font-size:10px;margin-left:4px">Portal</span>';
            })
            ->addColumn('owner_col', function ($b) {
                $contact = $b->owner_contact ? '<div class="td-muted">'.e($b->owner_contact).'</div>' : '';
                return '<div style="font-weight:600;font-size:13px;color:var(--navy)">'.e($b->owner_name).'</div>'.$contact;
            })
            ->addColumn('biz_col', function ($b) {
                return '<div style="font-weight:600;font-size:13px">'.e($b->business_name).'</div>
                        <div class="td-muted" style="font-size:11.5px">'.e($b->business_address).'</div>';
            })
            ->addColumn('type_col', fn ($b) => '<span class="badge badge-navy">'.e($b->business_type).'</span>')
            ->addColumn('appt_date_col', function ($b) {
                if (! $b->preferred_date) return '<span class="td-muted">—</span>';
                $date = \Carbon\Carbon::parse($b->preferred_date);
                $past = $date->isPast() && ! in_array($b->status, ['Active', 'Cancelled']);
                $style = $past ? 'color:#b45309;font-weight:600' : 'color:var(--text-muted)';
                return '<span style="'.$style.'">'.$date->format('M d, Y').'</span>';
            })
            ->addColumn('submitted_col', fn ($b) => '<span class="td-muted">'.$b->created_at->format('M d, Y').'</span>')
            ->addColumn('status_col', function ($b) {
                $cls = match ($b->status) {
                    'Active'     => 'badge-green',
                    'Pending'    => 'badge-yellow',
                    'For Review' => 'badge-blue',
                    'Cancelled'  => 'badge-red',
                    'Suspended'  => 'badge-yellow',
                    'Expired'    => 'badge-red',
                    default      => 'badge-gray',
                };
                return '<span class="badge '.$cls.'">'.e($b->status).'</span>';
            })
            ->addColumn('actions', function ($b) {
                $viewUrl      = route('businesses.show', $b);
                $issueUrl     = route('appointments.bizIssue', $b);
                $statusUrl    = route('appointments.bizStatus', $b);
                $apptDateFmt  = $b->preferred_date ? \Carbon\Carbon::parse($b->preferred_date)->format('M d, Y') : '—';

                if ($b->permit_date) {
                    return '<div style="display:flex;justify-content:flex-end">
                                <a href="'.$viewUrl.'" target="_blank"
                                   class="btn btn-secondary btn-sm"
                                   style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px">
                                    <i class="fas fa-stamp"></i> View Permit
                                </a>
                            </div>';
                }

                // Common data attributes for the status-update modal
                $dataAttrs = 'data-id="'.e($b->id).'"
                              data-num="'.e($b->permit_number).'"
                              data-biz="'.e($b->business_name).'"
                              data-owner="'.e($b->owner_name).'"
                              data-appt="'.e($apptDateFmt).'"
                              data-status="'.e($b->status).'"
                              data-status-url="'.e($statusUrl).'"
                              data-issue-url="'.e($issueUrl).'"';

                if ($b->status === 'Pending') {
                    return '<div style="display:flex;justify-content:flex-end;gap:6px">
                                <button class="btn btn-sm biz-status-btn"
                                        style="height:30px;padding:0 10px;font-size:12px;font-weight:600;
                                               background:#f0f4ff;color:#1d4ed8;border:1px solid #bfdbfe;
                                               border-radius:var(--radius-sm);cursor:pointer;white-space:nowrap"
                                        '.$dataAttrs.'>
                                    <i class="fas fa-pen-to-square" style="font-size:11px;margin-right:3px"></i>Update Status
                                </button>
                                <button class="btn btn-success btn-sm biz-issue-btn"
                                        style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                        '.$dataAttrs.'>
                                    <i class="fas fa-stamp"></i> Issue
                                </button>
                            </div>';
                }

                if ($b->status === 'For Review') {
                    return '<div style="display:flex;justify-content:flex-end;gap:6px">
                                <button class="btn btn-sm biz-status-btn"
                                        style="height:30px;padding:0 10px;font-size:12px;font-weight:600;
                                               background:#f0f4ff;color:#1d4ed8;border:1px solid #bfdbfe;
                                               border-radius:var(--radius-sm);cursor:pointer;white-space:nowrap"
                                        '.$dataAttrs.'>
                                    <i class="fas fa-pen-to-square" style="font-size:11px;margin-right:3px"></i>Update
                                </button>
                                <button class="btn btn-success btn-sm biz-issue-btn"
                                        style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                        '.$dataAttrs.'>
                                    <i class="fas fa-stamp"></i> Issue Permit
                                </button>
                            </div>';
                }

                return '';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $s = $request->search['value'];
                    $query->where(fn ($q) => $q
                        ->where('business_name', 'like', "%$s%")
                        ->orWhere('owner_name', 'like', "%$s%")
                        ->orWhere('permit_number', 'like', "%$s%")
                        ->orWhere('owner_contact', 'like', "%$s%"));
                }
            })
            ->rawColumns(['number_col', 'owner_col', 'biz_col', 'type_col', 'appt_date_col', 'submitted_col', 'status_col', 'actions'])
            ->make(true);
    }

    public function issueBizPermit(Request $request, Business $business)
    {
        // Guard: already issued
        if ($business->permit_date) {
            return response()->json([
                'success'  => false,
                'message'  => 'This business has already been issued a permit.',
                'view_url' => route('businesses.show', $business),
            ], 422);
        }

        $validated = $request->validate([
            'permit_date' => 'required|date',
            'expiry_date' => 'required|date|after:permit_date',
            'fee_paid'    => 'nullable|numeric|min:0',
            'or_number'   => 'nullable|string|max:100',
        ]);

        $old = $business->toArray();

        $business->update([
            'permit_date' => $validated['permit_date'],
            'expiry_date' => $validated['expiry_date'],
            'issued_by'   => auth()->id(),
            'status'      => 'Active',
        ]);

        $this->logActivity('updated', $business, $old, $business->fresh()->toArray());

        // Notify applicant if email is available
        if ($business->email) {
            try {
                Mail::to($business->email)->queue(new PortalStatusUpdated(
                    type:          'business',
                    requestNumber: $business->permit_number,
                    residentName:  $business->owner_name,
                    newStatus:     'Active',
                    notes:         'Your business permit has been issued. Permit valid until '
                                   .\Carbon\Carbon::parse($validated['expiry_date'])->format('F d, Y').'.',
                    preferredDate: $business->preferred_date?->format('Y-m-d'),
                ));
            } catch (\Exception $e) {
                logger()->warning('Business permit issued email failed: '.$e->getMessage());
            }
        }

        $bizPending = Business::where('source', 'portal')->whereIn('status', ['Pending', 'For Review'])->count();

        return response()->json([
            'success'     => true,
            'message'     => "Permit {$business->permit_number} issued successfully.",
            'permit_num'  => $business->permit_number,
            'view_url'    => route('businesses.show', $business),
            'biz_pending' => $bizPending,
        ]);
    }

    public function updateBizStatus(Request $request, Business $business)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,For Review,Active,Cancelled',
            'notes'  => 'nullable|string|max:500',
        ]);

        $old = $business->status;
        $business->update(['status' => $validated['status']]);

        $this->logActivity('updated', $business, ['status' => $old], ['status' => $validated['status']]);

        // Send email notification if available
        if ($business->email) {
            try {
                Mail::to($business->email)->queue(new PortalStatusUpdated(
                    type:          'business',
                    requestNumber: $business->permit_number,
                    residentName:  $business->owner_name,
                    newStatus:     $validated['status'],
                    notes:         $validated['notes'] ?? null,
                    preferredDate: $business->preferred_date?->format('Y-m-d'),
                ));
            } catch (\Exception $e) {
                logger()->warning('Business portal email failed: ' . $e->getMessage());
            }
        }

        $bizPending = Business::where('source', 'portal')->whereIn('status', ['Pending', 'For Review'])->count();

        return response()->json([
            'success'    => true,
            'message'    => "Status updated to {$validated['status']}.",
            'biz_pending' => $bizPending,
        ]);
    }

    /* ─────────────────────────────────────────────────────────────────────
     |  BLOTTER PORTAL APPOINTMENTS  (third table on same page)
     |────────────────────────────────────────────────────────────────────── */

    public function blotterAppointments(Request $request)
    {
        $query = BlotterCase::where('source', 'portal')
            ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status))
            ->when($request->incident_type, fn ($q) => $q->whereIn('incident_type', (array) $request->incident_type));

        return DataTables::of($query)
            ->addColumn('number_col', function ($c) {
                return '<span class="td-mono">'.e($c->case_number).'</span>
                        <span class="badge badge-blue" style="font-size:10px;margin-left:4px">Portal</span>';
            })
            ->addColumn('complainant_col', function ($c) {
                $contact = $c->complainant_contact
                    ? '<div class="td-muted">'.e($c->complainant_contact).'</div>' : '';
                return '<div style="font-weight:600;font-size:13px;color:var(--navy)">'.e($c->complainant_name).'</div>'.$contact;
            })
            ->addColumn('type_col', fn ($c) => '<span class="badge badge-navy">'.e($c->incident_type).'</span>')
            ->addColumn('incident_col', function ($c) {
                $date = $c->incident_date
                    ? \Carbon\Carbon::parse($c->incident_date)->format('M d, Y') : '—';
                return '<div style="font-size:13px">'.e($c->incident_location).'</div>
                        <div class="td-muted" style="font-size:11.5px">'.$date.'</div>';
            })
            ->addColumn('submitted_col', fn ($c) => '<span class="td-muted">'.$c->created_at->format('M d, Y').'</span>')
            ->addColumn('status_col', function ($c) {
                $cls = match ($c->status) {
                    'Pending'                      => 'badge-yellow',
                    'Active'                       => 'badge-red',
                    'Under Investigation'          => 'badge-yellow',
                    'Mediated'                     => 'badge-blue',
                    'Settled'                      => 'badge-green',
                    'Closed'                       => 'badge-gray',
                    'Referred to Higher Authority' => 'badge-orange',
                    default                        => 'badge-gray',
                };
                return '<span class="badge '.$cls.'">'.e($c->status).'</span>';
            })
            ->addColumn('actions', function ($c) {
                $viewUrl     = route('blotter.show', $c);
                $activateUrl = route('appointments.blotterActivate', $c);

                if ($c->status === 'Pending') {
                    return '<div style="display:flex;justify-content:flex-end">
                                <button class="btn btn-success btn-sm blotter-activate-btn"
                                        style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                        data-id="'.e($c->id).'"
                                        data-num="'.e($c->case_number).'"
                                        data-complainant="'.e($c->complainant_name).'"
                                        data-type="'.e($c->incident_type).'"
                                        data-date="'.($c->incident_date ? \Carbon\Carbon::parse($c->incident_date)->format('M d, Y') : '—').'"
                                        data-url="'.$activateUrl.'">
                                    <i class="fas fa-shield-halved"></i> Activate
                                </button>
                            </div>';
                }
                return '<div style="display:flex;justify-content:flex-end">
                            <a href="'.$viewUrl.'" target="_blank"
                               class="btn btn-secondary btn-sm"
                               style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px">
                                <i class="fas fa-shield-halved"></i> View Case
                            </a>
                        </div>';
            })
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $s = $request->search['value'];
                    $query->where(fn ($q) => $q
                        ->where('complainant_name', 'like', "%$s%")
                        ->orWhere('case_number', 'like', "%$s%")
                        ->orWhere('incident_type', 'like', "%$s%")
                        ->orWhere('incident_location', 'like', "%$s%"));
                }
            })
            ->rawColumns(['number_col', 'complainant_col', 'type_col', 'incident_col', 'submitted_col', 'status_col', 'actions'])
            ->make(true);
    }

    public function activateBlotter(Request $request, BlotterCase $blotterCase)
    {
        if ($blotterCase->status !== 'Pending') {
            return response()->json([
                'success'  => false,
                'message'  => 'This blotter case has already been activated.',
                'view_url' => route('blotter.show', $blotterCase),
            ], 422);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $old = $blotterCase->toArray();

        $blotterCase->update([
            'status'    => 'Active',
            'filed_by'  => auth()->id(),
        ]);

        if ($validated['notes'] ?? null) {
            $blotterCase->update(['resolution_notes' => $validated['notes']]);
        }

        $this->logActivity('updated', $blotterCase, $old, $blotterCase->fresh()->toArray());

        // Email notification
        if ($blotterCase->email) {
            try {
                Mail::to($blotterCase->email)->queue(new PortalStatusUpdated(
                    type:          'blotter',
                    requestNumber: $blotterCase->case_number,
                    residentName:  $blotterCase->complainant_name,
                    newStatus:     'Active',
                    notes:         $validated['notes'] ?? 'Your blotter report has been received and filed as an official case.',
                    preferredDate: null,
                ));
            } catch (\Exception $e) {
                logger()->warning('Blotter activation email failed: '.$e->getMessage());
            }
        }

        $blotterPending = BlotterCase::where('source', 'portal')->where('status', 'Pending')->count();

        return response()->json([
            'success'         => true,
            'message'         => "Case {$blotterCase->case_number} activated successfully.",
            'case_number'     => $blotterCase->case_number,
            'view_url'        => route('blotter.show', $blotterCase),
            'blotter_pending' => $blotterPending,
        ]);
    }

    public function updateBlotterStatus(Request $request, BlotterCase $blotterCase)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Active,Under Investigation,Mediated,Settled,Closed,Referred to Higher Authority',
            'notes'  => 'nullable|string|max:500',
        ]);

        $old = $blotterCase->status;
        $blotterCase->update(['status' => $validated['status']]);

        if ($validated['notes'] ?? null) {
            $blotterCase->update(['resolution_notes' => $validated['notes']]);
        }

        $this->logActivity('updated', $blotterCase, ['status' => $old], ['status' => $validated['status']]);

        // Send email notification if available
        if ($blotterCase->email) {
            try {
                Mail::to($blotterCase->email)->queue(new PortalStatusUpdated(
                    type:          'blotter',
                    requestNumber: $blotterCase->case_number,
                    residentName:  $blotterCase->complainant_name,
                    newStatus:     $validated['status'],
                    notes:         $validated['notes'] ?? null,
                    preferredDate: null,
                ));
            } catch (\Exception $e) {
                logger()->warning('Blotter portal status email failed: ' . $e->getMessage());
            }
        }

        $blotterPending = BlotterCase::where('source', 'portal')->where('status', 'Pending')->count();

        return response()->json([
            'success'         => true,
            'message'         => "Status updated to {$validated['status']}.",
            'blotter_pending' => $blotterPending,
        ]);
    }

    public function destroy(Request $request, DocumentAppointment $appointment)
    {
        $num = $appointment->appointment_number;
        $snap = $appointment->toArray();
        $appointment->delete();
        $this->logActivity('deleted', $appointment, $snap);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Appointment {$num} deleted."]);
        }

        return back()->with('success', "Appointment {$num} deleted.");
    }
}
