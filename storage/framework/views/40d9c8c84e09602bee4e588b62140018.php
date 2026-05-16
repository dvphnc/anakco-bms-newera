<?php $__env->startSection('title', $resident->full_name); ?>
<?php $__env->startSection('page-title', 'Residents'); ?>
<?php $__env->startSection('page-subtitle', 'Resident profile'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Resident Profile</h1>
        <p class="page-subtitle">Viewing record of <?php echo e($resident->full_name); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('residents.edit', $resident)); ?>" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="grid-2" style="grid-template-columns:300px 1fr;align-items:start">

    
    <div style="display:flex;flex-direction:column;gap:16px">

        
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:28px 20px;text-align:center">
                <div style="width:90px;height:90px;border-radius:50%;margin:0 auto 14px;overflow:hidden;border:3px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center">
                    <?php if($resident->photo_path): ?>
                        <img src="<?php echo e(asset('storage/'.$resident->photo_path)); ?>"
                             style="width:100%;height:100%;object-fit:cover">
                    <?php else: ?>
                        <span style="font-size:32px;font-weight:700;color:#fff">
                            <?php echo e(strtoupper(substr($resident->first_name,0,1))); ?>

                        </span>
                    <?php endif; ?>
                </div>
                <div style="font-size:18px;font-weight:700;color:#fff;line-height:1.2">
                    <?php echo e($resident->full_name); ?>

                </div>
                <div style="font-size:14px;color:rgba(255,255,255,0.7);margin-top:5px">
                    <?php echo e($resident->purok->name ?? '—'); ?>

                </div>
                <div style="margin-top:12px">
                    <?php
                        $cls = match($resident->residency_status) {
                            'Active'      => 'badge-green',
                            'Deceased'    => 'badge-gray',
                            'Transferred' => 'badge-yellow',
                            default       => 'badge-gray'
                        };
                    ?>
                    <span class="badge <?php echo e($cls); ?>"><?php echo e($resident->residency_status); ?></span>
                </div>
            </div>

            
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin-bottom:10px">
                    Classifications
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px">
                    <?php if($resident->is_voter): ?>       <span class="badge badge-green">Voter</span>        <?php endif; ?>
                    <?php if($resident->is_senior): ?>      <span class="badge badge-yellow">Senior Citizen</span> <?php endif; ?>
                    <?php if($resident->is_pwd): ?>         <span class="badge badge-blue">PWD</span>           <?php endif; ?>
                    <?php if($resident->is_solo_parent): ?> <span class="badge badge-orange">Solo Parent</span> <?php endif; ?>
                    <?php if($resident->is_4ps): ?>         <span class="badge badge-gold">4Ps</span>           <?php endif; ?>
                    <?php if(!$resident->is_voter && !$resident->is_senior && !$resident->is_pwd && !$resident->is_solo_parent && !$resident->is_4ps): ?>
                        <span style="font-size:13px;color:var(--text-subtle)">None</span>
                    <?php endif; ?>
                </div>
            </div>

            
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)">
                <div style="padding:16px;text-align:center;border-right:1px solid var(--border)">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)"><?php echo e($resident->age ?? '—'); ?></div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px">Age</div>
                </div>
                <div style="padding:16px;text-align:center">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)"><?php echo e($resident->years_of_residency ?? '—'); ?></div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px">Yrs. Residency</div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Quick Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="<?php echo e(route('documents.create', ['resident_id' => $resident->id])); ?>"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-file-circle-plus" style="color:var(--gold)"></i>
                    Issue Document
                </a>
                <a href="<?php echo e(route('blotter.create', ['complainant_resident_id' => $resident->id])); ?>"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-gavel" style="color:var(--crimson-mid)"></i>
                    File Blotter Case
                </a>
                <a href="<?php echo e(route('residents.edit', $resident)); ?>"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i>
                    Edit Profile
                </a>
                <form method="POST" action="<?php echo e(route('residents.destroy', $resident)); ?>"
                      data-confirm="Delete <?php echo e($resident->full_name); ?>? This action cannot be undone."
                      data-confirm-title="Delete Resident"
                      data-confirm-ok="Delete Permanently">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete Record
                    </button>
                </form>
            </div>
        </div>

    </div>

    
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-id-card"></i> Resident Details</span>
                <span class="td-mono">ID #<?php echo e(str_pad($resident->id, 5, '0', STR_PAD_LEFT)); ?></span>
            </div>
            <div class="card-body">

                
                <div class="tab-nav" id="profileTabs">
                    <button class="tab-btn active" onclick="switchTab(event,'tab-personal')">Personal</button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-documents')">
                        Documents
                        <?php if($resident->documents->count()): ?>
                            <span style="background:var(--gold);color:#fff;border-radius:99px;padding:2px 8px;font-size:13px;margin-left:5px">
                                <?php echo e($resident->documents->count()); ?>

                            </span>
                        <?php endif; ?>
                    </button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-blotter')">
                        Blotter
                        <?php if($resident->blotterCases->count()): ?>
                            <span style="background:var(--crimson-mid);color:#fff;border-radius:99px;padding:2px 8px;font-size:13px;margin-left:5px">
                                <?php echo e($resident->blotterCases->count()); ?>

                            </span>
                        <?php endif; ?>
                    </button>
                </div>

                
                <div class="tab-pane active" id="tab-personal">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                        <?php
                            $details = [
                                ['label' => 'Last Name',       'value' => $resident->last_name],
                                ['label' => 'First Name',      'value' => $resident->first_name],
                                ['label' => 'Middle Name',     'value' => $resident->middle_name ?: '—'],
                                ['label' => 'Date of Birth',   'value' => $resident->birthdate?->format('F d, Y')],
                                ['label' => 'Age',             'value' => $resident->age ? $resident->age . ' years old' : '—'],
                                ['label' => 'Gender',          'value' => $resident->gender],
                                ['label' => 'Civil Status',    'value' => $resident->civil_status],
                                ['label' => 'Nationality',     'value' => $resident->nationality ?: '—'],
                                ['label' => 'Religion',        'value' => $resident->religion ?: '—'],
                                ['label' => 'Occupation',      'value' => $resident->occupation ?: '—'],
                                ['label' => 'Contact Number',  'value' => $resident->contact_number ?: '—'],
                                ['label' => 'Purok',           'value' => $resident->purok->name ?? '—'],
                                ['label' => 'Household',       'value' => $resident->household?->household_number . ' — ' . ($resident->household?->household_head) ?: '—'],
                                ['label' => 'Address',         'value' => $resident->address],
                                ['label' => 'Yrs of Residency','value' => $resident->years_of_residency ? $resident->years_of_residency . ' years' : '—'],
                                ['label' => 'Registered',      'value' => $resident->created_at->format('F d, Y')],
                                ['label' => 'Last Updated',     'value' => $resident->updated_at->format('F d, Y')],
                            ];
                        ?>
                        <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div style="padding:12px 0;border-bottom:1px solid var(--border);<?php echo e($loop->iteration % 2 === 0 ? 'padding-left:24px' : ''); ?>">
                            <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-subtle);margin-bottom:3px">
                                <?php echo e($detail['label']); ?>

                            </div>
                            <div style="font-size:15px;color:var(--text);font-weight:500;line-height:1.4">
                                <?php echo e($detail['value']); ?>

                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                
                <div class="tab-pane" id="tab-documents">
                    <?php $__empty_1 = true; $__currentLoopData = $resident->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div class="td-mono"><?php echo e($doc->doc_number); ?></div>
                            <div style="font-weight:600;font-size:14px;margin-top:2px"><?php echo e($doc->document_type); ?></div>
                            <div class="td-muted" style="margin-top:2px"><?php echo e($doc->created_at->format('M d, Y')); ?></div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <?php
                                $cls = match($doc->status) {
                                    'Released'   => 'badge-green',
                                    'Processing' => 'badge-blue',
                                    'Cancelled'  => 'badge-gray',
                                    default      => 'badge-yellow'
                                };
                            ?>
                            <span class="badge <?php echo e($cls); ?>"><?php echo e($doc->status); ?></span>
                            <a href="<?php echo e(route('documents.show', $doc)); ?>" class="btn btn-secondary btn-sm btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <i class="fas fa-file-certificate"></i>
                        <p>No documents issued yet.</p>
                    </div>
                    <?php endif; ?>
                </div>

                
                <div class="tab-pane" id="tab-blotter">
                    <?php $__empty_1 = true; $__currentLoopData = $resident->blotterCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div class="td-mono"><?php echo e($case->case_number); ?></div>
                            <div style="font-weight:600;font-size:14px;margin-top:2px"><?php echo e($case->incident_type); ?></div>
                            <div class="td-muted" style="margin-top:2px"><?php echo e($case->incident_date?->format('M d, Y')); ?></div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <span class="badge <?php echo e($case->status_badge); ?>"><?php echo e($case->status); ?></span>
                            <a href="<?php echo e(route('blotter.show', $case)); ?>" class="btn btn-secondary btn-sm btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-state">
                        <i class="fas fa-gavel"></i>
                        <p>No blotter cases on record.</p>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function switchTab(e, tabId) {
        // Remove active from all buttons and panes
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        // Activate clicked
        e.currentTarget.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/residents/residents-show.blade.php ENDPATH**/ ?>