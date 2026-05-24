<?php $__env->startSection('title', 'Add Household'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Add Household</h1>
        <p class="page-subtitle">Register a new household in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('households.store')); ?>">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Household Head <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="household_head" id="household_head" class="form-control" value="<?php echo e(old('household_head')); ?>" placeholder="Full name of household head" required>
                <?php $__errorArgs = ['household_head'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Link to Resident
                    <span class="help-icon" data-tippy-content="Optional. Search for the household head in the resident registry. Selecting a resident will auto-fill their name and address. Useful for tracking which resident leads each household.">?</span>
                </label>
                <select name="head_resident_id" id="head_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson)">*</span></label>
                <select name="purok_id" id="s2Purok" class="form-control" required>
                    <option value="">Select Purok</option>
                    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purok->id); ?>" <?php echo e(old('purok_id') == $purok->id ? 'selected' : ''); ?>><?php echo e($purok->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['purok_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Family Size <span style="color:var(--crimson)">*</span></label>
                <input type="number" name="family_size" class="form-control" value="<?php echo e(old('family_size', 1)); ?>" min="1" max="50" required>
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Address <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="address" id="hh_address" class="form-control" value="<?php echo e(old('address')); ?>" placeholder="Full address" required>
            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-group">
            <label class="form-check">
                <input type="checkbox" name="is_voter_household" value="1" <?php echo e(old('is_voter_household') ? 'checked' : ''); ?>>
                <span>This household has registered voters</span>
            </label>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-house-circle-plus"></i> Add Household</button>
    <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
/* Purok — searchable Select2 */
$('#s2Purok').select2({
    dropdownParent: $('body'),
    width: '100%',
    placeholder: 'Select Purok',
    allowClear: true
});

$('#head_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const address  = parts[1] ? parts[1].trim() : '';
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#household_head').val(fullName);
    if (address) $('#hh_address').val(address);
});
$('#head_resident_id').on('select2:clear', function() {
    $('#household_head').val('');
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\households\households-create.blade.php ENDPATH**/ ?>