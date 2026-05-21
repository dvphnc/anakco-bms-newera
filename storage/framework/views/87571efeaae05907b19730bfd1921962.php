<?php $__env->startSection('title', 'Portal Appointments'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Portal Appointments</h1>
        <p class="page-subtitle">Track pick-up dates and request statuses from the Resident Portal</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-secondary" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Portal
        </a>
    </div>
</div>

<?php
    $pendingCount   = \App\Models\DocumentAppointment::where('status', 'Pending')->count();
    $readyCount     = \App\Models\DocumentAppointment::where('status', 'Ready')->count();
    $releasedCount  = \App\Models\DocumentAppointment::where('status', 'Released')->count();
    $totalCount     = \App\Models\DocumentAppointment::count();
    $bizPending     = \App\Models\Business::where('source', 'portal')->whereIn('status', ['Pending', 'For Review'])->count();
    $blotterPending = \App\Models\BlotterCase::where('source', 'portal')->where('status', 'Pending')->count();
?>


<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-file-lines"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statApptTotal"><?php echo e(number_format($totalCount)); ?></div>
            <div class="stat-label">Document Requests</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="switchTab('documents');aptQuickStatus('Pending')">
        <div class="stat-icon" style="background:rgba(200,134,26,0.10);color:var(--gold)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptPending"><?php echo e(number_format($pendingCount)); ?></div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="switchTab('documents');aptQuickStatus('Ready')">
        <div class="stat-icon" style="background:rgba(22,163,74,0.10);color:#16a34a"><i class="fas fa-box-open"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptReady"><?php echo e(number_format($readyCount)); ?></div>
            <div class="stat-label">Ready for Pick-up</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="switchTab('documents');aptQuickStatus('Released')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptReleased"><?php echo e(number_format($releasedCount)); ?></div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>


<div class="card" style="overflow:hidden">

    
    <div class="apt-tabbar-wrap">
        <div class="apt-tabbar" id="aptTabBar">
            <button class="apt-tab active" data-tab="documents" onclick="switchTab('documents')">
                <i class="fas fa-file-lines"></i>
                Document Requests
                <span class="apt-tab-badge" id="tabBadgeDocs"><?php echo e($totalCount); ?></span>
            </button>
            <button class="apt-tab" data-tab="business" onclick="switchTab('business')">
                <i class="fas fa-store"></i>
                Business Permits
                <span class="apt-tab-badge" id="tabBadgeBiz"><?php echo e($bizPending); ?></span>
            </button>
            <button class="apt-tab" data-tab="blotter" onclick="switchTab('blotter')">
                <i class="fas fa-shield-halved"></i>
                Blotter Reports
                <span class="apt-tab-badge" id="tabBadgeBlotter"><?php echo e($blotterPending); ?></span>
            </button>
        </div>
        <button class="apt-tab-more" id="aptTabMore" onclick="scrollTabBar()" title="Scroll tabs">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    
    <div id="panelDocuments" class="apt-panel">
        <div class="apt-toolbar">
            <div style="position:relative;flex:1;max-width:320px">
                <i class="fas fa-search apt-search-icon"></i>
                <input type="text" id="docSearch" class="apt-search"
                       placeholder="Name, appt. no., document type…">
            </div>
            <select id="docStatusFilter" class="apt-select" style="min-width:145px">
                <option value="">All statuses</option>
                <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>"><?php echo e($s); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select id="docTypeFilter" class="apt-select" style="min-width:165px">
                <option value="">All document types</option>
                <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($dt); ?>"><?php echo e($dt); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="button" class="btn btn-secondary btn-sm" onclick="resetDocFilters()">
                <i class="fas fa-xmark"></i> Reset
            </button>
        </div>
        <div class="table-responsive">
            <table id="appointmentsTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Resident</th>
                        <th>Document Type</th>
                        <th>Date Requested</th>
                        <th>Pick-up Date</th>
                        <th>Status</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    
    <div id="panelBusiness" class="apt-panel" style="display:none">
        <div class="apt-toolbar">
            <div style="position:relative;flex:1;max-width:320px">
                <i class="fas fa-search apt-search-icon"></i>
                <input type="text" id="bizSearch" class="apt-search"
                       placeholder="Business name, owner, permit no.…">
            </div>
            <select id="bizStatusFilter" class="apt-select" style="min-width:145px">
                <option value="">All statuses</option>
                <option value="Pending">Pending</option>
                <option value="For Review">For Review</option>
                <option value="Active">Active</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <button type="button" class="btn btn-secondary btn-sm" onclick="resetBizFilters()">
                <i class="fas fa-xmark"></i> Reset
            </button>
        </div>
        <div class="table-responsive">
            <table id="bizTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Owner</th>
                        <th>Business</th>
                        <th>Type</th>
                        <th>Appt. Date</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    
    <div id="panelBlotter" class="apt-panel" style="display:none">
        <div class="apt-toolbar">
            <div style="position:relative;flex:1;max-width:320px">
                <i class="fas fa-search apt-search-icon"></i>
                <input type="text" id="blotterSearch" class="apt-search"
                       placeholder="Complainant, case no., incident type…">
            </div>
            <select id="blotterStatusFilter" class="apt-select" style="min-width:145px">
                <option value="">All statuses</option>
                <option value="Pending">Pending</option>
                <option value="Active">Active</option>
                <option value="Under Investigation">Under Investigation</option>
                <option value="Mediated">Mediated</option>
                <option value="Settled">Settled</option>
                <option value="Closed">Closed</option>
            </select>
            <button type="button" class="btn btn-secondary btn-sm" onclick="resetBlotterFilters()">
                <i class="fas fa-xmark"></i> Reset
            </button>
        </div>
        <div class="table-responsive">
            <table id="blotterTable" style="width:100%">
                <thead>
                    <tr>
                        <th>Complainant</th>
                        <th>Incident Type</th>
                        <th>Location / Date</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th style="text-align:right">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>



<div id="aptConvertModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeConvertModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-file-circle-check" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Issue Document Record</span>
            </div>
            <button onclick="closeConvertModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:12px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Appointment No.</div>
                        <div id="cvtNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Document Type</div>
                        <div id="cvtType" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Resident</div>
                        <div id="cvtName" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div id="cvtPurposeRow" style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Purpose</div>
                        <div id="cvtPurpose" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Fee Paid (₱) <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="number" id="cvtFee" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">O.R. Number <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="text" id="cvtOR" class="form-control" placeholder="e.g. 2026-00123">
                </div>
            </div>
            <div id="cvtError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Creates a <strong style="color:var(--navy)">Released</strong> document record in Document Issuance and automatically marks this appointment as Released.</span>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeConvertModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="cvtSaveBtn" onclick="saveConvert()" class="btn btn-primary">
                    <i class="fas fa-file-circle-check"></i> Issue Document
                </button>
            </div>
        </div>
    </div>
</div>


<div id="bizIssueModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBizIssueModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:480px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-stamp" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Issue Business Permit</span>
            </div>
            <button onclick="closeBizIssueModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:10px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Permit No.</div>
                        <div id="bizIssueNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Appointment Date</div>
                        <div id="bizIssueAppt" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Business</div>
                        <div id="bizIssueBiz" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Owner</div>
                        <div id="bizIssueOwner" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Permit Date <span style="color:var(--crimson)">*</span></label>
                    <input type="date" id="bizIssuePermitDate" class="form-control">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Expiry Date <span style="color:var(--crimson)">*</span></label>
                    <input type="date" id="bizIssueExpiryDate" class="form-control">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Fee Paid (₱) <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="number" id="bizIssueFee" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">O.R. Number <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="text" id="bizIssueOR" class="form-control" placeholder="e.g. 2026-00123">
                </div>
            </div>
            <div id="bizIssueError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Sets the permit to <strong style="color:var(--navy)">Active</strong> and makes it visible as a real permit record in the Business Permits section.</span>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeBizIssueModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="bizIssueSaveBtn" onclick="saveBizIssue()" class="btn btn-success">
                    <i class="fas fa-stamp"></i> Issue Permit
                </button>
            </div>
        </div>
    </div>
</div>


<div id="blotterActivateModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBlotterActivateModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-shield-halved" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Activate Blotter Case</span>
            </div>
            <button onclick="closeBlotterActivateModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:10px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Case No.</div>
                        <div id="blotterActivateNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Incident Type</div>
                        <div id="blotterActivateType" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Complainant</div>
                        <div id="blotterActivateComplainant" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Incident Date</div>
                        <div id="blotterActivateDate" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:14px">
                <label class="form-label" style="font-size:12.5px">Staff Notes <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                <textarea id="blotterActivateNotes" class="form-control" rows="3"
                          placeholder="e.g., Verified with complainant; proceeding with mediation…"></textarea>
            </div>
            <div id="blotterActivateError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Sets the case to <strong style="color:var(--navy)">Active</strong> and makes it visible as a real case record in the Blotter section.</span>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeBlotterActivateModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="blotterActivateSaveBtn" onclick="saveBlotterActivate()" class="btn btn-success">
                    <i class="fas fa-shield-halved"></i> Activate Case
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* ── Page bg ── */
.main-content { background: #F8F9FA; }
.stat-card { background:#fff!important; box-shadow:0 1px 4px rgba(13,33,68,.07),0 4px 16px rgba(13,33,68,.04); }
.stat-label { font-size:12px; color:var(--text-subtle); font-weight:500; letter-spacing:.02em; }
.stat-number { font-size:28px; font-weight:700; color:var(--navy); line-height:1.1; }

/* ── Tab bar ── */
.apt-tabbar-wrap {
    display: flex;
    align-items: stretch;
    border-bottom: 1px solid var(--border);
    background: var(--surface);
    overflow: hidden;
}
.apt-tabbar {
    display: flex;
    flex: 1;
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.apt-tabbar::-webkit-scrollbar { display: none; }
.apt-tab {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 14px 20px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text-muted);
    white-space: nowrap;
    border-right: 1px solid var(--border);
    position: relative;
    transition: color .15s, background .15s;
    font-family: 'Poppins', sans-serif;
}
.apt-tab::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: transparent;
    transition: background .15s;
}
.apt-tab:hover { background: var(--surface2); color: var(--navy); }
.apt-tab.active { color: var(--navy); font-weight: 600; background: #fff; }
.apt-tab.active::after { background: var(--navy); }
.apt-tab-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 20px;
    padding: 0 6px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: var(--navy);
    color: #fff;
}
.apt-tab.active .apt-tab-badge {
    background: var(--gold);
    color: #fff;
}
.apt-tab-more {
    flex-shrink: 0;
    padding: 0 14px;
    border: none;
    background: none;
    cursor: pointer;
    color: var(--text-muted);
    border-left: 1px solid var(--border);
    font-size: 13px;
    transition: background .15s;
}
.apt-tab-more:hover { background: var(--surface2); color: var(--navy); }

/* ── Panel toolbar ── */
.apt-panel {}
.apt-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    background: #fafbfc;
    flex-wrap: wrap;
}
.apt-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-subtle);
    font-size: 12px;
    pointer-events: none;
}
.apt-search {
    width: 100%;
    height: 36px;
    padding: 0 10px 0 32px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text);
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
}
.apt-search:focus { outline: none; border-color: var(--navy); box-shadow: 0 0 0 2px rgba(13,33,68,.1); }
.apt-select {
    height: 36px;
    padding: 0 28px 0 10px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text);
    font-size: 13px;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    appearance: auto;
}
.apt-select:focus { outline: none; border-color: var(--navy); }

