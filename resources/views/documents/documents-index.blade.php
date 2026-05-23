@extends('layouts.app')
@section('title', 'Documents')
@section('page-title', 'Document Issuance')
@section('page-subtitle', 'Barangay certificates and clearances')
@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Document Issuance</h1>
            <p class="page-subtitle">Barangay certificates and clearances</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('documents')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a href="{{ route('export.pdf', 'documents') }}" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="{{ route('export.excel', 'documents') }}" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fas fa-file-circle-plus"></i> Issue Document
        </a>
    </div>
</div>

{{-- Stat cards --}}
@php
    $portalPending = \App\Models\Document::where('source','portal')
        ->whereIn('status',['Pending','Confirmed','Processing','Ready'])->count();
@endphp
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-file-lines"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statDocTotal">{{ number_format(\App\Models\Document::count()) }}</div>
            <div class="stat-label">Total Documents</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Pending')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Pending')->count()) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Processing')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-spinner"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Processing')->count()) }}</div>
            <div class="stat-label">Processing</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Released')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Released')->count()) }}</div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>

{{-- Portal Queue notice (shown only when there are active portal submissions) --}}
@if($portalPending > 0)
<div class="alert-tray open no-print mb-6">
    <div class="alert-tray-hdr" onclick="this.closest('.alert-tray').classList.toggle('open')">
        <i class="fas fa-globe tray-icon" style="color:var(--gold)"></i>
        <span><strong>{{ $portalPending }}</strong> Portal Submission{{ $portalPending > 1 ? 's' : '' }} active — pending release</span>
        <i class="fas fa-chevron-down tray-caret"></i>
    </div>
    <div class="alert-tray-body">
        {{-- How it works --}}
        <div class="alert-item" style="background:var(--navy-pale);border-color:var(--navy-border);flex-direction:column;align-items:flex-start;gap:10px">
            <div style="display:flex;align-items:flex-start;gap:10px;width:100%">
                <i class="fas fa-route" style="color:var(--navy);margin-top:2px;flex-shrink:0"></i>
                <div style="font-size:13px;color:var(--navy);line-height:1.6">
                    <strong>How portal requests flow into Document Issuance:</strong><br>
                    When a resident submits a request via the portal, it automatically appears here
                    <em>and</em> in the <strong>Appointments</strong> module simultaneously.<br>
                    <span style="color:var(--text-muted);font-size:12px">
                        Use <strong style="color:var(--navy)">Appointments → Document Requests</strong> to advance
                        the status (Pending → Processing → Ready). Once the resident picks up the document,
                        click <strong style="color:var(--navy)">Issue</strong> there — it marks the record Released here automatically.
                        You can also release it directly below using the <strong style="color:var(--navy)">Release</strong> row button.
                    </span>
                </div>
            </div>
            <div style="display:flex;gap:8px;padding-left:24px">
                <a href="{{ route('appointments.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-right"></i> Go to Appointments
                </a>
                <button class="btn btn-secondary btn-sm"
                        onclick="quickFilter('sourceFilter','portal')">
                    <i class="fas fa-filter" style="font-size:11px"></i> Show Portal Records
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Source Quick-Filter Chip Strip --}}
<div class="no-print" style="display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap">
    <span style="font-size:12px;font-weight:600;color:var(--text-subtle);text-transform:uppercase;letter-spacing:.06em">Source:</span>
    <button type="button" class="src-chip src-chip-active" data-src=""
            style="height:28px;padding:0 12px;border-radius:99px;font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid var(--navy);background:var(--navy);color:#fff;transition:all .15s">
        All
    </button>
    <button type="button" class="src-chip" data-src="portal"
            style="height:28px;padding:0 12px;border-radius:99px;font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid #bfdbfe;background:#eff6ff;color:#1d4ed8;transition:all .15s">
        <i class="fas fa-globe" style="font-size:10px;margin-right:4px"></i>Portal
        <span id="srcChipPortalCount" style="margin-left:5px;background:#1d4ed8;color:#fff;border-radius:99px;
              padding:1px 7px;font-size:10px;font-weight:700">
            {{ \App\Models\Document::where('source','portal')->whereNotIn('status',['Released','Cancelled'])->count() }}
        </span>
    </button>
    <button type="button" class="src-chip" data-src="walk-in"
            style="height:28px;padding:0 12px;border-radius:99px;font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);transition:all .15s">
        <i class="fas fa-walking" style="font-size:10px;margin-right:4px"></i>Walk-in
    </button>
</div>

