@extends('layouts.app')
@section('title', 'Generate Reports')

@php
    // Pre-load chart data
    $currentYear  = date('Y');
    $currentMonth = date('n');
    $currentQ     = ceil($currentMonth / 3);

    // Monthly docs for bar chart (current year)
    $monthlyDocs = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlyDocs[] = \App\Models\Document::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $m)->count();
    }

    // Monthly blotter
    $monthlyBlotter = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlyBlotter[] = \App\Models\BlotterCase::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $m)->count();
    }

    // Docs by type (this year)
    $docsByType = \App\Models\Document::whereYear('created_at', $currentYear)
        ->selectRaw('document_type, count(*) as total')
        ->groupBy('document_type')->pluck('total','document_type');

    // Blotter by type
    $blotterByType = \App\Models\BlotterCase::whereYear('created_at', $currentYear)
        ->selectRaw('incident_type, count(*) as total')
        ->groupBy('incident_type')->pluck('total','incident_type');

    // Quick stats
    $totalResidents   = \App\Models\Resident::count();
    $activeResidents  = \App\Models\Resident::where('residency_status','Active')->count();
    $totalDocs        = \App\Models\Document::whereYear('created_at', $currentYear)->count();
    $totalBlotter     = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->count();
    $totalBusinesses  = \App\Models\Business::where('status','Active')->count();
    $totalHouseholds  = \App\Models\Household::count();
