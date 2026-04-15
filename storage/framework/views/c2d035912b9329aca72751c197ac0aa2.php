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

<form method="POST" action="<?php echo e(route('users.update', $user)); ?>" id="edit-user-form">
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
                <?php $__errorArgs = ['name'];
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
            <?php $__errorArgs = ['role'];
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

        <div class="form-section-title">Change Password <span style="font-size:11px;color:var(--text-subtle);text-transform:none;font-weight:400;letter-spacing:0">(leave blank to keep current)</span></div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" autocomplete="new-password">
                <?php $__errorArgs = ['password'];
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
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat new password" autocomplete="new-password">
            </div>
        </div>

        
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Verified</div>
                <div style="font-size:13px;color:var(--text);margin-top:6px;display:flex;align-items:center;gap:8px">
                    <?php if($user->email_verified_at): ?>
                        <span class="badge badge-green"><i class="fas fa-check" style="margin-right:4px"></i>Verified</span>
                        <span style="font-size:11px;color:var(--text-subtle)"><?php echo e($user->email_verified_at->format('M d, Y')); ?></span>
                        <?php if(auth()->user()->role === 'Admin'): ?>
                        <button type="button" class="btn btn-secondary btn-sm"
                                onclick="if(confirm('Remove verification?')) document.getElementById('unverify-form').submit()"
                                style="font-size:11px;padding:3px 10px">
                            <i class="fas fa-times"></i> Unverify
                        </button>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-gray"><i class="fas fa-clock" style="margin-right:4px"></i>Unverified</span>
                        <?php if(auth()->user()->role === 'Admin'): ?>
                        <button type="button" class="btn btn-primary btn-sm"
                                onclick="document.getElementById('verify-form').submit()"
                                style="font-size:11px;padding:3px 10px">
                            <i class="fas fa-check"></i> Mark as Verified
                        </button>
                        <?php endif; ?>
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
    
    <button type="button" class="btn btn-danger" style="margin-left:auto"
            onclick="document.getElementById('delete-user-form').submit()"
            onmousedown="return confirm('Permanently delete <?php echo e($user->name); ?>? This cannot be undone.')">
        <i class="fas fa-trash"></i> Delete User
    </button>
    <?php endif; ?>
</div>

</form>


<?php if($user->id !== auth()->id()): ?>
<form method="POST" action="<?php echo e(route('users.destroy', $user)); ?>" id="delete-user-form">
    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
</form>
<?php endif; ?>


<form method="POST" action="<?php echo e(route('users.verify', $user)); ?>" id="verify-form">
    <?php echo csrf_field(); ?>
</form>
<form method="POST" action="<?php echo e(route('users.unverify', $user)); ?>" id="unverify-form">
    <?php echo csrf_field(); ?>
</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/users/users-edit.blade.php ENDPATH**/ ?>