<?php $__env->startSection('title', 'Generate Reports'); ?>

<?php
    $currentYear  = date('Y');
    $currentMonth = date('n');

    $monthlyDocs = [];
    $monthlyBlotter = [];
    for ($m = 1; $m <= 12; $m++) {
        $monthlyDocs[]    = \App\Models\Document::whereYear('created_at', $currentYear)->whereMonth('created_at', $m)->count();
        $monthlyBlotter[] = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->whereMonth('created_at', $m)->count();
    }

    $docsByType    = \App\Models\Document::whereYear('created_at', $currentYear)->selectRaw('document_type, count(*) as total')->groupBy('document_type')->pluck('total','document_type');
    $blotterByType = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->selectRaw('incident_type, count(*) as total')->groupBy('incident_type')->pluck('total','incident_type');
    $puroks        = \App\Models\Purok::withCount(['residents' => fn($q) => $q->where('residency_status','Active')])->orderBy('name')->get();

    $totalResidents  = \App\Models\Resident::count();
    $activeResidents = \App\Models\Resident::where('residency_status','Active')->count();
    $totalDocs       = \App\Models\Document::whereYear('created_at', $currentYear)->count();
    $totalBlotter    = \App\Models\BlotterCase::whereYear('created_at', $currentYear)->count();
    $totalBusinesses = \App\Models\Business::where('status','Active')->count();
    $totalHouseholds = \App\Models\Household::count();
    $totalMale       = \App\Models\Resident::where('gender','Male')->count();
    $totalFemale     = \App\Models\Resident::where('gender','Female')->count();
    $totalVoters     = \App\Models\Resident::where('is_voter',true)->count();
    $totalSeniors    = \App\Models\Resident::where('is_senior',true)->count();
    $totalPwd        = \App\Models\Resident::where('is_pwd',true)->count();
    $totalSoloParent = \App\Models\Resident::where('is_solo_parent',true)->count();
    $total4ps        = \App\Models\Resident::where('is_4ps',true)->count();

    $genderTotal = $totalMale + $totalFemale ?: 1;
    $malePct     = round(($totalMale / $genderTotal) * 100);
    $femalePct   = 100 - $malePct;
