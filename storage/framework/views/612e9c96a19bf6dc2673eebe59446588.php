<?php $__env->startSection('title', 'Database Backup'); ?>
<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Database Backup & Restore</h1>
        <p class="page-subtitle">Manage database backups for Barangay New Era BMS</p>
    </div>
    <div class="page-actions">
        <form method="POST" action="<?php echo e(route('backup.create')); ?>"
              data-confirm="Create a new database backup now? The previous backup will not be deleted."
              data-confirm-title="Create Backup"
              data-confirm-ok="Create Backup"
              data-confirm-type="safe">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-database"></i> Backup Now
            </button>
        </form>
    </div>
</div>

<div class="grid-2 mb-6" style="grid-template-columns:2fr 1fr">

    
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-history"></i> Backup History</span>
            <span style="font-size:13px;color:var(--text-muted)">Last 10 backups kept automatically</span>
        </div>
        <?php if($files->count()): ?>
        <table>
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:32px;height:32px;border-radius:var(--radius-sm);background:<?php echo e($i === 0 ? 'rgba(22,101,52,0.1)' : 'var(--surface2)'); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fas fa-file-code" style="color:<?php echo e($i === 0 ? '#16a34a' : 'var(--text-muted)'); ?>;font-size:13px"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;font-family:monospace;color:var(--text)"><?php echo e($file['name']); ?></div>
                                <?php if($i === 0): ?><span class="badge badge-green">Latest</span><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted)"><?php echo e($file['size']); ?></td>
                    <td style="color:var(--text-muted)"><?php echo e($file['created']); ?></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="<?php echo e(route('backup.download', $file['name'])); ?>"
                               class="btn btn-secondary btn-sm btn-icon" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('backup.restore')); ?>"
                                  data-confirm="Restore from <?php echo e($file['name']); ?>? This will OVERWRITE all current data and cannot be undone."
                                  data-confirm-title="Restore Database"
                                  data-confirm-ok="Yes, Restore">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="filename" value="<?php echo e($file['name']); ?>">
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon" title="Restore" style="color:var(--gold)">
                                    <i class="fas fa-rotate-left"></i>
                                </button>
                            </form>
                            <form method="POST" action="<?php echo e(route('backup.delete', $file['name'])); ?>"
                                  data-confirm="Delete backup file <?php echo e($file['name']); ?>? This cannot be recovered."
                                  data-confirm-title="Delete Backup"
                                  data-confirm-ok="Delete">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-database"></i>
            <p>No backups yet. Click <strong>Backup Now</strong> to create one.</p>
        </div>
        <?php endif; ?>
    </div>

    
    <div style="display:flex;flex-direction:column;gap:16px">

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-upload"></i> Upload & Restore</span>
            </div>
            <div class="card-body">
                <p style="font-size:14px;color:var(--text-muted);margin-bottom:14px">
                    Upload a <code>.sql</code> backup file from your computer to restore it.
                </p>
                <form method="POST" action="<?php echo e(route('backup.upload')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group mb-4">
                        <label class="form-label">SQL Backup File</label>
                        <input type="file" name="backup_file" class="form-control" accept=".sql,.txt" required>
                    </div>
                    <button type="submit" class="btn btn-secondary" style="width:100%">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                </form>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle"></i> Backup Info</span>
            </div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px">
                    <?php
                        $items = [
                            ['icon'=>'fa-database','label'=>'Database','value'=>config('database.connections.mysql.database'),'color'=>'var(--navy)'],
                            ['icon'=>'fa-server',  'label'=>'Host',    'value'=>config('database.connections.mysql.host').':'.config('database.connections.mysql.port'),'color'=>'var(--gold)'],
                            ['icon'=>'fa-folder',  'label'=>'Storage', 'value'=>'storage/app/backups/','color'=>'#16a34a'],
                            ['icon'=>'fa-shield-halved','label'=>'Retention','value'=>'Last 10 backups','color'=>'#2563eb'],
                        ];
                    ?>
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;align-items:center;gap:10px;padding:8px;background:var(--surface2);border-radius:var(--radius-sm);border:1px solid var(--border)">
                        <div style="width:28px;height:28px;border-radius:var(--radius-sm);background:<?php echo e($item['color']); ?>15;color:<?php echo e($item['color']); ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="fas <?php echo e($item['icon']); ?>" style="font-size:12px"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-subtle);text-transform:uppercase;letter-spacing:0.06em"><?php echo e($item['label']); ?></div>
                            <div style="font-size:13px;font-weight:600;color:var(--text);font-family:monospace"><?php echo e($item['value']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div style="margin-top:16px;padding:10px;background:#fef9c3;border:1px solid #fde047;border-radius:var(--radius-sm)">
                    <div style="font-size:13px;color:#854d0e;font-weight:600;margin-bottom:4px"><i class="fas fa-exclamation-triangle" style="margin-right:4px"></i> Warning</div>
                    <div style="font-size:13px;color:#854d0e">Restoring a backup will <strong>overwrite all current data</strong>. Always create a fresh backup before restoring.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/backup/index.blade.php ENDPATH**/ ?>