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
    'App\Models\Resident'    => ['label' => 'Residents',  'icon' => 'fa-users',        'color' => '#1d76db', 'slug' => 'residents'],
    'App\Models\Household'   => ['label' => 'Households', 'icon' => 'fa-house',        'color' => '#5319e7', 'slug' => 'households'],
    'App\Models\Document'    => ['label' => 'Documents',  'icon' => 'fa-file-alt',     'color' => '#006b75', 'slug' => 'documents'],
    'App\Models\BlotterCase' => ['label' => 'Blotter',    'icon' => 'fa-gavel',        'color' => '#e11d48', 'slug' => 'blotter'],
    'App\Models\Business'    => ['label' => 'Businesses', 'icon' => 'fa-store',        'color' => '#f97316', 'slug' => 'businesses'],
    'App\Models\Official'    => ['label' => 'Officials',  'icon' => 'fa-user-tie',     'color' => '#7c3aed', 'slug' => 'officials'],
    'App\Models\User'        => ['label' => 'Users',      'icon' => 'fa-user-shield',  'color' => '#9333ea', 'slug' => 'users'],
    'App\Models\Purok'       => ['label' => 'Puroks',     'icon' => 'fa-location-dot', 'color' => '#0891b2', 'slug' => 'puroks'],
];
$skipFields = ['created_at', 'updated_at', 'remember_token', 'password', 'deleted_at'];
$routeMap = [
    'App\Models\Resident'    => 'residents.show',
    'App\Models\Household'   => 'households.show',
    'App\Models\Document'    => 'documents.show',
    'App\Models\BlotterCase' => 'blotter.show',
    'App\Models\Business'    => 'businesses.show',
    'App\Models\Official'    => 'officials.edit',
];
@endphp

{{-- MONTHLY TREND + STATS --}}
<div class="grid-4 mb-6">
    <div class="stat-card" style="padding:16px">
        <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.08);color:var(--navy);font-size:16px"><i class="fas fa-calendar-day"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:24px">{{ number_format($todayCount) }}</div>
            <div class="stat-label">Today</div>
        </div>
    </div>
    <div class="stat-card" style="padding:16px">
        <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.06);color:var(--navy-mid);font-size:16px"><i class="fas fa-calendar-week"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:24px">{{ number_format($thisWeekCount) }}</div>
            <div class="stat-label">This Week</div>
        </div>
    </div>
    <div class="stat-card" style="padding:16px">
        <div class="stat-icon" style="width:40px;height:40px;background:rgba(200,134,26,0.1);color:var(--gold);font-size:16px"><i class="fas fa-calendar"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:24px">{{ number_format($thisMonthCount) }}</div>
            <div class="stat-label">This Month</div>
        </div>
    </div>
    <div class="stat-card" style="padding:16px">
        <div class="stat-icon" style="width:40px;height:40px;background:rgba(22,101,52,0.08);color:#16a34a;font-size:16px"><i class="fas fa-clock-rotate-left"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:24px">{{ number_format($actionTotals['created'] + $actionTotals['updated'] + $actionTotals['deleted']) }}</div>
            <div class="stat-label">Total Logs</div>
        </div>
    </div>
</div>