{{-- Collapsible Filter Bar --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('documents')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('documents')">
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
                               placeholder="Document no., resident name…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Document Type</label>
                    <select id="typeFilter">
                        <option value=""></option>
                        @foreach($documentTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        @foreach(\App\Models\Document::$statuses as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
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

{{-- Hidden source select — backing value for chip logic + DataTable AJAX --}}
<select id="sourceFilter" style="display:none">
    <option value=""></option>
    <option value="portal">Portal</option>
    <option value="walk-in">Walk-in</option>
</select>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-lines"></i> Document Records</span>
    </div>
    <div class="table-responsive">
        <table id="documentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Doc No.</th>
                    <th>Resident</th>
                    <th>Document Type</th>
                    <th>Purpose</th>
                    <th>Fee</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>


@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* ── Spinner keyframe ─────────────────────────────────────────────── */
@keyframes doc-spin { to { transform: rotate(360deg); } }

/* ── Page & stat card polish ──────────────────────────────────────── */
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }

/* ── DataTables chrome ────────────────────────────────────────────── */
#documentsTable_wrapper .dataTables_length,
#documentsTable_wrapper .dataTables_filter { display: none; }
#documentsTable_wrapper .dataTables_info { font-size: 13px; color: var(--text-muted); padding: 12px 20px; }
#documentsTable_wrapper .dataTables_paginate { padding: 12px 20px; }
#documentsTable_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 10px; border-radius: 6px; font-size: 13px; cursor: pointer;
    border: 1px solid var(--border) !important; background: white !important;
    color: var(--text) !important; margin: 0 2px;
}
#documentsTable_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--navy) !important; color: white !important; border-color: var(--navy) !important;
}
#documentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background: var(--navy-pale) !important; color: var(--navy) !important;
}

/* ── Filter panel Select2 — matches blotter / businesses ─────────── */
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

    $('#typeFilter').select2($.extend({}, s2Single, { placeholder: 'All document types…' }));
    $('#statusFilter').select2($.extend({}, s2Single, { placeholder: 'All statuses…' }));

    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#documentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('documents.index') }}',
            data: function (d) {
                d.document_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.source        = $('#sourceFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',   name: 'doc_number' },
            { data: 'resident_col', name: 'resident_id',   orderable: false },
            { data: 'type_col',     name: 'document_type' },
            { data: 'purpose_col',  name: 'purpose',       orderable: false },
            { data: 'fee_col',      name: 'fee_paid',      orderable: false },
            { data: 'date_col',     name: 'updated_at' },
            { data: 'status_col',   name: 'status' },
            { data: 'actions',      name: 'actions',       orderable: false, searchable: false },
        ],
        order: [[5, 'desc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-file-lines"></i><p>No documents found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No documents match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── Axios DELETE ─────────────────────────────────────────────────── */
    $('#documentsTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const btn  = $(this);
        const form = btn.closest('form');
        const url  = form.attr('action');

        bmsConfirm({
            title:   form.data('confirm-title') || 'Delete Document',
            message: form.data('confirm'),
            ok:      form.data('confirm-ok')    || 'Delete',
        }, function () {
            const icon = btn.find('i');
            const orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
            btn.prop('disabled', true);

            axios.delete(url)
                .then(function (res) {
                    table.row(form.closest('tr')).remove().draw(false);
                    bmsStatDecrement('statDocTotal');
                    bmsToast(res.data.message || 'Document deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete document.', 'error');
                });
        });
    });

    /* ── URL persistence ──────────────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s', 'status', 'document_type', 'source'].forEach(k => url.searchParams.delete(k));
        if ($('#searchInput').val())  url.searchParams.set('s', $('#searchInput').val());
        if ($('#statusFilter').val()) url.searchParams.set('status', $('#statusFilter').val());
        if ($('#typeFilter').val())   url.searchParams.set('document_type', $('#typeFilter').val());
        if ($('#sourceFilter').val()) url.searchParams.set('source', $('#sourceFilter').val());
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))             { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('status'))        { $('#statusFilter').val(p.get('status')).trigger('change.select2'); any = true; }
        if (p.get('document_type')) { $('#typeFilter').val(p.get('document_type')).trigger('change.select2'); any = true; }
        if (p.get('source'))        {
            var src = p.get('source');
            $('#sourceFilter').val(src);
            $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === src); });
            any = true;
        }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())  n++;
        if ($('#typeFilter').val())   n++;
        if ($('#statusFilter').val()) n++;
        if ($('#sourceFilter').val()) n++;
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
    }
    window.toggleFilters = function (key) {
        const panel  = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        localStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };
    window.quickFilter = function (filterId, value) {
        if (filterId === 'sourceFilter') {
            $('#sourceFilter').val(value);
            $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === value); });
        } else {
            $('#' + filterId).val(value).trigger('change');
        }
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            localStorage.setItem('fp_documents', '1');
        }
        saveToUrl(); table.ajax.reload();
    };

    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || localStorage.getItem('fp_documents') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#typeFilter, #statusFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#sourceFilter').val(null);
        $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === ''); });
        saveToUrl(); table.ajax.reload();
    });

}); // end document.ready

/* ── Source chip helpers ──────────────────────────────────────────── */
function _syncChip($chip, isActive) {
    var src = $chip.data('src');
    $chip.toggleClass('src-chip-active', isActive);
    if (src === '') {
        $chip.css({ background: isActive ? 'var(--navy)'  : '#fff',
                    color:       isActive ? '#fff'         : 'var(--text-muted)',
                    borderColor: isActive ? 'var(--navy)'  : 'var(--border)' });
    } else if (src === 'portal') {
        $chip.css({ background: isActive ? '#1d4ed8' : '#eff6ff',
                    color:       isActive ? '#fff'    : '#1d4ed8',
                    borderColor: isActive ? '#1d4ed8' : '#bfdbfe' });
    } else {
        $chip.css({ background: isActive ? 'var(--navy)'   : 'var(--surface)',
                    color:       isActive ? '#fff'          : 'var(--text-muted)',
                    borderColor: isActive ? 'var(--navy)'   : 'var(--border)' });
    }
}

$(document).on('click', '.src-chip', function () {
    var src = $(this).data('src');
    $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === src); });
    $('#sourceFilter').val(src || null);

    // Persist to URL + reload table (same as other filter changes)
    const url = new URL(window.location);
    url.searchParams.delete('source');
    if (src) url.searchParams.set('source', src);
    history.replaceState({}, '', url);

    // Update badge count
    var n = 0;
    if ($('#searchInput').val())  n++;
    if ($('#typeFilter').val())   n++;
    if ($('#statusFilter').val()) n++;
    if (src) n++;
    var badge = document.getElementById('filterBadge');
    var chip  = document.getElementById('headerFilterChip');
    var chipN = document.getElementById('headerFilterCount');
    if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; chipN.textContent = n; chip.style.display = ''; }
    else { badge.style.display = 'none'; chip.style.display = 'none'; }

    $('#documentsTable').DataTable().ajax.reload();
});

/* ── Pipeline one-click advance button ───────────────────────────── */
$(document).on('click', '#documentsTable .doc-pipeline-btn', function () {
    var $btn     = $(this);
    var next     = $btn.data('next');
    var url      = $btn.data('url');
    var isPortal = $btn.data('source') === 'portal';

    var origHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin" style="font-size:11px"></i>');

    axios.patch(url, { status: next })
        .then(function (res) {
            var $row = $btn.closest('tr');
            var statusBadge = $row.find('td .badge').filter(function () {
                return !$(this).hasClass('badge-navy') && !$(this).is('[style*="10px"]');
            }).first();
            statusBadge.removeClass(Object.values(DOC_STATUS_CLS).join(' '))
                       .addClass(DOC_STATUS_CLS[res.data.status] || 'badge-gray')
                       .text(res.data.status);
            $row.find('.doc-status-btn').data('status', res.data.status);

            bmsToast((isPortal ? '📧 Email queued · ' : '') + res.data.message, 'success');
            $('#documentsTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            $btn.prop('disabled', false).html(origHtml);
            bmsToast(err.response?.data?.message || 'Failed to advance status.', 'error');
        });
});

/* ── Quick Status Modal ───────────────────────────────────────────── */
const DOC_STATUS_CLS = {
    Pending:    'badge-yellow',
    Confirmed:  'badge-navy',
    Processing: 'badge-blue',
    Ready:      'badge-green',
    Released:   'badge-gray',
    Cancelled:  'badge-red',
};

var _docStatusId = null;

$(document).on('click', '#documentsTable .doc-status-btn', function () {
    _docStatusId = $(this).data('id');
    const isPortal = $(this).data('source') === 'portal';

    document.getElementById('docStatusNum').textContent     = $(this).data('num') || ('Doc #' + _docStatusId);
    document.getElementById('docStatusSelect').value        = $(this).data('status');
    document.getElementById('docStatusError').style.display = 'none';
    document.getElementById('docPortalNote').style.display  = isPortal ? '' : 'none';
    document.getElementById('docStatusModal').style.display = 'flex';
});

function closeDocStatusModal() {
    document.getElementById('docStatusModal').style.display = 'none';
    _docStatusId = null;
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDocStatusModal();
});

function saveDocStatus() {
    if (!_docStatusId) return;

    const btn     = document.getElementById('docStatusSaveBtn');
    const spinner = document.getElementById('docStatusSpinner');
    const icon    = document.getElementById('docStatusSaveIcon');
    const errDiv  = document.getElementById('docStatusError');
    const status  = document.getElementById('docStatusSelect').value;

    errDiv.style.display  = 'none';
    btn.disabled          = true;
    spinner.style.display = '';
    icon.style.display    = 'none';

    axios.patch('/documents/' + _docStatusId + '/status', { status: status })
        .then(function (res) {
            const row = $('button.doc-status-btn[data-id="' + _docStatusId + '"]').closest('tr');
            row.find('.doc-status-btn').data('status', res.data.status);
            row.find('td .badge:not(.badge-navy):not(.badge-blue[style*="10px"])').first()
               .removeClass(Object.values(DOC_STATUS_CLS).join(' '))
               .addClass(DOC_STATUS_CLS[res.data.status] || 'badge-gray')
               .text(res.data.status);

            closeDocStatusModal();
            bmsToast(res.data.message, 'success');
            $('#documentsTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            const msg = err.response?.data?.message || 'Failed to update status.';
            errDiv.textContent   = msg;
            errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled          = false;
            spinner.style.display = 'none';
            icon.style.display    = '';
        });
}
</script>
@endpush
