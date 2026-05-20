<?php $__env->startSection('title', 'Issue Document'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Issue Document</h1>
        <p class="page-subtitle">Create a new barangay certificate or clearance</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('documents.store')); ?>" id="docCreateForm">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-circle-plus"></i> Document Details</span>
    </div>
    <div class="card-body">

        
        <div class="form-section-title">Beneficiary & Type</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Beneficiary (Resident) <span style="color:var(--crimson)">*</span></label>
                <select name="resident_id" id="resident_id" class="select2-resident <?php $__errorArgs = ['resident_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required style="width:100%" data-placeholder="Type name to search...">
                    <?php if(old('resident_id') || request('resident_id')): ?>
                        <?php $sel = \App\Models\Resident::find(old('resident_id', request('resident_id'))); ?>
                        <?php if($sel): ?><option value="<?php echo e($sel->id); ?>" selected><?php echo e($sel->last_name); ?>, <?php echo e($sel->first_name); ?> — <?php echo e($sel->address); ?></option><?php endif; ?>
                    <?php else: ?>
                        <option value=""></option>
                    <?php endif; ?>
                </select>
                <?php $__errorArgs = ['resident_id'];
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
                    Document Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Determines which certificate template is used. 'Barangay Clearance' is the most common — issued for employment, loans, and general use.">?</span>
                </label>
                <select name="document_type" class="form-control <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Type</option>
                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('document_type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        
        <div class="form-section-title">Requestor / Processed By</div>

        
        <div class="form-group mb-3">
            <div style="display:flex;align-items:center;gap:12px;padding:13px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);cursor:pointer" onclick="document.getElementById('isRepCheck').click()">
                <input type="hidden" name="is_representative" value="0">
                <input type="checkbox" name="is_representative" id="isRepCheck" value="1"
                       style="width:17px;height:17px;accent-color:var(--navy);cursor:pointer;flex-shrink:0;pointer-events:none"
                       <?php echo e(old('is_representative') ? 'checked' : ''); ?>>
                <div style="pointer-events:none">
                    <div style="font-size:14px;font-weight:500;color:var(--text)">A <strong>representative</strong> is picking up / requesting this document</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:1px">Check this if someone other than the resident will collect the document (e.g. child, spouse, attorney).</div>
                </div>
            </div>
        </div>

        
        <div id="selfPickupInfo" style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:rgba(200,134,26,0.08);border:1px solid rgba(200,134,26,0.25);border-radius:var(--radius);margin-bottom:20px">
            <i class="fas fa-circle-check" style="color:var(--navy);font-size:14px"></i>
            <span style="font-size:13px;color:var(--navy)">Will be picked up by: <strong id="selfPickupName">the resident</strong></span>
        </div>

        
        <div id="repPanel" style="display:none;margin-bottom:8px">
            <div class="form-grid-2 mb-2" style="margin-top:16px">
                <div class="form-group">
                    <label class="form-label">Representative Name <span style="color:var(--crimson)">*</span></label>
                    <input type="text" name="requestor_name" id="requestorName"
                           class="form-control <?php $__errorArgs = ['requestor_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('requestor_name')); ?>"
                           placeholder="Full name of the person picking up">
                    <?php $__errorArgs = ['requestor_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Relationship to Resident <span style="color:var(--crimson)">*</span></label>
                    <select name="requestor_relationship" id="requestorRelationship"
                            class="form-control select2-rel <?php $__errorArgs = ['requestor_relationship'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="">Select Relationship</option>
                        <?php $__currentLoopData = $relationships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($rel); ?>" <?php echo e(old('requestor_relationship') === $rel ? 'selected' : ''); ?>><?php echo e($rel); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['requestor_relationship'];
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
                        Representative Contact
                        <span class="help-icon" data-tippy-content="Optional — phone or email to notify the representative when the document is ready.">?</span>
                    </label>
                    <input type="text" name="requestor_contact"
                           class="form-control <?php $__errorArgs = ['requestor_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('requestor_contact')); ?>"
                           placeholder="Phone or email (optional)">
                    <?php $__errorArgs = ['requestor_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        
        <div class="form-section-title">Request Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Purpose <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Why does the resident need this document? Examples: 'Employment at SM Fairview', 'Bank loan application', 'School enrollment requirement'. This appears on the printed certificate.">?</span>
                </label>
                <input type="text" name="purpose" class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('purpose')); ?>" placeholder="e.g. Employment, Loan, School requirement" required>
                <?php $__errorArgs = ['purpose'];
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
                    Status
                    <span class="help-icon" data-tippy-content="'Pending' = received, not yet processed. 'Processing' = being prepared. 'Released' = given to the resident. 'Cancelled' = request withdrawn.">?</span>
                </label>
                <select name="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__currentLoopData = ['Pending','Processing','Released','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status','Pending') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
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
                    <span class="help-icon" data-tippy-content="Leave at ₱0 if the document is free of charge. Enter the amount collected if a fee was paid (e.g., ₱50 for Business Clearance).">?</span>
                </label>
                <input type="number" name="fee_paid" class="form-control <?php $__errorArgs = ['fee_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('fee_paid', 0)); ?>" min="0" step="0.01">
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
                <label class="form-label">
                    OR Number (if paid)
                    <span class="help-icon" data-tippy-content="Official Receipt number from the Barangay Treasurer. Only required when a fee was collected. Leave blank for free documents.">?</span>
                </label>
                <input type="text" name="or_number" class="form-control <?php $__errorArgs = ['or_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('or_number')); ?>" placeholder="Official Receipt No.">
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

        
        <div class="form-section-title">Additional Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Date Released
                    <span class="help-icon" data-tippy-content="Leave blank if the document hasn't been given to the resident yet. This field auto-fills to today's date when you change Status to 'Released'.">?</span>
                </label>
                <input type="date" name="released_at" class="form-control <?php $__errorArgs = ['released_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('released_at')); ?>">
                <?php $__errorArgs = ['released_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary" id="docSubmitBtn">
        <span id="docSubmitLabel"><i class="fas fa-file-circle-plus"></i> Issue Document</span>
        <span id="docSubmitSpinner" style="display:none"><span class="doc-spin-icon"></span> Saving…</span>
    </button>
    <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<style>
@keyframes doc-spin { to { transform: rotate(360deg); } }
.doc-spin-icon {
    display: inline-block;
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: doc-spin 0.7s linear infinite;
    vertical-align: middle;
    margin-right: 4px;
}
</style>
<script>
(function () {
    var repCheck  = document.getElementById('isRepCheck');
    var repPanel  = document.getElementById('repPanel');
    var selfInfo  = document.getElementById('selfPickupInfo');
    var selfName  = document.getElementById('selfPickupName');
    var resSel    = document.getElementById('resident_id');

    /* ── Toggle rep panel ── */
    function syncToggle() {
        var checked = repCheck.checked;
        repPanel.style.display = checked ? '' : 'none';
        selfInfo.style.display = checked ? 'none' : '';
    }

    /* ── Keep "Will be picked up by" label in sync with resident picker ── */
    function syncResidentLabel() {
        if (!resSel) return;
        var opt = resSel.options[resSel.selectedIndex];
        if (opt && opt.value) {
            // option text format: "Last, First — address"
            selfName.textContent = opt.text.split('—')[0].trim();
        } else {
            selfName.textContent = 'the resident';
        }
    }

    repCheck.addEventListener('change', syncToggle);

    if (resSel) {
        $(resSel).on('select2:select',   syncResidentLabel);
        $(resSel).on('select2:unselect', function () { selfName.textContent = 'the resident'; });
    }

    /* ── Select2 for relationship dropdown ── */
    $('#requestorRelationship').select2({
        placeholder: 'Select relationship',
        allowClear: true,
        minimumResultsForSearch: Infinity,
        width: '100%'
    });

    /* ── Gold spinner on submit ── */
    document.getElementById('docCreateForm').addEventListener('submit', function (e) {
        // Client-side guard: rep panel visible but name empty
        if (repCheck.checked) {
            var rName = document.querySelector('[name="requestor_name"]').value.trim();
            var rRel  = document.querySelector('[name="requestor_relationship"]').value;
            if (!rName || !rRel) {
                e.preventDefault();
                return;
            }
        }
        document.getElementById('docSubmitLabel').style.display  = 'none';
        document.getElementById('docSubmitSpinner').style.display = '';
        document.getElementById('docSubmitBtn').disabled = true;
    });

    /* ── Init ── */
    syncToggle();
    syncResidentLabel();
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-create.blade.php ENDPATH**/ ?>