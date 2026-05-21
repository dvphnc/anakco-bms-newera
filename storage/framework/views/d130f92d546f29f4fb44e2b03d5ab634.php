<?php $__env->startSection('title', 'File Blotter Case'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">File Blotter Case</h1>
        <p class="page-subtitle">Record a new incident or complaint</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('blotter.store')); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-exclamation-triangle"></i> Incident Details</span>
    </div>
    <div class="card-body">
        <div class="form-section-title">Incident Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Incident Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the category that best describes the complaint or incident. Choose 'Other' if none of the options fit, and describe fully in the Incident Details field below.">?</span>
                </label>
                <select name="incident_type" class="form-control" required>
                    <option value="">Select Type</option>
                    <?php $__currentLoopData = ['Noise Complaint','Physical Assault','Verbal Abuse','Theft','Trespassing','Domestic Dispute','Property Damage','Threat','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('incident_type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
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
                <input type="date" name="incident_date" class="form-control" value="<?php echo e(old('incident_date', date('Y-m-d'))); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Active' = newly filed. 'Under Investigation' = Barangay is looking into it. 'Mediated' = parties have met. 'Settled' = issue resolved. 'Referred' = escalated to police or court.">?</span>
                </label>
                <select name="status" class="form-control">
                    <?php $__currentLoopData = ['Active','Under Investigation','Mediated','Settled','Closed','Referred to Higher Authority']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status','Active') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">
                Incident Location <span style="color:var(--crimson)">*</span>
                <span class="help-icon" data-tippy-content="Be as specific as possible. Include the Purok number and a landmark. Example: 'Purok 3, in front of Aling Nena's store near the basketball court.'">?</span>
            </label>
            <input type="text" name="incident_location" class="form-control" value="<?php echo e(old('incident_location')); ?>" placeholder="e.g. Purok 3, near the basketball court" required>
        </div>
        <div class="form-group">
            <label class="form-label">
                Incident Details <span style="color:var(--crimson)">*</span>
                <span class="help-icon" data-tippy-content="Write a factual, detailed account of what happened. Include the date and time, what was said or done, and who was present. This becomes the official record.">?</span>
            </label>
            <textarea name="incident_details" class="form-control" rows="5" placeholder="Describe what happened in detail..." required><?php echo e(old('incident_details')); ?></textarea>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-users"></i> Parties Involved</span>
    </div>
    <div class="card-body">
        <div class="form-section-title">Complainant</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Complainant Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="complainant_name" id="complainant_name" class="form-control" value="<?php echo e(old('complainant_name')); ?>" placeholder="Full name" required>
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
                    <?php if(old('complainant_resident_id')): ?>
                        <?php $cr = \App\Models\Resident::find(old('complainant_resident_id')); ?>
                        <?php if($cr): ?><option value="<?php echo e($cr->id); ?>" selected><?php echo e($cr->last_name); ?>, <?php echo e($cr->first_name); ?> — <?php echo e($cr->address); ?></option><?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <input type="text" name="complainant_address" id="complainant_address" class="form-control" value="<?php echo e(old('complainant_address')); ?>" placeholder="Address">
            </div>
            <div class="form-group">
                <label class="form-label">Contact</label>
                <input type="text" name="complainant_contact" id="complainant_contact" class="form-control" value="<?php echo e(old('complainant_contact')); ?>" placeholder="09XX XXX XXXX">
            </div>
        </div>

        <div class="form-section-title">Respondent</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Respondent Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="respondent_name" id="respondent_name" class="form-control" value="<?php echo e(old('respondent_name')); ?>" placeholder="Full name" required>
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
                    <?php if(old('respondent_resident_id')): ?>
                        <?php $rr = \App\Models\Resident::find(old('respondent_resident_id')); ?>
                        <?php if($rr): ?><option value="<?php echo e($rr->id); ?>" selected><?php echo e($rr->last_name); ?>, <?php echo e($rr->first_name); ?> — <?php echo e($rr->address); ?></option><?php endif; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Address</label>
                <input type="text" name="respondent_address" id="respondent_address" class="form-control" value="<?php echo e(old('respondent_address')); ?>" placeholder="Address">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Contact</label>
                <input type="text" name="respondent_contact" class="form-control" value="<?php echo e(old('respondent_contact')); ?>" placeholder="09XX XXX XXXX">
            </div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clipboard-list"></i> Resolution & Attachments</span>
    </div>
    <div class="card-body">
        <div class="form-group mb-6">
            <label class="form-label">
                Resolution Notes
                <span class="help-icon" data-tippy-content="Describe what action was taken: who mediated, what the parties agreed to, or why the case was referred. Leave blank if the case is newly filed.">?</span>
            </label>
            <textarea name="resolution_notes" class="form-control" rows="3" placeholder="Describe the action taken or resolution..."><?php echo e(old('resolution_notes')); ?></textarea>
        </div>
        <div class="form-group">
            <label class="form-label">
                Supporting Document
                <span class="help-icon" data-tippy-content="Attach any evidence: photos, screenshots, or a written complaint letter. Accepted: JPG, PNG, PDF, DOC — Max 5MB.">?</span>
            </label>
            <div style="border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center;cursor:pointer" id="dropZone">
                <i class="fas fa-cloud-arrow-up" style="font-size:30px;color:var(--text-muted);margin-bottom:10px;display:block"></i>
                <div style="font-size:14px;color:var(--text-muted);margin-bottom:6px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div style="font-size:13px;color:var(--text-subtle)">JPG, PNG, PDF, DOC — Max 5MB</div>
                <input type="file" id="fileInput" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display:none">
            </div>
            <div id="filePreview" style="display:none;margin-top:10px;padding:12px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);align-items:center;gap:12px">
                <i class="fas fa-file" style="color:var(--navy);font-size:20px"></i>
                <div style="flex:1"><div id="fileName" style="font-size:14px;font-weight:600"></div><div id="fileSize" style="font-size:13px;color:var(--text-muted);margin-top:2px"></div></div>
                <button type="button" onclick="clearFile()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:16px"><i class="fas fa-times"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-gavel"></i> File Case</button>
    <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
// Auto-fill complainant name/address/contact when resident is selected
$('#complainant_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    // text format: "LastName, FirstName MiddleInitial. — Address"
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const address  = parts[1] ? parts[1].trim() : '';
    // Convert "LastName, FirstName" to "FirstName LastName"
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#complainant_name').val(fullName);
    $('#complainant_address').val(address);
});

$('#complainant_resident_id').on('select2:clear', function() {
    $('#complainant_name').val('');
    $('#complainant_address').val('');
    $('#complainant_contact').val('');
});

// Auto-fill respondent
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

// File upload
const dropZone  = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const preview   = document.getElementById('filePreview');
dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor = 'var(--navy)'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = 'var(--border)'; });
dropZone.addEventListener('drop', e => {
    e.preventDefault(); dropZone.style.borderColor = 'var(--border)';
    const dt = new DataTransfer(); dt.items.add(e.dataTransfer.files[0]);
    fileInput.files = dt.files; showPreview(e.dataTransfer.files[0]);
});
fileInput.addEventListener('change', () => { if (fileInput.files[0]) showPreview(fileInput.files[0]); });
function showPreview(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size/1024).toFixed(1)+' KB';
    preview.style.display = 'flex';
}
function clearFile() { fileInput.value = ''; preview.style.display = 'none'; }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/blotter/blotter-create.blade.php ENDPATH**/ ?>