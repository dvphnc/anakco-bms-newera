<?php $__env->startSection('title', 'Request a Document'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-hd {
        margin-bottom: 1.75rem;
    }
    .page-hd h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .25rem;
    }
    .page-hd p {
        font-size: .82rem;
        color: #6b7280;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
    }

    .step-indicator {
        display: flex;
        gap: .5rem;
        margin-bottom: 1.75rem;
    }
    .step-dot {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .78rem;
        color: #9ca3af;
        font-weight: 500;
    }
    .step-dot .dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 700;
    }
    .step-dot.active { color: var(--navy); }
    .step-dot.active .dot { background: var(--navy); color: #fff; }
    .step-divider { flex: 1; height: 2px; background: #e5e7eb; align-self: center; }

    .section-label {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #9ca3af;
        margin: 1.5rem 0 .75rem;
    }
    .section-label:first-child { margin-top: 0; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f0f0f0;
    }
    .disclaimer {
        font-size: .75rem;
        color: #9ca3af;
        text-align: center;
        margin-top: 1rem;
        line-height: 1.6;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-card">
    <div class="step-indicator">
        <div class="step-dot active"><div class="dot">1</div> Fill Form</div>
        <div class="step-divider"></div>
        <div class="step-dot"><div class="dot">2</div> Confirmation</div>
        <div class="step-divider"></div>
        <div class="step-dot"><div class="dot">3</div> Claim Document</div>
    </div>

    <div class="page-hd">
        <h2><i class="fas fa-file-plus" style="color:var(--gold)"></i>&nbsp; Request a Barangay Document</h2>
        <p>Fill in your details below. All fields marked with <span style="color:var(--crimson)">*</span> are required.</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="p-alert p-alert-error">
            <i class="fas fa-exclamation-circle"></i>
            Please correct the errors below before submitting.
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('portal.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="section-label">Personal Information</div>
        <div class="form-row">
            <div class="form-group">
                <label>Full Name <span class="req">*</span></label>
                <input type="text" name="resident_name" class="form-control"
                       value="<?php echo e(old('resident_name')); ?>" placeholder="Last, First Middle" required>
                <?php $__errorArgs = ['resident_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label>Contact Number <span class="req">*</span></label>
                <input type="text" name="contact_number" class="form-control"
                       value="<?php echo e(old('contact_number')); ?>" placeholder="09XXXXXXXXX" required>
                <?php $__errorArgs = ['contact_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-group">
            <label>Email Address <span style="color:#9ca3af;font-weight:400">(optional — for status notifications)</span></label>
            <input type="email" name="email" class="form-control"
                   value="<?php echo e(old('email')); ?>" placeholder="you@example.com">
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="section-label">Document Request</div>
        <div class="form-row">
            <div class="form-group">
                <label>Document Type <span class="req">*</span></label>
                <select name="document_type" class="form-control" required>
                    <option value="">— Select document —</option>
                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dt); ?>" <?php echo e(old('document_type') === $dt ? 'selected' : ''); ?>><?php echo e($dt); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label>Preferred Pick-up Date <span class="req">*</span></label>
                <input type="date" name="preferred_date" class="form-control"
                       value="<?php echo e(old('preferred_date')); ?>"
                       min="<?php echo e(now()->addDay()->format('Y-m-d')); ?>" required>
                <?php $__errorArgs = ['preferred_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-group">
            <label>Purpose / Reason for Request</label>
            <textarea name="purpose" class="form-control" rows="2"
                      placeholder="e.g., For employment purposes, school enrollment, etc."><?php echo e(old('purpose')); ?></textarea>
            <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="form-actions">
            <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Submit Request
            </button>
        </div>

        <p class="disclaimer">
            By submitting this form, you confirm that all information provided is accurate.<br>
            Processing time is 1–3 business days. You will need to present a valid ID when claiming.
        </p>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/request.blade.php ENDPATH**/ ?>