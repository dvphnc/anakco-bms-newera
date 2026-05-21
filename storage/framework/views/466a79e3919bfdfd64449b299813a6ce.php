<?php $__env->startSection('title', $document->doc_number); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header no-print">
    <div>
        <h1 class="page-title">Document Details</h1>
        <p class="page-subtitle"><?php echo e($document->doc_number); ?> — <?php echo e($document->document_type); ?></p>
    </div>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Certificate
        </button>
        <a href="<?php echo e(route('documents.edit', $document)); ?>" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>


<div class="no-print" style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-file-alt" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div class="td-mono" style="color:rgba(255,255,255,0.5);font-size:12px;margin-bottom:4px"><?php echo e($document->doc_number); ?></div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.3;margin-bottom:10px"><?php echo e($document->document_type); ?></div>
                <?php
                    $cls = match($document->status) {
                        'Pending'    => 'badge-yellow',
                        'Confirmed'  => 'badge-navy',
                        'Processing' => 'badge-blue',
                        'Ready'      => 'badge-green',
                        'Released'   => 'badge-gray',
                        'Cancelled'  => 'badge-red',
                        default      => 'badge-gray'
                    };
                ?>
                <span class="badge <?php echo e($cls); ?>"><?php echo e($document->status); ?></span>
            </div>
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Resident</div>
                <?php if($document->resident): ?>
                <a href="<?php echo e(route('residents.show', $document->resident)); ?>"
                   style="display:flex;align-items:center;gap:10px;color:var(--navy)">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;color:#fff;font-size:13px">
                        <?php echo e(strtoupper(substr($document->resident->first_name, 0, 1))); ?>

                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px"><?php echo e($document->resident->full_name); ?></div>
                        <div class="td-muted"><?php echo e($document->resident->purok->name ?? ''); ?></div>
                    </div>
                </a>
                <?php else: ?>
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;border-radius:50%;background:rgba(200,134,26,0.15);border:1px solid rgba(200,134,26,0.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:13px">
                        <i class="fas fa-globe" style="color:var(--gold)"></i>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px"><?php echo e($document->resident_name_portal ?? '—'); ?></div>
                        <div class="td-muted">Portal Submission</div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="<?php echo e(route('documents.edit', $document)); ?>" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Document
                </a>
                <?php if(in_array($document->status, ['Pending', 'Confirmed', 'Processing', 'Ready'])): ?>
                <form method="POST" action="<?php echo e(route('documents.update', $document)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="status" value="Released">
                    <input type="hidden" name="resident_id" value="<?php echo e($document->resident_id); ?>">
                    <input type="hidden" name="document_type" value="<?php echo e($document->document_type); ?>">
                    <input type="hidden" name="purpose" value="<?php echo e($document->purpose ?? 'Portal Request'); ?>">
                    <input type="hidden" name="requestor_name" value="<?php echo e($document->requestor_name); ?>">
                    <input type="hidden" name="requestor_relationship" value="<?php echo e($document->requestor_relationship); ?>">
                    <input type="hidden" name="requestor_contact" value="<?php echo e($document->requestor_contact); ?>">
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-circle-check"></i> Mark as Released
                    </button>
                </form>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('documents.destroy', $document)); ?>"
                      data-confirm="Delete document <?php echo e($document->doc_number); ?>? This cannot be recovered."
                      data-confirm-title="Delete Document"
                      data-confirm-ok="Delete">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-circle-info"></i> Document Information</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                <?php
                    // Requestor display
                    $resFullName  = $document->resident?->full_name ?? $document->resident_name_portal ?? '';
                    $reqName      = $document->requestor_name ?? '';
                    $isRepDoc     = $reqName !== '' && $reqName !== $resFullName;
                    $reqDisplay   = $reqName ?: '—';
                    $relDisplay   = $document->requestor_relationship ?? '—';
                    $contDisplay  = $document->requestor_contact ?? '—';

                    $details = [
                        ['label'=>'Document No.',     'value'=>$document->doc_number],
                        ['label'=>'Document Type',    'value'=>$document->document_type],
                        ['label'=>'Status',           'value'=>$document->status],
                        ['label'=>'Purpose',          'value'=>$document->purpose ?? '—'],
                        ['label'=>'Fee',              'value'=>($document->fee_paid ?? 0) > 0 ? '₱'.number_format($document->fee_paid,2) : 'Free'],
                        ['label'=>'OR Number',        'value'=>$document->or_number ?? '—'],
                        ['label'=>'Issued By',        'value'=>$document->issuedBy->name ?? '—'],
                        ['label'=>'Date Requested',   'value'=>$document->created_at->format('F d, Y')],
                        ['label'=>'Date Released',    'value'=>$document->released_at?->format('F d, Y') ?? '—'],
                        ['label'=>'Received By',      'value'=>$reqDisplay.($isRepDoc ? '' : ' (resident)')],
                        ['label'=>'Relationship',     'value'=>$isRepDoc ? $relDisplay : '—'],
                        ['label'=>'Rep. Contact',     'value'=>$isRepDoc ? $contDisplay : '—'],
                    ];
                ?>
                <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="padding:10px 0;border-bottom:1px solid var(--border);<?php echo e($loop->even ? 'padding-left:24px' : ''); ?>">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px"><?php echo e($d['label']); ?></div>
                    <div style="font-size:15px;color:var(--text);font-weight:500"><?php echo e($d['value']); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

