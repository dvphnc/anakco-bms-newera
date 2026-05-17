<?php $__env->startSection('title', 'Business Permits'); ?>
<?php $__env->startSection('page-title', 'Business Permits'); ?>
<?php $__env->startSection('page-subtitle', 'Registered businesses in Barangay New Era'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
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
        <a href="<?php echo e(route('export.pdf', 'businesses')); ?>" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="<?php echo e(route('export.excel', 'businesses')); ?>" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="<?php echo e(route('businesses.create')); ?>" class="btn btn-primary">
            <i class="fas fa-file-plus"></i> Issue Permit
        </a>
    </div>
</div>


<<<<<<< Updated upstream
<?php
    $bizAlertCount = ($summaryCounts['Overdue'] > 0 ? 1 : 0) + ($summaryCounts['ExpiringSoon'] > 0 ? 1 : 0);
?>
<?php if($bizAlertCount): ?>
<div class="alert-tray open no-print mb-6">
    <div class="alert-tray-hdr" onclick="this.closest('.alert-tray').classList.toggle('open')">
        <i class="fas fa-bell tray-icon"></i>
        <span><?php echo e($bizAlertCount); ?> Notice<?php echo e($bizAlertCount > 1 ? 's' : ''); ?> — Business Permit Alert<?php echo e($bizAlertCount > 1 ? 's' : ''); ?></span>
        <i class="fas fa-chevron-down tray-caret"></i>
    </div>
    <div class="alert-tray-body">

        <?php if($summaryCounts['Overdue'] > 0): ?>
        <div class="alert-item alert-permit">
            <i class="fas fa-triangle-exclamation"></i>
            <span>
                <strong><?php echo e($summaryCounts['Overdue']); ?> Active Permit<?php echo e($summaryCounts['Overdue'] > 1 ? 's' : ''); ?> Overdue —</strong>
                <?php echo e($overdueBusinesses->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ')); ?><?php echo e($summaryCounts['Overdue'] > 3 ? ' +' . ($summaryCounts['Overdue'] - 3) . ' more' : ''); ?>

            </span>
            <button class="alert-link" style="background:none;cursor:pointer" onclick="toggleFilters('businesses');quickFilter('expiryFilter','expired')">
                <i class="fas fa-filter" style="font-size:11px;margin-right:4px"></i> Show Overdue
            </button>
        </div>
        <?php endif; ?>

        <?php if($summaryCounts['ExpiringSoon'] > 0): ?>
        <div class="alert-item alert-senior">
            <i class="fas fa-clock"></i>
            <span>
                <strong><?php echo e($summaryCounts['ExpiringSoon']); ?> Permit<?php echo e($summaryCounts['ExpiringSoon'] > 1 ? 's' : ''); ?> Expiring Within 30 Days —</strong>
                <?php echo e($expiringBusinesses->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ')); ?><?php echo e($summaryCounts['ExpiringSoon'] > 3 ? ' +' . ($summaryCounts['ExpiringSoon'] - 3) . ' more' : ''); ?>

            </span>
            <button class="alert-link" style="background:none;cursor:pointer" onclick="toggleFilters('businesses');quickFilter('expiryFilter','expiring_soon')">
                <i class="fas fa-filter" style="font-size:11px;margin-right:4px"></i> Show Expiring
            </button>
        </div>
        <?php endif; ?>

    </div>
=======
<?php if($summaryCounts['Overdue'] > 0): ?>
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:var(--radius);margin-bottom:14px">
    <i class="fas fa-triangle-exclamation" style="color:#ef4444;font-size:18px;flex-shrink:0"></i>
    <div style="flex:1">
        <div style="font-size:14px;font-weight:700;color:#991b1b"><?php echo e($summaryCounts['Overdue']); ?> Active Permit<?php echo e($summaryCounts['Overdue'] > 1 ? 's' : ''); ?> are Overdue!</div>
        <div style="font-size:13px;color:#ef4444">These businesses have active status but their permits have already expired. Consider updating their status.</div>
    </div>
    <button class="btn btn-secondary btn-sm" onclick="quickFilter('expiryFilter', 'expired')">
        <i class="fas fa-filter"></i> Show Overdue
    </button>
</div>
<?php endif; ?>

<?php if($summaryCounts['ExpiringSoon'] > 0): ?>
<div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:var(--radius);margin-bottom:14px">
    <i class="fas fa-clock" style="color:#f59e0b;font-size:18px;flex-shrink:0"></i>
    <div style="flex:1">
        <div style="font-size:14px;font-weight:700;color:#92400e"><?php echo e($summaryCounts['ExpiringSoon']); ?> Permit<?php echo e($summaryCounts['ExpiringSoon'] > 1 ? 's' : ''); ?> Expiring Within 30 Days</div>
        <div style="font-size:13px;color:#b45309">Notify business owners to renew their barangay permits soon.</div>
    </div>
    <button class="btn btn-secondary btn-sm" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <i class="fas fa-filter"></i> Show Expiring
    </button>
>>>>>>> Stashed changes
</div>
<?php endif; ?>


<div class="grid-4 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', null)">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statBizTotal"><?php echo e(number_format(array_sum([$summaryCounts['Active'],$summaryCounts['Expired'],$summaryCounts['Suspended'],$summaryCounts['Cancelled']]))); ?></div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Active')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-check-circle"></i></div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Active'])">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-check-circle"></i></div>
>>>>>>> Stashed changes
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Active'])); ?></div>
            <div class="stat-label">Active Permits</div>
        </div>
    </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-clock"></i></div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    <div class="stat-card" style="cursor:pointer;border-color:#fde68a" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <div class="stat-icon" style="background:#fffbeb;color:#b45309"><i class="fas fa-clock"></i></div>
>>>>>>> Stashed changes
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['ExpiringSoon'])); ?></div>
            <div class="stat-label">Expiring in 30 Days</div>
        </div>
    </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('expiryFilter', 'expired')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-triangle-exclamation"></i></div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    <div class="stat-card" style="cursor:pointer;border-color:#fecaca" onclick="quickFilter('expiryFilter', 'expired')">
        <div class="stat-icon" style="background:#fef2f2;color:#ef4444"><i class="fas fa-triangle-exclamation"></i></div>
>>>>>>> Stashed changes
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Overdue'])); ?></div>
            <div class="stat-label">Overdue (Active)</div>
        </div>
    </div>
</div>
<div class="grid-2 mb-6">
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Expired')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-times-circle"></i></div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Expired'])">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C"><i class="fas fa-times-circle"></i></div>
>>>>>>> Stashed changes
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Expired'])); ?></div>
            <div class="stat-label">Marked Expired</div>
        </div>
    </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Suspended')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-pause-circle"></i></div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', ['Suspended'])">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-pause-circle"></i></div>
