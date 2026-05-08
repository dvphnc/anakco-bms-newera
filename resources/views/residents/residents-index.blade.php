@extends('layouts.app')

@section('title', 'Residents')
@section('page-title', 'Residents')
@section('page-subtitle', 'Manage all registered residents of Barangay New Era')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Residents</h1>
        <p class="page-subtitle">All registered residents of Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('export.pdf', 'residents') }}" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="{{ route('export.excel', 'residents') }}" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="{{ route('residents.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Register Resident
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::count()) }}</div>
            <div class="stat-label">Total Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->count()) }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-check-to-slot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->where('is_voter',true)->count()) }}</div>
            <div class="stat-label">Registered Voters</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(122,21,21,0.08);color:#9B1C1C">
            <i class="fas fa-person-cane"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->where('is_senior',true)->count()) }}</div>
            <div class="stat-label">Senior Citizens</div>
        </div>
    </div>
</div>

{{-- Collapsible Filter Bar --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('residents')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" id="filterToggleBtn" onclick="event.stopPropagation();toggleFilters('residents')">
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
                               placeholder="Name, address, contact number...">
                    </div>
                </div>

                {{-- Purok --}}
                <div class="form-group">
                    <label class="form-label">Purok / Zone</label>
                    <select id="purokFilter">
                        <option value=""></option>
                        @foreach($puroks as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Gender --}}
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select id="genderFilter">
                        <option value=""></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                {{-- Residency Status --}}
                <div class="form-group">
                    <label class="form-label">Residency Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        <option value="Active">Active</option>
                        <option value="Deceased">Deceased</option>
                        <option value="Transferred">Transferred</option>
                    </select>
                </div>

                {{-- Civil Status --}}
                <div class="form-group">
                    <label class="form-label">Civil Status</label>
                    <select id="civilStatusFilter">
                        <option value=""></option>
                        @foreach(['Single','Married','Widowed','Separated','Annulled'] as $cs)
                            <option value="{{ $cs }}">{{ $cs }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Classifications / Tags (multi, span 2) --}}
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Classifications</label>
                    <select id="tagsFilter" multiple>
                        <option value="voter">Registered Voter</option>
                        <option value="senior">Senior Citizen (60+)</option>
                        <option value="pwd">Person with Disability (PWD)</option>
                        <option value="solo_parent">Solo Parent</option>
                        <option value="4ps">4Ps Beneficiary</option>
                    </select>
                </div>

                {{-- Age Range --}}
                <div class="form-group">
                    <label class="form-label">Age Range</label>
                    <div style="display:flex;align-items:center;gap:6px">
                        <input type="number" id="ageMin" class="form-control" placeholder="Min"
                               min="0" max="120" style="width:80px">
                        <span style="color:var(--text-subtle);font-size:11px;flex-shrink:0">–</span>
                        <input type="number" id="ageMax" class="form-control" placeholder="Max"
                               min="0" max="120" style="width:80px">
                    </div>
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
        <span class="card-title">
            <i class="fas fa-users"></i> Resident List
        </span>
    </div>
    <div class="table-responsive">
        <table id="residentsTable" class="w-full" style="width:100%">
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Purok</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Classifications</th>
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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<style>
#residentsTable_wrapper .dataTables_length,
#residentsTable_wrapper .dataTables_filter { display: none; }
#residentsTable_wrapper .dataTables_info { font-size:13px; color:var(--text-muted); padding: 12px 20px; }
#residentsTable_wrapper .dataTables_paginate { padding: 12px 20px; }
#residentsTable_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 10px; border-radius: 6px; font-size: 13px; cursor: pointer;
    border: 1px solid var(--border) !important; background: white !important;
    color: var(--text) !important; margin: 0 2px;
}
#residentsTable_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--navy) !important; color: white !important; border-color: var(--navy) !important;
}
#residentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background: var(--navy-pale) !important; color: var(--navy) !important;
}
#residentsTable_wrapper .dataTables_processing {
    background: white; border: 1px solid var(--border); border-radius: 8px;
    padding: 12px 20px; font-size: 13px; color: var(--navy);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
/* Filter panel transition */
#filterPanel { transition: none; }
</style>

