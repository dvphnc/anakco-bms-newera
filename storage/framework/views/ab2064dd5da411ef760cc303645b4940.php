<?php $__env->startSection('title', 'User Management'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">System accounts and access control</p>
    </div>
    <div class="page-actions">
        <button onclick="document.getElementById('addUserModal').style.display='flex'" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add User
        </button>
    </div>
</div>


<div class="grid-3 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" id="stat-total"><?php echo e(number_format($users->total())); ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:var(--crimson)">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" id="stat-admins"><?php echo e(number_format(\App\Models\User::where('role','Admin')->count())); ?></div>
            <div class="stat-label">Admins</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" id="stat-verified"><?php echo e(number_format(\App\Models\User::whereNotNull('email_verified_at')->count())); ?></div>
            <div class="stat-label">Verified</div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-shield"></i> System Users</span>
        <span id="stat-header-count" style="font-size:13px;color:var(--text-muted)"><?php echo e(number_format($users->total())); ?> users</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Created</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr data-id="<?php echo e($user->id); ?>">
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px"><?php echo e($user->name); ?></div>
                                <?php if($user->id === auth()->id()): ?>
                                    <span style="font-size:13px;color:var(--gold);font-weight:600">You</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="td-muted"><?php echo e($user->email); ?></td>
                    <td>
                        <?php
                            $roleCls = match($user->role) {
                                'Admin'     => 'badge-red',
                                'Secretary' => 'badge-yellow',
                                'Committee' => 'badge-navy',
                                default     => 'badge-gray'
                            };
                        ?>
                        <span class="badge <?php echo e($roleCls); ?>"><?php echo e($user->role ?? '—'); ?></span>
                    </td>
                    <td>
                        <?php if($user->email_verified_at): ?>
                            <span class="badge badge-green"><i class="fas fa-check" style="margin-right:3px"></i> Verified</span>
                        <?php else: ?>
                            <span class="badge badge-gray">Unverified</span>
                        <?php endif; ?>
                    </td>
                    <td class="td-muted"><?php echo e($user->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <button data-edit-btn
                                    onclick="openEditModal(<?php echo e($user->id); ?>, '<?php echo e(addslashes($user->name)); ?>', '<?php echo e($user->email); ?>', '<?php echo e($user->role); ?>')"
                                    class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </button>
                            <?php if($user->id !== auth()->id()): ?>
                            <button data-verify-btn
                                    onclick="toggleVerify(<?php echo e($user->id); ?>, <?php echo e($user->email_verified_at ? 'true' : 'false'); ?>)"
                                    class="btn btn-secondary btn-sm btn-icon"
                                    title="<?php echo e($user->email_verified_at ? 'Unverify' : 'Verify'); ?>">
                                <i class="fas <?php echo e($user->email_verified_at ? 'fa-user-xmark' : 'fa-user-check'); ?>"></i>
                            </button>
                            <button onclick="deleteUser(<?php echo e($user->id); ?>, '<?php echo e(addslashes($user->name)); ?>')"
                                    class="btn btn-danger btn-sm btn-icon" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No users found.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($users->hasPages()): ?>
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:13px;color:var(--text-muted)">
            Showing <?php echo e($users->firstItem()); ?> to <?php echo e($users->lastItem()); ?> of <?php echo e(number_format($users->total())); ?>

        </span>
        <?php echo e($users->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>


<div id="addUserModal" style="display:none;position:fixed;inset:0;background:rgba(13,33,68,0.5);z-index:1000;align-items:center;justify-content:center"
     onclick="if(event.target===this)closeModal('addUserModal')">
    <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.18);margin:16px">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:15px;font-weight:700;color:var(--navy)">
                <i class="fas fa-user-plus" style="margin-right:8px;color:var(--gold)"></i>Add User
            </span>
            <button onclick="closeModal('addUserModal')" style="background:none;border:none;font-size:18px;color:var(--text-muted);cursor:pointer;padding:4px;line-height:1">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:24px">
            <form id="addUserForm" action="<?php echo e(route('users.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div id="addUserError" style="display:none;background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:var(--radius-sm);font-size:13px;margin-bottom:16px"></div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="Full name" required>
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Email Address <span style="color:var(--crimson)">*</span></label>
                    <input type="email" name="email" id="addEmail" class="form-control" placeholder="email@example.com" required>
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Role <span style="color:var(--crimson)">*</span></label>
                    <select name="role" id="addRole" class="form-control" required>
                        <option value="">Select role…</option>
                        <option value="Admin">Admin</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Committee">Committee</option>
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px">
                    <div class="form-group">
                        <label class="form-label">Password <span style="color:var(--crimson)">*</span></label>
                        <input type="password" name="password" id="addPassword" class="form-control" placeholder="Min. 8 chars" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password <span style="color:var(--crimson)">*</span></label>
                        <input type="password" name="password_confirmation" id="addPasswordConfirm" class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" onclick="closeModal('addUserModal')" class="btn btn-secondary">Cancel</button>
                    <button type="button" id="addUserBtn" class="btn btn-primary" onclick="submitAddUser()">
                        <i class="fas fa-user-plus"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="editUserModal" style="display:none;position:fixed;inset:0;background:rgba(13,33,68,0.5);z-index:1000;align-items:center;justify-content:center"
     onclick="if(event.target===this)closeModal('editUserModal')">
    <div style="background:#fff;border-radius:var(--radius-lg);width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,0.18);margin:16px">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
            <span style="font-size:15px;font-weight:700;color:var(--navy)">
                <i class="fas fa-user-pen" style="margin-right:8px;color:var(--gold)"></i>Edit User
            </span>
            <button onclick="closeModal('editUserModal')" style="background:none;border:none;font-size:18px;color:var(--text-muted);cursor:pointer;padding:4px;line-height:1">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:24px">
            <form id="editUserForm" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="editUserId">
                <div id="editUserError" style="display:none;background:#fee2e2;color:#991b1b;padding:10px 14px;border-radius:var(--radius-sm);font-size:13px;margin-bottom:16px"></div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                    <input type="text" name="name" id="editUserName" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Email Address <span style="color:var(--crimson)">*</span></label>
                    <input type="email" name="email" id="editUserEmail" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:16px">
                    <label class="form-label">Role <span style="color:var(--crimson)">*</span></label>
                    <select name="role" id="editRole" class="form-control" required>
                        <option value="Admin">Admin</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Committee">Committee</option>
                    </select>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px">
                    <div class="form-group">
                        <label class="form-label">New Password
                            <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional)</span>
                        </label>
                        <input type="password" name="password" id="editUserPassword" class="form-control" placeholder="Leave blank to keep">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="editUserPasswordConfirm" class="form-control" placeholder="Repeat new password">
                    </div>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end">
                    <button type="button" onclick="closeModal('editUserModal')" class="btn btn-secondary">Cancel</button>
                    <button type="button" id="editUserBtn" class="btn btn-primary" onclick="submitEditUser()">
                        <i class="fas fa-floppy-disk"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Helpers ───────────────────────────────────────────────────────────────────
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeModal('addUserModal'); closeModal('editUserModal'); }
});

