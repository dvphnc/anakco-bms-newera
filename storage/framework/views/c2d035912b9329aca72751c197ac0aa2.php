<?php $__env->startSection('title', 'Edit User'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle"><?php echo e($user->name); ?> — <?php echo e($user->role); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('users.update', $user)); ?>">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-pen"></i> Account Details</span>
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>
            <span style="font-size:13px;color:var(--text-muted)"><?php echo e($user->email); ?></span>
        </div>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="<?php echo e(old('name', $user->name)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address <span style="color:var(--crimson)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?php echo e(old('email', $user->email)); ?>" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span style="font-size:11px;color:var(--crimson)"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Access Role</div>
        <div class="form-group mb-6">
            <label class="form-label">Role <span style="color:var(--crimson)">*</span></label>
            <select name="role" class="form-control" required style="max-width:320px"
                    <?php echo e($user->id === auth()->id() ? 'disabled' : ''); ?>>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r); ?>" <?php echo e(old('role', $user->role) === $r ? 'selected' : ''); ?>><?php echo e($r); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php if($user->id === auth()->id()): ?>
                <input type="hidden" name="role" value="<?php echo e($user->role); ?>">
                <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block">
                    <i class="fas fa-lock" style="font-size:10px"></i> You cannot change your own role.
                </span>
            <?php endif; ?>
        </div>

        <div class="form-section-title">Change Password <span style="font-size:11px;color:var(--text-subtle);text-transform:none;font-weight:400;letter-spacing:0">(leave blank to keep current)</span></div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat new password" autocomplete="new-password">
            </div>
        </div>

        
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Verified</div>
                <div style="font-size:13px;color:var(--text);margin-top:2px">
                    <?php if($user->email_verified_at): ?>
                        <span class="badge badge-green">Yes</span>
                    <?php else: ?>
                        <span class="badge badge-gray">No</span>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Member Since</div>
                <div style="font-size:13px;color:var(--text);margin-top:2px"><?php echo e($user->created_at->format('F d, Y')); ?></div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Last Updated</div>
                <div style="font-size:13px;color:var(--text);margin-top:2px"><?php echo e($user->updated_at->format('F d, Y')); ?></div>
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="<?php echo e(route('users.index')); ?>" class="btn btn-secondary">Cancel</a>
    <?php if($user->id !== auth()->id()): ?>
    <form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" style="margin-left:auto"
          onsubmit="return confirm('Permanently delete <?php echo e($user->name); ?>?')">
        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Delete User
        </button>
    </form>
    <?php endif; ?>
</div>

</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/users/users-edit.blade.php ENDPATH**/ ?>