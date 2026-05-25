@extends('layouts.app')
@section('title', 'Blotter Cases')
@section('page-title', 'Blotter Cases')
@section('page-subtitle', 'Incident and complaint records')
@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Blotter Cases</h1>
            <p class="page-subtitle">Incident and complaint records</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('blotter')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a id="btnExportPdf" href="{{ route('export.pdf', 'blotter') }}" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a id="btnExportExcel" href="{{ route('export.excel', 'blotter') }}" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="{{ route('blotter.create') }}" class="btn btn-primary">
            <i class="fas fa-file-plus"></i> File Case
        </a>
    </div>
</div>

<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-gavel"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statBlotTotal">{{ number_format(\App\Models\BlotterCase::count()) }}</div>
            <div class="stat-label">Total Cases</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Active')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active'] ?? 0) }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Under Investigation')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-magnifying-glass"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Under Investigation'] ?? 0) }}</div>
            <div class="stat-label">Under Investigation</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Settled')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-handshake"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Settled'] ?? 0) }}</div>
            <div class="stat-label">Settled</div>
        </div>
    </div>
</div>

{{-- Source Quick-Filter Chip Strip --}}
<div class="no-print" style="display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap">
    <span style="font-size:12px;font-weight:600;color:var(--text-subtle);text-transform:uppercase;letter-spacing:.06em">Source:</span>
    <button type="button" class="src-chip src-chip-active" data-src=""
            style="height:30px;padding:0 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid var(--navy);background:var(--navy);color:#fff;transition:all .15s">
        All
    </button>
    <button type="button" class="src-chip" data-src="portal"
            style="height:30px;padding:0 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid #bfdbfe;background:#eff6ff;color:#1d4ed8;transition:all .15s">
        <i class="fas fa-globe" style="font-size:10px;margin-right:4px"></i>Portal
        <span id="srcChipPortalCount" style="margin-left:5px;background:#1d4ed8;color:#fff;border-radius:4px;
              padding:1px 7px;font-size:10px;font-weight:700">
            {{ \App\Models\BlotterCase::where('source','portal')->where('status','!=','Pending')->count() }}
        </span>
    </button>
    <button type="button" class="src-chip" data-src="walk-in"
            style="height:30px;padding:0 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);transition:all .15s">
        <i class="fas fa-walking" style="font-size:10px;margin-right:4px"></i>Walk-in
    </button>
</div>

{{-- Hidden source select — backing value for chip logic + DataTable AJAX --}}
<select id="sourceFilter" style="display:none">
    <option value=""></option>
    <option value="portal">Portal</option>
    <option value="walk-in">Walk-in</option>
</select>

{{-- Collapsible Filter Bar --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('blotter')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('blotter')">
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
            <span id="filterToggleText">Show Filters</span>
        </button>
    </div>
    <div id="filterPanel" style="display:none">
        <div class="card-body" style="padding:20px 22px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                               placeholder="Case number, complainant, respondent…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Type</label>
                    <select id="typeFilter">
                        <option value=""></option>
                        @foreach($incidentTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Case Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Date — From</label>
                    <input type="date" id="dateFrom" class="form-control">
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Date — To</label>
                    <input type="date" id="dateTo" class="form-control">
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <button type="button" id="resetBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-xmark"></i> Reset All Filters
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-gavel"></i> Case Records</span>
    </div>
    <div class="table-responsive">
        <table id="blotterTable" style="width:100%">
            <thead>
                <tr>
                    <th>Case No.</th>
                    <th>Incident Type</th>
                    <th>Complainant</th>
                    <th>Respondent</th>
                    <th>Incident Date</th>
                    <th>Status</th>
                    <th style="display:none"></th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Activate Case Modal (portal submissions) --}}
<div id="blotterActivateModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9700;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBlotterActivateModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-shield-halved" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Activate Blotter Case</span>
            </div>
            <button onclick="closeBlotterActivateModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:10px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Case No.</div>
                        <div id="blotterActivateNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Incident Type</div>
                        <div id="blotterActivateType" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Complainant</div>
                        <div id="blotterActivateComplainant" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Incident Date</div>
                        <div id="blotterActivateDate" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px">
                <label class="form-label" style="font-size:12.5px">Staff Notes <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                <textarea id="blotterActivateNotes" class="form-control" rows="3"
                          placeholder="e.g., Verified with complainant; proceeding with mediation…"></textarea>
            </div>
            <div id="blotterActivateError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeBlotterActivateModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="blotterActivateSaveBtn" onclick="saveBlotterActivate()" class="btn btn-success">
                    <i class="fas fa-shield-halved"></i> Activate Case
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Quick Status Modal --}}
<div id="blotterStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBlotterStatusModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Case Status</span>
            </div>
            <button onclick="closeBlotterStatusModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Case: <strong id="blotterStatusNum" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="blotterStatusSelect" class="form-control">
                    @foreach($statuses as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Resolution Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional)</span></label>
                <textarea id="blotterStatusNotes" class="form-control" rows="3"
                          placeholder="e.g., Parties agreed to settle — signed on May 10, 2026…"></textarea>
            </div>
            <div id="blotterStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeBlotterStatusModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="blotterStatusSaveBtn" onclick="saveBlotterStatus()" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Save Status
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* ── SaaS surface & stat card polish ──────────────────────────────── */
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }
#blotterTable_wrapper .dataTables_length,
#blotterTable_wrapper .dataTables_filter { display:none; }
#blotterTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate { padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#blotterTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

