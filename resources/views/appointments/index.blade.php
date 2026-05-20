@extends('layouts.app')
@section('title', 'Document Appointments')
@section('page-title', 'Document Appointments')
@section('page-subtitle', 'Resident document request scheduling')
@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Document Appointments</h1>
            <p class="page-subtitle">Resident document request scheduling</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('appointments')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a href="{{ route('portal.index') }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Portal
        </a>
    </div>
</div>

{{-- Stat cards --}}
@php
    $aptCounts = [];
    foreach($statuses as $s) {
        $aptCounts[$s] = \App\Models\DocumentAppointment::where('status', $s)->count();
    }
    $pendingCount  = $aptCounts['Pending']  ?? 0;
    $totalCount    = \App\Models\DocumentAppointment::count();
    $releasedCount = $aptCounts['Released'] ?? 0;
    $readyCount    = $aptCounts['Ready']    ?? 0;
@endphp
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statApptTotal">{{ number_format($totalCount) }}</div>
            <div class="stat-label">Total Appointments</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Pending')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptPending">{{ number_format($pendingCount) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Ready')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-box-open"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptReady">{{ number_format($readyCount) }}</div>
            <div class="stat-label">Ready for Pick-up</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Released')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptReleased">{{ number_format($releasedCount) }}</div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>

{{-- Collapsible Filter Bar --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('appointments')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('appointments')">
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
                               placeholder="Resident name, appointment number…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Document Type</label>
                    <select id="docTypeFilter">
                        <option value=""></option>
                        @foreach($documentTypes as $dt)
                            <option value="{{ $dt }}">{{ $dt }}</option>
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

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-calendar-check"></i> Appointment Records</span>
    </div>
    <div class="table-responsive">
        <table id="appointmentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Appt. No.</th>
                    <th>Resident</th>
                    <th>Document Type</th>
                    <th>Preferred Date</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Issue Document Modal --}}
<div id="aptConvertModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeConvertModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        {{-- Header --}}
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-file-circle-check" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Issue Document Record</span>
            </div>
            <button onclick="closeConvertModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:18px">

            {{-- Appointment summary (read-only) --}}
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:12px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Appointment No.</div>
                        <div id="cvtNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Document Type</div>
                        <div id="cvtType" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Resident</div>
                        <div id="cvtName" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div id="cvtPurposeRow" style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Purpose</div>
                        <div id="cvtPurpose" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>

            {{-- Editable fields --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">
                        Fee Paid (₱)
                        <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span>
                    </label>
                    <input type="number" id="cvtFee" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">
                        O.R. Number
                        <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span>
                    </label>
                    <input type="text" id="cvtOR" class="form-control" placeholder="e.g. 2026-00123">
                </div>
            </div>

            <div id="cvtError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>

            {{-- Info note --}}
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Creates a <strong style="color:var(--navy)">Released</strong> document record in Document Issuance and automatically marks this appointment as Released.</span>
            </div>

            {{-- Actions --}}
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeConvertModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="cvtSaveBtn" onclick="saveConvert()" class="btn btn-primary">
                    <i class="fas fa-file-circle-check"></i> Issue Document
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Update Status Modal --}}
<div id="aptStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeAptModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:440px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Appointment Status</span>
            </div>
            <button onclick="closeAptModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Appointment: <strong id="aptModalNum" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="aptModalStatus" class="form-control">
                    @foreach($statuses as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional — visible to resident)</span></label>
                <textarea id="aptModalNotes" class="form-control" rows="3"
                          placeholder="e.g., Document is ready for pick-up…"></textarea>
            </div>
            <div id="aptStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeAptModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="aptStatusSaveBtn" onclick="saveAptStatus()" class="btn btn-primary">
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
/* ── SaaS surface & stat card polish ──────────────────────────────── */
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }
#appointmentsTable_wrapper .dataTables_length,
#appointmentsTable_wrapper .dataTables_filter { display:none; }
#appointmentsTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

