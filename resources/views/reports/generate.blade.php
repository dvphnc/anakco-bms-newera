@extends('layouts.app')
@section('title', 'Generate Reports')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Generate Reports</h1>
        <p class="page-subtitle">Monthly, quarterly, and annual reports for Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-chart-bar"></i> Analytics
        </a>
    </div>
</div>

<div class="grid-2" style="grid-template-columns:1fr 1.5fr;align-items:start">

    {{-- Form --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-pdf" style="color:#dc2626"></i> Report Generator</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('reports.generate') }}" id="reportForm">
                @csrf

                <div class="form-group mb-4">
                    <label class="form-label">Report Type <span style="color:var(--crimson)">*</span></label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                        @foreach(['monthly'=>['Monthly','fa-calendar-day'],'quarterly'=>['Quarterly','fa-calendar-week'],'annual'=>['Annual','fa-calendar']] as $val=>[$lbl,$icon])
                        <label style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:12px 8px;border:2px solid var(--border);border-radius:var(--radius);cursor:pointer;transition:all 0.15s"
                               id="type-card-{{ $val }}"
                               onclick="selectType('{{ $val }}')">
                            <input type="radio" name="report_type" value="{{ $val }}" style="display:none" {{ $val === 'monthly' ? 'checked' : '' }}>
                            <i class="fas {{ $icon }}" style="font-size:18px;color:var(--navy)"></i>
                            <span style="font-size:12px;font-weight:600;color:var(--text)">{{ $lbl }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Module <span style="color:var(--crimson)">*</span></label>
                    <select name="report_module" class="form-control" required>
                        <option value="summary">📊 Full Summary Report</option>
                        <option value="residents">👥 Residents</option>
                        <option value="documents">📄 Documents Issued</option>
                        <option value="blotter">⚖️ Blotter Cases</option>
                        <option value="businesses">🏪 Business Permits</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Year <span style="color:var(--crimson)">*</span></label>
                    <select name="year" class="form-control" required>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Month (shown for monthly) --}}
                <div class="form-group mb-4" id="month-field">
                    <label class="form-label">Month <span style="color:var(--crimson)">*</span></label>
                    <select name="month" class="form-control">
                        @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                            <option value="{{ $i+1 }}" {{ ($i+1) == date('n') ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Quarter (shown for quarterly) --}}
                <div class="form-group mb-4" id="quarter-field" style="display:none">
                    <label class="form-label">Quarter <span style="color:var(--crimson)">*</span></label>
                    <select name="quarter" class="form-control">
                        <option value="1">Q1 — January to March</option>
                        <option value="2">Q2 — April to June</option>
                        <option value="3">Q3 — July to September</option>
                        <option value="4">Q4 — October to December</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%">
                    <i class="fas fa-file-pdf"></i> Generate PDF Report
                </button>
            </form>
        </div>
    </div>

    {{-- Quick generate cards --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Quick Generate</span>
            </div>
            <div class="card-body">
                <p style="font-size:12.5px;color:var(--text-muted);margin-bottom:16px">One-click common reports for the current period.</p>
                <div style="display:flex;flex-direction:column;gap:8px">
                    @php
                        $quick = [
                            ['label'=>'This Month — Full Summary',   'type'=>'monthly',   'module'=>'summary',   'extra'=>'month='.date('n').'&year='.date('Y'),'color'=>'var(--navy)'],
                            ['label'=>'This Month — Documents',       'type'=>'monthly',   'module'=>'documents', 'extra'=>'month='.date('n').'&year='.date('Y'),'color'=>'#16a34a'],
                            ['label'=>'This Month — Blotter Cases',  'type'=>'monthly',   'module'=>'blotter',   'extra'=>'month='.date('n').'&year='.date('Y'),'color'=>'var(--crimson)'],
                            ['label'=>'This Quarter — Full Summary', 'type'=>'quarterly', 'module'=>'summary',   'extra'=>'quarter='.ceil(date('n')/3).'&year='.date('Y'),'color'=>'var(--gold)'],
                            ['label'=>date('Y').' Annual Summary',   'type'=>'annual',    'module'=>'summary',   'extra'=>'year='.date('Y'),'color'=>'#2563eb'],
                            ['label'=>date('Y').' Annual Residents', 'type'=>'annual',    'module'=>'residents', 'extra'=>'year='.date('Y'),'color'=>'#7c3aed'],
                        ];
                    @endphp
                    @foreach($quick as $q)
                    <form method="POST" action="{{ route('reports.generate') }}">
                        @csrf
                        <input type="hidden" name="report_type"   value="{{ $q['type'] }}">
                        <input type="hidden" name="report_module" value="{{ $q['module'] }}">
                        @foreach(explode('&', $q['extra']) as $param)
                            @php [$k,$v] = explode('=',$param); @endphp
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:flex-start;gap:10px">
                            <div style="width:8px;height:8px;border-radius:50%;background:{{ $q['color'] }};flex-shrink:0"></div>
                            <span style="font-size:12.5px">{{ $q['label'] }}</span>
                            <i class="fas fa-file-pdf" style="color:#dc2626;margin-left:auto"></i>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle"></i> Report Contents</span>
            </div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:8px;font-size:12.5px;color:var(--text-muted)">
                    <div><strong style="color:var(--text)">Full Summary</strong> — Population stats, document counts, blotter summary, business overview, demographics</div>
                    <div><strong style="color:var(--text)">Residents</strong> — New residents registered in the period with full details</div>
                    <div><strong style="color:var(--text)">Documents</strong> — All documents issued with type breakdown</div>
                    <div><strong style="color:var(--text)">Blotter</strong> — Cases filed with incident type breakdown</div>
                    <div><strong style="color:var(--text)">Businesses</strong> — Business permits issued/renewed</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function selectType(val) {
    document.querySelectorAll('[id^="type-card-"]').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background = 'var(--surface)';
    });
    const card = document.getElementById('type-card-' + val);
    card.style.borderColor = 'var(--gold)';
    card.style.background = 'rgba(200,134,26,0.06)';
    card.querySelector('input[type=radio]').checked = true;

    document.getElementById('month-field').style.display   = val === 'monthly'   ? 'block' : 'none';
    document.getElementById('quarter-field').style.display = val === 'quarterly' ? 'block' : 'none';
}
// Init
selectType('monthly');
</script>
@endpush