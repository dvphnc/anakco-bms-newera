@extends('layouts.app')
@section('title', $committee['name'])
@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div style="display:flex;align-items:center;gap:14px">
        <div style="width:46px;height:46px;border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;background:{{ $committee['color'] }}18;color:{{ $committee['color'] }}">
            <i class="fas {{ $committee['icon'] }}"></i>
        </div>
        <div>
            <h1 class="page-title">{{ $committee['name'] }}</h1>
            <p class="page-subtitle">Chairperson: {{ $committee['chair'] }}</p>
        </div>
    </div>
</div>

{{-- QUICK STATS --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:{{ $committee['color'] }}15;color:{{ $committee['color'] }}"><i class="fas fa-folder-open"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ $photos->count() + $reports->count() + $resolutions->count() + $otherRecords->count() }}</div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ $activities->count() + $accomplishments->count() }}</div>
            <div class="stat-label">Activities</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($attendances->sum('total_attendees')) }}</div>
            <div class="stat-label">Total Attendees</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ $inventory->count() }}</div>
            <div class="stat-label">Inventory Items</div>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="card">
    <div style="display:flex;border-bottom:2px solid var(--border);padding:0 20px;gap:4px;overflow-x:auto">
        @php
            $tabs = [
                ['id'=>'records',         'label'=>'Records',        'icon'=>'fas fa-folder-open'],
                ['id'=>'activities',      'label'=>'Activities',     'icon'=>'fas fa-calendar-check'],
                ['id'=>'accomplishments', 'label'=>'Accomplishments','icon'=>'fas fa-trophy'],
                ['id'=>'attendance',      'label'=>'Attendance',     'icon'=>'fas fa-users'],
                ['id'=>'inventory',       'label'=>'Inventory',      'icon'=>'fas fa-boxes-stacked'],
            ];
            // Add committee-specific tabs
            $specificTabs = match($committee['slug']) {
                'peace-order'    => [['id'=>'bpso','label'=>'BPSO List','icon'=>'fas fa-shield-halved'],['id'=>'patrol','label'=>'Patrol Logs','icon'=>'fas fa-binoculars']],
                'health'         => [['id'=>'health-records','label'=>'Health Records','icon'=>'fas fa-notes-medical']],
                'education'      => [['id'=>'scholars','label'=>'Scholars','icon'=>'fas fa-graduation-cap']],
                'infrastructure' => [['id'=>'projects','label'=>'Projects','icon'=>'fas fa-hard-hat']],
                'environment'    => [['id'=>'env-programs','label'=>'Programs','icon'=>'fas fa-leaf']],
                'livelihood'     => [['id'=>'beneficiaries','label'=>'Beneficiaries','icon'=>'fas fa-hand-holding-heart']],
                'transport'      => [['id'=>'toda','label'=>'TODA Registry','icon'=>'fas fa-bus']],
                'bdrrm'          => [['id'=>'emergency','label'=>'Emergency Logs','icon'=>'fas fa-exclamation-triangle'],['id'=>'evacuation','label'=>'Evacuation Centers','icon'=>'fas fa-house-chimney-medical']],
                default          => [],
            };
            $allTabs = array_merge($tabs, $specificTabs);
        @endphp
        @foreach($allTabs as $tab)
        <button class="tab-btn {{ $loop->first ? 'active' : '' }}"
                onclick="switchTab('{{ $tab['id'] }}')"
                id="tab-btn-{{ $tab['id'] }}">
            <i class="{{ $tab['icon'] }}" style="font-size:12px"></i>
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    {{-- TAB 1: RECORDS --}}
    <div id="tab-records" class="tab-content active">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-upload" style="color:var(--gold);margin-right:6px"></i> Upload New Record</div>
            <form method="POST" action="{{ route('committees.storeRecord', $committee['slug']) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group">
                        <label class="form-label">Record Type <span style="color:var(--crimson)">*</span></label>
                        <select name="record_type" class="form-control" required>
                            @foreach(['Photo','Video','Report','Resolution','Certificate','Partnership','Other'] as $rt)
                                <option value="{{ $rt }}">{{ $rt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Title <span style="color:var(--crimson)">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Record title" required>
                    </div>
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Optional description">
                    </div>
                    <div class="form-group">
                        <label class="form-label">File</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button></div>
            </form>
        </div>
        @if($photos->count())
        <div style="padding:16px 20px">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-images" style="color:var(--gold)"></i> Photos ({{ $photos->count() }})</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px">
                @foreach($photos as $photo)
                <div style="border-radius:var(--radius-sm);overflow:hidden;border:1px solid var(--border)">
                    @if($photo->file_path)
                    <a href="{{ asset('storage/'.$photo->file_path) }}" target="_blank">
                        <img src="{{ asset('storage/'.$photo->file_path) }}" style="width:100%;height:90px;object-fit:cover;display:block">
                    </a>
                    @endif
                    <div style="padding:6px 8px"><div style="font-size:11px;font-weight:600;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $photo->title }}</div></div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @php $docRecords = $reports->concat($resolutions)->concat($otherRecords)->sortByDesc('created_at'); @endphp
        @if($docRecords->count())
        <div style="border-top:1px solid var(--border)">
            <div style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)"><i class="fas fa-file-alt" style="color:var(--gold)"></i> Documents ({{ $docRecords->count() }})</div>
            <table><thead><tr><th>Title</th><th>Type</th><th>Description</th><th>Uploaded</th><th>File</th></tr></thead>
            <tbody>
                @foreach($docRecords as $rec)
                <tr>
                    <td style="font-weight:600">{{ $rec->title }}</td>
                    <td><span class="badge badge-navy" style="font-size:10px">{{ $rec->record_type }}</span></td>
                    <td class="td-muted">{{ $rec->description ?? '—' }}</td>
                    <td class="td-muted">{{ $rec->created_at->format('M d, Y') }}</td>
                    <td>@if($rec->file_path)<a href="{{ asset('storage/'.$rec->file_path) }}" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a>@else<span class="td-muted">—</span>@endif</td>
                </tr>
                @endforeach
            </tbody></table>
        </div>
        @endif
        @if($photos->count() === 0 && $docRecords->count() === 0)
        <div class="empty-state"><i class="fas fa-folder-open"></i><p>No records uploaded yet.</p></div>
        @endif
    </div>

    {{-- TAB 2: ACTIVITIES --}}
    <div id="tab-activities" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log New Activity</div>
            <form method="POST" action="{{ route('committees.storeActivity', $committee['slug']) }}">
                @csrf
                <input type="hidden" name="activity_type" value="Activity">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Activity Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control" placeholder="e.g. Barangay Assembly" required></div>
                    <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="activity_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" class="form-control" placeholder="Venue"></div>
                    <div class="form-group"><label class="form-label">Participants</label><input type="number" name="participants_count" class="form-control" min="0" placeholder="0"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Planned','Ongoing','Completed','Cancelled'] as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" class="form-control" placeholder="Brief description"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Activity</button></div>
            </form>
        </div>
        @if($activities->count())
        <table><thead><tr><th>Title</th><th>Date</th><th>Location</th><th>Participants</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($activities as $act)
            <tr>
                <td><div style="font-weight:600">{{ $act->title }}</div>@if($act->description)<div class="td-muted">{{ $act->description }}</div>@endif</td>
                <td class="td-muted">{{ \Carbon\Carbon::parse($act->activity_date)->format('M d, Y') }}</td>
                <td class="td-muted">{{ $act->location ?? '—' }}</td>
                <td style="font-weight:600;color:var(--navy)">{{ number_format($act->participants_count) }}</td>
                <td><span class="badge {{ match($act->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red',default=>'badge-gray' } }}">{{ $act->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-calendar-check"></i><p>No activities logged yet.</p></div>@endif
    </div>

    {{-- TAB 3: ACCOMPLISHMENTS --}}
    <div id="tab-accomplishments" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Accomplishment</div>
            <form method="POST" action="{{ route('committees.storeActivity', $committee['slug']) }}">
                @csrf
                <input type="hidden" name="activity_type" value="Accomplishment">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="activity_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Beneficiaries</label><input type="number" name="participants_count" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Completed','Ongoing','Planned','Cancelled'] as $s)<option value="{{ $s }}">{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-trophy"></i> Add</button></div>
            </form>
        </div>
        @if($accomplishments->count())
        <div style="padding:16px 20px;display:flex;flex-direction:column;gap:12px">
            @foreach($accomplishments as $acc)
            <div style="padding:14px 16px;background:var(--surface2);border:1px solid var(--border);border-left:4px solid {{ $committee['color'] }};border-radius:var(--radius-sm)">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
                    <div>
                        <div style="font-weight:600;font-size:13.5px;margin-bottom:3px">{{ $acc->title }}</div>
                        @if($acc->description)<div class="td-muted" style="margin-bottom:6px">{{ $acc->description }}</div>@endif
                        <div style="display:flex;gap:16px;flex-wrap:wrap">
                            <span style="font-size:11px;color:var(--text-subtle)"><i class="fas fa-calendar-alt" style="margin-right:4px"></i>{{ \Carbon\Carbon::parse($acc->activity_date)->format('M d, Y') }}</span>
                            @if($acc->location)<span style="font-size:11px;color:var(--text-subtle)"><i class="fas fa-location-dot" style="margin-right:4px"></i>{{ $acc->location }}</span>@endif
                        </div>
                    </div>
                    <span class="badge {{ match($acc->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red',default=>'badge-gray' } }}">{{ $acc->status }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else<div class="empty-state"><i class="fas fa-trophy"></i><p>No accomplishments logged yet.</p></div>@endif
    </div>

    {{-- TAB 4: ATTENDANCE --}}
    <div id="tab-attendance" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Record Attendance</div>
            <form method="POST" action="{{ route('committees.storeAttendance', $committee['slug']) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Event Name <span style="color:var(--crimson)">*</span></label><input type="text" name="event_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="event_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Venue</label><input type="text" name="venue" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Total Attendees <span style="color:var(--crimson)">*</span></label><input type="number" name="total_attendees" class="form-control" min="0" required></div>
                    <div class="form-group"><label class="form-label">Attendance Sheet</label><input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Record</button></div>
            </form>
        </div>
        @if($attendances->count())
        <table><thead><tr><th>Event</th><th>Date</th><th>Venue</th><th style="text-align:right">Attendees</th><th>Notes</th><th>Sheet</th></tr></thead>
        <tbody>
            @foreach($attendances as $att)
            <tr>
                <td style="font-weight:600">{{ $att->event_name }}</td>
                <td class="td-muted">{{ \Carbon\Carbon::parse($att->event_date)->format('M d, Y') }}</td>
                <td class="td-muted">{{ $att->venue ?? '—' }}</td>
                <td style="text-align:right;font-weight:700;color:var(--navy)">{{ number_format($att->total_attendees) }}</td>
                <td class="td-muted">{{ $att->notes ?? '—' }}</td>
                <td>@if($att->file_path)<a href="{{ asset('storage/'.$att->file_path) }}" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a>@else<span class="td-muted">—</span>@endif</td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-users"></i><p>No attendance records yet.</p></div>@endif
    </div>

    {{-- TAB 5: INVENTORY --}}
    <div id="tab-inventory" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Inventory Item</div>
            <form method="POST" action="{{ route('committees.storeInventory', $committee['slug']) }}">
                @csrf
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label><input type="text" name="item_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Category</label><input type="text" name="category" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Quantity <span style="color:var(--crimson)">*</span></label><input type="number" name="quantity" class="form-control" min="0" required></div>
                    <div class="form-group"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" placeholder="pcs, sets"></div>
                    <div class="form-group"><label class="form-label">Condition <span style="color:var(--crimson)">*</span></label><select name="condition" class="form-control" required>@foreach(['Good','Fair','Poor','For Disposal'] as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Item</button></div>
            </form>
        </div>
        @if($inventory->count())
        <table><thead><tr><th>Item Name</th><th>Category</th><th style="text-align:right">Qty</th><th>Unit</th><th>Condition</th><th>Remarks</th></tr></thead>
        <tbody>
            @foreach($inventory as $item)
            <tr>
                <td style="font-weight:600">{{ $item->item_name }}</td>
                <td class="td-muted">{{ $item->category ?? '—' }}</td>
                <td style="text-align:right;font-weight:700;color:var(--navy)">{{ number_format($item->quantity) }}</td>
                <td class="td-muted">{{ $item->unit ?? '—' }}</td>
                <td><span class="badge {{ match($item->condition) { 'Good'=>'badge-green','Fair'=>'badge-yellow','Poor'=>'badge-red',default=>'badge-gray' } }}">{{ $item->condition }}</span></td>
                <td class="td-muted">{{ $item->remarks ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-boxes-stacked"></i><p>No inventory items yet.</p></div>@endif
    </div>

    {{-- =============================================
         COMMITTEE-SPECIFIC TABS
    ============================================= --}}

    {{-- PEACE & ORDER: BPSO List --}}
    @if($committee['slug'] === 'peace-order')
    <div id="tab-bpso" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add BPSO Member</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="bpso">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Rank</label><input type="text" name="rank" class="form-control" placeholder="e.g. Senior BPSO"></div>
                    <div class="form-group"><label class="form-label">Badge No.</label><input type="text" name="badge_number" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Contact</label><input type="text" name="contact_number" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Assignment</label><input type="text" name="assignment" class="form-control" placeholder="Area/Post"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option>Active</option><option>Inactive</option><option>On Leave</option></select></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Member</button></div>
            </form>
        </div>
        @if(isset($specificData['bpso']) && $specificData['bpso']->count())
        <table><thead><tr><th>Name</th><th>Rank</th><th>Badge No.</th><th>Contact</th><th>Assignment</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['bpso'] as $b)
            <tr>
                <td style="font-weight:600">{{ $b->full_name }}</td>
                <td class="td-muted">{{ $b->rank ?? '—' }}</td>
                <td class="td-mono">{{ $b->badge_number ?? '—' }}</td>
                <td class="td-muted">{{ $b->contact_number ?? '—' }}</td>
                <td class="td-muted">{{ $b->assignment ?? '—' }}</td>
                <td><span class="badge {{ $b->status === 'Active' ? 'badge-green' : ($b->status === 'On Leave' ? 'badge-yellow' : 'badge-gray') }}">{{ $b->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-shield-halved"></i><p>No BPSO members yet.</p></div>@endif
    </div>

    <div id="tab-patrol" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Patrol</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="patrol">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="patrol_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Shift</label><select name="shift" class="form-control"><option>Morning</option><option>Afternoon</option><option>Night</option></select></div>
                    <div class="form-group"><label class="form-label">Area Covered <span style="color:var(--crimson)">*</span></label><input type="text" name="area_covered" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Personnel</label><input type="number" name="personnel_count" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Reported By</label><input type="text" name="reported_by" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Findings</label><input type="text" name="findings" class="form-control" placeholder="Observations / Incidents"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Log Patrol</button></div>
            </form>
        </div>
        @if(isset($specificData['patrol_logs']) && $specificData['patrol_logs']->count())
        <table><thead><tr><th>Date</th><th>Shift</th><th>Area</th><th>Personnel</th><th>Findings</th><th>Reported By</th></tr></thead>
        <tbody>
            @foreach($specificData['patrol_logs'] as $p)
            <tr>
                <td class="td-muted">{{ $p->patrol_date->format('M d, Y') }}</td>
                <td><span class="badge badge-navy" style="font-size:10px">{{ $p->shift ?? '—' }}</span></td>
                <td style="font-weight:600">{{ $p->area_covered }}</td>
                <td style="font-weight:600;color:var(--navy)">{{ $p->personnel_count }}</td>
                <td class="td-muted">{{ $p->findings ?? '—' }}</td>
                <td class="td-muted">{{ $p->reported_by ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-binoculars"></i><p>No patrol logs yet.</p></div>@endif
    </div>
    @endif

    {{-- HEALTH: Health Records --}}
    @if($committee['slug'] === 'health')
    <div id="tab-health-records" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Health Record</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="health">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Patient Name <span style="color:var(--crimson)">*</span></label><input type="text" name="patient_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Visit Date <span style="color:var(--crimson)">*</span></label><input type="date" name="visit_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Age</label><input type="number" name="age" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Gender</label><select name="gender" class="form-control"><option value="">—</option><option>Male</option><option>Female</option></select></div>
                    <div class="form-group"><label class="form-label">Program</label><select name="program" class="form-control"><option value="">—</option>@foreach(['Vaccination','Prenatal','Family Planning','Dental','Medical Mission','Nutrition','Other'] as $p)<option>{{ $p }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Attended By</label><input type="text" name="attended_by" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Diagnosis / Notes</label><input type="text" name="diagnosis" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Remarks</label><input type="text" name="notes" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Record</button></div>
            </form>
        </div>
        @if(isset($specificData['health_records']) && $specificData['health_records']->count())
        <table><thead><tr><th>Patient</th><th>Age</th><th>Gender</th><th>Program</th><th>Diagnosis</th><th>Attended By</th><th>Date</th></tr></thead>
        <tbody>
            @foreach($specificData['health_records'] as $h)
            <tr>
                <td style="font-weight:600">{{ $h->patient_name }}</td>
                <td>{{ $h->age ?? '—' }}</td>
                <td>{{ $h->gender ?? '—' }}</td>
                <td><span class="badge badge-blue" style="font-size:10px">{{ $h->program ?? '—' }}</span></td>
                <td class="td-muted">{{ $h->diagnosis ?? '—' }}</td>
                <td class="td-muted">{{ $h->attended_by ?? '—' }}</td>
                <td class="td-muted">{{ $h->visit_date->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-notes-medical"></i><p>No health records yet.</p></div>@endif
    </div>
    @endif

    {{-- EDUCATION: Scholars --}}
    @if($committee['slug'] === 'education')
    <div id="tab-scholars" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Scholar</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="scholar">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">School <span style="color:var(--crimson)">*</span></label><input type="text" name="school" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Course / Grade Level</label><input type="text" name="course_grade_level" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Year Level</label><input type="text" name="year_level" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Scholarship Type</label><input type="text" name="scholarship_type" class="form-control" placeholder="e.g. CHED, Barangay"></div>
                    <div class="form-group"><label class="form-label">Grant Amount (₱)</label><input type="number" name="grant_amount" class="form-control" min="0" step="0.01"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option>Active</option><option>Graduated</option><option>Dropped</option><option>Suspended</option></select></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Scholar</button></div>
            </form>
        </div>
        @if(isset($specificData['scholars']) && $specificData['scholars']->count())
        <table><thead><tr><th>Name</th><th>School</th><th>Course/Level</th><th>Year</th><th>Scholarship</th><th>Grant</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['scholars'] as $s)
            <tr>
                <td style="font-weight:600">{{ $s->full_name }}</td>
                <td class="td-muted">{{ $s->school }}</td>
                <td class="td-muted">{{ $s->course_grade_level ?? '—' }}</td>
                <td class="td-muted">{{ $s->year_level ?? '—' }}</td>
                <td class="td-muted">{{ $s->scholarship_type ?? '—' }}</td>
                <td style="font-weight:600;color:var(--navy)">{{ $s->grant_amount ? '₱'.number_format($s->grant_amount,2) : '—' }}</td>
                <td><span class="badge {{ match($s->status) { 'Active'=>'badge-green','Graduated'=>'badge-blue','Dropped'=>'badge-red',default=>'badge-yellow' } }}">{{ $s->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-graduation-cap"></i><p>No scholars yet.</p></div>@endif
    </div>
    @endif

    {{-- INFRASTRUCTURE: Projects --}}
    @if($committee['slug'] === 'infrastructure')
    <div id="tab-projects" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Project</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="project">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Project Name <span style="color:var(--crimson)">*</span></label><input type="text" name="project_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Type</label><select name="project_type" class="form-control"><option value="">—</option>@foreach(['Road','Drainage','Building','Electrical','Water','Other'] as $t)<option>{{ $t }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Planned','Ongoing','Completed','On Hold','Cancelled'] as $s)<option>{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Budget (₱)</label><input type="number" name="budget" class="form-control" min="0" step="0.01"></div>
                    <div class="form-group"><label class="form-label">Actual Cost (₱)</label><input type="number" name="actual_cost" class="form-control" min="0" step="0.01"></div>
                    <div class="form-group"><label class="form-label">Completion %</label><input type="number" name="completion_percentage" class="form-control" min="0" max="100" value="0"></div>
                    <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
                    <div class="form-group"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Project</button></div>
            </form>
        </div>
        @if(isset($specificData['projects']) && $specificData['projects']->count())
        <table><thead><tr><th>Project</th><th>Type</th><th>Location</th><th>Budget</th><th>Progress</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['projects'] as $p)
            <tr>
                <td style="font-weight:600">{{ $p->project_name }}</td>
                <td class="td-muted">{{ $p->project_type ?? '—' }}</td>
                <td class="td-muted">{{ $p->location ?? '—' }}</td>
                <td style="font-weight:600;color:var(--navy)">{{ $p->budget ? '₱'.number_format($p->budget,2) : '—' }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px">
                        <div class="progress-bar-wrap" style="flex:1;min-width:60px"><div class="progress-bar" style="width:{{ $p->completion_percentage }}%;background:{{ $p->completion_percentage >= 100 ? '#16a34a' : 'var(--gold)' }}"></div></div>
                        <span style="font-size:11px;color:var(--text-muted)">{{ $p->completion_percentage }}%</span>
                    </div>
                </td>
                <td><span class="badge {{ match($p->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red','On Hold'=>'badge-orange',default=>'badge-gray' } }}">{{ $p->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-hard-hat"></i><p>No projects yet.</p></div>@endif
    </div>
    @endif

    {{-- ENVIRONMENT: Programs --}}
    @if($committee['slug'] === 'environment')
    <div id="tab-env-programs" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Program</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="environment">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Program Name <span style="color:var(--crimson)">*</span></label><input type="text" name="program_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="program_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Type</label><select name="program_type" class="form-control"><option value="">—</option>@foreach(['Clean-up Drive','Tree Planting','Waste Management','Coastal Clean-up','Anti-littering','Other'] as $t)<option>{{ $t }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Planned','Completed','Cancelled'] as $s)<option>{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Volunteers</label><input type="number" name="volunteers" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Trees Planted</label><input type="number" name="trees_planted" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Waste Collected (kg)</label><input type="number" name="waste_collected_kg" class="form-control" min="0" step="0.01"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Program</button></div>
            </form>
        </div>
        @if(isset($specificData['programs']) && $specificData['programs']->count())
        <table><thead><tr><th>Program</th><th>Type</th><th>Date</th><th>Location</th><th>Volunteers</th><th>Trees</th><th>Waste (kg)</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['programs'] as $p)
            <tr>
                <td style="font-weight:600">{{ $p->program_name }}</td>
                <td class="td-muted">{{ $p->program_type ?? '—' }}</td>
                <td class="td-muted">{{ $p->program_date->format('M d, Y') }}</td>
                <td class="td-muted">{{ $p->location ?? '—' }}</td>
                <td style="font-weight:600;color:var(--navy)">{{ $p->volunteers }}</td>
                <td style="font-weight:600;color:#16a34a">{{ $p->trees_planted ?? '—' }}</td>
                <td style="font-weight:600">{{ $p->waste_collected_kg ?? '—' }}</td>
                <td><span class="badge {{ match($p->status) { 'Completed'=>'badge-green','Planned'=>'badge-yellow',default=>'badge-red' } }}">{{ $p->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-leaf"></i><p>No programs yet.</p></div>@endif
    </div>
    @endif

    {{-- LIVELIHOOD: Beneficiaries --}}
    @if($committee['slug'] === 'livelihood')
    <div id="tab-beneficiaries" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Beneficiary</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="livelihood">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Date Enrolled</label><input type="date" name="date_enrolled" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Contact</label><input type="text" name="contact_number" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Program Name <span style="color:var(--crimson)">*</span></label><input type="text" name="program_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Program Type</label><select name="program_type" class="form-control"><option value="">—</option>@foreach(['Training','Livelihood Goods','Cash Grant','Loan','Skills Program','Other'] as $t)<option>{{ $t }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Amount Received (₱)</label><input type="number" name="amount_received" class="form-control" min="0" step="0.01"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Active','Completed','Dropped'] as $s)<option>{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Beneficiary</button></div>
            </form>
        </div>
        @if(isset($specificData['beneficiaries']) && $specificData['beneficiaries']->count())
        <table><thead><tr><th>Name</th><th>Program</th><th>Type</th><th>Amount</th><th>Enrolled</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['beneficiaries'] as $b)
            <tr>
                <td style="font-weight:600">{{ $b->full_name }}</td>
                <td class="td-muted">{{ $b->program_name }}</td>
                <td class="td-muted">{{ $b->program_type ?? '—' }}</td>
                <td style="font-weight:600;color:var(--navy)">{{ $b->amount_received ? '₱'.number_format($b->amount_received,2) : '—' }}</td>
                <td class="td-muted">{{ $b->date_enrolled?->format('M d, Y') ?? '—' }}</td>
                <td><span class="badge {{ match($b->status) { 'Active'=>'badge-green','Completed'=>'badge-blue',default=>'badge-red' } }}">{{ $b->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-hand-holding-heart"></i><p>No beneficiaries yet.</p></div>@endif
    </div>
    @endif

    {{-- TRANSPORT: TODA Registry --}}
    @if($committee['slug'] === 'transport')
    <div id="tab-toda" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Register Vehicle</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="toda">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Operator Name <span style="color:var(--crimson)">*</span></label><input type="text" name="operator_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Driver Name</label><input type="text" name="driver_name" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Vehicle Type</label><select name="vehicle_type" class="form-control"><option value="">—</option>@foreach(['Tricycle','Jeepney','E-bike','UV Express','Other'] as $t)<option>{{ $t }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Plate Number</label><input type="text" name="plate_number" class="form-control"></div>
                    <div class="form-group"><label class="form-label">TODA Name</label><input type="text" name="toda_name" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Route</label><input type="text" name="route" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Active','Expired','Suspended'] as $s)<option>{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Registration Date</label><input type="date" name="registration_date" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Expiry Date</label><input type="date" name="expiry_date" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Register</button></div>
            </form>
        </div>
        @if(isset($specificData['toda']) && $specificData['toda']->count())
        <table><thead><tr><th>Operator</th><th>Driver</th><th>Type</th><th>Plate</th><th>TODA</th><th>Route</th><th>Expiry</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['toda'] as $t)
            <tr>
                <td style="font-weight:600">{{ $t->operator_name }}</td>
                <td class="td-muted">{{ $t->driver_name ?? '—' }}</td>
                <td class="td-muted">{{ $t->vehicle_type ?? '—' }}</td>
                <td class="td-mono">{{ $t->plate_number ?? '—' }}</td>
                <td class="td-muted">{{ $t->toda_name ?? '—' }}</td>
                <td class="td-muted">{{ $t->route ?? '—' }}</td>
                <td class="td-muted {{ $t->expiry_date && $t->expiry_date->isPast() ? 'td-danger' : '' }}">{{ $t->expiry_date?->format('M d, Y') ?? '—' }}</td>
                <td><span class="badge {{ match($t->status) { 'Active'=>'badge-green','Expired'=>'badge-red',default=>'badge-yellow' } }}">{{ $t->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-bus"></i><p>No vehicles registered yet.</p></div>@endif
    </div>
    @endif

    {{-- BDRRM: Emergency Logs + Evacuation Centers --}}
    @if($committee['slug'] === 'bdrrm')
    <div id="tab-emergency" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Emergency</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="emergency">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group"><label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label><select name="incident_type" class="form-control" required>@foreach(['Flood','Fire','Earthquake','Typhoon','Landslide','Accident','Medical Emergency','Other'] as $t)<option>{{ $t }}</option>@endforeach</select></div>
                    <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="incident_date" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Active','Resolved','Monitoring'] as $s)<option>{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Location <span style="color:var(--crimson)">*</span></label><input type="text" name="location" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Reported By</label><input type="text" name="reported_by" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Affected Families</label><input type="number" name="affected_families" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Affected Persons</label><input type="number" name="affected_persons" class="form-control" min="0"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Response Actions</label><input type="text" name="response_actions" class="form-control"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Log Emergency</button></div>
            </form>
        </div>
        @if(isset($specificData['emergency_logs']) && $specificData['emergency_logs']->count())
        <table><thead><tr><th>Type</th><th>Date</th><th>Location</th><th>Families</th><th>Persons</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($specificData['emergency_logs'] as $e)
            <tr>
                <td style="font-weight:600">{{ $e->incident_type }}</td>
                <td class="td-muted">{{ $e->incident_date->format('M d, Y') }}</td>
                <td class="td-muted">{{ $e->location }}</td>
                <td style="font-weight:600;color:var(--crimson)">{{ number_format($e->affected_families) }}</td>
                <td style="font-weight:600;color:var(--crimson)">{{ number_format($e->affected_persons) }}</td>
                <td><span class="badge {{ match($e->status) { 'Resolved'=>'badge-green','Monitoring'=>'badge-yellow',default=>'badge-red' } }}">{{ $e->status }}</span></td>
            </tr>
            @endforeach
        </tbody></table>
        @else<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><p>No emergency logs yet.</p></div>@endif
    </div>

    <div id="tab-evacuation" class="tab-content">
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Evacuation Center</div>
            <form method="POST" action="{{ route('committees.storeSpecific', $committee['slug']) }}">
                @csrf <input type="hidden" name="specific_type" value="evacuation">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Center Name <span style="color:var(--crimson)">*</span></label><input type="text" name="center_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['Available','Active','Full','Closed'] as $s)<option>{{ $s }}</option>@endforeach</select></div>
                    <div class="form-group" style="grid-column:span 2"><label class="form-label">Location <span style="color:var(--crimson)">*</span></label><input type="text" name="location" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">Capacity</label><input type="number" name="capacity" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Current Occupancy</label><input type="number" name="current_occupancy" class="form-control" min="0"></div>
                    <div class="form-group"><label class="form-label">Contact Person</label><input type="text" name="contact_person" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                    <div class="form-group" style="grid-column:span 3"><label class="form-label">Facilities</label><input type="text" name="facilities" class="form-control" placeholder="e.g. Restrooms, Beds, Kitchen"></div>
                </div>
                <div style="margin-top:12px"><button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Center</button></div>
            </form>
        </div>
        @if(isset($specificData['evacuation_centers']) && $specificData['evacuation_centers']->count())
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:12px">
            @foreach($specificData['evacuation_centers'] as $ec)
            @php $occ = $ec->capacity > 0 ? round(($ec->current_occupancy / $ec->capacity) * 100) : 0; @endphp
            <div style="padding:16px;border:1px solid var(--border);border-radius:var(--radius);background:var(--surface2)">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                    <div>
                        <div style="font-weight:700;font-size:14px;color:var(--navy)">{{ $ec->center_name }}</div>
                        <div class="td-muted">{{ $ec->location }}</div>
                    </div>
                    <span class="badge {{ match($ec->status) { 'Available'=>'badge-green','Active'=>'badge-blue','Full'=>'badge-red',default=>'badge-gray' } }}">{{ $ec->status }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);margin-bottom:6px">
                    <span>Occupancy</span>
                    <span><strong>{{ $ec->current_occupancy }}</strong> / {{ $ec->capacity }}</span>
                </div>
                <div class="progress-bar-wrap"><div class="progress-bar" style="width:{{ $occ }}%;background:{{ $occ >= 90 ? 'var(--crimson)' : ($occ >= 70 ? 'var(--gold)' : '#16a34a') }}"></div></div>
                @if($ec->contact_person)
                <div style="font-size:11px;color:var(--text-muted);margin-top:8px"><i class="fas fa-user" style="margin-right:4px"></i>{{ $ec->contact_person }} — {{ $ec->contact_number ?? '—' }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @else<div class="empty-state"><i class="fas fa-house-chimney-medical"></i><p>No evacuation centers yet.</p></div>@endif
    </div>
    @endif

</div>{{-- end .card --}}

@endsection

@push('scripts')
<style>
.tab-btn { padding:12px 16px; font-size:12.5px; font-weight:600; color:var(--text-muted); background:none; border:none; border-bottom:2px solid transparent; cursor:pointer; white-space:nowrap; display:flex; align-items:center; gap:6px; margin-bottom:-2px; transition:color 0.15s; }
.tab-btn:hover { color:var(--navy); }
.tab-btn.active { color:var(--navy); border-bottom-color:var(--gold); }
.tab-content { display:none; }
.tab-content.active { display:block; }
.td-danger { color:var(--crimson) !important; font-weight:700; }
</style>
<script>
function switchTab(id) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    const content = document.getElementById('tab-' + id);
    const btn = document.getElementById('tab-btn-' + id);
    if (content) content.classList.add('active');
    if (btn) btn.classList.add('active');
    history.replaceState(null, '', '#' + id);
}
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.replace('#', '');
    if (hash && document.getElementById('tab-' + hash)) switchTab(hash);
});
</script>
@endpush