function _firstError(err) {
    if (err.response && err.response.data) {
        if (err.response.data.errors) return Object.values(err.response.data.errors)[0][0];
        if (err.response.data.message) return err.response.data.message;
    }
    return 'An unexpected error occurred.';
}

function updateStats(data) {
    if (data.total !== undefined) {
        var t = document.getElementById('stat-total');
        if (t) t.textContent = data.total.toLocaleString();
        var h = document.getElementById('stat-header-count');
        if (h) h.textContent = data.total.toLocaleString() + ' users';
    }
    if (data.admins !== undefined) {
        var a = document.getElementById('stat-admins');
        if (a) a.textContent = data.admins.toLocaleString();
    }
    if (data.verified !== undefined) {
        var v = document.getElementById('stat-verified');
        if (v) v.textContent = data.verified.toLocaleString();
    }
}

// ── Add User ──────────────────────────────────────────────────────────────────
function submitAddUser() {
    var form = document.getElementById('addUserForm');
    var btn  = document.getElementById('addUserBtn');
    var errEl = document.getElementById('addUserError');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…';
    errEl.style.display = 'none';

    axios.post(form.action, new FormData(form))
        .then(function(res) {
            var tbody = document.querySelector('table tbody');
            tbody.insertAdjacentHTML('afterbegin', res.data.row_html);
            updateStats(res.data);
            closeModal('addUserModal');
            form.reset();
            $('#addRole').val('').trigger('change.select2');
            bmsToast(res.data.message, 'success');
        })
        .catch(function(err) {
            errEl.textContent = _firstError(err);
            errEl.style.display = 'block';
        })
        .finally(function() { btn.disabled = false; btn.innerHTML = orig; });
}

