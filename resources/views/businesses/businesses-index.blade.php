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
        <div style="font-size:13px;font-weight:700;color:#991b1b">{{ $summaryCounts['Overdue'] }} Active Permit{{ $summaryCounts['Overdue'] > 1 ? 's' : '' }} are Overdue!</div>
        <div style="font-size:12px;color:#ef4444">These businesses have active status but their permits have already expired. Consider updating their status.</div>
    </div>
    <button class="btn btn-secondary btn-sm" onclick="$('#expiryFilter').val('expired').trigger('change')">
        <i class="fas fa-filter"></i> Show Overdue
    </button>
</div>
@endif

@if($summaryCounts['ExpiringSoon'] > 0)
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:var(--radius);margin-bottom:14px">
    <i class="fas fa-clock" style="color:#f59e0b;font-size:18px;flex-shrink:0"></i>
    <div style="flex:1">
        <div style="font-size:13px;font-weight:700;color:#92400e">{{ $summaryCounts['ExpiringSoon'] }} Permit{{ $summaryCounts['ExpiringSoon'] > 1 ? 's' : '' }} Expiring Within 30 Days</div>
        <div style="font-size:12px;color:#b45309">Notify business owners to renew their barangay permits soon.</div>
    </div>
    <button class="btn btn-secondary btn-sm" onclick="$('#expiryFilter').val('expiring_soon').trigger('change')">
        <i class="fas fa-filter"></i> Show Expiring
    </button>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid-4 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="$('#statusFilter').val('').trigger('change');$('#expiryFilter').val('').trigger('change')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(array_sum([$summaryCounts['Active'],$summaryCounts['Expired'],$summaryCounts['Suspended'],$summaryCounts['Cancelled']])) }}</div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="$('#statusFilter').val('Active').trigger('change');$('#expiryFilter').val('').trigger('change')">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active']) }}</div>
            <div class="stat-label">Active Permits</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer;border-color:#fde68a" onclick="$('#expiryFilter').val('expiring_soon').trigger('change');$('#statusFilter').val('').trigger('change')">
        <div class="stat-icon" style="background:#fffbeb;color:#b45309"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="color:#b45309">{{ number_format($summaryCounts['ExpiringSoon']) }}</div>
            <div class="stat-label">Expiring in 30 Days</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer;border-color:#fecaca" onclick="$('#expiryFilter').val('expired').trigger('change');$('#statusFilter').val('').trigger('change')">
        <div class="stat-icon" style="background:#fef2f2;color:#ef4444"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="color:#ef4444">{{ number_format($summaryCounts['Overdue']) }}</div>
            <div class="stat-label">Overdue (Active)</div>
        </div>
    </div>
</div>
<div class="grid-2 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="$('#statusFilter').val('Expired').trigger('change');$('#expiryFilter').val('').trigger('change')">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Expired']) }}</div>
            <div class="stat-label">Marked Expired</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="$('#statusFilter').val('Suspended').trigger('change');$('#expiryFilter').val('').trigger('change')">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-pause-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Suspended']) }}</div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <div class="filter-bar">
            <div class="form-group flex-1">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                    <input type="text" id="searchInput" class="form-control" style="padding-left:32px" placeholder="Business name, owner, permit no...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Type</label>
                <select id="typeFilter" class="form-control">
                    <option value="">All Types</option>
                    @foreach($businessTypes as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="statusFilter" class="form-control">
                    <option value="">All</option>
                    @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry</label>
                <select id="expiryFilter" class="form-control">
                    <option value="">All</option>
                    <option value="expiring_soon">⚠ Expiring Soon (30 days)</option>
                    <option value="expired">🔴 Overdue</option>
                    <option value="valid">✅ Valid</option>
                </select>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <button id="resetBtn" class="btn btn-secondary"><i class="fas fa-times"></i> Reset</button>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Records</span>
        <div style="display:flex;align-items:center;gap:8px;font-size:11.5px;color:var(--text-muted)">
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

@push('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
#businessesTable_wrapper .dataTables_length,
#businessesTable_wrapper .dataTables_filter { display:none; }
#businessesTable_wrapper .dataTables_info { font-size:12px;color:var(--text-muted);padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate { padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#businessesTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
</style>
<script>
$(document).ready(function () {
    var table = $('#businessesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('businesses.index') }}',
            data: function (d) {
                d.business_type  = $('#typeFilter').val();
                d.status         = $('#statusFilter').val();
                d.expiry_filter  = $('#expiryFilter').val();
                d.search         = { value: $('#searchInput').val() };
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
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            emptyTable: '<div class="empty-state"><i class="fas fa-store"></i><p>No businesses found.</p></div>'
        },
        createdRow: function(row, data) {
            // Highlight overdue rows
            if (data.expiry_col && data.expiry_col.includes('overdue')) {
                $(row).css('background', '#fff5f5');
            } else if (data.expiry_col && data.expiry_col.includes('days left') && data.expiry_col.includes('#b45309')) {
                $(row).css('background', '#fffdf0');
            }
        }
    });

    let searchTimer;
    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 400);
    });
    $('#typeFilter, #statusFilter, #expiryFilter').on('change', function () {
        table.ajax.reload();
    });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter, #expiryFilter').val('');
        table.ajax.reload();
    });
});
</script>
@endpush