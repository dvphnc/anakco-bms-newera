@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Activity Log</h1>
        <p class="page-subtitle">Real-time record of all system changes</p>
    </div>
</div>

@php
$moduleMap = [
    'App\Models\Resident'   => ['label' => 'Residents',   'icon' => 'fa-users',       'color' => '#1d76db'],
    'App\Models\Household'  => ['label' => 'Households',  'icon' => 'fa-house',       'color' => '#5319e7'],
    'App\Models\Document'   => ['label' => 'Documents',   'icon' => 'fa-file-alt',    'color' => '#006b75'],
    'App\Models\BlotterCase'=> ['label' => 'Blotter',     'icon' => 'fa-gavel',       'color' => '#e11d48'],
    'App\Models\Business'   => ['label' => 'Businesses',  'icon' => 'fa-store',       'color' => '#f97316'],
    'App\Models\Official'   => ['label' => 'Officials',   'icon' => 'fa-user-tie',    'color' => '#7c3aed'],
    'App\Models\User'       => ['label' => 'Users',       'icon' => 'fa-user-shield', 'color' => '#9333ea'],
];

$actionColors = [
    'created' => ['bg' => 'rgba(22,163,74,0.1)',  'color' => '#166534', 'icon' => 'fa-plus-circle'],
    'updated' => ['bg' => 'rgba(217,119,6,0.1)',  'color' => '#92400e', 'icon' => 'fa-pen'],
    'deleted' => ['bg' => 'rgba(220,38,38,0.1)',  'color' => '#991b1b', 'icon' => 'fa-trash'],
];
@endphp

