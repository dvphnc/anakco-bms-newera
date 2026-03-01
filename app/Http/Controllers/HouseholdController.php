<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Models\Purok;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all households
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = Household::with(['purok', 'residents'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('household_number', 'like', "%{$request->search}%")
                  ->orWhere('household_head', 'like', "%{$request->search}%")
                  ->orWhere('address', 'like', "%{$request->search}%");
            })
            ->when($request->purok_id, function ($q) use ($request) {
                $q->where('purok_id', $request->purok_id);
            })
            ->orderBy('household_number');

        $households = $query->paginate(15)->withQueryString();
        $puroks     = Purok::orderBy('name')->get();

        return view('households.households-index', compact('households', 'puroks'));
    }

    // -------------------------------------------------------
    // CREATE — Show add form
    // -------------------------------------------------------
    public function create()
    {
        $puroks = Purok::orderBy('name')->get();

        return view('households.households-create', compact('puroks'));
    }

    // -------------------------------------------------------
    // STORE — Save new household
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purok_id'           => 'required|exists:puroks,id',
            'address'            => 'required|string|max:255',
            'household_head'     => 'nullable|string|max:255',
            'family_size'        => 'required|integer|min:1',
            'is_voter_household' => 'boolean',
        ]);

        // Auto-generate household number
        $validated['household_number'] = $this->generateHouseholdNumber();
        $validated['is_voter_household'] = $request->boolean('is_voter_household');

        Household::create($validated);

        return redirect()
            ->route('households.index')
            ->with('success', 'Household added successfully.');
    }

    // -------------------------------------------------------
    // SHOW — View household details
    // -------------------------------------------------------
    public function show(Household $household)
    {
        $household->load(['purok', 'residents']);

        return view('households.households-show', compact('household'));
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(Household $household)
    {
        $puroks = Purok::orderBy('name')->get();

        return view('households.households-edit', compact('household', 'puroks'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited household
    // -------------------------------------------------------
    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'purok_id'           => 'required|exists:puroks,id',
            'address'            => 'required|string|max:255',
            'household_head'     => 'nullable|string|max:255',
            'family_size'        => 'required|integer|min:1',
            'is_voter_household' => 'boolean',
        ]);

        $validated['is_voter_household'] = $request->boolean('is_voter_household');

        $household->update($validated);

        return redirect()
            ->route('households.index')
            ->with('success', 'Household updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete household
    // -------------------------------------------------------
    public function destroy(Household $household)
    {
        $household->delete();

        return redirect()
            ->route('households.index')
            ->with('success', 'Household deleted successfully.');
    }

    // -------------------------------------------------------
    // HELPER — Generate household number
    // -------------------------------------------------------
    private function generateHouseholdNumber(): string
    {
        $year  = date('Y');
        $count = Household::whereYear('created_at', $year)->count() + 1;

        return 'HH-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}