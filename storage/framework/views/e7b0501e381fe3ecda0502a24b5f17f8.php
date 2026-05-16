<?php $__env->startSection('title', 'Business Permits'); ?>
<?php $__env->startSection('page-title', 'Business Permits'); ?>
<?php $__env->startSection('page-subtitle', 'Registered businesses in Barangay New Era'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header" id="tour-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Business Permits</h1>
            <p class="page-subtitle">Registered businesses in Barangay New Era</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('businesses')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('export.pdf', 'businesses')); ?>" class="btn btn-secondary" title="Export PDF" id="tour-export">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="<?php echo e(route('export.excel', 'businesses')); ?>" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="<?php echo e(route('businesses.create')); ?>" class="btn btn-primary" id="tour-issue">
            <i class="fas fa-store"></i> Issue Permit
        </a>
    </div>
</div>


<?php if($summaryCounts['Overdue'] > 0 || $summaryCounts['ExpiringSoon'] > 0): ?>
<div class="biz-alerts mb-6">
    <?php if($summaryCounts['Overdue'] > 0): ?>
    <div class="biz-alert biz-alert-danger">
        <div class="biz-alert-icon"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="biz-alert-body">
            <div class="biz-alert-title">
                <?php echo e($summaryCounts['Overdue']); ?> Active Permit<?php echo e($summaryCounts['Overdue'] > 1 ? 's' : ''); ?> Overdue
            </div>
            <div class="biz-alert-text">These businesses still have Active status but their permits have already expired. Consider updating their status.</div>
        </div>
        <button class="btn btn-sm biz-alert-btn" onclick="quickFilter('expiryFilter','expired')">
            <i class="fas fa-filter"></i> Show Overdue
        </button>
    </div>
    <?php endif; ?>
    <?php if($summaryCounts['ExpiringSoon'] > 0): ?>
    <div class="biz-alert biz-alert-warning">
        <div class="biz-alert-icon"><i class="fas fa-clock"></i></div>
        <div class="biz-alert-body">
            <div class="biz-alert-title">
                <?php echo e($summaryCounts['ExpiringSoon']); ?> Permit<?php echo e($summaryCounts['ExpiringSoon'] > 1 ? 's' : ''); ?> Expiring Within 30 Days
            </div>
            <div class="biz-alert-text">Notify business owners to renew their barangay permits before they expire.</div>
        </div>
        <button class="btn btn-sm biz-alert-btn" onclick="quickFilter('expiryFilter','expiring_soon')">
            <i class="fas fa-filter"></i> Show Expiring
        </button>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>


<div class="grid-4 mb-6" id="tour-stats">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', null)">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(array_sum([$summaryCounts['Active'],$summaryCounts['Expired'],$summaryCounts['Suspended'],$summaryCounts['Cancelled']]))); ?></div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Active'])">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Active'])); ?></div>
            <div class="stat-label">Active Permits</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer;border-color:#fde68a" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <div class="stat-icon" style="background:#fffbeb;color:#b45309"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="color:#b45309"><?php echo e(number_format($summaryCounts['ExpiringSoon'])); ?></div>
            <div class="stat-label">Expiring in 30 Days</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer;border-color:#fecaca" onclick="quickFilter('expiryFilter', 'expired')">
        <div class="stat-icon" style="background:#fef2f2;color:#ef4444"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number" style="color:#ef4444"><?php echo e(number_format($summaryCounts['Overdue'])); ?></div>
            <div class="stat-label">Overdue (Active)</div>
        </div>
    </div>
</div>
<div class="grid-2 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Expired'])">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Expired'])); ?></div>
            <div class="stat-label">Marked Expired</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Suspended'])">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-pause-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Suspended'])); ?></div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>