/* ── Filter Select2 — match residents blade ───────────────────── */
#filterPanel .select2-container { width: 100% !important; }
#filterPanel .select2-container--default .select2-selection--single,
#filterPanel .select2-container--default .select2-selection--multiple {
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); min-height: 38px;
}
#filterPanel .select2-container--default .select2-selection--single {
    padding: 0 32px 0 10px; display: flex; align-items: center;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text); font-size: 13.5px; padding: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: normal;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__placeholder { color: var(--text-subtle); }
#filterPanel .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; top: 0; right: 8px; }
.btn-export-filtered { border-color: var(--gold) !important; box-shadow: 0 0 0 2px rgba(200,134,26,0.18) !important; }
</style>
<script>
$(document).ready(function () {

    /* ── Select2 init ─────────────────────────────────────────────────── */
    /* Temporarily expose the hidden filter panel so Select2 measures real dimensions.
       The browser won't paint until after this synchronous block, so no visual flash. */
    var $fp = $('#filterPanel');
    var _fpW = $fp.parent().width();
    $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });

    const s2Single = { dropdownParent: $('body'), allowClear: true, width: '100%',
                       minimumResultsForSearch: 0,
                       language: { noResults: () => 'No matches' } };

    $('#typeFilter').select2($.extend({}, s2Single, { placeholder: 'All incident types…' }));
    $('#statusFilter').select2($.extend({}, s2Single, { placeholder: 'All statuses…' }));

    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#blotterTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('blotter.index') }}',
            data: function (d) {
                d.incident_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.date_from     = $('#dateFrom').val();
                d.date_to       = $('#dateTo').val();
                d.source        = $('#sourceFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',      name: 'case_number' },
            { data: 'type_col',        name: 'incident_type' },
            { data: 'complainant_col', name: 'complainant_name' },
            { data: 'respondent_col',  name: 'respondent_name' },
            { data: 'date_col',        name: 'incident_date' },
            { data: 'status_col',      name: 'status' },
            { data: 'updated_at',      name: 'updated_at', visible: false, searchable: false },
            { data: 'actions',         name: 'actions', orderable: false, searchable: false },
        ],
        order: [[6, 'desc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#blotterTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-gavel"></i><p>No blotter cases found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No cases match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── Axios DELETE ─────────────────────────────────────────────────── */
    $('#blotterTable').on('click', '.blotter-delete-btn', function () {
        const $btn = $(this);
        const url  = $btn.data('url');
        const num  = $btn.data('num');
        const name = $btn.data('name');

        bmsConfirm({
            title:   'Delete Blotter Case',
            message: 'Delete case ' + num + ' — ' + name + '? This will permanently remove the record and any attachments.',
            ok:      'Delete',
        }, function () {
            const icon = $btn.find('i');
            const orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin');
            $btn.prop('disabled', true);

            axios.delete(url, { data: { _token: '{{ csrf_token() }}' } })
                .then(function (res) {
                    table.row($btn.closest('tr')).remove().draw(false);
                    bmsStatDecrement('statBlotTotal');
                    bmsToast(res.data.message || 'Case deleted.', 'success');
                    if (window.refreshPortalBadges) window.refreshPortalBadges();
                })
                .catch(function () {
                    icon.attr('class', orig);
                    $btn.prop('disabled', false);
                    bmsToast('Could not delete case.', 'error');
                });
        });
    });

    /* ── URL persistence ──────────────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s','date_from','date_to','source'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('incident_type');
        url.searchParams.delete('status');
        if ($('#searchInput').val()) url.searchParams.set('s', $('#searchInput').val());
        if ($('#dateFrom').val())    url.searchParams.set('date_from', $('#dateFrom').val());
        if ($('#dateTo').val())      url.searchParams.set('date_to', $('#dateTo').val());
        if ($('#typeFilter').val())   url.searchParams.set('incident_type', $('#typeFilter').val());
        if ($('#statusFilter').val()) url.searchParams.set('status', $('#statusFilter').val());
        if ($('#sourceFilter').val()) url.searchParams.set('source', $('#sourceFilter').val());
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))         { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('date_from')) { $('#dateFrom').val(p.get('date_from')); any = true; }
        if (p.get('date_to'))   { $('#dateTo').val(p.get('date_to')); any = true; }
        const type = p.get('incident_type'), status = p.get('status');
        if (type)   { $('#typeFilter').val(type).trigger('change.select2'); any = true; }
        if (status) { $('#statusFilter').val(status).trigger('change.select2'); any = true; }
        if (p.get('source')) {
            var src = p.get('source');
            $('#sourceFilter').val(src);
            $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === src); });
            any = true;
        }
        return any;
    }
    var _pdfBase  = '{{ route('export.pdf',   'blotter') }}';
    var _xlsxBase = '{{ route('export.excel', 'blotter') }}';

    function _syncExportUrls() {
        var p = {};
        var s  = $('#searchInput').val();   if (s)  p.s = s;
        var st = $('#statusFilter').val();  if (st) p.status = st;
        var tp = $('#typeFilter').val();    if (tp) p.incident_type = tp;
        var df = $('#dateFrom').val();      if (df) p.date_from = df;
        var dt = $('#dateTo').val();        if (dt) p.date_to = dt;
        var sc = $('#sourceFilter').val();  if (sc) p.source = sc;
        var qs = Object.keys(p).length ? '?' + $.param(p) : '';
        $('#btnExportPdf').attr('href', _pdfBase + qs)
            .attr('title', qs ? 'Export filtered results' : 'Export PDF');
        $('#btnExportExcel').attr('href', _xlsxBase + qs)
            .attr('title', qs ? 'Export filtered results' : 'Export Excel');
        $('#btnExportPdf, #btnExportExcel').toggleClass('btn-export-filtered', Object.keys(p).length > 0);
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if ($('#typeFilter').val())                     n++;
        if ($('#statusFilter').val())                   n++;
        if ($('#dateFrom').val() || $('#dateTo').val()) n++;
        if ($('#sourceFilter').val())                   n++;
        const badge = document.getElementById('filterBadge');
        const chip  = document.getElementById('headerFilterChip');
        const chipN = document.getElementById('headerFilterCount');
        if (n > 0) {
            badge.textContent = n + (n === 1 ? ' filter active' : ' filters active');
            badge.style.display = '';
            chipN.textContent = n;
            chip.style.display = '';
        } else {
            badge.style.display = 'none';
            chip.style.display  = 'none';
        }
        _syncExportUrls();
    }
    window.toggleFilters = function (key) {
        const panel  = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        localStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };
    window.quickFilter = function (filterId, values) {
        if (filterId === 'sourceFilter') {
            $('#sourceFilter').val(values);
            $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === values); });
        } else {
            $('#' + filterId).val(values).trigger('change');
            if (document.getElementById('filterPanel').style.display === 'none') {
                document.getElementById('filterPanel').style.display = 'block';
                document.getElementById('filterToggleText').textContent = 'Hide Filters';
                localStorage.setItem('fp_blotter', '1');
            }
        }
        saveToUrl(); table.ajax.reload();
    };
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || localStorage.getItem('fp_blotter') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#typeFilter, #statusFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#dateFrom, #dateTo').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#dateFrom, #dateTo').val('');
        $('#sourceFilter').val(null);
        $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === ''); });
        saveToUrl(); table.ajax.reload();
    });
});

/* ── Source chip helpers ──────────────────────────────────────────── */
function _syncChip($chip, isActive) {
    var src = $chip.data('src');
    $chip.toggleClass('src-chip-active', isActive);
    if (src === '') {
        $chip.css({ background: isActive ? 'var(--navy)' : '#fff',
                    color:       isActive ? '#fff'        : 'var(--text-muted)',
                    borderColor: isActive ? 'var(--navy)' : 'var(--border)' });
    } else if (src === 'portal') {
        $chip.css({ background: isActive ? '#1d4ed8' : '#eff6ff',
                    color:       isActive ? '#fff'    : '#1d4ed8',
                    borderColor: isActive ? '#1d4ed8' : '#bfdbfe' });
    } else {
        $chip.css({ background: isActive ? 'var(--navy)'  : 'var(--surface)',
                    color:       isActive ? '#fff'         : 'var(--text-muted)',
                    borderColor: isActive ? 'var(--navy)'  : 'var(--border)' });
    }
}

$(document).on('click', '.src-chip', function () {
    var src = $(this).data('src');
    $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === src); });
    $('#sourceFilter').val(src || null);
    const url = new URL(window.location);
    url.searchParams.delete('source');
    if (src) url.searchParams.set('source', src);
    history.replaceState({}, '', url);
    var n = 0;
    if ($('#searchInput').val())                    n++;
    if ($('#typeFilter').val())                     n++;
    if ($('#statusFilter').val())                   n++;
    if ($('#dateFrom').val() || $('#dateTo').val()) n++;
    if (src) n++;
    var badge = document.getElementById('filterBadge');
    var chip  = document.getElementById('headerFilterChip');
    var chipN = document.getElementById('headerFilterCount');
    if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; chipN.textContent = n; chip.style.display = ''; }
    else { badge.style.display = 'none'; chip.style.display = 'none'; }
    $('#blotterTable').DataTable().ajax.reload();
});

