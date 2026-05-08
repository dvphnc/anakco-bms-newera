@extends('layouts.app')
@section('title', 'Documents')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Document Issuance</h1>
        <p class="page-subtitle">Barangay certificates and clearances</p>
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

<div class="grid-4 mb-6">
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

                {{-- Search (full width) --}}
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                               placeholder="Document no., resident name…">
                    </div>
                </div>

                {{-- Document Type (multi, span 2) --}}
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Document Type</label>
                    <select id="typeFilter" multiple>
                        @foreach($documentTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status (single, span 2) --}}
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
    const s2Multi = {
        dropdownParent: $('body'),
        allowClear: false,
        width: '100%',
        closeOnSelect: false,
        language: {
            noResults: function () { return 'No matches — try a different term'; },
            searching: function () { return 'Searching…'; }
        }
    };
    const s2Single = {
        dropdownParent: $('body'),
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: -1,
        language: {
            noResults: function () { return 'No matches — try a different term'; }
        }
    };

    $('#typeFilter').select2($.extend({}, s2Multi, { placeholder: 'All document types…' }));
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
            { data: 'resident_col', name: 'resident_id', orderable: false },
            { data: 'type_col',     name: 'document_type' },
            { data: 'purpose_col',  name: 'purpose', orderable: false },
            { data: 'fee_col',      name: 'fee_paid', orderable: false },
            { data: 'date_col',     name: 'created_at' },
            { data: 'status_col',   name: 'status' },
            { data: 'actions',      name: 'actions', orderable: false, searchable: false },
        ],
        order: [[5, 'desc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-file-lines"></i><p>No documents found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No documents match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
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
        if (types.length) { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        return any;
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                  n++;
        if (($('#typeFilter').val() || []).length)    n++;
        if ($('#statusFilter').val())                 n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; }
        else       { badge.style.display = 'none'; }
    }

    /* ── Panel toggle ─────────────────────────────────────────────────── */
    window.toggleFilters = function (key) {
        const panel = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        sessionStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };

    // Quick-filter from stat cards — sets select and opens panel
    window.quickFilter = function (filterId, value) {
        $('#' + filterId).val(value).trigger('change');
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            sessionStorage.setItem('fp_documents', '1');
        }
        saveToUrl();
        table.ajax.reload();
    };

    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_documents') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    /* ── Event listeners ──────────────────────────────────────────────── */
    let debounce;

    $('#searchInput').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380);
    });

    $('#typeFilter, #statusFilter').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter').val(null).trigger('change');
        $('#statusFilter').val(null).trigger('change');
        saveToUrl(); table.ajax.reload();
    });
});
</script>
@endpush
