<?php

namespace App\Http\Controllers;

use App\Mail\PortalStatusUpdated;
use App\Models\BlotterCase;
use App\Models\BlotterStatusLog;
use App\Models\Resident;
use App\Traits\LogsActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class BlotterController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = BlotterCase::select('blotter_cases.*')
                // Portal submissions pending staff review live in Appointments, not here
                ->where(fn ($q) => $q->where('source', '!=', 'portal')->orWhere('status', '!=', 'Pending'))
                ->when($request->status, fn ($q) => $q->whereIn('status', (array) $request->status))
                ->when($request->incident_type, fn ($q) => $q->whereIn('incident_type', (array) $request->incident_type))
                ->when($request->date_from, fn ($q) => $q->whereDate('incident_date', '>=', $request->date_from))
                ->when($request->date_to, fn ($q) => $q->whereDate('incident_date', '<=', $request->date_to))
                ->when($request->source, fn ($q) => $q->where('source', $request->source));

            return DataTables::of($query)
                ->addColumn('number_col', function ($c) {
                    $badge = $c->source === 'portal'
                        ? ' <span class="badge badge-blue" style="font-size:10px;margin-left:4px;vertical-align:middle">Portal</span>'
                        : '';
                    return '<span class="td-mono">'.e($c->case_number).'</span>'.$badge;
                })
                ->addColumn('type_col', fn ($c) => '<span class="badge badge-navy">'.e($c->incident_type).'</span>')
                ->addColumn('complainant_col', fn ($c) => '<div style="font-weight:600;font-size:13px">'.e($c->complainant_name ?? '—').'</div>')
                ->addColumn('respondent_col', fn ($c) => '<span class="td-muted">'.e($c->respondent_name ?? '—').'</span>')
                ->addColumn('date_col', fn ($c) => '<span class="td-muted">'.($c->incident_date ? \Carbon\Carbon::parse($c->incident_date)->format('m/d/Y') : '—').'</span>')
                ->addColumn('status_col', function ($c) {
                    $cls = match ($c->status) {
                        'Active'                      => 'badge-red',
                        'Under Investigation'         => 'badge-yellow',
                        'Mediated'                    => 'badge-blue',
                        'Settled'                     => 'badge-green',
                        'Referred to Higher Authority'=> 'badge-orange',
                        default                       => 'badge-gray'
                    };

                    $out = '<span class="badge '.$cls.'">'.$c->status.'</span>';

                    $isOpen = in_array($c->status, ['Active', 'Under Investigation']);
                    if ($isOpen && $c->incident_date) {
                        $days = (int) Carbon::parse($c->incident_date)->diffInDays(now());
                        if ($days >= 30) {
                            $out .= ' <span class="badge badge-red" style="font-size:10px;gap:3px" title="Open for '.$days.' days">'.
                                    '<i class="fas fa-fire"></i> '.$days.'d overdue</span>';
                        }
                    }

                    return $out;
                })
                ->addColumn('actions', function ($c) {
                    $show   = route('blotter.show', $c);
                    $delete = route('blotter.destroy', $c);

                    $viewBtn = '<a href="'.e($show).'" class="btn btn-secondary btn-sm btn-icon"
                                   data-tippy-content="View Case"><i class="fas fa-eye"></i></a>';

                    $deleteBtn = '<button class="btn btn-danger btn-sm btn-icon blotter-delete-btn"
                                          data-tippy-content="Delete Case"
                                          data-url="'.e($delete).'"
                                          data-num="'.e($c->case_number).'"
                                          data-name="'.e($c->complainant_name ?? '').'">
                                     <i class="fas fa-trash"></i>
                                 </button>';

                    // Portal + Pending: show Activate button (3rd action)
                    $portalBtn = '';
                    if ($c->source === 'portal' && $c->status === 'Pending') {
                        $activateUrl  = route('appointments.blotterActivate', $c);
                        $incidentDate = $c->incident_date
                            ? \Carbon\Carbon::parse($c->incident_date)->format('m/d/Y') : '—';
                        $portalBtn = '<button class="btn btn-success btn-sm blotter-activate-btn"
                                              style="font-size:12px;padding:0 10px;height:30px;
                                                     display:inline-flex;align-items:center;gap:5px;white-space:nowrap"
                                              data-tippy-content="Activate Case"
                                              data-num="'.e($c->case_number).'"
                                              data-complainant="'.e($c->complainant_name ?? '').'"
                                              data-type="'.e($c->incident_type).'"
                                              data-date="'.e($incidentDate).'"
                                              data-url="'.e($activateUrl).'">
                                         <i class="fas fa-shield-halved" style="font-size:11px"></i> Activate
                                     </button>';
                    }

                    return '<div style="display:flex;justify-content:flex-end;gap:6px">'.
                           $portalBtn.$viewBtn.$deleteBtn.'</div>';
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
        $statuses = ['Active', 'Under Investigation', 'Mediated', 'Settled', 'Referred to Higher Authority'];
        $summaryCounts = [
            'Active'              => BlotterCase::where('status', 'Active')->count(),
            'Under Investigation' => BlotterCase::where('status', 'Under Investigation')->count(),
            'Settled'             => BlotterCase::where('status', 'Settled')->count(),
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
            'respondent_name'        => 'required|string|max:255',
            'respondent_address'     => 'nullable|string|max:255',
            'respondent_contact'     => 'nullable|string|max:20',
            'respondent_resident_id' => 'nullable|exists:residents,id',
            'responding_officer'     => 'nullable|string|max:255',
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
        $statuses = ['Active', 'Under Investigation', 'Mediated', 'Settled', 'Referred to Higher Authority'];

        return view('blotter.blotter-edit', compact('blotter', 'residents', 'incidentTypes', 'statuses'));
    }

    public function update(Request $request, BlotterCase $blotter)
    {
        $validated = $request->validate([
            'incident_type'          => 'required|string',
            'incident_date'          => 'required|date|before_or_equal:today',
            'incident_location'      => 'required|string|max:255',
            'incident_details'       => 'required|string',
            'complainant_name'       => 'required|string|max:255',
            'complainant_address'    => 'nullable|string|max:255',
            'complainant_contact'    => 'nullable|string|max:20',
            'complainant_resident_id'=> 'nullable|exists:residents,id',
            'respondent_name'        => 'required|string|max:255',
            'respondent_address'     => 'nullable|string|max:255',
            'respondent_contact'     => 'nullable|string|max:20',
            'respondent_resident_id' => 'nullable|exists:residents,id',
            'responding_officer'     => 'nullable|string|max:255',
            'status'                 => 'required|in:Pending,Active,Under Investigation,Mediated,Settled,Referred to Higher Authority',
            'resolution_notes'       => 'nullable|string',
            'settled_at'             => 'nullable|date',
            'status_message'         => 'nullable|string|max:500',
        ]);

        $statusMessage = $validated['status_message'] ?? null;
        unset($validated['status_message']);

        // Auto-set settled_at when moving into Settled
        if ($validated['status'] === 'Settled') {
            if (empty($validated['settled_at']) && $blotter->status !== 'Settled') {
                $validated['settled_at'] = now();
            }
        } else {
            $validated['settled_at'] = null;
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

        $oldStatus = $blotter->status;
        $oldData   = $blotter->getOriginal();
        $blotter->update($validated);
        $this->logActivity('updated', $blotter, $oldData, $blotter->fresh()->toArray());

        $newStatus = $blotter->status;

        // ── Status log + portal notification ─────────────────────────
        if ($oldStatus !== $newStatus) {
            BlotterStatusLog::create([
                'blotter_case_id' => $blotter->id,
                'from_status'     => $oldStatus,
                'to_status'       => $newStatus,
                'changed_by'      => auth()->user()->name,
                'note'            => $statusMessage ?: null,
                'created_at'      => now(),
            ]);

            if ($blotter->source === 'portal' && $blotter->email) {
                try {
                    Mail::to($blotter->email)->queue(new PortalStatusUpdated(
                        type:          'blotter',
                        requestNumber: $blotter->case_number,
                        residentName:  $blotter->complainant_name,
                        newStatus:     $newStatus,
                        notes:         $statusMessage,
                    ));
                } catch (\Exception $e) {
                    logger()->warning('Blotter edit status email failed: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('blotter.show', $blotter)->with('success', 'Blotter case updated successfully.');
    }

    public function quickStatus(Request $request, BlotterCase $blotter)
    {
        $validated = $request->validate([
            'status'           => 'required|in:Active,Under Investigation,Mediated,Settled,Referred to Higher Authority',
            'resolution_notes' => 'nullable|string|max:1000',
        ]);
        if ($validated['status'] === 'Settled' && $blotter->status !== 'Settled') {
            $validated['settled_at'] = now();
        }
        $old = $blotter->getOriginal();
        $blotter->update($validated);
        $this->logActivity('updated', $blotter, $old, $blotter->fresh()->toArray());

        // Email notification for portal-sourced blotter cases
        if ($blotter->source === 'portal' && $blotter->email) {
            try {
                Mail::to($blotter->email)->queue(new PortalStatusUpdated(
                    type:          'blotter',
                    requestNumber: $blotter->case_number,
                    residentName:  $blotter->complainant_name,
                    newStatus:     $blotter->status,
                    notes:         $validated['resolution_notes'] ?? null,
                ));
            } catch (\Exception $e) {
                logger()->warning('Portal blotter email failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Case status updated to {$blotter->status}.",
            'status'  => $blotter->status,
        ]);
    }

    public function destroy(Request $request, BlotterCase $blotter)
    {
        if ($blotter->file_path) {
            \Storage::disk('public')->delete($blotter->file_path);
        }
        $num = $blotter->case_number;
        $this->logActivity('deleted', $blotter);
        $blotter->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Case {$num} deleted."]);
        }

        return redirect()->route('blotter.index')->with('success', 'Blotter case deleted successfully.');
    }
}
