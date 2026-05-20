<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\AppointmentStatusLog;
use App\Models\BlotterCase;
use App\Models\Business;
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
            $query = DocumentAppointment::select('document_appointments.*')
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
                    $deleteUrl = route('appointments.destroy', $a);

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
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
