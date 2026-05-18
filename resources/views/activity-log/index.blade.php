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
@endphp

{{-- Charts Toggle --}}
<div style="margin-bottom:16px">
    <button onclick="toggleCharts()" id="charts-toggle-btn"
            style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 18px;min-height:44px;font-size:13px;font-weight:600;color:var(--text-muted);cursor:pointer;display:flex;align-items:center;gap:8px;font-family:'Poppins',sans-serif;transition:all 0.15s">
        <i class="fas fa-chart-line" style="color:var(--gold)"></i>
        <span id="charts-toggle-label">Show Charts</span>
        <i class="fas fa-chevron-down" id="charts-chevron" style="font-size:12px;transition:transform 0.2s"></i>
    </button>
</div>

<div id="charts-section" style="display:none">

{{-- MONTHLY TREND + STATS --}}
<div class="grid-4 mb-6">
    <a href="{{ route('activity-log.index', ['period' => 'today']) }}" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;{{ request('period') === 'today' ? 'border-color:var(--navy);box-shadow:0 0 0 3px var(--navy-pale)' : '' }}">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.08);color:var(--navy);font-size:16px"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px">{{ number_format($todayCount) }}</div>
                <div class="stat-label">Today</div>
            </div>
        </div>
    </a>
    <a href="{{ route('activity-log.index', ['period' => 'week']) }}" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;{{ request('period') === 'week' ? 'border-color:var(--navy);box-shadow:0 0 0 3px var(--navy-pale)' : '' }}">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.06);color:var(--navy-mid);font-size:16px"><i class="fas fa-calendar-week"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px">{{ number_format($thisWeekCount) }}</div>
                <div class="stat-label">This Week</div>
            </div>
        </div>
    </a>
    <a href="{{ route('activity-log.index', ['period' => 'month']) }}" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;{{ request('period') === 'month' ? 'border-color:var(--gold);box-shadow:0 0 0 3px var(--gold-glow)' : '' }}">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(200,134,26,0.1);color:var(--gold);font-size:16px"><i class="fas fa-calendar"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px">{{ number_format($thisMonthCount) }}</div>
                <div class="stat-label">This Month</div>
            </div>
        </div>
    </a>
    <a href="{{ route('activity-log.index') }}" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;{{ !request('period') && !request('module') && !request('action') ? 'border-color:var(--navy);box-shadow:0 0 0 3px var(--navy-pale)' : '' }}">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.06);color:var(--navy-mid);font-size:16px"><i class="fas fa-clock-rotate-left"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px">{{ number_format($actionTotals['created'] + $actionTotals['updated'] + $actionTotals['deleted']) }}</div>
                <div class="stat-label">All Logs</div>
            </div>
        </div>
    </a>
</div>

