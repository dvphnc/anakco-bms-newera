<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\BusinessPermitRequest;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class PortalBusinessController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BusinessPermitRequest::select('business_permit_requests.*')
                ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status));

            return DataTables::of($query)
                ->addColumn('number_col', fn ($r) => '<span class="td-mono">'.e($r->request_number).'</span>')
                ->addColumn('owner_col', function ($r) {
                    return '<div style="font-weight:600;font-size:13px;color:var(--navy)">'.e($r->owner_name).'</div>'
                          .'<div class="td-muted">'.e($r->contact_number).'</div>';
                })
                ->addColumn('business_col', function ($r) {
                    return '<div style="font-weight:600;font-size:13px">'.e($r->business_name).'</div>'
                          .'<div class="td-muted">'.e($r->business_type).'</div>';
                })
                ->addColumn('submitted_col', fn ($r) => '<span class="td-muted">'.$r->created_at->format('M d, Y').'</span>')
                ->addColumn('status_col', function ($r) {
                    $cls = match ($r->status) {
                        'Pending'       => 'badge-yellow',
                        'Under Review'  => 'badge-blue',
                        'For Inspection'=> 'badge-navy',
                        'Approved'      => 'badge-green',
                        'Rejected'      => 'badge-red',
                        'Cancelled'     => 'badge-gray',
                        default         => 'badge-gray',
                    };
                    return '<span class="badge '.$cls.'">'.e($r->status).'</span>';
                })
                ->addColumn('actions', function ($r) {
                    $deleteUrl = route('portal-business.destroy', $r);
                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <button class="btn btn-primary btn-sm btn-icon pbiz-status-btn"
                                    title="Update Status"
                                    data-id="'.$r->id.'"
                                    data-num="'.e($r->request_number).'"
                                    data-status="'.e($r->status).'"
                                    data-notes="'.e($r->notes ?? '').'">
                                <i class="fas fa-rotate"></i>
                            </button>
                            <form method="POST" action="'.$deleteUrl.'"
                                  data-confirm="Delete business request '.e($r->request_number).'? This cannot be undone."
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
                            ->where('owner_name', 'like', "%$s%")
                            ->orWhere('request_number', 'like', "%$s%")
                            ->orWhere('business_name', 'like', "%$s%")
                            ->orWhere('business_type', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col', 'owner_col', 'business_col', 'submitted_col', 'status_col', 'actions'])
                ->make(true);
        }

        $statuses = BusinessPermitRequest::$statuses;
        return view('portal-business.index', compact('statuses'));
    }

    public function updateStatus(Request $request, BusinessPermitRequest $businessPermitRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', BusinessPermitRequest::$statuses),
            'notes'  => 'nullable|string|max:500',
        ]);

        $validated['processed_by'] = auth()->user()->name;
        $old = $businessPermitRequest->toArray();
        $businessPermitRequest->update($validated);

        $this->logActivity('updated', $businessPermitRequest, $old, $businessPermitRequest->fresh()->toArray());

        if ($businessPermitRequest->email) {
            try {
                Mail::to($businessPermitRequest->email)->send(new PortalStatusUpdated(
                    type:          'business',
                    requestNumber: $businessPermitRequest->request_number,
                    residentName:  $businessPermitRequest->owner_name,
                    newStatus:     $validated['status'],
                    notes:         $validated['notes'] ?? null,
                ));
            } catch (\Exception $e) {
                logger()->warning('Portal business email failed: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status updated to {$validated['status']}.",
                'status'  => $validated['status'],
            ]);
        }

        return back()->with('success', "Business request status updated to {$validated['status']}.");
    }

    public function destroy(Request $request, BusinessPermitRequest $businessPermitRequest)
    {
        $num  = $businessPermitRequest->request_number;
        $snap = $businessPermitRequest->toArray();
        $businessPermitRequest->delete();
        $this->logActivity('deleted', $businessPermitRequest, $snap);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Business request {$num} deleted."]);
        }

        return back()->with('success', "Business request {$num} deleted.");
    }
}
