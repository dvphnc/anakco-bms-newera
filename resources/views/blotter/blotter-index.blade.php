@extends('layouts.app')
@section('title', 'Blotter Cases')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Blotter Cases</h1>
        <p class="page-subtitle">Incident and complaint records</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.create') }}" class="btn btn-primary">
            <i class="fas fa-gavel"></i> File Case
        </a>
    </div>
</div>

<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-gavel"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\BlotterCase::count()) }}</div>
            <div class="stat-label">Total Cases</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-circle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active'] ?? 0) }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-magnifying-glass"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Under Investigation'] ?? 0) }}</div>
            <div class="stat-label">Under Investigation</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-handshake"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Settled'] ?? 0) }}</div>
            <div class="stat-label">Settled</div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <div class="filter-bar" style="align-items:flex-end;gap:12px">
            <div class="form-group flex-1">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                    <input type="text" id="searchInput" class="form-control" style="padding-left:32px" placeholder="Case no., complainant, respondent...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Type</label>
                <select id="typeFilter" class="form-control">
                    <option value="">All Types</option>
                    @foreach($incidentTypes as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="statusFilter" class="form-control">
                    <option value="">All</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date Range</label>
                <div style="display:flex;align-items:center;gap:6px">
                    <input type="date" id="dateFrom" class="form-control" style="width:140px">
                    <span style="font-size:11px;color:var(--text-muted);flex-shrink:0">–</span>
                    <input type="date" id="dateTo" class="form-control" style="width:140px">
                    <button id="resetBtn" class="btn btn-secondary" style="white-space:nowrap;margin-left:4px">
                        <i class="fas fa-xmark"></i> Reset
                    </button>
                </div>
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
#blotterTable_wrapper .dataTables_length,
#blotterTable_wrapper .dataTables_filter { display:none; }
#blotterTable_wrapper .dataTables_info { font-size:12px;color:var(--text-muted);padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate { padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#blotterTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
</style>
<script>
$(document).ready(function () {
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
            { data: 'actions',         name: 'actions', orderable: false, searchable: false },
        ],
        order: [[4, 'desc']],
        pageLength: 15,
        language: { processing: '<i class="fas fa-spinner fa-spin"></i> Loading...', emptyTable: '<div class="empty-state"><i class="fas fa-gavel"></i><p>No blotter cases found.</p></div>' }
    });
    let searchTimer;
    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 400);
    });
    $('#typeFilter, #statusFilter, #dateFrom, #dateTo').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val('');
        $('#dateFrom, #dateTo').val('');
        table.ajax.reload();
    });
});
</script>
@endpush