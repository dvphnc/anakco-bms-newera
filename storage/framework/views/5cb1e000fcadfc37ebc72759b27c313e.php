<?php $__env->startSection('title', 'Residents'); ?>
<?php $__env->startSection('page-title', 'Residents'); ?>
<?php $__env->startSection('page-subtitle', 'Manage all registered residents of Barangay New Era'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Residents</h1>
            <p class="page-subtitle">All registered residents of Barangay New Era</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('residents')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a href="<?php echo e(route('export.pdf', 'residents')); ?>" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a href="<?php echo e(route('export.excel', 'residents')); ?>" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="<?php echo e(route('residents.create')); ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Register Resident
        </a>
    </div>
</div>


<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number" id="statResTotal"><?php echo e(number_format(\App\Models\Resident::count())); ?></div>
            <div class="stat-label">Total Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::where('residency_status','Active')->count())); ?></div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-check-to-slot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::where('residency_status','Active')->where('is_voter',true)->count())); ?></div>
            <div class="stat-label">Registered Voters</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-person-cane"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number"><?php echo e(number_format(\App\Models\Resident::where('residency_status','Active')->where('is_senior',true)->count())); ?></div>
            <div class="stat-label">Senior Citizens</div>
        </div>
    </div>
</div>


<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('residents')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" id="filterToggleBtn" onclick="event.stopPropagation();toggleFilters('residents')">
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
            <span id="filterToggleText">Show Filters</span>
        </button>
    </div>
    <div id="filterPanel" style="display:none">
        <div class="card-body" style="padding:20px 22px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">

                
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                               placeholder="Name, address, contact number...">
                    </div>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Purok / Zone</label>
                    <select id="purokFilter">
                        <option value=""></option>
                        <?php $__currentLoopData = $puroks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select id="genderFilter">
                        <option value=""></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Residency Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        <option value="Active">Active</option>
                        <option value="Deceased">Deceased</option>
                        <option value="Transferred">Transferred</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Civil Status</label>
                    <select id="civilStatusFilter">
                        <option value=""></option>
                        <?php $__currentLoopData = ['Single','Married','Widowed','Separated','Annulled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cs); ?>"><?php echo e($cs); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Classifications</label>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
                    <select id="tagsFilter">
                        <option value=""></option>
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
=======
                    <select id="tagsFilter" multiple>
>>>>>>> Stashed changes
                        <option value="voter">Registered Voter</option>
                        <option value="senior">Senior Citizen (60+)</option>
                        <option value="pwd">Person with Disability (PWD)</option>
                        <option value="solo_parent">Solo Parent</option>
                        <option value="4ps">4Ps Beneficiary</option>
                    </select>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Age Range</label>
                    <div style="display:flex;align-items:center;gap:6px">
                        <input type="number" id="ageMin" class="form-control" placeholder="Min"
                               min="0" max="120" style="width:80px">
                        <span style="color:var(--text-subtle);font-size:11px;flex-shrink:0">–</span>
                        <input type="number" id="ageMax" class="form-control" placeholder="Max"
                               min="0" max="120" style="width:80px">
                    </div>
                </div>

            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <button type="button" id="resetBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-xmark"></i> Reset All Filters
                </button>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <span class="card-title">
            <i class="fas fa-users"></i> Resident List
        </span>
    </div>
    <div class="table-responsive">
        <table id="residentsTable" class="w-full" style="width:100%">
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Purok</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Classifications</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<style>
#residentsTable_wrapper .dataTables_length,
#residentsTable_wrapper .dataTables_filter { display: none; }
#residentsTable_wrapper .dataTables_info { font-size:13px; color:var(--text-muted); padding: 12px 20px; }
#residentsTable_wrapper .dataTables_paginate { padding: 12px 20px; }
#residentsTable_wrapper .dataTables_paginate .paginate_button {
    padding: 4px 10px; border-radius: 6px; font-size: 13px; cursor: pointer;
    border: 1px solid var(--border) !important; background: white !important;
    color: var(--text) !important; margin: 0 2px;
}
#residentsTable_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--navy) !important; color: white !important; border-color: var(--navy) !important;
}
#residentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
    background: var(--navy-pale) !important; color: var(--navy) !important;
}
#residentsTable_wrapper .dataTables_processing {
    background: white; border: 1px solid var(--border); border-radius: 8px;
    padding: 12px 20px; font-size: 13px; color: var(--navy);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
