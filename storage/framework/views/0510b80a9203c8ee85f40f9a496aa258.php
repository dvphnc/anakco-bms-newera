<?php $__env->startSection('title', $committee['name']); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Tab Strip ───────────────────────────────────────────── */
.tab-strip-wrap {
    position: relative;
    background: var(--surface);
    border-radius: var(--radius-lg) var(--radius-lg) 0 0;
    border-bottom: 2px solid var(--border);
    display: flex;
    align-items: stretch;
}
.tab-scroll-btn {
    flex-shrink: 0;
    width: 32px;
    background: var(--surface);
    border: none;
    border-bottom: 2px solid var(--border);
    color: var(--text-muted);
    cursor: pointer;
    font-size: 11px;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2;
    transition: color .15s, background .15s;
}
.tab-scroll-btn:hover { color: var(--navy); background: var(--surface2); }
.tab-scroll-btn.left  { border-radius: var(--radius-lg) 0 0 0; border-right: 1px solid var(--border); }
.tab-scroll-btn.right { border-radius: 0 var(--radius-lg) 0 0; border-left: 1px solid var(--border); }
.tab-scroll-btn.visible { display: flex; }
.tab-strip {
    flex: 1;
    display: flex;
    padding: 0 12px;
    gap: 2px;
    overflow-x: auto;
    scrollbar-width: none;
    background: transparent;
    min-width: 0;
}
.tab-strip::-webkit-scrollbar { display: none; }
.tab-btn {
    padding: 13px 16px;
    font-size: 13px;
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
.tab-btn:hover  { color: var(--navy); background: rgba(13,33,68,0.03); }
.tab-btn.active {
    color: var(--navy);
    border-bottom: 3px solid var(--gold);
    background: linear-gradient(180deg, transparent 55%, rgba(200,134,26,0.07) 100%);
    font-weight: 700;
}
.tab-btn .tab-count {
    font-size: 11px; font-weight: 700;
    padding: 1px 7px; border-radius: 99px;
    background: var(--surface3);
    color: var(--text-muted);
    transition: background .15s, color .15s;
}
.tab-btn.active .tab-count {
    background: var(--gold-pale);
    color: var(--gold);
    border: 1px solid var(--gold-border);
}

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
.panel-hd-title i { color: var(--navy); opacity: 0.7; }

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
    font-size: 12px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .08em;
    color: var(--text-subtle); margin-bottom: 14px;
    line-height: 1.6;
}

/* ── Photo Grid ──────────────────────────────────────────── */
.photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 10px;
    padding: 16px 20px;
}
.photo-thumb {
    border-radius: var(--radius-sm);
    border: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    background: var(--surface);
    transition: box-shadow .15s;
}
.photo-thumb:hover { box-shadow: 0 2px 10px rgba(0,0,0,.08); }
.photo-thumb-img {
    overflow: hidden;
    border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    line-height: 0;
    cursor: zoom-in;
    flex-shrink: 0;
}
.photo-thumb img { width: 100%; height: 100px; object-fit: cover; display: block; transition: transform .2s; }
.photo-thumb-img:hover img { transform: scale(1.04); }
.photo-thumb-label { padding: 6px 8px; font-size: 12px; font-weight: 600; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1; }
.photo-thumb-actions { padding: 5px 6px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 4px; flex-shrink: 0; }

/* ── Accomplishment Cards ────────────────────────────────── */
.acc-list { padding: 16px 20px; display: flex; flex-direction: column; gap: 10px; }
.acc-card {
    padding: 14px 16px;
    background: var(--surface2);
    border: 1px solid var(--border);
    border-left: 4px solid var(--committee-color, var(--gold));
    border-radius: var(--radius-sm);
}
.acc-card-title { font-weight: 600; font-size: 14px; margin-bottom: 3px; }
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
.med-stat-lbl { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: .05em; margin-top: 3px; }
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

/* ── Enhanced Empty States ─────────────────────────────────── */
.empty-enhanced {
    text-align: center;
    padding: 52px 24px 48px;
    background: var(--surface);
}
.empty-enhanced-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: #EEF2F7;
    border: 2px solid #D6DCE8;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 18px;
    font-size: 26px;
    color: var(--navy);
    opacity: 0.75;
}
.empty-enhanced h4 {
    font-size: 14px; font-weight: 700;
    color: var(--navy); margin-bottom: 6px;
}
.empty-enhanced p {
    font-size: 13px; color: var(--text-muted);
    max-width: 340px; margin: 0 auto 18px;
    line-height: 1.6;
}
.empty-enhanced .btn { font-size: 13px; }

/* ── Compact table rows ────────────────────────────────────── */
table thead th { font-size: 12px; }
table tbody td { font-size: 13.5px; line-height: 1.55; }

/* ── Attendance summary strip ──────────────────────────────── */
.att-summary-strip {
    display: flex; gap: 0;
    background: #F8FAFE;
    border-bottom: 1px solid var(--border);
}
.att-summary-item {
    flex: 1; padding: 10px 16px;
    border-right: 1px solid var(--border);
    text-align: center;
}
.att-summary-item:last-child { border-right: none; }
.att-summary-num { font-size: 16px; font-weight: 700; color: var(--navy); line-height: 1; }
.att-summary-lbl { font-size: 11px; color: var(--text-muted); margin-top: 3px; text-transform: uppercase; letter-spacing: .04em; }

