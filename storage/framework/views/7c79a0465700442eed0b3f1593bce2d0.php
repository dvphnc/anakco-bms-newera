<?php $__env->startSection('title', $household->household_number); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Household Profile</h1>
        <p class="page-subtitle"><?php echo e($household->household_number); ?> — <?php echo e($household->household_head ?? "—"); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('households.edit', $household)); ?>" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="<?php echo e(route('households.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-house" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-bottom:4px;text-transform:uppercase;letter-spacing:0.1em">
                    <?php echo e($household->household_number); ?>

                </div>
                <div style="font-size:16px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px">
                    <?php echo e($household->household_head ?? "—"); ?>

                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.55)">
                    <?php echo e($household->purok->name ?? "—"); ?>

                </div>
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)">
                <div style="padding:14px 16px;text-align:center;border-right:1px solid var(--border)">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">
                        <?php echo e($household->residents->count()); ?>

                    </div>
                    <div style="font-size:13px;color:var(--text-muted)">Members</div>
                </div>
                <div style="padding:14px 16px;text-align:center">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">
                        <?php echo e($household->family_size ?? 0); ?>

                    </div>
                    <div style="font-size:13px;color:var(--text-muted)">Family Size</div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="<?php echo e(route('households.edit', $household)); ?>" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Household
                </a>
                <form method="POST" action="<?php echo e(route('households.destroy', $household)); ?>"
                      data-confirm="Delete household <?php echo e($household->household_number); ?>? This cannot be undone."
                      data-confirm-title="Delete Household"
                      data-confirm-ok="Delete">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:20px">

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-circle-info"></i> Household Details</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                    <?php
                        $details = [
                            ['label'=>'Household No.',  'value'=>$household->household_number],
                            ['label'=>'Household Head', 'value'=>$household->household_head ?? '—'],
                            ['label'=>'Purok',          'value'=>$household->purok->name ?? '—'],
                            ['label'=>'Family Size',    'value'=>$household->family_size ?? '—'],
                            ['label'=>'Voter Household','value'=>$household->is_voter_household ? 'Yes' : 'No'],
                            ['label'=>'Registered',     'value'=>$household->created_at->format('F d, Y')],
                        ];
                    ?>
                    <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="padding:10px 0;border-bottom:1px solid var(--border);<?php echo e($loop->even ? 'padding-left:24px' : ''); ?>">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">
                            <?php echo e($d['label']); ?>

                        </div>
                        <div style="font-size:15px;color:var(--text);font-weight:500"><?php echo e($d['value']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">Address</div>
                    <div style="font-size:15px;color:var(--text)"><?php echo e($household->address); ?></div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-users"></i> Household Members</span>
                <span style="font-size:13px;color:var(--text-muted)"><?php echo e($household->residents->count()); ?> member(s)</span>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $household->residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px"><?php echo e($resident->full_name); ?></div>
                                <div class="td-muted"><?php echo e($resident->civil_status); ?></div>
                            </td>
                            <td><?php echo e($resident->age ?? '—'); ?></td>
                            <td>
                                <span class="badge <?php echo e($resident->gender === 'Male' ? 'badge-blue' : 'badge-orange'); ?>">
                                    <?php echo e($resident->gender); ?>

                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo e($resident->residency_status === 'Active' ? 'badge-green' : 'badge-gray'); ?>">
                                    <?php echo e($resident->residency_status); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('residents.show', $resident)); ?>" class="btn btn-secondary btn-sm btn-icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state" style="padding:28px">
                                    <i class="fas fa-users"></i>
                                    <p>No members linked to this household.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/households/households-show.blade.php ENDPATH**/ ?>