>>>>>>> Stashed changes
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($summaryCounts['Suspended'])); ?></div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('businesses')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('businesses')">
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
            <i class="fas fa-sliders"></i>
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
=======
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
>>>>>>> Stashed changes
            <span id="filterToggleText">Show Filters</span>
        </button>
    </div>
    <div id="filterPanel" style="display:none">
        <div class="card-body" style="padding:20px 22px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
=======

                
>>>>>>> Stashed changes
=======

                
>>>>>>> Stashed changes
=======

                
>>>>>>> Stashed changes
=======

                
>>>>>>> Stashed changes
=======

                
>>>>>>> Stashed changes
=======

                
>>>>>>> Stashed changes
=======

                
>>>>>>> Stashed changes
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                               placeholder="Business name, owner, permit number…">
                    </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Business Type</label>
                    <select id="typeFilter">
                        <option value=""></option>
                        <?php $__currentLoopData = $businessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
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
=======
>>>>>>> Stashed changes
                </div>
=======
                </div>
>>>>>>> Stashed changes
=======
                </div>
>>>>>>> Stashed changes
=======
                </div>
>>>>>>> Stashed changes
=======
                </div>
>>>>>>> Stashed changes
=======
                </div>
>>>>>>> Stashed changes
=======
                </div>
>>>>>>> Stashed changes

                
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


