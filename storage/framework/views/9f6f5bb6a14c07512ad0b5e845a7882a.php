<?php $__env->startSection('title', 'Edit Official'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Official</h1>
        <p class="page-subtitle"><?php echo e($official->full_name); ?> — <?php echo e($official->position); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('officials.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('officials.update', $official)); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Official Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Personal Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="full_name" class="form-control"
                       value="<?php echo e(old('full_name', $official->full_name)); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="<?php echo e(old('contact_number', $official->contact_number)); ?>">
            </div>
        </div>

        <div class="form-section-title">Position & Committee</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Position <span style="color:var(--crimson)">*</span></label>
                <select name="position" class="form-control" required>
                    <?php $__currentLoopData = $positions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p); ?>" <?php echo e(old('position', $official->position) === $p ? 'selected' : ''); ?>><?php echo e($p); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Committee</label>
                <select name="committee" class="form-control">
                    <option value="">None / N/A</option>
                    <?php $__currentLoopData = $committees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c); ?>" <?php echo e(old('committee', $official->committee) === $c ? 'selected' : ''); ?>><?php echo e($c); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <div class="form-section-title">Term & Status</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Term Start <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_start" class="form-control"
                       value="<?php echo e(old('term_start', $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y-m-d') : '')); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Term End <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_end" class="form-control"
                       value="<?php echo e(old('term_end', $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y-m-d') : '')); ?>" required>
            </div>
            <div class="form-group" style="justify-content:flex-end;padding-bottom:4px">
                <label class="form-label">&nbsp;</label>
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1"
                           <?php echo e(old('is_active', $official->is_active) ? 'checked' : ''); ?>>
                    Currently Active
                </label>
            </div>
        </div>

        <div class="form-section-title">Photo</div>
        <div class="form-group">
            <?php if($official->photo_path): ?>
            <div style="margin-bottom:10px">
                <img src="<?php echo e(asset('storage/'.$official->photo_path)); ?>" alt="Current Photo"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px">Current photo</div>
            </div>
            <?php endif; ?>
            <label class="form-label">Upload New Photo</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="<?php echo e(route('officials.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/officials/officials-edit.blade.php ENDPATH**/ ?>