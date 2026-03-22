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
        <div class="stat-icon" style="background:{{ $committee['color'] }}15;color:{{ $committee['color'] }}">
            <i class="fas fa-folder-open"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $photos->count() + $reports->count() + $resolutions->count() + $otherRecords->count() }}</div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $activities->count() + $accomplishments->count() }}</div>
            <div class="stat-label">Activities</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($attendances->sum('total_attendees')) }}</div>
            <div class="stat-label">Total Attendees</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-boxes-stacked"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $inventory->count() }}</div>
            <div class="stat-label">Inventory Items</div>
        </div>
    </div>
</div>

{{-- TABS --}}
<div class="card">

    {{-- Tab Nav --}}
    <div style="display:flex;border-bottom:2px solid var(--border);padding:0 20px;gap:4px;overflow-x:auto">
        @php
            $tabs = [
                ['id' => 'records',        'label' => 'Records',        'icon' => 'fas fa-folder-open'],
                ['id' => 'activities',     'label' => 'Activities',     'icon' => 'fas fa-calendar-check'],
                ['id' => 'accomplishments','label' => 'Accomplishments','icon' => 'fas fa-trophy'],
                ['id' => 'attendance',     'label' => 'Attendance',     'icon' => 'fas fa-users'],
                ['id' => 'inventory',      'label' => 'Inventory',      'icon' => 'fas fa-boxes-stacked'],
            ];
        @endphp
        @foreach($tabs as $tab)
        <button class="tab-btn {{ $loop->first ? 'active' : '' }}"
                onclick="switchTab('{{ $tab['id'] }}')"
                id="tab-btn-{{ $tab['id'] }}">
            <i class="{{ $tab['icon'] }}" style="font-size:12px"></i>
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    {{-- =============================================
         TAB 1: RECORDS
    ============================================= --}}
    <div id="tab-records" class="tab-content active">

        {{-- Upload form --}}
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px">
                <i class="fas fa-upload" style="color:var(--gold);margin-right:6px"></i> Upload New Record
            </div>
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
                <div style="margin-top:12px">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>

        {{-- Photos --}}
        @if($photos->count())
        <div style="padding:16px 20px">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px">
                <i class="fas fa-images" style="color:var(--gold)"></i> Photos ({{ $photos->count() }})
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(130px,1fr));gap:10px">
                @foreach($photos as $photo)
                <div style="border-radius:var(--radius-sm);overflow:hidden;border:1px solid var(--border);background:var(--surface2)">
                    @if($photo->file_path)
                    <a href="{{ asset('storage/'.$photo->file_path) }}" target="_blank">
                        <img src="{{ asset('storage/'.$photo->file_path) }}" alt="{{ $photo->title }}"
                             style="width:100%;height:90px;object-fit:cover;display:block">
                    </a>
                    @else
                    <div style="height:90px;display:flex;align-items:center;justify-content:center;color:var(--text-subtle)">
                        <i class="fas fa-image" style="font-size:24px"></i>
                    </div>
                    @endif
                    <div style="padding:6px 8px">
                        <div style="font-size:11px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $photo->title }}</div>
                        <div style="font-size:10px;color:var(--text-subtle)">{{ $photo->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Documents (Reports, Resolutions, Other) --}}
        @php $docRecords = $reports->concat($resolutions)->concat($otherRecords)->sortByDesc('created_at'); @endphp
        @if($docRecords->count())
        <div style="border-top:1px solid var(--border)">
            <div style="padding:12px 20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">
                <i class="fas fa-file-alt" style="color:var(--gold)"></i> Documents & Files ({{ $docRecords->count() }})
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Uploaded</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($docRecords as $rec)
                    <tr>
                        <td style="font-weight:600;font-size:13px">{{ $rec->title }}</td>
                        <td><span class="badge badge-navy" style="font-size:10px">{{ $rec->record_type }}</span></td>
                        <td class="td-muted">{{ $rec->description ?? '—' }}</td>
                        <td class="td-muted">{{ $rec->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($rec->file_path)
                                <a href="{{ asset('storage/'.$rec->file_path) }}" target="_blank"
                                   class="btn btn-secondary btn-sm btn-icon" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                            @else
                                <span class="td-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($photos->count() === 0 && $docRecords->count() === 0)
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <p>No records uploaded yet.</p>
        </div>
        @endif

    </div>

    {{-- =============================================
         TAB 2: ACTIVITIES
    ============================================= --}}
    <div id="tab-activities" class="tab-content">

        {{-- Add Activity form --}}
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px">
                <i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log New Activity
            </div>
            <form method="POST" action="{{ route('committees.storeActivity', $committee['slug']) }}">
                @csrf
                <input type="hidden" name="activity_type" value="Activity">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Activity Title <span style="color:var(--crimson)">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Barangay Assembly" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date <span style="color:var(--crimson)">*</span></label>
                        <input type="date" name="activity_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Venue">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Participants</label>
                        <input type="number" name="participants_count" class="form-control" min="0" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            @foreach(['Planned','Ongoing','Completed','Cancelled'] as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:span 3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Brief description">
                    </div>
                </div>
                <div style="margin-top:12px">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Activity
                    </button>
                </div>
            </form>
        </div>

        {{-- Activities list --}}
        @if($activities->count())
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Participants</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $act)
                <tr>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $act->title }}</div>
                        @if($act->description)
                            <div class="td-muted">{{ $act->description }}</div>
                        @endif
                    </td>
                    <td class="td-muted">{{ \Carbon\Carbon::parse($act->activity_date)->format('M d, Y') }}</td>
                    <td class="td-muted">{{ $act->location ?? '—' }}</td>
                    <td style="font-weight:600;color:var(--navy)">{{ number_format($act->participants_count) }}</td>
                    <td>
                        @php
                            $sc = match($act->status) {
                                'Completed' => 'badge-green',
                                'Ongoing'   => 'badge-yellow',
                                'Cancelled' => 'badge-red',
                                default     => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $sc }}">{{ $act->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-check"></i>
            <p>No activities logged yet.</p>
        </div>
        @endif

    </div>

    {{-- =============================================
         TAB 3: ACCOMPLISHMENTS
    ============================================= --}}
    <div id="tab-accomplishments" class="tab-content">

        {{-- Add Accomplishment form --}}
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px">
                <i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Accomplishment
            </div>
            <form method="POST" action="{{ route('committees.storeActivity', $committee['slug']) }}">
                @csrf
                <input type="hidden" name="activity_type" value="Accomplishment">
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Accomplishment Title <span style="color:var(--crimson)">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Completed road repair" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date <span style="color:var(--crimson)">*</span></label>
                        <input type="date" name="activity_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="Where">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Beneficiaries</label>
                        <input type="number" name="participants_count" class="form-control" min="0" placeholder="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            @foreach(['Completed','Ongoing','Planned','Cancelled'] as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:span 3">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control" placeholder="Details">
                    </div>
                </div>
                <div style="margin-top:12px">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-trophy"></i> Add Accomplishment
                    </button>
                </div>
            </form>
        </div>

        @if($accomplishments->count())
        <div style="padding:16px 20px;display:flex;flex-direction:column;gap:12px">
            @foreach($accomplishments as $acc)
            <div style="padding:14px 16px;background:var(--surface2);border:1px solid var(--border);border-left:4px solid {{ $committee['color'] }};border-radius:var(--radius-sm)">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
                    <div>
                        <div style="font-weight:600;font-size:13.5px;color:var(--text);margin-bottom:3px">{{ $acc->title }}</div>
                        @if($acc->description)
                            <div class="td-muted" style="margin-bottom:6px">{{ $acc->description }}</div>
                        @endif
                        <div style="display:flex;gap:16px;flex-wrap:wrap">
                            <span style="font-size:11px;color:var(--text-subtle)">
                                <i class="fas fa-calendar-alt" style="margin-right:4px"></i>
                                {{ \Carbon\Carbon::parse($acc->activity_date)->format('M d, Y') }}
                            </span>
                            @if($acc->location)
                            <span style="font-size:11px;color:var(--text-subtle)">
                                <i class="fas fa-location-dot" style="margin-right:4px"></i>{{ $acc->location }}
                            </span>
                            @endif
                            @if($acc->participants_count)
                            <span style="font-size:11px;color:var(--text-subtle)">
                                <i class="fas fa-users" style="margin-right:4px"></i>{{ number_format($acc->participants_count) }} beneficiaries
                            </span>
                            @endif
                        </div>
                    </div>
                    @php
                        $sc = match($acc->status) {
                            'Completed' => 'badge-green',
                            'Ongoing'   => 'badge-yellow',
                            'Cancelled' => 'badge-red',
                            default     => 'badge-gray'
                        };
                    @endphp
                    <span class="badge {{ $sc }}">{{ $acc->status }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-trophy"></i>
            <p>No accomplishments logged yet.</p>
        </div>
        @endif

    </div>

    {{-- =============================================
         TAB 4: ATTENDANCE
    ============================================= --}}
    <div id="tab-attendance" class="tab-content">

        {{-- Add Attendance form --}}
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px">
                <i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Record Attendance
            </div>
            <form method="POST" action="{{ route('committees.storeAttendance', $committee['slug']) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Event / Meeting Name <span style="color:var(--crimson)">*</span></label>
                        <input type="text" name="event_name" class="form-control" placeholder="e.g. Monthly Meeting" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date <span style="color:var(--crimson)">*</span></label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Venue</label>
                        <input type="text" name="venue" class="form-control" placeholder="Location">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Attendees <span style="color:var(--crimson)">*</span></label>
                        <input type="number" name="total_attendees" class="form-control" min="0" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Attendance Sheet (PDF/Image)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="form-group" style="grid-column:span 3">
                        <label class="form-label">Notes</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional notes">
                    </div>
                </div>
                <div style="margin-top:12px">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-check"></i> Record
                    </button>
                </div>
            </form>
        </div>

        @if($attendances->count())
        <table>
            <thead>
                <tr>
                    <th>Event / Meeting</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th style="text-align:right">Attendees</th>
                    <th>Notes</th>
                    <th>Sheet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $att)
                <tr>
                    <td style="font-weight:600;font-size:13px">{{ $att->event_name }}</td>
                    <td class="td-muted">{{ \Carbon\Carbon::parse($att->event_date)->format('M d, Y') }}</td>
                    <td class="td-muted">{{ $att->venue ?? '—' }}</td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)">{{ number_format($att->total_attendees) }}</td>
                    <td class="td-muted">{{ $att->notes ?? '—' }}</td>
                    <td>
                        @if($att->file_path)
                            <a href="{{ asset('storage/'.$att->file_path) }}" target="_blank"
                               class="btn btn-secondary btn-sm btn-icon" title="View Sheet">
                                <i class="fas fa-eye"></i>
                            </a>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>No attendance records yet.</p>
        </div>
        @endif

    </div>

    {{-- =============================================
         TAB 5: INVENTORY
    ============================================= --}}
    <div id="tab-inventory" class="tab-content">

        {{-- Add Item form --}}
        <div style="padding:20px;border-bottom:1px solid var(--border);background:var(--surface2)">
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:12px">
                <i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Inventory Item
            </div>
            <form method="POST" action="{{ route('committees.storeInventory', $committee['slug']) }}">
                @csrf
                <div class="form-grid-3" style="gap:12px">
                    <div class="form-group" style="grid-column:span 2">
                        <label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label>
                        <input type="text" name="item_name" class="form-control" placeholder="e.g. Megaphone" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g. Equipment">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Quantity <span style="color:var(--crimson)">*</span></label>
                        <input type="number" name="quantity" class="form-control" min="0" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g. pcs, sets">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Condition <span style="color:var(--crimson)">*</span></label>
                        <select name="condition" class="form-control" required>
                            @foreach(['Good','Fair','Poor','For Disposal'] as $c)
                                <option value="{{ $c }}">{{ $c }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:span 3">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Optional notes">
                    </div>
                </div>
                <div style="margin-top:12px">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
            </form>
        </div>

        @if($inventory->count())
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th style="text-align:right">Qty</th>
                    <th>Unit</th>
                    <th>Condition</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventory as $item)
                <tr>
                    <td style="font-weight:600;font-size:13px">{{ $item->item_name }}</td>
                    <td class="td-muted">{{ $item->category ?? '—' }}</td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)">{{ number_format($item->quantity) }}</td>
                    <td class="td-muted">{{ $item->unit ?? '—' }}</td>
                    <td>
                        @php
                            $cc = match($item->condition) {
                                'Good'        => 'badge-green',
                                'Fair'        => 'badge-yellow',
                                'Poor'        => 'badge-red',
                                'For Disposal'=> 'badge-gray',
                                default       => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $cc }}">{{ $item->condition }}</span>
                    </td>
                    <td class="td-muted">{{ $item->remarks ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-boxes-stacked"></i>
            <p>No inventory items yet.</p>
        </div>
        @endif

    </div>

</div>{{-- end .card --}}

@endsection

@push('scripts')
<style>
.tab-btn {
    padding: 12px 16px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-muted);
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: -2px;
    transition: color 0.15s;
}
.tab-btn:hover { color: var(--navy); }
.tab-btn.active {
    color: var(--navy);
    border-bottom-color: var(--gold);
}
.tab-content { display: none; }
.tab-content.active { display: block; }
</style>

<script>
function switchTab(id) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + id).classList.add('active');
    document.getElementById('tab-btn-' + id).classList.add('active');
    // remember tab in URL hash
    history.replaceState(null, '', '#' + id);
}

// Restore tab from URL hash on load
document.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.replace('#', '');
    const valid = ['records','activities','accomplishments','attendance','inventory'];
    if (hash && valid.includes(hash)) switchTab(hash);
});
</script>
@endpush
