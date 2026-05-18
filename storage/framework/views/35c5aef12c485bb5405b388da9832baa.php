<?php $__env->startSection('title', 'Portal Blotter Requests'); ?>
<?php $__env->startSection('page-title', 'Portal Blotter Requests'); ?>
<?php $__env->startSection('page-subtitle', 'Blotter reports submitted via the Resident Portal'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Portal Blotter Requests</h1>
        <p class="page-subtitle">Blotter reports submitted via the Resident Portal</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-secondary" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Portal
        </a>
    </div>
</div>

<?php
    $counts = [];
    foreach($statuses as $s) {
        $counts[$s] = \App\Models\BlotterRequest::where('status', $s)->count();
    }
    $total   = \App\Models\BlotterRequest::count();
    $pending = $counts['Pending'] ?? 0;
    $resolved= $counts['Resolved'] ?? 0;
?>
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-gavel"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($total)); ?></div>
            <div class="stat-label">Total Reports</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.12);color:var(--gold)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($pending)); ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(37,99,235,0.08);color:#2563eb"><i class="fas fa-people-arrows"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($counts['For Mediation'] ?? 0)); ?></div>
            <div class="stat-label">For Mediation</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,163,74,0.08);color:#16a34a"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($resolved)); ?></div>
            <div class="stat-label">Resolved</div>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('pbl')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('pbl')">
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
                               placeholder="Complainant name, request number…">
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
        <span class="card-title"><i class="fas fa-gavel"></i> Blotter Reports</span>
    </div>
    <div class="table-responsive">
        <table id="pblTable" style="width:100%">
            <thead>
                <tr>
                    <th>Request No.</th>
                    <th>Complainant</th>
                    <th>Incident</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>


<div id="pblStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closePblModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:440px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Blotter Status</span>
            </div>
            <button onclick="closePblModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Request: <strong id="pblModalNum" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="pblModalStatus" class="form-control">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional — visible to resident if email provided)</span></label>
                <textarea id="pblModalNotes" class="form-control" rows="3"
                          placeholder="e.g., Scheduled for mediation on…"></textarea>
            </div>
            <div id="pblStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closePblModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="pblStatusSaveBtn" onclick="savePblStatus()" class="btn btn-primary">
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
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }
#pblTable_wrapper .dataTables_length, #pblTable_wrapper .dataTables_filter { display:none; }
#pblTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#pblTable_wrapper .dataTables_paginate { padding:12px 20px; }
#pblTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#pblTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#pblTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
#filterPanel .select2-container { width: 100% !important; }
#filterPanel .select2-container--default .select2-selection--single {
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); min-height: 38px; padding: 0 32px 0 10px;
    display: flex; align-items: center;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--text); font-size: 13.5px; padding: 0; }
#filterPanel .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; top: 0; right: 8px; }
</style>
<script>
$(document).ready(function () {
    var $fp = $('#filterPanel');
    var _fpW = $fp.parent().width();
    $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });
    $('#statusFilter').select2({ dropdownParent: $('body'), allowClear: true, width: '100%', placeholder: 'All statuses…', minimumResultsForSearch: 0 });
    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });

    var table = $('#pblTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('portal-blotter.index')); ?>',
            data: function (d) {
                d.status = $('#statusFilter').val();
                d.search = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',     name: 'request_number' },
            { data: 'complainant_col',name: 'complainant_name', orderable: false },
            { data: 'incident_col',   name: 'incident_type',   orderable: false },
            { data: 'submitted_col',  name: 'created_at' },
            { data: 'status_col',     name: 'status' },
            { data: 'actions',        name: 'actions', orderable: false, searchable: false },
        ],
        order: [[3, 'desc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-gavel"></i><p>No blotter reports found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No reports match your filters.</p></div>',
        }
    });

    /* Axios DELETE */
    $('#pblTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault(); e.stopImmediatePropagation();
        const btn = $(this), form = btn.closest('form'), url = form.attr('action');
        bmsConfirm({ title: form.data('confirm-title'), message: form.data('confirm'), ok: form.data('confirm-ok') }, function () {
            const icon = btn.find('i'), orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
            btn.prop('disabled', true);
            axios.delete(url)
                .then(function (res) {
                    table.row(form.closest('tr')).remove().draw(false);
                    bmsToast(res.data.message || 'Deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete request.', 'error');
                });
        });
    });

    /* Open modal */
    $('#pblTable').on('click', '.pbl-status-btn', function () {
        _pblId = $(this).data('id');
        document.getElementById('pblModalNum').textContent    = $(this).data('num');
        document.getElementById('pblModalStatus').value       = $(this).data('status');
        document.getElementById('pblModalNotes').value        = $(this).data('notes') || '';
        document.getElementById('pblStatusError').style.display = 'none';
        document.getElementById('pblStatusModal').style.display = 'flex';
    });

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => table.ajax.reload(), 380); });
    $('#statusFilter').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () { $('#searchInput').val(''); $('#statusFilter').val(null).trigger('change'); table.ajax.reload(); });

    const lsOpen = localStorage.getItem('fp_pbl') === '1';
    if (lsOpen) { $fp.show(); document.getElementById('filterToggleText').textContent = 'Hide Filters'; }
});

window.toggleFilters = function (key) {
    const panel  = document.getElementById('filterPanel');
    const isOpen = panel.style.display !== 'none';
    panel.style.display = isOpen ? 'none' : 'block';
    document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
    localStorage.setItem('fp_' + key, isOpen ? '0' : '1');
};

var _pblId = null;
function closePblModal() { document.getElementById('pblStatusModal').style.display = 'none'; _pblId = null; }
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePblModal(); });

function savePblStatus() {
    if (!_pblId) return;
    const btn    = document.getElementById('pblStatusSaveBtn');
    const errDiv = document.getElementById('pblStatusError');
    const status = document.getElementById('pblModalStatus').value;
    const notes  = document.getElementById('pblModalNotes').value;
    errDiv.style.display = 'none';
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';
    axios.patch('/portal-blotter/' + _pblId + '/status', { status: status, notes: notes })
        .then(function (res) {
            closePblModal();
            bmsToast(res.data.message || 'Status updated.', 'success');
            $('#pblTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            const data = err.response?.data;
            const msg  = data?.errors ? Object.values(data.errors).flat().join(' ') : (data?.message || 'Failed to update status.');
            errDiv.textContent = msg; errDiv.style.display = 'block';
        })
        .finally(function () { btn.disabled = false; btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status'; });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal-blotter/index.blade.php ENDPATH**/ ?>