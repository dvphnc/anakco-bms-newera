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
    $puroks        = \App\Models\Purok::withCount(['residents' => fn($q) => $q->where('residency_status','Active')])->orderByDesc('residents_count')->get();

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
    $totalSoloParent = \App\Models\Resident::where('is_solo_parent',true)->count();
    $total4ps        = \App\Models\Resident::where('is_4ps',true)->count();

    $genderTotal = $totalMale + $totalFemale ?: 1;
    $malePct     = round(($totalMale / $genderTotal) * 100);
    $femalePct   = 100 - $malePct;

    $todayDocs      = \App\Models\Document::whereDate('created_at', today())->count();
    $todayResidents = \App\Models\Resident::whereDate('created_at', today())->count();
    $todayBlotter   = \App\Models\BlotterCase::whereDate('created_at', today())->count();
    $thisMonthDocs  = \App\Models\Document::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count();
    $thisMonthBlt   = \App\Models\BlotterCase::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count();
    $thisMonthRes   = \App\Models\Resident::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count();

    $quick = [
        ['label' => 'This Month · Summary',   'short' => 'Summary',   'type' => 'monthly',   'module' => 'summary',   'month' => date('n'), 'year' => date('Y')],
        ['label' => 'This Month · Documents',  'short' => 'Documents', 'type' => 'monthly',   'module' => 'documents', 'month' => date('n'), 'year' => date('Y')],
        ['label' => 'This Month · Blotter',    'short' => 'Blotter',   'type' => 'monthly',   'module' => 'blotter',   'month' => date('n'), 'year' => date('Y')],
        ['label' => 'This Quarter · Summary',  'short' => 'Q Summary', 'type' => 'quarterly', 'module' => 'summary',   'quarter' => (int)ceil(date('n')/3), 'year' => date('Y')],
        ['label' => date('Y').' Annual',       'short' => 'Annual',    'type' => 'annual',    'module' => 'summary',   'year' => date('Y')],
    ];
