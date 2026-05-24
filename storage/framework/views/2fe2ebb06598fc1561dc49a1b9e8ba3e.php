<?php if($paginator->hasPages()): ?>
<nav style="display:flex;align-items:center;gap:4px;flex-wrap:wrap">

    
    <?php if($paginator->onFirstPage()): ?>
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface2);color:var(--text-subtle);font-size:12px;cursor:default">
            <i class="fas fa-chevron-left"></i>
        </span>
    <?php else: ?>
        <a href="<?php echo e($paginator->previousPageUrl()); ?>"
           style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;color:var(--text);font-size:12px;text-decoration:none;transition:all 0.15s"
           onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
            <i class="fas fa-chevron-left"></i>
        </a>
    <?php endif; ?>

    
    <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(is_string($element)): ?>
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:13px;color:var(--text-subtle)">…</span>
        <?php endif; ?>
        <?php if(is_array($element)): ?>
            <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($page == $paginator->currentPage()): ?>
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1.5px solid var(--navy);background:var(--navy);color:#fff;font-size:13px;font-weight:600">
                        <?php echo e($page); ?>

                    </span>
                <?php else: ?>
                    <a href="<?php echo e($url); ?>"
                       style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;color:var(--text-muted);font-size:13px;text-decoration:none;transition:all 0.15s"
                       onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)';this.style.background='var(--navy-pale)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)';this.style.background='#fff'">
                        <?php echo e($page); ?>

                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if($paginator->hasMorePages()): ?>
        <a href="<?php echo e($paginator->nextPageUrl()); ?>"
           style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;color:var(--text);font-size:12px;text-decoration:none;transition:all 0.15s"
           onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
            <i class="fas fa-chevron-right"></i>
        </a>
    <?php else: ?>
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface2);color:var(--text-subtle);font-size:12px;cursor:default">
            <i class="fas fa-chevron-right"></i>
        </span>
    <?php endif; ?>

</nav>
<?php endif; ?>
<?php /**PATH D:\laragon\www\anakco_bms\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>