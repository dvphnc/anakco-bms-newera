<?php

namespace App\Http\Controllers;

use App\Models\BpsoMember;
use App\Models\ClinicStaff;
use App\Models\CommitteeActivity;
use App\Models\CommitteeAttendance;
use App\Models\CommitteeInventory;
use App\Models\CommitteePartnership;
use App\Models\CommitteeRecord;
use App\Models\EmergencyLog;
use App\Models\EnvironmentProgram;
use App\Models\EvacuationCenter;
use App\Models\HealthRecord;
use App\Models\InfraContract;
use App\Models\InfraFinancial;
use App\Models\InfraProject;
use App\Models\LivelihoodBeneficiary;
use App\Models\MedicineInventory;
use App\Models\MedicineStockLog;
use App\Models\PatrolLog;
use App\Models\ReliefSupply;
use App\Models\Scholar;
use App\Models\StreetSweeper;
use App\Models\TanodTraining;
use App\Models\TodaVehicle;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    use LogsActivity;

    private array $committees = [
        'peace-order' => [
            'name'  => 'Peace & Order',
            'slug'  => 'peace-order',
            'icon'  => 'fa-shield-halved',
            'color' => '#1d4ed8',
            'chair' => 'Robert S. Romano',
        ],
        'health' => [
            'name'  => 'Health',
            'slug'  => 'health',
            'icon'  => 'fa-heartbeat',
            'color' => '#dc2626',
            'chair' => 'Twinkle B. Pineda-Corpuz',
        ],
        'education' => [
            'name'  => 'Education',
            'slug'  => 'education',
            'icon'  => 'fa-graduation-cap',
            'color' => '#7c3aed',
            'chair' => 'Alfredo L. Sicat',
        ],
        'infrastructure' => [
            'name'  => 'Infrastructure',
            'slug'  => 'infrastructure',
            'icon'  => 'fa-road',
            'color' => '#d97706',
            'chair' => 'Euler A. Moreno',
        ],
        'environment' => [
            'name'  => 'Environment',
            'slug'  => 'environment',
            'icon'  => 'fa-leaf',
            'color' => '#16a34a',
            'chair' => 'Medel R. Sulpico',
        ],
        'livelihood' => [
            'name'  => 'Livelihood',
            'slug'  => 'livelihood',
            'icon'  => 'fa-briefcase',
            'color' => '#0891b2',
            'chair' => 'Freddie C. Marcial',
        ],
        'transport' => [
            'name'  => 'Transport & Comm.',
            'slug'  => 'transport',
            'icon'  => 'fa-bus',
            'color' => '#ea580c',
            'chair' => 'Salvador L. Enriquez',
        ],
        'bdrrm' => [
            'name'  => 'BDRRM',
            'slug'  => 'bdrrm',
            'icon'  => 'fa-exclamation-triangle',
            'color' => '#b91c1c',
            'chair' => 'Joel A. Tamayo',
        ],
    ];

    public function show(string $slug)
    {
        $committee = $this->committees[$slug] ?? abort(404);

        // Generic tabs data
        $photos = CommitteeRecord::where('committee_slug', $slug)->where('record_type', 'Photo')->latest()->get();
        $reports = CommitteeRecord::where('committee_slug', $slug)->where('record_type', 'Report')->latest()->get();
        $resolutions = CommitteeRecord::where('committee_slug', $slug)->where('record_type', 'Resolution')->latest()->get();
        $otherRecords = CommitteeRecord::where('committee_slug', $slug)->whereNotIn('record_type', ['Photo', 'Report', 'Resolution'])->latest()->get();
        $activities = CommitteeActivity::where('committee_slug', $slug)->where('activity_type', 'Activity')->latest()->get();
        $accomplishments = CommitteeActivity::where('committee_slug', $slug)->where('activity_type', 'Accomplishment')->latest()->get();
        $attendances = CommitteeAttendance::where('committee_slug', $slug)->latest()->get();
        $inventory = CommitteeInventory::where('committee_slug', $slug)->latest()->get();

        // Partnerships — generic tab for all committees
        $partnerships = CommitteePartnership::where('committee_slug', $slug)->latest()->get();

        // Committee-specific data
        $specificData = $this->getSpecificData($slug);

        return view('committees.show', compact(
            'committee', 'photos', 'reports', 'resolutions', 'otherRecords',
            'activities', 'accomplishments', 'attendances', 'inventory',
            'partnerships', 'specificData'
        ));
    }

    private function getSpecificData(string $slug): array
    {
        return match ($slug) {
            'peace-order' => [
                'bpso'      => BpsoMember::orderBy('full_name')->get(),
                'patrol_logs' => PatrolLog::latest('patrol_date')->get(),
                'trainings' => TanodTraining::latest('training_date')->get(),
            ],
            'health' => [
                'health_records'     => HealthRecord::latest('visit_date')->get(),
                'clinic_staff'       => ClinicStaff::orderBy('full_name')->get(),
                'medicine_inventory' => MedicineInventory::with(['stockLogs' => fn ($q) => $q->latest()->limit(30)])->orderBy('generic_name')->orderBy('medicine_name')->get(),
            ],
            'education' => [
                'scholars' => Scholar::orderBy('full_name')->get(),
            ],
            'infrastructure' => [
                'projects'   => InfraProject::latest()->get(),
                'contracts'  => InfraContract::latest()->get(),
                'financials' => InfraFinancial::latest('date')->get(),
            ],
            'environment' => [
                'programs'  => EnvironmentProgram::latest('program_date')->get(),
                'sweepers'  => StreetSweeper::orderBy('full_name')->get(),
            ],
            'livelihood' => [
                'beneficiaries' => LivelihoodBeneficiary::orderBy('full_name')->get(),
            ],
            'transport' => [
                'toda' => TodaVehicle::orderBy('operator_name')->get(),
            ],
            'bdrrm' => [
                'emergency_logs'     => EmergencyLog::latest('incident_date')->get(),
                'evacuation_centers' => EvacuationCenter::orderBy('center_name')->get(),
                'relief_supplies'    => ReliefSupply::with('programs')->orderBy('item_name')->get(),
            ],
            default => [],
        };
    }

    // -------------------------------------------------------
    // STORE methods for generic tabs
    // -------------------------------------------------------
    public function storeRecord(Request $request, string $slug)
    {
        $validated = $request->validate([
            'record_type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store("committees/{$slug}", 'public');
        }

        $validated['committee_slug'] = $slug;
        CommitteeRecord::create($validated);

        if ($request->expectsJson()) {
            $count = \App\Models\CommitteeRecord::where('committee_slug', $slug)->count();
            return response()->json([
                'success'   => true,
                'message'   => 'Record uploaded successfully.',
                'tab_id'    => 'records',
                'new_count' => $count,
                'row_html'  => null, // page reload handles new row for file uploads
            ]);
        }

        return back()->with('success', 'Record uploaded successfully.')->withFragment('records');
    }

    public function storeActivity(Request $request, string $slug)
    {
        $validated = $request->validate([
            'activity_type' => 'required|string',
            'title' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $validated['committee_slug'] = $slug;
        CommitteeActivity::create($validated);

        $tab = $validated['activity_type'] === 'Accomplishment' ? 'accomplishments' : 'activities';

        if ($request->expectsJson()) {
            $isAcc   = $validated['activity_type'] === 'Accomplishment';
            $tabId   = $isAcc ? 'accomplishments' : 'activities';
            $count   = \App\Models\CommitteeActivity::where('committee_slug', $slug)
                            ->where('activity_type', $validated['activity_type'])->count();
            $act     = \App\Models\CommitteeActivity::where('committee_slug', $slug)->latest()->first();
            $badgeCls = match($act->status) {
                'Completed' => 'badge-green', 'Ongoing' => 'badge-yellow',
                'Cancelled' => 'badge-red',   default   => 'badge-gray',
            };
            $rowHtml = '<tr>'
                . '<td><div style="font-weight:600">' . e($act->title) . '</div>'
                . ($act->description ? '<div class="td-muted">' . e($act->description) . '</div>' : '')
                . '</td>'
                . '<td class="td-muted">' . $act->activity_date->format('m/d/Y') . '</td>'
                . '<td class="td-muted">' . e($act->location ?? '—') . '</td>'
                . '<td style="font-weight:600;color:var(--navy)">' . number_format($act->participants_count ?? 0) . '</td>'
                . '<td><span class="badge ' . $badgeCls . '">' . e($act->status) . '</span></td>'
                . '</tr>';
            return response()->json([
                'success'   => true,
                'message'   => 'Activity logged successfully.',
                'tab_id'    => $tabId,
                'new_count' => $count,
                'row_html'  => $rowHtml,
            ]);
        }

        return back()->with('success', 'Added successfully.')->withFragment($tab);
    }

    public function storeAttendance(Request $request, string $slug)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'total_attendees' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store("committees/{$slug}/attendance", 'public');
        }

        $validated['committee_slug'] = $slug;
        CommitteeAttendance::create($validated);

        if ($request->expectsJson()) {
            $count = \App\Models\CommitteeAttendance::where('committee_slug', $slug)->count();
            $att   = \App\Models\CommitteeAttendance::where('committee_slug', $slug)->latest()->first();
            $rowHtml = '<tr>'
                . '<td style="font-weight:600">' . e($att->event_name) . '</td>'
                . '<td class="td-muted">' . \Carbon\Carbon::parse($att->event_date)->format('m/d/Y') . '</td>'
                . '<td class="td-muted">' . e($att->venue ?? '—') . '</td>'
                . '<td style="text-align:right;font-weight:700;color:var(--navy)">' . number_format($att->total_attendees) . '</td>'
                . '<td class="td-muted">' . e($att->notes ?? '—') . '</td>'
                . '<td><span class="td-muted">—</span></td>'
                . '</tr>';
            return response()->json([
                'success'   => true,
                'message'   => 'Attendance recorded.',
                'tab_id'    => 'attendance',
                'new_count' => $count,
                'row_html'  => $rowHtml,
            ]);
        }

        return back()->with('success', 'Attendance recorded.')->withFragment('attendance');
    }

    public function storeInventory(Request $request, string $slug)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'condition' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
        $validated['committee_slug'] = $slug;
        CommitteeInventory::create($validated);

        if ($request->expectsJson()) {
            $count = \App\Models\CommitteeInventory::where('committee_slug', $slug)->count();
            $item  = \App\Models\CommitteeInventory::where('committee_slug', $slug)->latest()->first();
            $badgeCls = match($item->condition) {
                'Good' => 'badge-green', 'Fair' => 'badge-yellow',
                'Poor' => 'badge-red',   default => 'badge-gray',
            };
            $rowHtml = '<tr>'
                . '<td style="font-weight:600">' . e($item->item_name) . '</td>'
                . '<td class="td-muted">' . e($item->category ?? '—') . '</td>'
                . '<td style="text-align:right;font-weight:700;color:var(--navy)">' . number_format($item->quantity) . '</td>'
                . '<td class="td-muted">' . e($item->unit ?? '—') . '</td>'
                . '<td><span class="badge ' . $badgeCls . '">' . e($item->condition) . '</span></td>'
                . '<td class="td-muted">' . e($item->remarks ?? '—') . '</td>'
                . '</tr>';
            return response()->json([
                'success'   => true,
                'message'   => 'Item added to inventory.',
                'tab_id'    => 'inventory',
                'new_count' => $count,
                'row_html'  => $rowHtml,
            ]);
        }

        return back()->with('success', 'Item added.')->withFragment('inventory');
    }

    // -------------------------------------------------------
    // DELETE helpers
    // -------------------------------------------------------
    public function destroyPartnership(string $slug, int $id)
    {
        $record = CommitteePartnership::where('committee_slug', $slug)->findOrFail($id);
        $name   = $record->partner_name;
        $record->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => "Partnership with {$name} deleted."]);
        }

        return back()->with('success', 'Partnership record deleted.')->withFragment('partnerships');
    }

    public function destroyMedicine(string $slug, int $id)
    {
        $medicine = MedicineInventory::findOrFail($id);
        $name     = $medicine->medicine_name;
        $this->logActivity('deleted', $medicine);
        $medicine->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => "{$name} removed from inventory."]);
        }

        return back()->with('success', 'Medicine record deleted.')->withFragment('medicine-inventory');
    }

    public function destroyRelief(string $slug, int $id)
    {
        $record = ReliefSupply::findOrFail($id);
        $name   = $record->item_name;

        // Programs draw from this item, or claims were given out of it: deleting it
        // would break the program / erase the stock history
        $program = $record->programs()->withTrashed()->value('name');
        $given   = $record->movements()->whereNotNull('resident_transaction_id')->exists();
        if ($program || $given) {
            $message = $program
                ? "{$name} is used by the program “{$program}”. Remove it from that program first, or set its quantity to 0 instead."
                : "{$name} has claims recorded against it. Set its quantity to 0 instead, so the history is kept.";

            return request()->expectsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->with('error', $message)->withFragment('relief-supplies');
        }

        $record->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => "{$name} removed from relief inventory."]);
        }

        return back()->with('success', 'Relief supply deleted.')->withFragment('relief-supplies');
    }

    // -------------------------------------------------------
    // UPDATE — generic tabs
    // -------------------------------------------------------
    public function updateRecord(Request $request, string $slug, int $id)
    {
        $record = CommitteeRecord::where('committee_slug', $slug)->findOrFail($id);
        $v = $request->validate([
            'title'       => 'required|string|max:255',
            'record_type' => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);
        $record->update($v);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Record updated.', 'record' => $record->fresh()]);
        }
        return back()->with('success', 'Record updated.');
    }

    // -------------------------------------------------------
    // DELETE — generic tabs
    // -------------------------------------------------------
    public function destroyRecord(string $slug, int $id)
    {
        $record = CommitteeRecord::where('committee_slug', $slug)->findOrFail($id);
        if ($record->file_path) \Illuminate\Support\Facades\Storage::disk('public')->delete($record->file_path);
        $record->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Record deleted.']);
        }
        return back()->with('success', 'Record deleted.')->withFragment('records');
    }

    public function destroyActivity(string $slug, int $id)
    {
        $activity = CommitteeActivity::where('committee_slug', $slug)->findOrFail($id);
        $tab = $activity->activity_type === 'Accomplishment' ? 'accomplishments' : 'activities';
        $activity->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Deleted successfully.']);
        }
        return back()->with('success', 'Deleted.')->withFragment($tab);
    }

    public function destroyAttendance(string $slug, int $id)
    {
        $att = CommitteeAttendance::where('committee_slug', $slug)->findOrFail($id);
        if ($att->file_path) \Illuminate\Support\Facades\Storage::disk('public')->delete($att->file_path);
        $att->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Attendance record deleted.']);
        }
        return back()->with('success', 'Attendance record deleted.')->withFragment('attendance');
    }

    public function destroyInventory(string $slug, int $id)
    {
        $item = CommitteeInventory::where('committee_slug', $slug)->findOrFail($id);
        $item->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Inventory item deleted.']);
        }
        return back()->with('success', 'Item deleted.')->withFragment('inventory');
    }

    public function destroySpecificItem(string $slug, string $type, int $id)
    {
        $map = [
            'bpso'        => BpsoMember::class,
            'patrol'      => PatrolLog::class,
            'training'    => TanodTraining::class,
            'health'      => HealthRecord::class,
            'clinic'      => ClinicStaff::class,
            'scholar'     => Scholar::class,
            'project'     => InfraProject::class,
            'contract'    => InfraContract::class,
            'financial'   => InfraFinancial::class,
            'environment' => EnvironmentProgram::class,
            'sweeper'     => StreetSweeper::class,
            'beneficiary' => LivelihoodBeneficiary::class,
            'toda'        => TodaVehicle::class,
            'emergency'   => EmergencyLog::class,
            'evacuation'  => EvacuationCenter::class,
        ];
        abort_unless(isset($map[$type]), 404);
        $record = $map[$type]::findOrFail($id);
        $record->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Record deleted.']);
        }
        return back()->with('success', 'Record deleted.');
    }

    // -------------------------------------------------------
    // UPDATE — generic tabs
    // -------------------------------------------------------
    public function updateActivity(Request $request, string $slug, int $id)
    {
        $activity = CommitteeActivity::where('committee_slug', $slug)->findOrFail($id);
        $v = $request->validate([
            'title'              => 'required|string|max:255',
            'activity_date'      => 'required|date',
            'location'           => 'nullable|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'status'             => 'required|string',
            'description'        => 'nullable|string',
        ]);
        $activity->update($v);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Updated successfully.', 'record' => $activity->fresh()]);
        }
        return back()->with('success', 'Updated.');
    }

    public function updateAttendance(Request $request, string $slug, int $id)
    {
        $att = CommitteeAttendance::where('committee_slug', $slug)->findOrFail($id);
        $v = $request->validate([
            'event_name'     => 'required|string|max:255',
            'event_date'     => 'required|date',
            'venue'          => 'nullable|string|max:255',
            'total_attendees'=> 'required|integer|min:0',
            'notes'          => 'nullable|string',
        ]);
        $att->update($v);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Attendance updated.', 'record' => $att->fresh()]);
        }
        return back()->with('success', 'Attendance updated.');
    }

    public function updateInventory(Request $request, string $slug, int $id)
    {
        $item = CommitteeInventory::where('committee_slug', $slug)->findOrFail($id);
        $v = $request->validate([
            'item_name' => 'required|string|max:255',
            'category'  => 'nullable|string|max:100',
            'quantity'  => 'required|integer|min:0',
            'unit'      => 'nullable|string|max:50',
            'condition' => 'required|string',
            'remarks'   => 'nullable|string',
        ]);
        $item->update($v);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Inventory updated.', 'record' => $item->fresh()]);
        }
        return back()->with('success', 'Inventory updated.');
    }

    public function updatePartnership(Request $request, string $slug, int $id)
    {
        $p = CommitteePartnership::where('committee_slug', $slug)->findOrFail($id);
        $v = $request->validate([
            'partner_name'   => 'required|string|max:255',
            'partner_type'   => 'required|in:Government,NGO,Private,Community,Other',
            'mou_date'       => 'nullable|date',
            'validity_date'  => 'nullable|date',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'description'    => 'nullable|string',
        ]);
        $p->update($v);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Partnership updated.', 'record' => $p->fresh()]);
        }
        return back()->with('success', 'Partnership updated.');
    }

    public function updateSpecificItem(Request $request, string $slug, string $type, int $id)
    {
        $map = [
            'bpso'        => [BpsoMember::class,        ['full_name'=>'required|string|max:255','rank'=>'nullable|string|max:100','badge_number'=>'nullable|string|max:50','contact_number'=>'nullable|string|max:20','assignment'=>'nullable|string|max:255','status'=>'required|in:Active,Inactive,On Leave']],
            'patrol'      => [PatrolLog::class,          ['patrol_date'=>'required|date','shift'=>'nullable|string','area_covered'=>'required|string|max:255','personnel_count'=>'nullable|integer|min:0','reported_by'=>'nullable|string|max:255','findings'=>'nullable|string']],
            'training'    => [TanodTraining::class,      ['title'=>'required|string|max:255','training_type'=>'required|string','training_date'=>'required|date','duration'=>'nullable|string|max:100','venue'=>'nullable|string|max:255','facilitator'=>'nullable|string|max:255','participants_count'=>'nullable|integer|min:0','notes'=>'nullable|string']],
            'health'      => [HealthRecord::class,       ['patient_name'=>'required|string|max:255','visit_date'=>'required|date','age'=>'nullable|integer','gender'=>'nullable|string','program'=>'nullable|string','address'=>'nullable|string','attended_by'=>'nullable|string|max:255','diagnosis'=>'nullable|string','notes'=>'nullable|string']],
            'clinic'      => [ClinicStaff::class,        ['full_name'=>'required|string|max:255','position'=>'nullable|string|max:100','specialization'=>'nullable|string|max:100','contact_number'=>'nullable|string|max:20','schedule'=>'nullable|string|max:255','status'=>'nullable|string']],
            'scholar'     => [Scholar::class,            ['full_name'=>'required|string|max:255','school'=>'required|string|max:255','course_grade_level'=>'nullable|string|max:100','year_level'=>'nullable|string|max:50','scholarship_type'=>'nullable|string|max:100','grant_amount'=>'nullable|numeric|min:0','status'=>'required|string','start_date'=>'nullable|date']],
            'project'     => [InfraProject::class,       ['project_name'=>'required|string|max:255','project_type'=>'nullable|string','location'=>'nullable|string|max:255','status'=>'required|string','budget'=>'nullable|numeric|min:0','actual_cost'=>'nullable|numeric|min:0','completion_percentage'=>'nullable|integer|min:0|max:100','start_date'=>'nullable|date','end_date'=>'nullable|date','remarks'=>'nullable|string']],
            'contract'    => [InfraContract::class,      ['title'=>'required|string|max:255','contractor'=>'nullable|string|max:255','contract_amount'=>'nullable|numeric|min:0','start_date'=>'nullable|date','end_date'=>'nullable|date','status'=>'nullable|string','remarks'=>'nullable|string']],
            'financial'   => [InfraFinancial::class,     ['title'=>'required|string|max:255','type'=>'required|string','fund_source'=>'nullable|string|max:255','amount'=>'nullable|numeric|min:0','date'=>'nullable|date','reference_number'=>'nullable|string|max:100']],
            'environment' => [EnvironmentProgram::class, ['program_name'=>'required|string|max:255','program_date'=>'required|date','program_type'=>'nullable|string','location'=>'nullable|string|max:255','status'=>'required|string','volunteers'=>'nullable|integer|min:0','trees_planted'=>'nullable|integer|min:0','waste_collected_kg'=>'nullable|numeric|min:0','notes'=>'nullable|string']],
            'sweeper'     => [StreetSweeper::class,      ['full_name'=>'required|string|max:255','area_assigned'=>'nullable|string|max:255','contact_number'=>'nullable|string|max:20','shift'=>'nullable|string','status'=>'nullable|string']],
            'beneficiary' => [LivelihoodBeneficiary::class,['full_name'=>'required|string|max:255','program'=>'nullable|string|max:255','assistance_type'=>'nullable|string|max:100','amount'=>'nullable|numeric|min:0','date_granted'=>'nullable|date','status'=>'nullable|string','remarks'=>'nullable|string']],
            'toda'        => [TodaVehicle::class,        ['operator_name'=>'required|string|max:255','plate_number'=>'nullable|string|max:20','vehicle_type'=>'nullable|string|max:50','toda_association'=>'nullable|string|max:255','contact_number'=>'nullable|string|max:20','status'=>'nullable|string']],
            'emergency'   => [EmergencyLog::class,       ['incident_type'=>'required|string|max:255','incident_date'=>'required|date','location'=>'nullable|string|max:255','description'=>'nullable|string','casualties'=>'nullable|integer|min:0','response_action'=>'nullable|string','logged_by'=>'nullable|string|max:255']],
            'evacuation'  => [EvacuationCenter::class,   ['center_name'=>'required|string|max:255','location'=>'nullable|string|max:255','capacity'=>'nullable|integer|min:0','contact_person'=>'nullable|string|max:255','contact_number'=>'nullable|string|max:20','status'=>'nullable|string']],
            'relief'      => [ReliefSupply::class,        ['item_name'=>'required|string|max:255','category'=>'required|in:Food,Non-food,Medicine,PPE,Equipment,Other','quantity'=>'required|integer|min:0','unit'=>'nullable|string|max:50','source'=>'nullable|string|max:255','date_received'=>'nullable|date','status'=>'required|in:Available,Distributed,Depleted','remarks'=>'nullable|string|max:500']],
        ];
        abort_unless(isset($map[$type]), 404);
        [$modelClass, $rules] = $map[$type];
        $record = $modelClass::findOrFail($id);
        $v = $request->validate($rules);
        $record->update($v);
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Record updated.', 'record' => $record->fresh()]);
        }
        return back()->with('success', 'Record updated.');
    }

    // -------------------------------------------------------
    // Medicine stock adjustment
    // -------------------------------------------------------
    public function adjustMedicine(Request $request, string $slug, int $id)
    {
        $medicine = MedicineInventory::findOrFail($id);

        $v = $request->validate([
            'adjustment_type'        => 'required|in:in,out,disposed',
            'quantity'               => 'required|integer|min:1',
            'reason'                 => 'nullable|string|max:255',
            'beneficiary_name'       => 'nullable|string|max:255',
            'beneficiary_resident_id'=> 'nullable|exists:residents,id',
            'purpose'                => 'nullable|string|max:255',
        ]);

        $qty        = (int) $v['quantity'];
        $stockBefore = $medicine->current_stock;

        if ($v['adjustment_type'] === 'in') {
            $medicine->current_stock += $qty;
        } else {
            $medicine->current_stock = max(0, $medicine->current_stock - $qty);
        }

        $medicine->save();

        // Auto-fill beneficiary_name from linked resident if not manually typed
        $beneficiaryName = $v['beneficiary_name'] ?? null;
        if (! $beneficiaryName && ! empty($v['beneficiary_resident_id'])) {
            $bRes = \App\Models\Resident::find($v['beneficiary_resident_id']);
            $beneficiaryName = $bRes?->full_name;
        }

        MedicineStockLog::create([
            'medicine_id'              => $medicine->id,
            'adjustment_type'          => $v['adjustment_type'],
            'quantity'                 => $qty,
            'stock_before'             => $stockBefore,
            'stock_after'              => $medicine->current_stock,
            'reason'                   => $v['reason'] ?? null,
            'performed_by'             => auth()->user()->name,
            'beneficiary_name'         => $beneficiaryName,
            'beneficiary_resident_id'  => $v['beneficiary_resident_id'] ?? null,
            'purpose'                  => $v['purpose'] ?? null,
        ]);

        $this->logActivity('adjusted stock', $medicine,
            ['current_stock' => $stockBefore],
            ['current_stock' => $medicine->current_stock]
        );

        $label = match($v['adjustment_type']) {
            'in'       => "Added {$qty} units",
            'out'      => "Dispensed {$qty} units",
            'disposed' => "Disposed {$qty} units",
        };

        if ($request->expectsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => "{$label} of {$medicine->medicine_name}.",
                'new_stock'     => $medicine->current_stock,
                'reorder_level' => $medicine->reorder_level,
                'is_low_stock'  => $medicine->isLowStock(),
                'log_entry'     => [
                    'type'        => $v['adjustment_type'],
                    'qty'         => $qty,
                    'before'      => $stockBefore,
                    'after'       => $medicine->current_stock,
                    'reason'      => $v['reason'] ?? null,
                    'beneficiary' => $beneficiaryName,
                    'purpose'     => $v['purpose'] ?? null,
                    'by'          => auth()->user()->name,
                    'date'        => now()->format('m/d/Y g:i A'),
                ],
            ]);
        }

        return back()->with('success', "{$label} of {$medicine->medicine_name}.")->withFragment('medicine-inventory');
    }

    // Partnership tab — generic, works for all committees
    public function storePartnership(Request $request, string $slug)
    {
        $validated = $request->validate([
            'partner_name'   => 'required|string|max:255',
            'partner_type'   => 'required|in:Government,NGO,Private,Community,Other',
            'mou_date'       => 'nullable|date',
            'validity_date'  => 'nullable|date|after_or_equal:mou_date',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'description'    => 'nullable|string',
            'file'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store("committees/{$slug}/partnerships", 'public');
        }
        unset($validated['file']);
        $validated['committee_slug'] = $slug;

        CommitteePartnership::create($validated);

        if ($request->expectsJson()) {
            $count = \App\Models\CommitteePartnership::where('committee_slug', $slug)->count();
            $p     = \App\Models\CommitteePartnership::where('committee_slug', $slug)->latest()->first();
            $rowHtml = '<tr data-pid="' . $p->id . '">'
                . '<td><div style="font-weight:600;color:var(--navy)">' . e($p->partner_name) . '</div>'
                . ($p->description ? '<div style="font-size:11px;color:var(--text-muted);margin-top:2px">' . \Illuminate\Support\Str::limit($p->description, 60) . '</div>' : '')
                . '</td>'
                . '<td><span class="badge badge-navy">' . e($p->partner_type) . '</span></td>'
                . '<td class="td-muted">' . ($p->mou_date?->format('m/d/Y') ?? '—') . '</td>'
                . '<td class="td-muted">' . ($p->validity_date?->format('m/d/Y') ?? '—') . '</td>'
                . '<td class="td-muted">' . e($p->contact_person ?? '—') . '</td>'
                . '<td class="td-muted">' . e($p->contact_number ?? '—') . '</td>'
                . '<td><span class="td-muted">—</span></td>'
                . '<td><button type="button" onclick="deletePartnership(' . $p->id . ', \'' . addslashes($p->partner_name) . '\', \'' . $slug . '\')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button></td>'
                . '</tr>';
            return response()->json([
                'success'   => true,
                'message'   => 'Partnership record added.',
                'tab_id'    => 'partnerships',
                'new_count' => $count,
                'row_html'  => $rowHtml,
            ]);
        }

        return back()->with('success', 'Partnership record added.')->withFragment('partnerships');
    }

    // -------------------------------------------------------
    // STORE methods for committee-specific tabs
    // -------------------------------------------------------
    public function storeSpecific(Request $request, string $slug)
    {
        $type = $request->input('specific_type');

        switch ($slug) {
            case 'peace-order':
                if ($type === 'bpso') {
                    $v = $request->validate([
                        'full_name'      => 'required|string|max:255',
                        'rank'           => 'nullable|string|max:100',
                        'badge_number'   => 'nullable|string|max:50',
                        'contact_number' => 'nullable|string|max:20',
                        'assignment'     => 'nullable|string|max:255',
                        'status'         => 'required|in:Active,Inactive,On Leave',
                    ]);
                    BpsoMember::create($v);
                } elseif ($type === 'training') {
                    $v = $request->validate([
                        'title'              => 'required|string|max:255',
                        'training_type'      => 'required|in:Training,Seminar,Workshop,Drill,Other',
                        'training_date'      => 'required|date',
                        'duration'           => 'nullable|string|max:100',
                        'venue'              => 'nullable|string|max:255',
                        'facilitator'        => 'nullable|string|max:255',
                        'participants_count' => 'nullable|integer|min:0',
                        'notes'              => 'nullable|string',
                        'file'               => 'nullable|file|max:10240',
                    ]);
                    if ($request->hasFile('file')) {
                        $v['file_path'] = $request->file('file')->store('committees/peace-order/trainings', 'public');
                    }
                    unset($v['file']);
                    TanodTraining::create($v);
                } else {
                    $v = $request->validate([
                        'patrol_date'     => 'required|date',
                        'shift'           => 'nullable|string|max:50',
                        'area_covered'    => 'required|string|max:255',
                        'personnel_count' => 'nullable|integer|min:0',
                        'findings'        => 'nullable|string',
                        'reported_by'     => 'nullable|string|max:255',
                    ]);
                    PatrolLog::create($v);
                }
                break;

            case 'health':
                if ($type === 'clinic-staff') {
                    $v = $request->validate([
                        'full_name'      => 'required|string|max:255',
                        'position'       => 'required|in:Doctor,Nurse,Midwife,BHW,Dentist,Other',
                        'specialization' => 'nullable|string|max:100',
                        'affiliation'    => 'nullable|string|max:255',
                        'contact_number' => 'nullable|string|max:20',
                        'schedule'       => 'nullable|string|max:255',
                        'status'         => 'required|in:Active,Inactive,On Leave',
                    ]);
                    ClinicStaff::create($v);
                } elseif ($type === 'medicine') {
                    $v = $request->validate([
                        'medicine_name'  => 'required|string|max:255',
                        'brand_name'     => 'nullable|string|max:255',
                        'generic_name'   => 'nullable|string|max:255',
                        'category'       => 'nullable|string|max:100',
                        'dosage_form'    => 'nullable|string|max:100',
                        'unit'           => 'required|string|max:50',
                        'current_stock'  => 'required|integer|min:0',
                        'reorder_level'  => 'required|integer|min:0',
                        'expiry_date'    => 'nullable|date',
                        'supplier'       => 'nullable|string|max:255',
                        'batch_number'   => 'nullable|string|max:100',
                        'barcode'        => 'nullable|string|max:100',
                    ]);
                    $medicine = MedicineInventory::create($v);
                    $this->logActivity('added', $medicine);

                    if ($medicine->current_stock > 0) {
                        MedicineStockLog::create([
                            'medicine_id'     => $medicine->id,
                            'adjustment_type' => 'in',
                            'quantity'        => $medicine->current_stock,
                            'stock_before'    => 0,
                            'stock_after'     => $medicine->current_stock,
                            'reason'          => 'Initial stock entry',
                            'performed_by'    => auth()->user()->name,
                        ]);
                    }
                } else {
                    $v = $request->validate([
                        'patient_name' => 'required|string|max:255',
                        'age'          => 'nullable|integer|min:0',
                        'gender'       => 'nullable|string|max:10',
                        'address'      => 'nullable|string|max:255',
                        'diagnosis'    => 'nullable|string|max:255',
                        'program'      => 'nullable|string|max:100',
                        'visit_date'   => 'required|date',
                        'attended_by'  => 'nullable|string|max:255',
                        'notes'        => 'nullable|string',
                    ]);
                    HealthRecord::create($v);
                }
                break;

            case 'education':
                $v = $request->validate([
                    'full_name' => 'required|string|max:255',
                    'school' => 'required|string|max:255',
                    'course_grade_level' => 'nullable|string|max:100',
                    'scholarship_type' => 'nullable|string|max:100',
                    'year_level' => 'nullable|string|max:50',
                    'grant_amount' => 'nullable|numeric|min:0',
                    'status' => 'required|in:Active,Graduated,Dropped,Suspended',
                    'start_date' => 'nullable|date',
                ]);
                Scholar::create($v);
                break;

            case 'infrastructure':
                if ($type === 'contract') {
                    $v = $request->validate([
                        'contract_number'  => 'nullable|string|max:100',
                        'contractor_name'  => 'required|string|max:255',
                        'scope_of_work'    => 'nullable|string',
                        'contract_amount'  => 'nullable|numeric|min:0',
                        'start_date'       => 'nullable|date',
                        'end_date'         => 'nullable|date',
                        'status'           => 'required|in:Pending,Active,Completed,Terminated',
                        'file'             => 'nullable|file|max:10240',
                    ]);
                    if ($request->hasFile('file')) {
                        $v['file_path'] = $request->file('file')->store('committees/infrastructure/contracts', 'public');
                    }
                    unset($v['file']);
                    InfraContract::create($v);
                } elseif ($type === 'financial') {
                    $v = $request->validate([
                        'title'            => 'required|string|max:255',
                        'type'             => 'required|in:Budget,Utilization,Liquidation',
                        'fund_source'      => 'nullable|string|max:255',
                        'amount'           => 'required|numeric|min:0',
                        'date'             => 'required|date',
                        'reference_number' => 'nullable|string|max:100',
                        'remarks'          => 'nullable|string',
                        'file'             => 'nullable|file|max:10240',
                    ]);
                    if ($request->hasFile('file')) {
                        $v['file_path'] = $request->file('file')->store('committees/infrastructure/financials', 'public');
                    }
                    unset($v['file']);
                    InfraFinancial::create($v);
                } else {
                    $v = $request->validate([
                        'project_name'          => 'required|string|max:255',
                        'project_type'          => 'nullable|string|max:100',
                        'location'              => 'nullable|string|max:255',
                        'budget'                => 'nullable|numeric|min:0',
                        'actual_cost'           => 'nullable|numeric|min:0',
                        'start_date'            => 'nullable|date',
                        'end_date'              => 'nullable|date',
                        'completion_percentage' => 'nullable|integer|min:0|max:100',
                        'status'                => 'required|in:Planned,Ongoing,Completed,On Hold,Cancelled',
                        'remarks'               => 'nullable|string',
                    ]);
                    InfraProject::create($v);
                }
                break;

            case 'environment':
                if ($type === 'sweeper') {
                    $v = $request->validate([
                        'full_name'      => 'required|string|max:255',
                        'assigned_zone'  => 'nullable|string|max:255',
                        'contact_number' => 'nullable|string|max:20',
                        'schedule'       => 'nullable|string|max:255',
                        'date_assigned'  => 'nullable|date',
                        'status'         => 'required|in:Active,Inactive,On Leave',
                    ]);
                    StreetSweeper::create($v);
                } else {
                    $v = $request->validate([
                        'program_name'       => 'required|string|max:255',
                        'program_type'       => 'nullable|string|max:100',
                        'program_date'       => 'required|date',
                        'location'           => 'nullable|string|max:255',
                        'volunteers'         => 'nullable|integer|min:0',
                        'trees_planted'      => 'nullable|integer|min:0',
                        'waste_collected_kg' => 'nullable|numeric|min:0',
                        'status'             => 'required|in:Planned,Completed,Cancelled',
                        'notes'              => 'nullable|string',
                    ]);
                    EnvironmentProgram::create($v);
                }
                break;

            case 'livelihood':
                $v = $request->validate([
                    'full_name' => 'required|string|max:255',
                    'address' => 'nullable|string|max:255',
                    'contact_number' => 'nullable|string|max:20',
                    'program_name' => 'required|string|max:255',
                    'program_type' => 'nullable|string|max:100',
                    'date_enrolled' => 'nullable|date',
                    'amount_received' => 'nullable|numeric|min:0',
                    'status' => 'required|in:Active,Completed,Dropped',
                    'remarks' => 'nullable|string',
                ]);
                LivelihoodBeneficiary::create($v);
                break;

            case 'transport':
                $v = $request->validate([
                    'operator_name' => 'required|string|max:255',
                    'driver_name' => 'nullable|string|max:255',
                    'vehicle_type' => 'nullable|string|max:50',
                    'plate_number' => 'nullable|string|max:20',
                    'toda_name' => 'nullable|string|max:100',
                    'route' => 'nullable|string|max:255',
                    'registration_date' => 'nullable|date',
                    'expiry_date' => 'nullable|date',
                    'status' => 'required|in:Active,Expired,Suspended',
                ]);
                TodaVehicle::create($v);
                break;

            case 'bdrrm':
                if ($type === 'evacuation') {
                    $v = $request->validate([
                        'center_name'       => 'required|string|max:255',
                        'location'          => 'required|string|max:255',
                        'capacity'          => 'nullable|integer|min:0',
                        'current_occupancy' => 'nullable|integer|min:0',
                        'status'            => 'required|in:Available,Active,Full,Closed',
                        'contact_person'    => 'nullable|string|max:255',
                        'contact_number'    => 'nullable|string|max:20',
                        'facilities'        => 'nullable|string',
                    ]);
                    EvacuationCenter::create($v);
                } elseif ($type === 'relief') {
                    $v = $request->validate([
                        'item_name'     => 'required|string|max:255',
                        'category'      => 'required|in:Food,Non-food,Medicine,PPE,Equipment,Other',
                        'quantity'      => 'required|integer|min:0',
                        'unit'          => 'nullable|string|max:50',
                        'source'        => 'nullable|string|max:255',
                        'date_received' => 'nullable|date',
                        'status'        => 'required|in:Available,Distributed,Depleted',
                        'remarks'       => 'nullable|string',
                    ]);
                    ReliefSupply::create($v);
                } else {
                    $v = $request->validate([
                        'incident_type'     => 'required|string|max:100',
                        'incident_date'     => 'required|date',
                        'location'          => 'required|string|max:255',
                        'affected_families' => 'nullable|integer|min:0',
                        'affected_persons'  => 'nullable|integer|min:0',
                        'description'       => 'nullable|string',
                        'response_actions'  => 'nullable|string',
                        'reported_by'       => 'nullable|string|max:255',
                        'status'            => 'required|in:Active,Resolved,Monitoring',
                    ]);
                    EmergencyLog::create($v);
                }
                break;
        }

        if ($request->expectsJson()) {
            $tabMap = [
                'bpso'        => 'bpso',
                'patrol'      => 'patrol',
                'training'    => 'training',
                'health'      => 'health-records',
                'clinic-staff'=> 'clinic-staff',
                'medicine'    => 'medicine-inventory',
                'scholar'     => 'scholars',
                'project'     => 'projects',
                'contract'    => 'contracts',
                'financial'   => 'financials',
                'environment' => 'env-programs',
                'sweeper'     => 'sweepers',
                'beneficiary' => 'beneficiaries',
                'toda'        => 'toda',
                'emergency'   => 'emergency',
                'evacuation'  => 'evacuation',
                'relief'      => 'relief-supplies',
            ];
            return response()->json([
                'success'  => true,
                'message'  => 'Record saved successfully.',
                'row_html' => null,
                'tab_id'   => $tabMap[$type] ?? null,
                'reload'   => true,
            ]);
        }

        return back()->with('success', 'Record saved successfully.');
    }
}
