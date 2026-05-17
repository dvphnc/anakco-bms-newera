<?php $__env->startSection('title', 'Reports & Analytics'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <p class="page-subtitle">Barangay New Era — population & services overview</p>
    </div>
    <div class="page-actions">
        <span style="font-size:13px;color:var(--text-muted);padding:8px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius-sm)">
            <i class="fas fa-clock" style="color:var(--gold);margin-right:6px"></i>
            As of <?php echo e(now()->format('F d, Y')); ?>

        </span>
        <a href="<?php echo e(route('reports.generate')); ?>" class="btn btn-secondary">
            <i class="fas fa-file-pdf"></i> Generate Reports
        </a>
        <a href="<?php echo e(route('export.analytics', 'pdf')); ?>" class="btn btn-secondary" title="Export Population Summary PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> Population PDF
        </a>
        <a href="<?php echo e(route('export.analytics', 'excel')); ?>" class="btn btn-secondary" title="Export Population Summary Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Population Excel
        </a>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-download"></i> Export Data</span>
        <span style="font-size:13px;color:var(--text-muted)">Download records as PDF or Excel</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px">
            <?php
                $modules = [
                    ['key' => 'residents',  'label' => 'Residents',  'icon' => 'fa-users',    'color' => '#1d76db'],
                    ['key' => 'households', 'label' => 'Households', 'icon' => 'fa-house',    'color' => '#5319e7'],
                    ['key' => 'documents',  'label' => 'Documents',  'icon' => 'fa-file-alt', 'color' => '#006b75'],
                    ['key' => 'blotter',    'label' => 'Blotter',    'icon' => 'fa-gavel',    'color' => '#e11d48'],
                    ['key' => 'businesses', 'label' => 'Businesses', 'icon' => 'fa-store',    'color' => '#f97316'],
                ];
            ?>

            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
                
                <div style="background:<?php echo e($m['color']); ?>12;padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:<?php echo e($m['color']); ?>20;color:<?php echo e($m['color']); ?>;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0">
                        <i class="fas <?php echo e($m['icon']); ?>"></i>
                    </div>
                    <div>
                        <div style="font-size:14px;font-weight:700;color:var(--text)"><?php echo e($m['label']); ?></div>
                        <div style="font-size:13px;color:var(--text-muted)">All records</div>
                    </div>
                </div>
                
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                    <a href="<?php echo e(route('export.pdf', $m['key'])); ?>"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;font-size:13px;font-weight:600;color:#dc2626;background:#fff;border-right:1px solid var(--border);text-decoration:none;transition:background 0.15s"
                       onmouseover="this.style.background='#fee2e2'"
                       onmouseout="this.style.background='#fff'">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="<?php echo e(route('export.excel', $m['key'])); ?>"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;font-size:13px;font-weight:600;color:#16a34a;background:#fff;text-decoration:none;transition:background 0.15s"
                       onmouseover="this.style.background='#dcfce7'"
                       onmouseout="this.style.background='#fff'">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($totalActive)); ?></div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-house"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($totalHouseholds)); ?></div>
            <div class="stat-label">Households</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($totalDocuments)); ?></div>
            <div class="stat-label">Documents Issued</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:var(--crimson)">
            <i class="fas fa-gavel"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($totalBlotter)); ?></div>
            <div class="stat-label">Blotter Cases</div>
        </div>
    </div>
</div>


