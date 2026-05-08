<?php

namespace App\Http\Controllers;

use App\Models\BlotterCase;
use App\Models\Resident;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BlotterController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BlotterCase::select('blotter_cases.*')
                ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status))
                ->when($request->incident_type, fn ($q) => $q->whereIn('incident_type', (array) $request->incident_type))
                ->when($request->date_from, fn ($q) => $q->whereDate('incident_date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->whereDate('incident_date', '<=', $request->date_to));

            return DataTables::of($query)
                ->addColumn('number_col', fn ($c) => '<span class="td-mono">'.e($c->case_number).'</span>')
                ->addColumn('type_col', fn ($c) => '<span class="badge badge-navy">'.e($c->incident_type).'</span>')
                ->addColumn('complainant_col', fn ($c) => '<div style="font-weight:600;font-size:13px">'.e($c->complainant_name ?? '—').'</div>')
                ->addColumn('respondent_col', fn ($c) => '<span class="td-muted">'.e($c->respondent_name ?? '—').'</span>')
                ->addColumn('date_col', fn ($c) => '<span class="td-muted">'.($c->incident_date ? \Carbon\Carbon::parse($c->incident_date)->format('M d, Y') : '—').'</span>')
                ->addColumn('status_col', function ($c) {
                    $cls = match ($c->status) {
                        'Active'                      => 'badge-red',
                        'Under Investigation'         => 'badge-yellow',
                        'Mediated'                    => 'badge-blue',
                        'Settled'                     => 'badge-green',
                        'Closed'                      => 'badge-gray',
                        'Referred to Higher Authority'=> 'badge-orange',
                        default                       => 'badge-gray'
                    };

                    $out = '<span class="badge '.$cls.'">'.$c->status.'</span>';

                    $isOpen = in_array($c->status, ['Active', 'Under Investigation']);
                    if ($isOpen && $c->incident_date) {
                        $days = Carbon::parse($c->incident_date)->diffInDays(now());
                        if ($days >= 30) {
                            $out .= ' <span class="badge badge-red" style="font-size:10px;gap:3px" title="Open for '.$days.' days">'.
                                    '<i class="fas fa-fire"></i> '.$days.'d overdue</span>';
                        }
                    }

                    return $out;
                })
                ->addColumn('actions', function ($c) {
                    $show   = route('blotter.show', $c);
                    $edit   = route('blotter.edit', $c);
                    $delete = route('blotter.destroy', $c);

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="'.$show.'" class="btn btn-secondary btn-sm btn-icon" title="View Case"><i class="fas fa-eye"></i></a>
                            <a href="'.$edit.'" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="'.$delete.'"
                                  data-confirm="Delete Case '.e($c->case_number).'? This will permanently remove the record and any attachments."
                                  data-confirm-title="Delete Blotter Case"
                                  data-confirm-ok="Delete Case">
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
                            ->where('case_number', 'like', "%$s%")
                            ->orWhere('complainant_name', 'like', "%$s%")
                            ->orWhere('respondent_name', 'like', "%$s%")
                            ->orWhere('incident_type', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col', 'type_col', 'complainant_col', 'respondent_col', 'date_col', 'status_col', 'actions'])
                ->make(true);
        }

        $incidentTypes = ['Noise Complaint', 'Physical Assault', 'Verbal Abuse', 'Theft', 'Trespassing', 'Domestic Dispute', 'Property Damage', 'Threat', 'Other'];
        $statuses = ['Active', 'Under Investigation', 'Mediated', 'Settled', 'Closed', 'Referred to Higher Authority'];
        $summaryCounts = [
            'Active' => BlotterCase::where('status', 'Active')->count(),
            'Under Investigation' => BlotterCase::where('status', 'Under Investigation')->count(),
            'Settled' => BlotterCase::where('status', 'Settled')->count(),
            'Closed' => BlotterCase::where('status', 'Closed')->count(),
        ];

        return view('blotter.blotter-index', compact('incidentTypes', 'statuses', 'summaryCounts'));
    }

    public function create()
    {
        $residents = Resident::active()->orderBy('last_name')->orderBy('first_name')->get();
        $incidentTypes = ['Noise Complaint', 'Physical Assault', 'Verbal Abuse', 'Theft', 'Trespassing', 'Domestic Dispute', 'Property Damage', 'Threat', 'Other'];

        return view('blotter.blotter-create', compact('residents', 'incidentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_type' => 'required|string',
            'incident_date' => 'required|date|before_or_equal:today',
            'incident_location' => 'required|string|max:255',
            'incident_details' => 'required|string',
            'complainant_name' => 'required|string|max:255',
            'complainant_address' => 'nullable|string|max:255',
            'complainant_contact' => 'nullable|string|max:20',
            'complainant_resident_id' => 'nullable|exists:residents,id',
            'respondent_name' => 'required|string|max:255',
            'respondent_address' => 'nullable|string|max:255',
            'respondent_contact' => 'nullable|string|max:20',
            'status' => 'required|in:Active,Under Investigation,Mediated,Settled,Closed,Referred to Higher Authority',
            'resolution_notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);
        $validated['case_number'] = BlotterCase::generateCaseNumber();
        $validated['filed_by'] = auth()->id();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $validated['file_path'] = $file->store('blotter', 'public');
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_original_name'] = $file->getClientOriginalName();
        }

        $record = BlotterCase::create($validated);
        $this->logActivity('created', $record);

        return redirect()->route('blotter.index')->with('success', 'Blotter case filed successfully.');
    }

    public function show(BlotterCase $blotter)
    {
        $blotter->load(['complainantResident', 'filedBy']);

        return view('blotter.blotter-show', compact('blotter'));
    }

    public function edit(BlotterCase $blotter)
    {
        $residents = Resident::active()->orderBy('last_name')->get();
        $incidentTypes = ['Noise Complaint', 'Physical Assault', 'Verbal Abuse', 'Theft', 'Trespassing', 'Domestic Dispute', 'Property Damage', 'Threat', 'Other'];
        $statuses = ['Active', 'Under Investigation', 'Mediated', 'Settled', 'Closed', 'Referred to Higher Authority'];

        return view('blotter.blotter-edit', compact('blotter', 'residents', 'incidentTypes', 'statuses'));
    }

    public function update(Request $request, BlotterCase $blotter)
    {
        $validated = $request->validate([
            'incident_type' => 'required|string',
            'incident_date' => 'required|date|before_or_equal:today',
            'incident_location' => 'required|string|max:255',
            'incident_details' => 'required|string',
            'complainant_name' => 'required|string|max:255',
            'respondent_name' => 'required|string|max:255',
            'status' => 'required|in:Active,Under Investigation,Mediated,Settled,Closed,Referred to Higher Authority',
            'resolution_notes' => 'nullable|string',
        ]);
        if (in_array($validated['status'], ['Settled', 'Closed']) && ! in_array($blotter->status, ['Settled', 'Closed'])) {
            $validated['settled_at'] = now();
        }

        // Handle file removal
        if ($request->boolean('remove_attachment') && $blotter->file_path) {
            \Storage::disk('public')->delete($blotter->file_path);
            $validated['file_path'] = null;
            $validated['file_type'] = null;
            $validated['file_original_name'] = null;
        }

        // Handle new file upload
        if ($request->hasFile('attachment')) {
            if ($blotter->file_path) {
                \Storage::disk('public')->delete($blotter->file_path);
            }
            $file = $request->file('attachment');
            $validated['file_path'] = $file->store('blotter', 'public');
            $validated['file_type'] = $file->getClientOriginalExtension();
            $validated['file_original_name'] = $file->getClientOriginalName();
        }

        $oldData = $blotter->getOriginal();
        $blotter->update($validated);
        $this->logActivity('updated', $blotter, $oldData, $blotter->fresh()->toArray());

        return redirect()->route('blotter.index')->with('success', 'Blotter case updated successfully.');
    }

    public function destroy(BlotterCase $blotter)
    {
        $this->logActivity('deleted', $blotter);
        $blotter->delete();

        return redirect()->route('blotter.index')->with('success', 'Blotter case deleted successfully.');
    }
}
