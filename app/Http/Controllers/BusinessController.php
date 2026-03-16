<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Resident;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Business::select('businesses.*')
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->when($request->business_type, fn($q) => $q->where('business_type', $request->business_type));

            return DataTables::of($query)
                ->addColumn('number_col', fn($b) => '<span class="td-mono">' . e($b->permit_number) . '</span>')
                ->addColumn('name_col', fn($b) => '<div style="font-weight:600;font-size:13.5px">' . e($b->business_name) . '</div>')
                ->addColumn('type_col', fn($b) => '<span class="badge badge-navy">' . e($b->business_type) . '</span>')
                ->addColumn('owner_col', function ($b) {
                    $contact = $b->owner_contact ? '<div class="td-muted">' . e($b->owner_contact) . '</div>' : '';
                    return '<div style="font-size:13px">' . e($b->owner_name) . '</div>' . $contact;
                })
                ->addColumn('address_col', fn($b) => '<span class="td-muted" style="max-width:180px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' . e($b->business_address) . '</span>')
                ->addColumn('permit_date_col', fn($b) => '<span class="td-muted">' . ($b->permit_date ? \Carbon\Carbon::parse($b->permit_date)->format('M d, Y') : '—') . '</span>')
                ->addColumn('expiry_col', function ($b) {
                    if (!$b->expiry_date) return '<span class="td-muted">—</span>';
                    $expiry = \Carbon\Carbon::parse($b->expiry_date);
                    $style = $expiry->isPast() ? 'color:var(--crimson);font-weight:600' : 'color:var(--text-muted)';
                    return '<span style="font-size:12.5px;' . $style . '">' . $expiry->format('M d, Y') . '</span>';
                })
                ->addColumn('status_col', function ($b) {
                    $cls = match($b->status) {
                        'Active'    => 'badge-green',
                        'Expired'   => 'badge-red',
                        'Suspended' => 'badge-yellow',
                        'Cancelled' => 'badge-gray',
                        default     => 'badge-gray'
                    };
                    return '<span class="badge ' . $cls . '">' . $b->status . '</span>';
                })
                ->addColumn('actions', function ($b) {
                    $show   = route('businesses.show', $b);
                    $edit   = route('businesses.edit', $b);
                    $delete = route('businesses.destroy', $b);
                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="' . $show . '" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a>
                            <a href="' . $edit . '" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="' . $delete . '" onsubmit="return confirm(\'Delete this business permit?\')">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $s = $request->search['value'];
                        $query->where(fn($q) => $q
                            ->where('business_name', 'like', "%$s%")
                            ->orWhere('owner_name', 'like', "%$s%")
                            ->orWhere('permit_number', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col','name_col','type_col','owner_col','address_col','permit_date_col','expiry_col','status_col','actions'])
                ->make(true);
        }

        $businessTypes = ['Sari-Sari Store','Restaurant / Carinderia','Salon / Barbershop','Repair Shop','Pharmacy / Drugstore','Laundry','Printing / Photocopy','Retail Store','Other'];
        $summaryCounts = [
            'Active'    => Business::where('status','Active')->count(),
            'Expired'   => Business::where('status','Expired')->count(),
            'Suspended' => Business::where('status','Suspended')->count(),
            'Cancelled' => Business::where('status','Cancelled')->count(),
        ];
        return view('businesses.businesses-index', compact('businessTypes','summaryCounts'));
    }

    public function create()
    {
        $residents = Resident::active()->orderBy('last_name')->get();
        $businessTypes = ['Sari-Sari Store','Restaurant / Carinderia','Salon / Barbershop','Repair Shop','Pharmacy / Drugstore','Laundry','Printing / Photocopy','Retail Store','Other'];
        return view('businesses.businesses-create', compact('residents','businessTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name'     => 'required|string|max:255',
            'business_type'     => 'required|string',
            'business_address'  => 'required|string|max:255',
            'owner_name'        => 'required|string|max:255',
            'owner_contact'     => 'nullable|string|max:20',
            'owner_resident_id' => 'nullable|exists:residents,id',
            'permit_date'       => 'required|date',
            'expiry_date'       => 'required|date|after:permit_date',
            'status'            => 'required|in:Active,Expired,Suspended,Cancelled',
        ]);
        $validated['permit_number'] = Business::generatePermitNumber();
        $validated['issued_by']     = auth()->id();
        Business::create($validated);
        return redirect()->route('businesses.index')->with('success', 'Business permit issued successfully.');
    }

    public function show(Business $business)
    {
        $business->load(['ownerResident','issuedBy']);
        return view('businesses.businesses-show', compact('business'));
    }

    public function edit(Business $business)
    {
        $residents = Resident::active()->orderBy('last_name')->get();
        $businessTypes = ['Sari-Sari Store','Restaurant / Carinderia','Salon / Barbershop','Repair Shop','Pharmacy / Drugstore','Laundry','Printing / Photocopy','Retail Store','Other'];
        return view('businesses.businesses-edit', compact('business','residents','businessTypes'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            'business_name'    => 'required|string|max:255',
            'business_type'    => 'required|string',
            'business_address' => 'required|string|max:255',
            'owner_name'       => 'required|string|max:255',
            'owner_contact'    => 'nullable|string|max:20',
            'permit_date'      => 'required|date',
            'expiry_date'      => 'required|date|after:permit_date',
            'status'           => 'required|in:Active,Expired,Suspended,Cancelled',
        ]);
        $business->update($validated);
        return redirect()->route('businesses.index')->with('success', 'Business permit updated successfully.');
    }

    public function destroy(Business $business)
    {
        $business->delete();
        return redirect()->route('businesses.index')->with('success', 'Business permit deleted successfully.');
    }
}