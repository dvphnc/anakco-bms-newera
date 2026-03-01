<?php

namespace App\Http\Controllers;

use App\Models\BlotterCase;
use App\Models\Resident;
use Illuminate\Http\Request;

class BlotterController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all blotter cases
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = BlotterCase::with(['complainantResident', 'filedBy'])
            ->when($request->search, function ($q) use ($request) {
                $q->where('case_number', 'like', "%{$request->search}%")
                  ->orWhere('complainant_name', 'like', "%{$request->search}%")
                  ->orWhere('respondent_name', 'like', "%{$request->search}%")
                  ->orWhere('incident_type', 'like', "%{$request->search}%");
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->incident_type, function ($q) use ($request) {
                $q->where('incident_type', $request->incident_type);
            })
            ->when($request->date_from, function ($q) use ($request) {
                $q->whereDate('incident_date', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                $q->whereDate('incident_date', '<=', $request->date_to);
            })
            ->latest();

        $cases = $query->paginate(15)->withQueryString();

        $incidentTypes = [
            'Noise Complaint',
            'Physical Assault',
            'Verbal Abuse',
            'Theft',
            'Trespassing',
            'Domestic Dispute',
            'Property Damage',
            'Threat',
            'Other',
        ];

        $statuses = [
            'Active',
            'Under Investigation',
            'Mediated',
            'Settled',
            'Closed',
            'Referred to Higher Authority',
        ];

        // Summary counts for the status cards
        $summaryCounts = [
            'Active'               => BlotterCase::where('status', 'Active')->count(),
            'Under Investigation'  => BlotterCase::where('status', 'Under Investigation')->count(),
            'Settled'              => BlotterCase::where('status', 'Settled')->count(),
            'Closed'               => BlotterCase::where('status', 'Closed')->count(),
        ];

        return view('blotter.blotter-index', compact(
            'cases',
            'incidentTypes',
            'statuses',
            'summaryCounts'
        ));
    }

    // -------------------------------------------------------
    // CREATE — Show file case form
    // -------------------------------------------------------
    public function create()
    {
        $residents = Resident::active()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $incidentTypes = [
            'Noise Complaint',
            'Physical Assault',
            'Verbal Abuse',
            'Theft',
            'Trespassing',
            'Domestic Dispute',
            'Property Damage',
            'Threat',
            'Other',
        ];

        return view('blotter.blotter-create', compact('residents', 'incidentTypes'));
    }

    // -------------------------------------------------------
    // STORE — Save new blotter case
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_type'           => 'required|string',
            'incident_date'           => 'required|date|before_or_equal:today',
            'incident_location'       => 'required|string|max:255',
            'incident_details'        => 'required|string',
            'complainant_name'        => 'required|string|max:255',
            'complainant_address'     => 'nullable|string|max:255',
            'complainant_contact'     => 'nullable|string|max:20',
            'complainant_resident_id' => 'nullable|exists:residents,id',
            'respondent_name'         => 'required|string|max:255',
            'respondent_address'      => 'nullable|string|max:255',
            'respondent_contact'      => 'nullable|string|max:20',
            'status'                  => 'required|in:Active,Under Investigation,Mediated,Settled,Closed,Referred to Higher Authority',
        ]);

        $validated['case_number'] = BlotterCase::generateCaseNumber();
        $validated['filed_by']    = auth()->id();

        BlotterCase::create($validated);

        return redirect()
            ->route('blotter.index')
            ->with('success', 'Blotter case filed successfully.');
    }

    // -------------------------------------------------------
    // SHOW — View case details
    // -------------------------------------------------------
    public function show(BlotterCase $blotter)
    {
        $blotter->load(['complainantResident', 'filedBy']);

        return view('blotter.blotter-show', compact('blotter'));
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(BlotterCase $blotter)
    {
        $residents = Resident::active()
            ->orderBy('last_name')
            ->get();

        $incidentTypes = [
            'Noise Complaint',
            'Physical Assault',
            'Verbal Abuse',
            'Theft',
            'Trespassing',
            'Domestic Dispute',
            'Property Damage',
            'Threat',
            'Other',
        ];

        $statuses = [
            'Active',
            'Under Investigation',
            'Mediated',
            'Settled',
            'Closed',
            'Referred to Higher Authority',
        ];

        return view('blotter.blotter-edit', compact('blotter', 'residents', 'incidentTypes', 'statuses'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited case
    // -------------------------------------------------------
    public function update(Request $request, BlotterCase $blotter)
    {
        $validated = $request->validate([
            'incident_type'     => 'required|string',
            'incident_date'     => 'required|date|before_or_equal:today',
            'incident_location' => 'required|string|max:255',
            'incident_details'  => 'required|string',
            'complainant_name'  => 'required|string|max:255',
            'respondent_name'   => 'required|string|max:255',
            'status'            => 'required|in:Active,Under Investigation,Mediated,Settled,Closed,Referred to Higher Authority',
            'resolution_notes'  => 'nullable|string',
        ]);

        // Set settled_at when status changes to Settled or Closed
        if (in_array($validated['status'], ['Settled', 'Closed']) &&
            !in_array($blotter->status, ['Settled', 'Closed'])) {
            $validated['settled_at'] = now();
        }

        $blotter->update($validated);

        return redirect()
            ->route('blotter.index')
            ->with('success', 'Blotter case updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete case
    // -------------------------------------------------------
    public function destroy(BlotterCase $blotter)
    {
        $blotter->delete();

        return redirect()
            ->route('blotter.index')
            ->with('success', 'Blotter case deleted successfully.');
    }
}