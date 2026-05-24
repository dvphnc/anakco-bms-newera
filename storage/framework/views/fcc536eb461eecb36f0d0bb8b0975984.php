<?php $__env->startSection('title', 'Puroks'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Puroks & Leaders</h1>
        <p class="page-subtitle">Manage purok assignments and leaders in Barangay New Era</p>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-location-dot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($puroks->count()); ?></div>
            <div class="stat-label">Total Puroks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($puroks->whereNotNull('leader_id')->count()); ?></div>
            <div class="stat-label">With Leaders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-circle-exclamation"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($puroks->whereNull('leader_id')->count()); ?></div>
            <div class="stat-label">No Leader Assigned</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($puroks->sum('residents_count')); ?></div>
            <div class="stat-label">Total Residents</div>
        </div>
    </div>
</div>


<?php $totalResidents = $puroks->sum('residents_count') ?: 1; ?>
<div class="grid-3">
    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $pct = round(($purok->residents_count / $totalResidents) * 100); ?>
    <div class="card">
        <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:20px;display:flex;align-items:center;gap:14px">
            <div style="width:48px;height:48px;border-radius:var(--radius);background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-location-dot" style="font-size:20px;color:var(--gold-light)"></i>
            </div>
            <div style="min-width:0">
                <div style="font-size:15px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo e($purok->name); ?></div>
                <div style="font-size:13px;color:rgba(255,255,255,0.5);margin-top:2px">
                    <?php echo e($purok->residents_count); ?> <?php echo e(Str::plural('resident', $purok->residents_count)); ?>

                </div>
            </div>
            <div style="margin-left:auto">
                <a href="<?php echo e(route('puroks.edit', $purok)); ?>"
                   class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:#fff;border:1px solid rgba(255,255,255,0.2)">
                    <i class="fas fa-pen"></i> Edit
                </a>
            </div>
        </div>

        <div style="padding:16px 20px">
            
            <div style="margin-bottom:12px">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">
                    Purok Leader
                </div>
                <?php if($purok->leader): ?>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                            <?php if($purok->leader->photo_path): ?>
                                <img src="<?php echo e(asset('storage/'.$purok->leader->photo_path)); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($purok->leader->first_name, 0, 1))); ?>

                            <?php endif; ?>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px"><?php echo e($purok->leader->full_name); ?></div>
                            <div class="td-muted"><?php echo e($purok->leader->contact_number ?? 'No contact'); ?></div>
                        </div>
                        <span class="badge badge-green" style="margin-left:auto">Assigned</span>
                    </div>
                <?php else: ?>
                    <div style="display:flex;align-items:center;gap:10px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm);border:1px dashed var(--border)">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted)">
                            <i class="fas fa-user-slash" style="font-size:14px"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;color:var(--text-muted)">No leader assigned</div>
                            <a href="<?php echo e(route('puroks.edit', $purok)); ?>" style="font-size:13px;color:var(--navy)">
                                Assign now →
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            
            <div style="padding-top:12px;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <span style="font-size:11px;font-weight:700;text-transform:uppercase;
                                 letter-spacing:0.07em;color:var(--text-subtle)">
                        Share of Population
                    </span>
                    <span style="font-size:12px;font-weight:700;color:var(--navy)"><?php echo e($pct); ?>%</span>
                </div>
                <div style="background:var(--surface3);border-radius:99px;height:7px;overflow:hidden">
                    <div style="height:100%;border-radius:99px;background:var(--navy);
                                width:<?php echo e($pct); ?>%;transition:width .6s ease;opacity:.75"></div>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:5px">
                    <?php echo e(number_format($purok->residents_count)); ?> of <?php echo e(number_format($totalResidents)); ?> residents
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\puroks\puroks-index.blade.php ENDPATH**/ ?>