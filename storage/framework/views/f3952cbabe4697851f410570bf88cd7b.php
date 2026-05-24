<?php
$roleCls = match($user->role) {
    'Admin'     => 'badge-red',
    'Secretary' => 'badge-yellow',
    'Committee' => 'badge-navy',
    default     => 'badge-gray'
};
?>
<tr data-id="<?php echo e($user->id); ?>">
    <td>
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>
            <div style="font-weight:600;font-size:14px"><?php echo e($user->name); ?></div>
        </div>
    </td>
    <td class="td-muted"><?php echo e($user->email); ?></td>
    <td><span class="badge <?php echo e($roleCls); ?>"><?php echo e($user->role); ?></span></td>
    <td><span class="badge badge-gray">Unverified</span></td>
    <td class="td-muted"><?php echo e($user->created_at->format('M d, Y')); ?></td>
    <td>
        <div style="display:flex;justify-content:flex-end;gap:6px">
            <button data-edit-btn
                    onclick="openEditModal(<?php echo e($user->id); ?>, '<?php echo e(addslashes($user->name)); ?>', '<?php echo e($user->email); ?>', '<?php echo e($user->role); ?>')"
                    class="btn btn-secondary btn-sm btn-icon" title="Edit">
                <i class="fas fa-pen"></i>
            </button>
            <button data-verify-btn onclick="toggleVerify(<?php echo e($user->id); ?>, false)"
                    class="btn btn-secondary btn-sm btn-icon" title="Verify">
                <i class="fas fa-user-check"></i>
            </button>
            <button onclick="deleteUser(<?php echo e($user->id); ?>, '<?php echo e(addslashes($user->name)); ?>')"
                    class="btn btn-danger btn-sm btn-icon" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
<?php /**PATH D:\laragon\www\anakco_bms\resources\views\users\_row.blade.php ENDPATH**/ ?>