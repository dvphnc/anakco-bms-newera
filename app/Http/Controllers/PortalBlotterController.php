<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\BlotterRequest;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class PortalBlotterController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BlotterRequest::select('blotter_requests.*')
                ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status));

            return DataTables::of($query)
                ->addColumn('number_col', fn ($r) => '<span class="td-mono">'.e($r->request_number).'</span>')
                ->addColumn('complainant_col', function ($r) {
                    return '<div style="font-weight:600;font-size:13px;color:var(--navy)">'.e($r->complainant_name).'</div>'
                          .'<div class="td-muted">'.e($r->contact_number).'</div>';
                })
                ->addColumn('incident_col', function ($r) {
                    $date = $r->incident_date ? $r->incident_date->format('M d, Y') : '—';
                    return '<div style="font-weight:600;font-size:13px">'.e($r->incident_type).'</div>'
                          .'<div class="td-muted">'.$date.'</div>';
                })
                ->addColumn('submitted_col', fn ($r) => '<span class="td-muted">'.$r->created_at->format('M d, Y').'</span>')
                ->addColumn('status_col', function ($r) {
                    $cls = match ($r->status) {
                        'Pending'      => 'badge-yellow',
                        'Under Review' => 'badge-blue',
                        'For Mediation'=> 'badge-navy',
                        'Resolved'     => 'badge-green',
                        'Dismissed'    => 'badge-gray',
                        'Cancelled'    => 'badge-red',
                        default        => 'badge-gray',
                    };
                    return '<span class="badge '.$cls.'">'.e($r->status).'</span>';
                })
                ->addColumn('actions', function ($r) {
                    $deleteUrl = route('portal-blotter.destroy', $r);
                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <button class="btn btn-primary btn-sm btn-icon pbl-status-btn"
                                    title="Update Status"
                                    data-id="'.$r->id.'"
                                    data-num="'.e($r->request_number).'"
                                    data-status="'.e($r->status).'"
                                    data-notes="'.e($r->notes ?? '').'">
                                <i class="fas fa-rotate"></i>
                            </button>
                            <form method="POST" action="'.$deleteUrl.'"
                                  data-confirm="Delete blotter request '.e($r->request_number).'? This cannot be undone."
                                  data-confirm-title="Delete Request"
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
                            ->where('complainant_name', 'like', "%$s%")
                            ->orWhere('request_number', 'like', "%$s%")
                            ->orWhere('incident_type', 'like', "%$s%")
                            ->orWhere('respondent_name', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col', 'complainant_col', 'incident_col', 'submitted_col', 'status_col', 'actions'])
                ->make(true);
        }

        $statuses = BlotterRequest::$statuses;
        return view('portal-blotter.index', compact('statuses'));
    }

    public function updateStatus(Request $request, BlotterRequest $blotterRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', BlotterRequest::$statuses),
            'notes'  => 'nullable|string|max:500',
        ]);

        $validated['processed_by'] = auth()->user()->name;
        $old = $blotterRequest->toArray();
        $blotterRequest->update($validated);

        $this->logActivity('updated', $blotterRequest, $old, $blotterRequest->fresh()->toArray());

        // Send email notification if email is on record
        if ($blotterRequest->email) {
            try {
                Mail::to($blotterRequest->email)->send(new PortalStatusUpdated(
                    type:          'blotter',
                    requestNumber: $blotterRequest->request_number,
                    residentName:  $blotterRequest->complainant_name,
                    newStatus:     $validated['status'],
                    notes:         $validated['notes'] ?? null,
                ));
            } catch (\Exception $e) {
                logger()->warning('Portal blotter email failed: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status updated to {$validated['status']}.",
                'status'  => $validated['status'],
            ]);
        }

        return back()->with('success', "Blotter request status updated to {$validated['status']}.");
    }

    public function destroy(Request $request, BlotterRequest $blotterRequest)
    {
        $num  = $blotterRequest->request_number;
        $snap = $blotterRequest->toArray();
        $blotterRequest->delete();
        $this->logActivity('deleted', $blotterRequest, $snap);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Blotter request {$num} deleted."]);
        }

        return back()->with('success', "Blotter request {$num} deleted.");
    }
}