{{-- Monthly Trend Full Width --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-line"></i> Monthly Activity Trend — Last 12 Months</span>
    </div>
    <div class="card-body">
        <canvas id="monthlyChart" height="80"></canvas>
        @php
            $monthlyArr = collect($monthlyData ?? []);
            $peakMonth  = $monthlyArr->sortByDesc('total')->first();
            $latestMonth= $monthlyArr->last();
            $prevMonth  = $monthlyArr->count() > 1 ? $monthlyArr->reverse()->skip(1)->first() : null;
        @endphp
        @if($peakMonth)
        <div class="chart-insight" style="margin-top:14px">
            Peak month: <strong>{{ $peakMonth['label'] ?? '' }} {{ $peakMonth['year'] ?? '' }}</strong> with <strong>{{ number_format($peakMonth['total']) }}</strong> actions.
            @if($latestMonth && $prevMonth)
                This month so far: <strong>{{ number_format($latestMonth['total']) }}</strong>
                @if($latestMonth['total'] > $prevMonth['total'])
                    — <span style="color:#2e6b47"><i class="fas fa-arrow-up"></i> up from {{ $prevMonth['total'] }} last month.</span>
                @elseif($latestMonth['total'] < $prevMonth['total'])
                    — <span style="color:#8b2e2e"><i class="fas fa-arrow-down"></i> down from {{ $prevMonth['total'] }} last month.</span>
                @else
                    — same as last month.
                @endif
            @endif
        </div>
        @endif
    </div>
</div>

{{-- Weekly Bar Chart --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-bar"></i> Activity This Week</span>
        <div style="display:flex;gap:14px;font-size:13px;color:var(--text-muted)">
            <span style="display:flex;align-items:center;gap:5px"><span style="width:11px;height:11px;border-radius:2px;background:#4a8c5c;display:inline-block"></span>Created</span>
            <span style="display:flex;align-items:center;gap:5px"><span style="width:11px;height:11px;border-radius:2px;background:#C8861A;display:inline-block"></span>Updated</span>
            <span style="display:flex;align-items:center;gap:5px"><span style="width:11px;height:11px;border-radius:2px;background:#b04444;display:inline-block"></span>Deleted</span>
        </div>
    </div>
    <div class="card-body">
        <canvas id="weeklyChart" height="80"></canvas>
        @php
            $weeklyArr  = collect($weeklyData ?? []);
            $totalWeek  = $weeklyArr->sum('created') + $weeklyArr->sum('updated') + $weeklyArr->sum('deleted');
            $createdWk  = $weeklyArr->sum('created');
            $updatedWk  = $weeklyArr->sum('updated');
            $deletedWk  = $weeklyArr->sum('deleted');
        @endphp
        <div class="chart-insight" style="margin-top:14px">
            <strong>{{ number_format($totalWeek) }}</strong> total actions this week —
            <span style="color:#2e6b47"><strong>{{ $createdWk }}</strong> created</span>,
            <span style="color:#7a5200"><strong>{{ $updatedWk }}</strong> updated</span>,
            <span style="color:#8b2e2e"><strong>{{ $deletedWk }}</strong> deleted</span>.
        </div>
    </div>
</div>

</div>{{-- end charts-section --}}

{{-- Filters — always visible, no module dropdown (pills handle that) --}}
<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="{{ route('activity-log.index') }}" onsubmit="return false;">
            @if(request('module'))
                <input type="hidden" name="module" value="{{ request('module') }}">
            @endif
            @if(request('period'))
                <input type="hidden" name="period" value="{{ request('period') }}">
            @endif
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label">Action</label>
                    <select name="action" id="filterAction" class="form-control">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label class="form-label">User</label>
                    <select name="user_id" id="filterUser" class="form-control">
                        <option value="">All Users</option>
                        @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <a href="{{ route('activity-log.index') }}" class="btn btn-secondary" onclick="resetFilters(); return false;"><i class="fas fa-xmark"></i> Reset All</a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Module Filter Pills --}}
<div style="display:flex;gap:8px;flex-wrap:nowrap;overflow-x:auto;margin-bottom:20px;padding-bottom:4px" id="module-pills">
    <a href="{{ route('activity-log.index') }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:var(--radius-sm);border:1.5px solid {{ !request('module') ? 'var(--navy)' : 'var(--border)' }};background:{{ !request('module') ? 'var(--navy)' : 'var(--surface)' }};color:{{ !request('module') ? '#fff' : 'var(--text-muted)' }};font-size:13px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas fa-clock-rotate-left" style="font-size:12px"></i> All
    </a>
    @foreach($moduleMap as $class => $info)
    @php $count = $moduleCounts[$class] ?? 0; @endphp
    @if($count > 0)
    <a href="{{ route('activity-log.index', ['module' => $info['slug']]) }}"
       style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:var(--radius-sm);border:1.5px solid {{ request('module') === $info['slug'] ? $info['color'] : 'var(--border)' }};background:{{ request('module') === $info['slug'] ? $info['color'].'18' : 'var(--surface)' }};color:{{ request('module') === $info['slug'] ? $info['color'] : 'var(--text-muted)' }};font-size:13px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas {{ $info['icon'] }}" style="font-size:12px"></i>
        {{ $info['label'] }}
        <span style="background:{{ request('module') === $info['slug'] ? $info['color'] : 'var(--surface3)' }};color:{{ request('module') === $info['slug'] ? '#fff' : 'var(--text-muted)' }};border-radius:4px;padding:1px 6px;font-size:12px">{{ $count }}</span>
    </a>
    @endif
    @endforeach
</div>


{{-- Activity Feed --}}
<div class="card" id="activity-feed">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Activity Feed</span>
        <span id="feed-total" style="font-size:13px;color:var(--text-muted)">{{ number_format($query->total()) }} entries · refreshes every 60s</span>
    </div>
    <div id="feed-body">
        @include('activity-log._feed')
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const weeklyData  = @json($weeklyData);
const monthlyData = @json($monthlyData);
let chartsInitialized = false;

function initCharts() {
    if (chartsInitialized) return;
    chartsInitialized = true;

    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: weeklyData.map(d => d.label),
            datasets: [
                { label: 'Created', data: weeklyData.map(d => d.created), backgroundColor: '#4a8c5c', borderRadius: 3 },
                { label: 'Updated', data: weeklyData.map(d => d.updated), backgroundColor: '#C8861A', borderRadius: 3 },
                { label: 'Deleted', data: weeklyData.map(d => d.deleted), backgroundColor: '#b04444', borderRadius: 3 },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { stacked: true, ticks: { color: '#9CA3AF', font: { size: 12 } }, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 12 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
            }
        }
    });

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
                x: { ticks: { color: '#9CA3AF', font: { size: 12 } }, grid: { color: '#E5E7EB' } },
                y: { beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 12 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
            }
        }
    });
}

