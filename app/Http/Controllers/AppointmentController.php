<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\AppointmentStatusLog;
use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\DocumentAppointment;
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
                ->addColumn('date_col', fn ($a) => '<span class="td-muted">'.($a->preferred_date ? \Carbon\Carbon::parse($a->preferred_date)->format('M d, Y') : '—').'</span>')
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
                    $deleteUrl  = route('appointments.destroy', $a);
                    $convertUrl = route('appointments.convert', $a);

                    // Green "View Document" — Ready/Released but not yet issued (opens Issue modal)
                    // Grey  "View Document" — already issued (links directly to the document)
                    $viewBtn = '';
                    if ($a->document) {
                        $viewUrl = route('documents.show', $a->document);
                        $viewBtn = '<a href="'.$viewUrl.'" target="_blank"
                                      class="btn btn-secondary btn-sm apt-viewdoc-btn"
                                      style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                      data-tippy-content="View Document: '.e($a->document->doc_number).'">
                                        <i class="fas fa-file-lines"></i> View Document
                                    </a>';
                    } elseif (in_array($a->status, ['Ready', 'Released'])) {
                        $viewBtn = '
                            <button class="btn btn-success btn-sm apt-viewdoc-btn apt-convert-btn"
                                    style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                    data-tippy-content="View Document"
                                    data-id="'.e($a->id).'"
                                    data-num="'.e($a->appointment_number).'"
                                    data-name="'.e($a->resident_name).'"
                                    data-type="'.e($a->document_type).'"
                                    data-purpose="'.e($a->purpose ?? '').'"
                                    data-url="'.$convertUrl.'">
                                <i class="fas fa-file-lines"></i> View Document
                            </button>';
                    }

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            '.$viewBtn.'
                            <button class="btn btn-primary btn-sm btn-icon apt-status-btn"
                                    title="Update Status"
                                    data-id="'.$a->id.'"
                                    data-num="'.e($a->appointment_number).'"
                                    data-status="'.e($a->status).'"
                                    data-notes="'.e($a->notes ?? '').'">
                                <i class="fas fa-rotate"></i>
                            </button>
                            <form method="POST" action="'.$deleteUrl.'"
                                  data-confirm="Delete appointment '.e($a->appointment_number).'? This cannot be undone."
                                  data-confirm-title="Delete Appointment"
                                  data-confirm-ok="Delete">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
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

        $statuses      = DocumentAppointment::$statuses;
        $documentTypes = DocumentAppointment::$documentTypes;
        $businessTypes = Business::distinct()->where('source', 'portal')->pluck('business_type')->sort()->values()->toArray();

        return view('appointments.index', compact('statuses', 'documentTypes', 'businessTypes'));
    }

    public function updateStatus(Request $request, DocumentAppointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', DocumentAppointment::$statuses),
            'notes'  => 'nullable|string|max:500',
        ]);

        $validated['processed_by'] = auth()->user()->name;

        if ($validated['status'] === 'Released') {
            $validated['released_at'] = now();
        }

        $old = $appointment->toArray();

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status'    => $appointment->status,
            'to_status'      => $validated['status'],
            'changed_by'     => auth()->user()->name,
            'note'           => $validated['notes'] ?? null,
        ]);

        $appointment->update($validated);

        $this->logActivity('updated', $appointment, $old, $appointment->fresh()->toArray());

        // Send email notification if the appointment has an email address
        if ($appointment->email) {
            try {
                Mail::to($appointment->email)->send(new PortalStatusUpdated(
                    type:          'document',
                    requestNumber: $appointment->appointment_number,
                    residentName:  $appointment->resident_name,
                    newStatus:     $validated['status'],
                    notes:         $validated['notes'] ?? null,
                    preferredDate: $appointment->preferred_date?->format('Y-m-d'),
                ));
            } catch (\Exception $e) {
                // Mail failure is non-fatal; log silently
                logger()->warning('Portal email failed: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson()) {
            // Return fresh counts so the client can sync stat cards exactly
            $counts = DocumentAppointment::selectRaw(
                "SUM(status='Pending') as pending,
                 SUM(status='Ready')   as ready,
                 SUM(status='Released') as released"
            )->first();

            return response()->json([
                'success' => true,
                'message' => "Status updated to {$validated['status']}.",
                'status'  => $validated['status'],
                'counts'  => [
                    'Pending'  => (int) $counts->pending,
                    'Ready'    => (int) $counts->ready,
                    'Released' => (int) $counts->released,
                ],
            ]);
        }

        return back()->with('success', "Appointment status updated to {$validated['status']}.");
    }

    public function portalPendingCount()
    {
        return response()->json([
            'documents' => DocumentAppointment::where('status', 'Pending')->where('source', 'portal')->count(),
            'blotter'   => BlotterCase::where('source', 'portal')->whereNotIn('status', ['Settled', 'Closed'])->count(),
            'business'  => Business::where('source', 'portal')->whereIn('status', ['Pending', 'For Review'])->count(),
        ]);
    }

    public function convertToDocument(Request $request, DocumentAppointment $appointment)
    {
        // Already converted — return the existing doc
        if ($appointment->document()->exists()) {
            return response()->json([
                'success'  => false,
                'message'  => 'This appointment has already been issued as a document record.',
                'view_url' => route('documents.show', $appointment->document),
            ], 422);
        }

        $validated = $request->validate([
            'fee_paid'  => 'nullable|numeric|min:0',
            'or_number' => 'nullable|string|max:100',
        ]);

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

        // Auto-release the appointment if not already released
        if ($appointment->status !== 'Released') {
            $old = $appointment->status;

            AppointmentStatusLog::create([
                'appointment_id' => $appointment->id,
                'from_status'    => $old,
                'to_status'      => 'Released',
                'changed_by'     => auth()->user()->name,
                'note'           => 'Auto-released when document '.$document->doc_number.' was issued.',
            ]);

            $appointment->update([
                'status'       => 'Released',
                'released_at'  => now(),
                'processed_by' => auth()->user()->name,
            ]);

            $this->logActivity('updated', $appointment);
        }

        return response()->json([
            'success'    => true,
            'message'    => "Document {$document->doc_number} issued successfully.",
            'doc_number' => $document->doc_number,
            'doc_id'     => $document->id,
            'view_url'   => route('documents.show', $document),
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
                $viewUrl   = route('businesses.show', $b);
                $issueUrl  = route('appointments.bizIssue', $b);
                $deleteUrl = route('businesses.destroy', $b);

                // Green = not yet issued (appointment pending), Grey = already a real permit
                if ($b->permit_date) {
                    $issueBtn = '<a href="'.$viewUrl.'" target="_blank"
                                   class="btn btn-secondary btn-sm biz-viewpermit-btn"
                                   style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                   data-tippy-content="View Permit: '.e($b->permit_number).'">
                                    <i class="fas fa-file-certificate"></i> View Permit
                                </a>';
                } elseif (in_array($b->status, ['For Review', 'Pending'])) {
                    $issueBtn = '<button class="btn btn-success btn-sm biz-issue-btn"
                                         style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                         data-tippy-content="Issue Business Permit"
                                         data-id="'.e($b->id).'"
                                         data-num="'.e($b->permit_number).'"
                                         data-biz="'.e($b->business_name).'"
                                         data-owner="'.e($b->owner_name).'"
                                         data-appt="'.($b->preferred_date ? \Carbon\Carbon::parse($b->preferred_date)->format('M d, Y') : '—').'"
                                         data-url="'.$issueUrl.'">
                                    <i class="fas fa-file-certificate"></i> Issue Permit
                                </button>';
                } else {
                    $issueBtn = '';
                }

                return '
                    <div style="display:flex;justify-content:flex-end;gap:6px">
                        '.$issueBtn.'
                        <button class="btn btn-primary btn-sm btn-icon biz-apt-status-btn"
                                data-tippy-content="Update Status"
                                data-id="'.$b->id.'"
                                data-num="'.e($b->permit_number).'"
                                data-biz="'.e($b->business_name).'"
                                data-status="'.e($b->status).'">
                            <i class="fas fa-rotate"></i>
                        </button>
                        <form method="POST" action="'.$deleteUrl.'"
                              data-confirm="Delete appointment for '.e($b->business_name).'? This cannot be undone."
                              data-confirm-title="Delete Business Appointment"
                              data-confirm-ok="Delete">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>';
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
                Mail::to($business->email)->send(new PortalStatusUpdated(
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
                Mail::to($business->email)->send(new PortalStatusUpdated(
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
