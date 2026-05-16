<?php $__env->startSection('title', 'Document Appointments'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header" id="tour-header">
    <div>
        <h1 class="page-title">Document Appointments</h1>
        <p class="page-subtitle">Resident document request scheduling</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-secondary" target="_blank" id="tour-portal">
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
<div class="grid-4 mb-6" id="tour-stats">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($totalCount)); ?></div>
            <div class="stat-label">Total Appointments</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter',['Pending'])">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($pendingCount)); ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter',['Ready'])">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-box-open"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($readyCount)); ?></div>
            <div class="stat-label">Ready for Pick-up</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter',['Released'])">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($releasedCount)); ?></div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>


<div class="card mb-6" id="tour-filters">
    <div class="card-body" style="padding:16px 20px">
        <div class="filter-bar">
            <div class="form-group flex-1">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                    <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                           placeholder="Resident name, appointment number…">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="statusFilter" multiple>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Document Type</label>
                <select id="docTypeFilter" multiple>
                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dt); ?>"><?php echo e($dt); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <button id="resetBtn" class="btn btn-secondary"><i class="fas fa-xmark"></i> Reset</button>
            </div>
        </div>
    </div>
</div>


<div class="card" id="tour-table">
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
#appointmentsTable_wrapper .dataTables_length,
#appointmentsTable_wrapper .dataTables_filter { display:none; }
#appointmentsTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
</style>
<script>
$(document).ready(function () {

    const s2Multi = { dropdownParent: $('body'), allowClear: false, width: '100%', closeOnSelect: false,
                      language: { noResults: () => 'No matches', searching: () => 'Searching…' } };

    $('#statusFilter').select2($.extend({}, s2Multi, { placeholder: 'All statuses…' }));
    $('#docTypeFilter').select2($.extend({}, s2Multi, { placeholder: 'All types…' }));

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

    /* ── Filters ──────────────────────────────────────────────────────── */
    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => table.ajax.reload(), 380); });
    $('#statusFilter, #docTypeFilter').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#statusFilter, #docTypeFilter').val(null).trigger('change');
        table.ajax.reload();
    });
});

window.aptQuickFilter = function (filterId, values) {
    $('#' + filterId).val(values).trigger('change');
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

/* ── Shepherd.js Tour ─────────────────────────────────────────────── */
(function () {
    const TOUR_KEY = 'bms_tour_appointments_v1_<?php echo e(auth()->id()); ?>';
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

    tour.addStep({ id: 'header', title: '<i class="fas fa-calendar-check" style="color:var(--gold)"></i>&nbsp; Document Appointments', text: 'Residents can request documents through the public portal. Their appointments appear here for staff to track, process, and release.', attachTo: { element: '#tour-header', on: 'bottom' }, buttons: [skipBtn, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'stats', title: '<i class="fas fa-chart-bar" style="color:var(--gold)"></i>&nbsp; Status Overview', text: 'Click any stat card to filter appointments by status. Keep an eye on <strong>Ready</strong> — those documents are waiting for the resident to pick up.', attachTo: { element: '#tour-stats', on: 'bottom' }, buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'filters', title: '<i class="fas fa-sliders" style="color:var(--gold)"></i>&nbsp; Filter Appointments', text: 'Filter by status and document type. The search box finds appointments by resident name or appointment number instantly — no page reload.', attachTo: { element: '#tour-filters', on: 'bottom' }, buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'table', title: '<i class="fas fa-rotate" style="color:var(--gold)"></i>&nbsp; Update Status Inline', text: 'Click the <strong>🔄 rotate icon</strong> to update an appointment\'s status and add notes for the resident — the portal shows these notes automatically so residents stay informed.', attachTo: { element: '#tour-table', on: 'top' }, buttons: [skipBtn, { text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: 'Next <i class="fas fa-arrow-right"></i>', action: tour.next, classes: 'shepherd-button-primary' }] });
    tour.addStep({ id: 'portal', title: '<i class="fas fa-external-link-alt" style="color:var(--gold)"></i>&nbsp; Resident Portal', text: 'Click <strong>View Portal</strong> to see what residents see when they submit requests or track their appointment. Useful for explaining the process to them.', attachTo: { element: '#tour-portal', on: 'left' }, buttons: [{ text: '<i class="fas fa-arrow-left"></i> Back', action: tour.back, classes: 'shepherd-button-secondary' }, { text: '<i class="fas fa-check"></i> Got it!', action: tour.complete, classes: 'shepherd-button-primary' }] });

    tour.on('complete', function () {
        localStorage.setItem(TOUR_KEY, new Date().toISOString());
        bmsToast('Tour complete! You\'re all set.', 'success');
    });
    setTimeout(function () { tour.start(); }, 900);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/appointments/index.blade.php ENDPATH**/ ?>