<?php $__env->startSection('title', 'Blotter Cases'); ?>
<?php $__env->startSection('page-title', 'Blotter Cases'); ?>
<?php $__env->startSection('page-subtitle', 'Incident and complaint records'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Blotter Cases</h1>
            <p class="page-subtitle">Incident and complaint records</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('blotter')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('export.pdf', 'blotter')); ?>" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="<?php echo e(route('export.excel', 'blotter')); ?>" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="<?php echo e(route('blotter.create')); ?>" class="btn btn-primary">
            <i class="fas fa-file-plus"></i> File Case
        </a>
    </div>
</div>

<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-gavel"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\BlotterCase::count())); ?></div>
            <div class="stat-label">Total Cases</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Active'])">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Active'] ?? 0)); ?></div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Under Investigation'])">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-magnifying-glass"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Under Investigation'] ?? 0)); ?></div>
            <div class="stat-label">Under Investigation</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Settled'])">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-handshake"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Settled'] ?? 0)); ?></div>
            <div class="stat-label">Settled</div>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('blotter')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('blotter')">
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
                               placeholder="Case number, complainant, respondent…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Type</label>
                    <select id="typeFilter" multiple>
                        <?php $__currentLoopData = $incidentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Case Status</label>
                    <select id="statusFilter" multiple>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Date — From</label>
                    <input type="date" id="dateFrom" class="form-control">
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Date — To</label>
                    <input type="date" id="dateTo" class="form-control">
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


<div id="blotterStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBlotterStatusModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Case Status</span>
            </div>
            <button onclick="closeBlotterStatusModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Case: <strong id="blotterStatusNum" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="blotterStatusSelect" class="form-control">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Resolution Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional)</span></label>
                <textarea id="blotterStatusNotes" class="form-control" rows="3"
                          placeholder="e.g., Parties agreed to settle — signed on May 10, 2026…"></textarea>
            </div>
            <div id="blotterStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeBlotterStatusModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="blotterStatusSaveBtn" onclick="saveBlotterStatus()" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Save Status
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* ── SaaS surface & stat card polish ──────────────────────────────── */
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }
#blotterTable_wrapper .dataTables_length,
#blotterTable_wrapper .dataTables_filter { display:none; }
#blotterTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate { padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#blotterTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

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
#filterPanel .select2-container--default .select2-selection--multiple {
    padding: 3px 8px; cursor: pointer;
    display: block; width: 100%; box-sizing: border-box;
}
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    padding: 0; display: flex; flex-wrap: wrap; gap: 3px; align-items: center; min-height: 30px; width: 100%;
}
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: var(--text-subtle); font-size: 13.5px; margin: 0 4px;
    float: none; display: inline-block; white-space: nowrap;
    flex: 1 0 auto; line-height: 30px;
}
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background: var(--navy); color: #fff; border: none; border-radius: 99px;
    padding: 2px 8px; font-size: 12px; margin: 2px 2px 2px 0; display: inline-flex; align-items: center; gap: 5px;
}
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255,255,255,.65); background: transparent; border: none; font-weight: normal; order: 1; padding: 0;
}
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover { color: #fff; background: transparent; }
</style>
<script>
$(document).ready(function () {

    /* ── Select2 init ─────────────────────────────────────────────────── */
    const s2Multi = { dropdownParent: $('body'), allowClear: false, width: '100%', closeOnSelect: false,
                      minimumResultsForSearch: 0,
                      language: { noResults: () => 'No matches', searching: () => 'Searching…' } };

    $('#typeFilter').select2($.extend({}, s2Multi, { placeholder: 'All incident types…' }));
    $('#statusFilter').select2($.extend({}, s2Multi, { placeholder: 'All statuses…' }));

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#blotterTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('blotter.index')); ?>',
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
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-gavel"></i><p>No blotter cases found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No cases match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── Axios DELETE ─────────────────────────────────────────────────── */
    $('#blotterTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const btn  = $(this);
        const form = btn.closest('form');
        const url  = form.attr('action');

        bmsConfirm({
            title:   form.data('confirm-title') || 'Delete Case',
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
                    bmsToast(res.data.message || 'Case deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete case.', 'error');
                });
        });
    });

    /* ── URL persistence ──────────────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s','date_from','date_to'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('incident_type');
        url.searchParams.delete('status');
        if ($('#searchInput').val()) url.searchParams.set('s', $('#searchInput').val());
        if ($('#dateFrom').val())    url.searchParams.set('date_from', $('#dateFrom').val());
        if ($('#dateTo').val())      url.searchParams.set('date_to', $('#dateTo').val());
        ($('#typeFilter').val()   || []).forEach(v => url.searchParams.append('incident_type', v));
        ($('#statusFilter').val() || []).forEach(v => url.searchParams.append('status', v));
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))         { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('date_from')) { $('#dateFrom').val(p.get('date_from')); any = true; }
        if (p.get('date_to'))   { $('#dateTo').val(p.get('date_to')); any = true; }
        const types = p.getAll('incident_type'), statuses = p.getAll('status');
        if (types.length)    { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        if (statuses.length) { $('#statusFilter').val(statuses).trigger('change.select2'); any = true; }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if (($('#typeFilter').val()   || []).length)    n++;
        if (($('#statusFilter').val() || []).length)    n++;
        if ($('#dateFrom').val() || $('#dateTo').val()) n++;
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
    window.quickFilter = function (filterId, values) {
        $('#' + filterId).val(values).trigger('change');
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            localStorage.setItem('fp_blotter', '1');
        }
        saveToUrl(); table.ajax.reload();
    };
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || localStorage.getItem('fp_blotter') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#typeFilter, #statusFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#dateFrom, #dateTo').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#dateFrom, #dateTo').val('');
        saveToUrl(); table.ajax.reload();
    });
});

/* ── Quick Status Modal ───────────────────────────────────────────── */
var _blotterStatusId = null;