<script>
$(document).ready(function () {

    /* ── Select2 init ─────────────────────────────────────────────────── */
    const s2Base = {
        dropdownParent: $('body'),
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () {
                return 'No matches — try a different term';
            },
            searching: function () { return 'Searching…'; }
        }
    };

    $('#purokFilter').select2($.extend({}, s2Base, { placeholder: 'All Puroks' }));
    $('#genderFilter').select2($.extend({}, s2Base, { placeholder: 'All Genders', minimumResultsForSearch: -1 }));
    $('#statusFilter').select2($.extend({}, s2Base, { placeholder: 'All Statuses', minimumResultsForSearch: -1 }));
    $('#civilStatusFilter').select2($.extend({}, s2Base, { placeholder: 'Any Civil Status', minimumResultsForSearch: -1 }));
    $('#tagsFilter').select2($.extend({}, s2Base, {
        placeholder: 'Filter by classification…',
        minimumResultsForSearch: -1,
        closeOnSelect: false,
        allowClear: false
    }));

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#residentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route('residents.index') }}',
            data: function (d) {
                d.gender       = $('#genderFilter').val();
                d.status       = $('#statusFilter').val();
                d.purok_id     = $('#purokFilter').val();
                d.civil_status = $('#civilStatusFilter').val();
                d.tags         = $('#tagsFilter').val();
                d.age_min      = $('#ageMin').val();
                d.age_max      = $('#ageMax').val();
                d.search       = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'name_col',   name: 'first_name',       orderable: true },
            { data: 'purok_col',  name: 'purok_id',         orderable: false },
            { data: 'gender_col', name: 'gender',           orderable: true },
            { data: 'age_col',    name: 'birthdate',        orderable: true },
            { data: 'civil_col',  name: 'civil_status',     orderable: false },
            { data: 'tags_col',   name: 'is_voter',         orderable: false },
            { data: 'status_col', name: 'residency_status', orderable: true },
            { data: 'actions',    name: 'actions',          orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading residents…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-users"></i><p>No residents found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No residents match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── URL persistence helpers ──────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s','purok_id','gender','status','civil_status','age_min','age_max'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('tags');

        const s = $('#searchInput').val();
        if (s)                              url.searchParams.set('s', s);
        if ($('#purokFilter').val())        url.searchParams.set('purok_id', $('#purokFilter').val());
        if ($('#genderFilter').val())       url.searchParams.set('gender', $('#genderFilter').val());
        if ($('#statusFilter').val())       url.searchParams.set('status', $('#statusFilter').val());
        if ($('#civilStatusFilter').val())  url.searchParams.set('civil_status', $('#civilStatusFilter').val());
        if ($('#ageMin').val())             url.searchParams.set('age_min', $('#ageMin').val());
        if ($('#ageMax').val())             url.searchParams.set('age_max', $('#ageMax').val());
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));

        history.replaceState({}, '', url);
        updateBadge();
    }

    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))            { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('purok_id'))     { $('#purokFilter').val(p.get('purok_id')).trigger('change.select2'); any = true; }
        if (p.get('gender'))       { $('#genderFilter').val(p.get('gender')).trigger('change.select2'); any = true; }
        if (p.get('status'))       { $('#statusFilter').val(p.get('status')).trigger('change.select2'); any = true; }
        if (p.get('civil_status')) { $('#civilStatusFilter').val(p.get('civil_status')).trigger('change.select2'); any = true; }
        if (p.get('age_min'))      { $('#ageMin').val(p.get('age_min')); any = true; }
        if (p.get('age_max'))      { $('#ageMax').val(p.get('age_max')); any = true; }
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
        return any;
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if ($('#purokFilter').val())                    n++;
        if ($('#genderFilter').val())                   n++;
        if ($('#statusFilter').val())                   n++;
        if ($('#civilStatusFilter').val())              n++;
        if ($('#ageMin').val() || $('#ageMax').val())   n++;
        if (($('#tagsFilter').val() || []).length)      n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; }
        else       { badge.style.display = 'none'; }
    }

    /* ── Filter panel toggle (global so card-header click works) ─────── */
    window.toggleFilters = function (key) {
        const panel = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        sessionStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };

    // Restore panel state on load
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_residents') === '1') {
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

    $('#genderFilter, #statusFilter, #purokFilter, #civilStatusFilter, #tagsFilter').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

    $('#ageMin, #ageMax').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 600);
    });

    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#genderFilter, #statusFilter, #purokFilter, #civilStatusFilter').val(null).trigger('change');
        $('#tagsFilter').val(null).trigger('change');
        $('#ageMin, #ageMax').val('');
        saveToUrl(); table.ajax.reload();
    });
});
</script>
@endpush
