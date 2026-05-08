@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ── Alert Strip ─────────────────────────────────────────── */
.alert-strip { display:flex; flex-direction:column; gap:8px; margin-bottom:24px; }
.alert-item  { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:var(--radius); font-size:14px; }
.alert-item i { flex-shrink:0; font-size:14px; }
.alert-item > span { flex:1; line-height:1.5; }
.alert-senior  { background:var(--gold-pale);    border:1px solid var(--gold-border);    border-left:4px solid var(--gold);    color:#78450a; }
.alert-birthday{ background:var(--navy-pale);    border:1px solid var(--navy-border);    border-left:4px solid var(--navy);    color:var(--navy); }
.alert-permit  { background:var(--crimson-pale); border:1px solid var(--crimson-border); border-left:4px solid var(--crimson); color:var(--crimson); }
.alert-link {
    font-size:13px; font-weight:600; color:inherit; opacity:.85;
    text-decoration:none; padding:5px 14px; border:1px solid currentColor;
    border-radius:99px; white-space:nowrap; flex-shrink:0;
}
.alert-link:hover { opacity:1; }

/* ── Dashboard Tab Strip ─────────────────────────────────── */
.dash-tabs {
    display: flex;
    gap: 2px;
    margin-bottom: 20px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 6px;
    width: fit-content;
}
.dash-tab-btn {
    padding: 9px 22px;
    font-size: 13.5px;
    font-weight: 600;
    color: var(--text-muted);
    background: none;
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    white-space: nowrap;
    font-family: 'Poppins', sans-serif;
    transition: all .15s;
}
.dash-tab-btn:hover { color: var(--navy); background: var(--surface2); }
.dash-tab-btn.active { background: var(--navy); color: #fff; }
.dash-tab-btn .tab-badge {
    font-size: 11px; font-weight: 700;
    padding: 1px 6px; border-radius: 99px;
    background: rgba(255,255,255,.25);
    color: inherit;
}
.dash-tab-btn:not(.active) .tab-badge { background: var(--gold-pale); color: var(--gold); }

.dash-panel { display: none; }
.dash-panel.active { display: block; }

/* ── Stat Cards ──────────────────────────────────────────── */
.dash-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(170px,1fr)); gap:16px; margin-bottom:24px; }
.dash-stat-card {
    display:flex; align-items:center; gap:16px;
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--radius-lg); padding:20px 22px;
    text-decoration:none; box-shadow:var(--shadow-sm);
    transition:box-shadow .15s, transform .15s;
}
.dash-stat-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.dash-stat-icon {
    width:48px; height:48px; border-radius:var(--radius);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; flex-shrink:0;
}
.dash-stat-number { font-size:28px; font-weight:700; color:var(--text); line-height:1.1; }
.dash-stat-label  { font-size:14px; color:var(--text-muted); margin-top:4px; }

/* ── Mid Row: Chart + Quick Access ───────────────────────── */
.dash-mid { display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:24px; }

.quick-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.quick-item {
    display:flex; flex-direction:column; align-items:center; gap:7px;
    padding:14px 6px; background:var(--surface2);
    border:1px solid var(--border); border-radius:var(--radius);
    text-decoration:none; transition:all .15s;
}
.quick-item:hover { background:var(--navy-pale); border-color:var(--qa-color, var(--navy)); }
.quick-icon { width:38px; height:38px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:16px; }
.quick-label { font-size:13px; font-weight:600; color:var(--text); text-align:center; }