@endphp

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Generate Reports</h1>
        <p class="page-subtitle">Monthly, quarterly, and annual reports — Barangay New Era {{ $currentYear }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-chart-bar"></i> Analytics
        </a>
    </div>
</div>

{{-- STAT STRIP --}}
<div class="grid-4 mb-6" style="grid-template-columns:repeat(6,1fr)">
    @php $strips = [
        ['label'=>'Residents',  'value'=>number_format($totalResidents),  'icon'=>'fa-users',    'color'=>'var(--navy)'],
        ['label'=>'Households', 'value'=>number_format($totalHouseholds), 'icon'=>'fa-house',    'color'=>'var(--gold)'],
        ['label'=>'Documents',  'value'=>number_format($totalDocs),       'icon'=>'fa-file-alt', 'color'=>'#16a34a'],
        ['label'=>'Blotter',    'value'=>number_format($totalBlotter),    'icon'=>'fa-gavel',    'color'=>'var(--crimson)'],
        ['label'=>'Businesses', 'value'=>number_format($totalBusinesses), 'icon'=>'fa-store',    'color'=>'#2563eb'],
        ['label'=>'Active',     'value'=>number_format($activeResidents), 'icon'=>'fa-circle-check','color'=>'#16a34a'],
    ]; @endphp
    @foreach($strips as $s)
    <div class="stat-card" style="padding:12px 14px">
        <div class="stat-icon" style="background:{{ $s['color'] }}12;color:{{ $s['color'] }};width:34px;height:34px;font-size:14px">
            <i class="fas {{ $s['icon'] }}"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" style="font-size:18px">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start">

    {{-- LEFT: Report Generator Form --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header" style="background:var(--navy);border-radius:var(--radius) var(--radius) 0 0">
                <span class="card-title" style="color:#fff"><i class="fas fa-file-pdf" style="color:#ef4444"></i> Report Generator</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reports.generate') }}" id="reportForm">
                    @csrf

                    {{-- Report Type --}}
                    <div class="form-group mb-5">
                        <label class="form-label">Report Type</label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                            @foreach(['monthly'=>['Monthly','fa-calendar-day','Jan–Dec'],'quarterly'=>['Quarterly','fa-calendar-week','Q1–Q4'],'annual'=>['Annual','fa-calendar','Full Year']] as $val=>[$lbl,$icon,$sub])
                            <label style="display:flex;flex-direction:column;align-items:center;gap:5px;padding:10px 6px;border:2px solid var(--border);border-radius:var(--radius);cursor:pointer;transition:all 0.15s;text-align:center"
                                   id="type-card-{{ $val }}"
                                   onclick="selectType('{{ $val }}')">
                                <input type="radio" name="report_type" value="{{ $val }}" style="display:none" {{ $val === 'monthly' ? 'checked' : '' }}>
                                <i class="fas {{ $icon }}" style="font-size:16px;color:var(--navy)"></i>
                                <span style="font-size:11.5px;font-weight:700;color:var(--text)">{{ $lbl }}</span>
                                <span style="font-size:10px;color:var(--text-muted)">{{ $sub }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Module --}}
                    <div class="form-group mb-4">
                        <label class="form-label">Module</label>
                        <select name="report_module" class="form-control" required>
                            <option value="summary">📊 Full Summary Report</option>
                            <option value="residents">👥 Residents</option>
                            <option value="documents">📄 Documents Issued</option>
                            <option value="blotter">⚖️ Blotter Cases</option>
                            <option value="businesses">🏪 Business Permits</option>
                        </select>
                    </div>

                    {{-- Year --}}
                    <div class="form-group mb-4">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-control" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Month --}}
                    <div class="form-group mb-4" id="month-field">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-control">
                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                                <option value="{{ $i+1 }}" {{ ($i+1) == date('n') ? 'selected' : '' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quarter --}}
                    <div class="form-group mb-4" id="quarter-field" style="display:none">
                        <label class="form-label">Quarter</label>
                        <select name="quarter" class="form-control">
                            <option value="1">Q1 — January to March</option>
                            <option value="2">Q2 — April to June</option>
                            <option value="3">Q3 — July to September</option>
                            <option value="4">Q4 — October to December</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px">
                        <i class="fas fa-file-pdf"></i> Generate PDF Report
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick Generate --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt" style="color:var(--gold)"></i> Quick Generate</span>
            </div>
            <div class="card-body" style="padding:10px">
                @php $quick = [
                    ['label'=>'This Month — Summary',    'type'=>'monthly',   'module'=>'summary',   'extra'=>'month='.date('n').'&year='.date('Y'),'color'=>'var(--navy)'],
                    ['label'=>'This Month — Documents',  'type'=>'monthly',   'module'=>'documents', 'extra'=>'month='.date('n').'&year='.date('Y'),'color'=>'#16a34a'],
                    ['label'=>'This Month — Blotter',    'type'=>'monthly',   'module'=>'blotter',   'extra'=>'month='.date('n').'&year='.date('Y'),'color'=>'var(--crimson)'],
                    ['label'=>'This Quarter — Summary',  'type'=>'quarterly', 'module'=>'summary',   'extra'=>'quarter='.ceil(date('n')/3).'&year='.date('Y'),'color'=>'var(--gold)'],
                    ['label'=>date('Y').' Annual',       'type'=>'annual',    'module'=>'summary',   'extra'=>'year='.date('Y'),'color'=>'#2563eb'],
                ]; @endphp
                <div style="display:flex;flex-direction:column;gap:6px">
                @foreach($quick as $q)
                <form method="POST" action="{{ route('reports.generate') }}">
                    @csrf
                    <input type="hidden" name="report_type"   value="{{ $q['type'] }}">
                    <input type="hidden" name="report_module" value="{{ $q['module'] }}">
                    @foreach(explode('&', $q['extra']) as $param)
                        @php [$k,$v] = explode('=',$param); @endphp
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:flex-start;gap:8px;padding:8px 12px">
                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $q['color'] }};flex-shrink:0"></div>
                        <span style="font-size:12px;flex:1;text-align:left">{{ $q['label'] }}</span>
                        <i class="fas fa-file-pdf" style="color:#ef4444;font-size:11px"></i>
                    </button>
                </form>
                @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Charts & Data Preview --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Documents & Blotter Monthly Trend --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar"></i> {{ $currentYear }} Monthly Trend</span>
                <div style="display:flex;gap:16px;align-items:center;font-size:11px">
                    <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:2px;background:#0D2144;display:inline-block"></span> Documents</span>
                    <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:2px;background:#C8861A;display:inline-block"></span> Blotter</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="100"></canvas>
            </div>
        </div>

        {{-- Two charts side by side --}}
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-pie"></i> Documents by Type</span>
                </div>
                <div class="card-body">
                    <canvas id="docTypeChart" height="180"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-pie"></i> Blotter by Type</span>
                </div>
                <div class="card-body">
                    <canvas id="blotterTypeChart" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- Horizontal bar — population demographics --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar"></i> Population Demographics</span>
            </div>
            <div class="card-body">
                @php
                    $demos = [
                        ['label'=>'Active Residents', 'value'=>\App\Models\Resident::where('residency_status','Active')->count(), 'color'=>'#0D2144'],
                        ['label'=>'Registered Voters','value'=>\App\Models\Resident::where('is_voter',true)->count(),             'color'=>'#C8861A'],
                        ['label'=>'Senior Citizens',  'value'=>\App\Models\Resident::where('is_senior',true)->count(),            'color'=>'#2563eb'],
                        ['label'=>'PWD',              'value'=>\App\Models\Resident::where('is_pwd',true)->count(),               'color'=>'#7c3aed'],
                        ['label'=>'Solo Parents',     'value'=>\App\Models\Resident::where('is_solo_parent',true)->count(),       'color'=>'#ea580c'],
                        ['label'=>'4Ps Beneficiaries','value'=>\App\Models\Resident::where('is_4ps',true)->count(),               'color'=>'#dc2626'],
                        ['label'=>'Male',             'value'=>\App\Models\Resident::where('gender','Male')->count(),             'color'=>'#0891b2'],
                        ['label'=>'Female',           'value'=>\App\Models\Resident::where('gender','Female')->count(),           'color'=>'#db2777'],
                    ];
                    $maxVal = max(array_column($demos,'value')) ?: 1;
                @endphp
                <div style="display:flex;flex-direction:column;gap:10px">
                    @foreach($demos as $d)
                    @php $pct = round(($d['value']/$maxVal)*100); @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                            <span style="font-size:12px;color:var(--text)">{{ $d['label'] }}</span>
                            <span style="font-size:12px;font-weight:700;color:var(--navy)">{{ number_format($d['value']) }}</span>
                        </div>
                        <div style="background:var(--surface2);border-radius:99px;height:8px;overflow:hidden">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $d['color'] }};border-radius:99px;transition:width 1s"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Residents by Purok --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-location-dot"></i> Residents by Purok</span>
            </div>
            <div class="card-body">
                <canvas id="purokChart" height="90"></canvas>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// ---- Tab type selector ----