/* ── Activate Case Modal (portal submissions) ────────────────────── */
$('#blotterTable').on('click', '.blotter-activate-btn', function () {
    var $btn = $(this);
    document.getElementById('blotterActivateNum').textContent        = $btn.data('num');
    document.getElementById('blotterActivateType').textContent       = $btn.data('type');
    document.getElementById('blotterActivateComplainant').textContent = $btn.data('complainant');
    document.getElementById('blotterActivateDate').textContent       = $btn.data('date');
    document.getElementById('blotterActivateNotes').value            = '';
    document.getElementById('blotterActivateError').style.display    = 'none';
    window._blotterActivateUrl = $btn.data('url');
    document.getElementById('blotterActivateModal').style.display    = 'flex';
});

function closeBlotterActivateModal() {
    document.getElementById('blotterActivateModal').style.display = 'none';
    window._blotterActivateUrl = null;
}

function saveBlotterActivate() {
    var btn = document.getElementById('blotterActivateSaveBtn');
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Activating…';
    document.getElementById('blotterActivateError').style.display = 'none';

    axios.post(window._blotterActivateUrl, {
        notes:  document.getElementById('blotterActivateNotes').value || null,
        _token: '{{ csrf_token() }}',
    })
    .then(function (res) {
        closeBlotterActivateModal();
        $('#blotterTable').DataTable().ajax.reload(null, false);
        bmsToast(res.data.message || 'Case activated.', 'success');
        if (window.refreshPortalBadges) window.refreshPortalBadges();
    })
    .catch(function (err) {
        var msg = err.response?.data?.message || 'Failed to activate case.';
        document.getElementById('blotterActivateError').textContent   = msg;
        document.getElementById('blotterActivateError').style.display = '';
        if (err.response?.data?.view_url) {
            bmsToast('Already activated.', 'info');
            setTimeout(() => window.open(err.response.data.view_url, '_blank'), 1200);
            closeBlotterActivateModal();
        }
    })
    .finally(function () {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-shield-halved"></i> Activate Case';
    });
}