$(document).on('click', '#blotterTable .blotter-status-btn', function () {
    _blotterStatusId = $(this).data('id');
    document.getElementById('blotterStatusNum').textContent = $(this).data('num');
    document.getElementById('blotterStatusSelect').value    = $(this).data('status');
    document.getElementById('blotterStatusNotes').value     = $(this).data('notes') || '';
    document.getElementById('blotterStatusError').style.display = 'none';
    document.getElementById('blotterStatusModal').style.display = 'flex';
});

function closeBlotterStatusModal() {
    document.getElementById('blotterStatusModal').style.display = 'none';
    _blotterStatusId = null;
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeBlotterStatusModal(); }
});

function saveBlotterStatus() {
    if (!_blotterStatusId) return;
    const btn    = document.getElementById('blotterStatusSaveBtn');
    const errDiv = document.getElementById('blotterStatusError');
    const status = document.getElementById('blotterStatusSelect').value;
    const notes  = document.getElementById('blotterStatusNotes').value;

    errDiv.style.display = 'none';
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/blotter/' + _blotterStatusId + '/status', { status: status, resolution_notes: notes })
        .then(function (res) {
            const clsMap = {
                'Active':               'badge-red',
                'Under Investigation':  'badge-yellow',
                'Mediated':             'badge-blue',
                'Settled':              'badge-green',
                'Closed':               'badge-gray',
                'Referred to Higher Authority': 'badge-orange',
            };
            const row = $('button.blotter-status-btn[data-id="' + _blotterStatusId + '"]').closest('tr');
            row.find('.blotter-status-btn').data('status', res.data.status).data('notes', notes);
            row.find('td .badge:not(.badge-navy,.badge-red[title])').first().each(function () {
                $(this).removeClass('badge-red badge-yellow badge-blue badge-green badge-gray badge-orange')
                       .addClass(clsMap[res.data.status] || 'badge-gray').text(res.data.status);
            });
            closeBlotterStatusModal();
            bmsToast(res.data.message, 'success');
            $('#blotterTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            errDiv.textContent   = err.response?.data?.message || 'Failed to update status.';
            errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status';
        });
}

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/blotter/blotter-index.blade.php ENDPATH**/ ?>