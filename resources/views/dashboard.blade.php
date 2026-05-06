@extends('layouts.app')
@section('title', 'Dashboard')

@push('styles')
<style>
/* ── Alert Strip ─────────────────────────────────────────── */
.alert-strip { display:flex; flex-direction:column; gap:8px; margin-bottom:24px; }
.alert-item  { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:var(--radius); font-size:13px; }
.alert-item i { flex-shrink:0; font-size:14px; }
.alert-item > span { flex:1; line-height:1.5; }
.alert-senior  { background:var(--gold-pale);    border:1px solid var(--gold-border);    border-left:4px solid var(--gold);    color:#78450a; }
.alert-birthday{ background:var(--navy-pale);    border:1px solid var(--navy-border);    border-left:4px solid var(--navy);    color:var(--navy); }
.alert-permit  { background:var(--crimson-pale); border:1px solid var(--crimson-border); border-left:4px solid var(--crimson); color:var(--crimson); }
.alert-link {
    font-size:11.5px; font-weight:600; color:inherit; opacity:.8;
    text-decoration:none; padding:3px 12px; border:1px solid currentColor;
    border-radius:99px; white-space:nowrap; flex-shrink:0;
}
.alert-link:hover { opacity:1; }

/* ── Stat Cards ──────────────────────────────────────────── */
.dash-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
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
.dash-stat-number { font-size:26px; font-weight:700; color:var(--text); line-height:1.1; }
.dash-stat-label  { font-size:12px; color:var(--text-muted); margin-top:3px; }

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
.quick-label { font-size:11px; font-weight:600; color:var(--text); text-align:center; }

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
.feed-title { font-size:12.5px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:11px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ auth()->user()->name }} — {{ now()->format('l, F d, Y') }}</p>
    </div>
    <div class="page-actions no-print">
        <a href="{{ route('residents.create') }}"  class="btn btn-secondary btn-sm"><i class="fas fa-user-plus"></i> New Resident</a>
        <a href="{{ route('documents.create') }}"  class="btn btn-secondary btn-sm"><i class="fas fa-file-plus"></i> New Document</a>
        <a href="{{ route('blotter.create') }}"    class="btn btn-secondary btn-sm"><i class="fas fa-gavel"></i> New Blotter</a>
        <a href="{{ route('businesses.create') }}" class="btn btn-secondary btn-sm"><i class="fas fa-store"></i> New Permit</a>
    </div>
</div>

{{-- ALERTS STRIP --}}
@php
    $today        = now()->format('m-d');
    $birthdays    = \App\Models\Resident::where('residency_status', 'Active')
        ->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$today])
        ->orderBy('last_name')->get();
    $seniorBdays  = $birthdays->filter(fn($r) => $r->age >= 60);
    $regularBdays = $birthdays->filter(fn($r) => $r->age < 60);

    $expiringPermits = \App\Models\Business::where('status', 'Active')
        ->whereBetween('expiry_date', [now(), now()->addDays(30)])
        ->orderBy('expiry_date')->get();
@endphp

@if($birthdays->count() || $expiringPermits->count())
<div class="alert-strip">

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

</div>
@endif

{{-- STAT CARDS --}}
<div class="dash-stats">
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
        </div>
    </a>
    <a href="{{ route('businesses.index') }}" class="dash-stat-card">
        <div class="dash-stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-store"></i></div>
        <div>
            <div class="dash-stat-number">{{ number_format($activeBusinesses) }}</div>
            <div class="dash-stat-label">Active Businesses</div>
        </div>
    </a>
</div>

{{-- CHART + QUICK ACCESS --}}
<div class="dash-mid">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Documents Issued — {{ date('Y') }}</span>
        </div>
        <div class="card-body">
            <canvas id="monthlyDocChart" height="105"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
        </div>
        <div class="card-body">
            <div class="quick-grid">
                @php $links = [
                    ['href' => route('residents.index'),  'icon' => 'fa-users',     'label' => 'Residents',  'color' => '#4f46e5'],
                    ['href' => route('households.index'), 'icon' => 'fa-house',     'label' => 'Households', 'color' => '#0891b2'],
                    ['href' => route('documents.index'),  'icon' => 'fa-file-alt',  'label' => 'Documents',  'color' => '#d97706'],
                    ['href' => route('blotter.index'),    'icon' => 'fa-gavel',     'label' => 'Blotter',    'color' => '#dc2626'],
                    ['href' => route('businesses.index'), 'icon' => 'fa-store',     'label' => 'Businesses', 'color' => '#16a34a'],
                    ['href' => route('officials.index'),  'icon' => 'fa-user-tie',  'label' => 'Officials',  'color' => '#7c3aed'],
                    ['href' => route('reports.index'),    'icon' => 'fa-chart-bar', 'label' => 'Analytics',  'color' => '#0D2144'],
                    ['href' => route('reports.generate'), 'icon' => 'fa-file-pdf',  'label' => 'Reports',    'color' => '#ef4444'],
                    ['href' => route('backup.index'),     'icon' => 'fa-database',  'label' => 'Backup',     'color' => '#374151'],
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
                <span class="badge {{ $d->status === 'Released' ? 'badge-green' : ($d->status === 'Pending' ? 'badge-yellow' : 'badge-blue') }}" style="font-size:10px">{{ $d->status }}</span>
            </a>
            @empty
            <div class="empty-state" style="padding:32px"><i class="fas fa-file-alt"></i><p>No documents yet</p></div>
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
            <a href="{{ route('blotter.show', $b->id) }}" class="feed-row">
                <div class="feed-icon" style="background:#fef2f2"><i class="fas fa-gavel" style="color:#dc2626"></i></div>
                <div class="feed-body">
                    <div class="feed-title">{{ $b->case_number }}</div>
                    <div class="feed-sub">{{ $b->incident_type }}</div>
                </div>
                <span class="badge {{ $b->status === 'Settled' ? 'badge-green' : ($b->status === 'Active' ? 'badge-red' : 'badge-gray') }}" style="font-size:10px">{{ $b->status }}</span>
            </a>
            @empty
            <div class="empty-state" style="padding:32px"><i class="fas fa-gavel"></i><p>No blotter cases yet</p></div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
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
</script>
@endpush
