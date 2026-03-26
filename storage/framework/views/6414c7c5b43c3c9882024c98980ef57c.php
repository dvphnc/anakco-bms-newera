<?php $__env->startSection('title', 'Edit Document'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Document</h1>
        <p class="page-subtitle"><?php echo e($document->doc_number); ?> — <?php echo e($document->document_type); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('documents.show', $document)); ?>" class="btn btn-secondary">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('documents.update', $document)); ?>">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-pen"></i> Document Details</span>
        <span class="td-mono"><?php echo e($document->doc_number); ?></span>
    </div>
    <div class="card-body">

        
        <div class="form-section-title">Resident & Type</div>
        <div class="form-grid-2 mb-6">

            
            <div class="form-group">
                <label class="form-label">Resident</label>
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        <?php echo e(strtoupper(substr($document->resident->first_name ?? 'R', 0, 1))); ?>

                    </div>
                    <div>
                        <div style="font-weight:600"><?php echo e($document->resident->full_name ?? '—'); ?></div>
                        <div class="td-muted"><?php echo e($document->resident->purok->name ?? ''); ?> — <?php echo e($document->resident->address ?? ''); ?></div>
                    </div>
                </div>
                
                <input type="hidden" name="resident_id" value="<?php echo e($document->resident_id); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Document Type <span style="color:var(--crimson)">*</span></label>
                <select name="document_type" class="form-control" required>
                    <?php $__currentLoopData = [
                        'Barangay Clearance',
                        'Certificate of Residency',
                        'Certificate of Indigency',
                        'Good Moral Character',
                        'Business Clearance',
                        'Certificate of Live Birth',
                        'Other',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('document_type', $document->document_type) === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        
        <div class="form-section-title">Request Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purpose <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="purpose" class="form-control"
                       value="<?php echo e(old('purpose', $document->purpose)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <?php $__currentLoopData = ['Pending','Processing','Released','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status', $document->status) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Fee (₱)</label>
                <input type="number" name="fee_paid" class="form-control"
                       value="<?php echo e(old('fee_paid', $document->fee_paid ?? 0)); ?>" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label class="form-label">OR Number</label>
                <input type="text" name="or_number" class="form-control"
                       placeholder="e.g. OR-2026-00001"
                       value="<?php echo e(old('or_number', $document->or_number)); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Date Released</label>
                <input type="date" name="released_at" class="form-control"
                       value="<?php echo e(old('released_at', $document->released_at?->format('Y-m-d'))); ?>">
            </div>
        </div>

        
        <div class="form-section-title">Issued By</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Issuing Officer</label>
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div>
                        <div style="font-weight:600"><?php echo e($document->issuedBy->name ?? auth()->user()->name); ?></div>
                        <div class="td-muted"><?php echo e($document->issuedBy->role ?? auth()->user()->role); ?> — Issued on <?php echo e($document->created_at->format('F d, Y')); ?></div>
                    </div>
                </div>
                <input type="hidden" name="issued_by" value="<?php echo e($document->issued_by ?? auth()->id()); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Document Number</label>
                <div style="padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:13.5px;font-family:monospace;color:var(--text-muted)">
                    <?php echo e($document->doc_number); ?>

                </div>
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="<?php echo e(route('documents.show', $document)); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-edit.blade.php ENDPATH**/ ?>