/* ── Success button (green — not yet issued) ─────────────────── */
.btn-success { background:#16a34a; color:#fff; border-color:#16a34a; }
.btn-success:disabled { opacity:.65; cursor:not-allowed; }

/* ── View Document hover — same navy highlight as Update Status ── */
.apt-viewdoc-btn { transition:background .15s, color .15s, border-color .15s, box-shadow .15s; }
.apt-viewdoc-btn:hover { background:var(--navy) !important; color:#fff !important; border-color:var(--navy) !important; box-shadow:0 4px 12px rgba(13,33,68,0.2); }

/* ── Filter Select2 — match residents blade ───────────────────── */
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

    /* Temporarily expose the hidden filter panel so Select2 measures real dimensions.
       The browser won't paint until after this synchronous block, so no visual flash. */
    var $fp = $('#filterPanel');
    var _fpW = $fp.parent().width();
    $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });

    const s2Single = { dropdownParent: $('body'), allowClear: true, width: '100%',
                       minimumResultsForSearch: 0,
                       language: { noResults: () => 'No matches' } };

    $('#statusFilter').select2($.extend({}, s2Single, { placeholder: 'All statuses…' }));
    $('#docTypeFilter').select2($.extend({}, s2Single, { placeholder: 'All document types…' }));

    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });

    var table = $('#appointmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('appointments.index') }}',
            data: function (d) {
                d.status        = $('#statusFilter').val();
                d.document_type = $('#docTypeFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',    name: 'appointment_number' },
            { data: 'resident_col',  name: 'resident_name',    orderable: false },
            { data: 'document_col',  name: 'document_type',    orderable: false },
            { data: 'date_col',      name: 'preferred_date' },
            { data: 'submitted_col', name: 'created_at' },
            { data: 'status_col',    name: 'status' },
            { data: 'actions',       name: 'actions', orderable: false, searchable: false },
        ],
        order: [[3, 'asc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-calendar-check"></i><p>No appointments found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No appointments match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── Axios DELETE ─────────────────────────────────────────────────── */
    $('#appointmentsTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const btn  = $(this);
        const form = btn.closest('form');
        const url  = form.attr('action');

        bmsConfirm({
            title:   form.data('confirm-title') || 'Delete Appointment',
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
                    bmsStatDecrement('statApptTotal');
                    bmsToast(res.data.message || 'Appointment deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete appointment.', 'error');
                });
        });
    });

    /* ── Open convert modal from DataTable ───────────────────────────── */
    $('#appointmentsTable').on('click', '.apt-convert-btn', function () {
        var $btn = $(this);
        document.getElementById('cvtNum').textContent     = $btn.data('num');
        document.getElementById('cvtName').textContent    = $btn.data('name');
        document.getElementById('cvtType').textContent    = $btn.data('type');
        var purpose = $btn.data('purpose') || '';
        var purposeRow = document.getElementById('cvtPurposeRow');
        if (purpose) {
            document.getElementById('cvtPurpose').textContent = purpose;
            purposeRow.style.display = '';
        } else {
            purposeRow.style.display = 'none';
        }
        document.getElementById('cvtFee').value           = '';
        document.getElementById('cvtOR').value            = '';
        document.getElementById('cvtError').style.display = 'none';
        window._cvtUrl = $btn.data('url');
        window._cvtAptId = $btn.data('id');
        document.getElementById('aptConvertModal').style.display = 'flex';
    });

    /* ── Open status modal from DataTable ────────────────────────────── */
    $('#appointmentsTable').on('click', '.apt-status-btn', function () {
        _aptId       = $(this).data('id');
        _aptOldStatus = $(this).data('status');   // remember old status for stat-card delta
        document.getElementById('aptModalNum').textContent  = $(this).data('num');
        document.getElementById('aptModalStatus').value     = _aptOldStatus;
        document.getElementById('aptModalNotes').value      = $(this).data('notes') || '';
        document.getElementById('aptStatusError').style.display = 'none';
        document.getElementById('aptStatusModal').style.display = 'flex';
    });

    /* ── URL persistence ──────────────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('status');
        url.searchParams.delete('document_type');
        if ($('#searchInput').val()) url.searchParams.set('s', $('#searchInput').val());
        if ($('#statusFilter').val())  url.searchParams.set('status', $('#statusFilter').val());
        if ($('#docTypeFilter').val()) url.searchParams.set('document_type', $('#docTypeFilter').val());
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s')) { $('#searchInput').val(p.get('s')); any = true; }
        const status = p.get('status'), type = p.get('document_type');
        if (status) { $('#statusFilter').val(status).trigger('change.select2'); any = true; }
        if (type)   { $('#docTypeFilter').val(type).trigger('change.select2'); any = true; }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if ($('#statusFilter').val())  n++;
        if ($('#docTypeFilter').val()) n++;
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

    const hasUrlFilters = loadFromUrl();
    const lsOpen = localStorage.getItem('fp_appointments') === '1';
    if (hasUrlFilters || lsOpen) {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    /* ── Filters ──────────────────────────────────────────────────────── */
    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#statusFilter, #docTypeFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#statusFilter, #docTypeFilter').val(null).trigger('change');
        saveToUrl(); table.ajax.reload();
    });
});

window.aptQuickFilter = function (filterId, values) {
    $('#' + filterId).val(values).trigger('change');
    if (document.getElementById('filterPanel').style.display === 'none') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
        localStorage.setItem('fp_appointments', '1');
    }
    $('#appointmentsTable').DataTable().ajax.reload();
};

