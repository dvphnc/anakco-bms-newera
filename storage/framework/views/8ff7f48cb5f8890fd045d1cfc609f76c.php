<?php $__env->startSection('title', 'Officials & Staff'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div>
        <h1 class="page-title">Officials & Staff</h1>
        <p class="page-subtitle">Barangay New Era elected officials and personnel</p>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('officials.create')); ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Official
        </a>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($officials->count())); ?></div>
            <div class="stat-label">Total Officials</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($officials->where('is_active', true)->count())); ?></div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format($officials->whereIn('position', ['Punong Barangay','Barangay Captain'])->count())); ?></div>
            <div class="stat-label">Punong Barangay</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(date('Y')); ?></div>
            <div class="stat-label">Current Term Year</div>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <div class="filter-bar">
            <div class="form-group flex-1">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none"></i>
                    <input type="text" id="officialSearch" class="form-control" style="padding-left:32px"
                           placeholder="Name, position, committee...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="officialStatusFilter">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <div style="display:flex;gap:6px">
                    <button id="officialResetBtn" class="btn btn-secondary">
                        <i class="fas fa-xmark"></i> Reset
                    </button>
                    <div style="display:flex;border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden">
                        <button id="viewList" onclick="setOfficialView('list')"
                                class="btn btn-sm"
                                style="border-radius:0;border:none;background:var(--navy);color:#fff;
                                       padding:6px 12px;font-size:12px"
                                title="List view">
                            <i class="fas fa-list"></i>
                        </button>
                        <button id="viewGrouped" onclick="setOfficialView('grouped')"
                                class="btn btn-sm"
                                style="border-radius:0;border:none;background:var(--surface2);color:var(--text-muted);
                                       padding:6px 12px;font-size:12px"
                                title="By Position">
                            <i class="fas fa-layer-group"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="groupedView" style="display:none">
<?php
    $grouped = $officials->groupBy('position')->sortByDesc(fn($g) => in_array($g->first()->position, ['Punong Barangay','Barangay Captain']) ? 999 : 0);
