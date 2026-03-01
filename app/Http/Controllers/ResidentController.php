<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Purok;
use App\Models\Household;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all residents
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = Resident::with(['purok', 'household'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->search}%")
                  ->orWhere('last_name', 'like', "%{$request->search}%")
                  ->orWhere('address', 'like', "%{$request->search}%");
            })
            ->when($request->gender, function ($q) use ($request) {
                $q->where('gender', $request->gender);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('residency_status', $request->status);
            })
            ->when($request->purok_id, function ($q) use ($request) {
                $q->where('purok_id', $request->purok_id);
            })
            ->orderBy('last_name')
            ->orderBy('first_name');

        $residents = $query->paginate(15)->withQueryString();
        $puroks    = Purok::orderBy('name')->get();

        return view('residents.residents-index', compact('residents', 'puroks'));
    }

    // -------------------------------------------------------
    // CREATE — Show add form
    // -------------------------------------------------------
    public function create()
    {
        $puroks     = Purok::orderBy('name')->get();
        $households = Household::orderBy('household_number')->get();

        return view('residents.residents-create', compact('puroks', 'households'));
    }

    // -------------------------------------------------------
    // STORE — Save new resident
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name'        => 'required|string|max:100',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'suffix'           => 'nullable|string|max:10',
            'birthdate'        => 'required|date|before:today',
            'gender'           => 'required|in:Male,Female',
            'civil_status'     => 'nullable|in:Single,Married,Widowed,Separated,Annulled',
            'birthplace'       => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'religion'         => 'nullable|string|max:100',
            'occupation'       => 'nullable|string|max:100',
            'contact_number'   => 'nullable|string|max:20',
            'email_address'    => 'nullable|email|max:255',
            'address'          => 'required|string|max:255',
            'purok_id'         => 'required|exists:puroks,id',
            'household_id'     => 'nullable|exists:households,id',
            'is_voter'         => 'boolean',
            'is_pwd'           => 'boolean',
            'is_senior'        => 'boolean',
            'is_solo_parent'   => 'boolean',
            'is_4ps'           => 'boolean',
            'residency_status' => 'required|in:Active,Deceased,Transferred',
            'photo_path'       => 'nullable|image|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')
                ->store('residents', 'public');
        }

        // Handle checkboxes (unchecked = not in request = false)
        $validated['is_voter']      = $request->boolean('is_voter');
        $validated['is_pwd']        = $request->boolean('is_pwd');
        $validated['is_senior']     = $request->boolean('is_senior');
        $validated['is_solo_parent']= $request->boolean('is_solo_parent');
        $validated['is_4ps']        = $request->boolean('is_4ps');

        Resident::create($validated);

        return redirect()
            ->route('residents.index')
            ->with('success', 'Resident registered successfully.');
    }

    // -------------------------------------------------------
    // SHOW — View resident profile
    // -------------------------------------------------------
    public function show(Resident $resident)
    {
        $resident->load(['purok', 'household', 'documents', 'blotterCases']);

        return view('residents.residents-show', compact('resident'));
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(Resident $resident)
    {
        $puroks     = Purok::orderBy('name')->get();
        $households = Household::orderBy('household_number')->get();

        return view('residents.residents-edit', compact('resident', 'puroks', 'households'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited resident
    // -------------------------------------------------------
    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate([
            'last_name'        => 'required|string|max:100',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'suffix'           => 'nullable|string|max:10',
            'birthdate'        => 'required|date|before:today',
            'gender'           => 'required|in:Male,Female',
            'civil_status'     => 'nullable|in:Single,Married,Widowed,Separated,Annulled',
            'birthplace'       => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'religion'         => 'nullable|string|max:100',
            'occupation'       => 'nullable|string|max:100',
            'contact_number'   => 'nullable|string|max:20',
            'email_address'    => 'nullable|email|max:255',
            'address'          => 'required|string|max:255',
            'purok_id'         => 'required|exists:puroks,id',
            'household_id'     => 'nullable|exists:households,id',
            'is_voter'         => 'boolean',
            'is_pwd'           => 'boolean',
            'is_senior'        => 'boolean',
            'is_solo_parent'   => 'boolean',
            'is_4ps'           => 'boolean',
            'residency_status' => 'required|in:Active,Deceased,Transferred',
            'photo_path'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')
                ->store('residents', 'public');
        }

        $validated['is_voter']      = $request->boolean('is_voter');
        $validated['is_pwd']        = $request->boolean('is_pwd');
        $validated['is_senior']     = $request->boolean('is_senior');
        $validated['is_solo_parent']= $request->boolean('is_solo_parent');
        $validated['is_4ps']        = $request->boolean('is_4ps');

        $resident->update($validated);

        return redirect()
            ->route('residents.index')
            ->with('success', 'Resident updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete resident (soft delete)
    // -------------------------------------------------------
    public function destroy(Resident $resident)
    {
        $resident->delete();

        return redirect()
            ->route('residents.index')
            ->with('success', 'Resident removed successfully.');
    }
}