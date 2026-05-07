<?php $__env->startSection('title', $committee['name']); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Tab Strip ───────────────────────────────────────────── */
.tab-strip {
    display: flex;
    border-bottom: 2px solid var(--border);
    padding: 0 20px;
    gap: 2px;
    overflow-x: auto;
    scrollbar-width: none;
    background: var(--surface);
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
}
.tab-strip::-webkit-scrollbar { display: none; }
.tab-btn {
    padding: 13px 16px;
    font-size: 12.5px;
    font-weight: 600;
    color: var(--text-muted);
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: -2px;
    transition: color .15s;
    font-family: 'Poppins', sans-serif;
}
.tab-btn:hover  { color: var(--navy); }
.tab-btn.active { color: var(--navy); border-bottom-color: var(--gold); }
.tab-btn .tab-count {
    font-size: 10px; font-weight: 700;
    padding: 1px 6px; border-radius: 99px;
    background: var(--surface3);
    color: var(--text-muted);
}
.tab-btn.active .tab-count { background: var(--gold-pale); color: var(--gold); }

/* ── Tab Content ─────────────────────────────────────────── */
.tab-content { display: none; }
.tab-content.active { display: block; }

/* ── Panel Header (inside each tab) ─────────────────────── */
.panel-hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--surface);
}
.panel-hd-title {
    font-size: 13px;
    font-weight: 700;
    color: var(--navy);
    display: flex;
    align-items: center;
    gap: 8px;
}
.panel-hd-title i { color: var(--gold); }

/* ── Collapsible Form Panel ──────────────────────────────── */
.form-panel {
    display: none;
    padding: 20px;
    background: var(--surface2);
    border-bottom: 1px solid var(--border);
}
.form-panel.open { display: block; }
.form-panel-inner { max-width: 860px; }
.form-section-label {
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-subtle); margin-bottom: 14px;
}

/* ── Photo Grid ──────────────────────────────────────────── */
.photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    gap: 10px;
    padding: 16px 20px;
}
.photo-thumb { border-radius: var(--radius-sm); overflow: hidden; border: 1px solid var(--border); }
.photo-thumb img { width: 100%; height: 90px; object-fit: cover; display: block; }
.photo-thumb-label { padding: 6px 8px; font-size: 11px; font-weight: 600; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ── Accomplishment Cards ────────────────────────────────── */
.acc-list { padding: 16px 20px; display: flex; flex-direction: column; gap: 10px; }
.acc-card {
    padding: 14px 16px;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-left: 4px solid var(--committee-color, var(--gold));
    border-radius: var(--radius-sm);
}
.acc-card-title { font-weight: 600; font-size: 13.5px; margin-bottom: 3px; }
.acc-card-meta  { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 6px; }
.acc-card-meta span { font-size: 11px; color: var(--text-subtle); }

/* ── Evacuation Center Cards ─────────────────────────────── */
.evac-grid { padding: 16px 20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px; }
.evac-card { padding: 16px; border: 1px solid var(--border); border-radius: var(--radius); background: var(--surface2); }

/* ── Sub-section label inside tabs ──────────────────────── */
.data-section-label {
    padding: 10px 20px;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-subtle);
    background: var(--surface);
    border-bottom: 1px solid var(--border);
}
.td-danger { color: var(--crimson) !important; font-weight: 700; }

/* ── Pharmacy / Medicine Inventory ───────────────────────── */
.med-search-bar {
    display: flex; gap: 10px; align-items: center;
    padding: 12px 16px; background: var(--surface2);
    border-bottom: 1px solid var(--border);
    flex-wrap: wrap;
}
.med-search-input {
    flex: 1; min-width: 220px;
    padding: 7px 12px 7px 34px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: 'Poppins', sans-serif; font-size: .82rem;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
    background-size: 14px;
}
.med-search-input:focus { outline: none; border-color: var(--navy); }
.med-cat-filter {
    padding: 7px 10px; border: 1.5px solid var(--border);
    border-radius: var(--radius-sm); font-family: 'Poppins', sans-serif;
    font-size: .82rem; background: #fff; min-width: 175px;
}
.med-cat-filter:focus { outline: none; border-color: var(--navy); }