/* ── DataTables ── */
#appointmentsTable_wrapper .dataTables_length,
#appointmentsTable_wrapper .dataTables_filter,
#bizTable_wrapper .dataTables_length,
#bizTable_wrapper .dataTables_filter,
#blotterTable_wrapper .dataTables_length,
#blotterTable_wrapper .dataTables_filter { display:none; }

#appointmentsTable_wrapper .dataTables_info,
#bizTable_wrapper .dataTables_info,
#blotterTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }

#appointmentsTable_wrapper .dataTables_paginate,
#bizTable_wrapper .dataTables_paginate,
#blotterTable_wrapper .dataTables_paginate { padding:12px 20px; }

#appointmentsTable_wrapper .dataTables_paginate .paginate_button,
#bizTable_wrapper .dataTables_paginate .paginate_button,
#blotterTable_wrapper .dataTables_paginate .paginate_button {
    padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;
    border:1px solid var(--border)!important;background:white!important;color:var(--text)!important;margin:0 2px;
}
#appointmentsTable_wrapper .dataTables_paginate .paginate_button.current,
#bizTable_wrapper .dataTables_paginate .paginate_button.current,
#blotterTable_wrapper .dataTables_paginate .paginate_button.current {
    background:var(--navy)!important;color:white!important;border-color:var(--navy)!important;
}
#appointmentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current),
#bizTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current),
#blotterTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background:var(--navy-pale)!important;color:var(--navy)!important;
}
.btn-success { background:#16a34a;color:#fff;border-color:#16a34a; }
.btn-success:disabled { opacity:.65;cursor:not-allowed; }
</style>

<script>
$(document).ready(function () {

    /* ── Tab switching — define FIRST so calls below work ───────── */
    window.switchTab = function (tab) {
        document.querySelectorAll('.apt-tab').forEach(t => t.classList.remove('active'));
        var btn = document.querySelector('.apt-tab[data-tab="' + tab + '"]');
        if (btn) btn.classList.add('active');
        document.querySelectorAll('.apt-panel').forEach(p => p.style.display = 'none');
        var cap = tab.charAt(0).toUpperCase() + tab.slice(1);
        var panel = document.getElementById('panel' + cap);
        if (panel) panel.style.display = '';
        localStorage.setItem('apt_active_tab', tab);

        // Adjust DataTables column widths when panel becomes visible
        if (tab === 'documents' && typeof docTable !== 'undefined') docTable.columns.adjust();
        if (tab === 'business'  && typeof bizTable  !== 'undefined') bizTable.columns.adjust();
        if (tab === 'blotter'   && typeof blotterTable !== 'undefined') blotterTable.columns.adjust();
    };

    function checkTabOverflow() {
        var bar = document.getElementById('aptTabBar');
        var btn = document.getElementById('aptTabMore');
        if (!bar || !btn) return;
        btn.style.display = bar.scrollWidth > bar.clientWidth ? '' : 'none';
    }

    window.scrollTabBar = function () {
        document.getElementById('aptTabBar').scrollBy({ left: 160, behavior: 'smooth' });
    };

    window.addEventListener('resize', checkTabOverflow);

    /* ── Restore last-active tab then check overflow ─────────────── */
    var _activeTab = localStorage.getItem('apt_active_tab') || 'documents';
    switchTab(_activeTab);
    checkTabOverflow();

    /* ── DataTable vars — declared early so switchTab typeof checks work ── */
    var docTable, bizTable, blotterTable;

    /* ── Stat-card quick-filter (documents tab) ─────────────────── */
    window.aptQuickStatus = function (status) {
        var el = document.getElementById('docStatusFilter');
        if (!el) return;
        el.value = status;
        if (docTable) docTable.ajax.reload();
    };

    /* ── TABLE 1 — DOCUMENT REQUESTS ────────────────────────────── */
    docTable = $('#appointmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('appointments.index')); ?>',
            data: function (d) {
                d.status        = $('#docStatusFilter').val();
                d.document_type = $('#docTypeFilter').val();
                d.search        = { value: $('#docSearch').val() };
            }
        },
        columns: [
            { data: 'resident_col',  name: 'resident_name',  orderable: false },
            { data: 'document_col',  name: 'document_type',  orderable: false },
            { data: 'submitted_col', name: 'created_at',     width: '110px' },
            { data: 'date_col',      name: 'pickup_date',    width: '130px' },
            { data: 'status_col',    name: 'status',         width: '110px' },
            { data: 'actions',       name: 'actions',        orderable: false, searchable: false, width: '110px' },
        ],
        order: [[3, 'asc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#appointmentsTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-file-lines"></i><p>No document requests found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No requests match your filters.</p></div>',
        }
    });

    $('#docSearch').on('keyup', debounce(function () { docTable.ajax.reload(); }, 350));
    $('#docStatusFilter, #docTypeFilter').on('change', function () { docTable.ajax.reload(); });

    window.resetDocFilters = function () {
        $('#docSearch').val('');
        $('#docStatusFilter').val('');
        $('#docTypeFilter').val('');
        docTable.ajax.reload();
    };

    /* ── TABLE 2 — BUSINESS PERMIT APPOINTMENTS ─────────────────── */
    bizTable = $('#bizTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('appointments.bizData')); ?>',
            data: function (d) {
                d.status        = $('#bizStatusFilter').val();
                d.search        = { value: $('#bizSearch').val() };
            }
        },
        columns: [
            { data: 'owner_col',     name: 'owner_name',     orderable: false },
            { data: 'biz_col',       name: 'business_name' },
            { data: 'type_col',      name: 'business_type',  orderable: false, width: '120px' },
            { data: 'appt_date_col', name: 'preferred_date', width: '110px' },
            { data: 'submitted_col', name: 'created_at',     width: '100px' },
            { data: 'status_col',    name: 'status',         width: '100px' },
            { data: 'actions',       name: 'actions',        orderable: false, searchable: false, width: '120px' },
        ],
        order: [[3, 'asc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#bizTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-store"></i><p>No business permit appointments found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No appointments match your filters.</p></div>',
        }
    });

    $('#bizSearch').on('keyup', debounce(function () { bizTable.ajax.reload(); }, 350));
    $('#bizStatusFilter').on('change', function () { bizTable.ajax.reload(); });

    window.resetBizFilters = function () {
        $('#bizSearch').val('');
        $('#bizStatusFilter').val('');
        bizTable.ajax.reload();
    };

    /* ── TABLE 3 — BLOTTER PORTAL REPORTS ───────────────────────── */
    blotterTable = $('#blotterTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route('appointments.blotterData')); ?>',
            data: function (d) {
                d.status   = $('#blotterStatusFilter').val();
                d.search   = { value: $('#blotterSearch').val() };
            }
        },
        columns: [
            { data: 'complainant_col', name: 'complainant_name', orderable: false },
            { data: 'type_col',        name: 'incident_type',    orderable: false, width: '130px' },
            { data: 'incident_col',    name: 'incident_location', orderable: false },
            { data: 'submitted_col',   name: 'created_at',       width: '100px' },
            { data: 'status_col',      name: 'status',           width: '110px' },
            { data: 'actions',         name: 'actions',          orderable: false, searchable: false, width: '120px' },
        ],
        order: [[3, 'desc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#blotterTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-shield-halved"></i><p>No blotter reports from portal found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No reports match your filters.</p></div>',
        }
    });

    $('#blotterSearch').on('keyup', debounce(function () { blotterTable.ajax.reload(); }, 350));
    $('#blotterStatusFilter').on('change', function () { blotterTable.ajax.reload(); });

    window.resetBlotterFilters = function () {
        $('#blotterSearch').val('');
        $('#blotterStatusFilter').val('');
        blotterTable.ajax.reload();
    };

    /* ── Debounce helper ────────────────────────────────────────── */
    function debounce(fn, delay) {
        var t;
        return function () { clearTimeout(t); t = setTimeout(fn, delay); };
    }

    /* ═══════════════════════════════════════════════════════════════
     | MODAL — Issue Document
     ═══════════════════════════════════════════════════════════════ */
    $('#appointmentsTable').on('click', '.apt-convert-btn', function () {
        var $btn = $(this);
        document.getElementById('cvtNum').textContent  = $btn.data('num');
        document.getElementById('cvtName').textContent = $btn.data('name');
        document.getElementById('cvtType').textContent = $btn.data('type');
        var purpose    = $btn.data('purpose') || '';
        var purposeRow = document.getElementById('cvtPurposeRow');
        if (purpose) { document.getElementById('cvtPurpose').textContent = purpose; purposeRow.style.display = ''; }
        else { purposeRow.style.display = 'none'; }
        document.getElementById('cvtFee').value = '';
        document.getElementById('cvtOR').value  = '';
        document.getElementById('cvtError').style.display = 'none';
        window._cvtUrl   = $btn.data('url');
        window._cvtAptId = $btn.data('id');
        document.getElementById('aptConvertModal').style.display = 'flex';
    });

    window.closeConvertModal = function () {
        document.getElementById('aptConvertModal').style.display = 'none';
    };

    window.saveConvert = function () {
        var btn = document.getElementById('cvtSaveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Issuing…';
        document.getElementById('cvtError').style.display = 'none';

        axios.post(window._cvtUrl, {
            fee_paid:  document.getElementById('cvtFee').value  || null,
            or_number: document.getElementById('cvtOR').value   || null,
            _token: '<?php echo e(csrf_token()); ?>',
        })
        .then(function (res) {
            closeConvertModal();
            docTable.ajax.reload(null, false);
            bmsToast(res.data.message || 'Document issued.', 'success');
            bmsStatDecrement('statApptTotal');
        })
        .catch(function (err) {
            var msg = err.response?.data?.message || 'Failed to issue document.';
            document.getElementById('cvtError').textContent = msg;
            document.getElementById('cvtError').style.display = '';
            if (err.response?.data?.view_url) {
                bmsToast('Already issued. Redirecting…', 'info');
                setTimeout(() => window.open(err.response.data.view_url, '_blank'), 1200);
            }
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-file-circle-check"></i> Issue Document';
        });
    };

    /* ═══════════════════════════════════════════════════════════════
     | MODAL — Issue Business Permit
     ═══════════════════════════════════════════════════════════════ */
    $('#bizTable').on('click', '.biz-issue-btn', function () {
        var $btn = $(this);
        document.getElementById('bizIssueNum').textContent   = $btn.data('num');
        document.getElementById('bizIssueBiz').textContent   = $btn.data('biz');
        document.getElementById('bizIssueOwner').textContent = $btn.data('owner');
        document.getElementById('bizIssueAppt').textContent  = $btn.data('appt');
        var today    = new Date();
        var nextYear = new Date(today);
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        var fmt = function (d) { return d.toISOString().slice(0, 10); };
        document.getElementById('bizIssuePermitDate').value  = fmt(today);
        document.getElementById('bizIssueExpiryDate').value  = fmt(nextYear);
        document.getElementById('bizIssueFee').value         = '';
        document.getElementById('bizIssueOR').value          = '';
        document.getElementById('bizIssueError').style.display = 'none';
        window._bizIssueUrl = $btn.data('url');
        document.getElementById('bizIssueModal').style.display = 'flex';
    });

    window.closeBizIssueModal = function () {
        document.getElementById('bizIssueModal').style.display = 'none';
    };

    window.saveBizIssue = function () {
        var permitDate = document.getElementById('bizIssuePermitDate').value;
        var expiryDate = document.getElementById('bizIssueExpiryDate').value;
        var errEl = document.getElementById('bizIssueError');
        if (!permitDate || !expiryDate) {
            errEl.textContent = 'Please fill in both permit date and expiry date.';
            errEl.style.display = '';
            return;
        }
        var btn = document.getElementById('bizIssueSaveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Issuing…';
        errEl.style.display = 'none';

        axios.post(window._bizIssueUrl, {
            permit_date: permitDate,
            expiry_date: expiryDate,
            fee_paid:    document.getElementById('bizIssueFee').value  || null,
            or_number:   document.getElementById('bizIssueOR').value   || null,
            _token: '<?php echo e(csrf_token()); ?>',
        })
        .then(function (res) {
            closeBizIssueModal();
            bizTable.ajax.reload(null, false);
            bmsToast(res.data.message || 'Permit issued.', 'success');
            if (res.data.biz_pending !== undefined) {
                document.getElementById('tabBadgeBiz').textContent = res.data.biz_pending;
            }
        })
        .catch(function (err) {
            var msg = err.response?.data?.message || 'Failed to issue permit.';
            errEl.textContent = msg;
            errEl.style.display = '';
            if (err.response?.data?.view_url) {
                bmsToast('Already issued.', 'info');
                setTimeout(() => window.open(err.response.data.view_url, '_blank'), 1200);
            }
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-stamp"></i> Issue Permit';
        });
    };

    /* ═══════════════════════════════════════════════════════════════
     | MODAL — Activate Blotter Case
     ═══════════════════════════════════════════════════════════════ */
    $('#blotterTable').on('click', '.blotter-activate-btn', function () {
        var $btn = $(this);
        document.getElementById('blotterActivateNum').textContent        = $btn.data('num');
        document.getElementById('blotterActivateType').textContent       = $btn.data('type');
        document.getElementById('blotterActivateComplainant').textContent = $btn.data('complainant');
        document.getElementById('blotterActivateDate').textContent       = $btn.data('date');
        document.getElementById('blotterActivateNotes').value            = '';
        document.getElementById('blotterActivateError').style.display    = 'none';
        window._blotterActivateUrl = $btn.data('url');
        document.getElementById('blotterActivateModal').style.display    = 'flex';
    });

    window.closeBlotterActivateModal = function () {
        document.getElementById('blotterActivateModal').style.display = 'none';
    };

    window.saveBlotterActivate = function () {
        var btn = document.getElementById('blotterActivateSaveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Activating…';
        document.getElementById('blotterActivateError').style.display = 'none';

        axios.post(window._blotterActivateUrl, {
            notes: document.getElementById('blotterActivateNotes').value || null,
            _token: '<?php echo e(csrf_token()); ?>',
        })
        .then(function (res) {
            closeBlotterActivateModal();
            blotterTable.ajax.reload(null, false);
            bmsToast(res.data.message || 'Case activated.', 'success');
            if (res.data.blotter_pending !== undefined) {
                document.getElementById('tabBadgeBlotter').textContent = res.data.blotter_pending;
            }
        })
        .catch(function (err) {
            var msg = err.response?.data?.message || 'Failed to activate case.';
            document.getElementById('blotterActivateError').textContent = msg;
            document.getElementById('blotterActivateError').style.display = '';
            if (err.response?.data?.view_url) {
                bmsToast('Already activated.', 'info');
                setTimeout(() => window.open(err.response.data.view_url, '_blank'), 1200);
            }
        })
        .finally(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shield-halved"></i> Activate Case';
        });
    };

}); // end document.ready
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/appointments/index.blade.php ENDPATH**/ ?>