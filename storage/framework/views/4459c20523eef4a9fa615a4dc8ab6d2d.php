<?php $__env->startSection('title', 'Edit Household'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Household</h1>
        <p class="page-subtitle"><?php echo e($household->household_number); ?> — <?php echo e($household->household_head); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('households.show', $household)); ?>" class="btn btn-secondary">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('households.update', $household)); ?>">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household Information</span>
        <span class="td-mono"><?php echo e($household->household_number); ?></span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Household Head</label>
                <input type="text" name="household_head" class="form-control"
                       value="<?php echo e(old('household_head', $household->household_head)); ?>"
                       placeholder="Full name of household head">
            </div>
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson)">*</span></label>
                <select name="purok_id" class="form-control" required>
                    <option value="">Select Purok</option>
                    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($purok->id); ?>" <?php echo e(old('purok_id', $household->purok_id) == $purok->id ? 'selected' : ''); ?>>
                            <?php echo e($purok->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Address <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="address" class="form-control"
                   value="<?php echo e(old('address', $household->address)); ?>" required>
        </div>

        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Family Size <span style="color:var(--crimson)">*</span></label>
                <input type="number" name="family_size" class="form-control"
                       value="<?php echo e(old('family_size', $household->family_size)); ?>"
                       min="1" max="50" required
                       placeholder="Number of family members">
            </div>
            <div class="form-group">
                <label class="form-label">Voter Household</label>
                <div style="display:flex;align-items:center;height:40px">
                    <label class="form-check">
                        <input type="checkbox" name="is_voter_household" value="1"
                               <?php echo e(old('is_voter_household', $household->is_voter_household) ? 'checked' : ''); ?>>
                        <span>This household has registered voters</span>
                    </label>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="<?php echo e(route('households.show', $household)); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/households/households-edit.blade.php ENDPATH**/ ?>