?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Generate Reports</h1>
        <p class="page-subtitle">Monthly, quarterly, and annual reports — Barangay New Era <?php echo e($currentYear); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('reports.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-chart-bar"></i> Analytics
        </a>
    </div>
</div>


<div class="grid-4 mb-6">
    <?php $strips = [
        ['label'=>'Total Residents',  'value'=>number_format($totalResidents),  'icon'=>'fa-users',        'color'=>'#22c55e','bg'=>'#f0fdf4'],
        ['label'=>'Households',       'value'=>number_format($totalHouseholds), 'icon'=>'fa-house',        'color'=>'#3b82f6','bg'=>'#eff6ff'],
        ['label'=>'Documents '.$currentYear, 'value'=>number_format($totalDocs),'icon'=>'fa-file-alt',    'color'=>'#f59e0b','bg'=>'#fffbeb'],
        ['label'=>'Blotter '.$currentYear,   'value'=>number_format($totalBlotter),'icon'=>'fa-gavel',    'color'=>'#ef4444','bg'=>'#fef2f2'],
        ['label'=>'Active Businesses','value'=>number_format($totalBusinesses), 'icon'=>'fa-store',        'color'=>'#f59e0b','bg'=>'#fffbeb'],
        ['label'=>'Male Residents',   'value'=>number_format($totalMale),       'icon'=>'fa-person',       'color'=>'#3b82f6','bg'=>'#eff6ff'],
        ['label'=>'Female Residents', 'value'=>number_format($totalFemale),     'icon'=>'fa-person-dress', 'color'=>'#f97316','bg'=>'#fff7ed'],
        ['label'=>'Active Residents', 'value'=>number_format($activeResidents), 'icon'=>'fa-circle-check', 'color'=>'#22c55e','bg'=>'#f0fdf4'],
    ]; ?>
    <?php $__currentLoopData = $strips; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="stat-card">
        <div class="stat-icon" style="background:<?php echo e($s['bg']); ?>;color:<?php echo e($s['color']); ?>">
            <i class="fas <?php echo e($s['icon']); ?>"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e($s['value']); ?></div>
            <div class="stat-label"><?php echo e($s['label']); ?></div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div style="display:grid;grid-template-columns:320px minmax(0,1fr);gap:20px;align-items:start">

    
    <div id="left-col" style="display:flex;flex-direction:column;gap:16px;min-width:0;width:100%">

        
        <div class="card" style="width:100%">
            <div class="card-header">
                <span class="card-title">
                    <i class="fas fa-file-pdf" style="color:#ef4444"></i> Report Generator
                </span>
            </div>
            <div class="card-body" style="padding:20px 20px 24px">
                <form method="POST" action="<?php echo e(route('reports.generate')); ?>">
                    <?php echo csrf_field(); ?>

                    
                    <div class="form-group" style="margin-bottom:20px">
                        <label class="form-label">Report Type</label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                            <?php $__currentLoopData = ['monthly'=>['Monthly','fa-calendar-day','Jan–Dec'],'quarterly'=>['Quarterly','fa-calendar-week','Q1–Q4'],'annual'=>['Annual','fa-calendar','Full Year']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>[$lbl,$icon,$sub]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label style="display:flex;flex-direction:column;align-items:center;gap:6px;padding:14px 6px;border:1.5px solid var(--border);border-radius:var(--radius);cursor:pointer;transition:all 0.15s;text-align:center;background:var(--surface);min-width:0;flex:1"
                                   id="type-card-<?php echo e($val); ?>" onclick="selectType('<?php echo e($val); ?>')">
                                <input type="radio" name="report_type" value="<?php echo e($val); ?>" style="display:none" <?php echo e($val==='monthly'?'checked':''); ?>>
                                <i class="fas <?php echo e($icon); ?>" style="font-size:16px;color:var(--navy)"></i>
                                <span style="font-size:13px;font-weight:700;color:var(--text)"><?php echo e($lbl); ?></span>
                                <span style="font-size:11px;color:var(--text-muted)"><?php echo e($sub); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:16px">
                        <label class="form-label">Module</label>
                        <select name="report_module" class="form-control" required>
                            <option value="summary">📊 Full Summary</option>
                            <option value="residents">👥 Residents</option>
                            <option value="documents">📄 Documents</option>
                            <option value="blotter">⚖️ Blotter Cases</option>
                            <option value="businesses">🏪 Businesses</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom:16px">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-control" required>
                            <?php for($y=date('Y');$y>=2020;$y--): ?>
                                <option value="<?php echo e($y); ?>" <?php echo e($y==date('Y')?'selected':''); ?>><?php echo e($y); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div id="month-field" class="form-group" style="margin-bottom:16px">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-control">
                            <?php $__currentLoopData = ['January','February','March','April','May','June','July','August','September','October','November','December']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($i+1); ?>" <?php echo e(($i+1)==date('n')?'selected':''); ?>><?php echo e($m); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div id="quarter-field" class="form-group" style="display:none;margin-bottom:16px">
                        <label class="form-label">Quarter</label>
                        <select name="quarter" class="form-control">
                            <option value="1">Q1 — Jan to Mar</option>
                            <option value="2">Q2 — Apr to Jun</option>
                            <option value="3">Q3 — Jul to Sep</option>
                            <option value="4">Q4 — Oct to Dec</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px">
                        <i class="fas fa-file-pdf"></i> Generate PDF Report
                    </button>
                </form>
            </div>
        </div>

        
        <div class="card" style="width:100%">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt" style="color:var(--gold)"></i> Quick Generate</span>
            </div>
            <div class="card-body" style="padding:10px 12px;display:flex;flex-direction:column;gap:5px">
                <?php $quick = [
                    ['label'=>'This Month — Summary',   'type'=>'monthly',   'module'=>'summary',   'month'=>date('n'),'year'=>date('Y')],
                    ['label'=>'This Month — Documents', 'type'=>'monthly',   'module'=>'documents', 'month'=>date('n'),'year'=>date('Y')],
                    ['label'=>'This Month — Blotter',   'type'=>'monthly',   'module'=>'blotter',   'month'=>date('n'),'year'=>date('Y')],
                    ['label'=>'This Quarter — Summary', 'type'=>'quarterly', 'module'=>'summary',   'quarter'=>ceil(date('n')/3),'year'=>date('Y')],
                    ['label'=>date('Y').' Annual Summary','type'=>'annual',  'module'=>'summary',   'year'=>date('Y')],
                ]; ?>
                <?php $__currentLoopData = $quick; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form method="POST" action="<?php echo e(route('reports.generate')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="report_type"   value="<?php echo e($q['type']); ?>">
                    <input type="hidden" name="report_module" value="<?php echo e($q['module']); ?>">
                    <input type="hidden" name="year"          value="<?php echo e($q['year']); ?>">
                    <?php if(isset($q['month'])): ?>   <input type="hidden" name="month"   value="<?php echo e($q['month']); ?>"> <?php endif; ?>
                    <?php if(isset($q['quarter'])): ?> <input type="hidden" name="quarter" value="<?php echo e($q['quarter']); ?>"> <?php endif; ?>
                    <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:flex-start;padding:8px 12px;font-size:13px;gap:8px">
                        <i class="fas fa-file-pdf" style="color:#ef4444;font-size:13px;flex-shrink:0"></i>
                        <span style="flex:1;text-align:left;font-size:13px"><?php echo e($q['label']); ?></span>
                    </button>
                </form>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php
            $todayDocs     = \App\Models\Document::whereDate('created_at', today())->count();
            $todayResidents= \App\Models\Resident::whereDate('created_at', today())->count();
            $todayBlotter  = \App\Models\BlotterCase::whereDate('created_at', today())->count();
            $thisMonthDocs = \App\Models\Document::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count();
            $thisMonthBlt  = \App\Models\BlotterCase::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count();
            $thisMonthRes  = \App\Models\Resident::whereYear('created_at', date('Y'))->whereMonth('created_at', date('n'))->count();
        ?>
        <div class="card" id="snapshot-card" style="width:100%">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-calendar-day" style="color:var(--gold)"></i> Snapshot</span>
                <span style="font-size:12px;color:var(--text-muted)"><?php echo e(now()->format('M d, Y')); ?></span>
            </div>
            <div class="card-body" style="padding:10px 16px">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Today</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px">
                    <?php $todayItems = [
                        ['label'=>'Documents', 'value'=>$todayDocs,      'color'=>'#f59e0b','bg'=>'#fffbeb'],
                        ['label'=>'Residents', 'value'=>$todayResidents, 'color'=>'#22c55e','bg'=>'#f0fdf4'],
                        ['label'=>'Blotter',   'value'=>$todayBlotter,   'color'=>'#ef4444','bg'=>'#fef2f2'],
                    ]; ?>
                    <?php $__currentLoopData = $todayItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="text-align:center;padding:8px 6px;background:<?php echo e($t['bg']); ?>;border-radius:var(--radius-sm)">
                        <div style="font-size:20px;font-weight:800;color:<?php echo e($t['color']); ?>"><?php echo e($t['value']); ?></div>
                        <div style="font-size:11px;color:var(--text-muted)"><?php echo e($t['label']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">This Month — <?php echo e(now()->format('F')); ?></div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">
                    <?php $monthItems = [
                        ['label'=>'Documents', 'value'=>$thisMonthDocs, 'color'=>'#f59e0b','bg'=>'#fffbeb'],
                        ['label'=>'Residents', 'value'=>$thisMonthRes,  'color'=>'#22c55e','bg'=>'#f0fdf4'],
                        ['label'=>'Blotter',   'value'=>$thisMonthBlt,  'color'=>'#ef4444','bg'=>'#fef2f2'],
                    ]; ?>
                    <?php $__currentLoopData = $monthItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="text-align:center;padding:8px 6px;background:<?php echo e($t['bg']); ?>;border-radius:var(--radius-sm)">
                        <div style="font-size:20px;font-weight:800;color:<?php echo e($t['color']); ?>"><?php echo e($t['value']); ?></div>
                        <div style="font-size:11px;color:var(--text-muted)"><?php echo e($t['label']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    </div>

    
    <div id="left-col" style="display:flex;flex-direction:column;gap:16px;min-width:0;width:100%">

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar" style="color:var(--gold)"></i> <?php echo e($currentYear); ?> Monthly Trend</span>
                <div style="display:flex;gap:14px;font-size:13px;color:var(--text-muted)">
                    <span style="display:flex;align-items:center;gap:5px">
                        <span style="width:12px;height:3px;background:#0D2144;display:inline-block;border-radius:2px"></span> Documents
                    </span>
                    <span style="display:flex;align-items:center;gap:5px">
                        <span style="width:12px;height:3px;background:#C8861A;display:inline-block;border-radius:2px"></span> Blotter
                    </span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="trendChart" height="95"></canvas>
            </div>
        </div>

        
        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-pie" style="color:var(--gold)"></i> Documents by Type</span>
                </div>
                <div class="card-body" style="display:flex;justify-content:center;padding:12px">
                    <canvas id="docTypeChart" style="max-height:200px"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-chart-pie" style="color:var(--gold)"></i> Blotter by Type</span>
                </div>
                <div class="card-body" style="display:flex;justify-content:center;padding:12px">
                    <canvas id="blotterTypeChart" style="max-height:200px"></canvas>
                </div>
            </div>
        </div>

        
        <div id="demo-purok-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:stretch">
        <div class="card" id="demo-card" style="display:flex;flex-direction:column">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar" style="color:var(--gold)"></i> Population Demographics</span>
            </div>
            <div class="card-body" style="flex:1;display:flex;flex-direction:column;justify-content:space-between">
                <?php
                    $maxDemo = max($activeResidents, $totalVoters, $totalSeniors, $totalPwd, $totalSoloParent, $total4ps, $totalMale, $totalFemale) ?: 1;
                    $demos = [
                        ['label'=>'Active Residents',  'value'=>$activeResidents, 'color'=>'#0D2144'],
                        ['label'=>'Registered Voters', 'value'=>$totalVoters,     'color'=>'#C8861A'],
                        ['label'=>'Senior Citizens',   'value'=>$totalSeniors,    'color'=>'#f59e0b'],
                        ['label'=>'PWD',               'value'=>$totalPwd,        'color'=>'#7c3aed'],
                        ['label'=>'Solo Parents',      'value'=>$totalSoloParent, 'color'=>'#f97316'],
                        ['label'=>'4Ps Beneficiaries', 'value'=>$total4ps,        'color'=>'#ef4444'],
                        ['label'=>'Male',              'value'=>$totalMale,       'color'=>'#3b82f6'],
                        ['label'=>'Female',            'value'=>$totalFemale,     'color'=>'#ec4899'],
                    ];
                ?>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 32px;flex:1;align-content:space-between">
                    <?php $__currentLoopData = $demos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $pct = round(($d['value'] / $maxDemo) * 100); ?>
                    <div>
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px">
                            <span style="font-size:14px;color:var(--text)"><?php echo e($d['label']); ?></span>
                            <span style="font-size:14px;font-weight:700;color:var(--text)"><?php echo e(number_format($d['value'])); ?></span>
                        </div>
                        <div class="progress-bar-wrap">
                            <div class="progress-bar" style="width:<?php echo e($pct); ?>%;background:<?php echo e($d['color']); ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div style="border-top:1px solid var(--border);padding-top:14px;margin-top:4px">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Gender Split</div>
                    <div style="display:flex;border-radius:99px;overflow:hidden;height:10px;margin-bottom:8px">
                        <div style="width:<?php echo e($malePct); ?>%;background:#3b82f6"></div>
                        <div style="width:<?php echo e($femalePct); ?>%;background:#ec4899"></div>
                    </div>
                    <div style="display:flex;gap:20px">
                        <span style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:6px">
                            <span style="width:10px;height:10px;border-radius:50%;background:#3b82f6;display:inline-block"></span>
                            Male — <?php echo e(number_format($totalMale)); ?> (<?php echo e($malePct); ?>%)
                        </span>
                        <span style="font-size:13px;color:var(--text-muted);display:flex;align-items:center;gap:6px">
                            <span style="width:10px;height:10px;border-radius:50%;background:#ec4899;display:inline-block"></span>
                            Female — <?php echo e(number_format($totalFemale)); ?> (<?php echo e($femalePct); ?>%)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="purok-card" style="display:flex;flex-direction:column">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-location-dot" style="color:var(--gold)"></i> Residents by Purok</span>
            </div>
            <div class="card-body" style="padding:12px 16px;flex:1;display:flex;flex-direction:column">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;flex:1">
                    <?php
                        $purokColors = [
                            ['bg'=>'#eff6ff','border'=>'#bfdbfe','num'=>'#1d4ed8','badge'=>'#dbeafe','text'=>'#1e40af'],
                            ['bg'=>'#f0fdf4','border'=>'#bbf7d0','num'=>'#15803d','badge'=>'#dcfce7','text'=>'#166534'],
                            ['bg'=>'#fffbeb','border'=>'#fde68a','num'=>'#b45309','badge'=>'#fef3c7','text'=>'#92400e'],
                            ['bg'=>'#fdf4ff','border'=>'#e9d5ff','num'=>'#7e22ce','badge'=>'#f3e8ff','text'=>'#6b21a8'],
                            ['bg'=>'#fff1f2','border'=>'#fecdd3','num'=>'#be123c','badge'=>'#ffe4e6','text'=>'#9f1239'],
                            ['bg'=>'#f0fdfa','border'=>'#99f6e4','num'=>'#0f766e','badge'=>'#ccfbf1','text'=>'#115e59'],
                        ];
                    ?>
                    <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $total = $puroks->sum('residents_count') ?: 1;
                        $pct   = round(($purok->residents_count / $total) * 100);
                        $c     = $purokColors[$i % count($purokColors)];
                    ?>
                    <div style="background:<?php echo e($c['bg']); ?>;border:1px solid <?php echo e($c['border']); ?>;border-radius:var(--radius-sm);padding:12px;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center">
                        <div style="font-size:22px;font-weight:800;color:<?php echo e($c['num']); ?>"><?php echo e($purok->residents_count); ?></div>
                        <div style="display:inline-block;background:<?php echo e($c['badge']); ?>;color:<?php echo e($c['text']); ?>;font-size:11px;font-weight:700;padding:1px 7px;border-radius:99px;margin:3px 0"><?php echo e($pct); ?>%</div>
                        <div style="font-size:12px;font-weight:600;color:<?php echo e($c['text']); ?>;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100%"><?php echo e($purok->name); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
function selectType(val) {
    document.querySelectorAll('[id^="type-card-"]').forEach(el => {
        el.style.borderColor = 'var(--border)';
        el.style.background  = 'var(--surface)';
    });
    const card = document.getElementById('type-card-' + val);
    card.style.borderColor = 'var(--gold)';
    card.style.background  = 'rgba(200,134,26,0.05)';
    card.querySelector('input[type=radio]').checked = true;

    const mf = document.getElementById('month-field');
    const qf = document.getElementById('quarter-field');
    mf.style.display      = val === 'monthly'   ? 'block' : 'none';
    mf.style.marginBottom = val === 'monthly'   ? '16px'  : '0';
    qf.style.display      = val === 'quarterly' ? 'block' : 'none';
    qf.style.marginBottom = val === 'quarterly' ? '16px'  : '0';

    // Stretch demographics + purok to match snapshot height on monthly/quarterly
    setTimeout(() => matchDemoHeight(val), 50);
}

function matchDemoHeight(val) {
    const snapshotCard = document.getElementById('snapshot-card');
    const demoCard     = document.getElementById('demo-card');
    const purokCard    = document.getElementById('purok-card');
    if (!snapshotCard || !demoCard || !purokCard) return;

    demoCard.style.minHeight  = '';
    purokCard.style.minHeight = '';

    if (val === 'annual') return;

    // Use scrollHeight of left column vs position of demo card
    requestAnimationFrame(() => {
        const leftCol  = document.getElementById('left-col');
        const leftBottom = leftCol.getBoundingClientRect().bottom;
        const demoTop    = demoCard.getBoundingClientRect().top;
        const needed     = Math.floor(leftBottom - demoTop) - 1;
        if (needed > 0) {
            demoCard.style.minHeight  = needed + 'px';
            purokCard.style.minHeight = needed + 'px';
        }
    });
}

selectType('monthly');

const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

// Combined trend chart
new Chart(document.getElementById('trendChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Documents',
                data: <?php echo json_encode($monthlyDocs); ?>,
                backgroundColor: 'rgba(13,33,68,0.12)',
                borderColor: '#0D2144',
                borderWidth: 1.5,
                borderRadius: 4,
                order: 2,
            },
            {
                label: 'Blotter',
                data: <?php echo json_encode($monthlyBlotter); ?>,
                backgroundColor: 'rgba(200,134,26,0.15)',
                borderColor: '#C8861A',
                borderWidth: 1.5,
                borderRadius: 4,
                order: 1,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks:{ color:'#9CA3AF', font:{size:10} }, grid:{ color:'#F3F4F6' } },
            y: { ticks:{ color:'#9CA3AF', font:{size:10}, stepSize:1 }, grid:{ color:'#F3F4F6' }, beginAtZero:true }
        }
    }
});

// Docs by type
const palette = ['#0D2144','#C8861A','#22c55e','#3b82f6','#7c3aed','#ef4444','#f97316','#0891b2'];
new Chart(document.getElementById('docTypeChart'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($docsByType->keys()->toArray()); ?>,
        datasets: [{ data: <?php echo json_encode($docsByType->values()->toArray()); ?>, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout:'62%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8, color:'#6B7280', boxWidth:10 } } } }
});

// Blotter by type
const bPalette = ['#ef4444','#C8861A','#0D2144','#3b82f6','#7c3aed','#22c55e','#f97316','#0891b2'];
new Chart(document.getElementById('blotterTypeChart'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($blotterByType->keys()->toArray()); ?>,
        datasets: [{ data: <?php echo json_encode($blotterByType->values()->toArray()); ?>, backgroundColor: bPalette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: { responsive: true, cutout:'62%', plugins:{ legend:{ position:'bottom', labels:{ font:{size:10}, padding:8, color:'#6B7280', boxWidth:10 } } } }
});


// Match left column height to right column
function matchColumnHeights() {
    const left  = document.getElementById('left-col');
    const right = document.getElementById('right-col');
    const snapshot = document.getElementById('snapshot-card');
    if (!left || !right || !snapshot) return;

    // Reset first
    snapshot.style.minHeight = '0';

    const rightH = right.offsetHeight;
    const leftH  = left.offsetHeight;

    if (rightH > leftH) {
        const diff = rightH - leftH;
        const currentMin = snapshot.offsetHeight;
        snapshot.style.minHeight = (currentMin + diff) + 'px';
    }
}
window.addEventListener('load', () => {
    matchColumnHeights();
    const selected = document.querySelector('input[name="report_type"]:checked');
    if (selected) matchDemoHeight(selected.value);
});
window.addEventListener('resize', () => {
    matchColumnHeights();
    const selected = document.querySelector('input[name="report_type"]:checked');
    if (selected) matchDemoHeight(selected.value);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/reports/generate.blade.php ENDPATH**/ ?>