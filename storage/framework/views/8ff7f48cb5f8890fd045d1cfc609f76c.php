<?php $__env->startSection('title', 'Officials & Staff'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Officials & Staff</h1>
        <p class="page-subtitle">Barangay New Era elected officials and personnel</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('officials.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Official
        </a>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($officials->count())); ?></div>
            <div class="stat-label">Total Officials</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($officials->where('is_active', true)->count())); ?></div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($officials->whereIn('position', ['Punong Barangay','Barangay Captain'])->count())); ?></div>
            <div class="stat-label">Punong Barangay</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(date('Y')); ?></div>
            <div class="stat-label">Current Term Year</div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Officials List</span>
        <span style="font-size:13px;color:var(--text-muted)"><?php echo e($officials->count()); ?> officials</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Committee</th>
                    <th>Contact</th>
                    <th>Term</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $officials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $official): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="width:44px">
                        <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                            <?php if($official->photo_path): ?>
                                <img src="<?php echo e(asset('storage/'.$official->photo_path)); ?>" alt="" style="width:100%;height:100%;object-fit:cover">
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($official->full_name ?? 'O', 0, 1))); ?>

                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px"><?php echo e($official->full_name); ?></div>
                    </td>
                    <td>
                        <span class="badge badge-navy"><?php echo e($official->position); ?></span>
                    </td>
                    <td class="td-muted"><?php echo e($official->committee ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($official->contact_number ?? '—'); ?></td>
                    <td class="td-muted">
                        <?php echo e($official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—'); ?>

                        <?php if($official->term_end): ?>
                            – <?php echo e(\Carbon\Carbon::parse($official->term_end)->format('Y')); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?php echo e($official->is_active ? 'badge-green' : 'badge-gray'); ?>">
                            <?php echo e($official->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="<?php echo e(route('officials.show', $official)); ?>" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('officials.edit', $official)); ?>" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('officials.destroy', $official)); ?>"
                                  data-confirm="Delete <?php echo e($official->full_name); ?>? This cannot be undone."
                                  data-confirm-title="Delete Official"
                                  data-confirm-ok="Delete">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-user-tie"></i>
                            <p>No officials found.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/officials/officials-index.blade.php ENDPATH**/ ?>