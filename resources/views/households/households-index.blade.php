@extends('layouts.app')
@section('title', 'Households')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Households</h1>
        <p class="page-subtitle">All registered households in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('export.pdf', 'households') }}" class="btn btn-secondary" title="Export PDF">
    <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
</a>
<a href="{{ route('export.excel', 'households') }}" class="btn btn-secondary" title="Export Excel">
    <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
</a>
        <a href="{{ route('households.create') }}" class="btn btn-primary">
            <i class="fas fa-house-circle-plus"></i> Add Household
        </a>
    </div>
</div>

<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-house"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Household::count()) }}</div>
            <div class="stat-label">Total Households</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-people-roof"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->count()) }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-location-dot"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Purok::count()) }}</div>
            <div class="stat-label">Total Puroks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-chart-pie"></i></div>
        <div class="stat-info">
            @php
                $total = \App\Models\Household::count();
                $residents = \App\Models\Resident::where('residency_status','Active')->count();
                $avg = $total > 0 ? round($residents / $total, 1) : 0;
            @endphp
            <div class="stat-number">{{ $avg }}</div>
            <div class="stat-label">Avg. Members/HH</div>
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
                    <input type="text" id="searchInput" class="form-control" style="padding-left:32px" placeholder="Household no., head name, address...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Purok</label>
                <select id="purokFilter" class="form-control">
                    <option value="">All Puroks</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}">{{ $purok->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <button id="resetBtn" class="btn btn-secondary"><i class="fas fa-xmark"></i> Reset</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household List</span>
    </div>
    <div class="table-responsive">
        <table id="householdsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Household No.</th>
                    <th>Household Head</th>
                    <th>Purok</th>
                    <th>Address</th>
                    <th>Family Size</th>
                    <th>Voter HH</th>
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
#householdsTable_wrapper .dataTables_length,
#householdsTable_wrapper .dataTables_filter { display:none; }
#householdsTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#householdsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#householdsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#householdsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#householdsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
</style>
<script>
$(document).ready(function () {
    var table = $('#householdsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('households.index') }}',
            data: function (d) {
                d.purok_id = $('#purokFilter').val();
                d.search   = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',  name: 'household_number' },
            { data: 'head_col',    name: 'household_head' },
            { data: 'purok_col',   name: 'purok_id', orderable: false },
            { data: 'address_col', name: 'address', orderable: false },
            { data: 'size_col',    name: 'family_size' },
            { data: 'voter_col',   name: 'is_voter_household', orderable: false },
            { data: 'actions',     name: 'actions', orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        language: { processing: '<i class="fas fa-spinner fa-spin"></i> Loading...', emptyTable: '<div class="empty-state"><i class="fas fa-house"></i><p>No households found.</p></div>' }
    });
    let searchTimer;
    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 400);
    });
    $('#purokFilter').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#purokFilter').val('');
        table.ajax.reload();
    });
});
</script>
@endpush