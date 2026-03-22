@extends('layouts.app')
@section('title', 'Generate Reports')

@php
    $currentYear  = date('Y');
    $currentMonth = date('n');

    $monthlyDocs = [];
    $monthlyBlotter = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlyDocs[]    = \App\Models\Document::whereYear('created_at', $currentYear)->whereMonth('created_at', $m)->count();
        $monthlyBlotter[] = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->whereMonth('created_at', $m)->count();
    }

    $docsByType    = \App\Models\Document::whereYear('created_at', $currentYear)->selectRaw('document_type, count(*) as total')->groupBy('document_type')->pluck('total','document_type');
    $blotterByType = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->selectRaw('incident_type, count(*) as total')->groupBy('incident_type')->pluck('total','incident_type');
    $puroks        = \App\Models\Purok::withCount(['residents' => fn($q) => $q->where('residency_status','Active')])->orderBy('name')->get();

    $totalResidents  = \App\Models\Resident::count();
    $activeResidents = \App\Models\Resident::where('residency_status','Active')->count();
    $totalDocs       = \App\Models\Document::whereYear('created_at', $currentYear)->count();
    $totalBlotter    = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->count();
    $totalBusinesses = \App\Models\Business::where('status','Active')->count();
    $totalHouseholds = \App\Models\Household::count();
    $totalMale       = \App\Models\Resident::where('gender','Male')->count();
    $totalFemale     = \App\Models\Resident::where('gender','Female')->count();
    $totalVoters     = \App\Models\Resident::where('is_voter',true)->count();
    $totalSeniors    = \App\Models\Resident::where('is_senior',true)->count();
    $totalPwd        = \App\Models\Resident::where('is_pwd',true)->count();
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

