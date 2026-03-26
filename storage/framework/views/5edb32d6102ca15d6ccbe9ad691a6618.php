<?php $__env->startSection('title', 'Issue Document'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Issue Document</h1>
        <p class="page-subtitle">Create a new barangay certificate or clearance</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('documents.store')); ?>">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-circle-plus"></i> Document Details</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Resident & Type</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Resident <span style="color:var(--crimson)">*</span></label>
                <select name="resident_id" class="form-control" required>
                    <option value="">Select Resident</option>
                    <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($resident->id); ?>"
                            <?php echo e(old('resident_id', request('resident_id')) == $resident->id ? 'selected' : ''); ?>>
                            <?php echo e($resident->full_name); ?> — <?php echo e($resident->purok->name ?? ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Document Type <span style="color:var(--crimson)">*</span></label>
                <select name="document_type" class="form-control" required id="docType">
                    <option value="">Select Type</option>
                    <?php $__currentLoopData = [
                        'Barangay Clearance',
                        'Certificate of Residency',
                        'Certificate of Indigency',
                        'Business Clearance',
                        'Certificate of Good Moral',
                        'Barangay ID',
                        'First Time Job Seeker',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('document_type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-section-title">Request Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purpose <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="purpose" class="form-control"
                       value="<?php echo e(old('purpose')); ?>"
                       placeholder="e.g. Employment, Loan, School requirement" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <?php $__currentLoopData = ['Pending','Processing','Released','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status','Pending') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Fee (₱)</label>
                <input type="number" name="fee" class="form-control"
                       value="<?php echo e(old('fee', 0)); ?>" min="0" step="0.01"
                       placeholder="0.00">
            </div>
            <div class="form-group">
                <label class="form-label">OR Number (if paid)</label>
                <input type="text" name="or_number" class="form-control"
                       value="<?php echo e(old('or_number')); ?>" placeholder="Official Receipt No.">
            </div>
        </div>

        <div class="form-section-title">Additional Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Issued By</label>
                <input type="text" name="issued_by" class="form-control"
                       value="<?php echo e(old('issued_by', auth()->user()->name)); ?>"
                       placeholder="Name of issuing officer">
            </div>
            <div class="form-group">
                <label class="form-label">Date Released</label>
                <input type="date" name="date_released" class="form-control"
                       value="<?php echo e(old('date_released')); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"
                      placeholder="Optional remarks..."><?php echo e(old('remarks')); ?></textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-file-circle-plus"></i> Issue Document
    </button>
    <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-create.blade.php ENDPATH**/ ?>