?>
<?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $position => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $isPunong = in_array($position, ['Punong Barangay','Barangay Captain']); ?>
    <div class="card mb-4" style="<?php echo e($isPunong ? 'border-left:4px solid var(--gold)' : ''); ?>">
        <div class="card-header" style="<?php echo e($isPunong ? 'background:linear-gradient(90deg,var(--navy),var(--navy-mid))' : ''); ?>">
            <span class="card-title" style="<?php echo e($isPunong ? 'color:#fff' : ''); ?>">
                <i class="fas <?php echo e($isPunong ? 'fa-star' : 'fa-user-tie'); ?>"
                   style="color:<?php echo e($isPunong ? 'var(--gold-light)' : 'var(--navy)'); ?>"></i>
                <?php echo e($position); ?>

            </span>
            <span style="font-size:12px;font-weight:600;
                         color:<?php echo e($isPunong ? 'rgba(255,255,255,0.55)' : 'var(--text-muted)'); ?>">
                <?php echo e($group->count()); ?> <?php echo e(Str::plural('member', $group->count())); ?>

            </span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1px;background:var(--border)">
            <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="grouped-official-row" data-active="<?php echo e($off->is_active ? '1' : '0'); ?>"
                 style="background:var(--surface);padding:14px 18px;display:flex;align-items:center;gap:12px">
                <div style="width:38px;height:38px;border-radius:50%;overflow:hidden;flex-shrink:0;
                            background:linear-gradient(135deg,var(--navy),var(--navy-mid));
                            display:flex;align-items:center;justify-content:center;
                            font-weight:700;color:#fff;font-size:13px">
                    <?php if($off->photo_path): ?>
                        <img src="<?php echo e(asset('storage/'.$off->photo_path)); ?>" style="width:100%;height:100%;object-fit:cover">
                    <?php else: ?>
                        <?php echo e(strtoupper(substr($off->full_name ?? 'O', 0, 1))); ?>

                    <?php endif; ?>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;color:var(--text)"><?php echo e($off->full_name); ?></div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:1px">
                        <?php echo e($off->committee ?? 'No committee'); ?>

                        <?php if($off->term_start): ?> · <?php echo e(\Carbon\Carbon::parse($off->term_start)->format('Y')); ?>–<?php echo e($off->term_end ? \Carbon\Carbon::parse($off->term_end)->format('Y') : 'present'); ?> <?php endif; ?>
                    </div>
                </div>
                <span class="badge <?php echo e($off->is_active ? 'badge-green' : 'badge-gray'); ?>" style="flex-shrink:0">
                    <?php echo e($off->is_active ? 'Active' : 'Inactive'); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div id="listView" class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Officials List</span>
        <span style="font-size:13px;color:var(--text-muted)"><?php echo e($officials->count()); ?> officials</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Committee</th>
                    <th>Contact</th>
                    <th>Term</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $officials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $official): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="width:44px">
                        <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                            <?php if($official->photo_path): ?>
                                <img src="<?php echo e(asset('storage/'.$official->photo_path)); ?>" alt="" style="width:100%;height:100%;object-fit:cover">
                            <?php else: ?>
                                <?php echo e(strtoupper(substr($official->full_name ?? 'O', 0, 1))); ?>

                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px"><?php echo e($official->full_name); ?></div>
                    </td>
                    <td>
                        <span class="badge badge-navy"><?php echo e($official->position); ?></span>
                    </td>
                    <td class="td-muted"><?php echo e($official->committee ?? '—'); ?></td>
                    <td class="td-muted"><?php echo e($official->contact_number ?? '—'); ?></td>
                    <td class="td-muted">
                        <?php echo e($official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—'); ?>

                        <?php if($official->term_end): ?>
                            – <?php echo e(\Carbon\Carbon::parse($official->term_end)->format('Y')); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="badge <?php echo e($official->is_active ? 'badge-green' : 'badge-gray'); ?> status-toggle"
                                data-id="<?php echo e($official->id); ?>"
                                data-active="<?php echo e($official->is_active ? '1' : '0'); ?>"
                                style="border:none;cursor:pointer;font-family:inherit"
                                title="Click to toggle status">
                            <?php echo e($official->is_active ? 'Active' : 'Inactive'); ?>

                        </button>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="<?php echo e(route('officials.show', $official)); ?>" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo e(route('officials.edit', $official)); ?>" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('officials.destroy', $official)); ?>"
                                  data-confirm="Delete <?php echo e($official->full_name); ?>? This cannot be undone."
                                  data-confirm-title="Delete Official"
                                  data-confirm-ok="Delete">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-user-tie"></i>
                            <p>No officials found.</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* View toggle */
window.setOfficialView = function(mode) {
    const isList = mode === 'list';
    document.getElementById('listView').style.display    = isList ? '' : 'none';
    document.getElementById('groupedView').style.display = isList ? 'none' : '';
    document.getElementById('viewList').style.background    = isList ? 'var(--navy)' : 'var(--surface2)';
    document.getElementById('viewList').style.color         = isList ? '#fff'        : 'var(--text-muted)';
    document.getElementById('viewGrouped').style.background = isList ? 'var(--surface2)' : 'var(--navy)';
    document.getElementById('viewGrouped').style.color      = isList ? 'var(--text-muted)' : '#fff';
    localStorage.setItem('officials_view', mode);
};

$(document).ready(function () {
    /* Restore last view */
    const savedView = localStorage.getItem('officials_view') || 'list';
    setOfficialView(savedView);

    /* Select2 for status filter */
    $('#officialStatusFilter').select2({
        dropdownParent: $('body'),
        placeholder: 'All',
        allowClear: true,
        width: '160px',
        minimumResultsForSearch: -1
    });

    /* Client-side search + status filter (list + grouped views) */
    function filterTable() {
        const q      = $('#officialSearch').val().toLowerCase();
        const status = $('#officialStatusFilter').val();
        // List view
        $('tbody tr').each(function () {
            const text     = $(this).text().toLowerCase();
            const isActive = $(this).find('.status-toggle').data('active') == 1;
            const matchQ   = !q || text.includes(q);
            const matchS   = !status
                || (status === 'active' && isActive)
                || (status === 'inactive' && !isActive);
            $(this).toggle(matchQ && matchS);
        });
        // Grouped view
        $('.grouped-official-row').each(function () {
            const text     = $(this).text().toLowerCase();
            const isActive = $(this).data('active') == 1;
            const matchQ   = !q || text.includes(q);
            const matchS   = !status
                || (status === 'active' && isActive)
                || (status === 'inactive' && !isActive);
            $(this).toggle(matchQ && matchS);
        });
    }

    let debounce;
    $('#officialSearch').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(filterTable, 250);
    });
    $('#officialStatusFilter').on('change', filterTable);
    $('#officialResetBtn').on('click', function () {
        $('#officialSearch').val('');
        $('#officialStatusFilter').val(null).trigger('change');
        filterTable();
    });

    /* Axios status toggle */
    $(document).on('click', '.status-toggle', function () {
        const btn      = $(this);
        const id       = btn.data('id');
        const isActive = btn.data('active') == 1;

        btn.html('<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i>').prop('disabled', true);

        axios.patch(`/officials/${id}/toggle-status`, {
            _token: '<?php echo e(csrf_token()); ?>'
        })
        .then(({ data }) => {
            const nowActive = data.is_active;
            btn.removeClass('badge-green badge-gray')
               .addClass(nowActive ? 'badge-green' : 'badge-gray')
               .text(nowActive ? 'Active' : 'Inactive')
               .data('active', nowActive ? '1' : '0')
               .prop('disabled', false);
            filterTable();
        })
        .catch(() => {
            btn.text(isActive ? 'Active' : 'Inactive').prop('disabled', false);
            alert('Could not update status. Please try again.');
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/officials/officials-index.blade.php ENDPATH**/ ?>