/* Filter panel transition */
#filterPanel { transition: none; }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream

/* Audit B — Condensed row density */
#residentsTable td,
#residentsTable th { padding: 7px 11px !important; font-size: 13px; }
#residentsTable td .res-avatar {
    width: 26px !important; height: 26px !important; font-size: 10px !important;
}
#residentsTable td .res-phone { display: none; }
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
</style>

<script>
$(document).ready(function () {

    /* ── Select2 init ─────────────────────────────────────────────────── */
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    /* Temporarily expose the hidden filter panel so Select2 measures real dimensions.
       The browser won't paint until after this synchronous block, so no visual flash. */
    var $fp = $('#filterPanel');
    var _fpW = $fp.parent().width();
    $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });

=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    const s2Base = {
        dropdownParent: $('body'),
        allowClear: true,
        width: '100%',
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        minimumResultsForSearch: 0,
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        language: {
            noResults: function () {
                return 'No matches — try a different term';
            },
            searching: function () { return 'Searching…'; }
        }
    };

    $('#purokFilter').select2($.extend({}, s2Base, { placeholder: 'All Puroks' }));
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    $('#genderFilter').select2($.extend({}, s2Base, { placeholder: 'All Genders' }));
    $('#statusFilter').select2($.extend({}, s2Base, { placeholder: 'All Statuses' }));
    $('#civilStatusFilter').select2($.extend({}, s2Base, { placeholder: 'Any Civil Status' }));
    $('#tagsFilter').select2($.extend({}, s2Base, { placeholder: 'Filter by classification…' }));

    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    $('#genderFilter').select2($.extend({}, s2Base, { placeholder: 'All Genders', minimumResultsForSearch: -1 }));
    $('#statusFilter').select2($.extend({}, s2Base, { placeholder: 'All Statuses', minimumResultsForSearch: -1 }));
    $('#civilStatusFilter').select2($.extend({}, s2Base, { placeholder: 'Any Civil Status', minimumResultsForSearch: -1 }));
    $('#tagsFilter').select2($.extend({}, s2Base, {
        placeholder: 'Filter by classification…',
        minimumResultsForSearch: -1,
        closeOnSelect: false,
        allowClear: false
    }));
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

    /* ── DataTable ────────────────────────────────────────────────────── */
    var table = $('#residentsTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '<?php echo e(route('residents.index')); ?>',
            data: function (d) {
                d.gender       = $('#genderFilter').val();
                d.status       = $('#statusFilter').val();
                d.purok_id     = $('#purokFilter').val();
                d.civil_status = $('#civilStatusFilter').val();
                d.tags         = $('#tagsFilter').val();
                d.age_min      = $('#ageMin').val();
                d.age_max      = $('#ageMax').val();
                d.search       = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'name_col',   name: 'first_name',       orderable: true },
            { data: 'purok_col',  name: 'purok_id',         orderable: false },
            { data: 'gender_col', name: 'gender',           orderable: true },
            { data: 'age_col',    name: 'birthdate',        orderable: true },
            { data: 'civil_col',  name: 'civil_status',     orderable: false },
            { data: 'tags_col',   name: 'is_voter',         orderable: false },
            { data: 'status_col', name: 'residency_status', orderable: true },
            { data: 'actions',    name: 'actions',          orderable: false, searchable: false },
        ],
        order: [[0, 'asc']],
        pageLength: 15,
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading residents…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-users"></i><p>No residents found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No residents match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── URL persistence helpers ──────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s','purok_id','gender','status','civil_status','age_min','age_max'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('tags');

        const s = $('#searchInput').val();
        if (s)                              url.searchParams.set('s', s);
        if ($('#purokFilter').val())        url.searchParams.set('purok_id', $('#purokFilter').val());
        if ($('#genderFilter').val())       url.searchParams.set('gender', $('#genderFilter').val());
        if ($('#statusFilter').val())       url.searchParams.set('status', $('#statusFilter').val());
        if ($('#civilStatusFilter').val())  url.searchParams.set('civil_status', $('#civilStatusFilter').val());
        if ($('#ageMin').val())             url.searchParams.set('age_min', $('#ageMin').val());
        if ($('#ageMax').val())             url.searchParams.set('age_max', $('#ageMax').val());
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        if ($('#tagsFilter').val()) url.searchParams.set('tags', $('#tagsFilter').val());
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes
=======
        ($('#tagsFilter').val() || []).forEach(t => url.searchParams.append('tags', t));