<div class="grid-2 mb-6" style="grid-template-columns:2fr 1fr">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Documents Issued — <?php echo e(date('Y')); ?></span>
        </div>
        <div class="card-body">
            <canvas id="monthlyDocChart" height="110"></canvas>
            <?php
                $yearTotal  = array_sum($monthlyData);
                $thisMonthR = $monthlyData[now()->month] ?? 0;
                $prevMonthR = $monthlyData[now()->subMonth()->month] ?? 0;
                $peakVal    = max($monthlyData);
                $peakNum    = array_search($peakVal, $monthlyData);
                $months     = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            ?>
            <div class="chart-insight" style="margin-top:14px">
                <strong><?php echo e(number_format($yearTotal)); ?></strong> documents issued in <?php echo e(date('Y')); ?>.
                Busiest month: <strong><?php echo e($months[$peakNum] ?? ''); ?></strong> (<?php echo e(number_format($peakVal)); ?> docs).
                This month: <strong><?php echo e($thisMonthR); ?></strong>
                <?php if($thisMonthR > $prevMonthR): ?>
                    — <span style="color:#16a34a"><i class="fas fa-arrow-up"></i> up <?php echo e($thisMonthR - $prevMonthR); ?> from last month.</span>
                <?php elseif($thisMonthR < $prevMonthR): ?>
                    — <span style="color:var(--crimson)"><i class="fas fa-arrow-down"></i> down <?php echo e($prevMonthR - $thisMonthR); ?> from last month.</span>
                <?php else: ?>
                    — same as last month.
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-pie"></i> Age Groups</span>
        </div>
        <div class="card-body">
            <canvas id="ageChart" height="160"></canvas>
            <?php
                $largestGroup = '';
                $largestCount = 0;
                foreach ($ageGroups as $grp => $cnt) {
                    if ($cnt > $largestCount) { $largestCount = $cnt; $largestGroup = $grp; }
                }
            ?>
            <?php if($largestCount > 0): ?>
            <div class="chart-insight" style="margin-top:12px">
                Largest age group: <strong><?php echo e($largestGroup); ?></strong> with <strong><?php echo e(number_format($largestCount)); ?></strong> residents
                (<?php echo e($totalActive > 0 ? round(($largestCount / $totalActive) * 100) : 0); ?>% of active population).
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="grid-2 mb-6">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Population Demographics</span>
        </div>
        <div class="card-body">
            <?php
                $demos = [
                    ['label' => 'Registered Voters', 'value' => $totalVoters,     'color' => 'var(--navy)'],
                    ['label' => 'Senior Citizens',   'value' => $totalSeniors,    'color' => 'var(--gold)'],
                    ['label' => 'PWD',               'value' => $totalPwd,        'color' => '#2563eb'],
                    ['label' => 'Solo Parents',      'value' => $totalSoloParent, 'color' => '#7c3aed'],
                    ['label' => '4Ps Beneficiaries', 'value' => $total4ps,        'color' => '#dc2626'],
                ];
            ?>
            <?php $__currentLoopData = $demos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $pct = $totalActive > 0 ? round(($d['value'] / $totalActive) * 100) : 0; ?>
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:14px;color:var(--text)"><?php echo e($d['label']); ?></span>
                    <span style="font-size:13px;color:var(--text-muted)">
                        <?php echo e(number_format($d['value'])); ?> <span style="color:var(--text-subtle)">(<?php echo e($pct); ?>%)</span>
                    </span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:<?php echo e($pct); ?>%;background:<?php echo e($d['color']); ?>"></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">Residency Status</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                    <?php
                        $statuses = [
                            ['label'=>'Active',      'value'=>$totalActive,      'color'=>'#16a34a'],
                            ['label'=>'Deceased',    'value'=>$totalDeceased,    'color'=>'var(--text-muted)'],
                            ['label'=>'Transferred', 'value'=>$totalTransferred, 'color'=>'var(--gold)'],
                        ];
                    ?>
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="text-align:center;padding:10px;background:var(--surface2);border-radius:var(--radius-sm);border:1px solid var(--border)">
                        <div style="font-size:18px;font-weight:700;color:<?php echo e($s['color']); ?>"><?php echo e(number_format($s['value'])); ?></div>
                        <div style="font-size:13px;color:var(--text-muted);margin-top:2px"><?php echo e($s['label']); ?></div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">Gender Split</div>
                <?php
                    $total = $totalMale + $totalFemale;
                    $malePct   = $total > 0 ? round(($totalMale   / $total) * 100) : 50;
                    $femalePct = 100 - $malePct;
                ?>
                <div style="display:flex;border-radius:99px;overflow:hidden;height:12px;margin-bottom:8px">
                    <div style="width:<?php echo e($malePct); ?>%;background:var(--navy)"></div>
                    <div style="width:<?php echo e($femalePct); ?>%;background:var(--gold)"></div>
                </div>
                <div style="display:flex;gap:20px">
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--navy)"></div>
                        Male — <?php echo e(number_format($totalMale)); ?> (<?php echo e($malePct); ?>%)
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--gold)"></div>
                        Female — <?php echo e(number_format($totalFemale)); ?> (<?php echo e($femalePct); ?>%)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-location-dot"></i> Residents by Purok</span>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Purok</th>
                        <th style="text-align:right">Residents</th>
                        <th style="text-align:right">%</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $residentsByPurok; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purok): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $pct = $totalActive > 0 ? round(($purok->residents_count / $totalActive) * 100) : 0; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:26px;height:26px;border-radius:var(--radius-sm);background:var(--navy-pale);display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-location-dot" style="font-size:11px;color:var(--navy)"></i>
                                </div>
                                <span style="font-weight:500;font-size:13px"><?php echo e($purok->name); ?></span>
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:600;color:var(--navy)"><?php echo e(number_format($purok->residents_count)); ?></td>
                        <td style="text-align:right;color:var(--text-muted);font-size:13px"><?php echo e($pct); ?>%</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="3" style="text-align:center;padding:24px;color:var(--text-muted)">No purok data yet</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="grid-3 mb-6">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-alt"></i> Documents by Type</span>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th style="text-align:right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $documentsByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($type); ?></td>
                        <td style="text-align:right;font-weight:600;color:var(--navy)"><?php echo e(number_format($count)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-muted)">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:var(--gold)"><?php echo e(number_format($pendingDocuments)); ?></div>
                <div style="font-size:13px;color:var(--text-subtle)">Pending</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:#16a34a"><?php echo e(number_format($releasedDocuments)); ?></div>
                <div style="font-size:13px;color:var(--text-subtle)">Released</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-gavel"></i> Blotter by Type</span>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Incident Type</th>
                        <th style="text-align:right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $blotterByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($type); ?></td>
                        <td style="text-align:right;font-weight:600;color:var(--crimson)"><?php echo e(number_format($count)); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-muted)">No data</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:var(--crimson)"><?php echo e(number_format($activeBlotter)); ?></div>
                <div style="font-size:13px;color:var(--text-subtle)">Active</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:#16a34a"><?php echo e(number_format($settledBlotter)); ?></div>
                <div style="font-size:13px;color:var(--text-subtle)">Settled/Closed</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-store"></i> Business Permits</span>
        </div>
        <div class="card-body">
            <?php
                $bizStats = [
                    ['label' => 'Active',  'value' => $activeBusinesses,  'color' => '#16a34a'],
                    ['label' => 'Expired', 'value' => $expiredBusinesses, 'color' => 'var(--crimson)'],
                    ['label' => 'Other',   'value' => $totalBusinesses - $activeBusinesses - $expiredBusinesses, 'color' => 'var(--text-subtle)'],
                ];
            ?>
            <?php $__currentLoopData = $bizStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $pct = $totalBusinesses > 0 ? round(($b['value'] / $totalBusinesses) * 100) : 0; ?>
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:14px;color:var(--text)"><?php echo e($b['label']); ?></span>
                    <span style="font-size:13px;color:var(--text-muted)"><?php echo e(number_format($b['value'])); ?> (<?php echo e($pct); ?>%)</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:<?php echo e($pct); ?>%;background:<?php echo e($b['color']); ?>"></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border);text-align:center">
                <div style="font-size:22px;font-weight:700;color:var(--navy)"><?php echo e(number_format($totalBusinesses)); ?></div>
                <div style="font-size:13px;color:var(--text-subtle)">Total Registered Businesses</div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:12px">
            <?php
                $links = [
                    ['href' => route('residents.index'),  'icon' => 'fas fa-users',    'label' => 'Residents',  'color' => 'var(--navy)'],
                    ['href' => route('households.index'), 'icon' => 'fas fa-house',    'label' => 'Households', 'color' => 'var(--gold)'],
                    ['href' => route('documents.index'),  'icon' => 'fas fa-file-alt', 'label' => 'Documents',  'color' => '#16a34a'],
                    ['href' => route('blotter.index'),    'icon' => 'fas fa-gavel',    'label' => 'Blotter',    'color' => 'var(--crimson)'],
                    ['href' => route('businesses.index'), 'icon' => 'fas fa-store',    'label' => 'Businesses', 'color' => '#2563eb'],
                    ['href' => route('officials.index'),  'icon' => 'fas fa-user-tie', 'label' => 'Officials',  'color' => '#7c3aed'],
                ];
            ?>
            <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($l['href']); ?>"
               style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:16px 8px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);text-align:center;transition:all 0.15s;text-decoration:none"
               onmouseover="this.style.borderColor='<?php echo e($l['color']); ?>';this.style.background='var(--navy-pale)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface2)'">
                <div style="width:40px;height:40px;border-radius:var(--radius-sm);background:<?php echo e($l['color']); ?>18;display:flex;align-items:center;justify-content:center;color:<?php echo e($l['color']); ?>;font-size:17px">
                    <i class="<?php echo e($l['icon']); ?>"></i>
                </div>
                <span style="font-size:13px;font-weight:600;color:var(--text)"><?php echo e($l['label']); ?></span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('monthlyDocChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Documents',
                data: [
                    <?php echo e($monthlyData[1]); ?>, <?php echo e($monthlyData[2]); ?>, <?php echo e($monthlyData[3]); ?>,
                    <?php echo e($monthlyData[4]); ?>, <?php echo e($monthlyData[5]); ?>, <?php echo e($monthlyData[6]); ?>,
                    <?php echo e($monthlyData[7]); ?>, <?php echo e($monthlyData[8]); ?>, <?php echo e($monthlyData[9]); ?>,
                    <?php echo e($monthlyData[10]); ?>, <?php echo e($monthlyData[11]); ?>, <?php echo e($monthlyData[12]); ?>

                ],
                backgroundColor: 'rgba(13,33,68,0.15)',
                borderColor: '#0D2144',
                borderWidth: 2,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#9CA3AF', font: { size: 12 } }, grid: { color: '#E5E7EB' } },
                y: { ticks: { color: '#9CA3AF', font: { size: 12 }, stepSize: 1 }, grid: { color: '#E5E7EB' }, beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('ageChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_keys($ageGroups)); ?>,
            datasets: [{
                data: <?php echo json_encode(array_values($ageGroups)); ?>,
                backgroundColor: ['#C8861A', '#0D2144', '#16a34a', '#9CA3AF'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 12 }, padding: 10, color: '#4B5563' }
                }
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/reports/reports-index.blade.php ENDPATH**/ ?>