// ── Edit User ─────────────────────────────────────────────────────────────────
function openEditModal(id, name, email, role) {
    document.getElementById('editUserId').value              = id;
    document.getElementById('editUserName').value            = name;
    document.getElementById('editUserEmail').value           = email;
    document.getElementById('editUserPassword').value        = '';
    document.getElementById('editUserPasswordConfirm').value = '';
    document.getElementById('editUserError').style.display  = 'none';
    $('#editRole').val(role).trigger('change.select2');
    document.getElementById('editUserModal').style.display  = 'flex';
}

function submitEditUser() {
    var id   = document.getElementById('editUserId').value;
    var btn  = document.getElementById('editUserBtn');
    var errEl = document.getElementById('editUserError');
    var orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…';
    errEl.style.display = 'none';

    var fd = new FormData(document.getElementById('editUserForm'));
    fd.append('_method', 'PATCH');

    axios.post('/users/' + id, fd)
        .then(function(res) {
            var row = document.querySelector('tr[data-id="' + id + '"]');
            if (row) {
                var u = res.data.user;
                var nameDiv = row.cells[0].querySelector('div > div > div');
                if (nameDiv) nameDiv.textContent = u.name;
                row.cells[1].textContent = u.email;
                var roleMap = { Admin: 'badge-red', Secretary: 'badge-yellow', Committee: 'badge-navy' };
                row.cells[2].innerHTML = '<span class="badge ' + (roleMap[u.role] || 'badge-gray') + '">' + u.role + '</span>';
                var editBtn = row.querySelector('[data-edit-btn]');
                if (editBtn) {
                    editBtn.setAttribute('onclick', "openEditModal(" + u.id + ",'" + u.name.replace(/'/g,"\\'") + "','" + u.email + "','" + u.role + "')");
                }
            }
            updateStats(res.data);
            closeModal('editUserModal');
            bmsToast(res.data.message, 'success');
        })
        .catch(function(err) {
            errEl.textContent = _firstError(err);
            errEl.style.display = 'block';
        })
        .finally(function() { btn.disabled = false; btn.innerHTML = orig; });
}

// ── Delete User ───────────────────────────────────────────────────────────────
function deleteUser(id, name) {
    var rowEl = document.querySelector('tr[data-id="' + id + '"]');
    bmsConfirm({
        title:   'Delete User',
        message: 'Delete ' + name + '? This will permanently remove their account.',
        okText:  'Delete Permanently',
        okClass: 'btn-danger'
    }, function() {
        axios.delete('/users/' + id)
            .then(function(res) {
                if (rowEl) rowEl.remove();
                updateStats(res.data);
                bmsToast(res.data.message, 'success');
            })
            .catch(function(err) {
                bmsToast(_firstError(err), 'error');
            });
    });
}

// ── Verify Toggle ─────────────────────────────────────────────────────────────
function toggleVerify(id, isVerified) {
    axios.patch('/users/' + id + '/verify-toggle')
        .then(function(res) {
            var row = document.querySelector('tr[data-id="' + id + '"]');
            if (row) {
                row.cells[3].innerHTML = res.data.verified
                    ? '<span class="badge badge-green"><i class="fas fa-check" style="margin-right:3px"></i> Verified</span>'
                    : '<span class="badge badge-gray">Unverified</span>';
                var btn = row.querySelector('[data-verify-btn]');
                if (btn) {
                    btn.title = res.data.verified ? 'Unverify' : 'Verify';
                    btn.querySelector('i').className = 'fas ' + (res.data.verified ? 'fa-user-xmark' : 'fa-user-check');
                    btn.setAttribute('onclick', 'toggleVerify(' + id + ',' + res.data.verified + ')');
                }
            }
            var verEl = document.getElementById('stat-verified');
            if (verEl && res.data.verifiedCount !== undefined) verEl.textContent = res.data.verifiedCount.toLocaleString();
            bmsToast(res.data.message, 'success');
        })
        .catch(function(err) {
            bmsToast(_firstError(err), 'error');
        });
}

// ── Select2 ───────────────────────────────────────────────────────────────────
$(function(){
    $('#addRole, #editRole').select2({
        minimumResultsForSearch: -1,
        width: '100%',
        dropdownParent: $('body')
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/users/users-index.blade.php ENDPATH**/ ?>