>>>>>>> Stashed changes

        history.replaceState({}, '', url);
        updateBadge();
    }

    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))            { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('purok_id'))     { $('#purokFilter').val(p.get('purok_id')).trigger('change.select2'); any = true; }
        if (p.get('gender'))       { $('#genderFilter').val(p.get('gender')).trigger('change.select2'); any = true; }
        if (p.get('status'))       { $('#statusFilter').val(p.get('status')).trigger('change.select2'); any = true; }
        if (p.get('civil_status')) { $('#civilStatusFilter').val(p.get('civil_status')).trigger('change.select2'); any = true; }
        if (p.get('age_min'))      { $('#ageMin').val(p.get('age_min')); any = true; }
        if (p.get('age_max'))      { $('#ageMax').val(p.get('age_max')); any = true; }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        const tag = p.get('tags');
        if (tag) { $('#tagsFilter').val(tag).trigger('change.select2'); any = true; }
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
=======
        const tags = p.getAll('tags');
        if (tags.length)           { $('#tagsFilter').val(tags).trigger('change.select2'); any = true; }
>>>>>>> Stashed changes
        return any;
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())                    n++;
        if ($('#purokFilter').val())                    n++;
        if ($('#genderFilter').val())                   n++;
        if ($('#statusFilter').val())                   n++;
        if ($('#civilStatusFilter').val())              n++;
        if ($('#ageMin').val() || $('#ageMax').val())   n++;
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        if ($('#tagsFilter').val())                      n++;
        const badge = document.getElementById('filterBadge');
        const chip  = document.getElementById('headerFilterChip');
        const chipN = document.getElementById('headerFilterCount');
        if (n > 0) {
            badge.textContent = n + (n === 1 ? ' filter active' : ' filters active');
            badge.style.display = '';
            chipN.textContent = n;
            chip.style.display = '';
        } else {
            badge.style.display = 'none';
            chip.style.display  = 'none';
        }
    }

    /* ── Filter panel toggle — persists to localStorage ─────────────── */
    window.toggleFilters = function (key) {
        const panel  = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        localStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };

    // Restore panel state: open if URL filters present, localStorage says open, or any filter active
    const hasUrlFilters = loadFromUrl();
    const lsOpen = localStorage.getItem('fp_residents') === '1';
    if (hasUrlFilters || lsOpen) {
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        if (($('#tagsFilter').val() || []).length)      n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; }
        else       { badge.style.display = 'none'; }
    }

    /* ── Filter panel toggle (global so card-header click works) ─────── */
    window.toggleFilters = function (key) {
        const panel = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        sessionStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };

    // Restore panel state on load
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || sessionStorage.getItem('fp_residents') === '1') {
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    // Auto-open panel if filters are active (even if localStorage says closed)
    setTimeout(() => {
        const n = parseInt(document.getElementById('filterBadge').textContent) || 0;
        if (n > 0 && document.getElementById('filterPanel').style.display === 'none') {
            document.getElementById('filterPanel').style.display = 'block';
            document.getElementById('filterToggleText').textContent = 'Hide Filters';
        }
    }, 50);
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

    /* ── Event listeners ─────────────────────────────────────────────── */
    let debounce;

    $('#searchInput').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380);
    });

    $('#genderFilter, #statusFilter, #purokFilter, #civilStatusFilter, #tagsFilter').on('change', function () {
        saveToUrl(); table.ajax.reload();
    });

    $('#ageMin, #ageMax').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 600);
    });

    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#genderFilter, #statusFilter, #purokFilter, #civilStatusFilter').val(null).trigger('change');
        $('#tagsFilter').val(null).trigger('change');
        $('#ageMin, #ageMax').val('');
        saveToUrl(); table.ajax.reload();
    });
});

