<?php $__env->startSection('title', 'File Blotter Case'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">File Blotter Case</h1>
        <p class="page-subtitle">Record a new incident or complaint</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
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
                <label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label>
                <select name="incident_type" class="form-control" required>
                    <option value="">Select Type</option>
                    <?php $__currentLoopData = ['Noise Complaint','Physical Assault','Verbal Abuse','Theft','Trespassing','Domestic Dispute','Property Damage','Threat','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e(old('incident_type') === $t ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="incident_date" class="form-control"
                       value="<?php echo e(old('incident_date', date('Y-m-d'))); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <?php $__currentLoopData = ['Active','Under Investigation','Mediated','Settled','Closed','Referred to Higher Authority']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s); ?>" <?php echo e(old('status','Active') === $s ? 'selected' : ''); ?>><?php echo e($s); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">Incident Location <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="incident_location" class="form-control"
                   value="<?php echo e(old('incident_location')); ?>"
                   placeholder="e.g. Purok 3, near the basketball court" required>
        </div>
        <div class="form-group">
            <label class="form-label">Incident Details <span style="color:var(--crimson)">*</span></label>
            <textarea name="incident_details" class="form-control" rows="5"
                      placeholder="Describe what happened in detail..." required><?php echo e(old('incident_details')); ?></textarea>
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
                <input type="text" name="complainant_name" class="form-control"
                       value="<?php echo e(old('complainant_name')); ?>" placeholder="Full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident (optional)</label>
                <select name="complainant_resident_id" class="form-control">
                    <option value="">— Not a registered resident —</option>
                    <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($r->id); ?>" <?php echo e(old('complainant_resident_id') == $r->id ? 'selected' : ''); ?>>
                            <?php echo e($r->full_name); ?> — <?php echo e($r->purok->name ?? ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Address</label>
                <input type="text" name="complainant_address" class="form-control"
                       value="<?php echo e(old('complainant_address')); ?>" placeholder="Address">
            </div>
            <div class="form-group">
                <label class="form-label">Contact</label>
                <input type="text" name="complainant_contact" class="form-control"
                       value="<?php echo e(old('complainant_contact')); ?>" placeholder="09XX XXX XXXX">
            </div>
        </div>

        <div class="form-section-title">Respondent</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Respondent Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="respondent_name" class="form-control"
                       value="<?php echo e(old('respondent_name')); ?>" placeholder="Full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Address</label>
                <input type="text" name="respondent_address" class="form-control"
                       value="<?php echo e(old('respondent_address')); ?>" placeholder="Address">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Contact</label>
                <input type="text" name="respondent_contact" class="form-control"
                       value="<?php echo e(old('respondent_contact')); ?>" placeholder="09XX XXX XXXX">
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
            <label class="form-label">Resolution Notes</label>
            <textarea name="resolution_notes" class="form-control" rows="3"
                      placeholder="Describe the action taken or resolution..."><?php echo e(old('resolution_notes')); ?></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Supporting Document</label>
            <div style="border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center;cursor:pointer;transition:all 0.2s"
                 id="dropZone">
                <i class="fas fa-cloud-arrow-up" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:6px">
                    Drag & drop a file here, or
                    <span style="color:var(--navy);font-weight:600;cursor:pointer;text-decoration:underline">browse</span>
                </div>
                <div style="font-size:11px;color:var(--text-subtle)">JPG, PNG, PDF, DOC, DOCX — Max 5MB</div>
                <input type="file" id="fileInput" name="attachment"
                       accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                       style="display:none">
            </div>
            <div id="filePreview" style="display:none;margin-top:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);align-items:center;gap:10px">
                <i class="fas fa-file" style="color:var(--navy);font-size:18px;flex-shrink:0"></i>
                <div style="flex:1;min-width:0">
                    <div id="fileName" style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                    <div id="fileSize" style="font-size:11px;color:var(--text-muted)"></div>
                </div>
                <button type="button" onclick="clearFile()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:16px;flex-shrink:0">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-gavel"></i> File Case
    </button>
    <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->startPush('scripts'); ?>
<script>
const dropZone  = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const preview   = document.getElementById('filePreview');

dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.borderColor = 'var(--navy)'; dropZone.style.background = 'var(--navy-pale)'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = 'var(--border)'; dropZone.style.background = ''; });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.style.borderColor = 'var(--border)';
    dropZone.style.background = '';
    const dt = new DataTransfer();
    dt.items.add(e.dataTransfer.files[0]);
    fileInput.files = dt.files;
    showPreview(e.dataTransfer.files[0]);
});
fileInput.addEventListener('change', () => { if (fileInput.files[0]) showPreview(fileInput.files[0]); });

function showPreview(file) {
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
    preview.style.display = 'flex';
}
function clearFile() {
    fileInput.value = '';
    preview.style.display = 'none';
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/blotter/blotter-create.blade.php ENDPATH**/ ?>