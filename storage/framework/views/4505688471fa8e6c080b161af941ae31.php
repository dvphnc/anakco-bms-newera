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

<?php $__empty_1 = true; $__currentLoopData = $query; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<?php
    $module = $moduleMap[$log->loggable_type] ?? ['label' => 'Record', 'icon' => 'fa-circle', 'color' => '#9ca3af', 'slug' => ''];
    $actionColor = match($log->action) { 'created' => '#2e6b47', 'deleted' => '#8b2e2e', default => '#7a5200' };
    $actionBg    = match($log->action) { 'created' => '#e6f2ec', 'deleted' => '#f5e8e8', default => '#fdf0d5' };
    $dateStr = $log->created_at->isToday() ? 'Today' : ($log->created_at->isYesterday() ? 'Yesterday' : $log->created_at->format('M d, Y'));
    $changes = collect($log->changes ?? [])->filter(fn($v, $k) => !in_array($k, $skipFields))->take(4);
    $routeName = $routeMap[$log->loggable_type] ?? null;
?>
<div style="display:flex;gap:14px;padding:16px 20px;border-bottom:1px solid var(--border);align-items:flex-start">
    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0;margin-top:1px">
        <?php echo e(strtoupper(substr($log->user->name ?? '?', 0, 1))); ?>

    </div>
    <div style="flex:1;min-width:0">
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:7px">
            <span style="font-size:14px;font-weight:600;color:var(--text)"><?php echo e($log->user->name ?? 'Unknown'); ?></span>
            <span class="badge badge-navy"><?php echo e($log->user->role ?? 'Staff'); ?></span>
            <span style="display:inline-flex;align-items:center;padding:3px 9px;border-radius:99px;font-size:13px;font-weight:700;background:<?php echo e($actionBg); ?>;color:<?php echo e($actionColor); ?>">
                <?php echo e(strtoupper($log->action)); ?>

            </span>
            <span style="font-size:13px;font-weight:600;color:<?php echo e($module['color']); ?>">
                <i class="fas <?php echo e($module['icon']); ?>" style="font-size:11px"></i> <?php echo e($module['label']); ?>

            </span>
            <span style="font-size:13px;color:var(--text-subtle)">#<?php echo e($log->loggable_id); ?></span>
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
            <div style="display:inline-flex;align-items:center;gap:5px;padding:4px 11px;background:var(--surface2);border:1px solid var(--border);border-radius:6px;font-size:13px">
                <span style="font-weight:600;color:var(--text-muted)"><?php echo e($fieldLabel); ?>:</span>
                <span style="color:var(--text-subtle);text-decoration:line-through"><?php echo e($formatVal($oldVal)); ?></span>
                <i class="fas fa-arrow-right" style="font-size:9px;color:var(--text-subtle)"></i>
                <span style="color:var(--navy);font-weight:500"><?php echo e($formatVal($newVal)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if(count($log->changes ?? []) > 4): ?>
            <span style="font-size:13px;color:var(--text-subtle);padding:3px 0">+<?php echo e(count($log->changes) - 4); ?> more</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div style="font-size:13px;color:var(--text-subtle)">
            <i class="fas fa-clock" style="font-size:10px;margin-right:3px"></i>
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
    <span style="font-size:13px;color:var(--text-muted)">Showing <?php echo e($query->firstItem()); ?>–<?php echo e($query->lastItem()); ?> of <?php echo e(number_format($query->total())); ?></span>
    <?php echo e($query->withQueryString()->links()); ?>

</div>
<?php endif; ?>
<?php /**PATH D:\laragon\www\anakco_bms\resources\views\activity-log\_feed.blade.php ENDPATH**/ ?>