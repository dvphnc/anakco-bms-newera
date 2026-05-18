<?php $__env->startSection('title', 'Edit Document'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Document</h1>
        <p class="page-subtitle"><?php echo e($document->doc_number); ?> — <?php echo e($document->document_type); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('documents.show', $document)); ?>" class="btn btn-secondary"><i class="fas fa-eye"></i> View</a>
        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('documents.update', $document)); ?>" id="docEditForm">
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
                <label class="form-label">Resident <span style="color:var(--crimson)">*</span></label>
                <select name="resident_id" id="resident_id" class="select2-resident <?php $__errorArgs = ['resident_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required style="width:100%" data-placeholder="Search resident by name...">
                    <?php if($document->resident): ?>
                        <option value="<?php echo e($document->resident_id); ?>" selected>
                            <?php echo e($document->resident->last_name); ?>, <?php echo e($document->resident->first_name); ?> — <?php echo e($document->resident->address); ?>

                        </option>
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
                    <span class="help-icon" data-tippy-content="Each type generates a different certificate layout. Choose the type that matches what the resident is requesting.">?</span>
                </label>
                <select name="document_type" class="form-control <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('document_type', $document->document_type) === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
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

        
        <?php
            /* Determine initial representative state:
               A representative exists when requestor_name is set AND differs from the resident's own name.
               On validation failure, respect old('is_representative'). */
            $residentFullName  = $document->resident?->full_name ?? '';
            $savedReqName      = $document->requestor_name ?? '';
            $isRep             = old('is_representative') !== null
                                    ? (bool) old('is_representative')
                                    : ($savedReqName !== '' && $savedReqName !== $residentFullName);
        ?>

        <div class="form-section-title">Requestor / Processed By</div>

        
        <div class="form-group mb-3">
            <div style="display:flex;align-items:center;gap:12px;padding:13px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);cursor:pointer" onclick="document.getElementById('isRepCheck').click()">
                <input type="hidden" name="is_representative" value="0">
                <input type="checkbox" name="is_representative" id="isRepCheck" value="1"
                       style="width:17px;height:17px;accent-color:var(--navy);cursor:pointer;flex-shrink:0;pointer-events:none"
                       <?php echo e($isRep ? 'checked' : ''); ?>>
                <div style="pointer-events:none">
                    <div style="font-size:14px;font-weight:500;color:var(--text)">A <strong>representative</strong> is picking up / requesting this document</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:1px">Check this if someone other than the resident collected the document (e.g. child, spouse, attorney).</div>
                </div>
            </div>
        </div>

        
        <div id="selfPickupInfo" style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:rgba(200,134,26,0.08);border:1px solid rgba(200,134,26,0.25);border-radius:var(--radius);margin-bottom:20px<?php echo e($isRep ? ';display:none!important' : ''); ?>">
            <i class="fas fa-circle-check" style="color:var(--navy);font-size:14px"></i>
            <span style="font-size:13px;color:var(--navy)">Will be picked up by: <strong id="selfPickupName"><?php echo e($residentFullName ?: 'the resident'); ?></strong></span>
        </div>

        
        <div id="repPanel" style="<?php echo e($isRep ? '' : 'display:none;'); ?>margin-bottom:8px">
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
                           value="<?php echo e(old('requestor_name', $document->requestor_name)); ?>"
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
                            <option value="<?php echo e($rel); ?>"
                                <?php echo e(old('requestor_relationship', $document->requestor_relationship) === $rel ? 'selected' : ''); ?>>
                                <?php echo e($rel); ?>

                            </option>
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
                           value="<?php echo e(old('requestor_contact', $document->requestor_contact)); ?>"
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
                <label class="form-label">Purpose <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="purpose" class="form-control <?php $__errorArgs = ['purpose'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('purpose', $document->purpose)); ?>" required>
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
                    <span class="help-icon" data-tippy-content="'Pending' = not yet processed. 'Processing' = being prepared. 'Released' = given to the resident. 'Cancelled' = request withdrawn.">?</span>
                </label>
                <select name="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__currentLoopData = \App\Models\Document::$statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status', $document->status) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
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
                    <span class="help-icon" data-tippy-content="Enter 0 for indigent residents or free certificates.">?</span>
                </label>
                <input type="number" name="fee_paid" class="form-control <?php $__errorArgs = ['fee_paid'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('fee_paid', $document->fee_paid ?? 0)); ?>" min="0" step="0.01">
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
                    OR Number
                    <span class="help-icon" data-tippy-content="Official Receipt number from the cashier. Fill this in when the fee has been paid.">?</span>
                </label>
                <input type="text" name="or_number" class="form-control <?php $__errorArgs = ['or_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. OR-2026-00001" value="<?php echo e(old('or_number', $document->or_number)); ?>">
                <?php $__errorArgs = ['or_number'];
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
                    Date Released
                    <span class="help-icon" data-tippy-content="The date the document was physically given to the resident. Auto-fills to today when Status is set to Released.">?</span>
                </label>
                <input type="date" name="released_at" class="form-control <?php $__errorArgs = ['released_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('released_at', $document->released_at?->format('Y-m-d'))); ?>">
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
    <button type="submit" class="btn btn-primary" id="docEditSubmitBtn">
        <span id="docEditLabel"><i class="fas fa-floppy-disk"></i> Save Changes</span>
        <span id="docEditSpinner" style="display:none"><span class="doc-spin-icon"></span> Saving…</span>
    </button>
    <a href="<?php echo e(route('documents.show', $document)); ?>" class="btn btn-secondary">Cancel</a>
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
    var repCheck = document.getElementById('isRepCheck');
    var repPanel = document.getElementById('repPanel');
    var selfInfo = document.getElementById('selfPickupInfo');
    var selfName = document.getElementById('selfPickupName');
    var resSel   = document.getElementById('resident_id');

    function syncToggle() {
        var checked = repCheck.checked;
        repPanel.style.display = checked ? '' : 'none';
        selfInfo.style.display = checked ? 'none' : '';
    }

    function syncResidentLabel() {
        if (!resSel) return;
        var opt = resSel.options[resSel.selectedIndex];
        if (opt && opt.value) {
            selfName.textContent = opt.text.split('—')[0].trim();
        } else {
            selfName.textContent = 'the resident';
        }
    }

    repCheck.addEventListener('change', syncToggle);

    if (resSel) {
        $(resSel).on('select2:select',   syncResidentLabel);
        $(resSel).on('select2:unselect', function () { selfName.textContent = 'the resident'; });
        // Populate label from pre-selected option on page load
        syncResidentLabel();
    }

    $('#requestorRelationship').select2({
        placeholder: 'Select relationship',
        allowClear: true,
        minimumResultsForSearch: Infinity,
        width: '100%',
        dropdownParent: $('#repPanel')
    });

    document.getElementById('docEditForm').addEventListener('submit', function (e) {
        if (repCheck.checked) {
            var rName = document.querySelector('[name="requestor_name"]').value.trim();
            var rRel  = document.querySelector('[name="requestor_relationship"]').value;
            if (!rName || !rRel) {
                e.preventDefault();
                return;
            }
        }
        document.getElementById('docEditLabel').style.display   = 'none';
        document.getElementById('docEditSpinner').style.display = '';
        document.getElementById('docEditSubmitBtn').disabled = true;
    });

    // Init
    syncToggle();
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-edit.blade.php ENDPATH**/ ?>