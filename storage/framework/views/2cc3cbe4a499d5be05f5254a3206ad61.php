<?php $__env->startSection('title', 'Track Appointment'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .track-hd {
        margin-bottom: 1.5rem;
    }
    .track-hd h2 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .25rem;
    }
    .track-hd p { font-size: .82rem; color: #6b7280; }

    .search-row {
        display: flex;
        gap: .75rem;
        align-items: flex-start;
    }
    .search-row .form-control { flex: 1; font-size: .95rem; }
    .search-row .btn { flex-shrink: 0; }

    /* Status result */
    .result-card {
        margin-top: 2rem;
        border: 2px solid #e5e7eb;
        border-radius: var(--radius-lg);
        overflow: hidden;
    }
    .result-header {
        background: var(--navy);
        color: #fff;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .result-header .apt-num {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--gold);
    }
    .result-body {
        padding: 1.5rem;
    }

    .detail-table {
        width: 100%;
        border-collapse: collapse;
    }
    .detail-table td {
        padding: .55rem .5rem;
        font-size: .84rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-table td:first-child {
        color: #9ca3af;
        font-weight: 500;
        width: 35%;
    }
    .detail-table td:last-child { color: var(--navy); font-weight: 600; }

    /* Progress bar */
    .progress-section { margin-top: 1.5rem; }
    .progress-section h4 {
        font-size: .8rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: .75rem;
    }
    .progress-steps {
        display: flex;
        align-items: center;
        gap: 0;
    }
    .prog-step {
        flex: 1;
        text-align: center;
        position: relative;
    }
    .prog-step::before {
        content: '';
        position: absolute;
        top: 14px;
        left: calc(50% + 14px);
        right: calc(-50% + 14px);
        height: 2px;
        background: #e5e7eb;
        z-index: 0;
    }
    .prog-step:last-child::before { display: none; }
    .prog-step.done::before { background: var(--navy); }
    .prog-dot {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        margin: 0 auto .4rem;
        position: relative;
        z-index: 1;
        border: 2px solid #d1d5db;
    }
    .prog-step.done .prog-dot {
        background: var(--navy);
        color: #fff;
        border-color: var(--navy);
    }
    .prog-step.current .prog-dot {
        background: var(--gold);
        color: #fff;
        border-color: var(--gold);
    }
    .prog-step.cancelled .prog-dot {
        background: var(--crimson);
        color: #fff;
        border-color: var(--crimson);
    }
    .prog-label {
        font-size: .66rem;
        color: #9ca3af;
        font-weight: 500;
    }
    .prog-step.done .prog-label,
    .prog-step.current .prog-label { color: var(--navy); font-weight: 600; }

    .not-found {
        text-align: center;
        padding: 2.5rem 1rem;
    }
    .not-found .nf-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--crimson-pale);
        color: var(--crimson);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 1rem;
    }
    .not-found h3 { font-size: 1rem; color: var(--navy); margin-bottom: .35rem; }
    .not-found p { font-size: .82rem; color: #6b7280; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-card">
    <div class="track-hd">
        <h2><i class="fas fa-search" style="color:var(--gold)"></i>&nbsp; Track Your Appointment</h2>
        <p>Enter your appointment number (e.g., <code>APT-20260507-AB12</code>) to check the status of your request.</p>
    </div>

    <form method="POST" action="<?php echo e(route('portal.track.post')); ?>">
        <?php echo csrf_field(); ?>
        <div class="search-row">
            <input type="text" name="appointment_number" class="form-control"
                   placeholder="APT-YYYYMMDD-XXXX"
                   value="<?php echo e(request('apt') ?? old('appointment_number') ?? (isset($appointment) ? $appointment->appointment_number : '')); ?>"
                   style="text-transform:uppercase;letter-spacing:.08em"
                   required>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Track
            </button>
        </div>
        <?php $__errorArgs = ['appointment_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error" style="margin-top:.4rem"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </form>

    <?php if(isset($appointment)): ?>
        <?php if($appointment): ?>
            <?php
                $steps   = ['Pending','Confirmed','Processing','Ready','Released'];
                $current = $appointment->status;
                $cancelled = $current === 'Cancelled';
                $stepIndex = array_search($current, $steps);
            ?>

            <div class="result-card">
                <div class="result-header">
                    <div>
                        <div style="font-size:.72rem;opacity:.7;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.2rem">Appointment Number</div>
                        <div class="apt-num"><?php echo e($appointment->appointment_number); ?></div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-size:.72rem;opacity:.7;margin-bottom:.2rem">Submitted</div>
                        <div style="font-size:.85rem"><?php echo e($appointment->created_at->format('M d, Y')); ?></div>
                    </div>
                </div>

                <div class="result-body">
                    <table class="detail-table">
                        <tr>
                            <td>Name</td>
                            <td><?php echo e($appointment->resident_name); ?></td>
                        </tr>
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
                        <?php if($appointment->notes): ?>
                        <tr>
                            <td>Staff Notes</td>
                            <td><?php echo e($appointment->notes); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($appointment->released_at): ?>
                        <tr>
                            <td>Released On</td>
                            <td><?php echo e($appointment->released_at->format('F j, Y g:i A')); ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <?php if(!$cancelled): ?>
                    <div class="progress-section">
                        <h4>Progress</h4>
                        <div class="progress-steps">
                            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isDone    = $stepIndex !== false && $i < $stepIndex;
                                    $isCurrent = $current === $step;
                                ?>
                                <div class="prog-step <?php echo e($isDone ? 'done' : ''); ?> <?php echo e($isCurrent ? 'current' : ''); ?>">
                                    <div class="prog-dot">
                                        <?php if($isDone): ?>
                                            <i class="fas fa-check"></i>
                                        <?php elseif($isCurrent): ?>
                                            <i class="fas fa-circle-dot"></i>
                                        <?php else: ?>
                                            <?php echo e($i + 1); ?>

                                        <?php endif; ?>
                                    </div>
                                    <div class="prog-label"><?php echo e($step); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="margin-top:1.25rem;padding:.75rem 1rem;background:var(--crimson-pale);border-radius:var(--radius-sm);border-left:4px solid var(--crimson);font-size:.83rem;color:var(--crimson)">
                        <i class="fas fa-ban"></i> This appointment has been <strong>cancelled</strong>.
                        <?php if($appointment->notes): ?> <?php echo e($appointment->notes); ?> <?php endif; ?>
                        Please visit the barangay hall or submit a new request.
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="not-found">
                <div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>
                <h3>Appointment Not Found</h3>
                <p>No record found for that appointment number. Please double-check and try again.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/track.blade.php ENDPATH**/ ?>