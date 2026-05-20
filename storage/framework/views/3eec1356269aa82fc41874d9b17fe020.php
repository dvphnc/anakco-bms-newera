<?php $__env->startSection('title', $blotter->case_number); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header no-print">
    <div>
        <h1 class="page-title">Blotter Case</h1>
        <p class="page-subtitle"><?php echo e($blotter->case_number); ?> — <?php echo e($blotter->incident_type); ?></p>
    </div>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Report
        </button>
        <a href="<?php echo e(route('blotter.edit', $blotter)); ?>" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>


<div class="no-print" style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-gavel" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.5);margin-bottom:4px">
                    <?php echo e($blotter->case_number); ?>

                </div>
                <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:10px">
                    <?php echo e($blotter->incident_type); ?>

                </div>
                <?php
                    $cls = match($blotter->status) {
                        'Active'                       => 'badge-red',
                        'Under Investigation'          => 'badge-yellow',
                        'Mediated'                     => 'badge-blue',
                        'Settled'                      => 'badge-green',
                        'Closed'                       => 'badge-gray',
                        'Referred to Higher Authority' => 'badge-orange',
                        default                        => 'badge-gray'
                    };
                ?>
                <span class="badge <?php echo e($cls); ?>"><?php echo e($blotter->status); ?></span>
            </div>
            <div style="padding:14px 20px;border-top:1px solid var(--border)">
                <div style="display:flex;flex-direction:column;gap:10px">
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Incident Date</div>
                        <div style="font-size:15px;font-weight:500;color:var(--text)">
                            <?php echo e($blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('F d, Y') : '—'); ?>

                        </div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Location</div>
                        <div style="font-size:15px;color:var(--text-muted)"><?php echo e($blotter->incident_location ?? '—'); ?></div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Filed By</div>
                        <div style="font-size:15px;color:var(--text-muted)"><?php echo e($blotter->filedBy->name ?? '—'); ?></div>
                    </div>
                    <?php if($blotter->settled_at): ?>
                    <div>
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Settled On</div>
                        <div style="font-size:15px;color:var(--text-muted)"><?php echo e($blotter->settled_at->format('F d, Y')); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if($blotter->file_path): ?>
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-paperclip"></i> Attachment</span>
            </div>
            <div class="card-body">
                <?php $isImage = in_array(strtolower($blotter->file_type ?? ''), ['jpg','jpeg','png','gif']); ?>
                <?php if($isImage): ?>
                    <img src="<?php echo e(asset('storage/'.$blotter->file_path)); ?>"
                         style="width:100%;border-radius:var(--radius);border:1px solid var(--border)" alt="Attachment">
                <?php endif; ?>
                <div style="display:flex;align-items:center;gap:10px;<?php echo e($isImage ? 'margin-top:10px' : ''); ?>">
                    <i class="fas <?php echo e($isImage ? 'fa-image' : 'fa-file-pdf'); ?>" style="font-size:18px;color:var(--navy)"></i>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:14px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            <?php echo e($blotter->file_original_name ?? 'Attached File'); ?>

                        </div>
                        <div style="font-size:13px;color:var(--text-muted)"><?php echo e(strtoupper($blotter->file_type ?? '')); ?></div>
                    </div>
                    <a href="<?php echo e(asset('storage/'.$blotter->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="<?php echo e(route('blotter.edit', $blotter)); ?>" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Case
                </a>
                <?php if(in_array($blotter->status, ['Active','Under Investigation'])): ?>
                <form method="POST" action="<?php echo e(route('blotter.update', $blotter)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="status" value="Settled">
                    <input type="hidden" name="incident_type" value="<?php echo e($blotter->incident_type); ?>">
                    <input type="hidden" name="incident_date" value="<?php echo e($blotter->incident_date?->format('Y-m-d')); ?>">
                    <input type="hidden" name="incident_location" value="<?php echo e($blotter->incident_location); ?>">
                    <input type="hidden" name="incident_details" value="<?php echo e($blotter->incident_details); ?>">
                    <input type="hidden" name="complainant_name" value="<?php echo e($blotter->complainant_name); ?>">
                    <input type="hidden" name="respondent_name" value="<?php echo e($blotter->respondent_name); ?>">
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-handshake"></i> Mark as Settled
                    </button>
                </form>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('blotter.destroy', $blotter)); ?>"
                      data-confirm="Delete case <?php echo e($blotter->case_number); ?>? This cannot be recovered."
                      data-confirm-title="Delete Blotter Case"
                      data-confirm-ok="Delete Case">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete Case
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-lines"></i> Incident Details</span>
            </div>
            <div class="card-body">
                <div style="font-size:15px;color:var(--text);line-height:1.8;white-space:pre-line">
                    <?php echo e($blotter->incident_details ?? 'No details recorded.'); ?>

                </div>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-user"></i> Complainant</span>
                </div>
                <div class="card-body">
                    <?php if($blotter->complainantResident): ?>
                    <a href="<?php echo e(route('residents.show', $blotter->complainantResident)); ?>"
                       style="display:flex;align-items:center;gap:10px;margin-bottom:14px;color:var(--navy)">
                        <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;color:#fff">
                            <?php echo e(strtoupper(substr($blotter->complainantResident->first_name, 0, 1))); ?>

                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px"><?php echo e($blotter->complainantResident->full_name); ?></div>
                            <div class="td-muted">Registered Resident</div>
                        </div>
                    </a>
                    <?php else: ?>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-muted)">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px"><?php echo e($blotter->complainant_name ?? '—'); ?></div>
                            <div class="td-muted">External</div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <?php if($blotter->complainant_address): ?>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Address</div>
                            <div style="font-size:14px;color:var(--text-muted)"><?php echo e($blotter->complainant_address); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if($blotter->complainant_contact): ?>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Contact</div>
                            <div style="font-size:14px;color:var(--text-muted)"><?php echo e($blotter->complainant_contact); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-user-slash"></i> Respondent</span>
                </div>
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-muted)">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px"><?php echo e($blotter->respondent_name ?? '—'); ?></div>
                            <div class="td-muted">Respondent</div>
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <?php if($blotter->respondent_address): ?>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Address</div>
                            <div style="font-size:14px;color:var(--text-muted)"><?php echo e($blotter->respondent_address); ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if($blotter->respondent_contact): ?>
                        <div>
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Contact</div>
                            <div style="font-size:14px;color:var(--text-muted)"><?php echo e($blotter->respondent_contact); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if($blotter->resolution_notes): ?>
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-clipboard-check"></i> Resolution Notes</span>
            </div>
            <div class="card-body">
                <div style="font-size:15px;color:var(--text);line-height:1.8;white-space:pre-line">
                    <?php echo e($blotter->resolution_notes); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>


