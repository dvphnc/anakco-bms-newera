<?php $__env->startSection('title', 'Residents'); ?>
<?php $__env->startSection('page-title', 'Residents'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all registered residents of Barangay New Era'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Residents</h1>
        <p class="page-subtitle">All registered residents of Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('export.pdf', 'residents')); ?>" class="btn btn-secondary" title="Export PDF">
    <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
</a>
<a href="<?php echo e(route('export.excel', 'residents')); ?>" class="btn btn-secondary" title="Export Excel">
    <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
</a>
        <a href="<?php echo e(route('residents.create')); ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Register Resident
        </a>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::count())); ?></div>
            <div class="stat-label">Total Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::where('residency_status','Active')->count())); ?></div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-check-to-slot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::where('residency_status','Active')->where('is_voter',true)->count())); ?></div>
            <div class="stat-label">Registered Voters</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(122,21,21,0.08);color:#9B1C1C">
            <i class="fas fa-person-cane"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::where('residency_status','Active')->where('is_senior',true)->count())); ?></div>
            <div class="stat-label">Senior Citizens</div>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <div class="filter-bar" style="flex-wrap:wrap;gap:12px;align-items:flex-end">
            <div class="form-group flex-1" style="min-width:180px">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                    <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                           placeholder="Name, address, contact...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Purok</label>
                <select id="purokFilter" class="form-control">
                    <option value="">All Puroks</option>
                    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purok->id); ?>"><?php echo e($purok->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Gender</label>
                <select id="genderFilter" class="form-control">
                    <option value="">All</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="statusFilter" class="form-control">
                    <option value="">All</option>
                    <option value="Active">Active</option>
                    <option value="Deceased">Deceased</option>
                    <option value="Transferred">Transferred</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Age</label>
                <input type="number" id="ageExact" class="form-control"
                       placeholder="e.g. 25" min="0" max="120" style="width:90px">
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <button id="resetBtn" class="btn btn-secondary">
                    <i class="fas fa-xmark"></i> Reset
                </button>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title">
            <i class="fas fa-users"></i> Resident List
        </span>
    </div>
    <div class="table-responsive">
        <table id="residentsTable" class="w-full" style="width:100%">
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Purok</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Classifications</th>
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
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<style>
#residentsTable_wrapper .dataTables_length,
#residentsTable_wrapper .dataTables_filter { display: none; }
#residentsTable_wrapper .dataTables_info { font-size:12px; color:var(--text-muted); padding: 12px 20px; }
#residentsTable_wrapper .dataTables_paginate { padding: 12px 20px; }
#residentsTable_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    border: 1px solid var(--border) !important;
    background: white !important;
    color: var(--text) !important;
    margin: 0 2px;
}
#residentsTable_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--navy) !important;
    color: white !important;
    border-color: var(--navy) !important;
}
#residentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background: var(--navy-pale) !important;
    color: var(--navy) !important;
}
#residentsTable_wrapper .dataTables_processing {
    background: white;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 20px;
    font-size: 13px;
    color: var(--navy);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
</style>

<script>
$(document).ready(function () {
    var table = $('#residentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?php echo e(route('residents.index')); ?>',
            data: function (d) {
                d.gender    = $('#genderFilter').val();
                d.status    = $('#statusFilter').val();
                d.purok_id  = $('#purokFilter').val();
                d.age_exact = $('#ageExact').val();
                d.search    = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'name_col',   name: 'first_name',       orderable: true },
            { data: 'purok_col',  name: 'purok_id',         orderable: false },
            { data: 'gender_col', name: 'gender',           orderable: true },
            { data: 'age_col',    name: 'birthdate',        orderable: true },
            { data: 'civil_col',  name: 'civil_status',     orderable: false },
            { data: 'tags_col',   name: 'is_voter',         orderable: false },
            { data: 'status_col', name: 'residency_status', orderable: true },
            { data: 'actions',    name: 'actions',          orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading residents...',
            emptyTable: '<div class="empty-state"><i class="fas fa-users"></i><p>No residents found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No residents match your filters.</p></div>',
        }
    });

    let searchTimer;

    $('#searchInput').on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 400);
    });

    $('#genderFilter, #statusFilter, #purokFilter').on('change', function () {
        table.ajax.reload();
    });

    $('#ageExact').on('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => table.ajax.reload(), 600);
    });

    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#genderFilter, #statusFilter, #purokFilter').val('');
        $('#ageExact').val('');
        table.ajax.reload();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/residents/residents-index.blade.php ENDPATH**/ ?>