<div class="card">
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
/* ── SaaS surface & stat card polish ──────────────────────────────── */
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }
#businessesTable_wrapper .dataTables_length,
#businessesTable_wrapper .dataTables_filter { display:none; }
#businessesTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate { padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#businessesTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

/* ── Filter Select2 height override — filter panels use 38px, not 48px ── */
#filterPanel .select2-container--default .select2-selection--single {
    height: 38px !important;
    padding: 0 32px 0 10px !important;
    min-height: unset !important;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
    top: 0 !important;
    right: 8px !important;
}
</style>
<script>
$(document).ready(function () {

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    /* Temporarily expose the hidden filter panel so Select2 measures real dimensions.
       The browser won't paint until after this synchronous block, so no visual flash. */
    var $fp = $('#filterPanel');
    var _fpW = $fp.parent().width();
    $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });

    const s2Single = { dropdownParent: $('body'), allowClear: true,  width: '100%',
                       minimumResultsForSearch: 0,
                       language: { noResults: () => 'No matches' } };

    $('#typeFilter').select2($.extend({}, s2Single, { placeholder: 'All business types…' }));
    $('#statusFilter').select2($.extend({}, s2Single, { placeholder: 'All statuses…' }));
    $('#expiryFilter').select2($.extend({}, s2Single, { placeholder: 'All' }));

    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });

=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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
    const s2Single = {
        dropdownParent: $('body'),
        allowClear: true,
        width: '100%',
        language: {
            noResults: function () { return 'No matches'; }
        }
    };

    $('#typeFilter').select2($.extend({}, s2Multi, { placeholder: 'All business types…' }));
    $('#statusFilter').select2($.extend({}, s2Multi, { placeholder: 'All statuses…', minimumResultsForSearch: -1 }));
    $('#expiryFilter').select2($.extend({}, s2Single, { placeholder: 'All', minimumResultsForSearch: -1 }));

    /* ── DataTable ────────────────────────────────────────────────────── */
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
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
                    bmsStatDecrement('statBizTotal');
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
        if ($('#typeFilter').val())   url.searchParams.set('business_type', $('#typeFilter').val());
        if ($('#statusFilter').val()) url.searchParams.set('status', $('#statusFilter').val());
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))             { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('expiry_filter')) { $('#expiryFilter').val(p.get('expiry_filter')).trigger('change.select2'); any = true; }
        const type = p.get('business_type'), status = p.get('status');
        if (type)   { $('#typeFilter').val(type).trigger('change.select2'); any = true; }
        if (status) { $('#statusFilter').val(status).trigger('change.select2'); any = true; }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                 n++;
        if ($('#typeFilter').val())   n++;
        if ($('#statusFilter').val()) n++;
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
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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
        const types    = p.getAll('business_type');
        const statuses = p.getAll('status');
        if (types.length)    { $('#typeFilter').val(types).trigger('change.select2'); any = true; }
        if (statuses.length) { $('#statusFilter').val(statuses).trigger('change.select2'); any = true; }
        return any;
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if (($('#typeFilter').val()   || []).length)    n++;
        if (($('#statusFilter').val() || []).length)    n++;
        if ($('#expiryFilter').val())                   n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; }
        else       { badge.style.display = 'none'; }
    }

    /* ── Panel toggle + quick-filter (for stat cards & alerts) ───────── */
    window.toggleFilters = function (key) {
        const panel = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        sessionStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };

    window.quickFilter = function (filterId, value) {
        if (value === null) {
            $('#' + filterId).val(null).trigger('change');
        } else if (Array.isArray(value)) {
            $('#' + filterId).val(value).trigger('change');
        } else {
            $('#' + filterId).val(value).trigger('change');
        }
        if (document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
            sessionStorage.setItem('fp_businesses', '1');
        }
        saveToUrl();
        table.ajax.reload();
    };

    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_businesses') === '1') {
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

    $('#typeFilter, #statusFilter, #expiryFilter').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/businesses/businesses-index.blade.php ENDPATH**/ ?>