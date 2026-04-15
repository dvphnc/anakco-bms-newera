<?php $__env->startSection('title', 'Documents'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Document Issuance</h1>
        <p class="page-subtitle">Barangay certificates and clearances</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('export.pdf', 'documents')); ?>" class="btn btn-secondary" title="Export PDF">
    <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
</a>
<a href="<?php echo e(route('export.excel', 'documents')); ?>" class="btn btn-secondary" title="Export Excel">
    <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
</a>
        <a href="<?php echo e(route('documents.create')); ?>" class="btn btn-primary">
            <i class="fas fa-file-circle-plus"></i> Issue Document
        </a>
    </div>
</div>

<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-file-lines"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Document::count())); ?></div>
            <div class="stat-label">Total Documents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Document::where('status','Pending')->count())); ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-spinner"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Document::where('status','Processing')->count())); ?></div>
            <div class="stat-label">Processing</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Document::where('status','Released')->count())); ?></div>
            <div class="stat-label">Released</div>
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
                    <input type="text" id="searchInput" class="form-control" style="padding-left:32px" placeholder="Document no., resident name...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Type</label>
                <select id="typeFilter" class="form-control">
                    <option value="">All Types</option>
                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>"><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="statusFilter" class="form-control">
                    <option value="">All</option>
                    <?php $__currentLoopData = ['Pending','Processing','Released','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
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

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-lines"></i> Document Records</span>
    </div>
    <div class="table-responsive">
        <table id="documentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Doc No.</th>
                    <th>Resident</th>
                    <th>Document Type</th>
                    <th>Purpose</th>
                    <th>Fee</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
#documentsTable_wrapper .dataTables_length,
#documentsTable_wrapper .dataTables_filter { display:none; }
#documentsTable_wrapper .dataTables_info { font-size:12px;color:var(--text-muted);padding:12px 20px; }
#documentsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#documentsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#documentsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#documentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }
</style>
<script>
$(document).ready(function () {
    var table = $('#documentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('documents.index')); ?>',
            data: function (d) {
                d.document_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',   name: 'doc_number' },
            { data: 'resident_col', name: 'resident_id', orderable: false },
            { data: 'type_col',     name: 'document_type' },
            { data: 'purpose_col',  name: 'purpose', orderable: false },
            { data: 'fee_col',      name: 'fee_paid', orderable: false },
            { data: 'date_col',     name: 'created_at' },
            { data: 'status_col',   name: 'status' },
            { data: 'actions',      name: 'actions', orderable: false, searchable: false },
        ],
        order: [[5, 'desc']],
        pageLength: 15,
        language: { processing: '<i class="fas fa-spinner fa-spin"></i> Loading...', emptyTable: '<div class="empty-state"><i class="fas fa-file-lines"></i><p>No documents found.</p></div>' }
    });
    let searchTimer;
    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 400);
    });
    $('#typeFilter, #statusFilter').on('change', function () { table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val('');
        table.ajax.reload();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-index.blade.php ENDPATH**/ ?>