@extends('layouts.app')
@section('title', 'Documents')
@section('page-title', 'Document Issuance')
@section('page-subtitle', 'Barangay certificates and clearances')
@section('content')

<div class="page-header" id="tour-header">
    <div>
        <h1 class="page-title">Document Issuance</h1>
        <p class="page-subtitle">Barangay certificates and clearances</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('export.pdf', 'documents') }}" class="btn btn-secondary" title="Export PDF" id="tour-export">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="{{ route('export.excel', 'documents') }}" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="{{ route('documents.create') }}" class="btn btn-primary" id="tour-issue">
            <i class="fas fa-file-circle-plus"></i> Issue Document
        </a>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid-4 mb-6" id="tour-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-file-lines"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::count()) }}</div>
            <div class="stat-label">Total Documents</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Pending')">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-hourglass-half"></i></div>
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
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Released')->count()) }}</div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>

{{-- Collapsible Filter Bar --}}
<div class="card mb-6" id="tour-filters">
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
                    <select id="typeFilter" multiple>
                        @foreach($documentTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Status</label>
                    <select id="statusFilter">
                        @foreach(['Pending','Processing','Released','Cancelled'] as $s)
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

<div class="card" id="tour-table">
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

{{-- Quick Status Update Modal --}}
<div id="docStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeDocStatusModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:420px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Document Status</span>
            </div>
            <button onclick="closeDocStatusModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Document: <strong id="docStatusNum" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="docStatusSelect" class="form-control">
                    @foreach(['Pending','Processing','Released','Cancelled'] as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div id="docStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeDocStatusModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="docStatusSaveBtn" onclick="saveDocStatus()" class="btn btn-primary">
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
#documentsTable_wrapper .dataTables_length,
#documentsTable_wrapper .dataTables_filter { display:none; }
#documentsTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#documentsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#documentsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#documentsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#documentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
</style>
<script>
$(document).ready(function () {

    /* ── Select2 init ─────────────────────────────────────────────────── */
    const s2Multi  = { dropdownParent: $('body'), allowClear: false, width: '100%', closeOnSelect: false,
                       language: { noResults: () => 'No matches', searching: () => 'Searching…' } };
    const s2Single = { dropdownParent: $('body'), allowClear: true,  width: '100%',
                       language: { noResults: () => 'No matches' } };

    $('#typeFilter').select2($.extend({}, s2Multi,  { placeholder: 'All document types…' }));
    $('#statusFilter').select2($.extend({}, s2Single, { placeholder: 'All statuses…' }));

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#documentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('documents.index') }}',
            data: function (d) {
                d.document_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',   name: 'doc_number' },
            { data: 'resident_col', name: 'resident_id',   orderable: false },
            { data: 'type_col',     name: 'document_type' },
            { data: 'purpose_col',  name: 'purpose',       orderable: false },
            { data: 'fee_col',      name: 'fee_paid',      orderable: false },
            { data: 'date_col',     name: 'created_at' },
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
        ['s', 'status'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('document_type');
        if ($('#searchInput').val()) url.searchParams.set('s', $('#searchInput').val());
        if ($('#statusFilter').val()) url.searchParams.set('status', $('#statusFilter').val());
        ($('#typeFilter').val() || []).forEach(v => url.searchParams.append('document_type', v));
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))      { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('status')) { $('#statusFilter').val(p.get('status')).trigger('change.select2'); any = true; }
        const types = p.getAll('document_type');
        if (types.length)    { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())               n++;
        if (($('#typeFilter').val() || []).length) n++;
        if ($('#statusFilter').val())              n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; }
        else       { badge.style.display = 'none'; }
    }
    window.toggleFilters = function (key) {
        const panel  = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        sessionStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };
    window.quickFilter = function (filterId, value) {
        $('#' + filterId).val(value).trigger('change');
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            sessionStorage.setItem('fp_documents', '1');
        }
        saveToUrl(); table.ajax.reload();
    };
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_documents') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#typeFilter, #statusFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter').val(null).trigger('change');
        $('#statusFilter').val(null).trigger('change');
        saveToUrl(); table.ajax.reload();
    });
});

/* ── Quick Status Modal ───────────────────────────────────────────── */
var _docStatusId = null;