/* ── Bottom 2-col ────────────────────────────────────────── */
.dash-bottom { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

/* ── Feed Rows (shared) ──────────────────────────────────── */
.feed-row {
    display:flex; align-items:center; gap:12px;
    padding:12px 16px; border-bottom:1px solid var(--border);
    text-decoration:none; transition:background .1s;
}
.feed-row:last-child { border-bottom:none; }
.feed-row:hover { background:var(--navy-pale); }
.feed-icon { width:36px; height:36px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; }
.feed-body { flex:1; min-width:0; }
.feed-title { font-size:14px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:13px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px; }

/* ── Analytics Tab ───────────────────────────────────────── */
.analytics-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px; }
.demog-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; }
.demog-item {
    background:var(--surface2); border:1px solid var(--border);
    border-radius:var(--radius); padding:14px 16px; text-align:center;
}
.demog-num { font-size:22px; font-weight:700; color:var(--navy); line-height:1.1; }
.demog-lbl { font-size:13px; color:var(--text-muted); margin-top:4px; }

/* ── Appointment Status Badges (dashboard) ───────────────── */
.apt-badge {
    display:inline-block; padding:2px 8px; border-radius:999px;
    font-size:11px; font-weight:700; border:1.5px solid;
}
.apt-pending    { background:var(--gold-pale);    color:#78450a; border-color:var(--gold-border); }
.apt-confirmed  { background:var(--navy-pale);    color:var(--navy); border-color:var(--navy-border); }
.apt-processing { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
.apt-ready      { background:#f0fdf4; color:#14532d; border-color:#bbf7d0; }
.apt-released   { background:#f9fafb; color:#6b7280; border-color:#d1d5db; }
.apt-cancelled  { background:var(--crimson-pale); color:var(--crimson); border-color:var(--crimson-border); }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, <strong style="color:var(--navy)">{{ auth()->user()->name }}</strong> — {{ now()->format('l, F d, Y') }}</p>
    </div>
    <div class="page-actions no-print">
        <button id="startTourBtn" class="btn btn-secondary btn-sm" data-tippy-content="Take a guided tour of the Dashboard to learn about each section.">
            <i class="fas fa-map" style="color:var(--gold)"></i> Take Tour
        </button>
        <a href="{{ route('residents.create') }}"  class="btn btn-secondary btn-sm"><i class="fas fa-user-plus"></i> New Resident</a>
        <a href="{{ route('documents.create') }}"  class="btn btn-secondary btn-sm"><i class="fas fa-file-plus"></i> New Document</a>
        <a href="{{ route('blotter.create') }}"    class="btn btn-secondary btn-sm"><i class="fas fa-gavel"></i> New Blotter</a>
        <a href="{{ route('businesses.create') }}" class="btn btn-secondary btn-sm"><i class="fas fa-store"></i> New Permit</a>
    </div>
</div>

{{-- ALERTS STRIP --}}
@if($birthdays->count() || $expiringPermits->count() || $pendingAppointments || $lowStockMeds->count())
<div class="alert-strip" id="tour-alerts">

    @if($seniorBdays->count())
    <div class="alert-item alert-senior">
        <i class="fas fa-star"></i>
        <span>
            <strong>Senior Citizen {{ $seniorBdays->count() === 1 ? 'Birthday' : 'Birthdays' }} Today ({{ $seniorBdays->count() }}) —</strong>
            {{ $seniorBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ') }}{{ $seniorBdays->count() > 4 ? ' +' . ($seniorBdays->count() - 4) . ' more' : '' }}
        </span>
        <a href="{{ route('residents.index') }}" class="alert-link">View All</a>
    </div>
    @endif

    @if($regularBdays->count())
    <div class="alert-item alert-birthday">
        <i class="fas fa-birthday-cake"></i>
        <span>
            <strong>{{ $regularBdays->count() === 1 ? 'Birthday' : 'Birthdays' }} Today ({{ $regularBdays->count() }}) —</strong>
            {{ $regularBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ') }}{{ $regularBdays->count() > 4 ? ' +' . ($regularBdays->count() - 4) . ' more' : '' }}
        </span>
    </div>
    @endif

    @if($expiringPermits->count())
    <div class="alert-item alert-permit">
        <i class="fas fa-triangle-exclamation"></i>
        <span>
            <strong>{{ $expiringPermits->count() }} Business Permit{{ $expiringPermits->count() > 1 ? 's' : '' }} Expiring Within 30 Days —</strong>
            {{ $expiringPermits->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ') }}{{ $expiringPermits->count() > 3 ? ' +' . ($expiringPermits->count() - 3) . ' more' : '' }}
        </span>
        <a href="{{ route('businesses.index') }}" class="alert-link">View All</a>
    </div>
    @endif

    @if($pendingAppointments)
    <div class="alert-item alert-birthday">
        <i class="fas fa-calendar-clock"></i>
        <span>
            <strong>{{ $pendingAppointments }} Pending Document Appointment{{ $pendingAppointments > 1 ? 's' : '' }}</strong> — waiting for staff confirmation.
        </span>
        @if(in_array(auth()->user()->role, ['Admin','Secretary']))
        <a href="{{ route('appointments.index') }}?status=Pending" class="alert-link">Review</a>
        @endif
    </div>
    @endif

    @if($lowStockMeds->count())
    <div class="alert-item alert-permit">
        <i class="fas fa-pills"></i>
        <span>
            <strong>{{ $lowStockMeds->count() }} Medicine{{ $lowStockMeds->count() > 1 ? 's' : '' }} Low on Stock —</strong>
            {{ $lowStockMeds->map(fn($m) => $m->medicine_name . ' (' . $m->current_stock . ' ' . $m->unit . ')')->take(3)->implode(' · ') }}{{ $lowStockMeds->count() > 3 ? ' +' . ($lowStockMeds->count() - 3) . ' more' : '' }}
        </span>
        <a href="{{ route('committees.show', 'health') }}#medicine-inventory" class="alert-link">View</a>
    </div>
    @endif

</div>
@endif

{{-- DASHBOARD TABS --}}
<div class="dash-tabs">
    <button class="dash-tab-btn active" onclick="switchDashTab('overview')" id="dtab-overview">
        <i class="fas fa-tachometer-alt"></i> Overview
    </button>
    <button class="dash-tab-btn" onclick="switchDashTab('analytics')" id="dtab-analytics">
        <i class="fas fa-chart-pie"></i> Analytics
    </button>
    @if(in_array(auth()->user()->role, ['Admin','Secretary']))
    <button class="dash-tab-btn" onclick="switchDashTab('appointments')" id="dtab-appointments">
        <i class="fas fa-calendar-check"></i> Appointments
        @if($pendingAppointments) <span class="tab-badge">{{ $pendingAppointments }}</span> @endif
    </button>
    @endif
</div>

{{-- ═══════════ TAB 1: OVERVIEW ═══════════ --}}
<div id="dpanel-overview" class="dash-panel active">

    {{-- STAT CARDS --}}
    <div class="dash-stats" id="tour-statcards">
        <a href="{{ route('residents.index') }}" class="dash-stat-card">
            <div class="dash-stat-icon" style="background:#eef2ff;color:#4f46e5"><i class="fas fa-users"></i></div>
            <div>
                <div class="dash-stat-number">{{ number_format($totalResidents) }}</div>
                <div class="dash-stat-label">Total Residents</div>
            </div>
        </a>
        <a href="{{ route('documents.index') }}" class="dash-stat-card">
            <div class="dash-stat-icon" style="background:#fffbeb;color:#d97706"><i class="fas fa-file-alt"></i></div>
            <div>
                <div class="dash-stat-number">{{ number_format($pendingDocuments) }}</div>
                <div class="dash-stat-label">Pending Documents</div>
            </div>
        </a>
        <a href="{{ route('blotter.index') }}" class="dash-stat-card">
            <div class="dash-stat-icon" style="background:#fef2f2;color:#dc2626"><i class="fas fa-gavel"></i></div>
            <div>
                <div class="dash-stat-number">{{ number_format($activeBlotter) }}</div>
                <div class="dash-stat-label">Active Blotter Cases</div>
                @if($overdueBlotter > 0)
                <div style="font-size:13px;color:var(--crimson);margin-top:3px;font-weight:600">
                    <i class="fas fa-fire"></i> {{ $overdueBlotter }} overdue 30+ days
                </div>
                @endif
            </div>
        </a>
        <a href="{{ route('businesses.index') }}" class="dash-stat-card">
            <div class="dash-stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-store"></i></div>
            <div>
                <div class="dash-stat-number">{{ number_format($activeBusinesses) }}</div>
                <div class="dash-stat-label">Active Businesses</div>
            </div>
        </a>
        @if(in_array(auth()->user()->role, ['Admin','Secretary']))
        <a href="{{ route('appointments.index') }}" class="dash-stat-card">
            <div class="dash-stat-icon" style="background:var(--gold-pale);color:var(--gold)"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="dash-stat-number">{{ number_format($pendingAppointments) }}</div>
                <div class="dash-stat-label">Pending Appointments</div>
            </div>
        </a>
        @endif
    </div>

    {{-- CHART + QUICK ACCESS --}}
    <div class="dash-mid">
        <div class="card" id="tour-chart">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar"></i> Documents Issued — {{ date('Y') }}</span>
            </div>
            <div class="card-body">
                <canvas id="monthlyDocChart" height="105"></canvas>
                @php
                    $thisMonth = $monthlyData[now()->month] ?? 0;
                    $yearTotal = array_sum($monthlyData);
                    $prevMonth = $monthlyData[now()->subMonth()->month] ?? 0;
                    $trend = $thisMonth > $prevMonth ? 'up' : ($thisMonth < $prevMonth ? 'down' : 'the same as');
                @endphp
                <div class="chart-insight">
                    <strong>{{ number_format($yearTotal) }}</strong> documents issued so far in {{ date('Y') }}.
                    This month: <strong>{{ $thisMonth }}</strong> —
                    @if($thisMonth > $prevMonth)
                        <span style="color:#16a34a"><i class="fas fa-arrow-up"></i> up from {{ $prevMonth }} last month.</span>
                    @elseif($thisMonth < $prevMonth)
                        <span style="color:var(--crimson)"><i class="fas fa-arrow-down"></i> down from {{ $prevMonth }} last month.</span>
                    @else
                        same as last month ({{ $prevMonth }}).
                    @endif
                </div>
            </div>
        </div>

        <div class="card" id="tour-quickaccess">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
            </div>
            <div class="card-body">
                <div class="quick-grid">
                    @php $links = [
                        ['href' => route('residents.index'),    'icon' => 'fa-users',          'label' => 'Residents',    'color' => '#4f46e5'],
                        ['href' => route('households.index'),   'icon' => 'fa-house',          'label' => 'Households',   'color' => '#0891b2'],
                        ['href' => route('documents.index'),    'icon' => 'fa-file-alt',       'label' => 'Documents',    'color' => '#d97706'],
                        ['href' => route('blotter.index'),      'icon' => 'fa-gavel',          'label' => 'Blotter',      'color' => '#dc2626'],
                        ['href' => route('businesses.index'),   'icon' => 'fa-store',          'label' => 'Businesses',   'color' => '#16a34a'],
                        ['href' => route('appointments.index'), 'icon' => 'fa-calendar-check', 'label' => 'Appointments', 'color' => '#C8861A'],
                        ['href' => route('officials.index'),    'icon' => 'fa-user-tie',       'label' => 'Officials',    'color' => '#7c3aed'],
                        ['href' => route('reports.index'),      'icon' => 'fa-chart-bar',      'label' => 'Analytics',    'color' => '#0D2144'],
                        ['href' => route('backup.index'),       'icon' => 'fa-database',       'label' => 'Backup',       'color' => '#374151'],
                    ]; @endphp
                    @foreach($links as $l)
                    <a href="{{ $l['href'] }}" class="quick-item" style="--qa-color:{{ $l['color'] }}">
                        <div class="quick-icon" style="background:{{ $l['color'] }}18;color:{{ $l['color'] }}">
                            <i class="fas {{ $l['icon'] }}"></i>
                        </div>
                        <span class="quick-label">{{ $l['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT DOCUMENTS + RECENT BLOTTER --}}
    <div class="dash-bottom">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-alt"></i> Recent Documents</span>
                <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($recentDocuments as $d)
                <a href="{{ route('documents.show', $d->id) }}" class="feed-row">
                    <div class="feed-icon" style="background:#fffbeb"><i class="fas fa-file-alt" style="color:#d97706"></i></div>
                    <div class="feed-body">
                        <div class="feed-title">{{ $d->doc_number }}</div>
                        <div class="feed-sub">{{ $d->resident->full_name ?? '—' }} · {{ $d->document_type }}</div>
                    </div>
                    <span class="badge {{ $d->status === 'Released' ? 'badge-green' : ($d->status === 'Pending' ? 'badge-yellow' : 'badge-blue') }}">{{ $d->status }}</span>
                </a>
                @empty
                <div class="empty-state" style="padding:32px">
                    <i class="fas fa-file-alt"></i>
                    <p>No documents yet. <a href="{{ route('documents.create') }}" style="color:var(--navy);font-weight:600">Issue one now</a></p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-gavel"></i> Recent Blotter</span>
                <a href="{{ route('blotter.index') }}" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0">
                @forelse($recentBlotter as $b)
                @php
                    $bIsOpen   = in_array($b->status, ['Active', 'Under Investigation']);
                    $bDaysOpen = $bIsOpen && $b->incident_date
                        ? \Carbon\Carbon::parse($b->incident_date)->diffInDays(now()) : 0;
                @endphp
                <a href="{{ route('blotter.show', $b->id) }}" class="feed-row">
                    <div class="feed-icon" style="background:#fef2f2{{ $bIsOpen && $bDaysOpen >= 30 ? ';outline:2px solid #dc2626;outline-offset:-2px' : '' }}">
                        <i class="fas fa-gavel" style="color:#dc2626"></i>
                    </div>
                    <div class="feed-body">
                        <div class="feed-title">{{ $b->case_number }}</div>
                        <div class="feed-sub">{{ $b->incident_type }}{{ $b->complainant_name ? ' · ' . $b->complainant_name : '' }}</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex-shrink:0">
                        <span class="badge {{ $b->status === 'Settled' ? 'badge-green' : ($b->status === 'Active' ? 'badge-red' : 'badge-gray') }}">{{ $b->status }}</span>
                        @if($bIsOpen && $bDaysOpen >= 30)
                        <span style="font-size:11px;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:99px;white-space:nowrap;font-weight:700">
                            <i class="fas fa-fire"></i> {{ $bDaysOpen }}d
                        </span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="empty-state" style="padding:32px">
                    <i class="fas fa-gavel"></i>
                    <p>No blotter cases yet. <a href="{{ route('blotter.create') }}" style="color:var(--navy);font-weight:600">File a case</a></p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>{{-- end #dpanel-overview --}}

{{-- ═══════════ TAB 2: ANALYTICS ═══════════ --}}
<div id="dpanel-analytics" class="dash-panel">

    {{-- Demographic summary numbers --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-users"></i> Resident Demographics</span>
            <a href="{{ route('residents.index') }}" class="btn btn-secondary btn-sm">View Residents</a>
        </div>
        <div class="card-body">
            <div class="demog-grid">
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalActive) }}</div>
                    <div class="demog-lbl">Active</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num" style="color:var(--gold)">{{ number_format($totalSeniors) }}</div>
                    <div class="demog-lbl">Senior Citizens</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalVoters) }}</div>
                    <div class="demog-lbl">Registered Voters</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalPwd) }}</div>
                    <div class="demog-lbl">PWD</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalSoloParent) }}</div>
                    <div class="demog-lbl">Solo Parents</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($total4ps) }}</div>
                    <div class="demog-lbl">4Ps Beneficiaries</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalMale) }}</div>
                    <div class="demog-lbl">Male</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalFemale) }}</div>
                    <div class="demog-lbl">Female</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num">{{ number_format($totalHouseholds) }}</div>
                    <div class="demog-lbl">Households</div>
                </div>
            </div>
        </div>
    </div>

    <div class="analytics-grid">
        {{-- Age Groups --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-pie"></i> Age Distribution</span>
            </div>
            <div class="card-body" style="display:flex;align-items:center;gap:20px">
                <div style="flex-shrink:0;width:160px;height:160px">
                    <canvas id="ageChart"></canvas>
                </div>
                <div style="flex:1">
                    @php
                        $ageColors = ['#4f46e5','#C8861A','#16a34a','#dc2626']; $ai=0;
                        $largestAgeGroup = $ageGroups->sortDesc()->keys()->first();
                        $largestAgeCount = $ageGroups->max();
                    @endphp
                    @foreach($ageGroups as $label => $count)
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                        <div style="width:10px;height:10px;border-radius:2px;background:{{ $ageColors[$ai] }};flex-shrink:0"></div>
                        <div style="flex:1;font-size:13px;color:var(--text)">{{ $label }}</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy)">{{ number_format($count) }}</div>
                    </div>
                    @php $ai++; @endphp
                    @endforeach
                    <div class="chart-insight" style="margin-top:10px">
                        Largest group: <strong>{{ $largestAgeGroup }}</strong> with <strong>{{ number_format($largestAgeCount) }}</strong> residents.
                    </div>
                </div>
            </div>
        </div>

        {{-- Document Types --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-alt"></i> Documents by Type</span>
            </div>
            <div class="card-body" style="display:flex;align-items:center;gap:20px">
                <div style="flex-shrink:0;width:160px;height:160px">
                    <canvas id="docTypeChart"></canvas>
                </div>
                <div style="flex:1">
                    @php
                        $dtColors = ['#0D2144','#C8861A','#4f46e5','#16a34a','#dc2626','#0891b2']; $di=0;
                        $topDocType = $documentsByType->sortDesc()->keys()->first();
                        $topDocCount = $documentsByType->max();
                    @endphp
                    @foreach($documentsByType as $type => $count)
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:7px">
                        <div style="width:10px;height:10px;border-radius:2px;background:{{ $dtColors[$di % count($dtColors)] }};flex-shrink:0"></div>
                        <div style="flex:1;font-size:13px;color:var(--text)">{{ $type }}</div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy)">{{ number_format($count) }}</div>
                    </div>
                    @php $di++; @endphp
                    @endforeach
                    @if($topDocType)
                    <div class="chart-insight" style="margin-top:10px">
                        Most requested: <strong>{{ $topDocType }}</strong> ({{ number_format($topDocCount) }} issued).
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Purok Breakdown --}}
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-location-dot"></i> Residents by Purok</span>
        </div>
        <div class="card-body">
            <canvas id="purokChart" height="60"></canvas>
        </div>
    </div>

    {{-- Blotter by type --}}
    @if($blotterByType->count())
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-gavel"></i> Blotter by Incident Type</span>
            <a href="{{ route('blotter.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            @php $maxBlotter = $blotterByType->max() ?: 1; @endphp
            @foreach($blotterByType->sortDesc() as $type => $count)
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:9px">
                <div style="width:150px;font-size:13px;color:var(--text);flex-shrink:0;line-height:1.4">{{ $type }}</div>
                <div style="flex:1;background:#f3f4f6;border-radius:99px;height:9px;overflow:hidden">
                    <div style="height:100%;border-radius:99px;background:var(--crimson);width:{{ round(($count/$maxBlotter)*100) }}%"></div>
                </div>
                <div style="font-size:13px;font-weight:700;color:var(--crimson);width:30px;text-align:right">{{ $count }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>{{-- end #dpanel-analytics --}}

{{-- ═══════════ TAB 3: APPOINTMENTS ═══════════ --}}
@if(in_array(auth()->user()->role, ['Admin','Secretary']))
<div id="dpanel-appointments" class="dash-panel">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-calendar-check"></i> Document Appointments</span>
            <div style="display:flex;gap:.5rem">
                <a href="{{ route('portal.index') }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fas fa-globe"></i> View Portal
                </a>
                <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> Manage All
                </a>
            </div>
        </div>

        {{-- Summary strip --}}
        <div style="display:flex;gap:1px;background:var(--border);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
            @foreach(['Pending','Confirmed','Processing','Ready','Released','Cancelled'] as $st)
            <div style="flex:1;padding:12px 8px;text-align:center;background:var(--surface2)">
                <div style="font-size:18px;font-weight:700;color:var(--navy)">{{ $aptCounts[$st] ?? 0 }}</div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:3px;font-weight:500">{{ $st }}</div>
            </div>
            @endforeach
        </div>

        <div class="card-body" style="padding:0">
            @forelse($recentAppointments as $apt)
            @php $sc = strtolower($apt->status); @endphp
            <div class="feed-row" style="text-decoration:none">
                <div class="feed-icon" style="background:var(--gold-pale)"><i class="fas fa-calendar" style="color:var(--gold)"></i></div>
                <div class="feed-body">
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="feed-title">{{ $apt->appointment_number }}</span>
                        <span class="apt-badge apt-{{ $sc }}">{{ $apt->status }}</span>
                    </div>
                    <div class="feed-sub">{{ $apt->resident_name }} · {{ $apt->document_type }} · {{ $apt->preferred_date->format('M d, Y') }}</div>
                </div>
                <a href="{{ route('appointments.index') }}?search={{ $apt->appointment_number }}"
                   class="btn btn-secondary btn-sm" style="flex-shrink:0;font-size:13px">Update</a>
            </div>
            @empty
            <div class="empty-state" style="padding:40px"><i class="fas fa-calendar-check"></i><p>No appointments yet</p></div>
            @endforelse
        </div>

        @if($recentAppointments->count() >= 8)
        <div style="padding:.75rem 1rem;border-top:1px solid var(--border);text-align:center">
            <a href="{{ route('appointments.index') }}" style="font-size:13px;color:var(--navy);font-weight:600">
                View all appointments <i class="fas fa-arrow-right" style="font-size:10px"></i>
            </a>
        </div>
        @endif
    </div>

</div>{{-- end #dpanel-appointments --}}
@endif

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Tab switching
function switchDashTab(id) {
    document.querySelectorAll('.dash-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.dash-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('dpanel-' + id).classList.add('active');
    document.getElementById('dtab-' + id).classList.add('active');
}

// ── Shepherd.js Onboarding Tour ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Shepherd === 'undefined') return;

    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            cancelIcon: { enabled: true },
            scrollTo: { behavior: 'smooth', block: 'center' },
            popperOptions: { modifiers: [{ name: 'offset', options: { offset: [0, 14] } }] }
        }
    });

    const btn = (label, type, action) => ({ text: label, classes: 'shepherd-button-' + type, action });

    tour.addStep({
        id: 'welcome',
        title: 'Welcome to the Dashboard',
        text: 'This is your Command Center — a real-time summary of everything happening in Barangay New Era. This short tour will show you the most important parts.',
        buttons: [btn('Skip Tour', 'secondary', tour.cancel), btn('Start Tour →', 'primary', tour.next)]
    });

    @if($birthdays->count() || $expiringPermits->count() || $pendingAppointments || $lowStockMeds->count())
    tour.addStep({
        id: 'alerts',
        title: 'Alert Strip — Urgent Items',
        text: 'These colored banners appear when something needs your attention right now: birthdays today, expiring business permits, pending appointments, or low medicine stock. Click the links to act immediately.',
        attachTo: { element: '#tour-alerts', on: 'bottom' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });
    @endif

    tour.addStep({
        id: 'statcards',
        title: 'Key Numbers at a Glance',
        text: 'These cards show your most important counts — Total Residents, Pending Documents, Active Blotter Cases, and more. Click any card to go directly to that module.',
        attachTo: { element: '#tour-statcards', on: 'bottom' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });

    tour.addStep({
        id: 'chart',
        title: 'Monthly Documents Chart',
        text: 'This bar chart shows how many barangay documents were issued each month. The insight below the chart summarizes the trend in plain language — no need to analyze the numbers yourself.',
        attachTo: { element: '#tour-chart', on: 'right' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });

    tour.addStep({
        id: 'quickaccess',
        title: 'Quick Access — Go Anywhere Fast',
        text: 'Use these shortcut buttons to jump to any module instantly: Residents, Documents, Blotter, Businesses, Committees, and more. No need to use the sidebar.',
        attachTo: { element: '#tour-quickaccess', on: 'left' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });

    tour.addStep({
        id: 'tabs',
        title: 'Dashboard Tabs',
        text: 'Switch between <strong>Overview</strong> (today\'s summary), <strong>Analytics</strong> (charts and demographics), and <strong>Appointments</strong> (document requests from residents). The orange number on Appointments shows pending items.',
        attachTo: { element: '.dash-tabs', on: 'bottom' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Finish Tour ✓', 'primary', function () {
            tour.complete();
            sessionStorage.setItem('bms_tour_done', '1');
        })]
    });

    // Start tour button
    const startBtn = document.getElementById('startTourBtn');
    if (startBtn) startBtn.addEventListener('click', function () { tour.start(); });

    // Auto-start on first visit
    if (!sessionStorage.getItem('bms_tour_done')) {
        setTimeout(function () { tour.start(); }, 800);
    }
});

// Monthly documents bar chart (Overview tab)
new Chart(document.getElementById('monthlyDocChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Documents',
            data: [{{ $monthlyData[1] }},{{ $monthlyData[2] }},{{ $monthlyData[3] }},{{ $monthlyData[4] }},{{ $monthlyData[5] }},{{ $monthlyData[6] }},{{ $monthlyData[7] }},{{ $monthlyData[8] }},{{ $monthlyData[9] }},{{ $monthlyData[10] }},{{ $monthlyData[11] }},{{ $monthlyData[12] }}],
            backgroundColor: 'rgba(13,33,68,0.10)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(200,134,26,0.18)',
            hoverBorderColor: '#C8861A',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 11, family: 'Poppins' } }, grid: { color: '#F3F4F6' } },
            y: { ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 }, grid: { color: '#F3F4F6' }, beginAtZero: true }
        }
    }
});