/* ── Quick Status Modal ───────────────────────────────────────────── */
var _blotterStatusId = null;

$(document).on('click', '#blotterTable .blotter-status-btn', function () {
    _blotterStatusId = $(this).data('id');
    document.getElementById('blotterStatusNum').textContent = $(this).data('num');
    document.getElementById('blotterStatusSelect').value    = $(this).data('status');
    document.getElementById('blotterStatusNotes').value     = $(this).data('notes') || '';
    document.getElementById('blotterStatusError').style.display = 'none';
    document.getElementById('blotterStatusModal').style.display = 'flex';
});

function closeBlotterStatusModal() {
    document.getElementById('blotterStatusModal').style.display = 'none';
    _blotterStatusId = null;
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeBlotterStatusModal(); closeBlotterActivateModal(); }
});

function saveBlotterStatus() {
    if (!_blotterStatusId) return;
    const btn    = document.getElementById('blotterStatusSaveBtn');
    const errDiv = document.getElementById('blotterStatusError');
    const status = document.getElementById('blotterStatusSelect').value;
    const notes  = document.getElementById('blotterStatusNotes').value;

    errDiv.style.display = 'none';
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/blotter/' + _blotterStatusId + '/status', { status: status, resolution_notes: notes })
        .then(function (res) {
            const clsMap = {
                'Active':               'badge-red',
                'Under Investigation':  'badge-yellow',
                'Mediated':             'badge-blue',
                'Settled':              'badge-green',
                'Closed':               'badge-gray',
                'Referred to Higher Authority': 'badge-orange',
            };
            const row = $('button.blotter-status-btn[data-id="' + _blotterStatusId + '"]').closest('tr');
            row.find('.blotter-status-btn').data('status', res.data.status).data('notes', notes);
            row.find('td .badge:not(.badge-navy,.badge-red[title])').first().each(function () {
                $(this).removeClass('badge-red badge-yellow badge-blue badge-green badge-gray badge-orange')
                       .addClass(clsMap[res.data.status] || 'badge-gray').text(res.data.status);
            });
            closeBlotterStatusModal();
            bmsToast(res.data.message, 'success');
            $('#blotterTable').DataTable().ajax.reload(null, false);
            if (window.refreshPortalBadges) window.refreshPortalBadges();
        })
        .catch(function (err) {
            errDiv.textContent   = err.response?.data?.message || 'Failed to update status.';
            errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status';
        });
}

</script>
@endpush