/* ── Gold spinner on buttons ───────────────────────────────── */
.btn-loading { pointer-events: none; opacity: 0.8; }
.fa-spin-gold { color: #C8861A !important; }

/* ── Edit / CRUD Modals ────────────────────────────────────── */
.crud-modal-backdrop {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.45); z-index: 1100;
    align-items: center; justify-content: center; padding: 16px;
}
.crud-modal-backdrop.open { display: flex; }
.crud-modal {
    background: #fff; border-radius: var(--radius-lg);
    width: 100%; max-width: 640px; max-height: 90vh;
    overflow-y: auto; box-shadow: 0 16px 48px rgba(0,0,0,.22);
}
.crud-modal-header {
    padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; background: #fff; z-index: 1;
}
.crud-modal-title { font-size: .95rem; font-weight: 700; color: var(--navy); display: flex; align-items: center; gap: 8px; }
.crud-modal-title i { color: var(--gold); }
.crud-modal-close { background: none; border: none; font-size: 1rem; color: #9ca3af; cursor: pointer; padding: 4px; line-height: 1; }
.crud-modal-close:hover { color: var(--crimson); }
.crud-modal-body { padding: 1.25rem; }
.crud-modal-footer { padding: .75rem 1.25rem; border-top: 1px solid var(--border); display: flex; gap: 8px; justify-content: flex-end; background: var(--surface2); border-radius: 0 0 var(--radius-lg) var(--radius-lg); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header">
    <div style="display:flex;align-items:center;gap:14px">
        <div style="width:48px;height:48px;border-radius:var(--radius);display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;background:#EEF2F7;color:var(--navy)">
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
        <div class="stat-icon" style="background:#EEF2F7;color:#0D2144;border:1px solid #D6DCE8"><i class="fas fa-folder-open"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($photos->count() + $reports->count() + $resolutions->count() + $otherRecords->count()); ?></div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2F7;color:#0D2144;border:1px solid #D6DCE8"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($activities->count() + $accomplishments->count()); ?></div>
            <div class="stat-label">Activities</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2F7;color:#0D2144;border:1px solid #D6DCE8"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($attendances->sum('total_attendees'))); ?></div>
            <div class="stat-label">Total Attendees</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#EEF2F7;color:#0D2144;border:1px solid #D6DCE8"><i class="fas fa-boxes-stacked"></i></div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($inventory->count()); ?></div>
            <div class="stat-label">Inventory Items</div>
        </div>
    </div>
</div>


<div class="card" style="overflow:hidden">

    
    <div class="tab-strip-wrap">
    <button class="tab-scroll-btn left" id="tabScrollLeft" onclick="tabScroll(-1)" title="Scroll left"><i class="fas fa-chevron-left"></i></button>
    <div class="tab-strip" id="tabStrip">
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
    <button class="tab-scroll-btn right" id="tabScrollRight" onclick="tabScroll(1)" title="Scroll right"><i class="fas fa-chevron-right"></i></button>
    </div>

    
    <div id="tab-records" class="tab-content active">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-folder-open"></i> Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-records', this)" data-label="Upload Record" data-icon="fa-cloud-arrow-up">
                <i class="fas fa-cloud-arrow-up"></i> Upload Record
            </button>
        </div>
        <div id="form-records" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-upload" style="color:var(--gold);margin-right:6px"></i> Upload New Record</div>
                <form method="POST" action="<?php echo e(route('committees.storeRecord', $committee['slug'])); ?>" enctype="multipart/form-data" data-axios="true">
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
                            <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Record title" value="<?php echo e(old('title')); ?>" required>
                            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
        <div id="recPhotoSection">
        <div class="data-section-label"><i class="fas fa-images" style="color:var(--gold);margin-right:6px"></i> Photos (<?php echo e($photos->count()); ?>)</div>
        <div class="photo-grid">
            <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="photo-thumb"
                 data-id="<?php echo e($photo->id); ?>"
                 data-title="<?php echo e(addslashes($photo->title)); ?>"
                 data-rtype="Photo"
                 data-description="<?php echo e(addslashes($photo->description ?? '')); ?>">
                <?php if($photo->file_path): ?>
                <div class="photo-thumb-img" onclick="openPhotoLightbox('<?php echo e(asset('storage/'.$photo->file_path)); ?>','<?php echo e(addslashes($photo->title)); ?>')">
                    <img src="<?php echo e(asset('storage/'.$photo->file_path)); ?>" alt="<?php echo e($photo->title); ?>">
                </div>
                <?php else: ?>
                <div class="photo-thumb-img" style="cursor:default;height:100px;background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--text-muted)">
                    <i class="fas fa-image fa-2x"></i>
                </div>
                <?php endif; ?>
                <div class="photo-thumb-label"><?php echo e($photo->title); ?></div>
                <div class="photo-thumb-actions">
                    <button type="button" onclick="openEditRecord(this.closest('[data-id]'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                    <button type="button" onclick="deleteGeneric('records','<?php echo e($photo->id); ?>','<?php echo e(addslashes($photo->title)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        </div>
        <?php endif; ?>

        <?php $docRecords = $reports->concat($resolutions)->concat($otherRecords)->sortByDesc('created_at'); ?>
        <?php if($docRecords->count()): ?>
        <div id="recDocSection">
        <div class="data-section-label" style="border-top:1px solid var(--border)"><i class="fas fa-file-alt" style="color:var(--gold);margin-right:6px"></i> Documents (<?php echo e($docRecords->count()); ?>)</div>
        <table>
            <thead><tr><th>Title</th><th>Type</th><th>Description</th><th>Uploaded</th><th>File</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $docRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-id="<?php echo e($rec->id); ?>"
                    data-title="<?php echo e(addslashes($rec->title)); ?>"
                    data-rtype="<?php echo e($rec->record_type); ?>"
                    data-description="<?php echo e(addslashes($rec->description ?? '')); ?>">
                    <td style="font-weight:600"><?php echo e($rec->title); ?></td>
                    <td><span class="badge badge-navy"><?php echo e($rec->record_type); ?></span></td>
                    <td class="td-muted"><?php echo e($rec->description ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($rec->created_at->format('M d, Y')); ?></td>
                    <td>
                        <?php if($rec->file_path): ?>
                        <a href="<?php echo e(asset('storage/'.$rec->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a>
                        <?php else: ?> <span class="td-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditRecord(this.closest('[data-id]'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteGeneric('records','<?php echo e($rec->id); ?>','<?php echo e(addslashes($rec->title)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>

        <?php if($photos->count() === 0 && $docRecords->count() === 0): ?>
        <div class="empty-enhanced" id="recEmptyState">
            <div class="empty-enhanced-icon"><i class="fas fa-folder-open"></i></div>
            <h4>No Records Uploaded Yet</h4>
            <p>Upload photos, reports, or resolutions to keep your committee records organized.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-records', document.querySelector('[data-icon=fa-cloud-arrow-up]'))">
                <i class="fas fa-cloud-arrow-up"></i> Upload First Record
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-activities" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-calendar-check"></i> Activities</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-activities', this)" data-label="Log Activity" data-icon="fa-calendar-plus">
                <i class="fas fa-calendar-plus"></i> Log Activity
            </button>
        </div>
        <div id="form-activities" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log New Activity</div>
                <form method="POST" action="<?php echo e(route('committees.storeActivity', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="activity_type" value="Activity">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Activity Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Barangay Assembly" value="<?php echo e(old('title')); ?>" required><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Title</th><th>Date</th><th>Location</th><th>Participants</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($act->id); ?>"
                    data-title="<?php echo e(addslashes($act->title)); ?>"
                    data-date="<?php echo e($act->activity_date->format('Y-m-d')); ?>"
                    data-location="<?php echo e(addslashes($act->location ?? '')); ?>"
                    data-participants="<?php echo e($act->participants_count ?? 0); ?>"
                    data-status="<?php echo e($act->status); ?>"
                    data-description="<?php echo e(addslashes($act->description ?? '')); ?>"
                    data-type="<?php echo e($act->activity_type); ?>"
                >
                    <td>
                        <div style="font-weight:600"><?php echo e($act->title); ?></div>
                        <?php if($act->description): ?><div class="td-muted"><?php echo e($act->description); ?></div><?php endif; ?>
                    </td>
                    <td class="td-muted"><?php echo e(\Carbon\Carbon::parse($act->activity_date)->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($act->location ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e(number_format($act->participants_count)); ?></td>
                    <td><span class="badge <?php echo e(match($act->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($act->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditActivity(this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteGeneric('activities','<?php echo e($act->id); ?>','<?php echo e(addslashes($act->title)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-calendar-check"></i></div>
            <h4>No Activities Logged Yet</h4>
            <p>Track committee meetings, programs, and barangay events here.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-activities', document.querySelector('[data-icon=fa-calendar-plus]'))">
                <i class="fas fa-calendar-plus"></i> Log First Activity
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-accomplishments" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-trophy"></i> Accomplishments</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-accomplishments', this)" data-label="Log Accomplishment" data-icon="fa-trophy">
                <i class="fas fa-trophy"></i> Log Accomplishment
            </button>
        </div>
        <div id="form-accomplishments" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Accomplishment</div>
                <form method="POST" action="<?php echo e(route('committees.storeActivity', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="activity_type" value="Accomplishment">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title')); ?>" required><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <div class="acc-card"
                data-id="<?php echo e($acc->id); ?>"
                data-title="<?php echo e(addslashes($acc->title)); ?>"
                data-date="<?php echo e($acc->activity_date->format('Y-m-d')); ?>"
                data-location="<?php echo e(addslashes($acc->location ?? '')); ?>"
                data-participants="<?php echo e($acc->participants_count ?? 0); ?>"
                data-status="<?php echo e($acc->status); ?>"
                data-description="<?php echo e(addslashes($acc->description ?? '')); ?>"
                data-type="Accomplishment"
            >
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap">
                    <div style="flex:1;min-width:0">
                        <div class="acc-card-title"><?php echo e($acc->title); ?></div>
                        <?php if($acc->description): ?><div class="td-muted" style="font-size:14px;margin-bottom:4px"><?php echo e($acc->description); ?></div><?php endif; ?>
                        <div class="acc-card-meta">
                            <span><i class="fas fa-calendar-alt" style="margin-right:4px"></i><?php echo e(\Carbon\Carbon::parse($acc->activity_date)->format('M d, Y')); ?></span>
                            <?php if($acc->location): ?><span><i class="fas fa-location-dot" style="margin-right:4px"></i><?php echo e($acc->location); ?></span><?php endif; ?>
                            <?php if($acc->participants_count): ?><span><i class="fas fa-users" style="margin-right:4px"></i><?php echo e(number_format($acc->participants_count)); ?> beneficiaries</span><?php endif; ?>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                        <span class="badge <?php echo e(match($acc->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow','Cancelled'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($acc->status); ?></span>
                        <button type="button" onclick="openEditActivity(this.closest('[data-id]'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                        <button type="button" onclick="deleteGeneric('activities','<?php echo e($acc->id); ?>','<?php echo e(addslashes($acc->title)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-trophy"></i></div>
            <h4>No Accomplishments Logged Yet</h4>
            <p>Document completed programs, milestones, and committee achievements.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-accomplishments', document.querySelector('[data-icon=fa-trophy]'))">
                <i class="fas fa-trophy"></i> Log First Accomplishment
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-attendance" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-users"></i> Attendance Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-attendance', this)" data-label="Record Attendance" data-icon="fa-clipboard-list">
                <i class="fas fa-clipboard-list"></i> Record Attendance
            </button>
        </div>
        <div id="form-attendance" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Record Attendance</div>
                <form method="POST" action="<?php echo e(route('committees.storeAttendance', $committee['slug'])); ?>" enctype="multipart/form-data" data-axios="true">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Event Name <span style="color:var(--crimson)">*</span></label><input type="text" name="event_name" class="form-control <?php $__errorArgs = ['event_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('event_name')); ?>" required><?php $__errorArgs = ['event_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
        <div class="att-summary-strip">
            <div class="att-summary-item">
                <div class="att-summary-num"><?php echo e($attendances->count()); ?></div>
                <div class="att-summary-lbl">Total Sessions</div>
            </div>
            <div class="att-summary-item">
                <div class="att-summary-num"><?php echo e(number_format($attendances->sum('total_attendees'))); ?></div>
                <div class="att-summary-lbl">Total Attendees</div>
            </div>
            <div class="att-summary-item">
                <div class="att-summary-num"><?php echo e($attendances->count() ? number_format($attendances->avg('total_attendees'), 0) : '—'); ?></div>
                <div class="att-summary-lbl">Avg per Session</div>
            </div>
        </div>
        <table>
            <thead><tr><th>Event</th><th>Date</th><th>Venue</th><th style="text-align:right">Attendees</th><th>Notes</th><th>Sheet</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($att->id); ?>"
                    data-event="<?php echo e(addslashes($att->event_name)); ?>"
                    data-date="<?php echo e(\Carbon\Carbon::parse($att->event_date)->format('Y-m-d')); ?>"
                    data-venue="<?php echo e(addslashes($att->venue ?? '')); ?>"
                    data-attendees="<?php echo e($att->total_attendees); ?>"
                    data-notes="<?php echo e(addslashes($att->notes ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($att->event_name); ?></td>
                    <td class="td-muted"><?php echo e(\Carbon\Carbon::parse($att->event_date)->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($att->venue ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)"><?php echo e(number_format($att->total_attendees)); ?></td>
                    <td class="td-muted"><?php echo e($att->notes ?? '—'); ?></td>
                    <td><?php if($att->file_path): ?><a href="<?php echo e(asset('storage/'.$att->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditAttendance(this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteGeneric('attendance','<?php echo e($att->id); ?>','<?php echo e(addslashes($att->event_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-users"></i></div>
            <h4>No Attendance Records Yet</h4>
            <p>Start tracking attendance by recording your first committee event session.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-attendance', document.querySelector('[data-icon=fa-clipboard-list]'))">
                <i class="fas fa-clipboard-list"></i> Record First Attendance
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-inventory" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-boxes-stacked"></i> Inventory</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-inventory', this)" data-label="Add Item" data-icon="fa-circle-plus">
                <i class="fas fa-circle-plus"></i> Add Item
            </button>
        </div>
        <div id="form-inventory" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Inventory Item</div>
                <form method="POST" action="<?php echo e(route('committees.storeInventory', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label><input type="text" name="item_name" class="form-control <?php $__errorArgs = ['item_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('item_name')); ?>" required><?php $__errorArgs = ['item_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        <div class="form-group"><label class="form-label">Category</label><input type="text" name="category" class="form-control" value="<?php echo e(old('category')); ?>"></div>
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
            <thead><tr><th>Item Name</th><th>Category</th><th style="text-align:right">Qty</th><th>Unit</th><th>Condition</th><th>Remarks</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $inventory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($item->id); ?>"
                    data-name="<?php echo e(addslashes($item->item_name)); ?>"
                    data-category="<?php echo e(addslashes($item->category ?? '')); ?>"
                    data-qty="<?php echo e($item->quantity); ?>"
                    data-unit="<?php echo e(addslashes($item->unit ?? '')); ?>"
                    data-condition="<?php echo e($item->condition); ?>"
                    data-remarks="<?php echo e(addslashes($item->remarks ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($item->item_name); ?></td>
                    <td class="td-muted"><?php echo e($item->category ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)"><?php echo e(number_format($item->quantity)); ?></td>
                    <td class="td-muted"><?php echo e($item->unit ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($item->condition) { 'Good'=>'badge-green','Fair'=>'badge-yellow','Poor'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($item->condition); ?></span></td>
                    <td class="td-muted"><?php echo e($item->remarks ?? '—'); ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditInventory(this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteGeneric('inventory','<?php echo e($item->id); ?>','<?php echo e(addslashes($item->item_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-boxes-stacked"></i></div>
            <h4>No Inventory Items Yet</h4>
            <p>Add equipment, supplies, and assets assigned to this committee.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-inventory', document.querySelector('[data-icon=fa-circle-plus]'))">
                <i class="fas fa-circle-plus"></i> Add First Item
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-partnerships" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-handshake"></i> Partnership Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-partnership', this)" data-label="Add Partnership" data-icon="fa-handshake">
                <i class="fas fa-handshake"></i> Add Partnership
            </button>
        </div>
        <div id="form-partnership" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Partnership / MOU Record</div>
                <form method="POST" action="<?php echo e(route('committees.storePartnership', $committee['slug'])); ?>" enctype="multipart/form-data" data-axios="true">
                    <?php echo csrf_field(); ?>
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Partner Name / Organization <span style="color:var(--crimson)">*</span></label><input type="text" name="partner_name" class="form-control <?php $__errorArgs = ['partner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Quezon City Health Department" value="<?php echo e(old('partner_name')); ?>" required><?php $__errorArgs = ['partner_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
                <tr
                    data-pid="<?php echo e($p->id); ?>"
                    data-partner="<?php echo e(addslashes($p->partner_name)); ?>"
                    data-ptype="<?php echo e($p->partner_type); ?>"
                    data-mou="<?php echo e($p->mou_date?->format('Y-m-d') ?? ''); ?>"
                    data-validity="<?php echo e($p->validity_date?->format('Y-m-d') ?? ''); ?>"
                    data-contact="<?php echo e(addslashes($p->contact_person ?? '')); ?>"
                    data-phone="<?php echo e(addslashes($p->contact_number ?? '')); ?>"
                    data-desc="<?php echo e(addslashes($p->description ?? '')); ?>"
                >
                    <td>
                        <div style="font-weight:600;color:var(--navy)"><?php echo e($p->partner_name); ?></div>
                        <?php if($p->description): ?><div style="font-size:11px;color:var(--text-muted);margin-top:2px"><?php echo e(Str::limit($p->description, 60)); ?></div><?php endif; ?>
                    </td>
                    <td><span class="badge badge-navy"><?php echo e($p->partner_type); ?></span></td>
                    <td class="td-muted"><?php echo e($p->mou_date?->format('M d, Y') ?? '—'); ?></td>
                    <td class="<?php echo e($p->validity_date && $p->validity_date->isPast() ? 'td-danger' : 'td-muted'); ?>">
                        <?php echo e($p->validity_date?->format('M d, Y') ?? '—'); ?>

                        <?php if($p->validity_date && $p->validity_date->isPast()): ?> <span style="font-size:11px">(expired)</span> <?php endif; ?>
                    </td>
                    <td class="td-muted"><?php echo e($p->contact_person ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->contact_number ?? '—'); ?></td>
                    <td><?php if($p->file_path): ?><a href="<?php echo e(asset('storage/'.$p->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditPartnership(this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button"
                                    onclick="deletePartnership(<?php echo e($p->id); ?>, '<?php echo e(addslashes($p->partner_name)); ?>', '<?php echo e($committee['slug']); ?>')"
                                    class="btn btn-danger btn-sm btn-icon" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-handshake"></i></div>
            <h4>No Partnership Records Yet</h4>
            <p>Document MOUs, MOAs, and organizational partnerships for this committee.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-partnership', document.querySelector('[data-icon=fa-handshake]'))">
                <i class="fas fa-handshake"></i> Add First Partnership
            </button>
        </div>
        <?php endif; ?>
    </div>

    

    
    <?php if($committee['slug'] === 'peace-order'): ?>
    <div id="tab-bpso" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-shield-halved"></i> BPSO Members</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-bpso', this)" data-label="Add Member" data-icon="fa-user-plus">
                <i class="fas fa-user-plus"></i> Add Member
            </button>
        </div>
        <div id="form-bpso" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add BPSO Member</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="bpso">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('full_name')); ?>" required><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        <div class="form-group"><label class="form-label">Rank</label><input type="text" name="rank" class="form-control" placeholder="e.g. Senior BPSO" value="<?php echo e(old('rank')); ?>"></div>
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
            <thead><tr><th>Name</th><th>Rank</th><th>Badge No.</th><th>Contact</th><th>Assignment</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['bpso']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($b->id); ?>"
                    data-name="<?php echo e(addslashes($b->full_name)); ?>"
                    data-rank="<?php echo e(addslashes($b->rank ?? '')); ?>"
                    data-badge="<?php echo e(addslashes($b->badge_number ?? '')); ?>"
                    data-contact="<?php echo e(addslashes($b->contact_number ?? '')); ?>"
                    data-assignment="<?php echo e(addslashes($b->assignment ?? '')); ?>"
                    data-status="<?php echo e($b->status); ?>"
                >
                    <td style="font-weight:600"><?php echo e($b->full_name); ?></td>
                    <td class="td-muted"><?php echo e($b->rank ?? '—'); ?></td>
                    <td class="td-mono"><?php echo e($b->badge_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($b->contact_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($b->assignment ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($b->status === 'Active' ? 'badge-green' : ($b->status === 'On Leave' ? 'badge-yellow' : 'badge-gray')); ?>"><?php echo e($b->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('bpso', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('bpso','<?php echo e($b->id); ?>','<?php echo e(addslashes($b->full_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-shield-halved"></i></div>
            <h4>No BPSO Members Yet</h4>
            <p>Register Barangay Peace and Security Officers assigned to this committee.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-bpso', document.querySelector('[data-icon=fa-user-plus]'))">
                <i class="fas fa-user-plus"></i> Add First Member
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div id="tab-patrol" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-binoculars"></i> Patrol Logs</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-patrol', this)" data-label="Log Patrol" data-icon="fa-binoculars">
                <i class="fas fa-binoculars"></i> Log Patrol
            </button>
        </div>
        <div id="form-patrol" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Patrol</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
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
            <thead><tr><th>Date</th><th>Shift</th><th>Area</th><th>Personnel</th><th>Findings</th><th>Reported By</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['patrol_logs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($p->id); ?>"
                    data-date="<?php echo e($p->patrol_date->format('Y-m-d')); ?>"
                    data-shift="<?php echo e($p->shift ?? ''); ?>"
                    data-area="<?php echo e(addslashes($p->area_covered)); ?>"
                    data-personnel="<?php echo e($p->personnel_count ?? 0); ?>"
                    data-findings="<?php echo e(addslashes($p->findings ?? '')); ?>"
                    data-by="<?php echo e(addslashes($p->reported_by ?? '')); ?>"
                >
                    <td class="td-muted"><?php echo e($p->patrol_date->format('M d, Y')); ?></td>
                    <td><span class="badge badge-navy"><?php echo e($p->shift ?? '—'); ?></span></td>
                    <td style="font-weight:600"><?php echo e($p->area_covered); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($p->personnel_count); ?></td>
                    <td class="td-muted"><?php echo e($p->findings ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->reported_by ?? '—'); ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('patrol', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('patrol','<?php echo e($p->id); ?>','<?php echo e(addslashes($p->area_covered)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-binoculars"></i></div>
            <h4>No Patrol Logs Yet</h4>
            <p>Record patrol schedules, area coverage, and security observations.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-patrol', document.querySelector('[data-icon=fa-binoculars]'))">
                <i class="fas fa-binoculars"></i> Log First Patrol
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'health'): ?>
    <div id="tab-health-records" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-notes-medical"></i> Health Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-health', this)" data-label="Add Record" data-icon="fa-notes-medical">
                <i class="fas fa-notes-medical"></i> Add Record
            </button>
        </div>
        <div id="form-health" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Health Record</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="health">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Patient Name <span style="color:var(--crimson)">*</span></label><input type="text" name="patient_name" class="form-control <?php $__errorArgs = ['patient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('patient_name')); ?>" required><?php $__errorArgs = ['patient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Patient</th><th>Age</th><th>Gender</th><th>Program</th><th>Diagnosis</th><th>Attended By</th><th>Date</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['health_records']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($h->id); ?>"
                    data-patient="<?php echo e(addslashes($h->patient_name)); ?>"
                    data-date="<?php echo e($h->visit_date->format('Y-m-d')); ?>"
                    data-age="<?php echo e($h->age ?? ''); ?>"
                    data-gender="<?php echo e($h->gender ?? ''); ?>"
                    data-program="<?php echo e($h->program ?? ''); ?>"
                    data-address="<?php echo e(addslashes($h->address ?? '')); ?>"
                    data-by="<?php echo e(addslashes($h->attended_by ?? '')); ?>"
                    data-diagnosis="<?php echo e(addslashes($h->diagnosis ?? '')); ?>"
                    data-notes="<?php echo e(addslashes($h->notes ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($h->patient_name); ?></td>
                    <td><?php echo e($h->age ?? '—'); ?></td>
                    <td><?php echo e($h->gender ?? '—'); ?></td>
                    <td><span class="badge badge-blue"><?php echo e($h->program ?? '—'); ?></span></td>
                    <td class="td-muted"><?php echo e($h->diagnosis ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($h->attended_by ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($h->visit_date->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('health', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('health','<?php echo e($h->id); ?>','<?php echo e(addslashes($h->patient_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-notes-medical"></i></div>
            <h4>No Health Records Yet</h4>
            <p>Log patient visits, programs served, and health interventions.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-health', document.querySelector('[data-icon=fa-notes-medical]'))">
                <i class="fas fa-notes-medical"></i> Add First Record
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'education'): ?>
    <div id="tab-scholars" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-graduation-cap"></i> Scholars</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-scholars', this)" data-label="Add Scholar" data-icon="fa-graduation-cap">
                <i class="fas fa-graduation-cap"></i> Add Scholar
            </button>
        </div>
        <div id="form-scholars" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Scholar</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="scholar">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('full_name')); ?>" required><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        <div class="form-group"><label class="form-label">Start Date</label><input type="date" name="start_date" class="form-control" value="<?php echo e(old('start_date')); ?>"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">School <span style="color:var(--crimson)">*</span></label><input type="text" name="school" class="form-control <?php $__errorArgs = ['school'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('school')); ?>" required><?php $__errorArgs = ['school'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Name</th><th>School</th><th>Course/Level</th><th>Year</th><th>Scholarship</th><th>Grant</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['scholars']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($s->id); ?>"
                    data-name="<?php echo e(addslashes($s->full_name)); ?>"
                    data-school="<?php echo e(addslashes($s->school)); ?>"
                    data-course="<?php echo e(addslashes($s->course_grade_level ?? '')); ?>"
                    data-year="<?php echo e(addslashes($s->year_level ?? '')); ?>"
                    data-stype="<?php echo e(addslashes($s->scholarship_type ?? '')); ?>"
                    data-amount="<?php echo e($s->grant_amount ?? ''); ?>"
                    data-status="<?php echo e($s->status); ?>"
                    data-start="<?php echo e($s->start_date?->format('Y-m-d') ?? ''); ?>"
                >
                    <td style="font-weight:600"><?php echo e($s->full_name); ?></td>
                    <td class="td-muted"><?php echo e($s->school); ?></td>
                    <td class="td-muted"><?php echo e($s->course_grade_level ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($s->year_level ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($s->scholarship_type ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($s->grant_amount ? '₱'.number_format($s->grant_amount,2) : '—'); ?></td>
                    <td><span class="badge <?php echo e(match($s->status) { 'Active'=>'badge-green','Graduated'=>'badge-blue','Dropped'=>'badge-red',default=>'badge-yellow' }); ?>"><?php echo e($s->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('scholar', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('scholar','<?php echo e($s->id); ?>','<?php echo e(addslashes($s->full_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-graduation-cap"></i></div>
            <h4>No Scholars Yet</h4>
            <p>Register barangay scholarship recipients and track their academic progress.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-scholars', document.querySelector('[data-icon=fa-graduation-cap]'))">
                <i class="fas fa-graduation-cap"></i> Add First Scholar
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'infrastructure'): ?>
    <div id="tab-projects" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-hard-hat"></i> Projects</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-projects', this)" data-label="Add Project" data-icon="fa-hard-hat">
                <i class="fas fa-hard-hat"></i> Add Project
            </button>
        </div>
        <div id="form-projects" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Project</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="project">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Project Name <span style="color:var(--crimson)">*</span></label><input type="text" name="project_name" class="form-control <?php $__errorArgs = ['project_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('project_name')); ?>" required><?php $__errorArgs = ['project_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Project</th><th>Type</th><th>Location</th><th>Budget</th><th>Progress</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['projects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($p->id); ?>"
                    data-name="<?php echo e(addslashes($p->project_name)); ?>"
                    data-ptype="<?php echo e($p->project_type ?? ''); ?>"
                    data-location="<?php echo e(addslashes($p->location ?? '')); ?>"
                    data-status="<?php echo e($p->status); ?>"
                    data-budget="<?php echo e($p->budget ?? ''); ?>"
                    data-cost="<?php echo e($p->actual_cost ?? ''); ?>"
                    data-pct="<?php echo e($p->completion_percentage ?? 0); ?>"
                    data-start="<?php echo e($p->start_date?->format('Y-m-d') ?? ''); ?>"
                    data-end="<?php echo e($p->end_date?->format('Y-m-d') ?? ''); ?>"
                    data-remarks="<?php echo e(addslashes($p->remarks ?? '')); ?>"
                >
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
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('project', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('project','<?php echo e($p->id); ?>','<?php echo e(addslashes($p->project_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-hard-hat"></i></div>
            <h4>No Projects Yet</h4>
            <p>Track infrastructure projects, budgets, and completion timelines.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-projects', document.querySelector('[data-icon=fa-hard-hat]'))">
                <i class="fas fa-hard-hat"></i> Add First Project
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'environment'): ?>
    <div id="tab-env-programs" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-leaf"></i> Environmental Programs</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-env', this)" data-label="Add Program" data-icon="fa-leaf">
                <i class="fas fa-leaf"></i> Add Program
            </button>
        </div>
        <div id="form-env" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Program</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="environment">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Program Name <span style="color:var(--crimson)">*</span></label><input type="text" name="program_name" class="form-control <?php $__errorArgs = ['program_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('program_name')); ?>" required><?php $__errorArgs = ['program_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="program_date" class="form-control" value="<?php echo e(old('program_date')); ?>" required></div>
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
            <thead><tr><th>Program</th><th>Type</th><th>Date</th><th>Location</th><th>Volunteers</th><th>Trees</th><th>Waste (kg)</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['programs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($p->id); ?>"
                    data-name="<?php echo e(addslashes($p->program_name)); ?>"
                    data-date="<?php echo e($p->program_date->format('Y-m-d')); ?>"
                    data-ptype="<?php echo e($p->program_type ?? ''); ?>"
                    data-location="<?php echo e(addslashes($p->location ?? '')); ?>"
                    data-status="<?php echo e($p->status); ?>"
                    data-vol="<?php echo e($p->volunteers ?? 0); ?>"
                    data-trees="<?php echo e($p->trees_planted ?? ''); ?>"
                    data-waste="<?php echo e($p->waste_collected_kg ?? ''); ?>"
                    data-notes="<?php echo e(addslashes($p->notes ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($p->program_name); ?></td>
                    <td class="td-muted"><?php echo e($p->program_type ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($p->program_date->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($p->location ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($p->volunteers); ?></td>
                    <td style="font-weight:600;color:#16a34a"><?php echo e($p->trees_planted ?? '—'); ?></td>
                    <td style="font-weight:600"><?php echo e($p->waste_collected_kg ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($p->status) { 'Completed'=>'badge-green','Planned'=>'badge-yellow',default=>'badge-red' }); ?>"><?php echo e($p->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('environment', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('environment','<?php echo e($p->id); ?>','<?php echo e(addslashes($p->program_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-leaf"></i></div>
            <h4>No Environmental Programs Yet</h4>
            <p>Log clean-up drives, tree planting, and waste management programs.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-env', document.querySelector('[data-icon=fa-leaf]'))">
                <i class="fas fa-leaf"></i> Add First Program
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'livelihood'): ?>
    <div id="tab-beneficiaries" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-hand-holding-heart"></i> Beneficiaries</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-beneficiaries', this)" data-label="Add Beneficiary" data-icon="fa-hand-holding-heart">
                <i class="fas fa-hand-holding-heart"></i> Add Beneficiary
            </button>
        </div>
        <div id="form-beneficiaries" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Beneficiary</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="livelihood">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('full_name')); ?>" required><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        <div class="form-group"><label class="form-label">Date Enrolled</label><input type="date" name="date_enrolled" class="form-control" value="<?php echo e(old('date_enrolled')); ?>"></div>
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
            <thead><tr><th>Name</th><th>Program</th><th>Type</th><th>Amount</th><th>Enrolled</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['beneficiaries']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($b->id); ?>"
                    data-name="<?php echo e(addslashes($b->full_name)); ?>"
                    data-program="<?php echo e(addslashes($b->program_name ?? '')); ?>"
                    data-atype="<?php echo e(addslashes($b->program_type ?? '')); ?>"
                    data-amount="<?php echo e($b->amount_received ?? ''); ?>"
                    data-date="<?php echo e($b->date_enrolled?->format('Y-m-d') ?? ''); ?>"
                    data-status="<?php echo e($b->status); ?>"
                    data-remarks="<?php echo e(addslashes($b->remarks ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($b->full_name); ?></td>
                    <td class="td-muted"><?php echo e($b->program_name); ?></td>
                    <td class="td-muted"><?php echo e($b->program_type ?? '—'); ?></td>
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($b->amount_received ? '₱'.number_format($b->amount_received,2) : '—'); ?></td>
                    <td class="td-muted"><?php echo e($b->date_enrolled?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($b->status) { 'Active'=>'badge-green','Completed'=>'badge-blue',default=>'badge-red' }); ?>"><?php echo e($b->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('beneficiary', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('beneficiary','<?php echo e($b->id); ?>','<?php echo e(addslashes($b->full_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-hand-holding-heart"></i></div>
            <h4>No Beneficiaries Yet</h4>
            <p>Register livelihood program beneficiaries and track their assistance.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-beneficiaries', document.querySelector('[data-icon=fa-hand-holding-heart]'))">
                <i class="fas fa-hand-holding-heart"></i> Add First Beneficiary
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'transport'): ?>
    <div id="tab-toda" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-bus"></i> TODA Registry</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-toda', this)" data-label="Register Vehicle" data-icon="fa-bus">
                <i class="fas fa-bus"></i> Register Vehicle
            </button>
        </div>
        <div id="form-toda" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Register Vehicle</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="toda">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Operator Name <span style="color:var(--crimson)">*</span></label><input type="text" name="operator_name" class="form-control <?php $__errorArgs = ['operator_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('operator_name')); ?>" required><?php $__errorArgs = ['operator_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Operator</th><th>Driver</th><th>Type</th><th>Plate</th><th>TODA</th><th>Route</th><th>Expiry</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['toda']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($t->id); ?>"
                    data-operator="<?php echo e(addslashes($t->operator_name)); ?>"
                    data-plate="<?php echo e(addslashes($t->plate_number ?? '')); ?>"
                    data-vtype="<?php echo e($t->vehicle_type ?? ''); ?>"
                    data-assoc="<?php echo e(addslashes($t->toda_name ?? '')); ?>"
                    data-contact="<?php echo e(addslashes($t->contact_number ?? '')); ?>"
                    data-status="<?php echo e($t->status); ?>"
                >
                    <td style="font-weight:600"><?php echo e($t->operator_name); ?></td>
                    <td class="td-muted"><?php echo e($t->driver_name ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($t->vehicle_type ?? '—'); ?></td>
                    <td class="td-mono"><?php echo e($t->plate_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($t->toda_name ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($t->route ?? '—'); ?></td>
                    <td class="td-muted <?php echo e($t->expiry_date && $t->expiry_date->isPast() ? 'td-danger' : ''); ?>"><?php echo e($t->expiry_date?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($t->status) { 'Active'=>'badge-green','Expired'=>'badge-red',default=>'badge-yellow' }); ?>"><?php echo e($t->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('toda', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('toda','<?php echo e($t->id); ?>','<?php echo e(addslashes($t->operator_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-bus"></i></div>
            <h4>No Vehicles Registered Yet</h4>
            <p>Register tricycles, jeepneys, and other transport vehicles under TODA.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-toda', document.querySelector('[data-icon=fa-bus]'))">
                <i class="fas fa-bus"></i> Register First Vehicle
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'bdrrm'): ?>
    <div id="tab-emergency" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-exclamation-triangle"></i> Emergency Logs</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-emergency', this)" data-label="Log Emergency" data-icon="fa-triangle-exclamation">
                <i class="fas fa-triangle-exclamation"></i> Log Emergency
            </button>
        </div>
        <div id="form-emergency" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Emergency</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="emergency">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group"><label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label><select name="incident_type" class="form-control" required><?php $__currentLoopData = ['Flood','Fire','Earthquake','Typhoon','Landslide','Accident','Medical Emergency','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($t); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="incident_date" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><?php $__currentLoopData = ['Active','Resolved','Monitoring']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Location <span style="color:var(--crimson)">*</span></label><input type="text" name="location" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('location')); ?>" required><?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
                        <div class="form-group"><label class="form-label">Reported By</label><input type="text" name="reported_by" class="form-control" value="<?php echo e(old('reported_by')); ?>"></div>
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
            <thead><tr><th>Type</th><th>Date</th><th>Location</th><th>Families</th><th>Persons</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['emergency_logs']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($e->id); ?>"
                    data-itype="<?php echo e(addslashes($e->incident_type)); ?>"
                    data-date="<?php echo e($e->incident_date->format('Y-m-d')); ?>"
                    data-location="<?php echo e(addslashes($e->location)); ?>"
                    data-desc="<?php echo e(addslashes($e->description ?? '')); ?>"
                    data-casualties="<?php echo e($e->affected_persons ?? 0); ?>"
                    data-response="<?php echo e(addslashes($e->response_actions ?? '')); ?>"
                    data-by="<?php echo e(addslashes($e->reported_by ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($e->incident_type); ?></td>
                    <td class="td-muted"><?php echo e($e->incident_date->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($e->location); ?></td>
                    <td style="font-weight:600;color:var(--crimson)"><?php echo e(number_format($e->affected_families)); ?></td>
                    <td style="font-weight:600;color:var(--crimson)"><?php echo e(number_format($e->affected_persons)); ?></td>
                    <td><span class="badge <?php echo e(match($e->status) { 'Resolved'=>'badge-green','Monitoring'=>'badge-yellow',default=>'badge-red' }); ?>"><?php echo e($e->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('emergency', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('emergency','<?php echo e($e->id); ?>','<?php echo e(addslashes($e->incident_type)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-exclamation-triangle"></i></div>
            <h4>No Emergency Logs Yet</h4>
            <p>Record emergency incidents, affected families, and response actions.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-emergency', document.querySelector('[data-icon=fa-triangle-exclamation]'))">
                <i class="fas fa-triangle-exclamation"></i> Log First Emergency
            </button>
        </div>
        <?php endif; ?>
    </div>

    <div id="tab-evacuation" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-house-chimney-medical"></i> Evacuation Centers</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-evacuation', this)" data-label="Add Center" data-icon="fa-building-columns">
                <i class="fas fa-building-columns"></i> Add Center
            </button>
        </div>
        <div id="form-evacuation" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Evacuation Center</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="evacuation">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Center Name <span style="color:var(--crimson)">*</span></label><input type="text" name="center_name" class="form-control <?php $__errorArgs = ['center_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('center_name')); ?>" required><?php $__errorArgs = ['center_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <div class="evac-card"
                data-id="<?php echo e($ec->id); ?>"
                data-name="<?php echo e(addslashes($ec->center_name)); ?>"
                data-location="<?php echo e(addslashes($ec->location)); ?>"
                data-capacity="<?php echo e($ec->capacity ?? 0); ?>"
                data-contact="<?php echo e(addslashes($ec->contact_person ?? '')); ?>"
                data-phone="<?php echo e(addslashes($ec->contact_number ?? '')); ?>"
                data-status="<?php echo e($ec->status); ?>"
            >
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px">
                    <div>
                        <div style="font-weight:700;font-size:14px;color:var(--navy)"><?php echo e($ec->center_name); ?></div>
                        <div class="td-muted" style="margin-top:2px"><?php echo e($ec->location); ?></div>
                    </div>
                    <span class="badge <?php echo e(match($ec->status) { 'Available'=>'badge-green','Active'=>'badge-blue','Full'=>'badge-red',default=>'badge-gray' }); ?>"><?php echo e($ec->status); ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-muted);margin-bottom:6px">
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
                <div style="display:flex;gap:6px;margin-top:10px;justify-content:flex-end">
                    <button type="button" onclick="openEditSpecific('evacuation', this.closest('[data-id]'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                    <button type="button" onclick="deleteSpecific('evacuation','<?php echo e($ec->id); ?>','<?php echo e(addslashes($ec->center_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-house-chimney-medical"></i></div>
            <h4>No Evacuation Centers Yet</h4>
            <p>Register evacuation centers, capacity, and contact information.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-evacuation', document.querySelector('[data-icon=fa-building-columns]'))">
                <i class="fas fa-building-columns"></i> Add First Center
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-relief-supplies" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-boxes-stacked"></i> Relief Supplies</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-relief', this)" data-label="Add Item" data-icon="fa-box-archive">
                <i class="fas fa-box-archive"></i> Add Item
            </button>
        </div>
        <div id="form-relief" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Relief Supply Item</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="relief">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label><input type="text" name="item_name" class="form-control <?php $__errorArgs = ['item_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('item_name')); ?>" required><?php $__errorArgs = ['item_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
                <tr
                    data-id="<?php echo e($rs->id); ?>"
                    data-name="<?php echo e(addslashes($rs->item_name)); ?>"
                    data-category="<?php echo e($rs->category); ?>"
                    data-qty="<?php echo e($rs->quantity); ?>"
                    data-unit="<?php echo e(addslashes($rs->unit ?? '')); ?>"
                    data-source="<?php echo e(addslashes($rs->source ?? '')); ?>"
                    data-date="<?php echo e($rs->date_received?->format('Y-m-d') ?? ''); ?>"
                    data-status="<?php echo e($rs->status); ?>"
                    data-remarks="<?php echo e(addslashes($rs->remarks ?? '')); ?>"
                >
                    <td style="font-weight:600;color:var(--navy)"><?php echo e($rs->item_name); ?></td>
                    <td><span class="badge badge-navy"><?php echo e($rs->category); ?></span></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)"><?php echo e(number_format($rs->quantity)); ?></td>
                    <td class="td-muted"><?php echo e($rs->unit ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($rs->source ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($rs->date_received?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e(match($rs->status) { 'Available'=>'badge-green','Distributed'=>'badge-yellow',default=>'badge-gray' }); ?>"><?php echo e($rs->status); ?></span></td>
                    <td class="td-muted"><?php echo e($rs->remarks ?? '—'); ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('relief', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button"
                                    onclick="deleteReliefSupply(<?php echo e($rs->id); ?>, '<?php echo e(addslashes($rs->item_name)); ?>', '<?php echo e($committee['slug']); ?>')"
                                    class="btn btn-danger btn-sm btn-icon" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-boxes-stacked"></i></div>
            <h4>No Relief Supplies Yet</h4>
            <p>Track food packs, medicines, PPE, and emergency supplies for disaster response.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-relief', document.querySelector('[data-icon=fa-box-archive]'))">
                <i class="fas fa-box-archive"></i> Add First Item
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>


    
    <?php if($committee['slug'] === 'peace-order'): ?>
    <div id="tab-training" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-chalkboard-user"></i> Training & Seminar Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-training', this)" data-label="Add Training" data-icon="fa-chalkboard-user">
                <i class="fas fa-chalkboard-user"></i> Add Training
            </button>
        </div>
        <div id="form-training" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Log Training / Seminar</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" enctype="multipart/form-data" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="training">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Anti-Drug Campaign Seminar" value="<?php echo e(old('title')); ?>" required><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Title</th><th>Type</th><th>Date</th><th>Duration</th><th>Venue</th><th>Facilitator</th><th style="text-align:right">Participants</th><th>File</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['trainings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($tr->id); ?>"
                    data-title="<?php echo e(addslashes($tr->title)); ?>"
                    data-ttype="<?php echo e($tr->training_type); ?>"
                    data-date="<?php echo e($tr->training_date->format('Y-m-d')); ?>"
                    data-duration="<?php echo e(addslashes($tr->duration ?? '')); ?>"
                    data-venue="<?php echo e(addslashes($tr->venue ?? '')); ?>"
                    data-facilitator="<?php echo e(addslashes($tr->facilitator ?? '')); ?>"
                    data-participants="<?php echo e($tr->participants_count ?? 0); ?>"
                    data-notes="<?php echo e(addslashes($tr->notes ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($tr->title); ?></td>
                    <td><span class="badge badge-navy"><?php echo e($tr->training_type); ?></span></td>
                    <td class="td-muted"><?php echo e($tr->training_date->format('M d, Y')); ?></td>
                    <td class="td-muted"><?php echo e($tr->duration ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($tr->venue ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($tr->facilitator ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:600;color:var(--navy)"><?php echo e(number_format($tr->participants_count)); ?></td>
                    <td><?php if($tr->file_path): ?><a href="<?php echo e(asset('storage/'.$tr->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('training', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('training','<?php echo e($tr->id); ?>','<?php echo e(addslashes($tr->title)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-chalkboard-user"></i></div>
            <h4>No Training Records Yet</h4>
            <p>Log training sessions, seminars, workshops, and security drills for BPSO personnel.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-training', document.querySelector('[data-icon=fa-chalkboard-user]'))">
                <i class="fas fa-chalkboard-user"></i> Add First Training
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'health'): ?>
    <div id="tab-clinic-staff" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-user-doctor"></i> Clinic Doctors & Staff</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-clinic-staff', this)" data-label="Add Staff" data-icon="fa-user-nurse">
                <i class="fas fa-user-nurse"></i> Add Staff
            </button>
        </div>
        <div id="form-clinic-staff" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Clinic Doctor / Staff</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="clinic-staff">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('full_name')); ?>" required><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Name</th><th>Position</th><th>Specialization</th><th>Affiliation</th><th>Contact</th><th>Schedule</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['clinic_staff']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($cs->id); ?>"
                    data-name="<?php echo e(addslashes($cs->full_name)); ?>"
                    data-position="<?php echo e(addslashes($cs->position ?? '')); ?>"
                    data-spec="<?php echo e(addslashes($cs->specialization ?? '')); ?>"
                    data-contact="<?php echo e(addslashes($cs->contact_number ?? '')); ?>"
                    data-schedule="<?php echo e(addslashes($cs->schedule ?? '')); ?>"
                    data-status="<?php echo e($cs->status); ?>"
                >
                    <td style="font-weight:600"><?php echo e($cs->full_name); ?></td>
                    <td><span class="badge badge-blue"><?php echo e($cs->position); ?></span></td>
                    <td class="td-muted"><?php echo e($cs->specialization ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($cs->affiliation ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($cs->contact_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($cs->schedule ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($cs->status === 'Active' ? 'badge-green' : ($cs->status === 'On Leave' ? 'badge-yellow' : 'badge-gray')); ?>"><?php echo e($cs->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('clinic', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('clinic','<?php echo e($cs->id); ?>','<?php echo e(addslashes($cs->full_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-user-doctor"></i></div>
            <h4>No Clinic Staff Yet</h4>
            <p>Register doctors, nurses, midwives, and BHWs serving this health committee.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-clinic-staff', document.querySelector('[data-icon=fa-user-nurse]'))">
                <i class="fas fa-user-nurse"></i> Add First Staff
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-medicine-inventory" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-pills"></i> Pharmacy Inventory</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-medicine', this)" data-label="Add Medicine" data-icon="fa-pills">
                <i class="fas fa-pills"></i> Add Medicine
            </button>
        </div>

        
        <div id="form-medicine" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-capsules" style="color:var(--gold);margin-right:6px"></i> New Medicine Entry</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="medicine">
                    <div class="form-grid-3" style="gap:12px">
                        
                        <div class="form-group"><label class="form-label">Generic Name <span style="color:var(--crimson)">*</span></label><input type="text" name="medicine_name" class="form-control <?php $__errorArgs = ['medicine_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Paracetamol" value="<?php echo e(old('medicine_name')); ?>" required><?php $__errorArgs = ['medicine_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <span id="medCount" style="font-size:13px;color:var(--text-muted);white-space:nowrap"></span>
        </div>

        
        <div id="stockAdjustModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1050;align-items:center;justify-content:center">
            <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:420px;padding:1.5rem;position:relative;box-shadow:0 12px 50px rgba(0,0,0,.25)">
                <button onclick="document.getElementById('stockAdjustModal').style.display='none'" style="position:absolute;top:.75rem;right:.75rem;background:none;border:none;font-size:18px;color:#9ca3af;cursor:pointer"><i class="fas fa-times"></i></button>
                <div style="font-size:16px;font-weight:700;color:var(--navy);margin-bottom:.5rem;padding-bottom:.75rem;border-bottom:1px solid var(--border)">
                    <i class="fas fa-arrow-right-arrow-left" style="color:var(--gold)"></i>&nbsp; Adjust Stock
                </div>
                <div id="stockMedicineName" style="font-size:14px;margin-bottom:1rem;line-height:1.5"></div>
                <form id="stockAdjustForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <div style="margin-bottom:.9rem">
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--navy);margin-bottom:.45rem">Transaction Type</label>
                        <select name="adjustment_type" style="width:100%;padding:.65rem .9rem;min-height:44px;border:1.5px solid #d1d5db;border-radius:6px;font-family:'Poppins',sans-serif;font-size:14px">
                            <option value="in">📦 Stock In — received new supply</option>
                            <option value="out">💊 Dispense / Issue Out</option>
                            <option value="disposed">🗑️ Disposed / Expired removal</option>
                        </select>
                    </div>
                    <div style="margin-bottom:.9rem">
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--navy);margin-bottom:.45rem">Quantity <span style="color:var(--crimson)">*</span></label>
                        <input type="number" name="quantity" min="1" value="1" style="width:100%;padding:.65rem .9rem;min-height:44px;border:1.5px solid #d1d5db;border-radius:6px;font-family:'Poppins',sans-serif;font-size:14px" required>
                    </div>
                    <div style="margin-bottom:1.25rem">
                        <label style="display:block;font-size:13px;font-weight:600;color:var(--navy);margin-bottom:.45rem">Reason / Notes</label>
                        <input type="text" name="reason" placeholder="e.g. Monthly DOH supply, dispensed to patient…" style="width:100%;padding:.65rem .9rem;min-height:44px;border:1.5px solid #d1d5db;border-radius:6px;font-family:'Poppins',sans-serif;font-size:14px">
                    </div>
                    <div id="stockAdjustError" style="display:none;color:var(--crimson);font-size:13px;margin-bottom:.75rem;padding:.6rem .9rem;background:#fef2f2;border-radius:6px;border:1px solid #fca5a5"></div>
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
        <table id="medTable" style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Medicine (Generic / Brand)</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Category</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Dosage</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:right;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Stock</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Expiry</th>
                    <th style="background:var(--navy);color:rgba(255,255,255,.85);padding:.65rem 1rem;text-align:center;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Actions</th>
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
                    data-id="<?php echo e($med->id); ?>"
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
                        <button type="button"
                                onclick="deleteMedicine(<?php echo e($med->id); ?>, '<?php echo e(addslashes($med->medicine_name)); ?>', '<?php echo e($committee['slug']); ?>')"
                                class="btn btn-danger btn-sm btn-icon" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        </div>

        
        <?php
        $medRegistry = [];
        foreach ($meds as $m) {
            $logs = [];
            foreach ($m->stockLogs as $l) {
                $logs[] = [
                    'type'   => $l->adjustment_type,
                    'qty'    => $l->quantity,
                    'before' => $l->stock_before,
                    'after'  => $l->stock_after,
                    'reason' => $l->reason,
                    'by'     => $l->performed_by,
                    'date'   => $l->created_at->format('M d, Y h:i A'),
                ];
            }
            $medRegistry[$m->id] = [
                'medicine_name' => $m->medicine_name,
                'brand_name'    => $m->brand_name,
                'generic_name'  => $m->generic_name,
                'category'      => $m->category,
                'dosage_form'   => $m->dosage_form,
                'unit'          => $m->unit,
                'barcode'       => $m->barcode,
                'current_stock' => $m->current_stock,
                'reorder_level' => $m->reorder_level,
                'expiry_date'   => $m->expiry_date?->format('M d, Y'),
                'supplier'      => $m->supplier,
                'batch_number'  => $m->batch_number,
                'logs'          => $logs,
            ];
        }
        ?>
        <script>
        const __medicineData = <?php echo json_encode($medRegistry); ?>;
        </script>

        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-pills"></i></div>
            <h4>No Medicines in Inventory Yet</h4>
            <p>Add medicines, vitamins, and medical supplies to start tracking clinic stock.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-medicine', document.querySelector('[data-label=\'Add Medicine\']'))">
                <i class="fas fa-pills"></i> Add First Medicine
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'environment'): ?>
    <div id="tab-sweepers" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-broom"></i> Street Sweeper Registry</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-sweepers', this)" data-label="Add Sweeper" data-icon="fa-broom">
                <i class="fas fa-broom"></i> Add Sweeper
            </button>
        </div>
        <div id="form-sweepers" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Street Sweeper</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="sweeper">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label><input type="text" name="full_name" class="form-control <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('full_name')); ?>" required><?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Name</th><th>Assigned Zone</th><th>Schedule</th><th>Contact</th><th>Date Assigned</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['sweepers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($sw->id); ?>"
                    data-name="<?php echo e(addslashes($sw->full_name)); ?>"
                    data-area="<?php echo e(addslashes($sw->assigned_zone ?? '')); ?>"
                    data-contact="<?php echo e(addslashes($sw->contact_number ?? '')); ?>"
                    data-shift="<?php echo e(addslashes($sw->schedule ?? '')); ?>"
                    data-status="<?php echo e($sw->status); ?>"
                >
                    <td style="font-weight:600"><?php echo e($sw->full_name); ?></td>
                    <td class="td-muted"><?php echo e($sw->assigned_zone ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($sw->schedule ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($sw->contact_number ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($sw->date_assigned?->format('M d, Y') ?? '—'); ?></td>
                    <td><span class="badge <?php echo e($sw->status === 'Active' ? 'badge-green' : ($sw->status === 'On Leave' ? 'badge-yellow' : 'badge-gray')); ?>"><?php echo e($sw->status); ?></span></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('sweeper', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('sweeper','<?php echo e($sw->id); ?>','<?php echo e(addslashes($sw->full_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-broom"></i></div>
            <h4>No Street Sweepers Registered Yet</h4>
            <p>Register barangay street sweepers with their assigned zones and schedules.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-sweepers', document.querySelector('[data-icon=fa-broom]'))">
                <i class="fas fa-broom"></i> Add First Sweeper
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if($committee['slug'] === 'infrastructure'): ?>
    <div id="tab-contracts" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-file-signature"></i> Permits & Contracts</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-contracts', this)" data-label="Add Contract" data-icon="fa-file-signature">
                <i class="fas fa-file-signature"></i> Add Contract
            </button>
        </div>
        <div id="form-contracts" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Permit / Contract</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" enctype="multipart/form-data" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="contract">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group"><label class="form-label">Contract Number</label><input type="text" name="contract_number" class="form-control" placeholder="e.g. BNE-2026-001"></div>
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Contractor / Supplier Name <span style="color:var(--crimson)">*</span></label><input type="text" name="contractor_name" class="form-control <?php $__errorArgs = ['contractor_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('contractor_name')); ?>" required><?php $__errorArgs = ['contractor_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Contract No.</th><th>Contractor</th><th>Scope</th><th>Amount</th><th>Duration</th><th>Status</th><th>File</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['contracts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($ct->id); ?>"
                    data-title="<?php echo e(addslashes($ct->scope_of_work ?? $ct->contract_number ?? '')); ?>"
                    data-contractor="<?php echo e(addslashes($ct->contractor_name ?? '')); ?>"
                    data-amount="<?php echo e($ct->contract_amount ?? ''); ?>"
                    data-start="<?php echo e($ct->start_date?->format('Y-m-d') ?? ''); ?>"
                    data-end="<?php echo e($ct->end_date?->format('Y-m-d') ?? ''); ?>"
                    data-status="<?php echo e($ct->status); ?>"
                    data-remarks="<?php echo e(addslashes($ct->scope_of_work ?? '')); ?>"
                >
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
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('contract', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('contract','<?php echo e($ct->id); ?>','<?php echo e(addslashes($ct->contractor_name)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-file-signature"></i></div>
            <h4>No Contracts or Permits Yet</h4>
            <p>Record contractor agreements, permits, and project contracts for infrastructure work.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-contracts', document.querySelector('[data-icon=fa-file-signature]'))">
                <i class="fas fa-file-signature"></i> Add First Contract
            </button>
        </div>
        <?php endif; ?>
    </div>

    
    <div id="tab-financials" class="tab-content">
        <div class="panel-hd">
            <span class="panel-hd-title"><i class="fas fa-money-bill-wave"></i> Financial Records</span>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-financials', this)" data-label="Add Record" data-icon="fa-file-invoice-dollar">
                <i class="fas fa-file-invoice-dollar"></i> Add Record
            </button>
        </div>
        <div id="form-financials" class="form-panel">
            <div class="form-panel-inner">
                <div class="form-section-label"><i class="fas fa-plus" style="color:var(--gold);margin-right:6px"></i> Add Financial Record</div>
                <form method="POST" action="<?php echo e(route('committees.storeSpecific', $committee['slug'])); ?>" enctype="multipart/form-data" data-axios="true">
                    <?php echo csrf_field(); ?> <input type="hidden" name="specific_type" value="financial">
                    <div class="form-grid-3" style="gap:12px">
                        <div class="form-group" style="grid-column:span 2"><label class="form-label">Title / Description <span style="color:var(--crimson)">*</span></label><input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Road Repair Fund Utilization Q1" value="<?php echo e(old('title')); ?>" required><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
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
            <thead><tr><th>Title</th><th>Type</th><th>Fund Source</th><th style="text-align:right">Amount</th><th>Date</th><th>Ref No.</th><th>File</th><th></th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $specificData['financials']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr
                    data-id="<?php echo e($fin->id); ?>"
                    data-title="<?php echo e(addslashes($fin->title)); ?>"
                    data-ftype="<?php echo e($fin->type); ?>"
                    data-source="<?php echo e(addslashes($fin->fund_source ?? '')); ?>"
                    data-amount="<?php echo e($fin->amount ?? ''); ?>"
                    data-date="<?php echo e($fin->date->format('Y-m-d')); ?>"
                    data-ref="<?php echo e(addslashes($fin->reference_number ?? '')); ?>"
                >
                    <td style="font-weight:600"><?php echo e($fin->title); ?></td>
                    <td><span class="badge <?php echo e(match($fin->type) { 'Budget'=>'badge-navy','Utilization'=>'badge-yellow','Liquidation'=>'badge-green' }); ?>"><?php echo e($fin->type); ?></span></td>
                    <td class="td-muted"><?php echo e($fin->fund_source ?? '—'); ?></td>
                    <td style="text-align:right;font-weight:700;color:var(--navy)">₱<?php echo e(number_format($fin->amount, 2)); ?></td>
                    <td class="td-muted"><?php echo e($fin->date->format('M d, Y')); ?></td>
                    <td class="td-mono"><?php echo e($fin->reference_number ?? '—'); ?></td>
                    <td><?php if($fin->file_path): ?><a href="<?php echo e(asset('storage/'.$fin->file_path)); ?>" target="_blank" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-download"></i></a><?php else: ?><span class="td-muted">—</span><?php endif; ?></td>
                    <td>
                        <div style="display:flex;gap:4px;justify-content:flex-end">
                            <button type="button" onclick="openEditSpecific('financial', this.closest('tr'))" class="btn btn-primary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="deleteSpecific('financial','<?php echo e($fin->id); ?>','<?php echo e(addslashes($fin->title)); ?>')" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-enhanced">
            <div class="empty-enhanced-icon"><i class="fas fa-money-bill-wave"></i></div>
            <h4>No Financial Records Yet</h4>
            <p>Track budget allocations, fund utilizations, and liquidation reports.</p>
            <button type="button" class="btn btn-primary btn-sm" onclick="toggleForm('form-financials', document.querySelector('[data-icon=fa-file-invoice-dollar]'))">
                <i class="fas fa-file-invoice-dollar"></i> Add First Record
            </button>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

</div>


<div id="editActivityModal" class="crud-modal-backdrop" onclick="if(event.target===this)closeCrudModal('editActivityModal')">
<div class="crud-modal">
    <div class="crud-modal-header">
        <div class="crud-modal-title"><i class="fas fa-calendar-check"></i> <span id="editActivityModalTitle">Edit Activity</span></div>
        <button class="crud-modal-close" onclick="closeCrudModal('editActivityModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="crud-modal-body">
        <form id="editActivityForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <input type="hidden" name="_method" value="PATCH">
            <div class="form-grid-3" style="gap:12px">
                <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" id="eAct_title" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="activity_date" id="eAct_date" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Location</label><input type="text" name="location" id="eAct_location" class="form-control"></div>
                <div class="form-group"><label class="form-label">Participants</label><input type="number" name="participants_count" id="eAct_participants" class="form-control" min="0"></div>
                <div class="form-group"><label class="form-label">Status</label><select name="status" id="eAct_status" class="form-control"><?php $__currentLoopData = ['Planned','Ongoing','Completed','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($s); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" id="eAct_description" class="form-control"></div>
            </div>
    </div>
    <div class="crud-modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="closeCrudModal('editActivityModal')">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
    </div>
        </form>
</div>
</div>


<div id="editAttendanceModal" class="crud-modal-backdrop" onclick="if(event.target===this)closeCrudModal('editAttendanceModal')">
<div class="crud-modal">
    <div class="crud-modal-header">
        <div class="crud-modal-title"><i class="fas fa-users"></i> Edit Attendance Record</div>
        <button class="crud-modal-close" onclick="closeCrudModal('editAttendanceModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="crud-modal-body">
        <form id="editAttendanceForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <input type="hidden" name="_method" value="PATCH">
            <div class="form-grid-3" style="gap:12px">
                <div class="form-group" style="grid-column:span 2"><label class="form-label">Event Name <span style="color:var(--crimson)">*</span></label><input type="text" name="event_name" id="eAtt_event" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Date <span style="color:var(--crimson)">*</span></label><input type="date" name="event_date" id="eAtt_date" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Venue</label><input type="text" name="venue" id="eAtt_venue" class="form-control"></div>
                <div class="form-group"><label class="form-label">Total Attendees <span style="color:var(--crimson)">*</span></label><input type="number" name="total_attendees" id="eAtt_attendees" class="form-control" min="0" required></div>
                <div class="form-group"><label class="form-label">Notes</label><input type="text" name="notes" id="eAtt_notes" class="form-control"></div>
            </div>
    </div>
    <div class="crud-modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="closeCrudModal('editAttendanceModal')">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
    </div>
        </form>
</div>
</div>


<div id="editInventoryModal" class="crud-modal-backdrop" onclick="if(event.target===this)closeCrudModal('editInventoryModal')">
<div class="crud-modal">
    <div class="crud-modal-header">
        <div class="crud-modal-title"><i class="fas fa-boxes-stacked"></i> Edit Inventory Item</div>
        <button class="crud-modal-close" onclick="closeCrudModal('editInventoryModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="crud-modal-body">
        <form id="editInventoryForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <input type="hidden" name="_method" value="PATCH">
            <div class="form-grid-3" style="gap:12px">
                <div class="form-group" style="grid-column:span 2"><label class="form-label">Item Name <span style="color:var(--crimson)">*</span></label><input type="text" name="item_name" id="eInv_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Category</label><input type="text" name="category" id="eInv_category" class="form-control"></div>
                <div class="form-group"><label class="form-label">Quantity <span style="color:var(--crimson)">*</span></label><input type="number" name="quantity" id="eInv_qty" class="form-control" min="0" required></div>
                <div class="form-group"><label class="form-label">Unit</label><input type="text" name="unit" id="eInv_unit" class="form-control"></div>
                <div class="form-group"><label class="form-label">Condition <span style="color:var(--crimson)">*</span></label><select name="condition" id="eInv_condition" class="form-control" required><?php $__currentLoopData = ['Good','Fair','Poor','For Disposal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($c); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="form-group" style="grid-column:span 3"><label class="form-label">Remarks</label><input type="text" name="remarks" id="eInv_remarks" class="form-control"></div>
            </div>
    </div>
    <div class="crud-modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="closeCrudModal('editInventoryModal')">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
    </div>
        </form>
</div>
</div>


<div id="editPartnershipModal" class="crud-modal-backdrop" onclick="if(event.target===this)closeCrudModal('editPartnershipModal')">
<div class="crud-modal">
    <div class="crud-modal-header">
        <div class="crud-modal-title"><i class="fas fa-handshake"></i> Edit Partnership</div>
        <button class="crud-modal-close" onclick="closeCrudModal('editPartnershipModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="crud-modal-body">
        <form id="editPartnershipForm">
            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
            <input type="hidden" name="_method" value="PATCH">
            <div class="form-grid-3" style="gap:12px">
                <div class="form-group" style="grid-column:span 2"><label class="form-label">Partner Name <span style="color:var(--crimson)">*</span></label><input type="text" name="partner_name" id="ePart_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Type <span style="color:var(--crimson)">*</span></label><select name="partner_type" id="ePart_type" class="form-control" required><?php $__currentLoopData = ['Government','NGO','Private','Community','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option><?php echo e($pt); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select></div>
                <div class="form-group"><label class="form-label">MOU Date</label><input type="date" name="mou_date" id="ePart_mou" class="form-control"></div>
                <div class="form-group"><label class="form-label">Validity Date</label><input type="date" name="validity_date" id="ePart_validity" class="form-control"></div>
                <div class="form-group"><label class="form-label">Contact Person</label><input type="text" name="contact_person" id="ePart_contact" class="form-control"></div>
                <div class="form-group"><label class="form-label">Contact Number</label><input type="text" name="contact_number" id="ePart_phone" class="form-control"></div>
                <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><textarea name="description" id="ePart_desc" class="form-control" rows="2"></textarea></div>
            </div>
    </div>
    <div class="crud-modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" onclick="closeCrudModal('editPartnershipModal')">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
    </div>
        </form>
</div>
</div>


<div id="editSpecificModal" class="crud-modal-backdrop" onclick="if(event.target===this)closeCrudModal('editSpecificModal')">
<div class="crud-modal">
    <div class="crud-modal-header">
        <div class="crud-modal-title"><i id="editSpecificIcon" class="fas fa-pen"></i> <span id="editSpecificTitle">Edit Record</span></div>
        <button class="crud-modal-close" onclick="closeCrudModal('editSpecificModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="crud-modal-body" id="editSpecificBody">
        
    </div>
</div>
</div>


<div id="editRecordModal" class="crud-modal-backdrop" onclick="if(event.target===this)closeCrudModal('editRecordModal')">
<div class="crud-modal">
    <div class="crud-modal-header">
        <div class="crud-modal-title"><i class="fas fa-file-pen"></i> Edit Record</div>
        <button class="crud-modal-close" onclick="closeCrudModal('editRecordModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="crud-modal-body">
        <form id="editRecordForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PATCH">
            <div class="form-grid-3" style="gap:12px">
                <div class="form-group" style="grid-column:span 2"><label class="form-label">Title <span style="color:var(--crimson)">*</span></label><input type="text" name="title" id="eRec_title" class="form-control" required></div>
                <div class="form-group"><label class="form-label">Type <span style="color:var(--crimson)">*</span></label>
                    <select name="record_type" id="eRec_type" class="form-control" required>
                        <?php $__currentLoopData = ['Photo','Video','Report','Resolution','Certificate','Partnership','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($rt); ?>"><?php echo e($rt); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 3"><label class="form-label">Description</label><input type="text" name="description" id="eRec_description" class="form-control" placeholder="Optional description"></div>
            </div>
        </div>
        <div class="crud-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeCrudModal('editRecordModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>
        </div>
        </form>
</div>
</div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<div id="photoLightbox" onclick="closePhotoLightbox()" style="display:none;position:fixed;inset:0;background:rgba(8,16,32,.93);z-index:9999;flex-direction:column;align-items:center;justify-content:center">
    <div onclick="event.stopPropagation()" style="width:100%;max-width:960px;display:flex;align-items:center;justify-content:space-between;padding:14px 20px 10px;flex-shrink:0">
        <div style="display:flex;align-items:center;gap:10px;min-width:0">
            <div style="width:32px;height:32px;border-radius:8px;background:rgba(200,134,26,.18);border:1px solid rgba(200,134,26,.35);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-image" style="color:var(--gold);font-size:13px"></i>
            </div>
            <div style="min-width:0">
                <div id="lightboxCaption" style="font-size:14px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:680px"></div>
                <div style="font-size:11px;color:rgba(255,255,255,.4);margin-top:1px">Click outside or press <kbd style="font-size:10px;padding:1px 5px;border-radius:3px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.18);color:rgba(255,255,255,.55)">Esc</kbd> to close</div>
            </div>
        </div>
        <button onclick="closePhotoLightbox()" style="width:36px;height:36px;border-radius:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.8);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:15px;transition:background .15s;flex-shrink:0" onmouseover="this.style.background='rgba(255,255,255,.18)'" onmouseout="this.style.background='rgba(255,255,255,.08)'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div onclick="event.stopPropagation()" style="flex:1;display:flex;align-items:center;justify-content:center;padding:4px 20px 20px;min-height:0;width:100%">
        <img id="lightboxImg" src="" alt="" style="max-width:min(92vw,940px);max-height:calc(100vh - 120px);border-radius:10px;box-shadow:0 8px 48px rgba(0,0,0,.7);object-fit:contain;display:block">
    </div>
</div>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Select2: initialise all un-initialised <select> in a container ──
function initTabSelects(container) {
    var $c = container ? $(container) : $(document);
    $c.find('select:not(.select2-hidden-accessible)').each(function() {
        var $s       = $(this);
        var $backdrop = $s.closest('.crud-modal-backdrop');
        $s.select2({
            width: '100%',
            minimumResultsForSearch: $s.find('option').length > 8 ? 0 : Infinity,
            allowClear:   $s.find('option[value=""]').length > 0,
            placeholder:  $s.find('option[value=""]').first().text() || null,
            dropdownParent: $backdrop.length ? $backdrop : $('body'),
        });
    });
}
$(document).ready(function () { initTabSelects(); });

// ── Reload helper: navigate to current page + tab, bypassing cache ──
function _bmsReloadTab(tabId) {
    var base = window.location.pathname;
    var hash = tabId ? '#' + tabId : '';
    // Use a ?_t= cache-buster so the browser fetches fresh HTML, then the
    // DOMContentLoaded hash handler opens the right tab automatically.
    window.location.href = base + '?_t=' + Date.now() + hash;
}

// ── Axios Form Utility ──────────────────────────────────────
function axiosForm(form) {
    const btn         = form.querySelector('[type="submit"]');
    const origHtml    = btn ? btn.innerHTML : '';
    const origClass   = btn ? btn.className : '';
    if (btn) {
        btn.classList.add('btn-loading');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin fa-spin-gold"></i> Saving…';
    }
    const fd = new FormData(form);
    axios.post(form.action, fd, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function(res) {
        const d = res.data;

        // Determine the target tab (server hint > current active tab)
        var tabId = d.tab_id || '';
        if (!tabId) {
            var at = document.querySelector('.tab-content.active');
            if (at) tabId = at.id.replace(/^tab-/, '');
        }

        // Try seamless row injection when the server returned HTML and the
        // table already exists in the DOM.
        if (d.row_html) {
            const tabEl = form.closest('.tab-content');
            if (tabEl) {
                const empty = tabEl.querySelector('.empty-enhanced');
                if (empty) empty.remove();
                const tbody = tabEl.querySelector('table tbody');
                if (tbody) {
                    tbody.insertAdjacentHTML('afterbegin', d.row_html);
                    // Update the tab-count pill
                    if (d.tab_id && d.new_count !== undefined) {
                        const countEl = document.querySelector('#tab-btn-' + d.tab_id + ' .tab-count');
                        if (countEl) countEl.textContent = d.new_count;
                    }
                    bmsToast(d.message || 'Saved successfully.', 'success');
                    // Close and reset the form panel
                    const panel = form.closest('.form-panel');
                    if (panel) {
                        panel.classList.remove('open');
                        const trigBtn = document.querySelector('[onclick*="' + panel.id + '"][data-label]');
                        if (trigBtn) {
                            trigBtn.innerHTML = '<i class="fas ' + (trigBtn.dataset.icon || 'fa-plus') + '"></i> ' + (trigBtn.dataset.label || 'Add');
                            trigBtn.className = 'btn btn-primary btn-sm';
                        }
                    }
                    form.reset();
                    $(form).find('select.select2-hidden-accessible').trigger('change');
                    if (btn) { btn.classList.remove('btn-loading'); btn.innerHTML = origHtml; btn.className = origClass; }
                    return;
                }
            }
        }

        // Fallback: reload to the correct tab (first-add with no table, or
        // specific-tab items where row_html is not generated server-side).
        sessionStorage.setItem('_bmsToast', JSON.stringify({ msg: d.message || 'Saved successfully.', type: 'success' }));
        _bmsReloadTab(tabId);
    })
    .catch(function(err) {
        const data = err.response && err.response.data;
        if (data && data.errors) {
            const first = Object.values(data.errors).flat()[0];
            bmsToast(first, 'error');
            Object.entries(data.errors).forEach(function([field, msgs]) {
                const input = form.querySelector('[name="' + field + '"]');
                if (input) {
                    input.classList.add('is-invalid');
                    let fb = input.parentElement.querySelector('.invalid-feedback');
                    if (!fb) { fb = document.createElement('div'); fb.className = 'invalid-feedback'; input.parentElement.appendChild(fb); }
                    fb.textContent = msgs[0];
                }
            });
        } else {
            bmsToast((data && data.message) || 'An error occurred. Please try again.', 'error');
        }
    })
    .finally(function() {
        if (btn) { btn.classList.remove('btn-loading'); btn.innerHTML = origHtml; btn.className = origClass; }
    });
}

// Show any toast that was queued before a page reload (e.g. after first-add)
document.addEventListener('DOMContentLoaded', function() {
    var _t = sessionStorage.getItem('_bmsToast');
    if (_t) {
        sessionStorage.removeItem('_bmsToast');
        try { var _o = JSON.parse(_t); bmsToast(_o.msg, _o.type); } catch(e) {}
    }
});

// wire all data-axios forms
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[data-axios]').forEach(function(form) {
        // clear any stale is-invalid on input focus
        form.querySelectorAll('input, select, textarea').forEach(function(el) {
            el.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const fb = this.parentElement.querySelector('.invalid-feedback');
                if (fb) fb.textContent = '';
            });
        });
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            axiosForm(this);
        });
    });
});

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
        const icon  = btnEl.dataset.icon  || 'fa-plus';
        btnEl.innerHTML = isOpen
            ? '<i class="fas fa-times"></i> Cancel'
            : '<i class="fas ' + icon + '"></i> ' + label;
        btnEl.className = isOpen ? 'btn btn-secondary btn-sm' : 'btn btn-primary btn-sm';
    }
}

// Tab scroll arrows
function tabScroll(dir) {
    const strip = document.getElementById('tabStrip');
    if (strip) strip.scrollBy({ left: dir * 200, behavior: 'smooth' });
}
function updateTabScrollBtns() {
    const strip = document.getElementById('tabStrip');
    const btnL  = document.getElementById('tabScrollLeft');
    const btnR  = document.getElementById('tabScrollRight');
    if (!strip || !btnL || !btnR) return;
    const overflows = strip.scrollWidth > strip.clientWidth + 4;
    btnL.classList.toggle('visible', overflows && strip.scrollLeft > 4);
    btnR.classList.toggle('visible', overflows && strip.scrollLeft < strip.scrollWidth - strip.clientWidth - 4);
}
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash.replace('#', '');
    if (hash && document.getElementById('tab-' + hash)) switchTab(hash);

    const strip = document.getElementById('tabStrip');
    if (strip) {
        updateTabScrollBtns();
        strip.addEventListener('scroll', updateTabScrollBtns);
        window.addEventListener('resize', updateTabScrollBtns);
    }
});

// ── Medicine inventory JS ────────────────────────────────────
let _adjustMedId = null;
function openStockModal(medId, medName, currentStock) {
    _adjustMedId = medId;
    document.getElementById('stockMedicineName').innerHTML =
        '<strong style="color:var(--navy)">' + medName + '</strong>' +
        '&nbsp;<span style="color:#9ca3af">|</span>&nbsp;' +
        'Current stock: <strong>' + currentStock + '</strong>';
    document.getElementById('stockAdjustError').style.display = 'none';
    document.getElementById('stockAdjustForm').reset();
    document.getElementById('stockAdjustModal').style.display = 'flex';
}
document.getElementById('stockAdjustModal')?.addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});

document.getElementById('stockAdjustForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!_adjustMedId) return;

    const form     = this;
    const btn      = form.querySelector('[type="submit"]');
    const errDiv   = document.getElementById('stockAdjustError');
    const baseSlug = '<?php echo e($committee['slug']); ?>';
    const url      = '/committees/' + baseSlug + '/medicine/' + _adjustMedId + '/adjust';

    errDiv.style.display = 'none';
    btn.disabled    = true;
    btn.innerHTML   = '<i class="fas fa-spinner fa-spin"></i> Saving…';

    axios.post(url, new FormData(form))
        .then(function(res) {
            const d   = res.data;
            const row = document.querySelector('#medTable tr.med-row[data-id="' + _adjustMedId + '"]');
            if (row) {
                const stockEl = row.querySelector('.stock-num');
                if (stockEl) {
                    stockEl.textContent = Number(d.new_stock).toLocaleString();
                    stockEl.className   = 'stock-num ' + (d.is_low_stock ? 'stock-low' : 'stock-ok');
                }
                const existingLow = row.querySelector('.low-badge');
                if (d.is_low_stock && !existingLow && stockEl) {
                    stockEl.insertAdjacentHTML('afterend', '<span class="low-badge">Low</span>');
                } else if (!d.is_low_stock && existingLow) {
                    existingLow.remove();
                }
                row.dataset.low = d.is_low_stock ? '1' : '0';
            }
            if (typeof __medicineData !== 'undefined' && __medicineData[_adjustMedId]) {
                __medicineData[_adjustMedId].current_stock = d.new_stock;
                if (d.log_entry) __medicineData[_adjustMedId].logs.unshift(d.log_entry);
            }
            document.getElementById('stockAdjustModal').style.display = 'none';
            bmsToast(d.message, 'success');
        })
        .catch(function(err) {
            const data = err.response?.data;
            const msg  = data?.errors
                ? Object.values(data.errors).flat().join(' ')
                : (data?.message || 'Failed to save transaction.');
            errDiv.textContent    = msg;
            errDiv.style.display  = 'block';
        })
        .finally(function() {
            btn.disabled  = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Save Transaction';
        });
});

function deleteMedicine(medId, medName, slug) {
    bmsConfirm({
        title:   'Delete Medicine',
        message: 'Delete ' + medName + '? This cannot be undone.',
        ok:      'Delete',
        type:    'danger',
    }, function() {
        axios.delete('/committees/' + slug + '/medicine/' + medId)
            .then(function(res) {
                const row = document.querySelector('#medTable tr.med-row[data-id="' + medId + '"]');
                if (row) row.remove();
                if (typeof __medicineData !== 'undefined') delete __medicineData[medId];
                bmsToast(res.data.message, 'success');
            })
            .catch(function() {
                bmsToast('Failed to delete medicine.', 'error');
            });
    });
}

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
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#9ca3af;padding:20px;font-size:13px">No transactions recorded yet.</td></tr>';
    } else {
        logs.forEach(function(log) {
            const typeMap = { in: '📦 Stock In', out: '💊 Dispensed', disposed: '🗑️ Disposed' };
            const clsMap  = { in: 'log-in', out: 'log-out', disposed: 'log-disposed' };
            const note = [log.reason, log.by ? ('by ' + log.by) : ''].filter(Boolean).join(' · ');
            tbody.innerHTML += '<tr>' +
                '<td style="font-size:13px;color:#6b7280;white-space:nowrap">' + (log.date || '') + '</td>' +
                '<td class="' + (clsMap[log.type] || '') + '" style="font-size:14px">' + (typeMap[log.type] || log.type) + '</td>' +
                '<td style="text-align:right;font-size:14px;font-weight:700">' + log.qty + '</td>' +
                '<td style="text-align:right;font-size:13px;color:#6b7280">' + log.before + '</td>' +
                '<td style="text-align:right;font-size:14px;font-weight:700;color:var(--navy)">' + log.after + '</td>' +
                '<td style="font-size:13px;color:#6b7280">' + (note || '—') + '</td>' +
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

// ── Partnership delete ───────────────────────────────────────
function deletePartnership(id, name, slug) {
    var rowEl = document.querySelector('tr[data-pid="' + id + '"]');
    bmsConfirm({
        title:   'Delete Partnership',
        message: 'Delete the partnership with ' + name + '? This cannot be undone.',
        ok:      'Delete',
        type:    'danger',
    }, function() {
        axios.delete('/committees/' + slug + '/partnerships/' + id)
            .then(function(res) {
                if (rowEl) rowEl.remove();
                bmsToast(res.data.message || 'Partnership deleted.', 'success');
            })
            .catch(function(err) {
                var msg = err.response && err.response.data && err.response.data.message;
                bmsToast(msg || 'Failed to delete partnership.', 'error');
            });
    });
}

// ── Relief supply delete ─────────────────────────────────────
function deleteReliefSupply(id, name, slug) {
    var rowEl = document.querySelector('tr[data-id="' + id + '"]');
    bmsConfirm({
        title:   'Delete Relief Supply',
        message: 'Delete ' + name + ' from the relief inventory? This cannot be undone.',
        ok:      'Delete',
        type:    'danger',
    }, function() {
        axios.delete('/committees/' + slug + '/relief/' + id)
            .then(function(res) {
                if (rowEl) rowEl.remove();
                bmsToast(res.data.message || 'Relief supply deleted.', 'success');
            })
            .catch(function(err) {
                var msg = err.response && err.response.data && err.response.data.message;
                bmsToast(msg || 'Failed to delete relief supply.', 'error');
            });
    });
}

// ── CRUD: Generic delete (records, activities, attendance, inventory) ──
function deleteGeneric(type, id, name) {
    // Capture the row element synchronously at click time — not inside the async callback
    var rowEl = document.querySelector('[data-id="' + id + '"], [data-rid="' + id + '"]');
    var labels = {
        records: 'Record', activities: 'Activity', attendance: 'Attendance Record', inventory: 'Inventory Item'
    };
    bmsConfirm({
        title:   'Delete ' + (labels[type] || 'Record'),
        message: 'Delete ' + name + '? This cannot be undone.',
        ok: 'Delete', type: 'danger',
    }, function() {
        axios.delete('/committees/<?php echo e($committee['slug']); ?>/' + type + '/' + id)
            .then(function(res) {
                if (rowEl) rowEl.remove();
                bmsToast(res.data.message || 'Deleted.', 'success');
                if (type === 'records') checkRecordsEmpty();
            })
            .catch(function(err) {
                var msg = err.response && err.response.data && err.response.data.message;
                bmsToast(msg || 'Delete failed.', 'error');
            });
    });
}

// ── Records tab: show empty state when last record is deleted ──
function checkRecordsEmpty() {
    var photoSection = document.getElementById('recPhotoSection');
    var docSection   = document.getElementById('recDocSection');
    var photoCount   = photoSection ? photoSection.querySelectorAll('.photo-thumb').length : 0;
    var docCount     = docSection   ? docSection.querySelectorAll('tbody tr').length       : 0;

    if (photoSection && photoCount === 0) photoSection.style.display = 'none';
    if (docSection   && docCount   === 0) docSection.style.display   = 'none';

    if (photoCount === 0 && docCount === 0) {
        var tab = document.getElementById('tab-records');
        if (tab && !tab.querySelector('.empty-enhanced')) {
            var div = document.createElement('div');
            div.className = 'empty-enhanced';
            div.innerHTML =
                '<div class="empty-enhanced-icon"><i class="fas fa-folder-open"></i></div>' +
                '<h4>No Records Uploaded Yet</h4>' +
                '<p>Upload photos, reports, or resolutions to keep your committee records organized.</p>' +
                '<button type="button" class="btn btn-primary btn-sm" onclick="toggleForm(\'form-records\', document.querySelector(\'[data-icon=fa-cloud-arrow-up]\'))">' +
                '<i class="fas fa-cloud-arrow-up"></i> Upload First Record</button>';
            tab.appendChild(div);
        }
    }
}

// ── CRUD: Specific delete ──
function deleteSpecific(type, id, name) {
    var rowEl = document.querySelector('[data-id="' + id + '"]');
    bmsConfirm({
        title: 'Delete Record',
        message: 'Delete ' + name + '? This cannot be undone.',
        ok: 'Delete', type: 'danger',
    }, function() {
        axios.delete('/committees/<?php echo e($committee['slug']); ?>/specific/' + type + '/' + id)
            .then(function(res) {
                if (rowEl) rowEl.remove();
                bmsToast(res.data.message || 'Deleted.', 'success');
            })
            .catch(function(err) {
                var msg = err.response && err.response.data && err.response.data.message;
                bmsToast(msg || 'Delete failed.', 'error');
            });
    });
}

// ── CRUD: Close modal helper ──
function closeCrudModal(id) {
    document.getElementById(id).classList.remove('open');
}

// ── CRUD: Axios PATCH helper for edit modals ──
function axiosPatch(form, url, modalId, rowUpdater) {
    var btn = form.querySelector('[type="submit"]');
    var orig = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:#C8861A"></i> Saving…'; }
    var fd = new FormData(form);
    axios.post(url, fd, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
        .then(function(res) {
            closeCrudModal(modalId);
            if (rowUpdater && res.data.record) {
                // Seamless: updater handles the DOM cells
                rowUpdater(res.data.record);
                bmsToast(res.data.message || 'Updated.', 'success');
                if (btn) { btn.disabled = false; btn.innerHTML = orig; }
            } else {
                // No updater (specific-tab edit) — reload to current tab
                var at = document.querySelector('.tab-content.active');
                var tabId = at ? at.id.replace(/^tab-/, '') : '';
                sessionStorage.setItem('_bmsToast', JSON.stringify({ msg: res.data.message || 'Updated.', type: 'success' }));
                _bmsReloadTab(tabId);
            }
        })
        .catch(function(err) {
            var data = err.response && err.response.data;
            if (data && data.errors) {
                bmsToast(Object.values(data.errors).flat()[0], 'error');
            } else {
                bmsToast((data && data.message) || 'Update failed.', 'error');
            }
            if (btn) { btn.disabled = false; btn.innerHTML = orig; }
        });
}

// ── EDIT: Activity ──
var _editActId = null, _editActSlug = '<?php echo e($committee['slug']); ?>';
function openEditActivity(tr) {
    _editActId = tr.dataset.id;
    var isAcc = tr.dataset.type === 'Accomplishment';
    document.getElementById('editActivityModalTitle').textContent = isAcc ? 'Edit Accomplishment' : 'Edit Activity';
    document.getElementById('eAct_title').value        = tr.dataset.title        || '';
    document.getElementById('eAct_date').value         = tr.dataset.date         || '';
    document.getElementById('eAct_location').value     = tr.dataset.location     || '';
    document.getElementById('eAct_participants').value = tr.dataset.participants  || '0';
    document.getElementById('eAct_description').value  = tr.dataset.description  || '';
    $('#eAct_status').val(tr.dataset.status || '').trigger('change');
    document.getElementById('editActivityModal').classList.add('open');
}
document.getElementById('editActivityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var url = '/committees/' + _editActSlug + '/activities/' + _editActId;
    axiosPatch(this, url, 'editActivityModal', function(rec) {
        var row = document.querySelector('[data-id="' + _editActId + '"]');
        if (!row) return;
        var d = rec.activity_date ? rec.activity_date.substring(0,10) : '';
        var fmtDate = d ? new Date(d + 'T00:00:00').toLocaleDateString('en-US',{month:'short',day:'2-digit',year:'numeric'}) : '—';
        var statusMap = {Completed:'badge-green',Ongoing:'badge-yellow',Cancelled:'badge-red'};
        var badgeCls  = statusMap[rec.status] || 'badge-gray';

        if (row.tagName === 'TR') {
            // Table row (Activities tab)
            row.cells[0].innerHTML = '<div style="font-weight:600">' + rec.title + '</div>' + (rec.description ? '<div class="td-muted">' + rec.description + '</div>' : '');
            if (d) row.cells[1].textContent = fmtDate;
            row.cells[2].textContent = rec.location || '—';
            row.cells[3].textContent = rec.participants_count != null ? Number(rec.participants_count).toLocaleString() : '—';
            row.cells[4].innerHTML   = '<span class="badge ' + badgeCls + '">' + rec.status + '</span>';
        } else {
            // Accomplishment card (div)
            var titleEl = row.querySelector('.acc-card-title');
            if (titleEl) titleEl.textContent = rec.title;
            var descEl  = row.querySelector('.td-muted');
            if (rec.description) {
                if (descEl) { descEl.textContent = rec.description; }
                else { titleEl && titleEl.insertAdjacentHTML('afterend', '<div class="td-muted" style="font-size:14px;margin-bottom:4px">' + rec.description + '</div>'); }
            } else if (descEl) { descEl.remove(); }
            var metaSpans = row.querySelectorAll('.acc-card-meta span');
            if (metaSpans[0]) metaSpans[0].innerHTML = '<i class="fas fa-calendar-alt" style="margin-right:4px"></i>' + fmtDate;
            var badge = row.querySelector('.badge');
            if (badge) { badge.className = 'badge ' + badgeCls; badge.textContent = rec.status; }
        }

        // Update data attributes on the element for future edits
        row.dataset.title        = rec.title;
        row.dataset.date         = d;
        row.dataset.location     = rec.location || '';
        row.dataset.participants = rec.participants_count || '0';
        row.dataset.status       = rec.status;
        row.dataset.description  = rec.description || '';
    });
});

// ── EDIT: Attendance ──
var _editAttId = null;
function openEditAttendance(tr) {
    _editAttId = tr.dataset.id;
    document.getElementById('eAtt_event').value     = tr.dataset.event     || '';
    document.getElementById('eAtt_date').value      = tr.dataset.date      || '';
    document.getElementById('eAtt_venue').value     = tr.dataset.venue     || '';
    document.getElementById('eAtt_attendees').value = tr.dataset.attendees || '0';
    document.getElementById('eAtt_notes').value     = tr.dataset.notes     || '';
    document.getElementById('editAttendanceModal').classList.add('open');
}
document.getElementById('editAttendanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    axiosPatch(this, '/committees/<?php echo e($committee['slug']); ?>/attendance/' + _editAttId, 'editAttendanceModal', function(rec) {
        var row = document.querySelector('[data-id="' + _editAttId + '"]');
        if (row) {
            row.cells[0].innerHTML = '<span style="font-weight:600">' + rec.event_name + '</span>';
            var d = rec.event_date ? rec.event_date.substring(0,10) : '';
            if (d) row.cells[1].textContent = new Date(d + 'T00:00:00').toLocaleDateString('en-US',{month:'short',day:'2-digit',year:'numeric'});
            row.cells[2].textContent = rec.venue || '—';
            row.cells[3].innerHTML = '<span style="text-align:right;font-weight:700;color:var(--navy)">' + Number(rec.total_attendees).toLocaleString() + '</span>';
            row.cells[4].textContent = rec.notes || '—';
            row.dataset.event     = rec.event_name;
            row.dataset.date      = d;
            row.dataset.venue     = rec.venue || '';
            row.dataset.attendees = rec.total_attendees;
            row.dataset.notes     = rec.notes || '';
        }
    });
});

// ── EDIT: Inventory ──
var _editInvId = null;
function openEditInventory(tr) {
    _editInvId = tr.dataset.id;
    document.getElementById('eInv_name').value      = tr.dataset.name      || '';
    document.getElementById('eInv_category').value  = tr.dataset.category  || '';
    document.getElementById('eInv_qty').value        = tr.dataset.qty       || '0';
    document.getElementById('eInv_unit').value       = tr.dataset.unit      || '';
    document.getElementById('eInv_remarks').value    = tr.dataset.remarks   || '';
    $('#eInv_condition').val(tr.dataset.condition || '').trigger('change');
    document.getElementById('editInventoryModal').classList.add('open');
}
document.getElementById('editInventoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    axiosPatch(this, '/committees/<?php echo e($committee['slug']); ?>/inventory/' + _editInvId, 'editInventoryModal', function(rec) {
        var row = document.querySelector('[data-id="' + _editInvId + '"]');
        if (row) {
            row.cells[0].innerHTML = '<span style="font-weight:600">' + rec.item_name + '</span>';
            row.cells[1].textContent = rec.category || '—';
            row.cells[2].innerHTML = '<span style="text-align:right;font-weight:700;color:var(--navy)">' + Number(rec.quantity).toLocaleString() + '</span>';
            row.cells[3].textContent = rec.unit || '—';
            var condMap = {Good:'badge-green',Fair:'badge-yellow',Poor:'badge-red'};
            row.cells[4].innerHTML = '<span class="badge ' + (condMap[rec.condition] || 'badge-gray') + '">' + rec.condition + '</span>';
            row.cells[5].textContent = rec.remarks || '—';
            row.dataset.name      = rec.item_name;
            row.dataset.category  = rec.category || '';
            row.dataset.qty       = rec.quantity;
            row.dataset.unit      = rec.unit || '';
            row.dataset.condition = rec.condition;
            row.dataset.remarks   = rec.remarks || '';
        }
    });
});

// ── EDIT: Record (title / type / description) ──
var _editRecId = null;
function openEditRecord(el) {
    _editRecId = el.dataset.id;
    document.getElementById('eRec_title').value       = el.dataset.title       || '';
    document.getElementById('eRec_description').value = el.dataset.description || '';
    $('#eRec_type').val(el.dataset.rtype || '').trigger('change');
    document.getElementById('editRecordModal').classList.add('open');
}
document.getElementById('editRecordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    axiosPatch(this, '/committees/<?php echo e($committee['slug']); ?>/records/' + _editRecId, 'editRecordModal', function(rec) {
        var el = document.querySelector('[data-id="' + _editRecId + '"]');
        if (!el) return;
        if (el.tagName === 'TR') {
            // Documents table row — update cells
            el.cells[0].innerHTML = '<span style="font-weight:600">' + rec.title + '</span>';
            el.cells[1].innerHTML = '<span class="badge badge-navy">' + rec.record_type + '</span>';
            el.cells[2].textContent = rec.description || '—';
        } else {
            // Photo thumb card — update label
            var lbl = el.querySelector('.photo-thumb-label');
            if (lbl) lbl.textContent = rec.title;
        }
        el.dataset.title       = rec.title;
        el.dataset.rtype       = rec.record_type;
        el.dataset.description = rec.description || '';
    });
});

// ── EDIT: Partnership ──
var _editPartId = null;
function openEditPartnership(tr) {
    _editPartId = tr.dataset.pid;
    document.getElementById('ePart_name').value     = tr.dataset.partner   || '';
    document.getElementById('ePart_mou').value       = tr.dataset.mou       || '';
    document.getElementById('ePart_validity').value  = tr.dataset.validity  || '';
    document.getElementById('ePart_contact').value   = tr.dataset.contact   || '';
    document.getElementById('ePart_phone').value     = tr.dataset.phone     || '';
    document.getElementById('ePart_desc').value      = tr.dataset.desc      || '';
    $('#ePart_type').val(tr.dataset.ptype || '').trigger('change');
    document.getElementById('editPartnershipModal').classList.add('open');
}
document.getElementById('editPartnershipForm').addEventListener('submit', function(e) {
    e.preventDefault();
    axiosPatch(this, '/committees/<?php echo e($committee['slug']); ?>/partnerships/' + _editPartId, 'editPartnershipModal', function(rec) {
        var row = document.querySelector('[data-pid="' + _editPartId + '"]');
        if (row) {
            row.cells[0].innerHTML = '<div style="font-weight:600;color:var(--navy)">' + rec.partner_name + '</div>';
            row.cells[4].textContent = rec.contact_person || '—';
            row.cells[5].textContent = rec.contact_number || '—';
            row.dataset.partner  = rec.partner_name;
            row.dataset.ptype    = rec.partner_type;
            row.dataset.contact  = rec.contact_person || '';
            row.dataset.phone    = rec.contact_number || '';
            row.dataset.desc     = rec.description || '';
        }
    });
});

// ── EDIT: Specific (dynamic modal builder) ──
var _editSpecType = null, _editSpecId = null;
var _specConfig = {
    bpso:        { icon:'fa-shield-halved',    title:'Edit BPSO Member',         fields:[['full_name','Full Name','text',true],['rank','Rank','text',false],['badge_number','Badge No.','text',false],['contact_number','Contact','text',false],['assignment','Assignment','text',false],['status','Status','select',true,['Active','Inactive','On Leave']]] },
    patrol:      { icon:'fa-binoculars',       title:'Edit Patrol Log',          fields:[['patrol_date','Date','date',true],['shift','Shift','select',false,['Morning','Afternoon','Night']],['area_covered','Area Covered','text',true],['personnel_count','Personnel','number',false],['reported_by','Reported By','text',false],['findings','Findings','text',false]] },
    training:    { icon:'fa-chalkboard-user',  title:'Edit Training',            fields:[['title','Title','text',true],['training_type','Type','select',true,['Training','Seminar','Workshop','Drill','Other']],['training_date','Date','date',true],['duration','Duration','text',false],['venue','Venue','text',false],['facilitator','Facilitator','text',false],['participants_count','Participants','number',false],['notes','Notes','text',false]] },
    health:      { icon:'fa-notes-medical',    title:'Edit Health Record',       fields:[['patient_name','Patient Name','text',true],['visit_date','Visit Date','date',true],['age','Age','number',false],['gender','Gender','select',false,['','Male','Female']],['program','Program','select',false,['','Vaccination','Prenatal','Family Planning','Dental','Medical Mission','Nutrition','Other']],['attended_by','Attended By','text',false],['diagnosis','Diagnosis','text',false],['notes','Remarks','text',false]] },
    clinic:      { icon:'fa-user-doctor',      title:'Edit Clinic Staff',        fields:[['full_name','Full Name','text',true],['position','Position','text',false],['specialization','Specialization','text',false],['contact_number','Contact','text',false],['schedule','Schedule','text',false],['status','Status','select',false,['Active','Inactive']]] },
    scholar:     { icon:'fa-graduation-cap',   title:'Edit Scholar',             fields:[['full_name','Full Name','text',true],['school','School','text',true],['course_grade_level','Course/Level','text',false],['year_level','Year Level','text',false],['scholarship_type','Scholarship Type','text',false],['grant_amount','Grant Amount','number',false],['status','Status','select',true,['Active','Graduated','Dropped','Suspended']],['start_date','Start Date','date',false]] },
    project:     { icon:'fa-hard-hat',         title:'Edit Project',             fields:[['project_name','Project Name','text',true],['project_type','Type','select',false,['','Road','Drainage','Building','Electrical','Water','Other']],['location','Location','text',false],['status','Status','select',true,['Planned','Ongoing','Completed','On Hold','Cancelled']],['budget','Budget (₱)','number',false],['actual_cost','Actual Cost (₱)','number',false],['completion_percentage','Completion %','number',false],['start_date','Start Date','date',false],['end_date','End Date','date',false],['remarks','Remarks','text',false]] },
    contract:    { icon:'fa-file-signature',   title:'Edit Contract',            fields:[['title','Title','text',true],['contractor','Contractor','text',false],['contract_amount','Amount (₱)','number',false],['start_date','Start Date','date',false],['end_date','End Date','date',false],['status','Status','select',false,['Active','Completed','Cancelled','On Hold']],['remarks','Remarks','text',false]] },
    financial:   { icon:'fa-money-bill-wave',  title:'Edit Financial Record',    fields:[['title','Title','text',true],['type','Type','select',true,['Budget','Utilization','Liquidation']],['fund_source','Fund Source','text',false],['amount','Amount (₱)','number',false],['date','Date','date',false],['reference_number','Reference No.','text',false]] },
    environment: { icon:'fa-leaf',             title:'Edit Environmental Program',fields:[['program_name','Program Name','text',true],['program_date','Date','date',true],['program_type','Type','select',false,['','Clean-up Drive','Tree Planting','Waste Management','Coastal Clean-up','Anti-littering','Other']],['location','Location','text',false],['status','Status','select',true,['Planned','Completed','Cancelled']],['volunteers','Volunteers','number',false],['trees_planted','Trees Planted','number',false],['waste_collected_kg','Waste (kg)','number',false],['notes','Notes','text',false]] },
    sweeper:     { icon:'fa-broom',            title:'Edit Street Sweeper',      fields:[['full_name','Full Name','text',true],['area_assigned','Area Assigned','text',false],['contact_number','Contact','text',false],['shift','Shift','select',false,['Morning','Afternoon','Night']],['status','Status','select',false,['Active','Inactive']]] },
    beneficiary: { icon:'fa-hand-holding-heart',title:'Edit Beneficiary',        fields:[['full_name','Full Name','text',true],['program','Program','text',false],['assistance_type','Assistance Type','text',false],['amount','Amount (₱)','number',false],['date_granted','Date Granted','date',false],['status','Status','select',false,['Active','Completed','Cancelled']],['remarks','Remarks','text',false]] },
    toda:        { icon:'fa-bus',              title:'Edit TODA Vehicle',        fields:[['operator_name','Operator Name','text',true],['plate_number','Plate No.','text',false],['vehicle_type','Vehicle Type','text',false],['toda_association','TODA Association','text',false],['contact_number','Contact','text',false],['status','Status','select',false,['Active','Inactive','Suspended']]] },
    emergency:   { icon:'fa-exclamation-triangle',title:'Edit Emergency Log',    fields:[['incident_type','Incident Type','text',true],['incident_date','Date','date',true],['location','Location','text',false],['description','Description','text',false],['casualties','Casualties','number',false],['response_action','Response Action','text',false],['logged_by','Logged By','text',false]] },
    evacuation:  { icon:'fa-house-chimney-medical',title:'Edit Evacuation Center',fields:[['center_name','Center Name','text',true],['location','Location','text',false],['capacity','Capacity','number',false],['contact_person','Contact Person','text',false],['contact_number','Contact Number','text',false],['status','Status','select',false,['Active','Inactive','Under Renovation']]] },
    relief:      { icon:'fa-boxes-stacked',        title:'Edit Relief Supply',       fields:[['item_name','Item Name','text',true],['category','Category','select',true,['Food','Non-food','Medicine','PPE','Equipment','Other']],['quantity','Quantity','number',true],['unit','Unit','text',false],['source','Source / Donor','text',false],['date_received','Date Received','date',false],['status','Status','select',true,['Available','Distributed','Depleted']],['remarks','Remarks','text',false]] },
};

// data-attr key map per type (maps field_name to dataset key)
var _specDataMap = {
    bpso:        {full_name:'name',rank:'rank',badge_number:'badge',contact_number:'contact',assignment:'assignment',status:'status'},
    patrol:      {patrol_date:'date',shift:'shift',area_covered:'area',personnel_count:'personnel',reported_by:'by',findings:'findings'},
    training:    {title:'title',training_type:'ttype',training_date:'date',duration:'duration',venue:'venue',facilitator:'facilitator',participants_count:'participants',notes:'notes'},
    health:      {patient_name:'patient',visit_date:'date',age:'age',gender:'gender',program:'program',attended_by:'by',diagnosis:'diagnosis',notes:'notes'},
    clinic:      {full_name:'name',position:'position',specialization:'spec',contact_number:'contact',schedule:'schedule',status:'status'},
    scholar:     {full_name:'name',school:'school',course_grade_level:'course',year_level:'year',scholarship_type:'stype',grant_amount:'amount',status:'status',start_date:'start'},
    project:     {project_name:'name',project_type:'ptype',location:'location',status:'status',budget:'budget',actual_cost:'cost',completion_percentage:'pct',start_date:'start',end_date:'end',remarks:'remarks'},
    contract:    {title:'title',contractor:'contractor',contract_amount:'amount',start_date:'start',end_date:'end',status:'status',remarks:'remarks'},
    financial:   {title:'title',type:'ftype',fund_source:'source',amount:'amount',date:'date',reference_number:'ref'},
    environment: {program_name:'name',program_date:'date',program_type:'ptype',location:'location',status:'status',volunteers:'vol',trees_planted:'trees',waste_collected_kg:'waste',notes:'notes'},
    sweeper:     {full_name:'name',area_assigned:'area',contact_number:'contact',shift:'shift',status:'status'},
    beneficiary: {full_name:'name',program:'program',assistance_type:'atype',amount:'amount',date_granted:'date',status:'status',remarks:'remarks'},
    toda:        {operator_name:'operator',plate_number:'plate',vehicle_type:'vtype',toda_association:'assoc',contact_number:'contact',status:'status'},
    emergency:   {incident_type:'itype',incident_date:'date',location:'location',description:'desc',casualties:'casualties',response_action:'response',logged_by:'by'},
    evacuation:  {center_name:'name',location:'location',capacity:'capacity',contact_person:'contact',contact_number:'phone',status:'status'},
    relief:      {item_name:'name',category:'category',quantity:'qty',unit:'unit',source:'source',date_received:'date',status:'status',remarks:'remarks'},
};

function openEditSpecific(type, tr) {
    var cfg = _specConfig[type];
    if (!cfg) return;
    _editSpecType = type;
    _editSpecId   = tr.dataset.id;
    document.getElementById('editSpecificIcon').className  = 'fas ' + cfg.icon;
    document.getElementById('editSpecificTitle').textContent = cfg.title;
    var dmap = _specDataMap[type] || {};
    // build form HTML
    var html = '<form id="editSpecificForm" data-axios="true"><input type="hidden" name="_method" value="PATCH"><div class="form-grid-3" style="gap:12px">';
    cfg.fields.forEach(function(f) {
        var fname = f[0], flabel = f[1], ftype = f[2], freq = f[3], fopts = f[4];
        var dkey  = dmap[fname] || fname;
        var val   = tr.dataset[dkey] || '';
        var span  = (ftype === 'text' && (fname.includes('description') || fname.includes('findings') || fname.includes('notes') || fname.includes('remarks') || fname.includes('action'))) ? ' style="grid-column:span 3"' : '';
        html += '<div class="form-group"' + span + '>';
        html += '<label class="form-label">' + flabel + (freq ? ' <span style="color:var(--crimson)">*</span>' : '') + '</label>';
        if (ftype === 'select') {
            html += '<select name="' + fname + '" class="form-control"' + (freq ? ' required' : '') + '>';
            (fopts || []).forEach(function(o) { html += '<option value="' + o + '"' + (o == val ? ' selected' : '') + '>' + (o || '—') + '</option>'; });
            html += '</select>';
        } else {
            html += '<input type="' + ftype + '" name="' + fname + '" class="form-control" value="' + val.replace(/"/g,'&quot;') + '"' + (freq ? ' required' : '') + '>';
        }
        html += '</div>';
    });
    html += '</div>';
    html += '<div style="display:none" id="eSpec_csrf"><?php echo csrf_field(); ?></div>';
    html += '</div>';
    // footer inside modal-body so form wraps it
    html += '<div class="crud-modal-footer" style="margin:1rem -1.25rem -1.25rem;border-radius:0 0 var(--radius-lg) var(--radius-lg)">';
    html += '<button type="button" class="btn btn-secondary btn-sm" onclick="closeCrudModal(\'editSpecificModal\')">Cancel</button>';
    html += '<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Save Changes</button>';
    html += '</div></form>';
    document.getElementById('editSpecificBody').innerHTML = html;
    initTabSelects(document.getElementById('editSpecificBody'));
    // inject real CSRF
    var csrfInput = document.querySelector('meta[name="csrf-token"]');
    var token = csrfInput ? csrfInput.getAttribute('content') : (document.querySelector('input[name="_token"]') ? document.querySelector('input[name="_token"]').value : '');
    var tokenInput = document.createElement('input');
    tokenInput.type = 'hidden'; tokenInput.name = '_token'; tokenInput.value = token;
    document.getElementById('editSpecificForm').prepend(tokenInput);
    // wire submit
    document.getElementById('editSpecificForm').addEventListener('submit', function(e) {
        e.preventDefault();
        var url = '/committees/<?php echo e($committee['slug']); ?>/specific/' + _editSpecType + '/' + _editSpecId;
        axiosPatch(this, url, 'editSpecificModal', null);
    });
    document.getElementById('editSpecificModal').classList.add('open');
}

// ── Photo lightbox ───────────────────────────────────────────
function openPhotoLightbox(src, title) {
    document.getElementById('lightboxImg').src             = src;
    document.getElementById('lightboxCaption').textContent = title || 'Photo';
    var lb = document.getElementById('photoLightbox');
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Animate in
    lb.style.opacity = '0';
    requestAnimationFrame(function() {
        lb.style.transition = 'opacity .18s ease';
        lb.style.opacity = '1';
    });
}
function closePhotoLightbox() {
    var lb = document.getElementById('photoLightbox');
    lb.style.transition = 'opacity .15s ease';
    lb.style.opacity = '0';
    setTimeout(function() {
        lb.style.display = 'none';
        lb.style.opacity = '';
        lb.style.transition = '';
        document.body.style.overflow = '';
    }, 150);
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        var lb = document.getElementById('photoLightbox');
        if (lb && lb.style.display === 'flex') closePhotoLightbox();
    }
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