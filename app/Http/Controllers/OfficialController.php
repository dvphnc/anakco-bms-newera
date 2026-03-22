<?php

namespace App\Http\Controllers;

use App\Models\Official;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;

class OfficialController extends Controller
{
    use LogsActivity;

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

    public function create()
    {
        $positions  = ['Punong Barangay','Kagawad','SK Chairperson','SK Kagawad','Barangay Secretary','Barangay Treasurer','BPSO'];
        $committees = ['Peace & Order','Health','Education','Infrastructure','Environment','Livelihood','Transport & Communication','BDRRM'];
        return view('officials.officials-create', compact('positions', 'committees'));
    }

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
            $validated['photo_path'] = $request->file('photo_path')->store('officials', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $record = Official::create($validated);
        $this->logActivity('created', $record);

        return redirect()->route('officials.index')->with('success', 'Official added successfully.');
    }

    public function show(Official $official)
    {
        return view('officials.officials-show', compact('official'));
    }

    public function edit(Official $official)
    {
        $positions  = ['Punong Barangay','Kagawad','SK Chairperson','SK Kagawad','Barangay Secretary','Barangay Treasurer','BPSO'];
        $committees = ['Peace & Order','Health','Education','Infrastructure','Environment','Livelihood','Transport & Communication','BDRRM'];
        return view('officials.officials-edit', compact('official', 'positions', 'committees'));
    }

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
            $validated['photo_path'] = $request->file('photo_path')->store('officials', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $oldData = $official->getOriginal();
        $official->update($validated);
        $this->logActivity('updated', $official, $oldData, $official->fresh()->toArray());

        return redirect()->route('officials.index')->with('success', 'Official updated successfully.');
    }

    public function destroy(Official $official)
    {
        $this->logActivity('deleted', $official);
        $official->delete();
        return redirect()->route('officials.index')->with('success', 'Official removed successfully.');
    }
}