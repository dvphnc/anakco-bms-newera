<?php $__env->startSection('title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Add User</h1>
        <p class="page-subtitle">Create a new system account</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('users.store')); ?>">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-plus"></i> Account Details</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="<?php echo e(old('name')); ?>" placeholder="Full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address <span style="color:var(--crimson)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo e(old('email')); ?>" placeholder="user@bms.gov.ph" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Access Role</div>
        <div class="form-group mb-6">
            <label class="form-label">Role <span style="color:var(--crimson)">*</span></label>
            <select name="role" class="form-control" required style="max-width:320px">
                <option value="">Select Role</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r); ?>" <?php echo e(old('role') === $r ? 'selected' : ''); ?>><?php echo e($r); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:16px 20px;margin-bottom:24px">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.09em;color:var(--navy);margin-bottom:12px">
                <i class="fas fa-info-circle" style="color:var(--gold);margin-right:6px"></i> Role Permissions
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                <?php
                    $roleGuide = [
                        ['role'=>'Admin',     'desc'=>'Full system access. Can manage all records, users, and settings.', 'color'=>'var(--crimson)'],
                        ['role'=>'Secretary', 'desc'=>'Can manage residents, documents, blotter, and businesses. Cannot manage users.', 'color'=>'var(--gold)'],
                        ['role'=>'Committee', 'desc'=>'Access limited to committee pages and related records only.', 'color'=>'var(--navy)'],
                    ];
                ?>
                <?php $__currentLoopData = $roleGuide; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px">
                    <div style="font-size:13px;font-weight:700;color:<?php echo e($rg['color']); ?>;margin-bottom:5px"><?php echo e($rg['role']); ?></div>
                    <div style="font-size:13px;color:var(--text-muted);line-height:1.5"><?php echo e($rg['desc']); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="form-section-title">Password</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Password <span style="color:var(--crimson)">*</span></label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password <span style="color:var(--crimson)">*</span></label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat password" required autocomplete="new-password">
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Create Account
    </button>
    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\users\users-create.blade.php ENDPATH**/ ?>