<?php

namespace App\Http\Controllers;

use App\Models\Official;
use Illuminate\Http\Request;

class OfficialController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all officials
    // -------------------------------------------------------
    public function index()
    {
        $officials = Official::orderByRaw("FIELD(position,
            'Punong Barangay',
            'Kagawad',
            'SK Chairperson',
            'SK Kagawad',
            'Barangay Secretary',
            'Barangay Treasurer',
            'BPSO'
        )")->get();

        return view('officials.officials-index', compact('officials'));
    }

    // -------------------------------------------------------
    // CREATE — Show add form
    // -------------------------------------------------------
    public function create()
    {
        $positions = [
            'Punong Barangay',
            'Kagawad',
            'SK Chairperson',
            'SK Kagawad',
            'Barangay Secretary',
            'Barangay Treasurer',
            'BPSO',
        ];

        $committees = [
            'Peace & Order',
            'Health',
            'Education',
            'Infrastructure',
            'Environment',
            'Livelihood',
            'Transport & Communication',
            'BDRRM',
        ];

        return view('officials.officials-create', compact('positions', 'committees'));
    }

    // -------------------------------------------------------
    // STORE — Save new official
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'position'       => 'required|string|max:100',
            'committee'      => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'term_start'     => 'required|date',
            'term_end'       => 'required|date|after:term_start',
            'is_active'      => 'boolean',
            'photo_path'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')
                ->store('officials', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Official::create($validated);

        return redirect()
            ->route('officials.index')
            ->with('success', 'Official added successfully.');
    }

    // -------------------------------------------------------
    // SHOW — not used, redirect to edit
    // -------------------------------------------------------
    public function show(Official $official)
    {
        return redirect()->route('officials.edit', $official);
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(Official $official)
    {
        $positions = [
            'Punong Barangay',
            'Kagawad',
            'SK Chairperson',
            'SK Kagawad',
            'Barangay Secretary',
            'Barangay Treasurer',
            'BPSO',
        ];

        $committees = [
            'Peace & Order',
            'Health',
            'Education',
            'Infrastructure',
            'Environment',
            'Livelihood',
            'Transport & Communication',
            'BDRRM',
        ];

        return view('officials.officials-edit', compact('official', 'positions', 'committees'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited official
    // -------------------------------------------------------
    public function update(Request $request, Official $official)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'position'       => 'required|string|max:100',
            'committee'      => 'nullable|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'term_start'     => 'required|date',
            'term_end'       => 'required|date|after:term_start',
            'is_active'      => 'boolean',
            'photo_path'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')
                ->store('officials', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $official->update($validated);

        return redirect()
            ->route('officials.index')
            ->with('success', 'Official updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete official
    // -------------------------------------------------------
    public function destroy(Official $official)
    {
        $official->delete();

        return redirect()
            ->route('officials.index')
            ->with('success', 'Official removed successfully.');
    }
}