function selectType(val) {
    document.querySelectorAll('[id^="type-card-"]').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background  = '';
    });
    const card = document.getElementById('type-card-' + val);
    card.style.borderColor = 'var(--gold)';
    card.style.background  = 'rgba(200,134,26,0.06)';
    card.querySelector('input[type=radio]').checked = true;
    document.getElementById('month-field').style.display   = val === 'monthly'   ? 'block' : 'none';
    document.getElementById('quarter-field').style.display = val === 'quarterly' ? 'block' : 'none';
}
selectType('monthly');

const chartDefaults = {
    responsive: true,
    plugins: { legend: { labels: { font: { size: 11 }, color: '#4B5563' } } }
};

// ---- Monthly trend bar chart ----
new Chart(document.getElementById('trendChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [
            {
                label: 'Documents',
                data: {!! json_encode($monthlyDocs) !!},
                backgroundColor: 'rgba(13,33,68,0.75)',
                borderColor: '#0D2144',
                borderWidth: 1,
                borderRadius: 4,
            },
            {
                label: 'Blotter',
                data: {!! json_encode($monthlyBlotter) !!},
                backgroundColor: 'rgba(200,134,26,0.75)',
                borderColor: '#C8861A',
                borderWidth: 1,
                borderRadius: 4,
            }
        ]
    },
    options: {
        ...chartDefaults,
        plugins: { legend: { position:'top', labels:{ font:{size:11}, color:'#4B5563' } } },
        scales: {
            x: { ticks:{ color:'#9CA3AF', font:{size:10} }, grid:{ color:'#F3F4F6' } },
            y: { ticks:{ color:'#9CA3AF', font:{size:10}, stepSize:1 }, grid:{ color:'#F3F4F6' }, beginAtZero:true }
        }
    }
});

// ---- Documents by type doughnut ----
const docTypeLabels = {!! json_encode($docsByType->keys()) !!};
const docTypeData   = {!! json_encode($docsByType->values()) !!};
const colors = ['#0D2144','#C8861A','#16a34a','#2563eb','#7c3aed','#dc2626','#ea580c','#0891b2'];

new Chart(document.getElementById('docTypeChart'), {
    type: 'doughnut',
    data: {
        labels: docTypeLabels.length ? docTypeLabels : ['No Data'],
        datasets: [{ data: docTypeData.length ? docTypeData : [1], backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { ...chartDefaults, cutout:'55%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8 } } } }
});

// ---- Blotter by type doughnut ----
const blotterLabels = {!! json_encode($blotterByType->keys()) !!};
const blotterData   = {!! json_encode($blotterByType->values()) !!};
const blotterColors = ['#dc2626','#C8861A','#0D2144','#2563eb','#7c3aed','#16a34a','#ea580c','#0891b2'];

new Chart(document.getElementById('blotterTypeChart'), {
    type: 'doughnut',
    data: {
        labels: blotterLabels.length ? blotterLabels : ['No Data'],
        datasets: [{ data: blotterData.length ? blotterData : [1], backgroundColor: blotterColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { ...chartDefaults, cutout:'55%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8 } } } }
});

// ---- Residents by Purok horizontal bar ----
@php
    $puroks = \App\Models\Purok::withCount(['residents' => fn($q) => $q->where('residency_status','Active')])->orderBy('name')->get();
@endphp
new Chart(document.getElementById('purokChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($puroks->pluck('name')) !!},
        datasets: [{
            label: 'Residents',
            data: {!! json_encode($puroks->pluck('residents_count')) !!},
            backgroundColor: 'rgba(13,33,68,0.75)',
            borderColor: '#0D2144',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks:{ color:'#9CA3AF', font:{size:10} }, grid:{ color:'#F3F4F6' }, beginAtZero:true },
            y: { ticks:{ color:'#374151', font:{size:11} }, grid:{ display:false } }
        }
    }
});
</script>
@endpush