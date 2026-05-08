@extends('layouts.app')
@section('title', 'Business Permits')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Business Permits</h1>
        <p class="page-subtitle">Registered businesses in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('export.pdf', 'businesses') }}" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="{{ route('export.excel', 'businesses') }}" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="{{ route('businesses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Issue Permit
        </a>
    </div>
</div>

{{-- Expiry Alerts --}}
@if($summaryCounts['Overdue'] > 0)
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:var(--radius);margin-bottom:14px">
    <i class="fas fa-triangle-exclamation" style="color:#ef4444;font-size:18px;flex-shrink:0"></i>
    <div style="flex:1">
        <div style="font-size:14px;font-weight:700;color:#991b1b">{{ $summaryCounts['Overdue'] }} Active Permit{{ $summaryCounts['Overdue'] > 1 ? 's' : '' }} are Overdue!</div>
        <div style="font-size:13px;color:#ef4444">These businesses have active status but their permits have already expired. Consider updating their status.</div>
    </div>
    <button class="btn btn-secondary btn-sm" onclick="quickFilter('expiryFilter', 'expired')">
        <i class="fas fa-filter"></i> Show Overdue
    </button>
</div>
@endif

@if($summaryCounts['ExpiringSoon'] > 0)
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:var(--radius);margin-bottom:14px">
    <i class="fas fa-clock" style="color:#f59e0b;font-size:18px;flex-shrink:0"></i>
    <div style="flex:1">
        <div style="font-size:14px;font-weight:700;color:#92400e">{{ $summaryCounts['ExpiringSoon'] }} Permit{{ $summaryCounts['ExpiringSoon'] > 1 ? 's' : '' }} Expiring Within 30 Days</div>
        <div style="font-size:13px;color:#b45309">Notify business owners to renew their barangay permits soon.</div>
    </div>
    <button class="btn btn-secondary btn-sm" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <i class="fas fa-filter"></i> Show Expiring
    </button>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid-4 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', null)">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(array_sum([$summaryCounts['Active'],$summaryCounts['Expired'],$summaryCounts['Suspended'],$summaryCounts['Cancelled']])) }}</div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Active'])">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active']) }}</div>
            <div class="stat-label">Active Permits</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer;border-color:#fde68a" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <div class="stat-icon" style="background:#fffbeb;color:#b45309"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="color:#b45309">{{ number_format($summaryCounts['ExpiringSoon']) }}</div>
            <div class="stat-label">Expiring in 30 Days</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer;border-color:#fecaca" onclick="quickFilter('expiryFilter', 'expired')">
        <div class="stat-icon" style="background:#fef2f2;color:#ef4444"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="color:#ef4444">{{ number_format($summaryCounts['Overdue']) }}</div>
            <div class="stat-label">Overdue (Active)</div>
        </div>
    </div>
</div>
<div class="grid-2 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Expired'])">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Expired']) }}</div>
            <div class="stat-label">Marked Expired</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Suspended'])">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-pause-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Suspended']) }}</div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>

