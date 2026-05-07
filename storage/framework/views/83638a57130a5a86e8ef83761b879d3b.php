<?php $__env->startSection('title', 'Edit Business Permit'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Business Permit</h1>
        <p class="page-subtitle"><?php echo e($business->permit_number); ?> — <?php echo e($business->business_name); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('businesses.show', $business)); ?>" class="btn btn-secondary"><i class="fas fa-eye"></i> View</a>
        <a href="<?php echo e(route('businesses.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('businesses.update', $business)); ?>">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Information</span>
        <span class="td-mono"><?php echo e($business->permit_number); ?></span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Business Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Business Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_name" class="form-control" value="<?php echo e(old('business_name', $business->business_name)); ?>" required>
                <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:11px;color:var(--crimson);margin-top:4px;display:block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Business Type <span style="color:var(--crimson)">*</span></label>
                <select name="business_type" class="form-control" required>
                    <?php $__currentLoopData = $businessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('business_type', $business->business_type) === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Business Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_address" id="business_address" class="form-control" value="<?php echo e(old('business_address', $business->business_address)); ?>" required>
            </div>
        </div>

        <div class="form-section-title">Owner Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Owner Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="owner_name" id="owner_name" class="form-control" value="<?php echo e(old('owner_name', $business->owner_name)); ?>" required>
                <?php $__errorArgs = ['owner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span style="font-size:11px;color:var(--crimson);margin-top:4px;display:block"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Owner Contact</label>
                <input type="text" name="owner_contact" class="form-control" value="<?php echo e(old('owner_contact', $business->owner_contact)); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Link to Resident <span style="font-size:10px;color:var(--text-subtle)">(optional)</span></label>
                <select name="owner_resident_id" id="owner_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    <?php if($business->owner_resident_id): ?>
                        <?php $or = \App\Models\Resident::find(old('owner_resident_id', $business->owner_resident_id)); ?>
                        <?php if($or): ?><option value="<?php echo e($or->id); ?>" selected><?php echo e($or->last_name); ?>, <?php echo e($or->first_name); ?> — <?php echo e($or->address); ?></option><?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="form-section-title">Permit Details</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Permit Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="permit_date" class="form-control" value="<?php echo e(old('permit_date', $business->permit_date ? \Carbon\Carbon::parse($business->permit_date)->format('Y-m-d') : '')); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="expiry_date" class="form-control" value="<?php echo e(old('expiry_date', $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date)->format('Y-m-d') : '')); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <?php $__currentLoopData = ['Active','Expired','Suspended','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status', $business->status) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"><?php echo e(old('remarks', $business->remarks)); ?></textarea>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Changes</button>
    <a href="<?php echo e(route('businesses.show', $business)); ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
$('#owner_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#owner_name').val(fullName);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/businesses/businesses-edit.blade.php ENDPATH**/ ?>