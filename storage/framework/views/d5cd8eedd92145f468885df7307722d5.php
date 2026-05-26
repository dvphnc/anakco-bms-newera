<?php $__env->startSection('title', 'Edit Blotter Case'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Blotter Case</h1>
        <p class="page-subtitle"><?php echo e($blotter->case_number); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('blotter.show', $blotter)); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('blotter.update', $blotter)); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-exclamation-triangle"></i> Incident Details</span>
        <span class="td-mono"><?php echo e($blotter->case_number); ?></span>
    </div>
    <div class="card-body">
        <div class="form-section-title">Incident Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Incident Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the category that best describes the complaint or incident. Choose 'Other' if none of the options fit, and describe fully in the Incident Details field below.">?</span>
                </label>
                <select name="incident_type" class="form-control <?php $__errorArgs = ['incident_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <?php $__currentLoopData = ['Noise Complaint','Physical Assault','Verbal Abuse','Theft','Trespassing','Domestic Dispute','Property Damage','Threat','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('incident_type', $blotter->incident_type) === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['incident_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="incident_date" class="form-control <?php $__errorArgs = ['incident_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('incident_date', $blotter->incident_date?->format('Y-m-d'))); ?>" required>
                <?php $__errorArgs = ['incident_date'];
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
                    <span class="help-icon" data-tippy-content="'Active' = newly filed. 'Under Investigation' = Barangay is looking into it. 'Mediated' = parties have met. 'Settled' = issue resolved. 'Referred' = escalated to police or court.">?</span>
                </label>
                <select name="status" id="statusSelect" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__currentLoopData = ['Pending','Active','Under Investigation','Mediated','Settled','Referred to Higher Authority']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status', $blotter->status) === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
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
        </div>

        
        <?php $closingStatuses = ['Settled','Referred to Higher Authority']; ?>
        <div id="settledDateGroup" class="form-grid-2 mb-6"
             style="<?php echo e(old('status', $blotter->status) === 'Settled' ? '' : 'display:none'); ?>">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar-check" style="color:#16a34a;font-size:11px;margin-right:4px"></i>
                    Date Settled
                    <span class="help-icon" data-tippy-content="The date the case was officially resolved or closed. Defaults to today if left blank.">?</span>
                </label>
                <input type="date" name="settled_at" id="settledAtInput"
                       class="form-control <?php $__errorArgs = ['settled_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('settled_at', $blotter->settled_at?->format('Y-m-d'))); ?>"
                       style="border-color:#86efac">
                <?php $__errorArgs = ['settled_at'];
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
            <label class="form-label">
                Incident Location <span style="color:var(--crimson)">*</span>
                <span class="help-icon" data-tippy-content="Be as specific as possible. Include the Purok number and a landmark. Example: 'Purok 3, in front of Aling Nena's store near the basketball court.'">?</span>
            </label>
            <input type="text" name="incident_location" class="form-control <?php $__errorArgs = ['incident_location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('incident_location', $blotter->incident_location)); ?>" required>
            <?php $__errorArgs = ['incident_location'];
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
                Incident Details <span style="color:var(--crimson)">*</span>
                <span class="help-icon" data-tippy-content="Write a factual, detailed account of what happened. Include the date and time, what was said or done, and who was present. This becomes the official record.">?</span>
            </label>
            <textarea name="incident_details" class="form-control <?php $__errorArgs = ['incident_details'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" required><?php echo e(old('incident_details', $blotter->incident_details)); ?></textarea>
            <?php $__errorArgs = ['incident_details'];
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

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-users"></i> Parties Involved</span>
    </div>
    <div class="card-body">
        <div class="form-section-title">
            Complainant
            <span class="help-icon" data-tippy-content="The person filing the complaint. Fill in their name, address, and contact number. You may optionally link to a registered resident to auto-fill their details.">?</span>
        </div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Complainant Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="complainant_name" id="complainant_name" class="form-control <?php $__errorArgs = ['complainant_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('complainant_name', $blotter->complainant_name)); ?>" required>
                <?php $__errorArgs = ['complainant_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident <span style="font-size:12px;color:var(--text-subtle)">(optional — auto-fills name)</span></label>
                <select name="complainant_resident_id" id="complainant_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    <?php if($blotter->complainant_resident_id): ?>
                        <?php $cr = \App\Models\Resident::find(old('complainant_resident_id', $blotter->complainant_resident_id)); ?>
                        <?php if($cr): ?><option value="<?php echo e($cr->id); ?>" selected><?php echo e($cr->last_name); ?>, <?php echo e($cr->first_name); ?> — <?php echo e($cr->address); ?></option><?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Address</label>
                <input type="text" name="complainant_address" id="complainant_address" class="form-control" value="<?php echo e(old('complainant_address', $blotter->complainant_address)); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Contact</label>
                <input type="text" name="complainant_contact" class="form-control" value="<?php echo e(old('complainant_contact', $blotter->complainant_contact)); ?>">
            </div>
        </div>

        <div class="form-section-title">
            Respondent
            <span class="help-icon" data-tippy-content="The person being complained against. Fill in their name, address, and contact number if known. You may also link them to a registered resident to auto-fill their details.">?</span>
        </div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Respondent Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="respondent_name" id="respondent_name" class="form-control <?php $__errorArgs = ['respondent_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('respondent_name', $blotter->respondent_name)); ?>" required>
                <?php $__errorArgs = ['respondent_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> <?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident <span style="font-size:12px;color:var(--text-subtle)">(optional — auto-fills name)</span></label>
                <select name="respondent_resident_id" id="respondent_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    <?php if(isset($blotter->respondent_resident_id) && $blotter->respondent_resident_id): ?>
                        <?php $rr = \App\Models\Resident::find(old('respondent_resident_id', $blotter->respondent_resident_id)); ?>
                        <?php if($rr): ?><option value="<?php echo e($rr->id); ?>" selected><?php echo e($rr->last_name); ?>, <?php echo e($rr->first_name); ?> — <?php echo e($rr->address); ?></option><?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Address</label>
                <input type="text" name="respondent_address" id="respondent_address" class="form-control" value="<?php echo e(old('respondent_address', $blotter->respondent_address)); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Contact</label>
                <input type="text" name="respondent_contact" class="form-control" value="<?php echo e(old('respondent_contact', $blotter->respondent_contact)); ?>">
            </div>
        </div>

        <div class="form-section-title">Responding Officer</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Responding Officer
                    <span class="help-icon" data-tippy-content="The Barangay Tanod, duty officer, or barangay official who responded to or handled this incident. Leave blank if not applicable.">?</span>
                </label>
                <input type="text" name="responding_officer" class="form-control" value="<?php echo e(old('responding_officer', $blotter->responding_officer)); ?>" placeholder="e.g. Tanod Juan Dela Cruz">
            </div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clipboard-list"></i> Resolution & Attachments</span>
    </div>
    <div class="card-body">

        
        <div id="resolutionGroup" class="form-group mb-6">
            <label class="form-label" id="resolutionLabel">
                <i class="fas fa-gavel" id="resolutionIcon"
                   style="<?php echo e(in_array(old('status', $blotter->status), ['Settled','Referred to Higher Authority']) ? 'color:#16a34a;' : 'display:none;'); ?>font-size:11px;margin-right:4px"></i>
                Resolution Notes
                <span class="help-icon" data-tippy-content="Describe what action was taken: who mediated, what the parties agreed to, or why the case was referred. Leave blank if the case is newly filed.">?</span>
            </label>
            <textarea name="resolution_notes" id="resolutionNotes"
                      class="form-control"
                      rows="<?php echo e(in_array(old('status', $blotter->status), ['Settled','Referred to Higher Authority']) ? 4 : 3); ?>"
                      style="<?php echo e(in_array(old('status', $blotter->status), ['Settled','Referred to Higher Authority']) ? 'border-color:#86efac;' : ''); ?>"
                      placeholder="<?php echo e(in_array(old('status', $blotter->status), ['Settled','Referred to Higher Authority']) ? 'Describe the resolution — what was agreed, who mediated, or why it was referred.' : 'Optional: describe any action taken so far.'); ?>"><?php echo e(old('resolution_notes', $blotter->resolution_notes)); ?></textarea>
            <div id="resolutionHint"
                 style="<?php echo e(in_array(old('status', $blotter->status), ['Settled','Referred to Higher Authority']) ? '' : 'display:none;'); ?>font-size:11.5px;color:#16a34a;margin-top:5px;display:flex;align-items:center;gap:4px">
                <i class="fas fa-circle-info" style="font-size:10px"></i>
                Required for this status — describe the outcome clearly for the record.
            </div>
        </div>

        
        <?php if($blotter->source === 'portal'): ?>
        <div id="portalMessageSection" style="margin-bottom:24px">
            <div class="form-section-title">
                <i class="fas fa-comment-dots" style="font-size:12px;margin-right:5px;color:var(--gold)"></i>
                Message to Complainant
            </div>
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:var(--radius-sm);
                        padding:12px 14px;margin-bottom:12px;font-size:12.5px;color:#92400e;
                        display:flex;align-items:flex-start;gap:8px">
                <i class="fas fa-circle-info" style="flex-shrink:0;margin-top:1px"></i>
                <span>This is a <strong>portal report</strong>. Any message you enter below will be sent to the complainant by email and appear in their portal tracker when the status changes.</span>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label">
                    Message <span style="font-weight:400;color:var(--text-subtle);font-size:12px">— optional, only sent when status changes</span>
                </label>
                <textarea name="status_message" id="statusMessageArea" class="form-control" rows="3"
                          placeholder="e.g. Your case is now under investigation. A hearing is scheduled for next week. We will contact you with details."><?php echo e(old('status_message')); ?></textarea>
            </div>
        </div>
        <?php endif; ?>
        <?php if($blotter->file_path): ?>
        <div style="margin-bottom:16px;padding:12px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);display:flex;align-items:center;gap:12px">
            <i class="fas fa-file" style="font-size:20px;color:var(--navy);flex-shrink:0"></i>
            <div style="flex:1"><div style="font-size:14px;font-weight:600"><?php echo e($blotter->file_original_name ?? 'Attached File'); ?></div><div style="font-size:13px;color:var(--text-muted)">Currently attached</div></div>
            <a href="<?php echo e(asset('storage/' . $blotter->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View</a>
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--crimson);cursor:pointer"><input type="checkbox" name="remove_attachment" value="1"> Remove</label>
        </div>
        <?php endif; ?>
        <div class="form-group">
            <label class="form-label"><?php echo e($blotter->file_path ? 'Replace Attachment' : 'Supporting Document'); ?></label>
            <div style="border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center;cursor:pointer" id="dropZone">
                <i class="fas fa-cloud-arrow-up" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:6px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div style="font-size:13px;color:var(--text-subtle)">JPG, PNG, PDF, DOC — Max 5MB</div>
                <input type="file" id="fileInput" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display:none">
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Changes</button>
    <a href="<?php echo e(route('blotter.show', $blotter)); ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
/* ── Status-dependent conditional fields ──────────────────── */
(function () {
    var statusSel      = document.getElementById('statusSelect');
    var settledGroup   = document.getElementById('settledDateGroup');
    var resNotes       = document.getElementById('resolutionNotes');
    var resIcon        = document.getElementById('resolutionIcon');
    var resHint        = document.getElementById('resolutionHint');
    var msgArea        = document.getElementById('statusMessageArea');
    var origStatus     = '<?php echo e($blotter->status); ?>';

    var CLOSING  = ['Settled'];
    var TERMINAL = ['Settled', 'Referred to Higher Authority'];

    function syncStatusUi() {
        if (!statusSel) return;
        var s = statusSel.value;
        var isClosing  = CLOSING.indexOf(s) !== -1;
        var isTerminal = TERMINAL.indexOf(s) !== -1;

        // Settled date: show only when status = Settled
        if (settledGroup) settledGroup.style.display = isClosing ? '' : 'none';

        // Resolution notes: highlight when terminal status
        if (resNotes) {
            resNotes.style.borderColor = isTerminal ? '#86efac' : '';
            resNotes.rows = isTerminal ? 4 : 3;
            resNotes.placeholder = isTerminal
                ? 'Describe the resolution — what was agreed, who mediated, or why it was referred.'
                : 'Optional: describe any action taken so far.';
        }
        if (resIcon) resIcon.style.display = isTerminal ? '' : 'none';
        if (resHint) resHint.style.display  = isTerminal ? 'flex' : 'none';
    }

    if (statusSel) {
        statusSel.addEventListener('change', function () {
            syncStatusUi();
            // Auto-scroll to message field on status change (portal only)
            if (msgArea && statusSel.value !== origStatus) {
                msgArea.closest('.form-group').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                setTimeout(function () { msgArea.focus(); }, 250);
            }
        });
    }
    syncStatusUi();
}());

// Auto-fill complainant name/address when resident selected
$('#complainant_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const address  = parts[1] ? parts[1].trim() : '';
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#complainant_name').val(fullName);
    $('#complainant_address').val(address);
});
$('#complainant_resident_id').on('select2:clear', function() {
    $('#complainant_name').val('');
    $('#complainant_address').val('');
});

// Auto-fill respondent name/address when resident selected
$('#respondent_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const address  = parts[1] ? parts[1].trim() : '';
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#respondent_name').val(fullName);
    $('#respondent_address').val(address);
});
$('#respondent_resident_id').on('select2:clear', function() {
    $('#respondent_name').val('');
    $('#respondent_address').val('');
});

