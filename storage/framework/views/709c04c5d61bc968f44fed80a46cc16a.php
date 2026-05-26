
<?php $__env->startSection('title', 'My Submissions'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ─── Page header ───────────────────────────────────────── */
.sub-hd { margin-bottom: 1.75rem; }
.sub-hd h2 {
    font-size: 1.3rem; font-weight: 800;
    color: var(--navy); margin-bottom: .3rem;
}
.sub-hd p { font-size: .88rem; color: var(--muted); max-width: 540px; }

/* ─── Lookup card ───────────────────────────────────────── */
.lookup-card {
    background: var(--navy);
    border-radius: var(--radius-lg);
    padding: 1.5rem 1.75rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(13,33,68,.22);
}
.lookup-card label {
    display: block;
    font-size: .78rem; font-weight: 600;
    color: rgba(255,255,255,.65);
    text-transform: uppercase; letter-spacing: .06em;
    margin-bottom: .55rem;
}
.lookup-row {
    display: flex; gap: .65rem; align-items: flex-start;
}
.lookup-row .form-control {
    flex: 1;
    min-height: 48px;
    background: rgba(255,255,255,.1);
    border: 1.5px solid rgba(255,255,255,.22);
    color: #fff;
    font-size: .95rem;
}
.lookup-row .form-control::placeholder { color: rgba(255,255,255,.4); }
.lookup-row .form-control:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(200,134,26,.25);
    background: rgba(255,255,255,.13);
}
.lookup-row .btn-search {
    min-height: 48px; padding: .65rem 1.4rem;
    background: var(--gold); color: #fff;
    border: none; border-radius: var(--radius-sm);
    font-family: 'Poppins', sans-serif;
    font-size: .92rem; font-weight: 600;
    cursor: pointer; white-space: nowrap;
    transition: background .2s, box-shadow .2s;
    display: inline-flex; align-items: center; gap: .5rem;
}
.lookup-row .btn-search:hover {
    background: var(--gold-dark);
    box-shadow: 0 4px 16px rgba(200,134,26,.4);
}
.lookup-hint {
    font-size: .73rem; color: rgba(255,255,255,.45);
    margin-top: .55rem;
}

/* ─── Tabs ──────────────────────────────────────────────── */
.sub-tabs {
    display: flex; gap: 0;
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 1.5rem;
}
.sub-tab {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .7rem 1.25rem;
    font-size: .88rem; font-weight: 600;
    color: var(--muted);
    cursor: pointer;
    border-bottom: 2.5px solid transparent;
    margin-bottom: -2px;
    transition: color .2s, border-color .2s;
    user-select: none;
    background: none; border-top: none; border-left: none; border-right: none;
    font-family: 'Poppins', sans-serif;
}
.sub-tab:hover { color: var(--navy); }
.sub-tab.active {
    color: var(--navy);
    border-bottom-color: var(--navy);
}
.tab-badge {
    display: inline-flex; align-items: center; justify-content: center;
    min-width: 20px; height: 20px; padding: 0 5px;
    border-radius: 99px;
    font-size: .68rem; font-weight: 700;
    background: #e5e7eb; color: #6b7280;
    transition: background .2s, color .2s;
}
.sub-tab.active .tab-badge {
    background: var(--navy); color: #fff;
}

/* ─── Tab pane ──────────────────────────────────────────── */
.tab-pane { display: none; }
.tab-pane.active { display: block; }