</div>


<div class="print-only" id="certificate">
<style>
@media print {
    @page {
        size: letter;
        margin: 0.5in 0.7in;
    }

    /* Hide everything on the page first */
    body * { visibility: hidden; }

    /* Then show only the certificate and its children */
    #certificate, #certificate * { visibility: visible; }

    /* Pull the certificate to the very top-left of the page,
       bypassing any sidebar offsets or layout margins */
    #certificate {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .no-print { display: none !important; }
    .print-only { display: block !important; }
}
.print-only { display: none; }

.cert-page {
    font-family: 'Times New Roman', Times, serif;
    color: #000;
    width: 100%;
    max-width: 7in;
    margin: 0 auto;
    position: relative;
}

/* Decorative border */
.cert-border {
    border: 3px double #1a3a6b;
    padding: 32px 40px;
    position: relative;
}
.cert-border::before {
    content: '';
    position: absolute;
    top: 5px; left: 5px; right: 5px; bottom: 5px;
    border: 1px solid #c8861a;
    pointer-events: none;
}

/* Header */
.cert-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 12px;
    border-bottom: 2px solid #1a3a6b;
}
.cert-logo {
    width: 75px;
    height: 75px;
    object-fit: contain;
    flex-shrink: 0;
}
.cert-logo-placeholder {
    width: 75px;
    height: 75px;
    border: 2px solid #1a3a6b;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    text-align: center;
    color: #1a3a6b;
    flex-shrink: 0;
}
.cert-titles {
    text-align: center;
    flex: 1;
    padding: 0 16px;
}
.cert-republic {
    font-size: 10pt;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}
.cert-province {
    font-size: 9pt;
    margin-bottom: 2px;
}
.cert-barangay {
    font-size: 17pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #0d2144;
    margin-bottom: 2px;
}
.cert-address {
    font-size: 8.5pt;
    color: #444;
}
.cert-docnum {
    font-size: 8pt;
    text-align: right;
    color: #555;
    margin-top: 6px;
}

/* Document type title */
.cert-type-title {
    text-align: center;
    margin: 20px 0 6px;
    font-size: 17pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #0d2144;
    text-decoration: underline;
}

/* Body */
.cert-body {
    font-size: 11pt;
    line-height: 1.9;
    text-align: justify;
    margin: 16px 0 20px;
}
.cert-body .resident-name {
    font-weight: bold;
    text-transform: uppercase;
    text-decoration: underline;
    font-size: 12pt;
}
.cert-body .highlight {
    font-weight: bold;
}