// File upload — click + full drag-and-drop support
(function () {
    var zone  = document.getElementById('dropZone');
    var input = document.getElementById('fileInput');

    zone.addEventListener('click', function () { input.click(); });

    zone.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor    = 'var(--navy)';
        zone.style.backgroundColor = 'rgba(13,33,68,.04)';
    });

    zone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
    });

    zone.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';

        var files = e.dataTransfer.files;
        if (files.length > 0) {
            // Transfer the dropped file to the real <input type="file">
            var dt = new DataTransfer();
            dt.items.add(files[0]);
            input.files = dt.files;

            // Show file name feedback inside the drop zone
            var label = zone.querySelector('.drop-feedback');
            if (! label) {
                label = document.createElement('div');
                label.className = 'drop-feedback';
                label.style.cssText = 'margin-top:8px;font-size:13px;font-weight:600;color:var(--navy)';
                zone.appendChild(label);
            }
            label.textContent = '✔ ' + files[0].name;
        }
    });

    // Also show file name when using the browse button
    input.addEventListener('change', function () {
        if (input.files.length > 0) {
            var label = zone.querySelector('.drop-feedback');
            if (! label) {
                label = document.createElement('div');
                label.className = 'drop-feedback';
                label.style.cssText = 'margin-top:8px;font-size:13px;font-weight:600;color:var(--navy)';
                zone.appendChild(label);
            }
            label.textContent = '✔ ' + input.files[0].name;
        }
    });
}());
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\blotter\blotter-edit.blade.php ENDPATH**/ ?>