function toggleCharts() {
    const section  = document.getElementById('charts-section');
    const label    = document.getElementById('charts-toggle-label');
    const chevron  = document.getElementById('charts-chevron');
    const btn      = document.getElementById('charts-toggle-btn');
    const isHidden = section.style.display === 'none';
    section.style.display = isHidden ? 'block' : 'none';
    label.textContent       = isHidden ? 'Hide Charts' : 'Show Charts';
    chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0)';
    btn.style.color         = isHidden ? 'var(--navy)' : 'var(--text-muted)';
    localStorage.setItem('activityChartsOpen', isHidden ? '1' : '0');
    if (isHidden) initCharts();
}

// Restore chart state
if (localStorage.getItem('activityChartsOpen') === '1') toggleCharts();

// ── Filter state (seeded from current URL) ──────────────────────────────────
var _filterState = {
    module:  '{{ request("module") }}',
    action:  '{{ request("action") }}',
    user_id: '{{ request("user_id") }}',
    period:  '{{ request("period") }}',
    page:    '{{ request("page") }}'
};

function fetchFeed() {
    var params = new URLSearchParams();
    Object.keys(_filterState).forEach(function(k) {
        if (_filterState[k]) params.set(k, _filterState[k]);
    });
    var qs  = params.toString();
    var url = '{{ route("activity-log.index") }}' + (qs ? '?' + qs : '');
    history.pushState({}, '', url);

    var card = document.getElementById('activity-feed');
    card.style.opacity       = '0.55';
    card.style.pointerEvents = 'none';

    axios.get(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(res) {
            document.getElementById('feed-body').innerHTML = res.data.html;
            var el = document.getElementById('feed-total');
            if (el) el.textContent = res.data.total.toLocaleString() + ' entries · refreshes every 60s';
        })
        .catch(function() { location.href = url; })
        .finally(function() {
            card.style.opacity       = '1';
            card.style.pointerEvents = '';
        });
}

function resetFilters() {
    _filterState.action  = '';
    _filterState.user_id = '';
    _filterState.page    = '';
    $('#filterAction').val('').trigger('change.select2');
    $('#filterUser').val('').trigger('change.select2');
    fetchFeed();
}

// ── Select2 ──────────────────────────────────────────────────────────────────
$(function(){
    $('#filterAction').select2({ minimumResultsForSearch: -1, width: '100%' })
        .on('change', function() {
            _filterState.action = $(this).val() || '';
            _filterState.page   = '';
            fetchFeed();
        });

    $('#filterUser').select2({ minimumResultsForSearch: -1, width: '100%' })
        .on('change', function() {
            _filterState.user_id = $(this).val() || '';
            _filterState.page    = '';
            fetchFeed();
        });
});

// Auto-refresh every 60s via Axios (no full reload)
setInterval(fetchFeed, 60000);
</script>
@endpush