// Age distribution donut (Analytics tab)
new Chart(document.getElementById('ageChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($ageGroups)) !!},
        datasets: [{
            data: {!! json_encode(array_values($ageGroups)) !!},
            backgroundColor: ['#4f46e5','#C8861A','#16a34a','#dc2626'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});

// Documents by type donut (Analytics tab)
@php $dtLabels = $documentsByType->keys()->toArray(); $dtValues = $documentsByType->values()->toArray(); @endphp
new Chart(document.getElementById('docTypeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($dtLabels) !!},
        datasets: [{
            data: {!! json_encode($dtValues) !!},
            backgroundColor: ['#0D2144','#C8861A','#4f46e5','#16a34a','#dc2626','#0891b2'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});

// Residents by purok bar chart (Analytics tab)
new Chart(document.getElementById('purokChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($residentsByPurok->pluck('name')->toArray()) !!},
        datasets: [{
            label: 'Active Residents',
            data: {!! json_encode($residentsByPurok->pluck('residents_count')->toArray()) !!},
            backgroundColor: 'rgba(13,33,68,0.12)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 4,
            hoverBackgroundColor: 'rgba(200,134,26,0.20)',
            hoverBorderColor: '#C8861A',
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 10 } }, grid: { color: '#F3F4F6' }, beginAtZero: true },
            y: { ticks: { color: '#374151', font: { size: 11, family: 'Poppins' } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