/* Footer */
.cert-footer {
    margin-top: 28px;
}
.cert-issued-at {
    font-size: 10pt;
    margin-bottom: 24px;
    font-style: italic;
}
.cert-sig-area {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 8px;
}
.cert-sig-left {
    font-size: 9pt;
    color: #444;
}
.cert-sig-right {
    text-align: center;
    min-width: 220px;
}
.cert-sig-line {
    border-top: 1px solid #000;
    margin-bottom: 4px;
    width: 100%;
}
.cert-punong {
    font-size: 10pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    word-break: break-word;
    overflow-wrap: break-word;
    line-height: 1.3;
}
.cert-punong-title {
    font-size: 9pt;
    color: #333;
}
.cert-or {
    margin-top: 24px;
    padding-top: 10px;
    border-top: 1px dashed #999;
    font-size: 8.5pt;
    color: #555;
    display: flex;
    gap: 32px;
}
.cert-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    opacity: 0.04;
    width: 280px;
    height: 280px;
    object-fit: contain;
    pointer-events: none;
    z-index: 0;
}
.cert-content-wrap {
    position: relative;
    z-index: 1;
}
</style>

<?php
    $resident     = $document->resident;
    $purok        = $resident?->purok?->name ?? 'Barangay New Era';
    $address      = $resident?->address ?? 'Barangay New Era, Quezon City';
    // Strip any trailing barangay/city from the stored address to avoid duplication
    // since all templates already append ", Barangay New Era, Quezon City"
    $addrStreet   = trim(preg_replace('/[,\s]*(Barangay\s+)?New Era[,\s]*(Quezon City)?[,\s]*(Metro Manila)?[,\s]*$/i', '', $address), ', ');
    if (empty($addrStreet)) $addrStreet = $address;
    $fullName     = $resident?->full_name ?? $document->resident_name_portal ?? '—';
    $age          = $resident?->age ? $resident->age . ' years old' : 'of legal age';
    $civilStatus  = $resident?->civil_status ? strtolower($resident->civil_status) . ', ' : '';
    $gender       = $resident?->gender ?? 'Male';
    $heShe        = $gender === 'Female' ? 'She' : 'He';
    $hisHer       = $gender === 'Female' ? 'her' : 'his';
    $birthdate    = $resident?->birthdate ? $resident->birthdate->format('F d, Y') : null;
    $issuedDate   = $document->released_at ?? $document->created_at;
    $purpose      = $document->purpose ?? 'whatever legal purpose it may serve';
    $officialName  = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'ROBERT S. ROMANO';
    $secretaryName = \App\Models\Official::where('position','Barangay Secretary')->where('is_active',true)->first()?->full_name ?? 'JOSEPHINE A. FLORES';

    // Build certificate body based on document type
    $certBody = match($document->document_type) {
        'Barangay Clearance' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, $age, $civilStatus".
            "Filipino citizen, and a <span class='highlight'>bonafide resident</span> of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>, ".
            "has been known to be of <span class='highlight'>good moral character</span> ".
            "and has no derogatory record on file in this barangay as of this date.",

        'Certificate of Indigency' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, $age, $civilStatus".
            "a resident of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>, ".
            "belongs to an <span class='highlight'>indigent family</span> in this barangay. ".
            "This certification is issued upon the request of the aforementioned person ".
            "for the purpose of <span class='highlight'>".e($purpose)."</span>.",

        'Certificate of Residency' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, $age, $civilStatus".
            "is a <span class='highlight'>bonafide resident</span> of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>. ".
            "$heShe has been residing in this barangay for a considerable period of time ".
            "and is personally known to the undersigned.",

        'Good Moral Character' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, $age, $civilStatus".
            "a resident of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>, ".
            "is personally known to us and is a person of <span class='highlight'>good moral character, ".
            "good standing, and a reputable member</span> of this community. ".
            "No derogatory record has been filed against $hisHer in this office.",

        'Business Clearance' =>
            "This is to certify that the business establishment owned/operated by ".
            "<span class='resident-name'>$fullName</span>, located at <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>, ".
            "has been granted <span class='highlight'>barangay clearance</span> to operate within the jurisdiction ".
            "of Barangay New Era, Quezon City for the purpose of <span class='highlight'>".e($purpose)."</span>.",

        'First Time Job Seeker' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, $age, $civilStatus".
            "a resident of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>, ".
            "is a <span class='highlight'>first-time job seeker</span> as defined under Republic Act No. 11261. ".
            "$heShe is hereby entitled to the privileges and exemptions provided under the said Act. ".
            "This certification is issued for employment application purposes.",

        'Barangay ID' =>
            "This is to certify that <span class='resident-name'>$fullName</span>".
            ($birthdate ? ", born on <span class='highlight'>$birthdate</span>," : ", $age,").
            " is a registered resident of <span class='highlight'>$addrStreet, Barangay New Era, District VI, Quezon City</span>. ".
            "This identification is issued by the Office of the Punong Barangay.",

        'Certificate of Live Birth' =>
            "This is to certify that based on records available in this barangay, ".
            "<span class='resident-name'>$fullName</span>".
            ($birthdate ? ", born on <span class='highlight'>$birthdate</span>," : "").
            " is a registered resident of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>.",

        default =>
            "This is to certify that <span class='resident-name'>$fullName</span>, $age, $civilStatus".
            "a resident of <span class='highlight'>$addrStreet, Barangay New Era, Quezon City</span>, ".
            "has requested this certification for the purpose of ".
            "<span class='highlight'>".e($purpose)."</span>.",
    };