/* ── Resident Quick View Panel ──────────────────────────────────────── */
window.residentQuickView = function (id, url) {
    const panel = document.getElementById('qvPanel');
    const body  = document.getElementById('qvBody');

    // Show loading state
    panel.style.display = 'flex';
    requestAnimationFrame(() => { panel.style.opacity = '1'; });
    body.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                    height:200px;gap:12px">
            <i class="fas fa-spinner fa-spin" style="font-size:24px;color:var(--gold)"></i>
            <span style="font-size:13px;color:var(--text-muted)">Loading resident…</span>
        </div>`;

    axios.get(url)
        .then(({ data: r }) => {
            const badgeMap = { Active:'badge-green', Deceased:'badge-gray', Transferred:'badge-blue' };
            const tags = [
                r.is_voter       ? '<span class="badge badge-navy">Voter</span>'       : '',
                r.is_senior      ? '<span class="badge badge-gold">Senior</span>'      : '',
                r.is_pwd         ? '<span class="badge badge-blue">PWD</span>'         : '',
                r.is_solo_parent ? '<span class="badge badge-yellow">Solo Parent</span>': '',
                r.is_4ps         ? '<span class="badge badge-orange">4Ps</span>'        : '',
            ].filter(Boolean).join(' ');

            body.innerHTML = `
                
                <div style="display:flex;align-items:center;gap:14px;padding:20px 20px 16px;
                            border-bottom:1px solid var(--border)">
                    <div style="width:56px;height:56px;border-radius:50%;flex-shrink:0;overflow:hidden;
                                border:2px solid var(--border2)">
                        ${r.photo_url
                            ? `<img src="${r.photo_url}" style="width:100%;height:100%;object-fit:cover">`
                            : `<div style="width:100%;height:100%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));
                                          display:flex;align-items:center;justify-content:center;
                                          font-size:20px;font-weight:800;color:#fff">${r.initials}</div>`
                        }
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:17px;font-weight:700;color:var(--text);line-height:1.2">${r.full_name}</div>
                        <div style="font-size:13px;color:var(--text-muted);margin-top:3px">
                            ${r.age} yrs · ${r.gender} · ${r.civil_status}
                        </div>
                        <div style="margin-top:7px;display:flex;flex-wrap:wrap;gap:4px">
                            <span class="badge ${badgeMap[r.residency_status] || 'badge-gray'}">${r.residency_status}</span>
                            ${tags}
                        </div>
                    </div>
                </div>

                
                <div style="padding:16px 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px 18px">
                    ${qvField('fa-location-dot','Purok',r.purok)}
                    ${qvField('fa-house','Household',r.household !== '—' ? 'HH-' + r.household : '—')}
                    ${qvField('fa-phone','Contact',r.contact_number)}
                    ${qvField('fa-envelope','Email',r.email_address)}
                    ${qvField('fa-briefcase','Occupation',r.occupation)}
                    ${qvField('fa-cake-candles','Birthday',r.birthdate)}
                    <div style="grid-column:1/-1">${qvField('fa-map-pin','Address',r.address)}</div>
                </div>

                
                <div style="padding:14px 20px;border-top:1px solid var(--border);
                            display:flex;gap:8px;background:var(--surface2)">
                    <a href="${r.profile_url}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                        <i class="fas fa-eye"></i> Full Profile
                    </a>
                    <a href="${r.clearance_url}" class="btn btn-gold btn-sm" style="flex:1;justify-content:center">
                        <i class="fas fa-file-circle-check"></i> Clearance
                    </a>
                    <a href="${r.edit_url}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>`;
        })
        .catch(() => {
            body.innerHTML = `<div style="padding:32px;text-align:center;color:var(--crimson)">
                <i class="fas fa-exclamation-circle" style="font-size:28px;opacity:.5;display:block;margin-bottom:10px"></i>
                Could not load resident data.
            </div>`;
        });
};

