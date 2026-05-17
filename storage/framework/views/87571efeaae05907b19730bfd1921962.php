<?php $__env->startSection('title', 'Document Appointments'); ?>
<<<<<<< Updated upstream
<?php $__env->startSection('page-title', 'Document Appointments'); ?>
<?php $__env->startSection('page-subtitle', 'Resident document request scheduling'); ?>
=======

<?php $__env->startPush('styles'); ?>
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .page-header h1 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--navy);
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .page-header h1 i { color: var(--gold); }

    /* Filters */
    .filter-bar {
        background: #fff;
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        align-items: flex-end;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border: 1px solid #e5e7eb;
    }
    .filter-group { display: flex; flex-direction: column; gap: .3rem; }
    .filter-group label { font-size: .74rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .04em; }
    .filter-control {
        padding: .45rem .75rem;
        border: 1.5px solid #d1d5db;
        border-radius: var(--radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: .82rem;
        min-width: 160px;
    }
    .filter-control:focus { outline: none; border-color: var(--navy); }
    .filter-actions { display: flex; gap: .5rem; align-self: flex-end; }

    /* Table */
    .table-wrap {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .apt-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
    .apt-table th {
        background: var(--navy);
        color: rgba(255,255,255,.85);
        padding: .65rem 1rem;
        text-align: left;
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .apt-table td {
        padding: .75rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        color: #374151;
        font-size: 14px;
    }
    .apt-table tr:last-child td { border-bottom: none; }
    .apt-table tr:hover td { background: #fafafa; }

    .apt-num {
        font-weight: 700;
        color: var(--navy);
        font-size: .8rem;
        letter-spacing: .06em;
    }
    .apt-name { font-weight: 600; color: var(--navy); }
    .apt-doc { font-size: .8rem; color: #4b5563; }

    /* Status badges */
    .badge {
        display: inline-block;
        padding: .25rem .65rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 700;
        border: 1.5px solid;
    }
    .badge-pending    { background: var(--gold-pale);    color: #78450a; border-color: var(--gold-border); }
    .badge-confirmed  { background: var(--navy-pale);    color: var(--navy); border-color: var(--navy-border); }
    .badge-processing { background: #eff6ff;              color: #1d4ed8; border-color: #bfdbfe; }
    .badge-ready      { background: #f0fdf4;              color: #14532d; border-color: #bbf7d0; }
    .badge-released   { background: #f9fafb;              color: #6b7280; border-color: #d1d5db; }
    .badge-cancelled  { background: var(--crimson-pale); color: var(--crimson); border-color: var(--crimson-border); }

    /* Update modal */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop.open { display: flex; }
    .modal {
        background: #fff;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 440px;
        padding: 1.75rem;
        box-shadow: 0 8px 40px rgba(0,0,0,.2);
        position: relative;
    }
    .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 1.25rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 1rem;
        color: #9ca3af;
        cursor: pointer;
    }
    .modal-close:hover { color: var(--crimson); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .78rem; font-weight: 600; color: var(--navy); margin-bottom: .35rem; }
    .form-control {
        width: 100%;
        padding: .5rem .8rem;
        border: 1.5px solid #d1d5db;
        border-radius: var(--radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: .83rem;
    }
    .form-control:focus { outline: none; border-color: var(--navy); }
    .modal-actions { display: flex; justify-content: flex-end; gap: .6rem; margin-top: 1.25rem; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1.1rem;
        border-radius: var(--radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: opacity .2s;
    }
    .btn:hover { opacity: .88; }
    .btn-navy { background: var(--navy); color: #fff; }
    .btn-sm { padding: .35rem .75rem; font-size: .75rem; }
    .btn-outline { background: transparent; border: 1.5px solid var(--navy); color: var(--navy); }
    .btn-gold { background: var(--gold); color: #fff; }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #9ca3af;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: .75rem; }
    .empty-state p { font-size: .85rem; }

    .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid #f0f0f0; }
</style>
<?php $__env->stopPush(); ?>

>>>>>>> Stashed changes
<?php $__env->startSection('content'); ?>

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
        <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-secondary" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Portal
        </a>
    </div>
</div>


<?php
    $aptCounts = [];
    foreach($statuses as $s) {
        $aptCounts[$s] = \App\Models\DocumentAppointment::where('status', $s)->count();
    }
    $pendingCount  = $aptCounts['Pending']  ?? 0;
    $totalCount    = \App\Models\DocumentAppointment::count();
    $releasedCount = $aptCounts['Released'] ?? 0;
    $readyCount    = $aptCounts['Ready']    ?? 0;
?>
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statApptTotal"><?php echo e(number_format($totalCount)); ?></div>
            <div class="stat-label">Total Appointments</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Pending')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($pendingCount)); ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Ready')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-box-open"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($readyCount)); ?></div>
            <div class="stat-label">Ready for Pick-up</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Released')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($releasedCount)); ?></div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>


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
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Document Type</label>
                    <select id="docTypeFilter">
                        <option value=""></option>
                        <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dt); ?>"><?php echo e($dt); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<<<<<<< Updated upstream
            <tbody></tbody>
=======
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-id="<?php echo e($apt->id); ?>">
                    <td><span class="apt-num"><?php echo e($apt->appointment_number); ?></span></td>
                    <td>
                        <div class="apt-name"><?php echo e($apt->resident_name); ?></div>
                        <div style="font-size:13px;color:#9ca3af"><?php echo e($apt->contact_number); ?></div>
                    </td>
                    <td><div class="apt-doc"><?php echo e($apt->document_type); ?></div></td>
                    <td><?php echo e($apt->preferred_date->format('M d, Y')); ?></td>
                    <td style="color:#9ca3af"><?php echo e($apt->created_at->format('M d, Y')); ?></td>
                    <td>
                        <?php $sc = strtolower($apt->status); ?>
                        <span class="badge badge-<?php echo e($sc); ?>"><?php echo e($apt->status); ?></span>
                    </td>
                    <td style="white-space:nowrap;display:flex;gap:.4rem">
                        <button class="btn btn-navy btn-sm"
                                onclick="openModal(<?php echo e($apt->id); ?>, '<?php echo e($apt->appointment_number); ?>', '<?php echo e($apt->status); ?>', '<?php echo e(addslashes($apt->notes ?? '')); ?>')">
                            <i class="fas fa-pencil"></i> Update
                        </button>
                        <form method="POST" action="<?php echo e(route('appointments.destroy', $apt)); ?>"
                              data-confirm="Delete appointment <?php echo e($apt->appointment_number); ?>? This cannot be undone."
                              data-confirm-title="Delete Appointment"
                              data-confirm-ok="Delete">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm" style="background:var(--crimson-pale);color:var(--crimson);border:1.5px solid var(--crimson-border)"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
>>>>>>> Stashed changes
        </table>
    </div>
</div>


<<<<<<< Updated upstream
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
=======
<div class="modal-backdrop" id="statusModal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="modal-title"><i class="fas fa-rotate" style="color:var(--gold)"></i>&nbsp; Update Appointment Status</div>

        <form id="statusForm" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PATCH'); ?>

            <div style="font-size:13px;color:#6b7280;margin-bottom:1rem">
                Appointment: <strong id="modalAptNum" style="color:var(--navy)"></strong>
>>>>>>> Stashed changes
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
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional — visible to resident)</span></label>
                <textarea id="aptModalNotes" class="form-control" rows="3"
                          placeholder="e.g., Document is ready for pick-up…"></textarea>
            </div>
<<<<<<< Updated upstream
            <div id="aptStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeAptModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="aptStatusSaveBtn" onclick="saveAptStatus()" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Save Status
                </button>
=======

            <div id="statusError" style="display:none;color:var(--crimson);font-size:13px;margin-bottom:.75rem;padding:.5rem .75rem;background:#fef2f2;border-radius:6px;border:1px solid #fca5a5"></div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" id="statusSubmitBtn" class="btn btn-gold"><i class="fas fa-save"></i> Save Status</button>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
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
#appointmentsTable_wrapper .dataTables_length,
#appointmentsTable_wrapper .dataTables_filter { display:none; }
#appointmentsTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

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
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
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
            url: '<?php echo e(route('appointments.index')); ?>',
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

    /* ── Open status modal from DataTable ────────────────────────────── */
    $('#appointmentsTable').on('click', '.apt-status-btn', function () {
        _aptId = $(this).data('id');
        document.getElementById('aptModalNum').textContent  = $(this).data('num');
        document.getElementById('aptModalStatus').value     = $(this).data('status');
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

/* ── Status Modal ─────────────────────────────────────────────────── */
var _aptId = null;

function closeAptModal() {
    document.getElementById('aptStatusModal').style.display = 'none';
    _aptId = null;
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAptModal();
});

function saveAptStatus() {
    if (!_aptId) return;
    const btn    = document.getElementById('aptStatusSaveBtn');
    const errDiv = document.getElementById('aptStatusError');
    const status = document.getElementById('aptModalStatus').value;
    const notes  = document.getElementById('aptModalNotes').value;

    errDiv.style.display = 'none';
    btn.disabled   = true;
    btn.innerHTML  = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/appointments/' + _aptId + '/status', { status: status, notes: notes })
        .then(function (res) {
            closeAptModal();
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

=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    let _updateAptId = null;

    function openModal(id, aptNum, currentStatus, currentNotes) {
        _updateAptId = id;
        document.getElementById('modalAptNum').textContent = aptNum;
        document.getElementById('modalStatus').value  = currentStatus;
        document.getElementById('modalNotes').value   = currentNotes;
        document.getElementById('statusError').style.display = 'none';
        document.getElementById('statusModal').classList.add('open');
    }
    function closeModal() {
        document.getElementById('statusModal').classList.remove('open');
    }
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    const badgeClasses = ['badge-pending','badge-confirmed','badge-processing','badge-ready','badge-released','badge-cancelled'];

    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!_updateAptId) return;

        const btn    = document.getElementById('statusSubmitBtn');
        const errDiv = document.getElementById('statusError');
        const status = document.getElementById('modalStatus').value;
        const notes  = document.getElementById('modalNotes').value;
        const url    = '/appointments/' + _updateAptId + '/status';

        errDiv.style.display = 'none';
        btn.disabled    = true;
        btn.innerHTML   = '<i class="fas fa-spinner fa-spin"></i> Saving…';

        axios.patch(url, { status: status, notes: notes })
            .then(function(res) {
                const row = document.querySelector('tr[data-id="' + _updateAptId + '"]');
                if (row) {
                    const badge = row.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove(...badgeClasses);
                        badge.classList.add('badge-' + status.toLowerCase());
                        badge.textContent = status;
                    }
                }
                closeModal();
                bmsToast(res.data.message, 'success');
            })
            .catch(function(err) {
                const data = err.response?.data;
                const msg  = data?.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data?.message || 'Failed to update status.');
                errDiv.textContent   = msg;
                errDiv.style.display = 'block';
            })
            .finally(function() {
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Status';
            });
    });
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/appointments/index.blade.php ENDPATH**/ ?>