{{-- STAT STRIP — match dashboard style --}}
<div class="grid-4 mb-6">
    @php $strips = [
        ['label'=>'Total Residents',  'value'=>number_format($totalResidents),  'icon'=>'fa-users',     'color'=>'#22c55e', 'bg'=>'#f0fdf4'],
        ['label'=>'Households',       'value'=>number_format($totalHouseholds), 'icon'=>'fa-house',     'color'=>'#3b82f6', 'bg'=>'#eff6ff'],
        ['label'=>'Docs This Year',   'value'=>number_format($totalDocs),       'icon'=>'fa-file-alt',  'color'=>'#f59e0b', 'bg'=>'#fffbeb'],
        ['label'=>'Active Blotter',   'value'=>number_format($totalBlotter),    'icon'=>'fa-gavel',     'color'=>'#ef4444', 'bg'=>'#fef2f2'],
        ['label'=>'Active Businesses','value'=>number_format($totalBusinesses), 'icon'=>'fa-store',     'color'=>'#f59e0b', 'bg'=>'#fffbeb'],
        ['label'=>'Male Residents',   'value'=>number_format($totalMale),       'icon'=>'fa-person',    'color'=>'#3b82f6', 'bg'=>'#eff6ff'],
        ['label'=>'Female Residents', 'value'=>number_format($totalFemale),     'icon'=>'fa-person',    'color'=>'#f97316', 'bg'=>'#fff7ed'],
        ['label'=>'Active Residents', 'value'=>number_format($activeResidents), 'icon'=>'fa-circle-check','color'=>'#22c55e','bg'=>'#f0fdf4'],
    ]; @endphp
    @foreach($strips as $s)
    <div class="stat-card">
        <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }}">
            <i class="fas {{ $s['icon'] }}"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $s['value'] }}</div>
            <div class="stat-label">{{ $s['label'] }}</div>
        </div>
    </div>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:300px 1fr;gap:20px;align-items:start">

    {{-- LEFT: Form --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-pdf" style="color:#ef4444"></i> Report Generator</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reports.generate') }}">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="form-label">Report Type</label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                            @foreach(['monthly'=>['Monthly','fa-calendar-day'],'quarterly'=>['Quarterly','fa-calendar-week'],'annual'=>['Annual','fa-calendar']] as $val=>[$lbl,$icon])
                            <label style="display:flex;flex-direction:column;align-items:center;gap:4px;padding:10px 6px;border:1.5px solid var(--border);border-radius:var(--radius);cursor:pointer;transition:all 0.15s;text-align:center"
                                   id="type-card-{{ $val }}" onclick="selectType('{{ $val }}')">
                                <input type="radio" name="report_type" value="{{ $val }}" style="display:none" {{ $val==='monthly'?'checked':'' }}>
                                <i class="fas {{ $icon }}" style="font-size:15px;color:var(--navy)"></i>
                                <span style="font-size:11px;font-weight:600;color:var(--text)">{{ $lbl }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Module</label>
                        <select name="report_module" class="form-control" required>
                            <option value="summary">📊 Full Summary</option>
                            <option value="residents">👥 Residents</option>
                            <option value="documents">📄 Documents</option>
                            <option value="blotter">⚖️ Blotter Cases</option>
                            <option value="businesses">🏪 Businesses</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-control" required>
                            @for($y=date('Y');$y>=2020;$y--)
                                <option value="{{ $y }}" {{ $y==date('Y')?'selected':'' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group mb-4" id="month-field">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-control">
                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i=>$m)
                                <option value="{{ $i+1 }}" {{ ($i+1)==date('n')?'selected':'' }}>{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-4" id="quarter-field" style="display:none">
                        <label class="form-label">Quarter</label>
                        <select name="quarter" class="form-control">
                            <option value="1">Q1 — Jan to Mar</option>
                            <option value="2">Q2 — Apr to Jun</option>
                            <option value="3">Q3 — Jul to Sep</option>
                            <option value="4">Q4 — Oct to Dec</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        <i class="fas fa-file-pdf"></i> Generate PDF
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick Generate --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt" style="color:var(--gold)"></i> Quick Generate</span>
            </div>
            <div class="card-body" style="padding:10px 12px;display:flex;flex-direction:column;gap:6px">
                @php $quick = [
                    ['label'=>'This Month — Summary',   'type'=>'monthly',   'module'=>'summary',   'extra'=>'month='.date('n').'&year='.date('Y')],
                    ['label'=>'This Month — Documents', 'type'=>'monthly',   'module'=>'documents', 'extra'=>'month='.date('n').'&year='.date('Y')],
                    ['label'=>'This Month — Blotter',   'type'=>'monthly',   'module'=>'blotter',   'extra'=>'month='.date('n').'&year='.date('Y')],
                    ['label'=>'This Quarter — Summary', 'type'=>'quarterly', 'module'=>'summary',   'extra'=>'quarter='.ceil(date('n')/3).'&year='.date('Y')],
                    [' label'=>date('Y').' Annual',     'type'=>'annual',    'module'=>'summary',   'extra'=>'year='.date('Y')],
                ]; @endphp
                @foreach($quick as $q)
                <form method="POST" action="{{ route('reports.generate') }}">
                    @csrf
                    <input type="hidden" name="report_type"   value="{{ $q['type'] }}">
                    <input type="hidden" name="report_module" value="{{ $q['module'] }}">
                    @foreach(explode('&', $q['extra'] ?? 'year='.date('Y')) as $param)
                        @if(str_contains($param,'='))
                            @php [$k,$v]=explode('=',$param,2); @endphp
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:flex-start;padding:7px 10px;font-size:12px;gap:8px">
                        <i class="fas fa-file-pdf" style="color:#ef4444;font-size:11px;flex-shrink:0"></i>
                        {{ $q['label'] ?? $q[' label'] ?? '' }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RIGHT: Charts --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Documents trend --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar" style="color:var(--gold)"></i> Documents Issued — {{ $currentYear }}</span>
            </div>
            <div class="card-body">
                <canvas id="docsChart" height="100"></canvas>
            </div>
        </div>

        {{-- Blotter trend --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar" style="color:var(--gold)"></i> Blotter Cases Filed — {{ $currentYear }}</span>
            </div>
            <div class="card-body">
                <canvas id="blotterChart" height="100"></canvas>
            </div>
        </div>

        {{-- Two doughnuts --}}
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-pie" style="color:var(--gold)"></i> Docs by Type</span>
                </div>
                <div class="card-body" style="display:flex;justify-content:center">
                    <canvas id="docTypeChart" height="200" style="max-width:260px"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-pie" style="color:var(--gold)"></i> Blotter by Type</span>
                </div>
                <div class="card-body" style="display:flex;justify-content:center">
                    <canvas id="blotterTypeChart" height="200" style="max-width:260px"></canvas>
                </div>
            </div>
        </div>

        {{-- Demographics --}}
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-bar" style="color:var(--gold)"></i> Demographics</span>
                </div>
                <div class="card-body">
                    @php $demos = [
                        ['label'=>'Voters',      'value'=>$totalVoters,  'total'=>$activeResidents, 'color'=>'#0D2144'],
                        ['label'=>'Seniors',     'value'=>$totalSeniors, 'total'=>$activeResidents, 'color'=>'#C8861A'],
                        ['label'=>'PWD',         'value'=>$totalPwd,     'total'=>$activeResidents, 'color'=>'#3b82f6'],
                        ['label'=>'Male',        'value'=>$totalMale,    'total'=>$totalResidents,  'color'=>'#3b82f6'],
                        ['label'=>'Female',      'value'=>$totalFemale,  'total'=>$totalResidents,  'color'=>'#f97316'],
                    ]; @endphp
                    <div style="display:flex;flex-direction:column;gap:12px">
                        @foreach($demos as $d)
                        @php $pct = $d['total'] > 0 ? round(($d['value']/$d['total'])*100) : 0; @endphp
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:12.5px">
                                <span style="color:var(--text)">{{ $d['label'] }}</span>
                                <span style="color:var(--text-muted)">{{ number_format($d['value']) }} ({{ $pct }}%)</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $d['color'] }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Purok bar --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-location-dot" style="color:var(--gold)"></i> Residents by Purok</span>
                </div>
                <div class="card-body">
                    <canvas id="purokChart" height="200"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
function selectType(val) {
    document.querySelectorAll('[id^="type-card-"]').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background  = '';
    });
    const card = document.getElementById('type-card-' + val);
    card.style.borderColor = 'var(--gold)';
    card.style.background  = 'rgba(200,134,26,0.05)';
    card.querySelector('input[type=radio]').checked = true;
    document.getElementById('month-field').style.display   = val === 'monthly'   ? 'block' : 'none';
    document.getElementById('quarter-field').style.display = val === 'quarterly' ? 'block' : 'none';
}
selectType('monthly');

const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// Documents bar
new Chart(document.getElementById('docsChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Documents',
            data: {!! json_encode($monthlyDocs) !!},
            backgroundColor: 'rgba(34,197,94,0.15)',
            borderColor: '#22c55e',
            borderWidth: 1.5,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks:{ color:'#9CA3AF', font:{size:10} }, grid:{ color:'#F3F4F6' } },
            y: { ticks:{ color:'#9CA3AF', font:{size:10}, stepSize:1 }, grid:{ color:'#F3F4F6' }, beginAtZero:true }
        }
    }
});

// Blotter bar
new Chart(document.getElementById('blotterChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Blotter',
            data: {!! json_encode($monthlyBlotter) !!},
            backgroundColor: 'rgba(239,68,68,0.12)',
            borderColor: '#ef4444',
            borderWidth: 1.5,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks:{ color:'#9CA3AF', font:{size:10} }, grid:{ color:'#F3F4F6' } },
            y: { ticks:{ color:'#9CA3AF', font:{size:10}, stepSize:1 }, grid:{ color:'#F3F4F6' }, beginAtZero:true }
        }
    }
});

// Docs by type doughnut
const dColors = ['#0D2144','#C8861A','#22c55e','#3b82f6','#7c3aed','#ef4444','#f97316','#0891b2'];
new Chart(document.getElementById('docTypeChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($docsByType->keys()) !!},
        datasets: [{ data: {!! json_encode($docsByType->values()) !!}, backgroundColor: dColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout:'60%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8, color:'#4B5563' } } } }
});

// Blotter by type doughnut
const bColors = ['#ef4444','#C8861A','#0D2144','#3b82f6','#7c3aed','#22c55e','#f97316','#0891b2'];
new Chart(document.getElementById('blotterTypeChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($blotterByType->keys()) !!},
        datasets: [{ data: {!! json_encode($blotterByType->values()) !!}, backgroundColor: bColors, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout:'60%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8, color:'#4B5563' } } } }
});

// Purok horizontal bar
new Chart(document.getElementById('purokChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($puroks->pluck('name')) !!},
        datasets: [{
            label: 'Residents',
            data: {!! json_encode($puroks->pluck('residents_count')) !!},
            backgroundColor: 'rgba(13,33,68,0.1)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 3,
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