<?php $__env->startSection('title', 'Issue Business Permit'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Issue Business Permit</h1>
        <p class="page-subtitle">Register a new business in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('businesses.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('businesses.store')); ?>">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Business Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Business Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_name" class="form-control"
                       value="<?php echo e(old('business_name')); ?>"
                       placeholder="e.g. Juan's Sari-Sari Store" required>
            </div>
            <div class="form-group">
                <label class="form-label">Business Type <span style="color:var(--crimson)">*</span></label>
                <select name="business_type" class="form-control" required>
                    <option value="">Select Type</option>
                    <?php $__currentLoopData = $businessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('business_type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Business Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_address" class="form-control"
                       value="<?php echo e(old('business_address')); ?>"
                       placeholder="Full address of business location" required>
            </div>
        </div>

        <div class="form-section-title">Owner Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Owner Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="owner_name" class="form-control"
                       value="<?php echo e(old('owner_name')); ?>" placeholder="Full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Owner Contact</label>
                <input type="text" name="owner_contact" class="form-control"
                       value="<?php echo e(old('owner_contact')); ?>" placeholder="09XX XXX XXXX">
            </div>
            <div class="form-group">
                <label class="form-label">Owner (Resident)</label>
                <select name="owner_resident_id" class="form-control">
                    <option value="">Select if registered resident</option>
                    <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($r->id); ?>" <?php echo e(old('owner_resident_id') == $r->id ? 'selected' : ''); ?>>
                            <?php echo e($r->full_name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-section-title">Permit Details</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Permit Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="permit_date" class="form-control"
                       value="<?php echo e(old('permit_date', date('Y-m-d'))); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="expiry_date" class="form-control"
                       value="<?php echo e(old('expiry_date', date('Y-m-d', strtotime('+1 year')))); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <?php $__currentLoopData = ['Active','Expired','Suspended','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status','Active') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"
                      placeholder="Optional notes..."><?php echo e(old('remarks')); ?></textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Issue Permit
    </button>
    <a href="<?php echo e(route('businesses.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/businesses/businesses-create.blade.php ENDPATH**/ ?>