/* ─── Submission cards ──────────────────────────────────── */
.sub-list {
    display: flex; flex-direction: column; gap: .75rem;
}
.sub-card {
    display: flex; align-items: center; gap: 1rem;
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: var(--radius);
    padding: 1rem 1.25rem;
    text-decoration: none; color: inherit;
    transition: border-color .2s, box-shadow .2s, transform .15s;
    box-shadow: var(--shadow-sm);
}
.sub-card:hover {
    border-color: var(--navy);
    box-shadow: 0 4px 18px rgba(13,33,68,.1);
    transform: translateY(-1px);
}
.sub-card-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
}
.sub-card-icon.ic-blotter  { background: #fef2f2; color: #b91c1c; }
.sub-card-icon.ic-business { background: #fffbeb; color: #92400e; }
.sub-card-body { flex: 1; min-width: 0; }
.sub-card-ref {
    font-size: .78rem; font-weight: 700;
    font-family: 'Courier New', monospace;
    color: var(--navy); letter-spacing: .04em;
    margin-bottom: .18rem;
}
.sub-card-title {
    font-size: .9rem; font-weight: 600;
    color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: .1rem;
}
.sub-card-meta {
    font-size: .73rem; color: var(--muted);
}
.sub-card-right {
    flex-shrink: 0;
    display: flex; flex-direction: column; align-items: flex-end; gap: .3rem;
}
.sub-status {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .22rem .65rem; border-radius: 99px;
    font-size: .68rem; font-weight: 700; letter-spacing: .04em;
    border: 1.5px solid;
}
.s-pending   { background: #fefce8; color: #a16207; border-color: #fde68a; }
.s-active    { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
.s-settled   { background: #ecfdf5; color: #14532d; border-color: #86efac; }
.s-closed    { background: #f3f4f6; color: #6b7280; border-color: #d1d5db; }
.s-mediated  { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.s-referred  { background: #f5f3ff; color: #5b21b6; border-color: #c4b5fd; }
.s-for-review { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.s-processing { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.s-released  { background: #f0fdf4; color: #166534; border-color: #86efac; }
.s-cancelled { background: #fef2f2; color: #991b1b; border-color: #fca5a5; }
.sub-card-arrow { color: #9ca3af; font-size: .8rem; }

/* ─── Empty state ───────────────────────────────────────── */
.sub-empty {
    text-align: center; padding: 3rem 1.5rem;
    color: var(--muted);
}
.sub-empty-icon {
    font-size: 2.5rem; margin-bottom: .75rem; opacity: .35;
}
.sub-empty h4 {
    font-size: 1rem; font-weight: 600;
    color: var(--navy); margin-bottom: .35rem;
}
.sub-empty p { font-size: .85rem; max-width: 340px; margin: 0 auto; }

/* ─── No-search state (before lookup) ──────────────────── */
.pre-search {
    text-align: center; padding: 3rem 1.5rem;
}
.pre-search-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--navy-pale);
    border: 2px solid var(--navy-border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; color: var(--navy);
    margin: 0 auto 1.25rem;
}
.pre-search h4 {
    font-size: 1rem; font-weight: 700; color: var(--navy);
    margin-bottom: .4rem;
}
.pre-search p { font-size: .85rem; color: var(--muted); max-width: 380px; margin: 0 auto; }

@media (max-width: 560px) {
    .sub-card { padding: .85rem 1rem; }
    .sub-card-icon { width: 38px; height: 38px; font-size: .88rem; }
    .lookup-row { flex-direction: column; }
    .lookup-row .btn-search { width: 100%; justify-content: center; }
    .sub-tabs { gap: 0; }
    .sub-tab { padding: .65rem .9rem; font-size: .82rem; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="portal-wrap-md">

    
    <div class="sub-hd">
        <h2><i class="fas fa-folder-open" style="color:var(--gold);margin-right:.5rem"></i>My Submissions</h2>
        <p>Look up your blotter reports and business permit applications filed through this portal using your registered contact number.</p>
    </div>

    
    <div class="lookup-card">
        <form method="GET" action="<?php echo e(route('portal.submissions')); ?>" id="lookupForm">
            <label for="contactInput">
                <i class="fas fa-phone"></i>&ensp;Look up by contact number
            </label>
            <div class="lookup-row">
                <input type="tel" id="contactInput" name="contact"
                       class="form-control"
                       value="<?php echo e($contact); ?>"
                       placeholder="e.g. 09XX XXX XXXX"
                       autocomplete="tel"
                       required>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
            <div class="lookup-hint">
                <i class="fas fa-info-circle"></i>
                Enter the contact number you used when filing the report or permit application.
            </div>
        </form>
    </div>

    <?php if($contact): ?>
    
    <div class="p-card">

        
        <div class="sub-tabs">
            <button class="sub-tab active" data-tab="blotter" onclick="switchTab('blotter', this)">
                <i class="fas fa-gavel"></i>
                Blotter Reports
                <span class="tab-badge"><?php echo e($blotterCases->count()); ?></span>
            </button>
            <button class="sub-tab" data-tab="business" onclick="switchTab('business', this)">
                <i class="fas fa-store"></i>
                Business Permits
                <span class="tab-badge"><?php echo e($businesses->count()); ?></span>
            </button>
        </div>

        
        <div id="tab-blotter" class="tab-pane active">
            <?php if($blotterCases->isNotEmpty()): ?>
            <div class="sub-list">
                <?php $__currentLoopData = $blotterCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $case): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('portal.track')); ?>?ref=<?php echo e($case->case_number); ?>" class="sub-card">
                    <div class="sub-card-icon ic-blotter">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="sub-card-body">
                        <div class="sub-card-ref"><?php echo e($case->case_number); ?></div>
                        <div class="sub-card-title"><?php echo e($case->incident_type); ?></div>
                        <div class="sub-card-meta">
                            <i class="fas fa-calendar-days" style="width:12px"></i>
                            Incident: <?php echo e($case->incident_date ? \Carbon\Carbon::parse($case->incident_date)->format('m/d/Y') : '—'); ?>

                            &ensp;·&ensp;
                            <i class="fas fa-clock" style="width:12px"></i>
                            Submitted: <?php echo e($case->created_at->format('m/d/Y')); ?>

                        </div>
                    </div>
                    <div class="sub-card-right">
                        <?php
                            $statusClass = match($case->status) {
                                'Pending'                         => 's-pending',
                                'Active', 'Under Investigation'   => 's-active',
                                'Settled'                         => 's-settled',
                                'Mediated'                        => 's-mediated',
                                'Referred to Higher Authority'    => 's-referred',
                                default                           => 's-closed',
                            };
                        ?>
                        <span class="sub-status <?php echo e($statusClass); ?>">
                            <?php echo e($case->status); ?>

                        </span>
                        <span class="sub-card-arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="sub-empty">
                <div class="sub-empty-icon"><i class="fas fa-gavel"></i></div>
                <h4>No blotter reports found</h4>
                <p>No blotter reports were filed using <strong><?php echo e($contact); ?></strong> as the contact number.</p>
                <a href="<?php echo e(route('portal.blotter')); ?>" class="btn btn-outline" style="margin-top:1.25rem;font-size:.85rem;min-height:40px">
                    <i class="fas fa-plus"></i> File a Blotter Report
                </a>
            </div>
            <?php endif; ?>
        </div>

        
        <div id="tab-business" class="tab-pane">
            <?php if($businesses->isNotEmpty()): ?>
            <div class="sub-list">
                <?php $__currentLoopData = $businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $biz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('portal.track')); ?>?ref=<?php echo e($biz->permit_number); ?>" class="sub-card">
                    <div class="sub-card-icon ic-business">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="sub-card-body">
                        <div class="sub-card-ref"><?php echo e($biz->permit_number); ?></div>
                        <div class="sub-card-title"><?php echo e($biz->business_name); ?></div>
                        <div class="sub-card-meta">
                            <i class="fas fa-tag" style="width:12px"></i>
                            <?php echo e($biz->business_type); ?>

                            &ensp;·&ensp;
                            <i class="fas fa-clock" style="width:12px"></i>
                            Submitted: <?php echo e($biz->created_at->format('m/d/Y')); ?>

                        </div>
                    </div>
                    <div class="sub-card-right">
                        <?php
                            $bizStatusClass = match($biz->status) {
                                'Pending'    => 's-pending',
                                'For Review' => 's-for-review',
                                'Active'     => 's-settled',
                                'Cancelled'  => 's-cancelled',
                                default      => 's-closed',
                            };
                        ?>
                        <span class="sub-status <?php echo e($bizStatusClass); ?>">
                            <?php echo e($biz->status); ?>

                        </span>
                        <span class="sub-card-arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="sub-empty">
                <div class="sub-empty-icon"><i class="fas fa-store"></i></div>
                <h4>No business permit applications found</h4>
                <p>No business permit applications were filed using <strong><?php echo e($contact); ?></strong> as the contact number.</p>
                <a href="<?php echo e(route('portal.business')); ?>" class="btn btn-outline" style="margin-top:1.25rem;font-size:.85rem;min-height:40px">
                    <i class="fas fa-plus"></i> Apply for a Business Permit
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>

    <?php if($blotterCases->isEmpty() && $businesses->isEmpty()): ?>
    <div class="p-alert p-alert-warning" style="margin-top:1rem">
        <i class="fas fa-triangle-exclamation"></i>
        <span>No submissions found for contact number <strong><?php echo e($contact); ?></strong>. Please check the number and try again, or use the contact number you entered when submitting.</span>
    </div>
    <?php endif; ?>

    <?php else: ?>
    
    <div class="p-card">
        <div class="pre-search">
            <div class="pre-search-icon"><i class="fas fa-folder-open"></i></div>
            <h4>Enter your contact number to view your submissions</h4>
            <p>All blotter reports and business permit applications you filed through this portal will appear here.</p>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* Auto-submit if ?ref= is passed from a redirect */
(function () {
    var params = new URLSearchParams(window.location.search);
    /* Tab memory via URL hash */
    var hash = window.location.hash.replace('#', '');
    if (hash === 'business' || hash === 'blotter') {
        var btn = document.querySelector('.sub-tab[data-tab="' + hash + '"]');
        if (btn) { switchTab(hash, btn); }
    }
    /* If contact present but both tabs empty, focus the input for correction */
    var contact = params.get('contact');
    if (contact) {
        var input = document.getElementById('contactInput');
        if (input) input.focus();
    }
}());

function switchTab(name, el) {
    /* Deactivate all tabs + panes */
    document.querySelectorAll('.sub-tab').forEach(function (t) { t.classList.remove('active'); });
    document.querySelectorAll('.tab-pane').forEach(function (p) { p.classList.remove('active'); });
    /* Activate selected */
    el.classList.add('active');
    var pane = document.getElementById('tab-' + name);
    if (pane) pane.classList.add('active');
    /* Update URL hash without scroll */
    if (history.replaceState) {
        var url = window.location.pathname + window.location.search + '#' + name;
        history.replaceState(null, '', url);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/submissions.blade.php ENDPATH**/ ?>