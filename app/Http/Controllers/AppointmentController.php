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

                    // View Document button — shown for Ready/Released appointments
                    // If already converted: links directly to the document record
                    // If not yet converted: opens the Issue modal to create it first
                    $viewBtn = '';
                    if ($a->document) {
                        $viewUrl = route('documents.show', $a->document);
                        $viewBtn = '<a href="'.$viewUrl.'" target="_blank"
                                      class="btn btn-secondary btn-sm"
                                      style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                      title="View Document: '.e($a->document->doc_number).'">
                                        <i class="fas fa-file-lines"></i> View Document
                                    </a>';
                    } elseif (in_array($a->status, ['Ready', 'Released'])) {
                        $viewBtn = '
                            <button class="btn btn-secondary btn-sm apt-convert-btn"
                                    style="font-size:12px;padding:0 10px;height:30px;display:inline-flex;align-items:center;gap:5px"
                                    title="View Document"
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

        return view('appointments.index', compact('statuses', 'documentTypes'));
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
