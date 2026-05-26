<?php $__env->startSection('title', 'Edit Business Permit'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Business Permit</h1>
        <p class="page-subtitle"><?php echo e($business->permit_number); ?> — <?php echo e($business->business_name); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('businesses.show', $business)); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
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
                <input type="text" name="business_name" class="form-control <?php $__errorArgs = ['business_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('business_name', $business->business_name)); ?>" required>
                <?php $__errorArgs = ['business_name'];
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
                    Business Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the category that best describes what this business does. Choose 'Other' if none applies — you can add more detail in Remarks.">?</span>
                </label>
                <select name="business_type" class="form-control <?php $__errorArgs = ['business_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__currentLoopData = $businessTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('business_type', $business->business_type) === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['business_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Business Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_address" id="business_address" class="form-control <?php $__errorArgs = ['business_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('business_address', $business->business_address)); ?>" required>
                <?php $__errorArgs = ['business_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-section-title">Owner Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Owner Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="owner_name" id="owner_name" class="form-control <?php $__errorArgs = ['owner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('owner_name', $business->owner_name)); ?>" required>
                <?php $__errorArgs = ['owner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Owner Contact</label>
                <input type="text" name="owner_contact" class="form-control <?php $__errorArgs = ['owner_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('owner_contact', $business->owner_contact)); ?>">
                <?php $__errorArgs = ['owner_contact'];
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
                    <span class="help-icon" data-tippy-content="Optional. Search for the owner in the resident registry. Selecting a resident will automatically fill in their name. Useful for tracking which residents own businesses.">?</span>
                </label>
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
        <?php $currentStatus = old('status', $business->getRawOriginal('status')); ?>
        <div class="form-grid-3 mb-6">

            
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Pending' = awaiting review. 'For Review' = under staff assessment. 'Active' = permit issued. 'Expired' = past expiry. 'Suspended' = temporarily halted. 'Cancelled' = permit revoked.">?</span>
                </label>
                <select name="status" id="statusSelect" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__currentLoopData = ['Pending','For Review','Active','Expired','Suspended','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e($currentStatus === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['status'];
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
                    Fee (₱)
                    <span class="help-icon" data-tippy-content="Permit fee collected. OR Number will be auto-generated when fee is greater than 0.">?</span>
                </label>
                <input type="number" id="feeInput" name="fee_paid"
                       class="form-control <?php $__errorArgs = ['fee_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('fee_paid', $business->fee_paid ?? 0)); ?>"
                       min="0" step="0.01">
                <?php $__errorArgs = ['fee_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;justify-content:space-between">
                    <span>
                        OR Number
                        <span class="help-icon" data-tippy-content="Official Receipt number. Leave blank to auto-generate when fee is greater than 0.">?</span>
                    </span>
                    <button type="button" id="orAutoBtn"
                            style="display:none;font-size:11.5px;font-weight:600;color:var(--navy);
                                   background:rgba(13,33,68,0.07);border:1px solid rgba(13,33,68,0.18);
                                   border-radius:4px;padding:2px 8px;cursor:pointer;transition:.15s"
                            onclick="autoFillOrNumber()">
                        <i class="fas fa-wand-magic-sparkles" style="font-size:10px"></i> Auto-fill
                    </button>
                </label>
                <div style="position:relative">
                    <input type="text" id="orInput" name="or_number"
                           class="form-control <?php $__errorArgs = ['or_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="e.g. OR-2026-00001"
                           value="<?php echo e(old('or_number', $business->or_number)); ?>">
                    <span id="orAutoHint"
                          style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                 font-size:11px;color:#9ca3af;pointer-events:none">
                        auto-generate on save
                    </span>
                </div>
                <?php $__errorArgs = ['or_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        
        <div id="permitDatesGroup" class="form-grid-2 mb-6"
             style="<?php echo e($currentStatus === 'Active' ? '' : 'display:none'); ?>">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar-check" style="color:#16a34a;font-size:11px;margin-right:4px"></i>
                    Permit Date <span style="color:var(--crimson)">*</span>
                </label>
                <input type="date" name="permit_date"
                       class="form-control <?php $__errorArgs = ['permit_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('permit_date', $business->permit_date?->format('Y-m-d'))); ?>"
                       style="border-color:#86efac">
                <?php $__errorArgs = ['permit_date'];
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
                    <i class="fas fa-calendar-xmark" style="color:#dc2626;font-size:11px;margin-right:4px"></i>
                    Expiry Date <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Most permits are issued for 1 year. The system alerts you 30 days before expiry.">?</span>
                </label>
                <input type="date" name="expiry_date"
                       class="form-control <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('expiry_date', $business->expiry_date?->format('Y-m-d'))); ?>"
                       style="border-color:#86efac">
                <?php $__errorArgs = ['expiry_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"><?php echo e(old('remarks', $business->remarks)); ?></textarea>
        </div>

        
        <?php if($business->source === 'portal'): ?>
        <div id="portalMessageSection">
            <div class="form-section-title">
                <i class="fas fa-comment-dots" style="font-size:12px;margin-right:5px;color:var(--gold)"></i>
                Message to Applicant
            </div>
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:var(--radius-sm);
                        padding:12px 14px;margin-bottom:12px;font-size:12.5px;color:#92400e;
                        display:flex;align-items:flex-start;gap:8px">
                <i class="fas fa-circle-info" style="flex-shrink:0;margin-top:1px"></i>
                <span>This is a <strong>portal application</strong>. Any message you enter below will be sent to the applicant by email and appear in their portal tracker when the status changes.</span>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label">
                    Message <span style="font-weight:400;color:var(--text-subtle);font-size:12px">— optional, only sent when status changes</span>
                </label>
                <textarea name="status_message" id="statusMessageArea" class="form-control" rows="3"
                          placeholder="e.g. Your permit application is now under review. We will contact you within 3–5 business days."><?php echo e(old('status_message')); ?></textarea>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Changes</button>
    <a href="<?php echo e(route('businesses.show', $business)); ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<style>
#orAutoBtn:hover { background: rgba(13,33,68,.14) !important; }
</style>
<script>
/* ── OR auto-generate (same pattern as Document edit) ─────── */
(function () {
    var _generateOrUrl = '<?php echo e(route('documents.generateOrNumber')); ?>';
    var feeInput = document.getElementById('feeInput');
    var orInput  = document.getElementById('orInput');
    var orBtn    = document.getElementById('orAutoBtn');
    var orHint   = document.getElementById('orAutoHint');

    function syncOrUi() {
        var fee   = parseFloat(feeInput ? feeInput.value : 0) || 0;
        var orVal = orInput ? orInput.value.trim() : '';
        if (fee > 0 && !orVal) {
            if (orBtn)  orBtn.style.display  = '';
            if (orHint) orHint.style.display = '';
        } else {
            if (orBtn)  orBtn.style.display  = 'none';
            if (orHint) orHint.style.display = 'none';
        }
    }
    if (feeInput) feeInput.addEventListener('input', syncOrUi);
    if (orInput)  orInput.addEventListener('input', syncOrUi);
    syncOrUi();

    window.autoFillOrNumber = function () {
        if (orBtn) { orBtn.disabled = true; orBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i>'; }
        axios.get(_generateOrUrl)
            .then(function (res) {
                if (res.data.or_number) {
                    orInput.value = res.data.or_number;
                    syncOrUi();
                }
            })
            .catch(function () { alert('Could not generate OR number. Please try again.'); })
            .finally(function () {
                if (orBtn) { orBtn.disabled = false; orBtn.innerHTML = '<i class="fas fa-wand-magic-sparkles" style="font-size:10px"></i> Auto-fill'; }
            });
    };
}());

/* ── Status-dependent permit dates + portal message ──────── */
(function () {
    var statusSel   = document.getElementById('statusSelect');
    var datesGroup  = document.getElementById('permitDatesGroup');
    var msgArea     = document.getElementById('statusMessageArea');
    var origStatus  = '<?php echo e($business->getRawOriginal('status')); ?>';

    function syncStatusUi() {
        if (!statusSel) return;
        var s = statusSel.value;
        if (datesGroup) datesGroup.style.display = (s === 'Active') ? '' : 'none';
    }

    if (statusSel) {
        statusSel.addEventListener('change', function () {
            syncStatusUi();
            if (msgArea && statusSel.value !== origStatus) {
                msgArea.closest('.form-group').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                setTimeout(function () { msgArea.focus(); }, 250);
            }
        });
    }
    syncStatusUi();
}());

/* ── Owner resident auto-fill ─────────────────────────────── */
$('#owner_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#owner_name').val(fullName);
});
$('#owner_resident_id').on('select2:clear', function() {
    $('#owner_name').val('');
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/businesses/businesses-edit.blade.php ENDPATH**/ ?>