?>

<div class="cert-page">
    <div class="cert-border">

        
        <img src="<?php echo e(asset('images/bne-logo.png')); ?>" class="cert-watermark" alt="">

        <div class="cert-content-wrap">

            
            <div class="cert-header">
                <img src="<?php echo e(asset('images/qc-seal.png')); ?>" class="cert-logo" alt="Quezon City Seal">
                <div class="cert-titles">
                    <div class="cert-republic"><em>Republic of the Philippines</em></div>
                    <div class="cert-province">City of Quezon, National Capital Region</div>
                    <div class="cert-barangay">Barangay New Era</div>
                    <div class="cert-office" style="font-size:10pt;font-style:italic">Office of the Punong Barangay</div>
                    <div class="cert-address">New Era, Quezon City, Metro Manila</div>
                </div>
                <img src="<?php echo e(asset('images/bne-logo.png')); ?>" class="cert-logo" alt="Barangay New Era Seal">
            </div>

            <div class="cert-docnum">Doc No.: <?php echo e($document->doc_number); ?></div>

            
            <div class="cert-type-title"><?php echo e($document->document_type); ?></div>

            <div style="text-align:center;font-size:9.5pt;letter-spacing:0.15em;color:#555;margin-bottom:8px">
                ✦ &nbsp; TO WHOM IT MAY CONCERN &nbsp; ✦
            </div>

            
            <div class="cert-body">
                <p style="text-indent:48px"><?php echo $certBody; ?></p>

                <p style="text-indent:48px;margin-top:12px">
                    This certification is issued upon the request of the above-named person
                    for the purpose of <span class="highlight"><?php echo e($document->purpose ?? 'whatever legal purpose it may serve'); ?></span>
                    and is valid only for the purpose stated herein.
                </p>
            </div>

            
            <div class="cert-footer">
                <div class="cert-issued-at">
                    Issued this <span class="highlight"><?php echo e($issuedDate->format('jS')); ?> day of <?php echo e($issuedDate->format('F, Y')); ?></span>
                    at Barangay New Era, Quezon City.
                </div>

                <div class="cert-sig-area" style="display:block">
                    
                    <div style="display:flex;align-items:flex-end;gap:10px;margin-bottom:20px">
                        <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                            <?php if($resident?->photo_path): ?>
                                <img src="<?php echo e(asset('storage/'.$resident->photo_path)); ?>"
                                     style="width:65px;height:80px;object-fit:cover;border:1px solid #000;display:block">
                            <?php else: ?>
                                <div style="width:65px;height:80px;border:1px solid #000;display:flex;align-items:center;justify-content:center;font-size:6.5pt;text-align:center;color:#666;line-height:1.3">
                                    APPLICANT<br>PHOTO
                                </div>
                            <?php endif; ?>
                            <div style="font-size:6pt;margin-top:3px;text-transform:uppercase;letter-spacing:0.04em;text-align:center;width:65px">Applicant Photo</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0">
                            <div style="width:65px;height:80px;border:1px solid #000;display:flex;align-items:center;justify-content:center;font-size:6.5pt;text-align:center;color:#666;line-height:1.3">
                                APPLICANT<br>THUMBMARK
                            </div>
                            <div style="font-size:6pt;margin-top:3px;text-transform:uppercase;letter-spacing:0.04em;text-align:center;width:65px">Applicant Thumbmark</div>
                        </div>
                        <div style="flex:1;display:flex;flex-direction:column;justify-content:flex-end;padding-bottom:20px;margin-left:8px">
                            <div style="font-size:8.5pt;margin-bottom:26px">Applicant's Signature:</div>
                            <div style="border-top:1px solid #000;width:100%;max-width:280px"></div>
                        </div>
                    </div>
                    
                    <div style="display:flex;justify-content:space-between;align-items:flex-end;gap:20px">
                        
                        <div style="text-align:center;flex:1">
                            <div style="height:56px"></div>
                            <div style="border-top:1px solid #000;margin-bottom:3px"></div>
                            <div class="cert-punong"><?php echo e($secretaryName); ?></div>
                            <div class="cert-punong-title">Barangay Secretary</div>
                        </div>
                        
                        <div style="text-align:center;flex:1">
                            <div style="height:56px"></div>
                            <div style="border-top:1px solid #000;margin-bottom:3px"></div>
                            <div class="cert-punong"><?php echo e($officialName); ?></div>
                            <div class="cert-punong-title">Punong Barangay</div>
                        </div>
                    </div>
                </div>
                <div style="margin-top:16px;font-size:8pt;color:#555;border-top:1px solid #ccc;padding-top:8px">
                    <div style="margin-bottom:3px">Community Tax Certificate No.: _______________ &nbsp;&nbsp; Issued at: _______________ &nbsp;&nbsp; Date: _______________</div>
                    <div style="font-style:italic;color:#777;font-size:7.5pt">
                        &#9679; This certification document is not valid without the official barangay dry seal and Punong Barangay signature/stamp.<br>
                        &#9679; Officials and applicants who will submit false certification or documents shall be held liable for administrative/criminal liabilities.
                    </div>
                </div>

                <?php
                    $certResName  = $resident?->full_name ?? $document->resident_name_portal ?? '';
                    $certReqName  = $document->requestor_name ?? '';
                    $certIsRep    = $certReqName !== '' && $certReqName !== $certResName;
                    $certRelLabel = $document->requestor_relationship ? ' ('.$document->requestor_relationship.')' : '';
                ?>

                
                <?php if($certIsRep): ?>
                <div style="margin-top:14px;padding:8px 12px;border:1px solid #c8861a;border-radius:4px;background:#fffbf3;font-size:8.5pt">
                    <strong>Document Received By:</strong>
                    <?php echo e($certReqName); ?><?php echo e($certRelLabel); ?>

                    <?php if($document->requestor_contact): ?>
                        &nbsp;|&nbsp; <?php echo e($document->requestor_contact); ?>

                    <?php endif; ?>
                    <span style="float:right">Signature: _____________________</span>
                </div>
                <?php endif; ?>

                <div class="cert-or">
                    <span>O.R. No.: <?php echo e($document->or_number ?? "_______________"); ?></span>
                    <span>Amount Paid: ₱<?php echo e(number_format($document->fee_paid ?? 0, 2)); ?></span>
                    <span>Date: <?php echo e($issuedDate->format('m/d/Y')); ?></span>
                    <span style="margin-left:auto">Prepared by: _______________</span>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/documents/documents-show.blade.php ENDPATH**/ ?>