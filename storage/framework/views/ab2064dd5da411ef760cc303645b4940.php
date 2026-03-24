<?php $__env->startSection('title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">System accounts and access control</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>
</div>


<div class="grid-3 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($users->total())); ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:var(--crimson)">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\User::where('role','Admin')->count())); ?></div>
            <div class="stat-label">Admins</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\User::whereNotNull('email_verified_at')->count())); ?></div>
            <div class="stat-label">Verified</div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-shield"></i> System Users</span>
        <span style="font-size:12px;color:var(--text-muted)"><?php echo e(number_format($users->total())); ?> users</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Created</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px"><?php echo e($user->name); ?></div>
                                <?php if($user->id === auth()->id()): ?>
                                    <span style="font-size:10px;color:var(--gold);font-weight:600">You</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="td-muted"><?php echo e($user->email); ?></td>
                    <td>
                        <?php
                            $roleCls = match($user->role) {
                                'Admin'     => 'badge-red',
                                'Secretary' => 'badge-yellow',
                                'Committee' => 'badge-navy',
                                default     => 'badge-gray'
                            };
                        ?>
                        <span class="badge <?php echo e($roleCls); ?>"><?php echo e($user->role ?? '—'); ?></span>
                    </td>
                    <td>
                        <?php if($user->email_verified_at): ?>
                            <span class="badge badge-green"><i class="fas fa-check" style="font-size:9px;margin-right:3px"></i> Verified</span>
                        <?php else: ?>
                            <span class="badge badge-gray">Unverified</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-muted"><?php echo e($user->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <?php if($user->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>"
                                  onsubmit="return confirm('Delete this user permanently?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No users found.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($users->hasPages()): ?>
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing <?php echo e($users->firstItem()); ?> to <?php echo e($users->lastItem()); ?> of <?php echo e(number_format($users->total())); ?>

        </span>
        <?php echo e($users->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/users/users-index.blade.php ENDPATH**/ ?>