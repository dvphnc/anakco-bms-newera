<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Alert Strip ─────────────────────────────────────────── */
.alert-strip { display:flex; flex-direction:column; gap:8px; margin-bottom:24px; }
.alert-item  { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:var(--radius); font-size:13px; }
.alert-item i { flex-shrink:0; font-size:14px; }
.alert-item > span { flex:1; line-height:1.5; }
.alert-senior  { background:var(--gold-pale);    border:1px solid var(--gold-border);    border-left:4px solid var(--gold);    color:#78450a; }
.alert-birthday{ background:var(--navy-pale);    border:1px solid var(--navy-border);    border-left:4px solid var(--navy);    color:var(--navy); }
.alert-permit  { background:var(--crimson-pale); border:1px solid var(--crimson-border); border-left:4px solid var(--crimson); color:var(--crimson); }
.alert-link {
    font-size:11.5px; font-weight:600; color:inherit; opacity:.8;
    text-decoration:none; padding:3px 12px; border:1px solid currentColor;
    border-radius:99px; white-space:nowrap; flex-shrink:0;
}
.alert-link:hover { opacity:1; }

/* ── Stat Cards ──────────────────────────────────────────── */
.dash-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.dash-stat-card {
    display:flex; align-items:center; gap:16px;
    background:var(--surface); border:1px solid var(--border);
    border-radius:var(--radius-lg); padding:20px 22px;
    text-decoration:none; box-shadow:var(--shadow-sm);
    transition:box-shadow .15s, transform .15s;
}
.dash-stat-card:hover { box-shadow:var(--shadow-md); transform:translateY(-2px); }
.dash-stat-icon {
    width:48px; height:48px; border-radius:var(--radius);
    display:flex; align-items:center; justify-content:center;
    font-size:20px; flex-shrink:0;
}
.dash-stat-number { font-size:26px; font-weight:700; color:var(--text); line-height:1.1; }
.dash-stat-label  { font-size:12px; color:var(--text-muted); margin-top:3px; }

/* ── Mid Row: Chart + Quick Access ───────────────────────── */
.dash-mid { display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:24px; }

.quick-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.quick-item {
    display:flex; flex-direction:column; align-items:center; gap:7px;
    padding:14px 6px; background:var(--surface2);
    border:1px solid var(--border); border-radius:var(--radius);
    text-decoration:none; transition:all .15s;
}
.quick-item:hover { background:var(--navy-pale); border-color:var(--qa-color, var(--navy)); }
.quick-icon { width:38px; height:38px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:16px; }
.quick-label { font-size:11px; font-weight:600; color:var(--text); text-align:center; }

/* ── Bottom 2-col ────────────────────────────────────────── */
.dash-bottom { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

/* ── Feed Rows (shared) ──────────────────────────────────── */
.feed-row {
    display:flex; align-items:center; gap:12px;
    padding:12px 16px; border-bottom:1px solid var(--border);
    text-decoration:none; transition:background .1s;
}
.feed-row:last-child { border-bottom:none; }
.feed-row:hover { background:var(--navy-pale); }
.feed-icon { width:36px; height:36px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; }
.feed-body { flex:1; min-width:0; }
.feed-title { font-size:12.5px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:11px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?php echo e(auth()->user()->name); ?> — <?php echo e(now()->format('l, F d, Y')); ?></p>
    </div>
    <div class="page-actions no-print">
        <a href="<?php echo e(route('residents.create')); ?>"  class="btn btn-secondary btn-sm"><i class="fas fa-user-plus"></i> New Resident</a>
        <a href="<?php echo e(route('documents.create')); ?>"  class="btn btn-secondary btn-sm"><i class="fas fa-file-plus"></i> New Document</a>
        <a href="<?php echo e(route('blotter.create')); ?>"    class="btn btn-secondary btn-sm"><i class="fas fa-gavel"></i> New Blotter</a>
        <a href="<?php echo e(route('businesses.create')); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-store"></i> New Permit</a>
    </div>
</div>


<?php
    $today        = now()->format('m-d');
    $birthdays    = \App\Models\Resident::where('residency_status', 'Active')
        ->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$today])
        ->orderBy('last_name')->get();
    $seniorBdays  = $birthdays->filter(fn($r) => $r->age >= 60);
    $regularBdays = $birthdays->filter(fn($r) => $r->age < 60);

    $expiringPermits = \App\Models\Business::where('status', 'Active')
        ->whereBetween('expiry_date', [now(), now()->addDays(30)])
        ->orderBy('expiry_date')->get();
?>

<?php if($birthdays->count() || $expiringPermits->count()): ?>
<div class="alert-strip">

    <?php if($seniorBdays->count()): ?>
    <div class="alert-item alert-senior">
        <i class="fas fa-star"></i>
        <span>
            <strong>Senior Citizen <?php echo e($seniorBdays->count() === 1 ? 'Birthday' : 'Birthdays'); ?> Today (<?php echo e($seniorBdays->count()); ?>) —</strong>
            <?php echo e($seniorBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ')); ?><?php echo e($seniorBdays->count() > 4 ? ' +' . ($seniorBdays->count() - 4) . ' more' : ''); ?>

        </span>
        <a href="<?php echo e(route('residents.index')); ?>" class="alert-link">View All</a>
    </div>
    <?php endif; ?>

    <?php if($regularBdays->count()): ?>
    <div class="alert-item alert-birthday">
        <i class="fas fa-birthday-cake"></i>
        <span>
            <strong><?php echo e($regularBdays->count() === 1 ? 'Birthday' : 'Birthdays'); ?> Today (<?php echo e($regularBdays->count()); ?>) —</strong>
            <?php echo e($regularBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ')); ?><?php echo e($regularBdays->count() > 4 ? ' +' . ($regularBdays->count() - 4) . ' more' : ''); ?>

        </span>
    </div>
    <?php endif; ?>

    <?php if($expiringPermits->count()): ?>
    <div class="alert-item alert-permit">
        <i class="fas fa-triangle-exclamation"></i>
        <span>
            <strong><?php echo e($expiringPermits->count()); ?> Business Permit<?php echo e($expiringPermits->count() > 1 ? 's' : ''); ?> Expiring Within 30 Days —</strong>
            <?php echo e($expiringPermits->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ')); ?><?php echo e($expiringPermits->count() > 3 ? ' +' . ($expiringPermits->count() - 3) . ' more' : ''); ?>

        </span>
        <a href="<?php echo e(route('businesses.index')); ?>" class="alert-link">View All</a>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?>


<div class="dash-stats">
    <a href="<?php echo e(route('residents.index')); ?>" class="dash-stat-card">
        <div class="dash-stat-icon" style="background:#eef2ff;color:#4f46e5"><i class="fas fa-users"></i></div>
        <div>
            <div class="dash-stat-number"><?php echo e(number_format($totalResidents)); ?></div>
            <div class="dash-stat-label">Total Residents</div>
        </div>
    </a>
    <a href="<?php echo e(route('documents.index')); ?>" class="dash-stat-card">
        <div class="dash-stat-icon" style="background:#fffbeb;color:#d97706"><i class="fas fa-file-alt"></i></div>
        <div>
            <div class="dash-stat-number"><?php echo e(number_format($pendingDocuments)); ?></div>
            <div class="dash-stat-label">Pending Documents</div>
        </div>
    </a>
    <a href="<?php echo e(route('blotter.index')); ?>" class="dash-stat-card">
        <div class="dash-stat-icon" style="background:#fef2f2;color:#dc2626"><i class="fas fa-gavel"></i></div>
        <div>
            <div class="dash-stat-number"><?php echo e(number_format($activeBlotter)); ?></div>
            <div class="dash-stat-label">Active Blotter Cases</div>
        </div>
    </a>
    <a href="<?php echo e(route('businesses.index')); ?>" class="dash-stat-card">
        <div class="dash-stat-icon" style="background:#f0fdf4;color:#16a34a"><i class="fas fa-store"></i></div>
        <div>
            <div class="dash-stat-number"><?php echo e(number_format($activeBusinesses)); ?></div>
            <div class="dash-stat-label">Active Businesses</div>
        </div>
    </a>
</div>


<div class="dash-mid">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Documents Issued — <?php echo e(date('Y')); ?></span>
        </div>
        <div class="card-body">
            <canvas id="monthlyDocChart" height="105"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
        </div>
        <div class="card-body">
            <div class="quick-grid">
                <?php $links = [
                    ['href' => route('residents.index'),  'icon' => 'fa-users',     'label' => 'Residents',  'color' => '#4f46e5'],
                    ['href' => route('households.index'), 'icon' => 'fa-house',     'label' => 'Households', 'color' => '#0891b2'],
                    ['href' => route('documents.index'),  'icon' => 'fa-file-alt',  'label' => 'Documents',  'color' => '#d97706'],
                    ['href' => route('blotter.index'),    'icon' => 'fa-gavel',     'label' => 'Blotter',    'color' => '#dc2626'],
                    ['href' => route('businesses.index'), 'icon' => 'fa-store',     'label' => 'Businesses', 'color' => '#16a34a'],
                    ['href' => route('officials.index'),  'icon' => 'fa-user-tie',  'label' => 'Officials',  'color' => '#7c3aed'],
                    ['href' => route('reports.index'),    'icon' => 'fa-chart-bar', 'label' => 'Analytics',  'color' => '#0D2144'],
                    ['href' => route('reports.generate'), 'icon' => 'fa-file-pdf',  'label' => 'Reports',    'color' => '#ef4444'],
                    ['href' => route('backup.index'),     'icon' => 'fa-database',  'label' => 'Backup',     'color' => '#374151'],
                ]; ?>
                <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($l['href']); ?>" class="quick-item" style="--qa-color:<?php echo e($l['color']); ?>">
                    <div class="quick-icon" style="background:<?php echo e($l['color']); ?>18;color:<?php echo e($l['color']); ?>">
                        <i class="fas <?php echo e($l['icon']); ?>"></i>
                    </div>
                    <span class="quick-label"><?php echo e($l['label']); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

</div>


<div class="dash-bottom">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-alt"></i> Recent Documents</span>
            <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            <?php $__empty_1 = true; $__currentLoopData = $recentDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('documents.show', $d->id)); ?>" class="feed-row">
                <div class="feed-icon" style="background:#fffbeb"><i class="fas fa-file-alt" style="color:#d97706"></i></div>
                <div class="feed-body">
                    <div class="feed-title"><?php echo e($d->doc_number); ?></div>
                    <div class="feed-sub"><?php echo e($d->resident->full_name ?? '—'); ?> · <?php echo e($d->document_type); ?></div>
                </div>
                <span class="badge <?php echo e($d->status === 'Released' ? 'badge-green' : ($d->status === 'Pending' ? 'badge-yellow' : 'badge-blue')); ?>" style="font-size:10px"><?php echo e($d->status); ?></span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:32px"><i class="fas fa-file-alt"></i><p>No documents yet</p></div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-gavel"></i> Recent Blotter</span>
            <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            <?php $__empty_1 = true; $__currentLoopData = $recentBlotter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('blotter.show', $b->id)); ?>" class="feed-row">
                <div class="feed-icon" style="background:#fef2f2"><i class="fas fa-gavel" style="color:#dc2626"></i></div>
                <div class="feed-body">
                    <div class="feed-title"><?php echo e($b->case_number); ?></div>
                    <div class="feed-sub"><?php echo e($b->incident_type); ?></div>
                </div>
                <span class="badge <?php echo e($b->status === 'Settled' ? 'badge-green' : ($b->status === 'Active' ? 'badge-red' : 'badge-gray')); ?>" style="font-size:10px"><?php echo e($b->status); ?></span>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:32px"><i class="fas fa-gavel"></i><p>No blotter cases yet</p></div>
            <?php endif; ?>
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
            data: [<?php echo e($monthlyData[1]); ?>,<?php echo e($monthlyData[2]); ?>,<?php echo e($monthlyData[3]); ?>,<?php echo e($monthlyData[4]); ?>,<?php echo e($monthlyData[5]); ?>,<?php echo e($monthlyData[6]); ?>,<?php echo e($monthlyData[7]); ?>,<?php echo e($monthlyData[8]); ?>,<?php echo e($monthlyData[9]); ?>,<?php echo e($monthlyData[10]); ?>,<?php echo e($monthlyData[11]); ?>,<?php echo e($monthlyData[12]); ?>],
            backgroundColor: 'rgba(13,33,68,0.10)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(200,134,26,0.18)',
            hoverBorderColor: '#C8861A',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 11, family: 'Poppins' } }, grid: { color: '#F3F4F6' } },
            y: { ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 }, grid: { color: '#F3F4F6' }, beginAtZero: true }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/dashboard.blade.php ENDPATH**/ ?>