.med-stats-strip {
    display: flex; gap: 0;
    border-bottom: 1px solid var(--border);
}
.med-stat {
    flex: 1; padding: 10px 14px; text-align: center;
    border-right: 1px solid var(--border);
    background: var(--surface2);
}
.med-stat:last-child { border-right: none; }
.med-stat-num { font-size: 1.15rem; font-weight: 700; color: var(--navy); line-height: 1; }
.med-stat-lbl { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; margin-top: 3px; }
.med-stat.warn .med-stat-num { color: #b45309; }
.med-stat.danger .med-stat-num { color: var(--crimson); }

/* Category badges */
.cat-badge {
    display: inline-block; padding: 2px 9px; border-radius: 999px;
    font-size: .68rem; font-weight: 700; white-space: nowrap; border: 1.5px solid;
}
.cat-gold   { background: #fef3dc; color: #78450a; border-color: #f0c060; }
.cat-navy   { background: var(--navy-pale); color: var(--navy); border-color: var(--navy-border); }
.cat-purple { background: #ede9fe; color: #5b21b6; border-color: #c4b5fd; }
.cat-blue   { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
.cat-green  { background: #dcfce7; color: #14532d; border-color: #86efac; }
.cat-gray   { background: #f3f4f6; color: #6b7280; border-color: #d1d5db; }

/* Medicine table primary/subtitle */
.med-primary { font-size: .88rem; font-weight: 700; color: var(--navy); line-height: 1.2; }
.med-brand   { font-size: .74rem; color: var(--text-muted); margin-top: 2px; }
.med-barcode { font-size: .68rem; color: #9ca3af; font-family: monospace; margin-top: 2px; }

/* Dosage — high-contrast for clinic readability */
.dosage-text {
    font-size: .84rem; font-weight: 700;
    color: #111827; letter-spacing: .01em;
}

/* Stock display */
.stock-num      { font-size: .92rem; font-weight: 700; }
.stock-ok       { color: var(--navy); }
.stock-low      { color: var(--crimson); }
.stock-unit     { font-size: .72rem; color: var(--text-muted); margin-left: 3px; }
.low-badge {
    display: inline-block; padding: 1px 6px; border-radius: 999px;
    background: #fee2e2; color: var(--crimson); font-size: .64rem;
    font-weight: 700; border: 1px solid #fca5a5; margin-left: 4px; vertical-align: middle;
}
.expiring-badge {
    display: inline-block; padding: 1px 6px; border-radius: 999px;
    background: #fef3dc; color: #78450a; font-size: .64rem;
    font-weight: 700; border: 1px solid #f0c060; margin-left: 4px; vertical-align: middle;
}

/* Medicine Details Modal */
.med-modal-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); z-index: 1100;
    align-items: center; justify-content: center; padding: 16px;
}
.med-modal-backdrop.open { display: flex; }
.med-modal {
    background: #fff; border-radius: var(--radius-lg);
    width: 100%; max-width: 680px; max-height: 88vh;
    overflow-y: auto; box-shadow: 0 16px 60px rgba(0,0,0,.25);
    position: relative;
}
.med-modal-header {
    padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: flex-start; justify-content: space-between;
    position: sticky; top: 0; background: #fff; z-index: 1;
}
.med-modal-title { font-size: 1rem; font-weight: 700; color: var(--navy); }
.med-modal-subtitle { font-size: .78rem; color: var(--text-muted); margin-top: 2px; }
.med-modal-close {
    background: none; border: none; font-size: .95rem;
    color: #9ca3af; cursor: pointer; padding: 4px;
}
.med-modal-close:hover { color: var(--crimson); }
.med-modal-body { padding: 1.25rem 1.5rem; }
.med-specs-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; margin-bottom: 1.25rem;
}
.med-spec-item { background: var(--surface2); border-radius: var(--radius-sm); padding: 10px 12px; }
.med-spec-label { font-size: .68rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; }
.med-spec-value { font-size: .85rem; font-weight: 600; color: var(--navy); margin-top: 3px; }
.med-spec-item.full { grid-column: span 2; }
.med-section-title {
    font-size: .78rem; font-weight: 700; color: #fff;
    background: var(--navy); padding: 6px 12px;
    border-radius: var(--radius-sm); margin-bottom: 10px;
    text-transform: uppercase; letter-spacing: .05em;
}
.log-table { width: 100%; border-collapse: collapse; font-size: .78rem; }
.log-table th {
    background: #f8fafc; color: var(--text-muted); font-size: .67rem;
    font-weight: 600; padding: 6px 10px; text-align: left;
    text-transform: uppercase; letter-spacing: .04em;
    border-bottom: 1px solid var(--border);
}
.log-table td { padding: 7px 10px; border-bottom: 1px solid #f3f4f6; vertical-align: middle; }
.log-table tr:last-child td { border-bottom: none; }
.log-in      { color: #14532d; font-weight: 700; }
.log-out     { color: var(--navy); font-weight: 700; }
.log-disposed{ color: var(--crimson); font-weight: 700; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header">
    <div style="display:flex;align-items:center;gap:14px">
        <div style="width:48px;height:48px;border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;background:<?php echo e($committee['color']); ?>18;color:<?php echo e($committee['color']); ?>">
            <i class="fas <?php echo e($committee['icon']); ?>"></i>
        </div>
        <div>
            <h1 class="page-title"><?php echo e($committee['name']); ?></h1>
            <p class="page-subtitle">Chairperson: <strong><?php echo e($committee['chair']); ?></strong></p>
        </div>
    </div>
    <div class="page-actions">
        <?php if($committee['slug'] === 'peace-order'): ?>
        <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-gavel"></i> View Blotter Cases
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('export.pdf', 'committees')); ?>?slug=<?php echo e($committee['slug']); ?>"
           class="btn btn-secondary btn-sm" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:<?php echo e($committee['color']); ?>15;color:<?php echo e($committee['color']); ?>"><i class="fas fa-folder-open"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($photos->count() + $reports->count() + $resolutions->count() + $otherRecords->count()); ?></div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($activities->count() + $accomplishments->count()); ?></div>
            <div class="stat-label">Activities</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--gold-glow);color:var(--gold)"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($attendances->sum('total_attendees'))); ?></div>
            <div class="stat-label">Total Attendees</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:var(--navy-pale);color:var(--navy)"><i class="fas fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($inventory->count()); ?></div>
            <div class="stat-label">Inventory Items</div>
        </div>
    </div>
</div>


<div class="card" style="overflow:hidden">

    
    <div class="tab-strip">
        <?php
            $tabs = [
                ['id' => 'records',         'label' => 'Records',         'icon' => 'fas fa-folder-open',   'count' => $photos->count() + $reports->count() + $resolutions->count() + $otherRecords->count()],
                ['id' => 'activities',      'label' => 'Activities',      'icon' => 'fas fa-calendar-check','count' => $activities->count()],
                ['id' => 'accomplishments', 'label' => 'Accomplishments', 'icon' => 'fas fa-trophy',        'count' => $accomplishments->count()],
                ['id' => 'attendance',      'label' => 'Attendance',      'icon' => 'fas fa-users',         'count' => $attendances->count()],
                ['id' => 'inventory',       'label' => 'Inventory',       'icon' => 'fas fa-boxes-stacked', 'count' => $inventory->count()],
                ['id' => 'partnerships',    'label' => 'Partnerships',    'icon' => 'fas fa-handshake',     'count' => $partnerships->count()],
            ];
            $specificTabs = match($committee['slug']) {
                'peace-order'    => [
                    ['id' => 'bpso',     'label' => 'BPSO List',    'icon' => 'fas fa-shield-halved',      'count' => isset($specificData['bpso'])        ? $specificData['bpso']->count()        : 0],
                    ['id' => 'patrol',   'label' => 'Patrol Logs',  'icon' => 'fas fa-binoculars',         'count' => isset($specificData['patrol_logs']) ? $specificData['patrol_logs']->count() : 0],
                    ['id' => 'training', 'label' => 'Trainings',    'icon' => 'fas fa-chalkboard-user',    'count' => isset($specificData['trainings'])   ? $specificData['trainings']->count()   : 0],
                ],
                'health'         => [
                    ['id' => 'health-records',     'label' => 'Health Records',     'icon' => 'fas fa-notes-medical',  'count' => isset($specificData['health_records'])     ? $specificData['health_records']->count()     : 0],
                    ['id' => 'clinic-staff',        'label' => 'Clinic Staff',       'icon' => 'fas fa-user-doctor',    'count' => isset($specificData['clinic_staff'])       ? $specificData['clinic_staff']->count()       : 0],
                    ['id' => 'medicine-inventory',  'label' => 'Medicine Inventory', 'icon' => 'fas fa-pills',          'count' => isset($specificData['medicine_inventory']) ? $specificData['medicine_inventory']->count() : 0],
                ],
                'education'      => [['id' => 'scholars',    'label' => 'Scholars',       'icon' => 'fas fa-graduation-cap',     'count' => isset($specificData['scholars'])      ? $specificData['scholars']->count()      : 0]],
                'infrastructure' => [
                    ['id' => 'projects',   'label' => 'Projects',          'icon' => 'fas fa-hard-hat',          'count' => isset($specificData['projects'])   ? $specificData['projects']->count()   : 0],
                    ['id' => 'contracts',  'label' => 'Contracts',         'icon' => 'fas fa-file-signature',    'count' => isset($specificData['contracts'])  ? $specificData['contracts']->count()  : 0],
                    ['id' => 'financials', 'label' => 'Financial Records', 'icon' => 'fas fa-money-bill-wave',   'count' => isset($specificData['financials']) ? $specificData['financials']->count() : 0],
                ],
                'environment'    => [
                    ['id' => 'env-programs', 'label' => 'Programs',        'icon' => 'fas fa-leaf',  'count' => isset($specificData['programs']) ? $specificData['programs']->count() : 0],
                    ['id' => 'sweepers',     'label' => 'Street Sweepers', 'icon' => 'fas fa-broom', 'count' => isset($specificData['sweepers']) ? $specificData['sweepers']->count() : 0],
                ],
                'livelihood'     => [['id' => 'beneficiaries', 'label' => 'Beneficiaries',  'icon' => 'fas fa-hand-holding-heart',   'count' => isset($specificData['beneficiaries'])    ? $specificData['beneficiaries']->count()    : 0]],
                'transport'      => [['id' => 'toda',           'label' => 'TODA Registry',  'icon' => 'fas fa-bus',                  'count' => isset($specificData['toda'])             ? $specificData['toda']->count()             : 0]],
                'bdrrm'          => [
                    ['id' => 'emergency',       'label' => 'Emergency Logs',     'icon' => 'fas fa-exclamation-triangle',  'count' => isset($specificData['emergency_logs'])     ? $specificData['emergency_logs']->count()     : 0],
                    ['id' => 'evacuation',       'label' => 'Evacuation Centers', 'icon' => 'fas fa-house-chimney-medical', 'count' => isset($specificData['evacuation_centers']) ? $specificData['evacuation_centers']->count() : 0],
                    ['id' => 'relief-supplies',  'label' => 'Relief Supplies',   'icon' => 'fas fa-boxes-stacked',         'count' => isset($specificData['relief_supplies'])    ? $specificData['relief_supplies']->count()    : 0],
                ],
                default => [],
            };
            $allTabs = array_merge($tabs, $specificTabs);
        ?>
        <?php $__currentLoopData = $allTabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button class="tab-btn <?php echo e($loop->first ? 'active' : ''); ?>"
                onclick="switchTab('<?php echo e($tab['id']); ?>')"
                id="tab-btn-<?php echo e($tab['id']); ?>">
            <i class="<?php echo e($tab['icon']); ?>" style="font-size:11px"></i>
            <?php echo e($tab['label']); ?>

            <span class="tab-count"><?php echo e($tab['count']); ?></span>
        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div id="tab-records" class="tab-content active">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-folder-open"></i> Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-records', this)" data-label="Upload Record">
                <i class="fas fa-plus"></i> Upload Record
            </button>
        </div>
        <div id="form-records" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-upload" style="color:var(--gold);margin-right:6px"></i> Upload New Record</div>
                <form method="POST" action="<?php echo e(route('committees.storeRecord', $committee['slug'])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group">
                            <label class="form-label">Record Type <span style="color:var(--crimson)">*</span></label>
                            <select name="record_type" class="form-control" required>
                                <?php $__currentLoopData = ['Photo','Video','Report','Resolution','Certificate','Partnership','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($rt); ?>"><?php echo e($rt); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group" style="grid-column:span 2">
                            <label class="form-label">Title <span style="color:var(--crimson)">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Record title" required>
                        </div>
                        <div class="form-group" style="grid-column:span 2">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Optional description">
                        </div>
                        <div class="form-group">
                            <label class="form-label">File</label>
                            <input type="file" name="file" class="form-control">
                        </div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Upload</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-records', document.querySelector('[onclick*=form-records]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if($photos->count()): ?>
        <div class="data-section-label"><i class="fas fa-images" style="color:var(--gold);margin-right:6px"></i> Photos (<?php echo e($photos->count()); ?>)</div>
        <div class="photo-grid">
            <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="photo-thumb">
                <?php if($photo->file_path): ?>
                <a href="<?php echo e(asset('storage/'.$photo->file_path)); ?>" target="_blank">
                    <img src="<?php echo e(asset('storage/'.$photo->file_path)); ?>" alt="<?php echo e($photo->title); ?>">
                </a>
                <?php endif; ?>
                <div class="photo-thumb-label"><?php echo e($photo->title); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <?php $docRecords = $reports->concat($resolutions)->concat($otherRecords)->sortByDesc('created_at'); ?>
        <?php if($docRecords->count()): ?>
        <div class="data-section-label" style="border-top:1px solid var(--border)"><i class="fas fa-file-alt" style="color:var(--gold);margin-right:6px"></i> Documents (<?php echo e($docRecords->count()); ?>)</div>
        <table>
            <thead><tr><th>Title</th><th>Type</th><th>Description</th><th>Uploaded</th><th>File</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $docRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($rec->title); ?></td>
                    <td><span class="badge badge-navy" style="font-size:10px"><?php echo e($rec->record_type); ?></span></td>
                    <td class="td-muted"><?php echo e($rec->description ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($rec->created_at->format('M d, Y')); ?></td>
                    <td>
                        <?php if($rec->file_path): ?>
                        <a href="<?php echo e(asset('storage/'.$rec->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a>
                        <?php else: ?> <span class="td-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php endif; ?>

        <?php if($photos->count() === 0 && $docRecords->count() === 0): ?>
        <div class="empty-state"><i class="fas fa-folder-open"></i><p>No records uploaded yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-activities" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-calendar-check"></i> Activities</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-activities', this)" data-label="Log Activity">
                <i class="fas fa-plus"></i> Log Activity
            </button>
        </div>
        <div id="form-activities" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log New Activity</div>
                <form method="POST" action="<?php echo e(route('committees.storeActivity', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="activity_type" value="Activity">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Activity Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control" placeholder="e.g. Barangay Assembly" required></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="activity_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" class="form-control" placeholder="Venue"></div>
                        <div class="form-group"><label class="form-label">Participants</label><input type="number" name="participants_count" class="form-control" min="0" placeholder="0"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Planned','Ongoing','Completed','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>"><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" class="form-control" placeholder="Brief description"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Activity</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-activities', document.querySelector('[onclick*=form-activities]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if($activities->count()): ?>
        <table>
            <thead><tr><th>Title</th><th>Date</th><th>Location</th><th>Participants</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div style="font-weight:600"><?php echo e($act->title); ?></div>
                        <?php if($act->description): ?><div class="td-muted"><?php echo e($act->description); ?></div><?php endif; ?>
                    </td>
                    <td class="td-muted"><?php echo e(\Carbon\Carbon::parse($act->activity_date)->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($act->location ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e(number_format($act->participants_count)); ?></td>
                    <td><span class="badge <?php echo e(match($act->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($act->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-calendar-check"></i><p>No activities logged yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-accomplishments" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-trophy"></i> Accomplishments</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-accomplishments', this)" data-label="Log Accomplishment">
                <i class="fas fa-plus"></i> Log Accomplishment
            </button>
        </div>
        <div id="form-accomplishments" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Accomplishment</div>
                <form method="POST" action="<?php echo e(route('committees.storeActivity', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="activity_type" value="Accomplishment">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="activity_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Beneficiaries</label><input type="number" name="participants_count" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Completed','Ongoing','Planned','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($s); ?>"><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-trophy"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-accomplishments', document.querySelector('[onclick*=form-accomplishments]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if($accomplishments->count()): ?>
        <div class="acc-list" style="--committee-color:<?php echo e($committee['color']); ?>">
            <?php $__currentLoopData = $accomplishments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="acc-card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
                    <div style="flex:1;min-width:0">
                        <div class="acc-card-title"><?php echo e($acc->title); ?></div>
                        <?php if($acc->description): ?><div class="td-muted" style="font-size:12.5px;margin-bottom:4px"><?php echo e($acc->description); ?></div><?php endif; ?>
                        <div class="acc-card-meta">
                            <span><i class="fas fa-calendar-alt" style="margin-right:4px"></i><?php echo e(\Carbon\Carbon::parse($acc->activity_date)->format('M d, Y')); ?></span>
                            <?php if($acc->location): ?><span><i class="fas fa-location-dot" style="margin-right:4px"></i><?php echo e($acc->location); ?></span><?php endif; ?>
                            <?php if($acc->participants_count): ?><span><i class="fas fa-users" style="margin-right:4px"></i><?php echo e(number_format($acc->participants_count)); ?> beneficiaries</span><?php endif; ?>
                        </div>
                    </div>
                    <span class="badge <?php echo e(match($acc->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($acc->status); ?></span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-trophy"></i><p>No accomplishments logged yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-attendance" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-users"></i> Attendance Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-attendance', this)" data-label="Record Attendance">
                <i class="fas fa-plus"></i> Record Attendance
            </button>
        </div>
        <div id="form-attendance" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Record Attendance</div>
                <form method="POST" action="<?php echo e(route('committees.storeAttendance', $committee['slug'])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Event Name <span style="color:var(--crimson)">*</span></label><input type="text" name="event_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="event_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Venue</label><input type="text" name="venue" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Total Attendees <span style="color:var(--crimson)">*</span></label><input type="number" name="total_attendees" class="form-control" min="0" required></div>
                        <div class="form-group"><label class="form-label">Attendance Sheet</label><input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Record</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-attendance', document.querySelector('[onclick*=form-attendance]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if($attendances->count()): ?>
        <table>
            <thead><tr><th>Event</th><th>Date</th><th>Venue</th><th style="text-align:right">Attendees</th><th>Notes</th><th>Sheet</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($att->event_name); ?></td>
                    <td class="td-muted"><?php echo e(\Carbon\Carbon::parse($att->event_date)->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($att->venue ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)"><?php echo e(number_format($att->total_attendees)); ?></td>
                    <td class="td-muted"><?php echo e($att->notes ?? '—'); ?></td>
                    <td><?php if($att->file_path): ?><a href="<?php echo e(asset('storage/'.$att->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-users"></i><p>No attendance records yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-inventory" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-boxes-stacked"></i> Inventory</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-inventory', this)" data-label="Add Item">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </div>
        <div id="form-inventory" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Inventory Item</div>
                <form method="POST" action="<?php echo e(route('committees.storeInventory', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label><input type="text" name="item_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Category</label><input type="text" name="category" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Quantity <span style="color:var(--crimson)">*</span></label><input type="number" name="quantity" class="form-control" min="0" required></div>
                        <div class="form-group"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" placeholder="pcs, sets"></div>
                        <div class="form-group"><label class="form-label">Condition <span style="color:var(--crimson)">*</span></label><select name="condition" class="form-control" required><?php $__currentLoopData = ['Good','Fair','Poor','For Disposal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($c); ?>"><?php echo e($c); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Item</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-inventory', document.querySelector('[onclick*=form-inventory]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if($inventory->count()): ?>
        <table>
            <thead><tr><th>Item Name</th><th>Category</th><th style="text-align:right">Qty</th><th>Unit</th><th>Condition</th><th>Remarks</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $inventory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($item->item_name); ?></td>
                    <td class="td-muted"><?php echo e($item->category ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)"><?php echo e(number_format($item->quantity)); ?></td>
                    <td class="td-muted"><?php echo e($item->unit ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($item->condition) { 'Good'=>'badge-green','Fair'=>'badge-yellow','Poor'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($item->condition); ?></span></td>
                    <td class="td-muted"><?php echo e($item->remarks ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-boxes-stacked"></i><p>No inventory items yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-partnerships" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-handshake"></i> Partnership Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-partnership', this)" data-label="Add Partnership">
                <i class="fas fa-plus"></i> Add Partnership
            </button>
        </div>
        <div id="form-partnership" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Partnership / MOU Record</div>
                <form method="POST" action="<?php echo e(route('committees.storePartnership', $committee['slug'])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Partner Name / Organization <span style="color:var(--crimson)">*</span></label><input type="text" name="partner_name" class="form-control" placeholder="e.g. Quezon City Health Department" required></div>
                        <div class="form-group"><label class="form-label">Partner Type <span style="color:var(--crimson)">*</span></label><select name="partner_type" class="form-control" required><?php $__currentLoopData = ['Government','NGO','Private','Community','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($pt); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">MOU / MOA Date</label><input type="date" name="mou_date" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Validity Date</label><input type="date" name="validity_date" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Contact Person</label><input type="text" name="contact_person" class="form-control" placeholder="Name of focal person"></div>
                        <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Description / Scope</label><textarea name="description" class="form-control" rows="2" placeholder="Brief description of the partnership"></textarea></div>
                        <div class="form-group"><label class="form-label">MOU / MOA Document</label><input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-partnership', document.querySelector('[onclick*=form-partnership]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if($partnerships->count()): ?>
        <table>
            <thead><tr><th>Organization</th><th>Type</th><th>MOU Date</th><th>Valid Until</th><th>Contact Person</th><th>Contact</th><th>MOU File</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $partnerships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--navy)"><?php echo e($p->partner_name); ?></div>
                        <?php if($p->description): ?><div style="font-size:11px;color:var(--text-muted);margin-top:2px"><?php echo e(Str::limit($p->description, 60)); ?></div><?php endif; ?>
                    </td>
                    <td><span class="badge badge-navy" style="font-size:10px"><?php echo e($p->partner_type); ?></span></td>
                    <td class="td-muted"><?php echo e($p->mou_date?->format('M d, Y') ?? '—'); ?></td>
                    <td class="<?php echo e($p->validity_date && $p->validity_date->isPast() ? 'td-danger' : 'td-muted'); ?>">
                        <?php echo e($p->validity_date?->format('M d, Y') ?? '—'); ?>

                        <?php if($p->validity_date && $p->validity_date->isPast()): ?> <span style="font-size:10px">(expired)</span> <?php endif; ?>
                    </td>
                    <td class="td-muted"><?php echo e($p->contact_person ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->contact_number ?? '—'); ?></td>
                    <td><?php if($p->file_path): ?><a href="<?php echo e(asset('storage/'.$p->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('committees.destroyPartnership', [$committee['slug'], $p->id])); ?>" onsubmit="return confirm('Delete this partnership record?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-handshake"></i><p>No partnership records yet.</p></div>
        <?php endif; ?>
    </div>

    

    
    <?php if($committee['slug'] === 'peace-order'): ?>
    <div id="tab-bpso" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-shield-halved"></i> BPSO Members</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-bpso', this)" data-label="Add Member">
                <i class="fas fa-plus"></i> Add Member
            </button>
        </div>
        <div id="form-bpso" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add BPSO Member</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="bpso">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Rank</label><input type="text" name="rank" class="form-control" placeholder="e.g. Senior BPSO"></div>
                        <div class="form-group"><label class="form-label">Badge No.</label><input type="text" name="badge_number" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Contact</label><input type="text" name="contact_number" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Assignment</label><input type="text" name="assignment" class="form-control" placeholder="Area/Post"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option>Active</option><option>Inactive</option><option>On Leave</option></select></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Member</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-bpso', document.querySelector('[onclick*=form-bpso]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['bpso']) && $specificData['bpso']->count()): ?>
        <table>
            <thead><tr><th>Name</th><th>Rank</th><th>Badge No.</th><th>Contact</th><th>Assignment</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['bpso']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($b->full_name); ?></td>
                    <td class="td-muted"><?php echo e($b->rank ?? '—'); ?></td>
                    <td class="td-mono"><?php echo e($b->badge_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($b->contact_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($b->assignment ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($b->status === 'Active' ? 'badge-green' : ($b->status === 'On Leave' ? 'badge-yellow' : 'badge-gray')); ?>"><?php echo e($b->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-shield-halved"></i><p>No BPSO members yet.</p></div><?php endif; ?>
    </div>

    <div id="tab-patrol" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-binoculars"></i> Patrol Logs</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-patrol', this)" data-label="Log Patrol">
                <i class="fas fa-plus"></i> Log Patrol
            </button>
        </div>
        <div id="form-patrol" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Patrol</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="patrol">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="patrol_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Shift</label><select name="shift" class="form-control"><option>Morning</option><option>Afternoon</option><option>Night</option></select></div>
                        <div class="form-group"><label class="form-label">Area Covered <span style="color:var(--crimson)">*</span></label><input type="text" name="area_covered" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Personnel</label><input type="number" name="personnel_count" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Reported By</label><input type="text" name="reported_by" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Findings</label><input type="text" name="findings" class="form-control" placeholder="Observations / Incidents"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Log Patrol</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-patrol', document.querySelector('[onclick*=form-patrol]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['patrol_logs']) && $specificData['patrol_logs']->count()): ?>
        <table>
            <thead><tr><th>Date</th><th>Shift</th><th>Area</th><th>Personnel</th><th>Findings</th><th>Reported By</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['patrol_logs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="td-muted"><?php echo e($p->patrol_date->format('M d, Y')); ?></td>
                    <td><span class="badge badge-navy" style="font-size:10px"><?php echo e($p->shift ?? '—'); ?></span></td>
                    <td style="font-weight:600"><?php echo e($p->area_covered); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($p->personnel_count); ?></td>
                    <td class="td-muted"><?php echo e($p->findings ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->reported_by ?? '—'); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-binoculars"></i><p>No patrol logs yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'health'): ?>
    <div id="tab-health-records" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-notes-medical"></i> Health Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-health', this)" data-label="Add Record">
                <i class="fas fa-plus"></i> Add Record
            </button>
        </div>
        <div id="form-health" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Health Record</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="health">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Patient Name <span style="color:var(--crimson)">*</span></label><input type="text" name="patient_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Visit Date <span style="color:var(--crimson)">*</span></label><input type="date" name="visit_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Age</label><input type="number" name="age" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Gender</label><select name="gender" class="form-control"><option value="">—</option><option>Male</option><option>Female</option></select></div>
                        <div class="form-group"><label class="form-label">Program</label><select name="program" class="form-control"><option value="">—</option><?php $__currentLoopData = ['Vaccination','Prenatal','Family Planning','Dental','Medical Mission','Nutrition','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($p); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Attended By</label><input type="text" name="attended_by" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Diagnosis / Notes</label><input type="text" name="diagnosis" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Remarks</label><input type="text" name="notes" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Record</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-health', document.querySelector('[onclick*=form-health]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['health_records']) && $specificData['health_records']->count()): ?>
        <table>
            <thead><tr><th>Patient</th><th>Age</th><th>Gender</th><th>Program</th><th>Diagnosis</th><th>Attended By</th><th>Date</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['health_records']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($h->patient_name); ?></td>
                    <td><?php echo e($h->age ?? '—'); ?></td>
                    <td><?php echo e($h->gender ?? '—'); ?></td>
                    <td><span class="badge badge-blue" style="font-size:10px"><?php echo e($h->program ?? '—'); ?></span></td>
                    <td class="td-muted"><?php echo e($h->diagnosis ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($h->attended_by ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($h->visit_date->format('M d, Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-notes-medical"></i><p>No health records yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'education'): ?>
    <div id="tab-scholars" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-graduation-cap"></i> Scholars</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-scholars', this)" data-label="Add Scholar">
                <i class="fas fa-plus"></i> Add Scholar
            </button>
        </div>
        <div id="form-scholars" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Scholar</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="scholar">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">School <span style="color:var(--crimson)">*</span></label><input type="text" name="school" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Course / Grade Level</label><input type="text" name="course_grade_level" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Year Level</label><input type="text" name="year_level" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Scholarship Type</label><input type="text" name="scholarship_type" class="form-control" placeholder="e.g. CHED, Barangay"></div>
                        <div class="form-group"><label class="form-label">Grant Amount (₱)</label><input type="number" name="grant_amount" class="form-control" min="0" step="0.01"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option>Active</option><option>Graduated</option><option>Dropped</option><option>Suspended</option></select></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Scholar</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-scholars', document.querySelector('[onclick*=form-scholars]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['scholars']) && $specificData['scholars']->count()): ?>
        <table>
            <thead><tr><th>Name</th><th>School</th><th>Course/Level</th><th>Year</th><th>Scholarship</th><th>Grant</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['scholars']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($s->full_name); ?></td>
                    <td class="td-muted"><?php echo e($s->school); ?></td>
                    <td class="td-muted"><?php echo e($s->course_grade_level ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($s->year_level ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($s->scholarship_type ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($s->grant_amount ? '₱'.number_format($s->grant_amount,2) : '—'); ?></td>
                    <td><span class="badge <?php echo e(match($s->status) { 'Active'=>'badge-green','Graduated'=>'badge-blue','Dropped'=>'badge-red',default=>'badge-yellow' }); ?>"><?php echo e($s->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-graduation-cap"></i><p>No scholars yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'infrastructure'): ?>
    <div id="tab-projects" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-hard-hat"></i> Projects</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-projects', this)" data-label="Add Project">
                <i class="fas fa-plus"></i> Add Project
            </button>
        </div>
        <div id="form-projects" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Project</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="project">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Project Name <span style="color:var(--crimson)">*</span></label><input type="text" name="project_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Type</label><select name="project_type" class="form-control"><option value="">—</option><?php $__currentLoopData = ['Road','Drainage','Building','Electrical','Water','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Planned','Ongoing','Completed','On Hold','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Budget (₱)</label><input type="number" name="budget" class="form-control" min="0" step="0.01"></div>
                        <div class="form-group"><label class="form-label">Actual Cost (₱)</label><input type="number" name="actual_cost" class="form-control" min="0" step="0.01"></div>
                        <div class="form-group"><label class="form-label">Completion %</label><input type="number" name="completion_percentage" class="form-control" min="0" max="100" value="0"></div>
                        <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
                        <div class="form-group"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Project</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-projects', document.querySelector('[onclick*=form-projects]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['projects']) && $specificData['projects']->count()): ?>
        <table>
            <thead><tr><th>Project</th><th>Type</th><th>Location</th><th>Budget</th><th>Progress</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($p->project_name); ?></td>
                    <td class="td-muted"><?php echo e($p->project_type ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->location ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($p->budget ? '₱'.number_format($p->budget,2) : '—'); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="progress-bar-wrap" style="flex:1;min-width:60px">
                                <div class="progress-bar" style="width:<?php echo e($p->completion_percentage); ?>%;background:<?php echo e($p->completion_percentage >= 100 ? '#16a34a' : 'var(--gold)'); ?>"></div>
                            </div>
                            <span style="font-size:11px;color:var(--text-muted);white-space:nowrap"><?php echo e($p->completion_percentage); ?>%</span>
                        </div>
                    </td>
                    <td><span class="badge <?php echo e(match($p->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red','On Hold'=>'badge-orange',default=>'badge-gray' }); ?>"><?php echo e($p->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-hard-hat"></i><p>No projects yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'environment'): ?>
    <div id="tab-env-programs" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-leaf"></i> Environmental Programs</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-env', this)" data-label="Add Program">
                <i class="fas fa-plus"></i> Add Program
            </button>
        </div>
        <div id="form-env" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Program</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="environment">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Program Name <span style="color:var(--crimson)">*</span></label><input type="text" name="program_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="program_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Type</label><select name="program_type" class="form-control"><option value="">—</option><?php $__currentLoopData = ['Clean-up Drive','Tree Planting','Waste Management','Coastal Clean-up','Anti-littering','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Planned','Completed','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Volunteers</label><input type="number" name="volunteers" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Trees Planted</label><input type="number" name="trees_planted" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Waste Collected (kg)</label><input type="number" name="waste_collected_kg" class="form-control" min="0" step="0.01"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Program</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-env', document.querySelector('[onclick*=form-env]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['programs']) && $specificData['programs']->count()): ?>
        <table>
            <thead><tr><th>Program</th><th>Type</th><th>Date</th><th>Location</th><th>Volunteers</th><th>Trees</th><th>Waste (kg)</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['programs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($p->program_name); ?></td>
                    <td class="td-muted"><?php echo e($p->program_type ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->program_date->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($p->location ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($p->volunteers); ?></td>
                    <td style="font-weight:600;color:#16a34a"><?php echo e($p->trees_planted ?? '—'); ?></td>
                    <td style="font-weight:600"><?php echo e($p->waste_collected_kg ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($p->status) { 'Completed'=>'badge-green','Planned'=>'badge-yellow',default=>'badge-red' }); ?>"><?php echo e($p->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-leaf"></i><p>No programs yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'livelihood'): ?>
    <div id="tab-beneficiaries" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-hand-holding-heart"></i> Beneficiaries</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-beneficiaries', this)" data-label="Add Beneficiary">
                <i class="fas fa-plus"></i> Add Beneficiary
            </button>
        </div>
        <div id="form-beneficiaries" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Beneficiary</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="livelihood">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Date Enrolled</label><input type="date" name="date_enrolled" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Contact</label><input type="text" name="contact_number" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Program Name <span style="color:var(--crimson)">*</span></label><input type="text" name="program_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Program Type</label><select name="program_type" class="form-control"><option value="">—</option><?php $__currentLoopData = ['Training','Livelihood Goods','Cash Grant','Loan','Skills Program','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Amount Received (₱)</label><input type="number" name="amount_received" class="form-control" min="0" step="0.01"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Active','Completed','Dropped']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Beneficiary</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-beneficiaries', document.querySelector('[onclick*=form-beneficiaries]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['beneficiaries']) && $specificData['beneficiaries']->count()): ?>
        <table>
            <thead><tr><th>Name</th><th>Program</th><th>Type</th><th>Amount</th><th>Enrolled</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['beneficiaries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($b->full_name); ?></td>
                    <td class="td-muted"><?php echo e($b->program_name); ?></td>
                    <td class="td-muted"><?php echo e($b->program_type ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($b->amount_received ? '₱'.number_format($b->amount_received,2) : '—'); ?></td>
                    <td class="td-muted"><?php echo e($b->date_enrolled?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($b->status) { 'Active'=>'badge-green','Completed'=>'badge-blue',default=>'badge-red' }); ?>"><?php echo e($b->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-hand-holding-heart"></i><p>No beneficiaries yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'transport'): ?>
    <div id="tab-toda" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-bus"></i> TODA Registry</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-toda', this)" data-label="Register Vehicle">
                <i class="fas fa-plus"></i> Register Vehicle
            </button>
        </div>
        <div id="form-toda" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Register Vehicle</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="toda">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Operator Name <span style="color:var(--crimson)">*</span></label><input type="text" name="operator_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Driver Name</label><input type="text" name="driver_name" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Vehicle Type</label><select name="vehicle_type" class="form-control"><option value="">—</option><?php $__currentLoopData = ['Tricycle','Jeepney','E-bike','UV Express','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Plate Number</label><input type="text" name="plate_number" class="form-control"></div>
                        <div class="form-group"><label class="form-label">TODA Name</label><input type="text" name="toda_name" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Route</label><input type="text" name="route" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Active','Expired','Suspended']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Registration Date</label><input type="date" name="registration_date" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Expiry Date</label><input type="date" name="expiry_date" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Register</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-toda', document.querySelector('[onclick*=form-toda]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['toda']) && $specificData['toda']->count()): ?>
        <table>
            <thead><tr><th>Operator</th><th>Driver</th><th>Type</th><th>Plate</th><th>TODA</th><th>Route</th><th>Expiry</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['toda']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($t->operator_name); ?></td>
                    <td class="td-muted"><?php echo e($t->driver_name ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($t->vehicle_type ?? '—'); ?></td>
                    <td class="td-mono"><?php echo e($t->plate_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($t->toda_name ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($t->route ?? '—'); ?></td>
                    <td class="td-muted <?php echo e($t->expiry_date && $t->expiry_date->isPast() ? 'td-danger' : ''); ?>"><?php echo e($t->expiry_date?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($t->status) { 'Active'=>'badge-green','Expired'=>'badge-red',default=>'badge-yellow' }); ?>"><?php echo e($t->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-bus"></i><p>No vehicles registered yet.</p></div><?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'bdrrm'): ?>
    <div id="tab-emergency" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-exclamation-triangle"></i> Emergency Logs</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-emergency', this)" data-label="Log Emergency">
                <i class="fas fa-plus"></i> Log Emergency
            </button>
        </div>
        <div id="form-emergency" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Emergency</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="emergency">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group"><label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label><select name="incident_type" class="form-control" required><?php $__currentLoopData = ['Flood','Fire','Earthquake','Typhoon','Landslide','Accident','Medical Emergency','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="incident_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Active','Resolved','Monitoring']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Location <span style="color:var(--crimson)">*</span></label><input type="text" name="location" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Reported By</label><input type="text" name="reported_by" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Affected Families</label><input type="number" name="affected_families" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Affected Persons</label><input type="number" name="affected_persons" class="form-control" min="0"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Response Actions</label><input type="text" name="response_actions" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Log Emergency</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-emergency', document.querySelector('[onclick*=form-emergency]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['emergency_logs']) && $specificData['emergency_logs']->count()): ?>
        <table>
            <thead><tr><th>Type</th><th>Date</th><th>Location</th><th>Families</th><th>Persons</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['emergency_logs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($e->incident_type); ?></td>
                    <td class="td-muted"><?php echo e($e->incident_date->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($e->location); ?></td>
                    <td style="font-weight:600;color:var(--crimson)"><?php echo e(number_format($e->affected_families)); ?></td>
                    <td style="font-weight:600;color:var(--crimson)"><?php echo e(number_format($e->affected_persons)); ?></td>
                    <td><span class="badge <?php echo e(match($e->status) { 'Resolved'=>'badge-green','Monitoring'=>'badge-yellow',default=>'badge-red' }); ?>"><?php echo e($e->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?><div class="empty-state"><i class="fas fa-exclamation-triangle"></i><p>No emergency logs yet.</p></div><?php endif; ?>
    </div>

    <div id="tab-evacuation" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-house-chimney-medical"></i> Evacuation Centers</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-evacuation', this)" data-label="Add Center">
                <i class="fas fa-plus"></i> Add Center
            </button>
        </div>
        <div id="form-evacuation" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Evacuation Center</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="evacuation">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Center Name <span style="color:var(--crimson)">*</span></label><input type="text" name="center_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Available','Active','Full','Closed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Location <span style="color:var(--crimson)">*</span></label><input type="text" name="location" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Capacity</label><input type="number" name="capacity" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Current Occupancy</label><input type="number" name="current_occupancy" class="form-control" min="0"></div>
                        <div class="form-group"><label class="form-label">Contact Person</label><input type="text" name="contact_person" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Facilities</label><input type="text" name="facilities" class="form-control" placeholder="e.g. Restrooms, Beds, Kitchen"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Center</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-evacuation', document.querySelector('[onclick*=form-evacuation]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['evacuation_centers']) && $specificData['evacuation_centers']->count()): ?>
        <div class="evac-grid">
            <?php $__currentLoopData = $specificData['evacuation_centers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $occ = $ec->capacity > 0 ? round(($ec->current_occupancy / $ec->capacity) * 100) : 0; ?>
            <div class="evac-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                    <div>
                        <div style="font-weight:700;font-size:14px;color:var(--navy)"><?php echo e($ec->center_name); ?></div>
                        <div class="td-muted" style="margin-top:2px"><?php echo e($ec->location); ?></div>
                    </div>
                    <span class="badge <?php echo e(match($ec->status) { 'Available'=>'badge-green','Active'=>'badge-blue','Full'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($ec->status); ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--text-muted);margin-bottom:6px">
                    <span>Occupancy</span>
                    <span><strong><?php echo e($ec->current_occupancy); ?></strong> / <?php echo e($ec->capacity); ?></span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:<?php echo e($occ); ?>%;background:<?php echo e($occ >= 90 ? 'var(--crimson)' : ($occ >= 70 ? 'var(--gold)' : '#16a34a')); ?>"></div>
                </div>
                <?php if($ec->contact_person): ?>
                <div style="font-size:11px;color:var(--text-muted);margin-top:8px">
                    <i class="fas fa-user" style="margin-right:4px"></i><?php echo e($ec->contact_person); ?> — <?php echo e($ec->contact_number ?? '—'); ?>

                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?><div class="empty-state"><i class="fas fa-house-chimney-medical"></i><p>No evacuation centers yet.</p></div><?php endif; ?>
    </div>

    
    <div id="tab-relief-supplies" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-boxes-stacked"></i> Relief Supplies</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-relief', this)" data-label="Add Item">
                <i class="fas fa-plus"></i> Add Item
            </button>
        </div>
        <div id="form-relief" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Relief Supply Item</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="relief">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label><input type="text" name="item_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Category <span style="color:var(--crimson)">*</span></label><select name="category" class="form-control" required><?php $__currentLoopData = ['Food','Non-food','Medicine','PPE','Equipment','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($c); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Quantity <span style="color:var(--crimson)">*</span></label><input type="number" name="quantity" class="form-control" min="0" value="0" required></div>
                        <div class="form-group"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" placeholder="e.g. pcs, packs, boxes, sacks"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Available','Distributed','Depleted']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Source / Donated By</label><input type="text" name="source" class="form-control" placeholder="DSWD, LGU, private donor, etc."></div>
                        <div class="form-group"><label class="form-label">Date Received</label><input type="date" name="date_received" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-relief', document.querySelector('[onclick*=form-relief]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['relief_supplies']) && $specificData['relief_supplies']->count()): ?>
        <table>
            <thead><tr><th>Item</th><th>Category</th><th style="text-align:right">Qty</th><th>Unit</th><th>Source</th><th>Date Received</th><th>Status</th><th>Remarks</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['relief_supplies']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($rs->item_name); ?></td>
                    <td><span class="badge badge-navy" style="font-size:10px"><?php echo e($rs->category); ?></span></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)"><?php echo e(number_format($rs->quantity)); ?></td>
                    <td class="td-muted"><?php echo e($rs->unit ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($rs->source ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($rs->date_received?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($rs->status) { 'Available'=>'badge-green','Distributed'=>'badge-yellow',default=>'badge-gray' }); ?>"><?php echo e($rs->status); ?></span></td>
                    <td class="td-muted"><?php echo e($rs->remarks ?? '—'); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('committees.destroyRelief', [$committee['slug'], $rs->id])); ?>" onsubmit="return confirm('Delete this item?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-boxes-stacked"></i><p>No relief supplies recorded yet.</p></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>


    
    <?php if($committee['slug'] === 'peace-order'): ?>
    <div id="tab-training" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-chalkboard-user"></i> Training & Seminar Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-training', this)" data-label="Add Training">
                <i class="fas fa-plus"></i> Add Training
            </button>
        </div>
        <div id="form-training" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Training / Seminar</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="training">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control" placeholder="e.g. Anti-Drug Campaign Seminar" required></div>
                        <div class="form-group"><label class="form-label">Type <span style="color:var(--crimson)">*</span></label><select name="training_type" class="form-control" required><?php $__currentLoopData = ['Training','Seminar','Workshop','Drill','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="training_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Duration</label><input type="text" name="duration" class="form-control" placeholder="e.g. 3 days, 8 hours"></div>
                        <div class="form-group"><label class="form-label">Venue</label><input type="text" name="venue" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Facilitator / Trainer</label><input type="text" name="facilitator" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Participants</label><input type="number" name="participants_count" class="form-control" min="0" value="0"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Notes</label><input type="text" name="notes" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Certificate / Attendance Sheet</label><input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-training', document.querySelector('[onclick*=form-training]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['trainings']) && $specificData['trainings']->count()): ?>
        <table>
            <thead><tr><th>Title</th><th>Type</th><th>Date</th><th>Duration</th><th>Venue</th><th>Facilitator</th><th style="text-align:right">Participants</th><th>File</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['trainings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($tr->title); ?></td>
                    <td><span class="badge badge-navy" style="font-size:10px"><?php echo e($tr->training_type); ?></span></td>
                    <td class="td-muted"><?php echo e($tr->training_date->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($tr->duration ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($tr->venue ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($tr->facilitator ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:600;color:var(--navy)"><?php echo e(number_format($tr->participants_count)); ?></td>
                    <td><?php if($tr->file_path): ?><a href="<?php echo e(asset('storage/'.$tr->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-chalkboard-user"></i><p>No training or seminar records yet.</p></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'health'): ?>
    <div id="tab-clinic-staff" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-user-doctor"></i> Clinic Doctors & Staff</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-clinic-staff', this)" data-label="Add Staff">
                <i class="fas fa-plus"></i> Add Staff
            </button>
        </div>
        <div id="form-clinic-staff" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Clinic Doctor / Staff</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="clinic-staff">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Position <span style="color:var(--crimson)">*</span></label><select name="position" class="form-control" required><?php $__currentLoopData = ['Doctor','Nurse','Midwife','BHW','Dentist','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($p); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Specialization</label><input type="text" name="specialization" class="form-control" placeholder="e.g. Pediatrics"></div>
                        <div class="form-group"><label class="form-label">Affiliation</label><input type="text" name="affiliation" class="form-control" placeholder="e.g. DOH, RHU, Private"></div>
                        <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Schedule</label><input type="text" name="schedule" class="form-control" placeholder="e.g. Mon–Fri 8am–5pm"></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Active','Inactive','On Leave']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-clinic-staff', document.querySelector('[onclick*=form-clinic-staff]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['clinic_staff']) && $specificData['clinic_staff']->count()): ?>
        <table>
            <thead><tr><th>Name</th><th>Position</th><th>Specialization</th><th>Affiliation</th><th>Contact</th><th>Schedule</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['clinic_staff']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($cs->full_name); ?></td>
                    <td><span class="badge badge-blue" style="font-size:10px"><?php echo e($cs->position); ?></span></td>
                    <td class="td-muted"><?php echo e($cs->specialization ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($cs->affiliation ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($cs->contact_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($cs->schedule ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($cs->status === 'Active' ? 'badge-green' : ($cs->status === 'On Leave' ? 'badge-yellow' : 'badge-gray')); ?>"><?php echo e($cs->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-user-doctor"></i><p>No clinic doctors or staff listed yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-medicine-inventory" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-pills"></i> Pharmacy Inventory</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-medicine', this)" data-label="Add Medicine">
                <i class="fas fa-plus"></i> Add Medicine
            </button>
        </div>

        
        <div id="form-medicine" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-capsules" style="color:var(--gold);margin-right:6px"></i> New Medicine Entry</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="medicine">
                    <div class="form-grid-3" style="gap:12px">
                        
                        <div class="form-group"><label class="form-label">Generic Name <span style="color:var(--crimson)">*</span></label><input type="text" name="medicine_name" class="form-control" placeholder="e.g. Paracetamol" required></div>
                        <div class="form-group"><label class="form-label">Brand Name</label><input type="text" name="brand_name" class="form-control" placeholder="e.g. Biogesic"></div>
                        <div class="form-group"><label class="form-label">Alt. Generic Name</label><input type="text" name="generic_name" class="form-control" placeholder="INN / USAN"></div>
                        
                        <div class="form-group"><label class="form-label">Category</label>
                            <select name="category" class="form-control">
                                <option value="">— Select —</option>
                                <?php $__currentLoopData = ['Analgesic / Pain Reliever','Antibiotic','Antihypertensive','Antidiabetic','Antihistamine','Vitamins & Supplements','Cardiovascular','Respiratory','GI / Antacid','Dermatological','Neurological','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option><?php echo e($cat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Dosage Form</label><input type="text" name="dosage_form" class="form-control" placeholder="e.g. 500mg Tablet"></div>
                        <div class="form-group"><label class="form-label">Unit <span style="color:var(--crimson)">*</span></label>
                            <select name="unit" class="form-control" required>
                                <?php $__currentLoopData = ['tablets','capsules','vials','sachets','bottles','ampules','boxes','strips','packs','other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option><?php echo e($u); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        <div class="form-group"><label class="form-label">Current Stock <span style="color:var(--crimson)">*</span></label><input type="number" name="current_stock" class="form-control" min="0" value="0" required></div>
                        <div class="form-group"><label class="form-label">Reorder Level <span style="color:var(--crimson)">*</span></label><input type="number" name="reorder_level" class="form-control" min="0" value="10" required></div>
                        <div class="form-group"><label class="form-label">Expiry Date</label><input type="date" name="expiry_date" class="form-control"></div>
                        
                        <div class="form-group"><label class="form-label">Supplier / Source</label><input type="text" name="supplier" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Batch / Lot Number</label><input type="text" name="batch_number" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Barcode <span style="color:var(--text-muted);font-weight:400">(optional)</span></label><input type="text" name="barcode" class="form-control" placeholder="Scan or type"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add to Inventory</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-medicine', document.querySelector('[data-label=\'Add Medicine\']'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
            $meds = $specificData['medicine_inventory'] ?? collect();
            $lowCount      = $meds->filter(fn($m) => $m->isLowStock())->count();
            $expiredCount  = $meds->filter(fn($m) => $m->isExpired())->count();
            $expiringCount = $meds->filter(fn($m) => $m->isExpiringSoon())->count();
            $categories    = $meds->pluck('category')->filter()->unique()->sort()->values();
        ?>

        <?php if($meds->count()): ?>

        
        <div class="med-stats-strip">
            <div class="med-stat">
                <div class="med-stat-num"><?php echo e($meds->count()); ?></div>
                <div class="med-stat-lbl">Total Items</div>
            </div>
            <div class="med-stat <?php echo e($lowCount ? 'warn' : ''); ?>">
                <div class="med-stat-num"><?php echo e($lowCount); ?></div>
                <div class="med-stat-lbl">Low Stock</div>
            </div>
            <div class="med-stat <?php echo e($expiringCount ? 'warn' : ''); ?>">
                <div class="med-stat-num"><?php echo e($expiringCount); ?></div>
                <div class="med-stat-lbl">Expiring ≤60d</div>
            </div>
            <div class="med-stat <?php echo e($expiredCount ? 'danger' : ''); ?>">
                <div class="med-stat-num"><?php echo e($expiredCount); ?></div>
                <div class="med-stat-lbl">Expired</div>
            </div>
            <div class="med-stat">
                <div class="med-stat-num"><?php echo e(number_format($meds->sum('current_stock'))); ?></div>
                <div class="med-stat-lbl">Total Units</div>
            </div>
        </div>

        
        <div class="med-search-bar">
            <input type="text" id="medSearch" class="med-search-input"
                   placeholder="Search generic or brand name…"
                   oninput="filterMeds()">
            <select id="medCatFilter" class="med-cat-filter" onchange="filterMeds()">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select id="medStatusFilter" class="med-cat-filter" onchange="filterMeds()">
                <option value="">All Status</option>
                <option value="low">Low Stock</option>
                <option value="expired">Expired</option>
                <option value="expiring">Expiring Soon</option>
            </select>
            <span id="medCount" style="font-size:.76rem;color:var(--text-muted);white-space:nowrap"></span>
        </div>

        
        <div id="stockAdjustModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
            <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:420px;padding:1.5rem;position:relative;box-shadow:0 12px 50px rgba(0,0,0,.25)">
                <button onclick="document.getElementById('stockAdjustModal').style.display='none'" style="position:absolute;top:.75rem;right:.75rem;background:none;border:none;font-size:1rem;color:#9ca3af;cursor:pointer"><i class="fas fa-times"></i></button>
                <div style="font-size:.95rem;font-weight:700;color:var(--navy);margin-bottom:.5rem;padding-bottom:.75rem;border-bottom:1px solid var(--border)">
                    <i class="fas fa-arrow-right-arrow-left" style="color:var(--gold)"></i>&nbsp; Adjust Stock
                </div>
                <div id="stockMedicineName" style="font-size:.82rem;margin-bottom:1rem"></div>
                <form id="stockAdjustForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div style="margin-bottom:.9rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--navy);margin-bottom:.35rem">Transaction Type</label>
                        <select name="adjustment_type" style="width:100%;padding:.5rem .8rem;border:1.5px solid #d1d5db;border-radius:6px;font-family:'Poppins',sans-serif;font-size:.83rem">
                            <option value="in">📦 Stock In — received new supply</option>
                            <option value="out">💊 Dispense / Issue Out</option>
                            <option value="disposed">🗑️ Disposed / Expired removal</option>
                        </select>
                    </div>
                    <div style="margin-bottom:.9rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--navy);margin-bottom:.35rem">Quantity <span style="color:var(--crimson)">*</span></label>
                        <input type="number" name="quantity" min="1" value="1" style="width:100%;padding:.5rem .8rem;border:1.5px solid #d1d5db;border-radius:6px;font-family:'Poppins',sans-serif;font-size:.83rem" required>
                    </div>
                    <div style="margin-bottom:1.25rem">
                        <label style="display:block;font-size:.78rem;font-weight:600;color:var(--navy);margin-bottom:.35rem">Reason / Notes</label>
                        <input type="text" name="reason" placeholder="e.g. Monthly DOH supply, dispensed to patient…" style="width:100%;padding:.5rem .8rem;border:1.5px solid #d1d5db;border-radius:6px;font-family:'Poppins',sans-serif;font-size:.83rem">
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:.6rem">
                        <button type="button" onclick="document.getElementById('stockAdjustModal').style.display='none'" class="btn btn-secondary btn-sm">Cancel</button>
                        <button type="submit" class="btn btn-gold btn-sm"><i class="fas fa-save"></i> Save Transaction</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div id="medDetailsModal" class="med-modal-backdrop">
            <div class="med-modal">
                <div class="med-modal-header">
                    <div>
                        <div class="med-modal-title" id="md-title">Medicine Details</div>
                        <div class="med-modal-subtitle" id="md-subtitle"></div>
                    </div>
                    <button class="med-modal-close" onclick="closeMedDetails()"><i class="fas fa-times"></i></button>
                </div>
                <div class="med-modal-body">
                    
                    <div class="med-section-title"><i class="fas fa-info-circle"></i> Specifications</div>
                    <div class="med-specs-grid">
                        <div class="med-spec-item full">
                            <div class="med-spec-label">Generic Name (Official)</div>
                            <div class="med-spec-value" id="md-medicine-name" style="font-size:1rem"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Brand Name</div>
                            <div class="med-spec-value" id="md-brand-name"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Category</div>
                            <div class="med-spec-value" id="md-category"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Dosage Form</div>
                            <div class="med-spec-value" id="md-dosage-form" style="font-size:1.05rem;font-weight:800"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Unit of Measure</div>
                            <div class="med-spec-value" id="md-unit"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Current Stock</div>
                            <div class="med-spec-value" id="md-current-stock" style="font-size:1.2rem"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Reorder Level</div>
                            <div class="med-spec-value" id="md-reorder-level"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Expiry Date</div>
                            <div class="med-spec-value" id="md-expiry-date"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Supplier / Source</div>
                            <div class="med-spec-value" id="md-supplier"></div>
                        </div>
                        <div class="med-spec-item">
                            <div class="med-spec-label">Batch / Lot Number</div>
                            <div class="med-spec-value" id="md-batch-number"></div>
                        </div>
                        <div class="med-spec-item full">
                            <div class="med-spec-label">Barcode</div>
                            <div class="med-spec-value" id="md-barcode" style="font-family:monospace;letter-spacing:.05em"></div>
                        </div>
                    </div>

                    
                    <div class="med-section-title" style="margin-top:1.25rem"><i class="fas fa-clock-rotate-left"></i> Stock Transaction History</div>
                    <div style="overflow-x:auto">
                        <table class="log-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th style="text-align:right">Qty</th>
                                    <th style="text-align:right">Before</th>
                                    <th style="text-align:right">After</th>
                                    <th>Reason / By</th>
                                </tr>
                            </thead>
                            <tbody id="md-logs-tbody">
                                <tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:20px">No history yet.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        
        <div style="overflow-x:auto">
        <table id="medTable" style="width:100%;border-collapse:collapse;font-size:.83rem">
            <thead>
                <tr>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Medicine (Generic / Brand)</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Category</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Dosage</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:right;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Stock</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Expiry</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:center;font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $meds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $catClass = match($med->category) {
                        'Analgesic / Pain Reliever'  => 'cat-gold',
                        'Vitamins & Supplements'     => 'cat-green',
                        'Antibiotic'                 => 'cat-navy',
                        'Cardiovascular'             => 'cat-navy',
                        'Antihypertensive'           => 'cat-purple',
                        'Neurological'               => 'cat-purple',
                        'Antidiabetic'               => 'cat-purple',
                        'Antihistamine'              => 'cat-blue',
                        'Respiratory'                => 'cat-blue',
                        'GI / Antacid'               => 'cat-blue',
                        'Dermatological'             => 'cat-blue',
                        default                      => 'cat-gray',
                    };
                    $stockClass = $med->isLowStock() ? 'stock-low' : 'stock-ok';
                    $expiryClass = $med->isExpired() ? 'td-danger' : ($med->isExpiringSoon() ? '' : 'td-muted');
                ?>
                <tr class="med-row"
                    data-generic="<?php echo e(strtolower($med->medicine_name)); ?>"
                    data-brand="<?php echo e(strtolower($med->brand_name ?? '')); ?>"
                    data-category="<?php echo e($med->category); ?>"
                    data-low="<?php echo e($med->isLowStock() ? '1' : '0'); ?>"
                    data-expired="<?php echo e($med->isExpired() ? '1' : '0'); ?>"
                    data-expiring="<?php echo e($med->isExpiringSoon() ? '1' : '0'); ?>"
                    style="border-bottom:1px solid #f3f4f6">
                    <td style="padding:.8rem 1rem;vertical-align:middle">
                        <div class="med-primary"><?php echo e($med->medicine_name); ?></div>
                        <?php if($med->brand_name): ?><div class="med-brand"><?php echo e($med->brand_name); ?></div><?php endif; ?>
                        <?php if($med->barcode): ?><div class="med-barcode"><?php echo e($med->barcode); ?></div><?php endif; ?>
                    </td>
                    <td style="padding:.8rem 1rem;vertical-align:middle">
                        <?php if($med->category): ?>
                            <span class="cat-badge <?php echo e($catClass); ?>"><?php echo e($med->category); ?></span>
                        <?php else: ?>
                            <span class="td-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="padding:.8rem 1rem;vertical-align:middle">
                        <span class="dosage-text"><?php echo e($med->dosage_form ?? '—'); ?></span>
                    </td>
                    <td style="padding:.8rem 1rem;vertical-align:middle;text-align:right">
                        <span class="stock-num <?php echo e($stockClass); ?>"><?php echo e(number_format($med->current_stock)); ?></span>
                        <span class="stock-unit"><?php echo e($med->unit); ?></span>
                        <?php if($med->isLowStock()): ?><span class="low-badge">Low</span><?php endif; ?>
                    </td>
                    <td style="padding:.8rem 1rem;vertical-align:middle" class="<?php echo e($expiryClass); ?>">
                        <?php echo e($med->expiry_date?->format('M d, Y') ?? '—'); ?>

                        <?php if($med->isExpired()): ?><span class="low-badge">Expired</span>
                        <?php elseif($med->isExpiringSoon()): ?><span class="expiring-badge">Soon</span><?php endif; ?>
                    </td>
                    <td style="padding:.8rem 1rem;vertical-align:middle;text-align:center;white-space:nowrap">
                        <button type="button"
                                onclick="openStockModal(<?php echo e($med->id); ?>, '<?php echo e(addslashes($med->medicine_name)); ?>', <?php echo e($med->current_stock); ?>)"
                                class="btn btn-primary btn-sm btn-icon" title="Adjust stock">
                            <i class="fas fa-arrow-right-arrow-left"></i>
                        </button>
                        <button type="button"
                                onclick="openMedDetails(<?php echo e($med->id); ?>)"
                                class="btn btn-secondary btn-sm btn-icon" title="View details & history">
                            <i class="fas fa-eye"></i>
                        </button>
                        <form method="POST" action="<?php echo e(route('committees.destroyMedicine', [$committee['slug'], $med->id])); ?>" style="display:inline" onsubmit="return confirm('Delete <?php echo e(addslashes($med->medicine_name)); ?>?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>

        
        <script>
        const __medicineData = <?php echo json_encode($meds->keyBy('id')->map(fn($m) => [
            'medicine_name' => $m->medicine_name, 'brand_name'    => $m->brand_name, 'generic_name'  => $m->generic_name) ?>;
        </script>

        <?php else: ?>
        <div class="empty-state"><i class="fas fa-pills"></i><p>No medicines in inventory yet. Add one above.</p></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'environment'): ?>
    <div id="tab-sweepers" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-broom"></i> Street Sweeper Registry</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-sweepers', this)" data-label="Add Sweeper">
                <i class="fas fa-plus"></i> Add Sweeper
            </button>
        </div>
        <div id="form-sweepers" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Street Sweeper</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="sweeper">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Active','Inactive','On Leave']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Assigned Zone / Area</label><input type="text" name="assigned_zone" class="form-control" placeholder="e.g. Purok 3 — Main Road"></div>
                        <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Date Assigned</label><input type="date" name="date_assigned" class="form-control"></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Schedule</label><input type="text" name="schedule" class="form-control" placeholder="e.g. Mon–Sat 6am–10am"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-sweepers', document.querySelector('[onclick*=form-sweepers]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['sweepers']) && $specificData['sweepers']->count()): ?>
        <table>
            <thead><tr><th>Name</th><th>Assigned Zone</th><th>Schedule</th><th>Contact</th><th>Date Assigned</th><th>Status</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['sweepers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($sw->full_name); ?></td>
                    <td class="td-muted"><?php echo e($sw->assigned_zone ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($sw->schedule ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($sw->contact_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($sw->date_assigned?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($sw->status === 'Active' ? 'badge-green' : ($sw->status === 'On Leave' ? 'badge-yellow' : 'badge-gray')); ?>"><?php echo e($sw->status); ?></span></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-broom"></i><p>No street sweepers registered yet.</p></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'infrastructure'): ?>
    <div id="tab-contracts" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-file-signature"></i> Permits & Contracts</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-contracts', this)" data-label="Add Contract">
                <i class="fas fa-plus"></i> Add Contract
            </button>
        </div>
        <div id="form-contracts" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Permit / Contract</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="contract">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group"><label class="form-label">Contract Number</label><input type="text" name="contract_number" class="form-control" placeholder="e.g. BNE-2026-001"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Contractor / Supplier Name <span style="color:var(--crimson)">*</span></label><input type="text" name="contractor_name" class="form-control" required></div>
                        <div class="form-group" style="grid-column:span 3"><label class="form-label">Scope of Work</label><input type="text" name="scope_of_work" class="form-control" placeholder="Brief description of the contract"></div>
                        <div class="form-group"><label class="form-label">Contract Amount (₱)</label><input type="number" name="contract_amount" class="form-control" min="0" step="0.01"></div>
                        <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control"></div>
                        <div class="form-group"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Status <span style="color:var(--crimson)">*</span></label><select name="status" class="form-control" required><?php $__currentLoopData = ['Pending','Active','Completed','Terminated']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Contract Document</label><input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-contracts', document.querySelector('[onclick*=form-contracts]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['contracts']) && $specificData['contracts']->count()): ?>
        <table>
            <thead><tr><th>Contract No.</th><th>Contractor</th><th>Scope</th><th>Amount</th><th>Duration</th><th>Status</th><th>File</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['contracts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="td-mono"><?php echo e($ct->contract_number ?? '—'); ?></td>
                    <td style="font-weight:600"><?php echo e($ct->contractor_name); ?></td>
                    <td class="td-muted" style="max-width:200px;white-space:normal"><?php echo e($ct->scope_of_work ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($ct->contract_amount ? '₱'.number_format($ct->contract_amount, 2) : '—'); ?></td>
                    <td class="td-muted">
                        <?php if($ct->start_date && $ct->end_date): ?> <?php echo e($ct->start_date->format('M d')); ?> – <?php echo e($ct->end_date->format('M d, Y')); ?>

                        <?php elseif($ct->start_date): ?> From <?php echo e($ct->start_date->format('M d, Y')); ?>

                        <?php else: ?> —
                        <?php endif; ?>
                    </td>
                    <td><span class="badge <?php echo e(match($ct->status) { 'Active'=>'badge-green','Completed'=>'badge-blue','Terminated'=>'badge-red',default=>'badge-yellow' }); ?>"><?php echo e($ct->status); ?></span></td>
                    <td><?php if($ct->file_path): ?><a href="<?php echo e(asset('storage/'.$ct->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-file-signature"></i><p>No contracts or permits recorded yet.</p></div>
        <?php endif; ?>
    </div>

    
    <div id="tab-financials" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-money-bill-wave"></i> Financial Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-financials', this)" data-label="Add Record">
                <i class="fas fa-plus"></i> Add Record
            </button>
        </div>
        <div id="form-financials" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Financial Record</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="financial">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Title / Description <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control" required placeholder="e.g. Road Repair Fund Utilization Q1"></div>
                        <div class="form-group"><label class="form-label">Type <span style="color:var(--crimson)">*</span></label><select name="type" class="form-control" required><?php $__currentLoopData = ['Budget','Utilization','Liquidation']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Amount (₱) <span style="color:var(--crimson)">*</span></label><input type="number" name="amount" class="form-control" min="0" step="0.01" required></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Fund Source</label><input type="text" name="fund_source" class="form-control" placeholder="e.g. LDRRMF, GAA, Barangay Fund"></div>
                        <div class="form-group"><label class="form-label">Reference No.</label><input type="text" name="reference_number" class="form-control" placeholder="e.g. DV-2026-001"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Supporting Document</label><input type="file" name="file" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                    </div>
                    <div style="margin-top:14px;display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleForm('form-financials', document.querySelector('[onclick*=form-financials]'))">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if(isset($specificData['financials']) && $specificData['financials']->count()): ?>
        <?php
            $budgetTotal      = $specificData['financials']->where('type','Budget')->sum('amount');
            $utilizationTotal = $specificData['financials']->where('type','Utilization')->sum('amount');
            $liquidationTotal = $specificData['financials']->where('type','Liquidation')->sum('amount');
        ?>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:16px 20px;border-bottom:1px solid var(--border)">
            <div style="padding:14px;background:var(--surface2);border-radius:var(--radius);border:1px solid var(--border);text-align:center">
                <div style="font-size:18px;font-weight:700;color:var(--navy)">₱<?php echo e(number_format($budgetTotal,2)); ?></div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px">Total Budget</div>
            </div>
            <div style="padding:14px;background:var(--surface2);border-radius:var(--radius);border:1px solid var(--border);text-align:center">
                <div style="font-size:18px;font-weight:700;color:var(--gold)">₱<?php echo e(number_format($utilizationTotal,2)); ?></div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px">Total Utilized</div>
            </div>
            <div style="padding:14px;background:var(--surface2);border-radius:var(--radius);border:1px solid var(--border);text-align:center">
                <div style="font-size:18px;font-weight:700;color:#16a34a">₱<?php echo e(number_format($liquidationTotal,2)); ?></div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:3px">Total Liquidated</div>
            </div>
        </div>
        <table>
            <thead><tr><th>Title</th><th>Type</th><th>Fund Source</th><th style="text-align:right">Amount</th><th>Date</th><th>Ref No.</th><th>File</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['financials']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-weight:600"><?php echo e($fin->title); ?></td>
                    <td><span class="badge <?php echo e(match($fin->type) { 'Budget'=>'badge-navy','Utilization'=>'badge-yellow','Liquidation'=>'badge-green' }); ?>" style="font-size:10px"><?php echo e($fin->type); ?></span></td>
                    <td class="td-muted"><?php echo e($fin->fund_source ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)">₱<?php echo e(number_format($fin->amount, 2)); ?></td>
                    <td class="td-muted"><?php echo e($fin->date->format('M d, Y')); ?></td>
                    <td class="td-mono"><?php echo e($fin->reference_number ?? '—'); ?></td>
                    <td><?php if($fin->file_path): ?><a href="<?php echo e(asset('storage/'.$fin->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state"><i class="fas fa-money-bill-wave"></i><p>No financial records yet.</p></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function switchTab(id) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
    const content = document.getElementById('tab-' + id);
    const btn = document.getElementById('tab-btn-' + id);
    if (content) content.classList.add('active');
    if (btn) btn.classList.add('active');
    history.replaceState(null, '', '#' + id);
}

function toggleForm(id, btnEl) {
    const panel = document.getElementById(id);
    const isOpen = panel.classList.toggle('open');
    if (btnEl) {
        const label = btnEl.dataset.label || 'Add';
        btnEl.innerHTML = isOpen
            ? '<i class="fas fa-times"></i> Cancel'
            : '<i class="fas fa-plus"></i> ' + label;
        btnEl.className = isOpen ? 'btn btn-secondary btn-sm' : 'btn btn-primary btn-sm';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#', '');
    if (hash && document.getElementById('tab-' + hash)) switchTab(hash);
});

// ── Medicine inventory JS ────────────────────────────────────
function openStockModal(medId, medName, currentStock) {
    const baseSlug = '<?php echo e($committee['slug']); ?>';
    document.getElementById('stockAdjustForm').action = '/committees/' + baseSlug + '/medicine/' + medId + '/adjust';
    document.getElementById('stockMedicineName').innerHTML =
        '<strong style="color:var(--navy)">' + medName + '</strong>' +
        '&nbsp;<span style="color:#9ca3af">|</span>&nbsp;' +
        'Current stock: <strong>' + currentStock + '</strong>';
    document.getElementById('stockAdjustModal').style.display = 'flex';
}
document.getElementById('stockAdjustModal')?.addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

function openMedDetails(medId) {
    const med = (typeof __medicineData !== 'undefined') ? __medicineData[medId] : null;
    if (!med) return;

    document.getElementById('md-title').textContent      = med.medicine_name || '—';
    document.getElementById('md-subtitle').textContent   = med.brand_name ? ('Brand: ' + med.brand_name) : (med.category || '');
    document.getElementById('md-medicine-name').textContent = med.medicine_name || '—';
    document.getElementById('md-brand-name').textContent    = med.brand_name   || '—';
    document.getElementById('md-category').textContent      = med.category     || '—';
    document.getElementById('md-dosage-form').textContent   = med.dosage_form  || '—';
    document.getElementById('md-unit').textContent          = med.unit         || '—';
    document.getElementById('md-barcode').textContent       = med.barcode      || '—';
    document.getElementById('md-supplier').textContent      = med.supplier     || '—';
    document.getElementById('md-batch-number').textContent  = med.batch_number || '—';
    document.getElementById('md-reorder-level').textContent = med.reorder_level;

    const stockEl = document.getElementById('md-current-stock');
    stockEl.textContent = med.current_stock + ' ' + (med.unit || '');
    stockEl.style.color = med.current_stock <= med.reorder_level ? 'var(--crimson)' : 'var(--navy)';

    const expiryEl = document.getElementById('md-expiry-date');
    expiryEl.textContent = med.expiry_date || '—';

    // Populate logs
    const tbody = document.getElementById('md-logs-tbody');
    tbody.innerHTML = '';
    const logs = med.logs || [];
    if (logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:20px;font-size:.8rem">No transactions recorded yet.</td></tr>';
    } else {
        logs.forEach(function(log) {
            const typeMap = { in: '📦 Stock In', out: '💊 Dispensed', disposed: '🗑️ Disposed' };
            const clsMap  = { in: 'log-in', out: 'log-out', disposed: 'log-disposed' };
            const note = [log.reason, log.by ? ('by ' + log.by) : ''].filter(Boolean).join(' · ');
            tbody.innerHTML += '<tr>' +
                '<td style="font-size:.75rem;color:#6b7280;white-space:nowrap">' + (log.date || '') + '</td>' +
                '<td class="' + (clsMap[log.type] || '') + '" style="font-size:.78rem">' + (typeMap[log.type] || log.type) + '</td>' +
                '<td style="text-align:right;font-size:.82rem;font-weight:700">' + log.qty + '</td>' +
                '<td style="text-align:right;font-size:.78rem;color:#6b7280">' + log.before + '</td>' +
                '<td style="text-align:right;font-size:.82rem;font-weight:700;color:var(--navy)">' + log.after + '</td>' +
                '<td style="font-size:.74rem;color:#6b7280">' + (note || '—') + '</td>' +
                '</tr>';
        });
    }

    document.getElementById('medDetailsModal').classList.add('open');
}
function closeMedDetails() {
    document.getElementById('medDetailsModal').classList.remove('open');
}
document.getElementById('medDetailsModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeMedDetails();
});

// Live search + filter for medicine table
function filterMeds() {
    const q       = (document.getElementById('medSearch')?.value || '').toLowerCase();
    const cat     = (document.getElementById('medCatFilter')?.value || '').toLowerCase();
    const status  = (document.getElementById('medStatusFilter')?.value || '');
    const rows    = document.querySelectorAll('#medTable .med-row');
    let visible   = 0;

    rows.forEach(function(row) {
        const generic   = row.dataset.generic  || '';
        const brand     = row.dataset.brand    || '';
        const rowCat    = (row.dataset.category || '').toLowerCase();
        const isLow     = row.dataset.low     === '1';
        const isExpired = row.dataset.expired  === '1';
        const isExpiring= row.dataset.expiring === '1';

        const matchQ   = !q   || generic.includes(q) || brand.includes(q);
        const matchCat = !cat || rowCat === cat;
        const matchSt  = !status
            || (status === 'low'      && isLow)
            || (status === 'expired'  && isExpired)
            || (status === 'expiring' && isExpiring);

        const show = matchQ && matchCat && matchSt;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    const countEl = document.getElementById('medCount');
    if (countEl) countEl.textContent = 'Showing ' + visible + ' of ' + rows.length;
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/committees/show.blade.php ENDPATH**/ ?>