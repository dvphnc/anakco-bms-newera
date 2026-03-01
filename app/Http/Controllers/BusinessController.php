<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Resident;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all businesses
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = Business::with(['ownerResident', 'issuedBy'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('business_name', 'like', "%{$request->search}%")
                  ->orWhere('owner_name', 'like', "%{$request->search}%")
                  ->orWhere('permit_number', 'like', "%{$request->search}%");
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->business_type, function ($q) use ($request) {
                $q->where('business_type', $request->business_type);
            })
            ->latest();

        $businesses = $query->paginate(15)->withQueryString();

        $businessTypes = [
            'Sari-Sari Store',
            'Restaurant / Carinderia',
            'Salon / Barbershop',
            'Repair Shop',
            'Pharmacy / Drugstore',
            'Laundry',
            'Printing / Photocopy',
            'Retail Store',
            'Other',
        ];

        $summaryCounts = [
            'Active'    => Business::where('status', 'Active')->count(),
            'Expired'   => Business::where('status', 'Expired')->count(),
            'Suspended' => Business::where('status', 'Suspended')->count(),
            'Cancelled' => Business::where('status', 'Cancelled')->count(),
        ];

        return view('businesses.businesses-index', compact(
            'businesses',
            'businessTypes',
            'summaryCounts'
        ));
    }

    // -------------------------------------------------------
    // CREATE — Show new permit form
    // -------------------------------------------------------
    public function create()
    {
        $residents = Resident::active()
            ->orderBy('last_name')
            ->get();

        $businessTypes = [
            'Sari-Sari Store',
            'Restaurant / Carinderia',
            'Salon / Barbershop',
            'Repair Shop',
            'Pharmacy / Drugstore',
            'Laundry',
            'Printing / Photocopy',
            'Retail Store',
            'Other',
        ];

        return view('businesses.businesses-create', compact('residents', 'businessTypes'));
    }

    // -------------------------------------------------------
    // STORE — Save new business permit
    // -------------------------------------------------------
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

        return redirect()
            ->route('businesses.index')
            ->with('success', 'Business permit issued successfully.');
    }

    // -------------------------------------------------------
    // SHOW — View business details
    // -------------------------------------------------------
    public function show(Business $business)
    {
        $business->load(['ownerResident', 'issuedBy']);

        return view('businesses.businesses-show', compact('business'));
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(Business $business)
    {
        $residents = Resident::active()
            ->orderBy('last_name')
            ->get();

        $businessTypes = [
            'Sari-Sari Store',
            'Restaurant / Carinderia',
            'Salon / Barbershop',
            'Repair Shop',
            'Pharmacy / Drugstore',
            'Laundry',
            'Printing / Photocopy',
            'Retail Store',
            'Other',
        ];

        return view('businesses.businesses-edit', compact('business', 'residents', 'businessTypes'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited business
    // -------------------------------------------------------
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

        return redirect()
            ->route('businesses.index')
            ->with('success', 'Business permit updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete business
    // -------------------------------------------------------
    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()
            ->route('businesses.index')
            ->with('success', 'Business permit deleted successfully.');
    }
}