function qvField(icon, label, value) {
    return `<div>
        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;
                    color:var(--text-subtle);display:flex;align-items:center;gap:5px;margin-bottom:3px">
            <i class="fas ${icon}" style="font-size:10px"></i> ${label}
        </div>
        <div style="font-size:14px;font-weight:500;color:var(--text)">${value || '—'}</div>
    </div>`;
}

window.closeQvPanel = function () {
    const panel = document.getElementById('qvPanel');
    panel.style.opacity = '0';
    setTimeout(() => { panel.style.display = 'none'; }, 200);
};

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeQvPanel();
});
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream

/* ── Axios DELETE — DataTable row removal ──────────────────────────────── */
$(document).on('click', '#residentsTable form[data-confirm] button[type="submit"]', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    const btn  = $(this);
    const form = btn.closest('form');
    const url  = form.attr('action');

    bmsConfirm({
        title:   form.data('confirm-title') || 'Delete Resident',
        message: form.data('confirm'),
        ok:      form.data('confirm-ok')    || 'Delete',
    }, function () {
        const icon = btn.find('i');
        const orig = icon.attr('class');
        icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
        btn.prop('disabled', true);

        axios.delete(url, { headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' } })
            .then(res => {
                const dt = $('#residentsTable').DataTable();
                dt.row(form.closest('tr')).remove().draw(false);
                bmsStatDecrement('statResTotal');
                bmsToast(res.data.message || 'Resident deleted.', 'success');
            })
            .catch(() => {
                icon.attr('class', orig).css('color', '');
                btn.prop('disabled', false);
                bmsToast('Could not delete resident. Please try again.', 'error');
            });
    });
});

/* ── Axios PATCH — Residency status inline toggle ──────────────────────── */
$(document).on('click', '#residentsTable .res-status-toggle', function () {
    const btn    = $(this);
    const id     = btn.data('id');
    const cur    = btn.data('status');

    btn.html('<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i>').prop('disabled', true);

    axios.patch(`/residents/${id}/toggle-status`, { _token: '<?php echo e(csrf_token()); ?>' })
        .then(({ data }) => {
            const s = data.residency_status;
            const clsMap = { Active: 'badge-green', Transferred: 'badge-yellow', Deceased: 'badge-gray' };
            btn.removeClass('badge-green badge-yellow badge-gray')
               .addClass(clsMap[s] || 'badge-gray')
               .text(s)
               .data('status', s)
               .prop('disabled', false);
        })
        .catch(() => {
            btn.text(cur).prop('disabled', false);
            alert('Could not update status. Please try again.');
        });
});
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
</script>


<div id="qvPanel"
     style="display:none;opacity:0;position:fixed;inset:0;z-index:9500;
            align-items:flex-start;justify-content:flex-end;
            background:rgba(9,20,40,0.45);backdrop-filter:blur(3px);
            transition:opacity .2s"
     onclick="if(event.target===this) closeQvPanel()">
    <div style="width:380px;max-width:95vw;height:100vh;background:var(--surface);
                overflow-y:auto;box-shadow:-8px 0 40px rgba(0,0,0,0.22);
                display:flex;flex-direction:column;animation:qvSlideIn .2s ease">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid var(--border);
                    background:var(--navy);flex-shrink:0">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-id-card" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Resident Quick View</span>
            </div>
            <button onclick="closeQvPanel()"
                    style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;
                           display:flex;align-items:center;justify-content:center;
                           color:rgba(255,255,255,0.7);cursor:pointer;font-size:13px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="qvBody" style="flex:1"></div>
    </div>
</div>
<style>
@keyframes qvSlideIn { from { transform:translateX(32px); opacity:0; } to { transform:translateX(0); opacity:1; } }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/residents/residents-index.blade.php ENDPATH**/ ?>