
<?php $__env->startSection('title', 'Edit Document'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Document</h1>
        <p class="page-subtitle"><?php echo e($document->doc_number); ?> — <?php echo e($document->document_type); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('documents.show', $document)); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
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
                <?php
                    $preResident = $document->resident ?? $suggestedResident ?? null;
                    $preResidentId = $document->resident ? $document->resident_id : ($suggestedResident?->id);
                ?>
                <select name="resident_id" id="resident_id"
                        class="select2-resident <?php $__errorArgs = ['resident_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required style="width:100%"
                        data-placeholder="Search resident by name..."
                        data-initial-id="<?php echo e($preResidentId); ?>"
                        data-initial-text="<?php echo e($preResident ? $preResident->last_name.', '.$preResident->first_name.' — '.($preResident->address ?? '') : ''); ?>">
                    <?php if($preResident && $preResidentId): ?>
                        <option value="<?php echo e($preResidentId); ?>" selected>
                            <?php echo e($preResident->last_name); ?>, <?php echo e($preResident->first_name); ?>

                            <?php if($preResident->address): ?> — <?php echo e($preResident->address); ?> <?php endif; ?>
                        </option>
                    <?php endif; ?>
                </select>
                <?php if($suggestedResident && !$document->resident): ?>
                    <div style="font-size:11.5px;color:#b45309;margin-top:5px;display:flex;align-items:center;gap:5px">
                        <i class="fas fa-circle-info"></i>
                        Auto-matched from portal name "<strong><?php echo e($document->resident_name_portal); ?></strong>" — please verify before saving.
                    </div>
                <?php endif; ?>
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
                <?php if($document->status === 'Released'): ?>
                    
                    <div style="padding:9px 12px;background:var(--surface2);border:1px solid var(--border);
                                border-radius:var(--radius-sm);font-size:13.5px;color:var(--text-muted);
                                display:flex;align-items:center;gap:8px">
                        <i class="fas fa-lock" style="font-size:11px;color:#9ca3af"></i>
                        Released — <span style="font-style:italic;font-size:12px">status is locked after release</span>
                    </div>
                    <input type="hidden" name="status" value="Released">
                <?php else: ?>
                    <select name="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__currentLoopData = \App\Models\Document::$statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($s !== 'Released' || $document->status === 'Released'): ?>
                                <option value="<?php echo e($s); ?>" <?php echo e(old('status', $document->status) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                <?php endif; ?>
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
                <div style="display:flex;align-items:center;gap:10px;
                            padding:10px 14px;min-height:58px;
                            background:var(--surface2);border:1px solid var(--border);
                            border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;
                                background:linear-gradient(135deg,var(--navy),var(--navy-mid));
                                display:flex;align-items:center;justify-content:center;
                                font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div>
                        <div style="font-weight:600"><?php echo e($document->issuedBy->name ?? auth()->user()->name); ?></div>
                        <div class="td-muted"><?php echo e($document->issuedBy->role ?? auth()->user()->role); ?> — Issued on <?php echo e($document->created_at->format('m/d/Y')); ?></div>
                    </div>
                </div>
                <input type="hidden" name="issued_by" value="<?php echo e($document->issued_by ?? auth()->id()); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Document Number</label>
                <div style="display:flex;align-items:center;gap:10px;
                            padding:10px 14px;min-height:58px;
                            background:var(--surface2);border:1px solid var(--border);
                            border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;
                                background:rgba(200,134,26,0.12);border:1.5px solid rgba(200,134,26,0.35);
                                display:flex;align-items:center;justify-content:center;
                                font-size:14px;flex-shrink:0;color:var(--gold)">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-family:monospace;color:var(--navy);font-size:14px;letter-spacing:.03em">
                            <?php echo e($document->doc_number); ?>

                        </div>
                        <div class="td-muted"><?php echo e($document->document_type); ?> · <?php echo e($document->created_at->format('m/d/Y')); ?></div>
                    </div>
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
    // ── Submit spinner ────────────────────────────────────────────
    document.getElementById('docEditForm').addEventListener('submit', function () {
        document.getElementById('docEditLabel').style.display   = 'none';
        document.getElementById('docEditSpinner').style.display = '';
        document.getElementById('docEditSubmitBtn').disabled = true;
    });

    // ── Resident Select2 pre-selection ────────────────────────────
    // Global init (app.blade.php) runs in $(document).ready.
    // We wait for it to finish, then call val().trigger('change')
    // so Select2 renders the pre-populated <option selected>.
    $(document).ready(function () {
        setTimeout(function () {
            var $sel = $('#resident_id');
            var preId = $sel.data('initial-id');
            if (preId && $sel.find('option[value="' + preId + '"]').length) {
                $sel.val(String(preId)).trigger('change');
            }
        }, 80);
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-edit.blade.php ENDPATH**/ ?>