<div class="print-only" id="blotter-report">
<?php
    $officialName  = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'ROBERT S. ROMANO';
    $secretaryName = \App\Models\Official::where('position','Barangay Secretary')->where('is_active',true)->first()?->full_name ?? 'JOSEPHINE A. FLORES';
?>
<style>
@media print {
    @page { size: letter; margin: 0.4in 0.6in; }
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    .sidebar, .topbar, .watermark { display: none !important; }
}
.print-only { display: none; }

.rpt-page {
    font-family: 'Times New Roman', Times, serif;
    color: #000;
    font-size: 10.5pt;
    line-height: 1.6;
    width: 100%;
    max-width: 6.9in;
    margin: 0 auto;
}
.rpt-border {
    border: 2px solid #1a3a6b;
    padding: 18px 28px;
    position: relative;
}
.rpt-border::before {
    content: '';
    position: absolute;
    top: 4px; left: 4px; right: 4px; bottom: 4px;
    border: 1px solid #c8861a;
    pointer-events: none;
}
.rpt-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 8px;
    border-bottom: 2px solid #1a3a6b;
    margin-bottom: 10px;
}
.rpt-logo { width: 58px; height: 58px; object-fit: contain; flex-shrink: 0; }
.rpt-titles { text-align: center; flex: 1; padding: 0 12px; }
.rpt-titles .rep  { font-size: 9.5pt; font-style: italic; }
.rpt-titles .prov { font-size: 9pt; }
.rpt-titles .brgy { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.06em; color: #0d2144; }
.rpt-titles .off  { font-size: 9.5pt; font-style: italic; }
.rpt-titles .addr { font-size: 8.5pt; color: #444; }

.rpt-doc-title {
    text-align: center;
    font-size: 14pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #0d2144;
    text-decoration: underline;
    margin: 8px 0 2px;
}
.rpt-case-num {
    text-align: center;
    font-size: 10pt;
    color: #555;
    margin-bottom: 10px;
}

.rpt-section-title {
    font-size: 10pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    background: #0d2144;
    color: #fff;
    padding: 4px 10px;
    margin: 10px 0 6px;
}

.rpt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border: 1px solid #ccc;
    margin-bottom: 2px;
}
.rpt-field {
    padding: 4px 8px;
    border-bottom: 1px solid #e0e0e0;
    border-right: 1px solid #e0e0e0;
}
.rpt-field:nth-child(even) { border-right: none; }
.rpt-field .lbl { font-size: 8.5pt; font-weight: bold; color: #555; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px; }
.rpt-field .val { font-size: 10.5pt; }

.rpt-fullwidth {
    border: 1px solid #ccc;
    padding: 5px 8px;
    margin-bottom: 2px;
}
.rpt-fullwidth .lbl { font-size: 8.5pt; font-weight: bold; color: #555; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 4px; }
.rpt-fullwidth .val { font-size: 10.5pt; line-height: 1.7; white-space: pre-line; }

.rpt-sig-area {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 12px;
}
.rpt-sig-box { text-align: center; flex: 1; min-width: 0; }
.rpt-sig-line { border-top: 1px solid #000; margin-bottom: 4px; }
.rpt-sig-name { font-size: 10pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.03em; line-height: 1.3; word-break: break-word; overflow-wrap: break-word; }
.rpt-sig-title { font-size: 9pt; color: #333; }

.rpt-footer {
    margin-top: 20px;
    padding-top: 10px;
    border-top: 1px dashed #aaa;
    font-size: 8pt;
    color: #666;
    display: flex;
    justify-content: space-between;
}
</style>

<div class="rpt-page">
<div class="rpt-border">

    
    <div class="rpt-header">
        <img src="<?php echo e(asset('images/qc-seal.png')); ?>" class="rpt-logo" alt="QC Seal">
        <div class="rpt-titles">
            <div class="rep"><em>Republic of the Philippines</em></div>
            <div class="prov">City of Quezon, National Capital Region</div>
            <div class="brgy">Barangay New Era</div>
            <div class="off">Office of the Punong Barangay</div>
            <div class="addr">New Era, Quezon City, Metro Manila</div>
        </div>
        <img src="<?php echo e(asset('images/bne-logo.png')); ?>" class="rpt-logo" alt="BNE Seal">
    </div>

    <div class="rpt-doc-title">Barangay Blotter Report</div>
    <div class="rpt-case-num">Case No.: <?php echo e($blotter->case_number); ?></div>

    
    <div class="rpt-section-title">Case Information</div>
    <div class="rpt-grid">
        <div class="rpt-field">
            <span class="lbl">Case Number</span>
            <span class="val"><?php echo e($blotter->case_number); ?></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Incident Type</span>
            <span class="val"><?php echo e($blotter->incident_type); ?></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Incident Date</span>
            <span class="val"><?php echo e($blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('F d, Y') : '—'); ?></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Status</span>
            <span class="val"><strong><?php echo e($blotter->status); ?></strong></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Date Filed</span>
            <span class="val"><?php echo e($blotter->created_at->format('F d, Y')); ?></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Filed By</span>
            <span class="val"><?php echo e($blotter->filedBy->name ?? '—'); ?></span>
        </div>
    </div>
    <div class="rpt-fullwidth">
        <span class="lbl">Incident Location</span>
        <span class="val"><?php echo e($blotter->incident_location ?? '—'); ?></span>
    </div>

    
    <div class="rpt-section-title">Parties Involved</div>
    <div class="rpt-grid">
        <div class="rpt-field">
            <span class="lbl">Complainant</span>
            <span class="val"><strong><?php echo e($blotter->complainant_name ?? ($blotter->complainantResident?->full_name ?? '—')); ?></strong></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Respondent</span>
            <span class="val"><strong><?php echo e($blotter->respondent_name ?? '—'); ?></strong></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Complainant Address</span>
            <span class="val"><?php echo e($blotter->complainant_address ?? '—'); ?></span>
        </div>
        <div class="rpt-field">
            <span class="lbl">Respondent Address</span>
            <span class="val"><?php echo e($blotter->respondent_address ?? '—'); ?></span>
        </div>
        <div class="rpt-field" style="border-bottom:none">
            <span class="lbl">Complainant Contact</span>
            <span class="val"><?php echo e($blotter->complainant_contact ?? '—'); ?></span>
        </div>
        <div class="rpt-field" style="border-bottom:none">
            <span class="lbl">Respondent Contact</span>
            <span class="val"><?php echo e($blotter->respondent_contact ?? '—'); ?></span>
        </div>
    </div>

    
    <div class="rpt-section-title">Incident Details / Narrative</div>
    <div class="rpt-fullwidth">
        <span class="val"><?php echo e($blotter->incident_details ?? 'No details recorded.'); ?></span>
    </div>

    
    <?php if($blotter->resolution_notes || $blotter->settled_at): ?>
    <div class="rpt-section-title">Resolution</div>
    <div class="rpt-grid">
        <?php if($blotter->settled_at): ?>
        <div class="rpt-field" style="border-bottom:none">
            <span class="lbl">Date Settled</span>
            <span class="val"><?php echo e($blotter->settled_at->format('F d, Y')); ?></span>
        </div>
        <div class="rpt-field" style="border-bottom:none">
            <span class="lbl">Final Status</span>
            <span class="val"><?php echo e($blotter->status); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php if($blotter->resolution_notes): ?>
    <div class="rpt-fullwidth">
        <span class="lbl">Resolution Notes</span>
        <span class="val"><?php echo e($blotter->resolution_notes); ?></span>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    <div class="rpt-sig-area">
        <div class="rpt-sig-box">
            <div style="height:36px"></div>
            <div class="rpt-sig-line"></div>
            <div class="rpt-sig-name"><?php echo e($blotter->complainant_name ?? '—'); ?></div>
            <div class="rpt-sig-title">Complainant</div>
        </div>
        <div class="rpt-sig-box">
            <div style="height:36px"></div>
            <div class="rpt-sig-line"></div>
            <div class="rpt-sig-name"><?php echo e($blotter->respondent_name ?? '—'); ?></div>
            <div class="rpt-sig-title">Respondent</div>
        </div>
        <div class="rpt-sig-box">
            <div style="height:36px"></div>
            <div class="rpt-sig-line"></div>
            <div class="rpt-sig-name"><?php echo e($secretaryName); ?></div>
            <div class="rpt-sig-title">Barangay Secretary</div>
        </div>
        <div class="rpt-sig-box">
            <div style="height:36px"></div>
            <div class="rpt-sig-line"></div>
            <div class="rpt-sig-name"><?php echo e($officialName); ?></div>
            <div class="rpt-sig-title">Punong Barangay</div>
        </div>
    </div>

    
    <div class="rpt-footer">
        <span>Printed by: <?php echo e(auth()->user()->name); ?> — <?php echo e(now()->format('F d, Y \a\t h:i A')); ?></span>
        <span>Barangay New Era BMS</span>
    </div>

</div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/blotter/blotter-show.blade.php ENDPATH**/ ?>