<?php

namespace App\Http\Controllers;

use App\Models\CommitteeRecord;
use App\Models\CommitteeActivity;
use App\Models\CommitteeAttendance;
use App\Models\CommitteeInventory;
use Illuminate\Http\Request;

class CommitteeController extends Controller
{
    // -------------------------------------------------------
    // Committee config — name, chair, icon, color per slug
    // -------------------------------------------------------
    private function getCommitteeConfig(string $slug): array
    {
        $committees = [
            'peace-order' => [
                'slug'  => 'peace-order',
                'name'  => 'Committee on Peace and Order',
                'chair' => 'Kap Robert',
                'icon'  => 'fa-shield-halved',
                'color' => '#f85149',
            ],
            'health' => [
                'slug'  => 'health',
                'name'  => 'Committee on Health',
                'chair' => 'Kgd Doc Twinkle',
                'icon'  => 'fa-heart-pulse',
                'color' => '#f78166',
            ],
            'education' => [
                'slug'  => 'education',
                'name'  => 'Committee on Education',
                'chair' => 'Kgd Fred Sicat',
                'icon'  => 'fa-graduation-cap',
                'color' => '#79c0ff',
            ],
            'infrastructure' => [
                'slug'  => 'infrastructure',
                'name'  => 'Committee on Infrastructure',
                'chair' => 'Kgd Euler',
                'icon'  => 'fa-road',
                'color' => '#e3b341',
            ],
            'environment' => [
                'slug'  => 'environment',
                'name'  => 'Committee on Environment',
                'chair' => 'Kgd Medel',
                'icon'  => 'fa-leaf',
                'color' => '#3fb950',
            ],
            'livelihood' => [
                'slug'  => 'livelihood',
                'name'  => 'Committee on Livelihood',
                'chair' => 'Kgd Fred',
                'icon'  => 'fa-briefcase',
                'color' => '#f0883e',
            ],
            'transport' => [
                'slug'  => 'transport',
                'name'  => 'Committee on Transport and Communication',
                'chair' => 'Kgd Bem',
                'icon'  => 'fa-bus',
                'color' => '#58a6ff',
            ],
            'bdrrm' => [
                'slug'  => 'bdrrm',
                'name'  => 'Barangay Disaster Risk Reduction & Management',
                'chair' => 'Kgd Joel',
                'icon'  => 'fa-triangle-exclamation',
                'color' => '#da3633',
            ],
        ];

        abort_unless(array_key_exists($slug, $committees), 404, 'Committee not found.');

        return $committees[$slug];
    }

    // -------------------------------------------------------
    // SHOW — View a committee page (tabbed)
    // -------------------------------------------------------
    public function show(string $slug)
    {
        $committee = $this->getCommitteeConfig($slug);

        // Load all data for this committee
        $photos = CommitteeRecord::forCommittee($slug)
            ->photos()
            ->latest()
            ->get();

        $reports = CommitteeRecord::forCommittee($slug)
            ->reports()
            ->latest()
            ->get();

        $resolutions = CommitteeRecord::forCommittee($slug)
            ->resolutions()
            ->latest()
            ->get();

        $otherRecords = CommitteeRecord::forCommittee($slug)
            ->whereNotIn('record_type', ['Photo', 'Report', 'Resolution'])
            ->latest()
            ->get();

        $activities = CommitteeActivity::forCommittee($slug)
            ->activities()
            ->latest()
            ->get();

        $accomplishments = CommitteeActivity::forCommittee($slug)
            ->accomplishments()
            ->latest()
            ->get();

        $attendances = CommitteeAttendance::forCommittee($slug)
            ->latest()
            ->get();

        $inventory = CommitteeInventory::forCommittee($slug)
            ->orderBy('category')
            ->orderBy('item_name')
            ->get();

        return view('committees.committees-show', compact(
            'committee',
            'photos',
            'reports',
            'resolutions',
            'otherRecords',
            'activities',
            'accomplishments',
            'attendances',
            'inventory'
        ));
    }

