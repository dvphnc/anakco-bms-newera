@extends('layouts.app')
@section('title', 'Business Permits')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Business Permits</h1>
        <p class="page-subtitle">Registered businesses in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Issue Permit
        </a>
    </div>
</div>

<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(array_sum($summaryCounts)) }}</div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active']) }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Expired']) }}</div>
            <div class="stat-label">Expired</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-pause-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Suspended']) }}</div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>

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
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <button id="resetBtn" class="btn btn-secondary"><i class="fas fa-times"></i> Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Records</span>
    </div>
    <div class="table-responsive">
        <table id="businessesTable" style="width:100%">
            <thead>
                <tr>
                    <th>Permit No.</th>
                    <th>Business Name</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Address</th>
                    <th>Permit Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
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
                d.business_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',      name: 'permit_number' },
            { data: 'name_col',        name: 'business_name' },
            { data: 'type_col',        name: 'business_type' },
            { data: 'owner_col',       name: 'owner_name', orderable: false },
            { data: 'address_col',     name: 'business_address', orderable: false },
            { data: 'permit_date_col', name: 'permit_date' },
            { data: 'expiry_col',      name: 'expiry_date' },
            { data: 'status_col',      name: 'status' },
            { data: 'actions',         name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        language: { processing: '<i class="fas fa-spinner fa-spin"></i> Loading...', emptyTable: '<div class="empty-state"><i class="fas fa-store"></i><p>No businesses found.</p></div>' }
    });
    let searchTimer;
    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 400);
    });
    $('#typeFilter, #statusFilter').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val('');
        table.ajax.reload();
    });
});
</script>
@endpush