/* ── Convert Modal ────────────────────────────────────────────────── */
window._cvtUrl   = null;
window._cvtAptId = null;

function closeConvertModal() {
    document.getElementById('aptConvertModal').style.display = 'none';
    window._cvtUrl   = null;
    window._cvtAptId = null;
}

function saveConvert() {
    if (!window._cvtUrl) return;
    var btn    = document.getElementById('cvtSaveBtn');
    var errDiv = document.getElementById('cvtError');
    var fee    = document.getElementById('cvtFee').value;
    var or_num = document.getElementById('cvtOR').value;

    errDiv.style.display = 'none';
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Issuing…';

    axios.post(window._cvtUrl, { fee_paid: fee || null, or_number: or_num || null })
        .then(function (res) {
            closeConvertModal();
            bmsToast(res.data.message, 'success');

            // Reload table so the row now shows the View Document button
            $('#appointmentsTable').DataTable().ajax.reload(null, false);

            // Offer a quick link to view/print the document
            setTimeout(function () {
                bmsConfirm({
                    title:   'Document Issued',
                    message: res.data.doc_number + ' has been created. Open the document record now?',
                    ok:      'Open Document',
                }, function () {
                    window.open(res.data.view_url, '_blank');
                });
            }, 400);
        })
        .catch(function (err) {
            var data = err.response?.data;
            var msg  = data?.errors
                ? Object.values(data.errors).flat().join(' ')
                : (data?.message || 'Failed to issue document.');

            // If already converted, offer the view link
            if (err.response?.status === 422 && data?.view_url) {
                msg += ' <a href="' + data.view_url + '" target="_blank" style="color:var(--navy);font-weight:600">View it here →</a>';
            }
            errDiv.innerHTML     = msg;
            errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-file-circle-check"></i> Issue Document';
        });
}

/* ── Status Modal ─────────────────────────────────────────────────── */
var _aptId = null;
var _aptOldStatus = null;

// Maps a status name to its stat-card element ID (only the 3 tracked ones)
var _aptStatMap = { 'Pending': 'statAptPending', 'Ready': 'statAptReady', 'Released': 'statAptReleased' };

function _aptStatDelta(status, delta) {
    var elId = _aptStatMap[status];
    if (!elId) return;
    var el = document.getElementById(elId);
    if (!el) return;
    var current = parseInt(el.textContent.replace(/,/g, ''), 10) || 0;
    var next    = Math.max(0, current + delta);
    // Animate the number with a brief highlight flash
    el.textContent = next.toLocaleString();
    el.style.transition = 'color .15s';
    el.style.color = delta > 0 ? 'var(--gold)' : 'var(--crimson)';
    setTimeout(function () { el.style.color = ''; }, 800);
}

function closeAptModal() {
    document.getElementById('aptStatusModal').style.display = 'none';
    _aptId        = null;
    _aptOldStatus = null;
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeAptModal(); closeConvertModal(); }
});

function saveAptStatus() {
    if (!_aptId) return;
    const btn       = document.getElementById('aptStatusSaveBtn');
    const errDiv    = document.getElementById('aptStatusError');
    const newStatus = document.getElementById('aptModalStatus').value;
    const notes     = document.getElementById('aptModalNotes').value;
    const oldStatus = _aptOldStatus;

    if (newStatus === oldStatus) {
        closeAptModal();
        return;   // nothing to do
    }

    errDiv.style.display = 'none';
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/appointments/' + _aptId + '/status', { status: newStatus, notes: notes })
        .then(function (res) {
            closeAptModal();

            // ── Sync stat cards from server-confirmed counts ──────────
            if (res.data.counts) {
                Object.entries(res.data.counts).forEach(function ([s, n]) {
                    var elId = _aptStatMap[s];
                    if (!elId) return;
                    var el = document.getElementById(elId);
                    if (!el) return;
                    var prev = parseInt(el.textContent.replace(/,/g, ''), 10) || 0;
                    el.textContent = n.toLocaleString();
                    if (n !== prev) {
                        el.style.transition = 'color .15s';
                        el.style.color = n > prev ? 'var(--gold)' : 'var(--crimson)';
                        setTimeout(function () { el.style.color = ''; }, 800);
                    }
                });
            } else {
                // Fallback: optimistic delta if server didn't return counts
                _aptStatDelta(oldStatus, -1);
                _aptStatDelta(newStatus, +1);
            }

            bmsToast(res.data.message || 'Status updated.', 'success');
            $('#appointmentsTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            const data = err.response?.data;
            const msg  = data?.errors
                ? Object.values(data.errors).flat().join(' ')
                : (data?.message || 'Failed to update status.');
            errDiv.textContent   = msg;
            errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status';
        });
}

</script>
@endpush
