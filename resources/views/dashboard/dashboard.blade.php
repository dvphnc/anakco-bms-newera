@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle')Welcome back, {{ auth()->user()->name }}@endsection
@section('content')

{{-- =============================================
     STAT CARDS ROW 1
============================================= --}}
<div class="grid-4 mb-6">

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(45,164,78,0.12);color:#3fb950">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalResidents) }}</div>
            <div class="stat-label">Total Residents</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(121,192,255,0.12);color:#79c0ff">
            <i class="fas fa-house-chimney"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalHouseholds) }}</div>
            <div class="stat-label">Households</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(227,179,65,0.12);color:#e3b341">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($pendingDocuments) }}</div>
            <div class="stat-label">Pending Documents</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(248,81,73,0.12);color:#f85149">
            <i class="fas fa-gavel"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($activeBlotter) }}</div>
            <div class="stat-label">Active Blotter Cases</div>
        </div>
    </div>

</div>

{{-- =============================================
     STAT CARDS ROW 2
============================================= --}}
<div class="grid-4 mb-6">

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(121,192,255,0.12);color:#79c0ff">
            <i class="fas fa-person"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalMale) }}</div>
            <div class="stat-label">Male Residents</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(240,136,62,0.12);color:#f0883e">
            <i class="fas fa-person-dress"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalFemale) }}</div>
            <div class="stat-label">Female Residents</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(45,164,78,0.12);color:#3fb950">
            <i class="fas fa-check-to-slot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalVoters) }}</div>
            <div class="stat-label">Registered Voters</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(227,179,65,0.12);color:#e3b341">
            <i class="fas fa-store"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($activeBusinesses) }}</div>
            <div class="stat-label">Active Businesses</div>
        </div>
    </div>

</div>