$(document).on('click', '#documentsTable .doc-status-btn', function () {
    _docStatusId = $(this).data('id');
    document.getElementById('docStatusNum').textContent = 'Doc #' + _docStatusId;
    document.getElementById('docStatusSelect').value    = $(this).data('status');
    document.getElementById('docStatusError').style.display = 'none';
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
    const btn    = document.getElementById('docStatusSaveBtn');
    const errDiv = document.getElementById('docStatusError');
    const status = document.getElementById('docStatusSelect').value;

    errDiv.style.display = 'none';
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/documents/' + _docStatusId + '/status', { status: status })
        .then(function (res) {
            const dt  = $('#documentsTable').DataTable();
            const row = $('button.doc-status-btn[data-id="' + _docStatusId + '"]').closest('tr');
            row.find('.doc-status-btn').data('status', res.data.status);

            const clsMap = { Pending: 'badge-yellow', Processing: 'badge-blue', Released: 'badge-green', Cancelled: 'badge-gray' };
            row.find('td .badge:not(.badge-navy)').first().each(function () {
                $(this).removeClass('badge-yellow badge-blue badge-green badge-gray badge-red')
                       .addClass(clsMap[res.data.status] || 'badge-gray').text(res.data.status);
            });

            closeDocStatusModal();
            bmsToast(res.data.message, 'success');
            dt.ajax.reload(null, false);
        })
        .catch(function (err) {
            const msg = err.response?.data?.message || 'Failed to update status.';
            errDiv.textContent   = msg;
            errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status';
        });
}

/* ── Shepherd.js Tour ─────────────────────────────────────────────── */
(function () {
    const TOUR_KEY = 'bms_tour_documents_v1_{{ auth()->id() }}';
    if (localStorage.getItem(TOUR_KEY)) return;
    if (typeof Shepherd === 'undefined') return;

    const tour = new Shepherd.Tour({
        defaultStepOptions: {
            cancelIcon: { enabled: false },
            scrollTo: { behavior: 'smooth', block: 'center' },
        },
        useModalOverlay: true,
    });

    const skipBtn = {
        text: '<i class="fas fa-forward"></i> Skip Tour',
        action: function () {
            Swal.fire({
                title: 'Skip this tour?',
                text: 'You can re-enable it by clearing your browser\'s local storage.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0D2144',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, skip it',
                cancelButtonText: 'Continue tour',
                customClass: { popup: 'swal-poppins' },
            }).then(function (result) {
                if (result.isConfirmed) {
                    localStorage.setItem(TOUR_KEY, new Date().toISOString());
                    tour.cancel();
                } else {
                    tour.show(tour.getCurrentStep().id);
                }
            });
        },
        classes: 'shepherd-button-secondary',
    };

    tour.addStep({
        id: 'step-header',
        title: '<i class="fas fa-file-lines" style="color:var(--gold)"></i>&nbsp; Document Issuance',
        text: 'This page manages all barangay document requests — clearances, certificates, and more. Staff can issue, track, and update document status from here.',
        attachTo: { element: '#tour-header', on: 'bottom' },
        buttons: [skipBtn, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }],
    });
    tour.addStep({
        id: 'step-stats',
        title: '<i class="fas fa-chart-bar" style="color:var(--gold)"></i>&nbsp; Status Summary',
        text: 'Click any stat card to instantly filter the table by that status. The numbers update in real time as you issue and release documents.',
        attachTo: { element: '#tour-stats', on: 'bottom' },
        buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }],
    });
    tour.addStep({
        id: 'step-filters',
        title: '<i class="fas fa-sliders" style="color:var(--gold)"></i>&nbsp; Smart Filters',
        text: 'Expand the filter panel to search by document type, status, or resident name. Active filters are shown as a badge. Filters are saved in the URL for easy sharing.',
        attachTo: { element: '#tour-filters', on: 'bottom' },
        buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }],
    });
    tour.addStep({
        id: 'step-table',
        title: '<i class="fas fa-table" style="color:var(--gold)"></i>&nbsp; Document Records',
        text: 'The table loads instantly with server-side processing. Use the <strong>🔄 rotate icon</strong> to update a document\'s status in one click — no page reload needed. The <strong>🗑 trash icon</strong> deletes with a confirmation prompt.',
        attachTo: { element: '#tour-table', on: 'top' },
        buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }],
    });
    tour.addStep({
        id: 'step-issue',
        title: '<i class="fas fa-file-circle-plus" style="color:var(--gold)"></i>&nbsp; Issue a Document',
        text: 'Click <strong>Issue Document</strong> to open the issuance form. Resident data auto-fills age, gender, civil status, and birthdate on the printed certificate.',
        attachTo: { element: '#tour-issue', on: 'left' },
        buttons: [{ text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: '<i class="fas fa-check"></i> Got it!', action: tour.complete, classes: 'shepherd-button-primary' }],
    });

    tour.on('complete', function () {
        localStorage.setItem(TOUR_KEY, new Date().toISOString());
        bmsToast('Tour complete! You\'re all set.', 'success');
    });

    setTimeout(function () { tour.start(); }, 900);
})();
</script>
@endpush
