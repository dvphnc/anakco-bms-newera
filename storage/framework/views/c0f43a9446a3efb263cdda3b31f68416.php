<?php $__env->startSection('title', 'Edit Purok'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit <?php echo e($purok->name); ?></h1>
        <p class="page-subtitle">Assign a leader and update purok details</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('puroks.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="<?php echo e(route('puroks.update', $purok)); ?>">
<?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

    
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-location-dot"></i> <?php echo e($purok->name); ?></span>
            <span style="font-size:13px;color:var(--text-muted)"><?php echo e($purok->residents_count); ?> residents</span>
        </div>
        <div class="card-body">

            <div class="form-section-title">Purok Leader</div>
            <div class="form-group mb-6">
                <label class="form-label">Assign Leader</label>
                <select name="leader_id" class="form-control" id="leaderSelect">
                    <option value="">— No leader assigned —</option>
                    <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($resident->id); ?>"
                            <?php echo e(old('leader_id', $purok->leader_id) == $resident->id ? 'selected' : ''); ?>

                            data-contact="<?php echo e($resident->contact_number); ?>"
                            data-address="<?php echo e($resident->address); ?>">
                            <?php echo e($resident->last_name); ?>, <?php echo e($resident->first_name); ?>

                            <?php echo e($resident->middle_name ? $resident->middle_name[0].'.' : ''); ?>

                            — <?php echo e($resident->purok->name ?? ''); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px">
                    Only active residents of this purok are shown
                </div>
            </div>

            
            <div id="leaderPreview" style="display:<?php echo e($purok->leader ? 'block' : 'none'); ?>;margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--navy-pale);border:1px solid rgba(13,33,68,0.15);border-radius:var(--radius)">
                    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:14px;flex-shrink:0" id="leaderAvatar">
                        <?php echo e($purok->leader ? strtoupper(substr($purok->leader->first_name, 0, 1)) : ''); ?>

                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px" id="leaderName">
                            <?php echo e($purok->leader?->full_name ?? ''); ?>

                        </div>
                        <div class="td-muted" id="leaderContact">
                            <?php echo e($purok->leader?->contact_number ?? ''); ?>

                        </div>
                    </div>
                    <span class="badge badge-green" style="margin-left:auto">Selected</span>
                </div>
            </div>

            <div class="form-section-title">Purok Details</div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"
                    placeholder="Brief description of this purok (location, landmarks, etc.)"><?php echo e(old('description', $purok->description)); ?></textarea>
            </div>

        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-users"></i> Residents</span>
            <span class="badge badge-navy"><?php echo e($purok->residents_count); ?></span>
        </div>
        <div style="max-height:420px;overflow-y:auto">
            <?php $__empty_1 = true; $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border)">
                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:11px;flex-shrink:0">
                    <?php echo e(strtoupper(substr($r->first_name, 0, 1))); ?>

                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        <?php echo e($r->full_name); ?>

                        <?php if($purok->leader_id == $r->id): ?>
                            <span class="badge badge-gold" style="margin-left:4px">Leader</span>
                        <?php endif; ?>
                    </div>
                    <div class="td-muted" style="font-size:13px"><?php echo e($r->contact_number ?? '—'); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No active residents in this purok</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="form-actions" style="margin-top:20px">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="<?php echo e(route('puroks.index')); ?>" class="btn btn-secondary">Cancel</a>
</div>

</form>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select  = document.getElementById('leaderSelect');
    const preview = document.getElementById('leaderPreview');
    const avatar  = document.getElementById('leaderAvatar');
    const name    = document.getElementById('leaderName');
    const contact = document.getElementById('leaderContact');

    select.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            const text = opt.text.split('—')[0].trim();
            avatar.textContent  = text.charAt(0).toUpperCase();
            name.textContent    = text;
            contact.textContent = opt.dataset.contact || 'No contact on file';
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/puroks/puroks-edit.blade.php ENDPATH**/ ?>