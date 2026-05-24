<?php if(session('success')): ?>
<div class="alert alert-success mb-4">
    <i class="fas fa-check-circle alert-icon"></i>
    <div class="alert-message"><?php echo e(session('success')); ?></div>
    <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if(session('warning')): ?>
<div class="alert alert-warning mb-4">
    <i class="fas fa-triangle-exclamation alert-icon"></i>
    <div class="alert-message"><?php echo e(session('warning')); ?></div>
    <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-error mb-4">
    <i class="fas fa-exclamation-circle alert-icon"></i>
    <div class="alert-message"><?php echo e(session('error')); ?></div>
    <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>

<?php if(isset($errors) && $errors->any()): ?>
<div class="alert alert-error mb-4">
    <i class="fas fa-exclamation-circle alert-icon"></i>
    <div class="alert-message">
        <strong>Please fix the following errors:</strong>
        <ul class="alert-list">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php endif; ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\partials\_alerts.blade.php ENDPATH**/ ?>