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
?>


<div style="margin-bottom:16px">
    <button onclick="toggleCharts()" id="charts-toggle-btn"
            style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:9px 18px;min-height:44px;font-size:13px;font-weight:600;color:var(--text-muted);cursor:pointer;display:flex;align-items:center;gap:8px;font-family:'Poppins',sans-serif;transition:all 0.15s">
        <i class="fas fa-chart-line" style="color:var(--gold)"></i>
        <span id="charts-toggle-label">Show Charts</span>
        <i class="fas fa-chevron-down" id="charts-chevron" style="font-size:12px;transition:transform 0.2s"></i>
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
        <?php
            $monthlyArr = collect($monthlyData ?? []);
            $peakMonth  = $monthlyArr->sortByDesc('total')->first();
            $latestMonth= $monthlyArr->last();
            $prevMonth  = $monthlyArr->count() > 1 ? $monthlyArr->reverse()->skip(1)->first() : null;
        ?>
        <?php if($peakMonth): ?>
        <div class="chart-insight" style="margin-top:14px">
            Peak month: <strong><?php echo e($peakMonth['label'] ?? ''); ?> <?php echo e($peakMonth['year'] ?? ''); ?></strong> with <strong><?php echo e(number_format($peakMonth['total'])); ?></strong> actions.
            <?php if($latestMonth && $prevMonth): ?>
                This month so far: <strong><?php echo e(number_format($latestMonth['total'])); ?></strong>
                <?php if($latestMonth['total'] > $prevMonth['total']): ?>
                    — <span style="color:#16a34a"><i class="fas fa-arrow-up"></i> up from <?php echo e($prevMonth['total']); ?> last month.</span>
                <?php elseif($latestMonth['total'] < $prevMonth['total']): ?>
                    — <span style="color:var(--crimson)"><i class="fas fa-arrow-down"></i> down from <?php echo e($prevMonth['total']); ?> last month.</span>
                <?php else: ?>
                    — same as last month.
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-chart-bar"></i> Activity This Week</span>
        <div style="display:flex;gap:14px;font-size:13px;color:var(--text-muted)">
            <span style="display:flex;align-items:center;gap:5px"><span style="width:11px;height:11px;border-radius:2px;background:#16a34a;display:inline-block"></span>Created</span>
            <span style="display:flex;align-items:center;gap:5px"><span style="width:11px;height:11px;border-radius:2px;background:#f59e0b;display:inline-block"></span>Updated</span>
            <span style="display:flex;align-items:center;gap:5px"><span style="width:11px;height:11px;border-radius:2px;background:#ef4444;display:inline-block"></span>Deleted</span>
        </div>
    </div>
    <div class="card-body">
        <canvas id="weeklyChart" height="80"></canvas>
        <?php
            $weeklyArr  = collect($weeklyData ?? []);
            $totalWeek  = $weeklyArr->sum('created') + $weeklyArr->sum('updated') + $weeklyArr->sum('deleted');
            $createdWk  = $weeklyArr->sum('created');
            $updatedWk  = $weeklyArr->sum('updated');
            $deletedWk  = $weeklyArr->sum('deleted');
        ?>
        <div class="chart-insight" style="margin-top:14px">
            <strong><?php echo e(number_format($totalWeek)); ?></strong> total actions this week —
            <span style="color:#166534"><strong><?php echo e($createdWk); ?></strong> created</span>,
            <span style="color:#92400e"><strong><?php echo e($updatedWk); ?></strong> updated</span>,
            <span style="color:#991b1b"><strong><?php echo e($deletedWk); ?></strong> deleted</span>.
        </div>
    </div>
</div>

</div>