{{-- =============================================
     MAIN CONTENT ROW
============================================= --}}
<div class="grid-2 mb-6" style="grid-template-columns: 2fr 1fr;">

    {{-- Monthly Documents Chart --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-chart-bar"></i>
                Documents Issued — {{ date('Y') }}
            </span>
        </div>
        <div class="card-body">
            <canvas id="monthlyChart" height="120"></canvas>
        </div>
    </div>

    {{-- Demographics --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-chart-pie"></i>
                Demographics
            </span>
        </div>
        <div class="card-body">

            @php
                $demographics = [
                    ['label' => 'Voters',      'value' => $totalVoters,  'color' => '#2da44e', 'total' => $totalResidents],
                    ['label' => 'Seniors',     'value' => $totalSeniors, 'color' => '#79c0ff', 'total' => $totalResidents],
                    ['label' => 'PWD',         'value' => $totalPwd,     'color' => '#f0883e', 'total' => $totalResidents],
                ];
            @endphp

            @foreach($demographics as $d)
            @php $pct = $d['total'] > 0 ? round(($d['value'] / $d['total']) * 100) : 0; @endphp
            <div style="margin-bottom:18px">
                <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                    <span style="font-size:13px;color:var(--text)">{{ $d['label'] }}</span>
                    <span style="font-size:12px;color:var(--text-muted)">
                        {{ number_format($d['value']) }} ({{ $pct }}%)
                    </span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar"
                         style="width:{{ $pct }}%;background:{{ $d['color'] }}">
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Gender Split --}}
            <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-muted);margin-bottom:12px">
                    Gender Split
                </div>
                @php
                    $malePct   = $totalResidents > 0 ? round(($totalMale / $totalResidents) * 100) : 0;
                    $femalePct = 100 - $malePct;
                @endphp
                <div style="display:flex;border-radius:99px;overflow:hidden;height:10px">
                    <div style="width:{{ $malePct }}%;background:#79c0ff"></div>
                    <div style="width:{{ $femalePct }}%;background:#f0883e"></div>
                </div>
                <div style="display:flex;gap:16px;margin-top:8px">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:#79c0ff"></div>
                        Male {{ $malePct }}%
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:#f0883e"></div>
                        Female {{ $femalePct }}%
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- =============================================
     RECENT ACTIVITY ROW
============================================= --}}
<div class="grid-3 mb-6">

    {{-- Recent Residents --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-users"></i>
                Recent Residents
            </span>
            <a href="{{ route('residents.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        @forelse($recentResidents as $r)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border)">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-hover));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                {{ strtoupper(substr($r->first_name, 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $r->full_name }}
                </div>
                <div style="font-size:11px;color:var(--text-muted)">
                    {{ $r->purok->name ?? '—' }}
                </div>
            </div>
            <span class="badge {{ $r->residency_status === 'Active' ? 'badge-green' : 'badge-gray' }}" style="font-size:10px">
                {{ $r->residency_status }}
            </span>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>No residents yet</p>
        </div>
        @endforelse
    </div>

    {{-- Recent Documents --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-file-alt"></i>
                Recent Documents
            </span>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        @forelse($recentDocuments as $d)
        <div style="padding:12px 20px;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px">
                <div style="min-width:0">
                    <div style="font-size:12px;font-family:monospace;color:var(--text-muted)">{{ $d->doc_number }}</div>
                    <div style="font-size:13px;font-weight:500;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $d->resident->full_name ?? '—' }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $d->document_type }}</div>
                </div>
                @php
                    $cls = match($d->status) {
                        'Released'   => 'badge-green',
                        'Processing' => 'badge-blue',
                        'Cancelled'  => 'badge-gray',
                        default      => 'badge-yellow'
                    };
                @endphp
                <span class="badge {{ $cls }}" style="font-size:10px;flex-shrink:0">{{ $d->status }}</span>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-file-certificate"></i>
            <p>No documents yet</p>
        </div>
        @endforelse
    </div>

    {{-- Recent Blotter --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-gavel"></i>
                Recent Blotter
            </span>
            <a href="{{ route('blotter.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        @forelse($recentBlotter as $b)
        <div style="padding:12px 20px;border-bottom:1px solid var(--border)">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px">
                <div style="min-width:0">
                    <div style="font-size:12px;font-family:monospace;color:var(--text-muted)">{{ $b->case_number }}</div>
                    <div style="font-size:13px;font-weight:500;color:var(--text)">{{ $b->incident_type }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $b->complainant_name }}</div>
                </div>
                <span class="badge {{ $b->status_badge }}" style="font-size:10px;flex-shrink:0">{{ $b->status }}</span>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-gavel"></i>
            <p>No blotter cases yet</p>
        </div>
        @endforelse
    </div>

</div>

{{-- =============================================
     QUICK ACTIONS + COMMITTEES
============================================= --}}
<div class="grid-2" style="grid-template-columns:1fr 2fr">

    {{-- Quick Actions --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </span>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
            <a href="{{ route('residents.create') }}" class="btn btn-secondary" style="justify-content:flex-start">
                <i class="fas fa-user-plus" style="color:var(--primary)"></i>
                Register New Resident
            </a>
            <a href="{{ route('documents.create') }}" class="btn btn-secondary" style="justify-content:flex-start">
                <i class="fas fa-file-circle-plus" style="color:#79c0ff"></i>
                Issue Document
            </a>
            <a href="{{ route('blotter.create') }}" class="btn btn-secondary" style="justify-content:flex-start">
                <i class="fas fa-gavel" style="color:#f85149"></i>
                File Blotter Case
            </a>
            <a href="{{ route('households.create') }}" class="btn btn-secondary" style="justify-content:flex-start">
                <i class="fas fa-house" style="color:#e3b341"></i>                
                Add Household
            </a>
            <a href="{{ route('businesses.create') }}" class="btn btn-secondary" style="justify-content:flex-start">
                <i class="fas fa-store" style="color:#f0883e"></i>
                Issue Business Permit
            </a>
            <a href="{{ route('officials.create') }}" class="btn btn-secondary" style="justify-content:flex-start">
                <i class="fas fa-user-tie" style="color:#e3b341"></i>
                Add Official
            </a>
        </div>
    </div>

    {{-- Committees Grid --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <i class="fas fa-people-group"></i>
                Committees
            </span>
        </div>
        <div class="card-body">
            @php
                $committeeList = [
                    ['slug' => 'peace-order',    'name' => 'Peace & Order',      'icon' => 'fa-shield',      'color' => '#f85149'],
                    ['slug' => 'health',         'name' => 'Health',             'icon' => 'fa-heart-pulse',         'color' => '#f78166'],
                    ['slug' => 'education',      'name' => 'Education',          'icon' => 'fa-graduation-cap',      'color' => '#79c0ff'],
                    ['slug' => 'infrastructure', 'name' => 'Infrastructure',     'icon' => 'fa-road',                'color' => '#e3b341'],
                    ['slug' => 'environment',    'name' => 'Environment',        'icon' => 'fa-leaf',                'color' => '#3fb950'],
                    ['slug' => 'livelihood',     'name' => 'Livelihood',         'icon' => 'fa-briefcase',           'color' => '#f0883e'],
                    ['slug' => 'transport',      'name' => 'Transport & Comm.',  'icon' => 'fa-bus',                 'color' => '#58a6ff'],
                    ['slug' => 'bdrrm',          'name' => 'BDRRM',             'icon' => 'fa-triangle-exclamation','color' => '#da3633'],
                ];
            @endphp
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                @foreach($committeeList as $c)
                <a href="{{ route('committees.show', $c['slug']) }}"
                   style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:16px 8px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);text-align:center;transition:all 0.15s;text-decoration:none"
                   onmouseover="this.style.borderColor='{{ $c['color'] }}';this.style.background='rgba(0,0,0,0.1)'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface2)'">
                    <div style="width:38px;height:38px;border-radius:var(--radius-sm);background:{{ $c['color'] }}20;display:flex;align-items:center;justify-content:center;color:{{ $c['color'] }};font-size:16px">
                        <i class="fas {{ $c['icon'] }}"></i>
                    </div>
                    <span style="font-size:11.5px;font-weight:600;color:var(--text);line-height:1.3">{{ $c['name'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Documents Issued',
                data: [
                    {{ $monthlyData[1] }}, {{ $monthlyData[2] }}, {{ $monthlyData[3] }},
                    {{ $monthlyData[4] }}, {{ $monthlyData[5] }}, {{ $monthlyData[6] }},
                    {{ $monthlyData[7] }}, {{ $monthlyData[8] }}, {{ $monthlyData[9] }},
                    {{ $monthlyData[10] }}, {{ $monthlyData[11] }}, {{ $monthlyData[12] }}
                ],
                backgroundColor: 'rgba(45,164,78,0.25)',
                borderColor: '#2da44e',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    ticks: { color: '#8b949e', font: { size: 11 } },
                    grid:  { color: '#21262d' }
                },
                y: {
                    ticks: { color: '#8b949e', font: { size: 11 }, stepSize: 1 },
                    grid:  { color: '#21262d' },
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush