<?php $__env->startSection('title', 'Add Official'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Add Official</h1>
        <p class="page-subtitle">Register a barangay official or staff member</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('officials.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('officials.store')); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Official Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Personal Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('full_name')); ?>" placeholder="e.g. Juan dela Cruz" required>
                <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('contact_number')); ?>" placeholder="09XX XXX XXXX">
                <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Position & Committee</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Position <span style="color:var(--crimson)">*</span></label>
                <select name="position" id="s2Position" class="form-control <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Position</option>
                    <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p); ?>" <?php echo e(old('position') === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Committee</label>
                <select name="committee" id="s2Committee" class="form-control <?php $__errorArgs = ['committee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">None / N/A</option>
                    <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c); ?>" <?php echo e(old('committee') === $c ? 'selected' : ''); ?>><?php echo e($c); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['committee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Term & Status</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Term Start <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_start" class="form-control <?php $__errorArgs = ['term_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('term_start')); ?>" required>
                <?php $__errorArgs = ['term_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Term End <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_end" class="form-control <?php $__errorArgs = ['term_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('term_end')); ?>" required>
                <?php $__errorArgs = ['term_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group" style="justify-content:flex-end;padding-bottom:4px">
                <label class="form-label">&nbsp;</label>
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', '1') ? 'checked' : ''); ?>>
                    Currently Active
                </label>
            </div>
        </div>

        <div class="form-section-title">Photo</div>
        <div class="form-group">
            <label class="form-label">Official Photo</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
            <span style="font-size:13px;color:var(--text-subtle);margin-top:3px"><i class="fas fa-circle-info" style="color:var(--navy);opacity:0.5"></i> JPG, PNG. Max 2MB.</span>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Official
    </button>
    <a href="<?php echo e(route('officials.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(function () {
    const s2 = { dropdownParent: $('body'), width: '100%', allowClear: false };
    $('#s2Position').select2($.extend({}, s2, { placeholder: 'Select Position' }));
    $('#s2Committee').select2($.extend({}, s2, { placeholder: 'None / N/A', allowClear: true }));
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/officials/officials-create.blade.php ENDPATH**/ ?>