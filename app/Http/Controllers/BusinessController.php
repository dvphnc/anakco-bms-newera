<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\Business;
use App\Models\Resident;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class BusinessController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Business::select('businesses.*')
                ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status))
                ->when($request->business_type, fn ($q) => $q->whereIn('business_type', (array) $request->business_type))
                ->when($request->expiry_filter, function ($q) use ($request) {
                    if ($request->expiry_filter === 'expired') {
                        $q->where('expiry_date', '<', now())->where('status', 'Active');
                    } elseif ($request->expiry_filter === 'expiring_soon') {
                        $q->whereBetween('expiry_date', [now(), now()->addDays(30)])->where('status', 'Active');
                    } elseif ($request->expiry_filter === 'valid') {
                        $q->where('expiry_date', '>', now()->addDays(30))->where('status', 'Active');
                    }
                });

            return DataTables::of($query)
                ->addColumn('number_col', function ($b) {
                    $badge = $b->source === 'portal'
                        ? ' <span class="badge badge-blue" style="font-size:10px;margin-left:4px;vertical-align:middle">Portal</span>'
                        : '';
                    return '<span class="td-mono">'.e($b->permit_number).'</span>'.$badge;
                })
                ->addColumn('name_col', function ($b) {
                    $expiry = $b->expiry_date ? Carbon::parse($b->expiry_date) : null;
                    $tag = '';
                    if ($expiry && $b->status === 'Active') {
                        if ($expiry->isPast()) {
                            $tag = '<span style="display:inline-block;font-size:9px;font-weight:700;background:#fee2e2;color:#991b1b;border-radius:4px;padding:1px 6px;margin-left:6px;vertical-align:middle">OVERDUE</span>';
                        } elseif ($expiry->diffInDays(now()) <= 30 && $expiry->isFuture()) {
                            $tag = '<span style="display:inline-block;font-size:9px;font-weight:700;background:#fef3c7;color:#92400e;border-radius:4px;padding:1px 6px;margin-left:6px;vertical-align:middle">EXPIRING SOON</span>';
                        }
                    }

                    return '<div style="font-weight:600;font-size:13.5px">'.e($b->business_name).$tag.'</div>
                            <div class="td-muted" style="font-size:11.5px">'.e($b->business_address).'</div>';
                })
                ->addColumn('type_col', fn ($b) => '<span class="badge badge-navy">'.e($b->business_type).'</span>')
                ->addColumn('owner_col', function ($b) {
                    $contact = $b->owner_contact ? '<div class="td-muted">'.e($b->owner_contact).'</div>' : '';

                    return '<div style="font-size:13px">'.e($b->owner_name).'</div>'.$contact;
                })
                ->addColumn('permit_date_col', function ($b) {
                    if ($b->permit_date) {
                        return '<span class="td-muted">'.Carbon::parse($b->permit_date)->format('M d, Y').'</span>';
                    }
                    if ($b->preferred_date && $b->source === 'portal') {
                        return '<span class="td-muted" style="color:#1e40af"><i class="fas fa-calendar-check" style="font-size:10px"></i> '.Carbon::parse($b->preferred_date)->format('M d, Y').'</span>
                                <div style="font-size:10px;color:#9ca3af">Appt. Date</div>';
                    }
                    return '<span class="td-muted">—</span>';
                })
                ->addColumn('expiry_col', function ($b) {
                    if (! $b->expiry_date) {
                        return '<span class="td-muted">—</span>';
                    }
                    $expiry = Carbon::parse($b->expiry_date);
                    $now = now();

                    if ($b->status !== 'Active') {
                        return '<span class="td-muted">'.$expiry->format('M d, Y').'</span>';
                    }

                    if ($expiry->isPast()) {
                        $daysAgo = (int) floor($now->floatDiffInDays($expiry));

                        return '<div>
                            <span style="color:var(--crimson);font-weight:700;font-size:12.5px">'.$expiry->format('M d, Y').'</span>
                            <div style="font-size:10.5px;color:var(--crimson);margin-top:1px"><i class="fas fa-triangle-exclamation"></i> '.$daysAgo.' day'.($daysAgo != 1 ? 's' : '').' overdue</div>
                        </div>';
                    } elseif ($expiry->diffInDays($now) <= 30) {
                        $daysLeft = (int) ceil($now->floatDiffInDays($expiry));

                        return '<div>
                            <span style="color:#b45309;font-weight:600;font-size:12.5px">'.$expiry->format('M d, Y').'</span>
                            <div style="font-size:10.5px;color:#b45309;margin-top:1px"><i class="fas fa-clock"></i> '.$daysLeft.' day'.($daysLeft != 1 ? 's' : '').' left</div>
                        </div>';
                    } else {
                        $daysLeft = (int) ceil($now->floatDiffInDays($expiry));

                        return '<div>
                            <span style="color:var(--text-muted);font-size:12.5px">'.$expiry->format('M d, Y').'</span>
                            <div style="font-size:10.5px;color:#16a34a;margin-top:1px"><i class="fas fa-circle-check"></i> '.$daysLeft.' days left</div>
                        </div>';
                    }
                })
                ->addColumn('status_col', function ($b) {
                    $cls = match ($b->status) {
                        'Active'     => 'badge-green',
                        'Expired'    => 'badge-red',
                        'Suspended'  => 'badge-yellow',
                        'Cancelled'  => 'badge-gray',
                        'Pending'    => 'badge-yellow',
                        'For Review' => 'badge-blue',
                        default      => 'badge-gray',
                    };
                    return '<span class="badge '.$cls.'">'.$b->status.'</span>';
                })
                ->addColumn('actions', function ($b) {
                    $show   = route('businesses.show', $b);
                    $edit   = route('businesses.edit', $b);
                    $delete = route('businesses.destroy', $b);

                    $statusBtn = '';
                    if (in_array($b->status, ['Pending', 'For Review']) || $b->source === 'portal') {
                        $statusBtn = '<button class="btn btn-primary btn-sm btn-icon biz-status-btn"
                                    title="Update Status"
                                    data-id="'.$b->id.'"
                                    data-num="'.e($b->permit_number).'"
                                    data-status="'.e($b->status).'"
                                    data-notes=""
                                    data-email="'.e($b->email ?? '').'"
                                    data-name="'.e($b->owner_name).'">
                                <i class="fas fa-rotate"></i>
                            </button>';
                    }

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="'.$show.'" class="btn btn-secondary btn-sm btn-icon biz-qv-btn" title="Quick View" data-url="'.$show.'"><i class="fas fa-eye"></i></a>
                            '.$statusBtn.'
                            <a href="'.$edit.'" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="'.$delete.'"
                                  data-confirm="Delete permit '.e($b->permit_number).'? This cannot be undone."
                                  data-confirm-title="Delete Business Permit"
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
                            ->orWhere('business_address', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col', 'name_col', 'type_col', 'owner_col', 'permit_date_col', 'expiry_col', 'status_col', 'actions'])
                ->make(true);
        }

        $businessTypes = ['Sari-Sari Store', 'Restaurant / Carinderia', 'Salon / Barbershop', 'Repair Shop', 'Pharmacy / Drugstore', 'Laundry', 'Printing / Photocopy', 'Retail Store', 'Online Selling / E-commerce', 'Other'];
        $summaryCounts = [
            'Active' => Business::where('status', 'Active')->count(),
            'Pending' => Business::whereIn('status', ['Pending', 'For Review'])->count(),
            'Expired' => Business::where('status', 'Expired')->count(),
            'Suspended' => Business::where('status', 'Suspended')->count(),
            'Cancelled' => Business::where('status', 'Cancelled')->count(),
            'ExpiringSoon' => Business::where('status', 'Active')
                ->whereBetween('expiry_date', [now(), now()->addDays(30)])
                ->count(),
            'Overdue' => Business::where('status', 'Active')
                ->where('expiry_date', '<', now())
                ->count(),
        ];

        $expiringBusinesses = Business::where('status', 'Active')
            ->whereBetween('expiry_date', [now(), now()->addDays(30)])
            ->orderBy('expiry_date')
            ->get(['business_name', 'expiry_date']);

        $overdueBusinesses = Business::where('status', 'Active')
            ->where('expiry_date', '<', now())
            ->orderBy('expiry_date')
            ->get(['business_name', 'expiry_date']);

        return view('businesses.businesses-index', compact('businessTypes', 'summaryCounts', 'expiringBusinesses', 'overdueBusinesses'));
    }

    public function create()
    {
        $residents = Resident::where('residency_status', 'Active')->orderBy('last_name')->get();
        $businessTypes = ['Sari-Sari Store', 'Restaurant / Carinderia', 'Salon / Barbershop', 'Repair Shop', 'Pharmacy / Drugstore', 'Laundry', 'Printing / Photocopy', 'Retail Store', 'Online Selling / E-commerce', 'Other'];

        return view('businesses.businesses-create', compact('residents', 'businessTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string',
            'business_address' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_contact' => 'nullable|string|max:20',
            'owner_resident_id' => 'nullable|exists:residents,id',
            'permit_date' => 'required|date',
            'expiry_date' => 'required|date|after:permit_date',
            'status' => 'required|in:Active,Expired,Suspended,Cancelled',
            'remarks' => 'nullable|string',
        ]);
        $validated['permit_number'] = Business::generatePermitNumber();
        $validated['issued_by'] = auth()->id();
        $record = Business::create($validated);
        $this->logActivity('created', $record);

        return redirect()->route('businesses.index')->with('success', 'Business permit issued successfully.');
    }

    public function show(Request $request, Business $business)
    {
        $business->load(['ownerResident', 'issuedBy']);

        if ($request->wantsJson()) {
            $expiry  = $business->expiry_date ? Carbon::parse($business->expiry_date) : null;
            $expStat = 'valid';
            $expDays = null;
            if ($expiry && $business->status === 'Active') {
                if ($expiry->isPast()) {
                    $expStat = 'overdue';
                    $expDays = (int) floor(now()->floatDiffInDays($expiry));
                } elseif ($expiry->diffInDays(now()) <= 30) {
                    $expStat = 'expiring_soon';
                    $expDays = (int) ceil(now()->floatDiffInDays($expiry));
                }
            }

            return response()->json([
                'permit_number'    => $business->permit_number,
                'business_name'    => $business->business_name,
                'business_type'    => $business->business_type,
                'business_address' => $business->business_address,
                'owner_name'       => $business->owner_name,
                'owner_contact'    => $business->owner_contact ?? '—',
                'permit_date'      => $business->permit_date ? Carbon::parse($business->permit_date)->format('M d, Y') : '—',
                'expiry_date'      => $expiry ? $expiry->format('M d, Y') : '—',
                'expiry_status'    => $expStat,
                'expiry_days'      => $expDays,
                'status'           => $business->status,
                'remarks'          => $business->remarks ?? '—',
                'issued_by'        => $business->issuedBy?->name ?? '—',
                'show_url'         => route('businesses.show', $business),
                'edit_url'         => route('businesses.edit', $business),
            ]);
        }

        return view('businesses.businesses-show', compact('business'));
    }

    public function edit(Business $business)
    {
        $residents = Resident::where('residency_status', 'Active')->orderBy('last_name')->get();
        $businessTypes = ['Sari-Sari Store', 'Restaurant / Carinderia', 'Salon / Barbershop', 'Repair Shop', 'Pharmacy / Drugstore', 'Laundry', 'Printing / Photocopy', 'Retail Store', 'Online Selling / E-commerce', 'Other'];

        return view('businesses.businesses-edit', compact('business', 'residents', 'businessTypes'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string',
            'business_address' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_contact' => 'nullable|string|max:20',
            'owner_resident_id' => 'nullable|exists:residents,id',
            'permit_date' => 'required|date',
            'expiry_date' => 'required|date|after:permit_date',
            'status' => 'required|in:Active,Expired,Suspended,Cancelled',
            'remarks' => 'nullable|string',
        ]);
        $oldData = $business->getOriginal();
        $business->update($validated);
        $this->logActivity('updated', $business, $oldData, $business->fresh()->toArray());

        return redirect()->route('businesses.index')->with('success', 'Business permit updated successfully.');
    }

    /**
     * Axios PATCH — quick status update for portal-sourced business applications.
     */
    public function quickStatus(Request $request, Business $business)
    {
        $validated = $request->validate([
            'status' => 'required|in:Active,Expired,Suspended,Cancelled,Pending,For Review',
            'notes'  => 'nullable|string|max:500',
        ]);

        if (in_array($validated['status'], ['Active']) && ! $business->permit_date) {
            $validated['permit_date'] = now()->toDateString();
            $validated['expiry_date'] = now()->addYear()->toDateString();
        }

        $old = $business->getOriginal();
        $business->update($validated);
        $this->logActivity('updated', $business, $old, $business->fresh()->toArray());

        // Email notification for portal-sourced business applications
        if ($business->source === 'portal' && $business->email) {
            try {
                Mail::to($business->email)->send(new PortalStatusUpdated(
                    type:          'business',
                    requestNumber: $business->permit_number,
                    residentName:  $business->owner_name,
                    newStatus:     $business->status,
                    notes:         $validated['notes'] ?? null,
                ));
            } catch (\Exception $e) {
                logger()->warning('Portal business email failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Business permit status updated to {$business->status}.",
            'status'  => $business->status,
        ]);
    }

    public function destroy(Request $request, Business $business)
    {
        $num = $business->permit_number;
        $this->logActivity('deleted', $business);
        $business->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Permit {$num} deleted."]);
        }

        return redirect()->route('businesses.index')->with('success', 'Business permit deleted successfully.');
    }
}
