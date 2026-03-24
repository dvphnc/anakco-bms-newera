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


<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px">
    <a href="<?php echo e(route('activity-log.index')); ?>"
       style="flex:1;min-width:110px;display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border:2px solid <?php echo e(!request('module') ? 'var(--navy)' : 'var(--border)'); ?>;border-radius:var(--radius-lg);text-decoration:none;transition:all 0.15s">
        <div style="width:40px;height:40px;border-radius:var(--radius);background:rgba(13,33,68,0.08);color:var(--navy);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
            <i class="fas fa-clock-rotate-left"></i>
        </div>
        <div>
            <div style="font-size:20px;font-weight:700;color:var(--navy)"><?php echo e(number_format(\App\Models\ActivityLog::count())); ?></div>
            <div style="font-size:11px;color:var(--text-muted)">All Activity</div>
        </div>
    </a>

    <?php $__currentLoopData = $moduleMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $count = $moduleCounts[$class] ?? 0; ?>
    <?php if($count > 0): ?>
    <a href="<?php echo e(route('activity-log.index', ['module' => $info['slug']])); ?>"
       style="flex:1;min-width:110px;display:flex;align-items:center;gap:12px;padding:16px;background:var(--surface);border:2px solid <?php echo e(request('module') === $info['slug'] ? 'var(--navy)' : 'var(--border)'); ?>;border-radius:var(--radius-lg);text-decoration:none;transition:all 0.15s">
        <div style="width:40px;height:40px;border-radius:var(--radius);background:<?php echo e($info['color']); ?>18;color:<?php echo e($info['color']); ?>;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">
            <i class="fas <?php echo e($info['icon']); ?>"></i>
        </div>
        <div>
            <div style="font-size:20px;font-weight:700;color:var(--navy)"><?php echo e(number_format($count)); ?></div>
            <div style="font-size:11px;color:var(--text-muted)"><?php echo e($info['label']); ?></div>
        </div>
    </a>
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="<?php echo e(route('activity-log.index')); ?>">
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label">Module</label>
                    <select name="module" class="form-control" onchange="this.form.submit()">
                        <option value="">All Modules</option>
                        <?php $__currentLoopData = $moduleMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($info['slug']); ?>" <?php echo e(request('module') === $info['slug'] ? 'selected' : ''); ?>>
                            <?php echo e($info['label']); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Action</label>
                    <select name="action" class="form-control" onchange="this.form.submit()">
                        <option value="">All Actions</option>
                        <option value="created" <?php echo e(request('action') === 'created' ? 'selected' : ''); ?>>Created</option>
                        <option value="updated" <?php echo e(request('action') === 'updated' ? 'selected' : ''); ?>>Updated</option>
                        <option value="deleted" <?php echo e(request('action') === 'deleted' ? 'selected' : ''); ?>>Deleted</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-control" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>>
                            <?php echo e($u->name); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">&nbsp;</label>
                    <a href="<?php echo e(route('activity-log.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-xmark"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Activity Feed</span>
        <span style="font-size:12px;color:var(--text-muted)"><?php echo e($query->total()); ?> entries · refreshes every 30s</span>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
        $module = $moduleMap[$log->loggable_type] ?? ['label' => 'Record', 'icon' => 'fa-circle', 'color' => '#9ca3af', 'slug' => ''];

        $actionLabel = match($log->action) {
            'created' => 'created a new',
            'deleted' => 'deleted a',
            default   => 'updated a',
        };

        $actionColor = match($log->action) {
            'created' => '#166534',
            'deleted' => '#991b1b',
            default   => '#92400e',
        };

        $dateStr = $log->created_at->isToday()
            ? 'Today'
            : ($log->created_at->isYesterday() ? 'Yesterday' : $log->created_at->format('M d, Y'));

        $changes = collect($log->changes ?? [])
            ->filter(fn($v, $k) => !in_array($k, $skipFields))
            ->take(4);

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
                <span style="font-size:12px;color:var(--text-muted)"><?php echo e($actionLabel); ?></span>
                <span style="font-size:12px;font-weight:600;color:<?php echo e($module['color']); ?>">
                    <i class="fas <?php echo e($module['icon']); ?>" style="font-size:10px"></i>
                    <?php echo e($module['label']); ?>

                </span>
                <span style="font-size:11px;color:var(--text-subtle)">#<?php echo e($log->loggable_id); ?></span>
            </div>

            
            <?php if($changes->count() > 0 && $log->action === 'updated'): ?>
            <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px">
                <?php $__currentLoopData = $changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $oldVal = $change['old'] ?? null;
                    $newVal = $change['new'] ?? null;
                    // Format any date string
                    $formatDate = function($v) {
                        if (is_null($v) || $v === '') return '—';
                        if (is_array($v)) return '[file]';
                        if (is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}/', $v)) {
                            try { return \Carbon\Carbon::parse($v)->format('M d, Y'); } catch (\Exception $e) {}
                        }
                        return \Illuminate\Support\Str::limit((string)$v, 24);
                    };
                    $oldVal = $formatDate($oldVal);
                    $newVal = $formatDate($newVal);
                    // Field label formatting
                    $fieldLabel = ucwords(str_replace('_', ' ', $field));
                ?>
                <div style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;font-size:11.5px">
                    <span style="font-weight:600;color:var(--text-muted)"><?php echo e($fieldLabel); ?>:</span>
                    <span style="color:var(--text-subtle);text-decoration:line-through"><?php echo e($oldVal); ?></span>
                    <i class="fas fa-arrow-right" style="font-size:8px;color:var(--text-subtle)"></i>
                    <span style="color:var(--navy);font-weight:500"><?php echo e($newVal); ?></span>
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

                <span style="margin:0 5px">·</span>
                <?php echo e($log->created_at->diffForHumans()); ?>

            </div>
        </div>

        
        <?php if($routeName && $log->action !== 'deleted'): ?>
        <a href="<?php echo e(route($routeName, $log->loggable_id)); ?>"
           class="btn btn-secondary btn-sm btn-icon" title="View record" style="flex-shrink:0">
            <i class="fas fa-eye"></i>
        </a>
        <?php endif; ?>

    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state">
        <i class="fas fa-clock-rotate-left"></i>
        <p>No activity logs found.</p>
    </div>
    <?php endif; ?>

    <?php if($query->hasPages()): ?>
    <div style="padding:14px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing <?php echo e($query->firstItem()); ?>–<?php echo e($query->lastItem()); ?> of <?php echo e(number_format($query->total())); ?>

        </span>
        <?php echo e($query->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
setTimeout(() => location.reload(), 30000);
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/activity-log/index.blade.php ENDPATH**/ ?>