<div class="card mb-6" id="tour-filters">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('businesses')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('businesses')">
            <i class="fas fa-sliders"></i>
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
                               placeholder="Business name, owner, permit number…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Business Type</label>
                    <select id="typeFilter" multiple>
                        <?php $__currentLoopData = $businessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="statusFilter" multiple>
                        <?php $__currentLoopData = ['Active','Expired','Suspended','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
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


<div class="card" id="tour-table">
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


<div id="bizQvPanel"
     style="display:none;opacity:0;position:fixed;inset:0;z-index:9500;
            align-items:flex-start;justify-content:flex-end;
            background:rgba(9,20,40,0.45);backdrop-filter:blur(3px);
            transition:opacity .2s"
     onclick="if(event.target===this)closeBizPanel()">
    <div style="width:420px;max-width:95vw;height:100vh;background:var(--surface);
                overflow-y:auto;box-shadow:-8px 0 40px rgba(0,0,0,0.22);
                display:flex;flex-direction:column;animation:qvSlideIn .2s ease">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid var(--border);
                    background:var(--navy);flex-shrink:0">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-store" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Business Quick View</span>
            </div>
            <button onclick="closeBizPanel()"
                    style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;
                           display:flex;align-items:center;justify-content:center;
                           color:rgba(255,255,255,0.7);cursor:pointer;font-size:13px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="bizQvBody" style="flex:1"></div>
    </div>
</div>
<style>
@keyframes qvSlideIn { from { transform:translateX(32px);opacity:0; } to { transform:translateX(0);opacity:1; } }
</style>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* Business Alert Banners */
.biz-alerts { display:flex; flex-direction:column; gap:10px; }
.biz-alert { display:flex; align-items:center; gap:14px; padding:12px 16px;
             border-radius:var(--radius); border:1px solid transparent;
             border-left-width:4px; }
.biz-alert-danger  { background:var(--crimson-pale,#fef2f2); border-color:var(--crimson-border,#fecaca); border-left-color:var(--crimson,#dc2626); }
.biz-alert-warning { background:#fffbeb; border-color:#fde68a; border-left-color:#f59e0b; }
.biz-alert-icon { font-size:18px; flex-shrink:0; }
.biz-alert-danger  .biz-alert-icon { color:var(--crimson,#dc2626); }
.biz-alert-warning .biz-alert-icon { color:#b45309; }
.biz-alert-body { flex:1; min-width:0; }
.biz-alert-title { font-size:13.5px; font-weight:700; line-height:1.3; }
.biz-alert-danger  .biz-alert-title { color:#991b1b; }
.biz-alert-warning .biz-alert-title { color:#92400e; }
.biz-alert-text { font-size:12px; margin-top:3px; }
.biz-alert-danger  .biz-alert-text { color:#b91c1c; }
.biz-alert-warning .biz-alert-text { color:#b45309; }
.biz-alert-btn { flex-shrink:0; border:1px solid var(--border); background:var(--surface); color:var(--text); }
.biz-alert-btn:hover { background:var(--navy); color:#fff; border-color:var(--navy); }
#businessesTable_wrapper .dataTables_length,
#businessesTable_wrapper .dataTables_filter { display:none; }
#businessesTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate { padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#businessesTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

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
#filterPanel .select2-container--default .select2-selection--multiple { padding: 3px 8px; cursor: pointer; }
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__rendered {
    padding: 0; display: flex; flex-wrap: wrap; gap: 3px; align-items: center; min-height: 30px;
}
#filterPanel .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: var(--text-subtle); font-size: 13.5px; margin: 2px 4px;
    float: none; display: inline-block; white-space: nowrap;
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

    const s2Multi  = { dropdownParent: $('body'), allowClear: false, width: '100%', closeOnSelect: false,
                       minimumResultsForSearch: 0,
                       language: { noResults: () => 'No matches', searching: () => 'Searching…' } };
    const s2Single = { dropdownParent: $('body'), allowClear: true,  width: '100%',
                       minimumResultsForSearch: 0,
                       language: { noResults: () => 'No matches' } };

    $('#typeFilter').select2($.extend({}, s2Multi,  { placeholder: 'All business types…' }));
    $('#statusFilter').select2($.extend({}, s2Multi,  { placeholder: 'All statuses…' }));
    $('#expiryFilter').select2($.extend({}, s2Single, { placeholder: 'All' }));

    var table = $('#businessesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('businesses.index')); ?>',
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

    /* ── Axios DELETE ─────────────────────────────────────────────────── */
    $('#businessesTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        const btn  = $(this);
        const form = btn.closest('form');
        const url  = form.attr('action');

        bmsConfirm({
            title:   form.data('confirm-title') || 'Delete Permit',
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
                    bmsToast(res.data.message || 'Permit deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete permit.', 'error');
                });
        });
    });

    /* ── Quick View ───────────────────────────────────────────────────── */
    $('#businessesTable').on('click', 'a.biz-qv-btn', function (e) {
        e.preventDefault();
        openBizPanel($(this).data('url'));
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
        const types = p.getAll('business_type'), statuses = p.getAll('status');
        if (types.length)    { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        if (statuses.length) { $('#statusFilter').val(statuses).trigger('change.select2'); any = true; }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                 n++;
        if (($('#typeFilter').val()   || []).length) n++;
        if (($('#statusFilter').val() || []).length) n++;
        if ($('#expiryFilter').val())                n++;
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
    window.quickFilter = function (filterId, value) {
        if (value === null)          { $('#' + filterId).val(null).trigger('change'); }
        else if (Array.isArray(value)) { $('#' + filterId).val(value).trigger('change'); }
        else                         { $('#' + filterId).val(value).trigger('change'); }
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            localStorage.setItem('fp_businesses', '1');
        }
        saveToUrl(); table.ajax.reload();
    };
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || localStorage.getItem('fp_businesses') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#typeFilter, #statusFilter, #expiryFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#expiryFilter').val(null).trigger('change');
        saveToUrl(); table.ajax.reload();
    });
});

/* ── Business Quick View Panel ────────────────────────────────────── */
function openBizPanel(url) {
    const panel = document.getElementById('bizQvPanel');
    const body  = document.getElementById('bizQvBody');
    panel.style.display = 'flex';
    requestAnimationFrame(() => { panel.style.opacity = '1'; });
    body.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                    height:200px;gap:12px">
            <i class="fas fa-spinner fa-spin" style="font-size:24px;color:var(--gold)"></i>
            <span style="font-size:13px;color:var(--text-muted)">Loading permit…</span>
        </div>`;

    axios.get(url)
        .then(function ({ data: b }) {
            const expBadge = b.expiry_status === 'overdue'
                ? `<span class="badge badge-red"><i class="fas fa-triangle-exclamation"></i> Overdue ${b.expiry_days ? '— ' + b.expiry_days + 'd ago' : ''}</span>`
                : b.expiry_status === 'expiring_soon'
                    ? `<span class="badge badge-yellow"><i class="fas fa-clock"></i> Expiring in ${b.expiry_days ?? '?'} days</span>`
                    : '';
            const statusCls = { Active: 'badge-green', Expired: 'badge-red', Suspended: 'badge-yellow', Cancelled: 'badge-gray' };

            body.innerHTML = `
                <div style="padding:18px 20px 14px;border-bottom:1px solid var(--border)">
                    <div style="font-size:16px;font-weight:700;color:var(--navy)">${b.business_name}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">${b.permit_number}</div>
                    <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap">
                        <span class="badge badge-navy"><i class="fas fa-store"></i> ${b.business_type}</span>
                        <span class="badge ${statusCls[b.status] || 'badge-gray'}">${b.status}</span>
                        ${expBadge}
                    </div>
                </div>
                <div style="padding:14px 20px">
                    <div style="display:grid;gap:10px">
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Address</div>
                            <div style="color:var(--text)">${b.business_address}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Owner</div>
                            <div style="color:var(--text);font-weight:600">${b.owner_name}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Contact</div>
                            <div style="color:var(--text)">${b.owner_contact}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px;padding-top:10px;border-top:1px solid var(--border)">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Permit Date</div>
                            <div style="color:var(--text)">${b.permit_date}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Expiry Date</div>
                            <div style="color:${b.expiry_status === 'overdue' ? 'var(--crimson)' : b.expiry_status === 'expiring_soon' ? '#b45309' : 'var(--text)'};font-weight:${b.expiry_status !== 'valid' ? '700' : '400'}">${b.expiry_date}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Issued By</div>
                            <div style="color:var(--text)">${b.issued_by}</div>
                        </div>
                        ${b.remarks && b.remarks !== '—' ? `
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Remarks</div>
                            <div style="color:var(--text-muted);font-style:italic">${b.remarks}</div>
                        </div>` : ''}
                    </div>
                </div>
                <div style="padding:12px 20px;border-top:1px solid var(--border);
                            display:flex;gap:8px;background:var(--surface2);margin-top:auto">
                    <a href="${b.show_url}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                        <i class="fas fa-eye"></i> Full Details
                    </a>
                    <a href="${b.edit_url}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>`;
        })
        .catch(function () {
            body.innerHTML = `<div style="padding:32px;text-align:center;color:var(--crimson)">
                <i class="fas fa-exclamation-circle" style="font-size:28px;opacity:.5;display:block;margin-bottom:10px"></i>
                Could not load business data.
            </div>`;
        });
}

function closeBizPanel() {
    const panel = document.getElementById('bizQvPanel');
    panel.style.opacity = '0';
    setTimeout(() => { panel.style.display = 'none'; }, 200);
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeBizPanel();
});

/* ── Shepherd.js Tour ─────────────────────────────────────────────── */
(function () {
    const TOUR_KEY = 'bms_tour_businesses_v1_<?php echo e(auth()->id()); ?>';
    if (localStorage.getItem(TOUR_KEY)) return;
    if (typeof Shepherd === 'undefined') return;

    const tour = new Shepherd.Tour({
        defaultStepOptions: { cancelIcon: { enabled: false }, scrollTo: { behavior: 'smooth', block: 'center' } },
        useModalOverlay: true,
    });

    const skipBtn = {
        text: '<i class="fas fa-forward"></i> Skip Tour',
        classes: 'shepherd-button-secondary',
        action: function () {
            Swal.fire({
                title: 'Skip this tour?',
                text: 'You can clear your browser\'s local storage to see it again.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0D2144',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, skip it',
                cancelButtonText: 'Continue tour',
            }).then(function (result) {
                if (result.isConfirmed) { localStorage.setItem(TOUR_KEY, new Date().toISOString()); tour.cancel(); }
                else { tour.show(tour.getCurrentStep().id); }
            });
        },
    };

    tour.addStep({ id: 'header', title: '<i class="fas fa-store" style="color:var(--gold)"></i>&nbsp; Business Permits', text: 'This module tracks all registered businesses in Barangay New Era — sari-sari stores, restaurants, salons, and more. Permits can be issued, renewed, suspended, or expired.', attachTo: { element: '#tour-header', on: 'bottom' }, buttons: [skipBtn, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'stats', title: '<i class="fas fa-clock" style="color:var(--gold)"></i>&nbsp; Expiry Tracking', text: 'The dashboard shows real-time expiry alerts. <strong style="color:#b45309">Expiring Soon</strong> means within 30 days. <strong style="color:#ef4444">Overdue</strong> means still Active but already expired. Click any card to filter.', attachTo: { element: '#tour-stats', on: 'bottom' }, buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'table', title: '<i class="fas fa-eye" style="color:var(--gold)"></i>&nbsp; Quick View Panel', text: 'Click the <strong>👁 eye icon</strong> on any record to open an instant preview panel — owner details, permit dates, expiry countdown — without leaving the page.', attachTo: { element: '#tour-table', on: 'top' }, buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'issue', title: '<i class="fas fa-store" style="color:var(--gold)"></i>&nbsp; Issue a Permit', text: 'Click <strong>Issue Permit</strong> to register a new business. You can link the owner to an existing resident record for faster data entry.', attachTo: { element: '#tour-issue', on: 'left' }, buttons: [{ text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: '<i class="fas fa-check"></i> Got it!', action: tour.complete, classes: 'shepherd-button-primary' }] });

    tour.on('complete', function () {
        localStorage.setItem(TOUR_KEY, new Date().toISOString());
        bmsToast('Tour complete! You\'re all set.', 'success');
    });
    setTimeout(function () { tour.start(); }, 900);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/businesses/businesses-index.blade.php ENDPATH**/ ?>