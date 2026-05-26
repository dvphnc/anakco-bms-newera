<?php $__env->startSection('title', 'Request Submitted'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.confirm-wrap {
    text-align: center;
    padding: clamp(2rem, 6vw, 3.5rem) 1.5rem;
}
.confirm-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: #ecfdf5;
    border: 2px solid #86efac;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #16a34a;
    margin: 0 auto 1.5rem;
    animation: pop-in .4s cubic-bezier(.34,1.56,.64,1);
}
@keyframes pop-in { from { transform: scale(.4); opacity: 0; } }
.confirm-number {
    display: inline-block;
    font-family: 'Courier New', monospace;
    font-size: 1.4rem; font-weight: 700;
    color: var(--navy);
    background: var(--navy-pale);
    border: 1.5px dashed var(--navy-border);
    border-radius: var(--radius-sm);
    padding: .45rem 1.25rem;
    margin: .75rem 0 1.5rem;
    letter-spacing: .06em;
    cursor: pointer;
}
.confirm-number::after {
    content: '';
}
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .6rem .5rem;
    text-align: left;
    background: #f8f9fa;
    border: 1px solid #e2e6ea;
    border-radius: var(--radius-sm);
    padding: 1rem 1.25rem;
    margin: 1.25rem 0 1.75rem;
    font-size: .84rem;
}
.detail-grid dt { color: #6b7280; font-weight: 500; }
.detail-grid dd { color: var(--navy); font-weight: 600; }
@media (max-width: 500px) {
    .detail-grid { grid-template-columns: 1fr; gap: .3rem; }
    .detail-grid dt { margin-top: .4rem; }
    .detail-grid dt:first-child { margin-top: 0; }
}
.copy-tip {
    font-size: .75rem; color: #9ca3af;
    margin-top: .3rem; margin-bottom: 1.25rem;
}
.confirm-actions {
    display: flex; gap: .75rem; justify-content: center;
    flex-wrap: wrap; margin-top: .5rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="portal-wrap-sm">
<div class="p-card">
    <div class="confirm-wrap">

        <div class="confirm-icon"><i class="fas fa-check"></i></div>

        <h2 style="font-size:1.3rem;font-weight:700;color:var(--navy);margin-bottom:.4rem">
            <?php if($type === 'blotter'): ?> Blotter Report Submitted
            <?php else: ?> Appointment Scheduled
            <?php endif; ?>
        </h2>
        <p style="font-size:.9rem;color:#6b7280;max-width:440px;margin:0 auto .75rem">
            <?php if($type === 'blotter'): ?>
                Your blotter report has been received. A barangay staff member will contact you for follow-up.
            <?php else: ?>
                Your business permit appointment has been scheduled. Please visit the Barangay Hall on your preferred date with your documents.
            <?php endif; ?>
        </p>

        
        <div id="refNum"
             class="confirm-number"
             title="Click to copy"
             onclick="copyRef('<?php echo e($number); ?>')"><?php echo e($number); ?></div>
        <div class="copy-tip" id="copyTip">Click the number above to copy</div>

        
        <dl class="detail-grid">
            <?php if($type === 'blotter'): ?>
                <dt>Complainant</dt>
                <dd><?php echo e($record->complainant_name); ?></dd>
                <dt>Incident Type</dt>
                <dd><?php echo e($record->incident_type); ?></dd>
                <dt>Incident Date</dt>
                <dd><?php echo e($record->incident_date->format('F d, Y')); ?></dd>
                <dt>Status</dt>
                <dd><span style="color:#c8861a;font-weight:700">Pending</span></dd>
                <dt>Submitted</dt>
                <dd><?php echo e($record->created_at->format('M d, Y g:i A')); ?></dd>
                <?php if($record->email): ?>
                    <dt>Notification</dt>
                    <dd>Will be sent to <?php echo e($record->email); ?></dd>
                <?php endif; ?>
            <?php else: ?>
                <dt>Owner</dt>
                <dd><?php echo e($record->owner_name); ?></dd>
                <dt>Business Name</dt>
                <dd><?php echo e($record->business_name); ?></dd>
                <dt>Business Type</dt>
                <dd><?php echo e($record->business_type); ?></dd>
                <dt>Appointment Date</dt>
                <dd><?php echo e($record->preferred_date ? $record->preferred_date->format('F d, Y') : '—'); ?></dd>
                <dt>Status</dt>
                <dd><span style="color:#c8861a;font-weight:700">Pending</span></dd>
                <dt>Submitted</dt>
                <dd><?php echo e($record->created_at->format('M d, Y g:i A')); ?></dd>
                <?php if($record->email): ?>
                    <dt>Notification</dt>
                    <dd>Will be sent to <?php echo e($record->email); ?></dd>
                <?php endif; ?>
            <?php endif; ?>
        </dl>

        <div class="p-alert p-alert-warning" style="text-align:left">
            <i class="fas fa-triangle-exclamation"></i>
            <span>
                <strong>Save your reference number:</strong> <?php echo e($number); ?><br>
                <?php if($type === 'business'): ?>
                    Bring this number and your required documents on your appointment date. Office hours: Mon–Fri, 8AM–5PM.
                <?php else: ?>
                    You will need this number for follow-up inquiries. Visit the Barangay Hall (Mon–Fri, 8AM–5PM) if needed.
                <?php endif; ?>
            </span>
        </div>

        <div class="confirm-actions">
            <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-outline">
                <i class="fas fa-home"></i> Back to Portal
            </a>
            <a href="<?php echo e(route('portal.track')); ?>?ref=<?php echo e($number); ?>" class="btn btn-outline">
                <i class="fas fa-magnifying-glass"></i> Track Status
            </a>
            <?php
                $submissionContact = $type === 'blotter'
                    ? ($record->complainant_contact ?? '')
                    : ($record->owner_contact ?? '');
            ?>
            <a href="<?php echo e(route('portal.submissions')); ?>?contact=<?php echo e(urlencode($submissionContact)); ?>#<?php echo e($type); ?>" class="btn btn-outline">
                <i class="fas fa-folder-open"></i> My Submissions
            </a>
            <?php if($type === 'blotter'): ?>
                <a href="<?php echo e(route('portal.blotter')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> File Another Report
                </a>
            <?php else: ?>
                <a href="<?php echo e(route('portal.business')); ?>" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i> Schedule Another
                </a>
            <?php endif; ?>
        </div>

    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function copyRef(num) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(num).then(function () {
            document.getElementById('copyTip').textContent = '✓ Copied to clipboard!';
            setTimeout(function () {
                document.getElementById('copyTip').textContent = 'Click the number above to copy';
            }, 2500);
        });
    } else {
        var el = document.getElementById('refNum');
        var range = document.createRange();
        range.selectNodeContents(el);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
        document.getElementById('copyTip').textContent = '✓ Copied!';
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/submitted.blade.php ENDPATH**/ ?>