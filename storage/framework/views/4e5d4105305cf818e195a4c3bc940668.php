<?php $__env->startSection('title', 'Blotter Cases'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Blotter Cases</h1>
        <p class="page-subtitle">Incident and complaint records</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('export.pdf', 'blotter')); ?>" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="<?php echo e(route('export.excel', 'blotter')); ?>" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="<?php echo e(route('blotter.create')); ?>" class="btn btn-primary">
            <i class="fas fa-gavel"></i> File Case
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
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-circle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Active'] ?? 0)); ?></div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Under Investigation'])">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-magnifying-glass"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Under Investigation'] ?? 0)); ?></div>
            <div class="stat-label">Under Investigation</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Settled'])">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-handshake"></i></div>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
#blotterTable_wrapper .dataTables_length,
#blotterTable_wrapper .dataTables_filter { display:none; }
#blotterTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate { padding:12px 20px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#blotterTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#blotterTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
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

    $('#typeFilter').select2($.extend({}, s2Multi, { placeholder: 'All incident types…' }));
    $('#statusFilter').select2($.extend({}, s2Multi, {
        placeholder: 'All statuses…',
        minimumResultsForSearch: -1
    }));

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
        const types    = p.getAll('incident_type');
        const statuses = p.getAll('status');
        if (types.length)    { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        if (statuses.length) { $('#statusFilter').val(statuses).trigger('change.select2'); any = true; }
        return any;
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                   n++;
        if (($('#typeFilter').val()   || []).length)   n++;
        if (($('#statusFilter').val() || []).length)   n++;
        if ($('#dateFrom').val() || $('#dateTo').val()) n++;
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

    // Quick-filter from stat cards — sets multi-select and opens panel
    window.quickFilter = function (filterId, values) {
        $('#' + filterId).val(values).trigger('change');
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            sessionStorage.setItem('fp_blotter', '1');
        }
        saveToUrl();
        table.ajax.reload();
    };

    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_blotter') === '1') {
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

    $('#typeFilter, #statusFilter').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

    $('#dateFrom, #dateTo').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#dateFrom, #dateTo').val('');
        saveToUrl(); table.ajax.reload();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/blotter/blotter-index.blade.php ENDPATH**/ ?>