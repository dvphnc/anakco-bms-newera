<?php $__env->startSection('title', 'Activity Log'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Activity Log</h1>
        <p class="page-subtitle">Real-time record of all system changes</p>
    </div>
</div>

<?php
$moduleMap = [
    'App\Models\Resident'    => ['label' => 'Residents',  'icon' => 'fa-users',        'color' => '#1d76db', 'slug' => 'residents'],
    'App\Models\Household'   => ['label' => 'Households', 'icon' => 'fa-house',        'color' => '#5319e7', 'slug' => 'households'],
    'App\Models\Document'    => ['label' => 'Documents',  'icon' => 'fa-file-alt',     'color' => '#006b75', 'slug' => 'documents'],
    'App\Models\BlotterCase' => ['label' => 'Blotter',    'icon' => 'fa-gavel',        'color' => '#e11d48', 'slug' => 'blotter'],
    'App\Models\Business'    => ['label' => 'Businesses', 'icon' => 'fa-store',        'color' => '#f97316', 'slug' => 'businesses'],
    'App\Models\Official'    => ['label' => 'Officials',  'icon' => 'fa-user-tie',     'color' => '#7c3aed', 'slug' => 'officials'],
    'App\Models\User'        => ['label' => 'Users',      'icon' => 'fa-user-shield',  'color' => '#9333ea', 'slug' => 'users'],
    'App\Models\Purok'       => ['label' => 'Puroks',     'icon' => 'fa-location-dot', 'color' => '#0891b2', 'slug' => 'puroks'],
];
$skipFields = ['created_at', 'updated_at', 'remember_token', 'password', 'deleted_at'];
$routeMap = [
    'App\Models\Resident'    => 'residents.show',
    'App\Models\Household'   => 'households.show',
    'App\Models\Document'    => 'documents.show',
    'App\Models\BlotterCase' => 'blotter.show',
    'App\Models\Business'    => 'businesses.show',
    'App\Models\Official'    => 'officials.edit',
];
?>


<div style="margin-bottom:16px">
    <button onclick="toggleCharts()" id="charts-toggle-btn"
            style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:7px 16px;font-size:12.5px;font-weight:600;color:var(--text-muted);cursor:pointer;display:flex;align-items:center;gap:8px;font-family:'Poppins',sans-serif;transition:all 0.15s">
        <i class="fas fa-chart-line" style="color:var(--gold)"></i>
        <span id="charts-toggle-label">Show Charts</span>
        <i class="fas fa-chevron-down" id="charts-chevron" style="font-size:11px;transition:transform 0.2s"></i>
    </button>
</div>

<div id="charts-section" style="display:none">


<div class="grid-4 mb-6">
    <a href="<?php echo e(route('activity-log.index', ['period' => 'today'])); ?>" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;<?php echo e(request('period') === 'today' ? 'border-color:var(--navy);box-shadow:0 0 0 3px var(--navy-pale)' : ''); ?>">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.08);color:var(--navy);font-size:16px"><i class="fas fa-calendar-day"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px"><?php echo e(number_format($todayCount)); ?></div>
                <div class="stat-label">Today</div>
            </div>
        </div>
    </a>
    <a href="<?php echo e(route('activity-log.index', ['period' => 'week'])); ?>" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;<?php echo e(request('period') === 'week' ? 'border-color:var(--navy);box-shadow:0 0 0 3px var(--navy-pale)' : ''); ?>">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(13,33,68,0.06);color:var(--navy-mid);font-size:16px"><i class="fas fa-calendar-week"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px"><?php echo e(number_format($thisWeekCount)); ?></div>
                <div class="stat-label">This Week</div>
            </div>
        </div>
    </a>
    <a href="<?php echo e(route('activity-log.index', ['period' => 'month'])); ?>" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;<?php echo e(request('period') === 'month' ? 'border-color:var(--gold);box-shadow:0 0 0 3px var(--gold-glow)' : ''); ?>">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(200,134,26,0.1);color:var(--gold);font-size:16px"><i class="fas fa-calendar"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px"><?php echo e(number_format($thisMonthCount)); ?></div>
                <div class="stat-label">This Month</div>
            </div>
        </div>
    </a>
    <a href="<?php echo e(route('activity-log.index')); ?>" style="text-decoration:none">
        <div class="stat-card" style="padding:16px;cursor:pointer;<?php echo e(!request('period') && !request('module') && !request('action') ? 'border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,101,52,0.08)' : ''); ?>">
            <div class="stat-icon" style="width:40px;height:40px;background:rgba(22,101,52,0.08);color:#16a34a;font-size:16px"><i class="fas fa-clock-rotate-left"></i></div>
            <div class="stat-info">
                <div class="stat-number" style="font-size:24px"><?php echo e(number_format($actionTotals['created'] + $actionTotals['updated'] + $actionTotals['deleted'])); ?></div>
                <div class="stat-label">All Logs</div>
            </div>
        </div>
    </a>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-line"></i> Monthly Activity Trend — Last 12 Months</span>
    </div>
    <div class="card-body">
        <canvas id="monthlyChart" height="80"></canvas>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-bar"></i> Activity This Week</span>
        <div style="display:flex;gap:14px;font-size:11px;color:var(--text-muted)">
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#16a34a;display:inline-block"></span>Created</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#f59e0b;display:inline-block"></span>Updated</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:2px;background:#ef4444;display:inline-block"></span>Deleted</span>
        </div>
    </div>
    <div class="card-body">
        <canvas id="weeklyChart" height="80"></canvas>
    </div>
</div>

</div>


<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="<?php echo e(route('activity-log.index')); ?>">
            <?php if(request('module')): ?>
                <input type="hidden" name="module" value="<?php echo e(request('module')); ?>">
            <?php endif; ?>
            <?php if(request('period')): ?>
                <input type="hidden" name="period" value="<?php echo e(request('period')); ?>">
            <?php endif; ?>
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label">Action</label>
                    <select name="action" class="form-control" onchange="this.form.submit()">
                        <option value="">All Actions</option>
                        <option value="created" <?php echo e(request('action') === 'created' ? 'selected' : ''); ?>>Created</option>
                        <option value="updated" <?php echo e(request('action') === 'updated' ? 'selected' : ''); ?>>Updated</option>
                        <option value="deleted" <?php echo e(request('action') === 'deleted' ? 'selected' : ''); ?>>Deleted</option>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <a href="<?php echo e(route('activity-log.index')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i> Reset All</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px" id="module-pills">
    <a href="<?php echo e(route('activity-log.index')); ?>"
       style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:99px;border:1.5px solid <?php echo e(!request('module') ? 'var(--navy)' : 'var(--border)'); ?>;background:<?php echo e(!request('module') ? 'var(--navy)' : 'var(--surface)'); ?>;color:<?php echo e(!request('module') ? '#fff' : 'var(--text-muted)'); ?>;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas fa-clock-rotate-left" style="font-size:11px"></i> All
    </a>
    <?php $__currentLoopData = $moduleMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $count = $moduleCounts[$class] ?? 0; ?>
    <?php if($count > 0): ?>
    <a href="<?php echo e(route('activity-log.index', ['module' => $info['slug']])); ?>"
       style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border-radius:99px;border:1.5px solid <?php echo e(request('module') === $info['slug'] ? $info['color'] : 'var(--border)'); ?>;background:<?php echo e(request('module') === $info['slug'] ? $info['color'].'18' : 'var(--surface)'); ?>;color:<?php echo e(request('module') === $info['slug'] ? $info['color'] : 'var(--text-muted)'); ?>;font-size:12px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas <?php echo e($info['icon']); ?>" style="font-size:11px"></i>
        <?php echo e($info['label']); ?>

        <span style="background:<?php echo e(request('module') === $info['slug'] ? $info['color'] : 'var(--surface3)'); ?>;color:<?php echo e(request('module') === $info['slug'] ? '#fff' : 'var(--text-muted)'); ?>;border-radius:99px;padding:0 6px;font-size:10px"><?php echo e($count); ?></span>
    </a>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>



<div class="card" id="activity-feed">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Activity Feed</span>
        <span style="font-size:12px;color:var(--text-muted)"><?php echo e($query->total()); ?> entries · refreshes every 60s</span>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $module = $moduleMap[$log->loggable_type] ?? ['label' => 'Record', 'icon' => 'fa-circle', 'color' => '#9ca3af', 'slug' => ''];
        $actionColor = match($log->action) { 'created' => '#166534', 'deleted' => '#991b1b', default => '#92400e' };
        $actionBg    = match($log->action) { 'created' => '#dcfce7', 'deleted' => '#fee2e2', default => '#fef3c7' };
        $dateStr = $log->created_at->isToday() ? 'Today' : ($log->created_at->isYesterday() ? 'Yesterday' : $log->created_at->format('M d, Y'));
        $changes = collect($log->changes ?? [])->filter(fn($v, $k) => !in_array($k, $skipFields))->take(4);
        $routeName = $routeMap[$log->loggable_type] ?? null;
    ?>
    <div style="display:flex;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);align-items:flex-start">
        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0;margin-top:1px">
            <?php echo e(strtoupper(substr($log->user->name ?? '?', 0, 1))); ?>

        </div>
        <div style="flex:1;min-width:0">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:6px">
                <span style="font-size:13.5px;font-weight:600;color:var(--text)"><?php echo e($log->user->name ?? 'Unknown'); ?></span>
                <span class="badge badge-navy" style="font-size:10px"><?php echo e($log->user->role ?? 'Staff'); ?></span>
                <span style="display:inline-flex;align-items:center;padding:1px 8px;border-radius:99px;font-size:10px;font-weight:700;background:<?php echo e($actionBg); ?>;color:<?php echo e($actionColor); ?>">
                    <?php echo e(strtoupper($log->action)); ?>

                </span>
                <span style="font-size:12px;font-weight:600;color:<?php echo e($module['color']); ?>">
                    <i class="fas <?php echo e($module['icon']); ?>" style="font-size:10px"></i> <?php echo e($module['label']); ?>

                </span>
                <span style="font-size:11px;color:var(--text-subtle)">#<?php echo e($log->loggable_id); ?></span>
            </div>
            <?php if($changes->count() > 0 && $log->action === 'updated'): ?>
            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px">
                <?php $__currentLoopData = $changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $oldVal = $change['old'] ?? null;
                    $newVal = $change['new'] ?? null;
                    $formatVal = function($v) {
                        if (is_null($v) || $v === '') return '—';
                        if (is_array($v)) return '[file]';
                        if (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}/', $v)) {
                            try { return \Carbon\Carbon::parse($v)->format('M d, Y'); } catch (\Exception $e) {}
                        }
                        return \Illuminate\Support\Str::limit((string)$v, 24);
                    };
                    $fieldLabel = ucwords(str_replace('_', ' ', $field));
                ?>
                <div style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;font-size:11.5px">
                    <span style="font-weight:600;color:var(--text-muted)"><?php echo e($fieldLabel); ?>:</span>
                    <span style="color:var(--text-subtle);text-decoration:line-through"><?php echo e($formatVal($oldVal)); ?></span>
                    <i class="fas fa-arrow-right" style="font-size:8px;color:var(--text-subtle)"></i>
                    <span style="color:var(--navy);font-weight:500"><?php echo e($formatVal($newVal)); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($log->changes ?? []) > 4): ?>
                <span style="font-size:11px;color:var(--text-subtle);padding:3px 0">+<?php echo e(count($log->changes) - 4); ?> more</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div style="font-size:11px;color:var(--text-subtle)">
                <i class="fas fa-clock" style="font-size:9px;margin-right:3px"></i>
                <?php echo e($dateStr); ?> at <?php echo e($log->created_at->format('h:i A')); ?>

                <span style="margin:0 5px">·</span><?php echo e($log->created_at->diffForHumans()); ?>

            </div>
        </div>
        <?php if($routeName && $log->action !== 'deleted'): ?>
        <a href="<?php echo e(route($routeName, $log->loggable_id)); ?>" class="btn btn-secondary btn-sm btn-icon" title="View record" style="flex-shrink:0">
            <i class="fas fa-eye"></i>
        </a>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state"><i class="fas fa-clock-rotate-left"></i><p>No activity logs found.</p></div>
    <?php endif; ?>

    <?php if($query->hasPages()): ?>
    <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">Showing <?php echo e($query->firstItem()); ?>–<?php echo e($query->lastItem()); ?> of <?php echo e(number_format($query->total())); ?></span>
        <?php echo e($query->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const weeklyData  = <?php echo json_encode($weeklyData, 15, 512) ?>;
const monthlyData = <?php echo json_encode($monthlyData, 15, 512) ?>;
let chartsInitialized = false;

function initCharts() {
    if (chartsInitialized) return;
    chartsInitialized = true;

    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: weeklyData.map(d => d.label),
            datasets: [
                { label: 'Created', data: weeklyData.map(d => d.created), backgroundColor: '#16a34a', borderRadius: 3 },
                { label: 'Updated', data: weeklyData.map(d => d.updated), backgroundColor: '#f59e0b', borderRadius: 3 },
                { label: 'Deleted', data: weeklyData.map(d => d.deleted), backgroundColor: '#ef4444', borderRadius: 3 },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { stacked: true, ticks: { color: '#9CA3AF', font: { size: 11 } }, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
            }
        }
    });

    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthlyData.map(d => d.label + ' ' + d.year),
            datasets: [{
                label: 'Total Activity',
                data: monthlyData.map(d => d.total),
                borderColor: '#0D2144',
                backgroundColor: 'rgba(13,33,68,0.06)',
                borderWidth: 2,
                pointBackgroundColor: '#C8861A',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#9CA3AF', font: { size: 11 } }, grid: { color: '#E5E7EB' } },
                y: { beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
            }
        }
    });
}

function toggleCharts() {
    const section  = document.getElementById('charts-section');
    const label    = document.getElementById('charts-toggle-label');
    const chevron  = document.getElementById('charts-chevron');
    const btn      = document.getElementById('charts-toggle-btn');
    const isHidden = section.style.display === 'none';
    section.style.display = isHidden ? 'block' : 'none';
    label.textContent       = isHidden ? 'Hide Charts' : 'Show Charts';
    chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0)';
    btn.style.color         = isHidden ? 'var(--navy)' : 'var(--text-muted)';
    localStorage.setItem('activityChartsOpen', isHidden ? '1' : '0');
    if (isHidden) initCharts();
}

// Restore state
if (localStorage.getItem('activityChartsOpen') === '1') toggleCharts();

// Auto-refresh every 60s
setTimeout(() => location.reload(), 60000);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/activity-log/index.blade.php ENDPATH**/ ?>