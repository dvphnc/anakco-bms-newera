<?php $__env->startSection('title', 'Request Submitted'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .confirm-wrap { text-align: center; padding: 1rem 0; }
    .confirm-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #ecfdf5;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.25rem;
        border: 3px solid #16a34a;
    }
    .confirm-wrap h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .4rem;
    }
    .confirm-wrap p {
        color: #6b7280;
        font-size: .88rem;
        max-width: 440px;
        margin: 0 auto 1.5rem;
    }

    .apt-number-box {
        display: inline-block;
        background: var(--navy);
        color: #fff;
        border-radius: var(--radius);
        padding: .9rem 2rem;
        margin-bottom: 1.5rem;
    }
    .apt-number-box .label {
        font-size: .72rem;
        opacity: .7;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: .25rem;
    }
    .apt-number-box .number {
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: .1em;
        color: var(--gold);
    }

    .detail-table {
        width: 100%;
        max-width: 480px;
        margin: 0 auto 1.75rem;
        border-collapse: collapse;
        text-align: left;
    }
    .detail-table td {
        padding: .5rem .75rem;
        font-size: .83rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-table td:first-child {
        color: #9ca3af;
        font-weight: 500;
        width: 40%;
    }
    .detail-table td:last-child { color: var(--navy); font-weight: 600; }

    .status-badge {
        display: inline-block;
        padding: .25rem .75rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
        background: var(--gold-pale);
        color: var(--gold);
        border: 1px solid var(--gold-border);
    }

    .confirm-actions {
        display: flex;
        gap: .75rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .save-notice {
        font-size: .75rem;
        color: #9ca3af;
        margin-top: 1.25rem;
        background: #f9fafb;
        border-radius: var(--radius-sm);
        padding: .7rem 1rem;
    }

    .apt-number-wrap {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }
    .copy-btn {
        position: absolute;
        top: .5rem;
        right: .5rem;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        border-radius: 4px;
        padding: .2rem .5rem;
        font-size: .65rem;
        cursor: pointer;
        letter-spacing: .04em;
        transition: background .15s;
    }
    .copy-btn:hover { background: rgba(255,255,255,.25); }
    .copy-btn.copied { color: #86efac; border-color: #86efac; }

    .submitted-stamp {
        font-size: .72rem;
        color: #9ca3af;
        margin-bottom: 1.25rem;
    }
    .submitted-stamp strong { color: #6b7280; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="portal-wrap portal-wrap-sm">
<div class="p-card">
    <div class="confirm-wrap">
        <div class="confirm-icon"><i class="fas fa-check"></i></div>
        <h2>Request Submitted Successfully!</h2>
        <p>Your document request has been received. Save your appointment number below — you will need it to track your status.</p>

        <div class="apt-number-wrap">
            <div class="apt-number-box" style="margin-bottom:0">
                <div class="label">Your Appointment Number</div>
                <div class="number" id="aptNumDisplay"><?php echo e($appointment->appointment_number); ?></div>
            </div>
            <button class="copy-btn" id="copyBtn" onclick="copyAptNum()" title="Copy to clipboard">
                <i class="fas fa-copy"></i> Copy
            </button>
        </div>

        <div class="submitted-stamp">
            <i class="fas fa-clock"></i>&nbsp;
            Submitted on <strong><?php echo e($appointment->created_at->format('F j, Y')); ?></strong>
            at <strong><?php echo e($appointment->created_at->format('g:i A')); ?></strong>
        </div>

        <table class="detail-table">
            <tr>
                <td>Name</td>
                <td><?php echo e($appointment->resident_name); ?></td>
            </tr>
            <tr>
                <td>Contact Number</td>
                <td><?php echo e($appointment->contact_number); ?></td>
            </tr>
            <?php if($appointment->email): ?>
            <tr>
                <td>Email</td>
                <td><?php echo e($appointment->email); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Document</td>
                <td><?php echo e($appointment->document_type); ?></td>
            </tr>
            <tr>
                <td>Preferred Date</td>
                <td><?php echo e($appointment->preferred_date->format('F j, Y')); ?></td>
            </tr>
            <?php if($appointment->purpose): ?>
            <tr>
                <td>Purpose</td>
                <td><?php echo e($appointment->purpose); ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td>Status</td>
                <td><span class="status-badge"><?php echo e($appointment->status); ?></span></td>
            </tr>
        </table>

        <div class="confirm-actions">
            <a href="<?php echo e(route('portal.track')); ?>?apt=<?php echo e($appointment->appointment_number); ?>" class="btn btn-primary">
                <i class="fas fa-search"></i> Track My Status
            </a>
            <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-outline">
                <i class="fas fa-home"></i> Back to Portal
            </a>
        </div>

        <div class="save-notice">
            <i class="fas fa-bookmark"></i>
            <strong>Tip:</strong> Screenshot or note down your appointment number. Staff will confirm your schedule within 1–2 business days. Bring a valid ID when claiming your document.
        </div>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/confirmation.blade.php ENDPATH**/ ?>