@endphp

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Generate Reports</h1>
        <p class="page-subtitle">Monthly, quarterly, and annual — Barangay New Era {{ $currentYear }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-chart-bar"></i> Analytics
        </a>
    </div>
</div>

{{-- PDF Generation Overlay --}}
<div id="pdf-overlay" style="display:none;position:fixed;inset:0;background:rgba(13,33,68,0.62);z-index:9999;align-items:center;justify-content:center;flex-direction:column;gap:16px">
    <div style="width:56px;height:56px;border:4px solid rgba(200,134,26,0.3);border-top-color:#C8861A;border-radius:50%;animation:bms-pdf-spin 0.75s linear infinite"></div>
    <div style="color:#fff;font-size:14px;font-weight:600;letter-spacing:0.04em">Generating PDF…</div>
</div>

{{-- STAT STRIP --}}
<div class="grid-4 mb-6">
    @php $strips = [
        ['label' => 'Total Residents',         'value' => number_format($totalResidents),  'icon' => 'fa-users',        'color' => '#0D2144', 'bg' => 'rgba(13,33,68,0.08)'],
        ['label' => 'Households',              'value' => number_format($totalHouseholds), 'icon' => 'fa-house',        'color' => '#C8861A', 'bg' => 'rgba(200,134,26,0.1)'],
        ['label' => 'Documents '.$currentYear, 'value' => number_format($totalDocs),       'icon' => 'fa-file-alt',     'color' => '#0D2144', 'bg' => 'rgba(13,33,68,0.08)'],
        ['label' => 'Blotter '.$currentYear,   'value' => number_format($totalBlotter),    'icon' => 'fa-gavel',        'color' => '#9b1c1c', 'bg' => 'rgba(155,28,28,0.08)'],
        ['label' => 'Active Businesses',       'value' => number_format($totalBusinesses), 'icon' => 'fa-store',        'color' => '#C8861A', 'bg' => 'rgba(200,134,26,0.1)'],
        ['label' => 'Male Residents',          'value' => number_format($totalMale),       'icon' => 'fa-person',       'color' => '#0D2144', 'bg' => 'rgba(13,33,68,0.08)'],
        ['label' => 'Female Residents',        'value' => number_format($totalFemale),     'icon' => 'fa-person-dress', 'color' => '#8b4a68', 'bg' => 'rgba(139,74,104,0.08)'],
        ['label' => 'Active Residents',        'value' => number_format($activeResidents), 'icon' => 'fa-circle-check', 'color' => '#2e6b47', 'bg' => 'rgba(46,107,71,0.08)'],
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

{{-- MAIN LAYOUT --}}
<div style="display:grid;grid-template-columns:300px minmax(0,1fr);gap:20px;align-items:start">

    {{-- ===== LEFT COLUMN ===== --}}
    <div id="left-col" style="display:flex;flex-direction:column;gap:14px">

        {{-- Quick Generate Pills --}}
        <div style="display:flex;gap:6px;overflow-x:auto;padding-bottom:2px;flex-wrap:nowrap">
            @foreach($quick as $q)
            <div style="flex-shrink:0;position:relative">
                <input type="hidden" class="qg-type"    value="{{ $q['type'] }}">
                <input type="hidden" class="qg-module"  value="{{ $q['module'] }}">
                <input type="hidden" class="qg-year"    value="{{ $q['year'] }}">
                <input type="hidden" class="qg-month"   value="{{ $q['month']   ?? '' }}">
                <input type="hidden" class="qg-quarter" value="{{ $q['quarter'] ?? '' }}">
                <button type="button" title="{{ $q['label'] }}"
                        onclick="quickGenerate(this)"
                        style="display:inline-flex;align-items:center;gap:5px;padding:6px 11px;font-size:12px;font-weight:600;border-radius:var(--radius-sm);border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);cursor:pointer;white-space:nowrap;font-family:'Poppins',sans-serif;transition:all 0.15s"
                        onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)'"
                        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)'">
                    <i class="fas fa-file-pdf" style="color:#9b3535;font-size:11px"></i>{{ $q['short'] }}
                </button>
            </div>
            @endforeach
        </div>

        {{-- Form Card --}}
        <div class="card">
            <div class="card-header" style="padding:14px 20px">
                <span class="card-title" style="font-size:13px">
                    <i class="fas fa-sliders" style="color:var(--gold)"></i> Report Generator
                </span>
            </div>
            <div class="card-body" style="padding:18px 20px 22px">
                <form method="POST" action="{{ route('reports.generate') }}" id="reportForm">
                    @csrf
                    <input type="hidden" name="report_type" id="reportType" value="monthly">

                    {{-- Segmented Control --}}
                    <div class="form-group" style="margin-bottom:18px">
                        <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-subtle)">Report Type</label>
                        <div style="display:flex;border:1.5px solid var(--border);border-radius:var(--radius-sm);overflow:hidden;background:var(--surface2)">
                            <button type="button" id="seg-monthly" onclick="selectType('monthly')"
                                    style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;background:var(--navy);color:#fff;cursor:pointer;font-family:'Poppins',sans-serif;transition:all 0.15s">
                                <i class="fas fa-calendar-day" style="font-size:10px;margin-right:3px"></i>Monthly
                            </button>
                            <button type="button" id="seg-quarterly" onclick="selectType('quarterly')"
                                    style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-left:1px solid var(--border);background:transparent;color:var(--text-muted);cursor:pointer;font-family:'Poppins',sans-serif;transition:all 0.15s">
                                <i class="fas fa-calendar-week" style="font-size:10px;margin-right:3px"></i>Quarterly
                            </button>
                            <button type="button" id="seg-annual" onclick="selectType('annual')"
                                    style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-left:1px solid var(--border);background:transparent;color:var(--text-muted);cursor:pointer;font-family:'Poppins',sans-serif;transition:all 0.15s">
                                <i class="fas fa-calendar" style="font-size:10px;margin-right:3px"></i>Annual
                            </button>
                        </div>
                    </div>

                    {{-- Module --}}
                    <div class="form-group" style="margin-bottom:14px">
                        <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-subtle)">Module</label>
                        <select name="report_module" id="reportModule" class="form-control" required>
                            <option value="summary">Full Summary</option>
                            <option value="residents">Residents</option>
                            <option value="documents">Documents</option>
                            <option value="blotter">Blotter Cases</option>
                            <option value="businesses">Businesses</option>
                        </select>
                    </div>

                    {{-- Year --}}
                    <div class="form-group" style="margin-bottom:14px">
                        <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-subtle)">Year</label>
                        <select name="year" id="reportYear" class="form-control" required>
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Month --}}
                    <div id="month-field" class="form-group" style="margin-bottom:14px">
                        <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-subtle)">Month</label>
                        <select name="month" id="reportMonth" class="form-control">
                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $mon)
                                <option value="{{ $i + 1 }}" {{ ($i + 1) == date('n') ? 'selected' : '' }}>{{ $mon }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quarter --}}
                    <div id="quarter-field" class="form-group" style="display:none;margin-bottom:14px">
                        <label class="form-label" style="font-size:11px;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-subtle)">Quarter</label>
                        <select name="quarter" id="reportQuarter" class="form-control">
                            <option value="1">Q1 — Jan to Mar</option>
                            <option value="2">Q2 — Apr to Jun</option>
                            <option value="3">Q3 — Jul to Sep</option>
                            <option value="4">Q4 — Oct to Dec</option>
                        </select>
                    </div>

                    {{-- Axios Preview Chip --}}
                    <div id="preview-chip" style="margin-bottom:16px;padding:10px 13px;background:rgba(13,33,68,0.04);border:1px solid rgba(13,33,68,0.1);border-radius:var(--radius-sm);display:flex;align-items:center;gap:8px;min-height:38px">
                        <div id="preview-spinner" style="display:none;width:13px;height:13px;border:2px solid rgba(200,134,26,0.3);border-top-color:#C8861A;border-radius:50%;animation:bms-pdf-spin 0.75s linear infinite;flex-shrink:0"></div>
                        <i class="fas fa-crosshairs" id="preview-icon" style="font-size:11px;color:var(--navy-mid);flex-shrink:0"></i>
                        <span style="font-size:13px;color:var(--text)">
                            Targeting <strong id="preview-count" style="color:var(--navy)">—</strong>
                            <span id="preview-module" style="color:var(--text-muted)">records</span>
                            · <span id="preview-period" style="color:var(--text-muted)">—</span>
                        </span>
                    </div>

                    <button type="button" class="btn btn-primary" style="width:100%;justify-content:center"
                            onclick="doGeneratePdf(document.getElementById('reportForm'))">
                        <i class="fas fa-file-pdf"></i> Generate PDF Report
                    </button>
                </form>
            </div>
        </div>

        {{-- Snapshot Card --}}
        <div class="card" id="snapshot-card">
            <div class="card-header" style="padding:12px 20px">
                <span class="card-title" style="font-size:13px">
                    <i class="fas fa-calendar-day" style="color:var(--gold)"></i> Snapshot
                </span>
                <span style="font-size:12px;color:var(--text-muted)">{{ now()->format('M d, Y') }}</span>
            </div>
            <div class="card-body" style="padding:12px 16px">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Today</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:14px">
                    @php $todayItems = [
                        ['label' => 'Documents', 'value' => $todayDocs,      'color' => '#0D2144', 'bg' => 'rgba(13,33,68,0.05)'],
                        ['label' => 'Residents', 'value' => $todayResidents, 'color' => '#C8861A', 'bg' => 'rgba(200,134,26,0.07)'],
                        ['label' => 'Blotter',   'value' => $todayBlotter,   'color' => '#9b1c1c', 'bg' => 'rgba(155,28,28,0.05)'],
                    ]; @endphp
                    @foreach($todayItems as $t)
                    <div style="text-align:center;padding:8px 4px;background:{{ $t['bg'] }};border-radius:var(--radius-sm)">
                        <div style="font-size:18px;font-weight:800;color:{{ $t['color'] }}">{{ $t['value'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $t['label'] }}</div>
                    </div>
                    @endforeach
                </div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">{{ now()->format('F') }}</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px">
                    @php $monthItems = [
                        ['label' => 'Documents', 'value' => $thisMonthDocs, 'color' => '#0D2144', 'bg' => 'rgba(13,33,68,0.05)'],
                        ['label' => 'Residents', 'value' => $thisMonthRes,  'color' => '#C8861A', 'bg' => 'rgba(200,134,26,0.07)'],
                        ['label' => 'Blotter',   'value' => $thisMonthBlt,  'color' => '#9b1c1c', 'bg' => 'rgba(155,28,28,0.05)'],
                    ]; @endphp
                    @foreach($monthItems as $t)
                    <div style="text-align:center;padding:8px 4px;background:{{ $t['bg'] }};border-radius:var(--radius-sm)">
                        <div style="font-size:18px;font-weight:800;color:{{ $t['color'] }}">{{ $t['value'] }}</div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ $t['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    {{-- ===== RIGHT COLUMN ===== --}}
    <div id="right-col" style="display:flex;flex-direction:column;gap:16px">

        {{-- Monthly Trend --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-chart-bar" style="color:var(--gold)"></i> {{ $currentYear }} Monthly Trend
                </span>
                <div style="display:flex;gap:16px;font-size:13px;color:var(--text-muted)">
                    <span style="display:flex;align-items:center;gap:5px">
                        <span style="width:12px;height:3px;background:#0D2144;display:inline-block;border-radius:2px"></span> Documents
                    </span>
                    <span style="display:flex;align-items:center;gap:5px">
                        <span style="width:12px;height:3px;background:#C8861A;display:inline-block;border-radius:2px"></span> Blotter
                    </span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="160"></canvas>
            </div>
        </div>

        {{-- Doughnuts --}}
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <span class="card-title" style="font-size:13px">
                        <i class="fas fa-chart-pie" style="color:var(--gold)"></i> Documents by Type
                    </span>
                </div>
                <div class="card-body" style="display:flex;justify-content:center;padding:12px">
                    <canvas id="docTypeChart" style="max-height:260px"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title" style="font-size:13px">
                        <i class="fas fa-chart-pie" style="color:var(--gold)"></i> Blotter by Type
                    </span>
                </div>
                <div class="card-body" style="display:flex;justify-content:center;padding:12px">
                    <canvas id="blotterTypeChart" style="max-height:260px"></canvas>
                </div>
            </div>
        </div>

        {{-- Demographics + Purok Ranked List --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">

            {{-- Population Demographics --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title" style="font-size:13px">
                        <i class="fas fa-chart-bar" style="color:var(--gold)"></i> Population Demographics
                    </span>
                </div>
                <div class="card-body">
                    @php
                        $maxDemo = max($activeResidents, $totalVoters, $totalSeniors, $totalPwd, $totalSoloParent, $total4ps, $totalMale, $totalFemale) ?: 1;
                        $demos = [
                            ['label' => 'Active Residents',  'value' => $activeResidents, 'color' => '#0D2144'],
                            ['label' => 'Voters',            'value' => $totalVoters,     'color' => '#C8861A'],
                            ['label' => 'Senior Citizens',   'value' => $totalSeniors,    'color' => 'var(--gold)'],
                            ['label' => 'PWD',               'value' => $totalPwd,        'color' => '#3a5fa0'],
                            ['label' => 'Solo Parents',      'value' => $totalSoloParent, 'color' => '#a05828'],
                            ['label' => '4Ps Beneficiaries', 'value' => $total4ps,        'color' => '#8b2e2e'],
                            ['label' => 'Male',              'value' => $totalMale,       'color' => '#0D2144'],
                            ['label' => 'Female',            'value' => $totalFemale,     'color' => '#8b4a68'],
                        ];
                    @endphp
                    <div style="display:flex;flex-direction:column;gap:11px">
                        @foreach($demos as $d)
                        @php $pct = round(($d['value'] / $maxDemo) * 100); @endphp
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                                <span style="font-size:13px;color:var(--text)">{{ $d['label'] }}</span>
                                <span style="font-size:13px;font-weight:700;color:var(--text)">{{ number_format($d['value']) }}</span>
                            </div>
                            <div style="height:5px;background:var(--surface3);border-radius:3px;overflow:hidden">
                                <div style="height:100%;width:{{ $pct }}%;background:{{ $d['color'] }};border-radius:3px"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border)">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Gender Split</div>
                        <div style="display:flex;border-radius:99px;overflow:hidden;height:8px;margin-bottom:8px">
                            <div style="width:{{ $malePct }}%;background:var(--navy)"></div>
                            <div style="width:{{ $femalePct }}%;background:#8b4a68"></div>
                        </div>
                        <div style="display:flex;gap:16px">
                            <span style="font-size:12px;color:var(--text-muted);display:flex;align-items:center;gap:5px">
                                <span style="width:8px;height:8px;border-radius:50%;background:var(--navy);display:inline-block"></span>
                                Male — {{ number_format($totalMale) }} ({{ $malePct }}%)
                            </span>
                            <span style="font-size:12px;color:var(--text-muted);display:flex;align-items:center;gap:5px">
                                <span style="width:8px;height:8px;border-radius:50%;background:#8b4a68;display:inline-block"></span>
                                Female — {{ number_format($totalFemale) }} ({{ $femalePct }}%)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Residents by Purok — Ranked List --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title" style="font-size:13px">
                        <i class="fas fa-location-dot" style="color:var(--gold)"></i> Residents by Purok
                    </span>
                </div>
                <div class="card-body">
                    @php $purokTotal = $puroks->sum('residents_count') ?: 1; @endphp
                    <div style="display:flex;flex-direction:column;gap:12px">
                        @foreach($puroks as $i => $purok)
                        @php $pct = round(($purok->residents_count / $purokTotal) * 100); @endphp
                        <div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span style="font-size:11px;font-weight:700;color:var(--text-subtle);width:14px;text-align:right;flex-shrink:0">{{ $i + 1 }}</span>
                                    <span style="font-size:13px;color:var(--text)">{{ $purok->name }}</span>
                                </div>
                                <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                                    <span style="font-size:12px;color:var(--text-subtle)">{{ $pct }}%</span>
                                    <span style="font-size:13px;font-weight:700;color:var(--navy);min-width:28px;text-align:right">{{ $purok->residents_count }}</span>
                                </div>
                            </div>
                            <div style="height:4px;background:var(--surface3);border-radius:2px;overflow:hidden;margin-left:22px">
                                <div style="height:100%;width:{{ $pct }}%;background:var(--navy);border-radius:2px;opacity:0.75"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<style>
@keyframes bms-pdf-spin { to { transform: rotate(360deg); } }
#reportModule + .select2-container,
#reportYear   + .select2-container,
#reportMonth  + .select2-container,
#reportQuarter + .select2-container { width: 100% !important; }
</style>
<script>
$(function () {
    $('#reportModule, #reportYear, #reportMonth, #reportQuarter')
        .select2({ minimumResultsForSearch: -1, width: '100%' })
        .on('change', schedulePreview);
    selectType('monthly');
    fetchPreview();
});

function selectType(val) {
    ['monthly', 'quarterly', 'annual'].forEach(function (v) {
        var btn = document.getElementById('seg-' + v);
        if (v === val) {
            btn.style.background = 'var(--navy)';
            btn.style.color = '#fff';
        } else {
            btn.style.background = 'transparent';
            btn.style.color = 'var(--text-muted)';
        }
    });
    document.getElementById('reportType').value = val;
    document.getElementById('month-field').style.display   = val === 'monthly'   ? 'block' : 'none';
    document.getElementById('quarter-field').style.display = val === 'quarterly' ? 'block' : 'none';
    schedulePreview();
}

var _previewTimer = null;
function schedulePreview() {
    clearTimeout(_previewTimer);
    _previewTimer = setTimeout(fetchPreview, 350);
}

function fetchPreview() {
    var params = new URLSearchParams({
        report_type:   document.getElementById('reportType').value,
        report_module: $('#reportModule').val() || 'summary',
        year:          $('#reportYear').val()    || {{ $currentYear }},
        month:         $('#reportMonth').val()   || {{ $currentMonth }},
        quarter:       $('#reportQuarter').val() || 1,
    });
    document.getElementById('preview-icon').style.display    = 'none';
    document.getElementById('preview-spinner').style.display = 'block';
    axios.get('{{ route("reports.preview") }}?' + params.toString())
        .then(function (res) {
            document.getElementById('preview-count').textContent  = Number(res.data.count).toLocaleString();
            document.getElementById('preview-module').textContent = res.data.module;
            document.getElementById('preview-period').textContent = res.data.period;
        })
        .catch(function () {
            document.getElementById('preview-count').textContent = '—';
        })
        .finally(function () {
            document.getElementById('preview-spinner').style.display = 'none';
            document.getElementById('preview-icon').style.display    = '';
        });
}

function quickGenerate(btn) {
    var wrap = btn.parentElement;
    var fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    fd.append('report_type',   wrap.querySelector('.qg-type').value);
    fd.append('report_module', wrap.querySelector('.qg-module').value);
    fd.append('year',          wrap.querySelector('.qg-year').value);
    var month   = wrap.querySelector('.qg-month').value;
    var quarter = wrap.querySelector('.qg-quarter').value;
    if (month)   fd.append('month',   month);
    if (quarter) fd.append('quarter', quarter);

    var overlay = document.getElementById('pdf-overlay');
    overlay.style.display = 'flex';
    axios.post('{{ route("reports.generate") }}', fd, { responseType: 'blob' })
        .then(function (res) {
            var blob = new Blob([res.data], { type: 'application/pdf' });
            var url  = URL.createObjectURL(blob);
            window.open(url, '_blank');
            setTimeout(function () { URL.revokeObjectURL(url); }, 10000);
        })
        .catch(function () { bmsToast('Failed to generate PDF report. Please try again.', 'error'); })
        .finally(function () { overlay.style.display = 'none'; });
}

function doGeneratePdf(formEl) {
    var overlay = document.getElementById('pdf-overlay');
    overlay.style.display = 'flex';
    var fd = new FormData(formEl);
    axios.post(formEl.action, fd, { responseType: 'blob' })
        .then(function (res) {
            var blob = new Blob([res.data], { type: 'application/pdf' });
            var url  = URL.createObjectURL(blob);
            window.open(url, '_blank');
            setTimeout(function () { URL.revokeObjectURL(url); }, 10000);
        })
        .catch(function () {
            bmsToast('Failed to generate PDF report. Please try again.', 'error');
        })
        .finally(function () {
            overlay.style.display = 'none';
        });
    return false;
}

const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

new Chart(document.getElementById('trendChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Documents',
                data: {!! json_encode($monthlyDocs) !!},
                backgroundColor: 'rgba(13,33,68,0.12)',
                borderColor: '#0D2144',
                borderWidth: 1.5,
                borderRadius: 4,
                order: 2,
            },
            {
                label: 'Blotter',
                data: {!! json_encode($monthlyBlotter) !!},
                backgroundColor: 'rgba(200,134,26,0.15)',
                borderColor: '#C8861A',
                borderWidth: 1.5,
                borderRadius: 4,
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 10 } }, grid: { color: '#F3F4F6' } },
            y: { ticks: { color: '#9CA3AF', font: { size: 10 }, stepSize: 1 }, grid: { color: '#F3F4F6' }, beginAtZero: true }
        }
    }
});

const palette  = ['#0D2144','#C8861A','#3d7a55','#3a5fa0','#5e4b8b','#9b3535','#a05828','#2a7a8a'];
const bPalette = ['#9b3535','#C8861A','#0D2144','#3a5fa0','#5e4b8b','#3d7a55','#a05828','#2a7a8a'];

new Chart(document.getElementById('docTypeChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($docsByType->keys()->toArray()) !!},
        datasets: [{ data: {!! json_encode($docsByType->values()->toArray()) !!}, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout: '62%', plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8, color: '#6B7280', boxWidth: 10 } } } }
});

new Chart(document.getElementById('blotterTypeChart'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($blotterByType->keys()->toArray()) !!},
        datasets: [{ data: {!! json_encode($blotterByType->values()->toArray()) !!}, backgroundColor: bPalette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout: '62%', plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8, color: '#6B7280', boxWidth: 10 } } } }
});
</script>
@endpush