{{-- Module Summary Cards --}}
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px">
    <a href="{{ route('activity-log.index') }}"
       class="stat-card" style="flex:1;min-width:120px;text-decoration:none;{{ !request('module') ? 'border-color:var(--navy);box-shadow:0 0 0 2px var(--navy-pale)' : '' }}">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-clock-rotate-left"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:20px">{{ number_format(\App\Models\ActivityLog::count()) }}</div>
            <div class="stat-label">All Activity</div>
        </div>
    </a>
    @foreach($moduleMap as $class => $info)
    @php $count = $moduleCounts[$class] ?? 0; @endphp
    @if($count > 0)
    <a href="{{ route('activity-log.index', ['module' => strtolower(class_basename($class) === 'BlotterCase' ? 'blotter' : strtolower(class_basename($class)) . 's')]) }}"
       class="stat-card" style="flex:1;min-width:120px;text-decoration:none;{{ request('module') === strtolower(class_basename($class) === 'BlotterCase' ? 'blotter' : strtolower(class_basename($class)) . 's') ? 'border-color:var(--navy);box-shadow:0 0 0 2px var(--navy-pale)' : '' }}">
        <div class="stat-icon" style="background:{{ $info['color'] }}18;color:{{ $info['color'] }}">
            <i class="fas {{ $info['icon'] }}"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:20px">{{ number_format($count) }}</div>
            <div class="stat-label">{{ $info['label'] }}</div>
        </div>
    </a>
    @endif
    @endforeach
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" action="{{ route('activity-log.index') }}">
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label">Module</label>
                    <select name="module" class="form-control" onchange="this.form.submit()">
                        <option value="">All Modules</option>
                        <option value="residents"  {{ request('module') === 'residents'  ? 'selected' : '' }}>Residents</option>
                        <option value="households" {{ request('module') === 'households' ? 'selected' : '' }}>Households</option>
                        <option value="documents"  {{ request('module') === 'documents'  ? 'selected' : '' }}>Documents</option>
                        <option value="blotter"    {{ request('module') === 'blotter'    ? 'selected' : '' }}>Blotter</option>
                        <option value="businesses" {{ request('module') === 'businesses' ? 'selected' : '' }}>Businesses</option>
                        <option value="officials"  {{ request('module') === 'officials'  ? 'selected' : '' }}>Officials</option>
                        <option value="users"      {{ request('module') === 'users'      ? 'selected' : '' }}>Users</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Action</label>
                    <select name="action" class="form-control" onchange="this.form.submit()">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('activity-log.index') }}" class="btn btn-secondary">
                        <i class="fas fa-xmark"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Activity Feed --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">
            <i class="fas fa-clock-rotate-left"></i> Activity Feed
        </span>
        <span style="font-size:12px;color:var(--text-muted)">
            {{ $query->total() }} total entries · Auto-refreshes every 30s
        </span>
    </div>

    @forelse($query as $log)
    @php
        $module  = $moduleMap[$log->loggable_type] ?? ['label' => class_basename($log->loggable_type), 'icon' => 'fa-circle', 'color' => '#9ca3af'];
        $action  = $actionColors[$log->action] ?? ['bg' => 'rgba(156,163,175,0.1)', 'color' => '#6b7280', 'icon' => 'fa-circle'];
        $isToday = $log->created_at->isToday();
        $isYest  = $log->created_at->isYesterday();
        $dateStr = $isToday ? 'Today' : ($isYest ? 'Yesterday' : $log->created_at->format('M d, Y'));
    @endphp

    <div style="display:flex;gap:14px;padding:14px 20px;border-bottom:1px solid var(--border);align-items:flex-start;transition:background 0.15s"
         onmouseover="this.style.background='var(--navy-pale)'" onmouseout="this.style.background=''">

        {{-- User Avatar --}}
        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0;margin-top:2px">
            {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
        </div>

        {{-- Content --}}
        <div style="flex:1;min-width:0">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:4px">
                {{-- User --}}
                <span style="font-size:13.5px;font-weight:600;color:var(--text)">
                    {{ $log->user->name ?? 'Unknown' }}
                </span>
                {{-- Role --}}
                <span class="badge badge-navy" style="font-size:10px">
                    {{ $log->user->role ?? 'Staff' }}
                </span>
                {{-- Action badge --}}
                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:99px;font-size:10.5px;font-weight:600;background:{{ $action['bg'] }};color:{{ $action['color'] }}">
                    <i class="fas {{ $action['icon'] }}" style="font-size:9px"></i>
                    {{ ucfirst($log->action) }}
                </span>
                {{-- Module badge --}}
                <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:99px;font-size:10.5px;font-weight:600;background:{{ $module['color'] }}15;color:{{ $module['color'] }}">
                    <i class="fas {{ $module['icon'] }}" style="font-size:9px"></i>
                    {{ $module['label'] }}
                </span>
                {{-- Record ID --}}
                <span style="font-size:11px;color:var(--text-subtle)">#{{ $log->loggable_id }}</span>
            </div>

            {{-- Changes --}}
            @if($log->changes && count($log->changes) > 0)
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:6px">
                @foreach(array_slice($log->changes, 0, 5) as $field => $change)
                <div style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;font-size:11.5px">
                    <span style="font-weight:600;color:var(--text)">{{ ucwords(str_replace('_',' ',$field)) }}</span>
                    <span style="color:var(--text-subtle);text-decoration:line-through">
                        {{ is_array($change['old'] ?? null) ? '[file]' : \Illuminate\Support\Str::limit($change['old'] ?? '—', 20) }}
                    </span>
                    <i class="fas fa-arrow-right" style="font-size:8px;color:var(--text-subtle)"></i>
                    <span style="color:var(--navy);font-weight:500">
                        {{ is_array($change['new'] ?? null) ? '[file]' : \Illuminate\Support\Str::limit($change['new'] ?? '—', 20) }}
                    </span>
                </div>
                @endforeach
                @if(count($log->changes) > 5)
                <span style="font-size:11px;color:var(--text-subtle);padding:3px 0">+{{ count($log->changes) - 5 }} more</span>
                @endif
            </div>
            @endif

            {{-- Timestamp --}}
            <div style="font-size:11px;color:var(--text-subtle);margin-top:5px">
                <i class="fas fa-clock" style="font-size:9px"></i>
                {{ $dateStr }} at {{ $log->created_at->format('h:i:s A') }}
                <span style="margin-left:6px;color:var(--border2)">·</span>
                <span style="margin-left:6px">{{ $log->created_at->diffForHumans() }}</span>
            </div>
        </div>

        {{-- View Record Link --}}
        @php
            $routeMap = [
                'App\Models\Resident'    => 'residents.show',
                'App\Models\Household'   => 'households.show',
                'App\Models\Document'    => 'documents.show',
                'App\Models\BlotterCase' => 'blotter.show',
                'App\Models\Business'    => 'businesses.show',
                'App\Models\Official'    => 'officials.edit',
            ];
            $routeName = $routeMap[$log->loggable_type] ?? null;
            $recordExists = $routeName && \Illuminate\Support\Facades\Route::has($routeName);
        @endphp
        @if($recordExists && $log->action !== 'deleted')
        @try
        <a href="{{ route($routeName, $log->loggable_id) }}"
           class="btn btn-secondary btn-sm" style="flex-shrink:0;margin-top:2px" title="View record">
            <i class="fas fa-eye"></i>
        </a>
        @endtry
        @endif
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-clock-rotate-left"></i>
        <p>No activity logs found.</p>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($query->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing {{ $query->firstItem() }}–{{ $query->lastItem() }} of {{ number_format($query->total()) }}
        </span>
        {{ $query->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
// Auto-refresh every 30 seconds
setTimeout(() => location.reload(), 30000);

// Countdown timer
let seconds = 30;
const badge = document.querySelector('[data-refresh]');
setInterval(() => {
    seconds--;
    if (seconds <= 0) seconds = 30;
}, 1000);
</script>
@endpush