<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="<?php echo e(route('activity-log.index')); ?>" onsubmit="return false;">
            <?php if(request('module')): ?>
                <input type="hidden" name="module" value="<?php echo e(request('module')); ?>">
            <?php endif; ?>
            <?php if(request('period')): ?>
                <input type="hidden" name="period" value="<?php echo e(request('period')); ?>">
            <?php endif; ?>
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label">Action</label>
                    <select name="action" id="filterAction" class="form-control">
                        <option value="">All Actions</option>
                        <option value="created" <?php echo e(request('action') === 'created' ? 'selected' : ''); ?>>Created</option>
                        <option value="updated" <?php echo e(request('action') === 'updated' ? 'selected' : ''); ?>>Updated</option>
                        <option value="deleted" <?php echo e(request('action') === 'deleted' ? 'selected' : ''); ?>>Deleted</option>
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label class="form-label">User</label>
                    <select name="user_id" id="filterUser" class="form-control">
                        <option value="">All Users</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <a href="<?php echo e(route('activity-log.index')); ?>" class="btn btn-secondary" onclick="resetFilters(); return false;"><i class="fas fa-xmark"></i> Reset All</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:20px" id="module-pills">
    <a href="<?php echo e(route('activity-log.index')); ?>"
       style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:99px;border:1.5px solid <?php echo e(!request('module') ? 'var(--navy)' : 'var(--border)'); ?>;background:<?php echo e(!request('module') ? 'var(--navy)' : 'var(--surface)'); ?>;color:<?php echo e(!request('module') ? '#fff' : 'var(--text-muted)'); ?>;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas fa-clock-rotate-left" style="font-size:12px"></i> All
    </a>
    <?php $__currentLoopData = $moduleMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $count = $moduleCounts[$class] ?? 0; ?>
    <?php if($count > 0): ?>
    <a href="<?php echo e(route('activity-log.index', ['module' => $info['slug']])); ?>"
       style="display:inline-flex;align-items:center;gap:7px;padding:8px 16px;border-radius:99px;border:1.5px solid <?php echo e(request('module') === $info['slug'] ? $info['color'] : 'var(--border)'); ?>;background:<?php echo e(request('module') === $info['slug'] ? $info['color'].'18' : 'var(--surface)'); ?>;color:<?php echo e(request('module') === $info['slug'] ? $info['color'] : 'var(--text-muted)'); ?>;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.15s">
        <i class="fas <?php echo e($info['icon']); ?>" style="font-size:12px"></i>
        <?php echo e($info['label']); ?>

        <span style="background:<?php echo e(request('module') === $info['slug'] ? $info['color'] : 'var(--surface3)'); ?>;color:<?php echo e(request('module') === $info['slug'] ? '#fff' : 'var(--text-muted)'); ?>;border-radius:99px;padding:1px 7px;font-size:13px"><?php echo e($count); ?></span>
    </a>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>



<div class="card" id="activity-feed">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Activity Feed</span>
        <span id="feed-total" style="font-size:13px;color:var(--text-muted)"><?php echo e(number_format($query->total())); ?> entries · refreshes every 60s</span>
    </div>
    <div id="feed-body">
        <?php echo $__env->make('activity-log._feed', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
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
                x: { stacked: true, ticks: { color: '#9CA3AF', font: { size: 12 } }, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 12 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
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
                x: { ticks: { color: '#9CA3AF', font: { size: 12 } }, grid: { color: '#E5E7EB' } },
                y: { beginAtZero: true, ticks: { color: '#9CA3AF', font: { size: 12 }, stepSize: 1 }, grid: { color: '#E5E7EB' } }
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

// Restore chart state
if (localStorage.getItem('activityChartsOpen') === '1') toggleCharts();

// ── Filter state (seeded from current URL) ──────────────────────────────────
var _filterState = {
    module:  '<?php echo e(request("module")); ?>',
    action:  '<?php echo e(request("action")); ?>',
    user_id: '<?php echo e(request("user_id")); ?>',
    period:  '<?php echo e(request("period")); ?>',
    page:    '<?php echo e(request("page")); ?>'
};

function fetchFeed() {
    var params = new URLSearchParams();
    Object.keys(_filterState).forEach(function(k) {
        if (_filterState[k]) params.set(k, _filterState[k]);
    });
    var qs  = params.toString();
    var url = '<?php echo e(route("activity-log.index")); ?>' + (qs ? '?' + qs : '');
    history.pushState({}, '', url);

    var card = document.getElementById('activity-feed');
    card.style.opacity       = '0.55';
    card.style.pointerEvents = 'none';

    axios.get(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function(res) {
            document.getElementById('feed-body').innerHTML = res.data.html;
            var el = document.getElementById('feed-total');
            if (el) el.textContent = res.data.total.toLocaleString() + ' entries · refreshes every 60s';
        })
        .catch(function() { location.href = url; })
        .finally(function() {
            card.style.opacity       = '1';
            card.style.pointerEvents = '';
        });
}

function resetFilters() {
    _filterState.action  = '';
    _filterState.user_id = '';
    _filterState.page    = '';
    $('#filterAction').val('').trigger('change.select2');
    $('#filterUser').val('').trigger('change.select2');
    fetchFeed();
}

// ── Select2 ──────────────────────────────────────────────────────────────────
$(function(){
    $('#filterAction').select2({ minimumResultsForSearch: -1, width: '100%' })
        .on('change', function() {
            _filterState.action = $(this).val() || '';
            _filterState.page   = '';
            fetchFeed();
        });

    $('#filterUser').select2({ minimumResultsForSearch: -1, width: '100%' })
        .on('change', function() {
            _filterState.user_id = $(this).val() || '';
            _filterState.page    = '';
            fetchFeed();
        });
});

// Auto-refresh every 60s via Axios (no full reload)
setInterval(fetchFeed, 60000);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/activity-log/index.blade.php ENDPATH**/ ?>