{{-- Monthly Trend Full Width --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-line"></i> Monthly Activity Trend — Last 12 Months</span>
    </div>
    <div class="card-body">
        <canvas id="monthlyChart" height="80"></canvas>
    </div>
</div>

{{-- Weekly Bar Chart --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-bar"></i> Activity This Week</span>
        <div style="display:flex;gap:14px;font-size:11px;color:var(--text-muted)">
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#16a34a;display:inline-block"></span>Created</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#f59e0b;display:inline-block"></span>Updated</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#ef4444;display:inline-block"></span>Deleted</span>
        </div>
    </div>
    <div class="card-body">
        <canvas id="weeklyChart" height="80"></canvas>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="{{ route('activity-log.index') }}">
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label">Module</label>
                    <select name="module" class="form-control" onchange="this.form.submit()">
                        <option value="">All Modules</option>
                        @foreach($moduleMap as $info)
                        <option value="{{ $info['slug'] }}" {{ request('module') === $info['slug'] ? 'selected' : '' }}>{{ $info['label'] }}</option>
                        @endforeach
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
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('activity-log.index') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i> Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Module Filter Pills --}}
<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px" id="module-pills">
    <a href="{{ route('activity-log.index') }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:99px;border:1.5px solid {{ !request('module') ? 'var(--navy)' : 'var(--border)' }};background:{{ !request('module') ? 'var(--navy)' : 'var(--surface)' }};color:{{ !request('module') ? '#fff' : 'var(--text-muted)' }};font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas fa-clock-rotate-left" style="font-size:11px"></i> All
    </a>
    @foreach($moduleMap as $class => $info)
    @php $count = $moduleCounts[$class] ?? 0; @endphp
    @if($count > 0)
    <a href="{{ route('activity-log.index', ['module' => $info['slug']]) }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:99px;border:1.5px solid {{ request('module') === $info['slug'] ? $info['color'] : 'var(--border)' }};background:{{ request('module') === $info['slug'] ? $info['color'].'18' : 'var(--surface)' }};color:{{ request('module') === $info['slug'] ? $info['color'] : 'var(--text-muted)' }};font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas {{ $info['icon'] }}" style="font-size:11px"></i>
        {{ $info['label'] }}
        <span style="background:{{ request('module') === $info['slug'] ? $info['color'] : 'var(--surface3)' }};color:{{ request('module') === $info['slug'] ? '#fff' : 'var(--text-muted)' }};border-radius:99px;padding:0 6px;font-size:10px">{{ $count }}</span>
    </a>
    @endif
    @endforeach
</div>


{{-- Activity Feed --}}
<div class="card" id="activity-feed">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Activity Feed</span>
        <span style="font-size:12px;color:var(--text-muted)">{{ $query->total() }} entries · refreshes every 60s</span>
    </div>

    @forelse($query as $log)
    @php
        $module = $moduleMap[$log->loggable_type] ?? ['label' => 'Record', 'icon' => 'fa-circle', 'color' => '#9ca3af', 'slug' => ''];
        $actionLabel = match($log->action) { 'created' => 'created a new', 'deleted' => 'deleted a', default => 'updated a' };
        $actionColor = match($log->action) { 'created' => '#166534', 'deleted' => '#991b1b', default => '#92400e' };
        $actionBg    = match($log->action) { 'created' => '#dcfce7', 'deleted' => '#fee2e2', default => '#fef3c7' };
        $dateStr = $log->created_at->isToday() ? 'Today' : ($log->created_at->isYesterday() ? 'Yesterday' : $log->created_at->format('M d, Y'));
        $changes = collect($log->changes ?? [])->filter(fn($v, $k) => !in_array($k, $skipFields))->take(4);
        $routeName = $routeMap[$log->loggable_type] ?? null;
    @endphp
    <div style="display:flex;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);align-items:flex-start">
        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0;margin-top:1px">
            {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
        </div>
        <div style="flex:1;min-width:0">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:6px">
                <span style="font-size:13.5px;font-weight:600;color:var(--text)">{{ $log->user->name ?? 'Unknown' }}</span>
                <span class="badge badge-navy" style="font-size:10px">{{ $log->user->role ?? 'Staff' }}</span>
                <span style="display:inline-flex;align-items:center;padding:1px 8px;border-radius:99px;font-size:10px;font-weight:700;background:{{ $actionBg }};color:{{ $actionColor }}">
                    {{ strtoupper($log->action) }}
                </span>
                <span style="font-size:12px;font-weight:600;color:{{ $module['color'] }}">
                    <i class="fas {{ $module['icon'] }}" style="font-size:10px"></i> {{ $module['label'] }}
                </span>
                <span style="font-size:11px;color:var(--text-subtle)">#{{ $log->loggable_id }}</span>
            </div>
            @if($changes->count() > 0 && $log->action === 'updated')
            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px">
                @foreach($changes as $field => $change)
                @php
                    $oldVal = $change['old'] ?? null;
                    $newVal = $change['new'] ?? null;
                    $formatVal = function($v) {
                        if (is_null($v) || $v === '') return '—';
                        if (is_array($v)) return '[file]';
                        if (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}/', $v)) {
                            try { return \Carbon\Carbon::parse($v)->format('M d, Y'); } catch (\Exception $e) {}
                        }
                        return \Illuminate\Support\Str::limit((string)$v, 24);
                    };
                    $fieldLabel = ucwords(str_replace('_', ' ', $field));
                @endphp
                <div style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;font-size:11.5px">
                    <span style="font-weight:600;color:var(--text-muted)">{{ $fieldLabel }}:</span>
                    <span style="color:var(--text-subtle);text-decoration:line-through">{{ $formatVal($oldVal) }}</span>
                    <i class="fas fa-arrow-right" style="font-size:8px;color:var(--text-subtle)"></i>
                    <span style="color:var(--navy);font-weight:500">{{ $formatVal($newVal) }}</span>
                </div>
                @endforeach
                @if(count($log->changes ?? []) > 4)
                <span style="font-size:11px;color:var(--text-subtle);padding:3px 0">+{{ count($log->changes) - 4 }} more</span>
                @endif
            </div>
            @endif
            <div style="font-size:11px;color:var(--text-subtle)">
                <i class="fas fa-clock" style="font-size:9px;margin-right:3px"></i>
                {{ $dateStr }} at {{ $log->created_at->format('h:i A') }}
                <span style="margin:0 5px">·</span>{{ $log->created_at->diffForHumans() }}
            </div>
        </div>
        @if($routeName && $log->action !== 'deleted')
        <a href="{{ route($routeName, $log->loggable_id) }}" class="btn btn-secondary btn-sm btn-icon" title="View record" style="flex-shrink:0">
            <i class="fas fa-eye"></i>
        </a>
        @endif
    </div>
    @empty
    <div class="empty-state"><i class="fas fa-clock-rotate-left"></i><p>No activity logs found.</p></div>
    @endforelse

    @if($query->hasPages())
    <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">Showing {{ $query->firstItem() }}–{{ $query->lastItem() }} of {{ number_format($query->total()) }}</span>
        {{ $query->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Weekly stacked bar chart
const weeklyData = @json($weeklyData);
new Chart(document.getElementById('weeklyChart'), {
    type: 'bar',
    data: {
        labels: weeklyData.map(d => d.label + '\n' + d.date),
        datasets: [
            {
                label: 'Created',
                data: weeklyData.map(d => d.created),
                backgroundColor: '#16a34a',
                borderRadius: 3,
            },
            {
                label: 'Updated',
                data: weeklyData.map(d => d.updated),
                backgroundColor: '#f59e0b',
                borderRadius: 3,
            },
            {
                label: 'Deleted',
                data: weeklyData.map(d => d.deleted),
                backgroundColor: '#ef4444',
                borderRadius: 3,
            },
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                stacked: true,
                ticks: { color: '#9CA3AF', font: { size: 11 } },
                grid: { display: false }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 },
                grid: { color: '#E5E7EB' }
            }
        }
    }
});

// Monthly line chart
const monthlyData = @json($monthlyData);
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: monthlyData.map(d => d.label + ' ' + d.year),
        datasets: [{
            label: 'Total Activity',
            data: monthlyData.map(d => d.total),
            borderColor: '#0D2144',
            backgroundColor: 'rgba(13,33,68,0.06)',
            borderWidth: 2,
            pointBackgroundColor: '#C8861A',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            tension: 0.3,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 11 } }, grid: { color: '#E5E7EB' } },
            y: { beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
        }
    }
});

// Auto-refresh every 60s
setTimeout(() => location.reload(), 60000);
</script>
@endpush