{{-- Collapsible Filter Bar --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('businesses')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('businesses')">
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
                               placeholder="Business name, owner, permit number…">
                    </div>
                </div>

                {{-- Business Type (multi, span 2) --}}
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Business Type</label>
                    <select id="typeFilter" multiple>
                        @foreach($businessTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status (multi) --}}
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="statusFilter" multiple>
                        @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Expiry Filter (single) --}}
                <div class="form-group">
                    <label class="form-label">Expiry Status</label>
                    <select id="expiryFilter">
                        <option value=""></option>
                        <option value="expiring_soon">⚠ Expiring Soon (30 days)</option>
                        <option value="expired">🔴 Overdue</option>
                        <option value="valid">✅ Valid</option>
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

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Records</span>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted)">
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block"></span>Overdue</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block"></span>Expiring Soon</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:50%;background:#16a34a;display:inline-block"></span>Valid</span>
        </div>
    </div>
    <div class="table-responsive">
        <table id="businessesTable" style="width:100%">
            <thead>
                <tr>
                    <th>Permit No.</th>
                    <th>Business Name</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Permit Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th style="text-align:right;width:110px">Actions</th>
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
#businessesTable_wrapper .dataTables_length,
#businessesTable_wrapper .dataTables_filter { display:none; }
#businessesTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate { padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#businessesTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
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
        language: {
            noResults: function () { return 'No matches'; }
        }
    };

    $('#typeFilter').select2($.extend({}, s2Multi, { placeholder: 'All business types…' }));
    $('#statusFilter').select2($.extend({}, s2Multi, { placeholder: 'All statuses…', minimumResultsForSearch: -1 }));
    $('#expiryFilter').select2($.extend({}, s2Single, { placeholder: 'All', minimumResultsForSearch: -1 }));

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#businessesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('businesses.index') }}',
            data: function (d) {
                d.business_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.expiry_filter = $('#expiryFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',      name: 'permit_number',  width: '130px' },
            { data: 'name_col',        name: 'business_name' },
            { data: 'type_col',        name: 'business_type',  width: '130px' },
            { data: 'owner_col',       name: 'owner_name',     orderable: false },
            { data: 'permit_date_col', name: 'permit_date',    width: '110px' },
            { data: 'expiry_col',      name: 'expiry_date',    width: '140px' },
            { data: 'status_col',      name: 'status',         width: '100px' },
            { data: 'actions',         name: 'actions',        orderable: false, searchable: false, width: '110px' },
        ],
        order: [[5, 'asc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-store"></i><p>No businesses found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No businesses match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        },
        createdRow: function (row, data) {
            if (data.expiry_col && data.expiry_col.includes('overdue')) {
                $(row).css('background', '#fff5f5');
            } else if (data.expiry_col && data.expiry_col.includes('days left') && data.expiry_col.includes('#b45309')) {
                $(row).css('background', '#fffdf0');
            }
        }
    });

    /* ── URL persistence ──────────────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s','expiry_filter'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('business_type');
        url.searchParams.delete('status');

        if ($('#searchInput').val())  url.searchParams.set('s', $('#searchInput').val());
        if ($('#expiryFilter').val()) url.searchParams.set('expiry_filter', $('#expiryFilter').val());
        ($('#typeFilter').val()   || []).forEach(v => url.searchParams.append('business_type', v));
        ($('#statusFilter').val() || []).forEach(v => url.searchParams.append('status', v));

        history.replaceState({}, '', url);
        updateBadge();
    }

    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))             { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('expiry_filter')) { $('#expiryFilter').val(p.get('expiry_filter')).trigger('change.select2'); any = true; }
        const types    = p.getAll('business_type');
        const statuses = p.getAll('status');
        if (types.length)    { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        if (statuses.length) { $('#statusFilter').val(statuses).trigger('change.select2'); any = true; }
        return any;
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if (($('#typeFilter').val()   || []).length)    n++;
        if (($('#statusFilter').val() || []).length)    n++;
        if ($('#expiryFilter').val())                   n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; }
        else       { badge.style.display = 'none'; }
    }

    /* ── Panel toggle + quick-filter (for stat cards & alerts) ───────── */
    window.toggleFilters = function (key) {
        const panel = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        sessionStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };

    window.quickFilter = function (filterId, value) {
        if (value === null) {
            $('#' + filterId).val(null).trigger('change');
        } else if (Array.isArray(value)) {
            $('#' + filterId).val(value).trigger('change');
        } else {
            $('#' + filterId).val(value).trigger('change');
        }
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            sessionStorage.setItem('fp_businesses', '1');
        }
        saveToUrl();
        table.ajax.reload();
    };

    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_businesses') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    /* ── Event listeners ─────────────────────────────────────────────── */
    let debounce;

    $('#searchInput').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380);
    });

    $('#typeFilter, #statusFilter, #expiryFilter').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#expiryFilter').val(null).trigger('change');
        saveToUrl(); table.ajax.reload();
    });
});
</script>
@endpush
