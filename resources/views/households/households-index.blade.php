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
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)"><i class="fas fa-people-roof"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->count()) }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)"><i class="fas fa-location-dot"></i></div>
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
                <select id="purokFilter">
                    <option value=""></option>
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

{{-- Household Quick View Panel --}}
<div id="hhQvPanel"
     style="display:none;opacity:0;position:fixed;inset:0;z-index:9500;
            align-items:flex-start;justify-content:flex-end;
            background:rgba(9,20,40,0.45);backdrop-filter:blur(3px);
            transition:opacity .2s"
     onclick="if(event.target===this) closeHhPanel()">
    <div style="width:420px;max-width:95vw;height:100vh;background:var(--surface);
                overflow-y:auto;box-shadow:-8px 0 40px rgba(0,0,0,0.22);
                display:flex;flex-direction:column;animation:qvSlideIn .2s ease">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid var(--border);
                    background:var(--navy);flex-shrink:0">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-house" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Household Quick View</span>
            </div>
            <button onclick="closeHhPanel()"
                    style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;
                           display:flex;align-items:center;justify-content:center;
                           color:rgba(255,255,255,0.7);cursor:pointer;font-size:13px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="hhQvBody" style="flex:1"></div>
    </div>
</div>
<style>
@keyframes qvSlideIn { from { transform:translateX(32px);opacity:0; } to { transform:translateX(0);opacity:1; } }
</style>

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
    $('#purokFilter').select2({
        dropdownParent: $('body'),
        placeholder: 'All Puroks',
        allowClear: true,
        width: '100%',
        minimumResultsForSearch: -1
    });

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
    $('#searchInput').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 380);
    });
    $('#purokFilter').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#purokFilter').val(null).trigger('change');
        table.ajax.reload();
    });

    /* Intercept view-button clicks in the DataTable */
    $('#householdsTable').on('click', 'a.btn-icon[title="View"], a[href*="/households/"][title="View"]', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        openHhPanel(url);
    });

    /* Axios DELETE — household row removal */
    $('#householdsTable').on('click', 'form button[type="submit"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const btn  = $(this);
        const form = btn.closest('form');
        const url  = form.attr('action');

        bmsConfirm({
            title:   'Delete Household',
            message: 'Delete this household? This cannot be undone.',
            ok:      'Delete',
        }, function () {
            const icon = btn.find('i');
            const orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
            btn.prop('disabled', true);

            axios.delete(url, { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(() => {
                    table.row(form.closest('tr')).remove().draw(false);
                })
                .catch(() => {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    alert('Could not delete household. Please try again.');
                });
        });
    });
});

function openHhPanel(url) {
    const panel = document.getElementById('hhQvPanel');
    const body  = document.getElementById('hhQvBody');
    panel.style.display = 'flex';
    requestAnimationFrame(() => { panel.style.opacity = '1'; });
    body.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                    height:200px;gap:12px">
            <i class="fas fa-spinner fa-spin" style="font-size:24px;color:var(--gold)"></i>
            <span style="font-size:13px;color:var(--text-muted)">Loading household…</span>
        </div>`;

    axios.get(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(({ data: h }) => {
            const members = (h.members || []).map(m => `
                <div style="display:flex;align-items:center;gap:10px;padding:8px 0;
                            border-bottom:1px solid var(--border)">
                    <div style="width:30px;height:30px;border-radius:50%;flex-shrink:0;overflow:hidden;
                                background:linear-gradient(135deg,var(--navy),var(--navy-mid));
                                display:flex;align-items:center;justify-content:center;
                                font-size:11px;font-weight:700;color:#fff">
                        ${m.photo_url
                            ? `<img src="${m.photo_url}" style="width:100%;height:100%;object-fit:cover">`
                            : m.initials}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;color:var(--text)">${m.full_name}</div>
                        <div style="font-size:12px;color:var(--text-muted)">${m.age} yrs · ${m.gender}${m.is_household_head ? ' · <strong style="color:var(--navy)">Head</strong>' : ''}</div>
                    </div>
                    ${m.is_voter ? '<span class="badge badge-navy" style="font-size:10px">Voter</span>' : ''}
                </div>`).join('') || '<div style="padding:20px;text-align:center;color:var(--text-muted)">No members found.</div>';

            body.innerHTML = `
                <div style="padding:18px 20px 14px;border-bottom:1px solid var(--border)">
                    <div style="font-size:16px;font-weight:700;color:var(--navy)">${h.household_number}</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:2px">${h.address || '—'}</div>
                    <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap">
                        <span class="badge badge-navy"><i class="fas fa-location-dot"></i> ${h.purok || '—'}</span>
                        <span class="badge badge-gray"><i class="fas fa-users"></i> ${h.family_size} member${h.family_size != 1 ? 's' : ''}</span>
                        ${h.is_voter_household ? '<span class="badge badge-green">Voter HH</span>' : ''}
                    </div>
                </div>
                <div style="padding:14px 20px">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;
                                color:var(--text-subtle);margin-bottom:10px">
                        <i class="fas fa-users"></i> Members
                    </div>
                    ${members}
                </div>
                <div style="padding:12px 20px;border-top:1px solid var(--border);
                            display:flex;gap:8px;background:var(--surface2)">
                    <a href="${h.show_url}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                        <i class="fas fa-eye"></i> Full Details
                    </a>
                    <a href="${h.edit_url}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>`;
        })
        .catch(() => {
            body.innerHTML = `<div style="padding:32px;text-align:center;color:var(--crimson)">
                <i class="fas fa-exclamation-circle" style="font-size:28px;opacity:.5;display:block;margin-bottom:10px"></i>
                Could not load household data.
            </div>`;
        });
}

function closeHhPanel() {
    const panel = document.getElementById('hhQvPanel');
    panel.style.opacity = '0';
    setTimeout(() => { panel.style.display = 'none'; }, 200);
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeHhPanel();
});
</script>
@endpush