    // -------------------------------------------------------
    // STORE RECORD — Upload photo / file / document
    // -------------------------------------------------------
    public function storeRecord(Request $request, string $slug)
    {
        $this->getCommitteeConfig($slug); // validates slug

        $validated = $request->validate([
            'record_type' => 'required|in:Photo,Video,Report,Resolution,Certificate,Partnership,Other',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'file'        => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($request->hasFile('file')) {
            $file      = $request->file('file');
            $extension = $file->getClientOriginalExtension();

            $validated['file_path'] = $file->store("committees/{$slug}", 'public');
            $validated['file_type'] = $extension;
        }

        CommitteeRecord::create([
            'committee_slug' => $slug,
            'record_type'    => $validated['record_type'],
            'title'          => $validated['title'],
            'description'    => $validated['description'] ?? null,
            'file_path'      => $validated['file_path'] ?? null,
            'file_type'      => $validated['file_type'] ?? null,
            'uploaded_by'    => auth()->id(),
        ]);

        return redirect()
            ->route('committees.show', $slug)
            ->with('success', 'Record uploaded successfully.');
    }

    // -------------------------------------------------------
    // STORE ACTIVITY — Log activity or accomplishment
    // -------------------------------------------------------
    public function storeActivity(Request $request, string $slug)
    {
        $this->getCommitteeConfig($slug);

        $validated = $request->validate([
            'activity_type'      => 'required|in:Activity,Accomplishment',
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'activity_date'      => 'required|date',
            'location'           => 'nullable|string|max:255',
            'participants_count' => 'nullable|integer|min:0',
            'status'             => 'required|in:Planned,Ongoing,Completed,Cancelled',
        ]);

        CommitteeActivity::create([
            'committee_slug'     => $slug,
            'activity_type'      => $validated['activity_type'],
            'title'              => $validated['title'],
            'description'        => $validated['description'] ?? null,
            'activity_date'      => $validated['activity_date'],
            'location'           => $validated['location'] ?? null,
            'participants_count' => $validated['participants_count'] ?? 0,
            'status'             => $validated['status'],
            'logged_by'          => auth()->id(),
        ]);

        return redirect()
            ->route('committees.show', $slug)
            ->with('success', 'Activity logged successfully.');
    }

    // -------------------------------------------------------
    // STORE ATTENDANCE — Record attendance sheet
    // -------------------------------------------------------
    public function storeAttendance(Request $request, string $slug)
    {
        $this->getCommitteeConfig($slug);

        $validated = $request->validate([
            'event_name'      => 'required|string|max:255',
            'event_date'      => 'required|date',
            'venue'           => 'nullable|string|max:255',
            'total_attendees' => 'required|integer|min:0',
            'notes'           => 'nullable|string',
            'file'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')
                ->store("committees/{$slug}/attendance", 'public');
        }

        CommitteeAttendance::create([
            'committee_slug'  => $slug,
            'event_name'      => $validated['event_name'],
            'event_date'      => $validated['event_date'],
            'venue'           => $validated['venue'] ?? null,
            'total_attendees' => $validated['total_attendees'],
            'notes'           => $validated['notes'] ?? null,
            'file_path'       => $filePath,
            'recorded_by'     => auth()->id(),
        ]);

        return redirect()
            ->route('committees.show', $slug)
            ->with('success', 'Attendance recorded successfully.');
    }

    // -------------------------------------------------------
    // STORE INVENTORY — Add inventory item
    // -------------------------------------------------------
    public function storeInventory(Request $request, string $slug)
    {
        $this->getCommitteeConfig($slug);

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category'  => 'nullable|string|max:100',
            'quantity'  => 'required|integer|min:0',
            'unit'      => 'nullable|string|max:50',
            'condition' => 'required|in:Good,Fair,Poor,For Disposal',
            'remarks'   => 'nullable|string',
        ]);

        CommitteeInventory::create([
            'committee_slug' => $slug,
            'item_name'      => $validated['item_name'],
            'category'       => $validated['category'] ?? null,
            'quantity'       => $validated['quantity'],
            'unit'           => $validated['unit'] ?? null,
            'condition'      => $validated['condition'],
            'remarks'        => $validated['remarks'] ?? null,
            'recorded_by'    => auth()->id(),
        ]);

        return redirect()
            ->route('committees.show', $slug)
            ->with('success', 'Inventory item added successfully.');
    }
}