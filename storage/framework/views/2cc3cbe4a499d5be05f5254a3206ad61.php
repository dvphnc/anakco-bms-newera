<?php $__env->startSection('title', 'Track Your Request'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ─── Page header ─────────────────────────────────────────── */
.track-hd { margin-bottom: 1.5rem; }
.track-hd h2 {
    font-size: 1.2rem; font-weight: 700;
    color: var(--navy); margin-bottom: .25rem;
}
.track-hd p { font-size: .82rem; color: #6b7280; }

/* ─── Type pills hint ─────────────────────────────────────── */
.ref-hints {
    display: flex; flex-wrap: wrap; gap: .5rem;
    margin-top: .75rem;
}
.ref-hint {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .72rem; font-weight: 600;
    padding: .25rem .65rem; border-radius: 99px;
    border: 1px solid;
}
.rh-doc     { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.rh-biz     { background: #fffbeb; color: #92400e; border-color: #fde68a; }
.rh-blotter { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

/* ─── Search bar ──────────────────────────────────────────── */
.search-row { display: flex; gap: .75rem; align-items: flex-start; }
.search-row .form-control { flex: 1; font-size: .95rem; }
.search-row .btn { flex-shrink: 0; }

/* ─── Result card shell ───────────────────────────────────── */
.result-card {
    margin-top: 2rem;
    border: 1.5px solid #e5e7eb;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(13,33,68,.06);
}
.result-header {
    background: var(--navy);
    color: #fff;
    padding: 1.1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.result-header .apt-num {
    font-size: 1.15rem; font-weight: 800;
    color: var(--gold); letter-spacing: .06em;
}
.result-header .apt-label {
    font-size: .65rem; opacity: .65;
    text-transform: uppercase; letter-spacing: .07em; margin-bottom: .2rem;
}
.result-header .type-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .65rem; font-weight: 700; letter-spacing: .05em;
    text-transform: uppercase;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
    border-radius: 99px; padding: .2rem .6rem;
    color: rgba(255,255,255,.8);
    margin-top: .3rem;
}

/* Status badge in header */
.apt-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: .3rem .8rem; border-radius: 999px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
    border: 1.5px solid;
}
.apt-status-badge.s-pending             { background: rgba(200,134,26,.18); color: #e6a020; border-color: rgba(200,134,26,.35); }
.apt-status-badge.s-confirmed           { background: rgba(255,255,255,.12); color: #fff; border-color: rgba(255,255,255,.3); }
.apt-status-badge.s-processing          { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-ready               { background: rgba(22,163,74,.2); color: #86efac; border-color: rgba(22,163,74,.4); }
.apt-status-badge.s-released            { background: rgba(255,255,255,.08); color: #d1d5db; border-color: rgba(255,255,255,.2); }
.apt-status-badge.s-cancelled           { background: rgba(220,38,38,.2); color: #fca5a5; border-color: rgba(220,38,38,.35); }
.apt-status-badge.s-active              { background: rgba(220,38,38,.2); color: #fca5a5; border-color: rgba(220,38,38,.35); }
.apt-status-badge.s-for-review          { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-under-investigation { background: rgba(200,134,26,.18); color: #e6a020; border-color: rgba(200,134,26,.35); }
.apt-status-badge.s-mediated            { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-settled             { background: rgba(22,163,74,.2); color: #86efac; border-color: rgba(22,163,74,.4); }
.apt-status-badge.s-closed              { background: rgba(255,255,255,.08); color: #d1d5db; border-color: rgba(255,255,255,.2); }
.apt-status-badge.s-referred-to-higher-authority { background: rgba(124,58,237,.2); color: #c4b5fd; border-color: rgba(124,58,237,.35); }

/* ─── Info grid ───────────────────────────────────────────── */
.result-body { padding: 1.4rem 1.5rem; }
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .5rem .75rem;
    margin-bottom: 1.5rem;
}
.info-cell {
    padding: .65rem .9rem;
    background: #f9fafb;
    border-radius: var(--radius-sm);
    border: 1px solid #f0f1f3;
}
.info-cell .ic-label {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .06em;
    color: #9ca3af; margin-bottom: .18rem;
}
.info-cell .ic-value {
    font-size: .875rem; font-weight: 600; color: var(--navy); line-height: 1.35;
}
.info-cell.full-width { grid-column: 1 / -1; }

/* ─── Progress stepper ────────────────────────────────────── */
.progress-section {
    background: #f9fafb;
    border: 1px solid #f0f1f3;
    border-radius: var(--radius);
    padding: 1.1rem 1.25rem 1rem;
    margin-bottom: 1.5rem;
}
.progress-section .ps-title {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em;
    color: #9ca3af; margin-bottom: 1rem;
}
.progress-steps { display: flex; align-items: flex-start; gap: 0; }
.prog-step {
    flex: 1; text-align: center; position: relative;
}
.prog-step::before {
    content: '';
    position: absolute;
    top: 16px;
    left: calc(50% + 18px);
    right: calc(-50% + 18px);
    height: 2px;
    background: #e5e7eb;
    z-index: 0;
}
.prog-step:last-child::before { display: none; }
.prog-step.done::before { background: var(--navy); }
.prog-step.current::before { background: linear-gradient(90deg, var(--navy) 0%, #e5e7eb 100%); }

.prog-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #9ca3af;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700;
    margin: 0 auto .45rem;
    position: relative; z-index: 1;
    border: 2px solid #d1d5db;
    transition: all .2s;
}
.prog-step.done .prog-dot {
    background: var(--navy); color: #fff;
    border-color: var(--navy);
    font-size: .75rem;
}
.prog-step.current .prog-dot {
    background: var(--gold); color: #fff;
    border-color: var(--gold);
    box-shadow: 0 0 0 4px rgba(200,134,26,.2);
    font-size: .75rem;
}
.prog-step.cancelled .prog-dot {
    background: #dc2626; color: #fff; border-color: #dc2626;
}
.prog-label {
    font-size: .63rem; color: #9ca3af; font-weight: 500; line-height: 1.2;
}
.prog-step.done .prog-label    { color: var(--navy); font-weight: 700; }
.prog-step.current .prog-label { color: var(--gold);  font-weight: 700; }

/* ─── Timeline ────────────────────────────────────────────── */
.timeline-section { margin-top: 0; }
.timeline-hd {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: #9ca3af; margin-bottom: 1rem;
    display: flex; align-items: center; gap: .4rem;
}
.timeline { position: relative; padding-left: 1.6rem; }
.timeline::before {
    content: '';
    position: absolute;
    left: 7px; top: 8px;
    bottom: 8px; width: 2px;
    background: #e5e7eb;
    border-radius: 2px;
}
.tl-item { position: relative; margin-bottom: .9rem; }
.tl-item.tl-last { margin-bottom: 0; }
.tl-dot {
    position: absolute;
    left: -1.6rem; top: .3rem;
    width: 16px; height: 16px;
    border-radius: 50%;
    border: 2px solid;
    background: #fff;
    z-index: 1;
}
.tl-dot-pending    { border-color: var(--gold);  background: rgba(200,134,26,.15); }
.tl-dot-confirmed  { border-color: var(--navy);  background: rgba(13,33,68,.1); }
.tl-dot-processing { border-color: #2563eb;      background: rgba(37,99,235,.1); }
.tl-dot-ready      { border-color: #16a34a;      background: rgba(22,163,74,.1); }
.tl-dot-released   { border-color: #6b7280;      background: rgba(107,114,128,.1); }
.tl-dot-cancelled  { border-color: #dc2626;      background: rgba(220,38,38,.1); }
.tl-dot-active     { border-color: #dc2626;      background: rgba(220,38,38,.1); }
.tl-dot-settled    { border-color: #16a34a;      background: rgba(22,163,74,.1); }
.tl-dot-mediated   { border-color: #2563eb;      background: rgba(37,99,235,.1); }
.tl-dot-closed     { border-color: #6b7280;      background: rgba(107,114,128,.1); }

.tl-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: var(--radius-sm);
    padding: .65rem .85rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.tl-status { font-size: .85rem; font-weight: 700; color: var(--navy); margin-bottom: .2rem; }
.tl-meta {
    font-size: .72rem; color: #9ca3af;
    display: flex; flex-wrap: wrap; gap: .25rem .5rem;
}
.tl-meta .tl-who { color: #6b7280; font-weight: 600; }
.tl-note {
    margin-top: .4rem; font-size: .77rem; color: #6b7280;
    background: #f9fafb; border-left: 3px solid #e5e7eb;
    padding: .35rem .6rem; border-radius: 0 4px 4px 0; font-style: italic;
}

/* ─── Closed/cancelled banner ─────────────────────────────── */
.closed-banner {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .9rem 1.1rem;
    background: #fef2f2;
    border-radius: var(--radius-sm);
    border: 1px solid #fecaca;
    margin-bottom: 1.25rem;
}
.closed-banner .cb-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 50%; background: #dc2626; color: #fff;
    display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.closed-banner .cb-title { font-size: .88rem; font-weight: 700; color: #991b1b; margin-bottom: .15rem; }
.closed-banner .cb-text  { font-size: .8rem; color: #b91c1c; }

/* ─── Not found ───────────────────────────────────────────── */
.not-found { text-align: center; padding: 2.5rem 1rem; margin-top: 1.5rem; }
.nf-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: #fef2f2; color: #dc2626;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin: 0 auto 1rem;
}
.not-found h3 { font-size: 1rem; color: var(--navy); margin-bottom: .35rem; }
.not-found p  { font-size: .82rem; color: #6b7280; }

.section-divider { height: 1px; background: #f0f1f3; margin: 1.25rem 0; }

/* ─── Mobile ──────────────────────────────────────────────── */
@media (max-width: 600px) {
    .search-row { flex-direction: column; }
    .search-row .btn { width: 100%; justify-content: center; }
    .info-grid { grid-template-columns: 1fr; }
    .info-cell.full-width { grid-column: 1; }
    .result-header { flex-direction: column; align-items: flex-start; gap: .5rem; }
    .progress-steps {
        flex-direction: column; align-items: flex-start;
        gap: 0; padding-left: .5rem;
    }
    .prog-step {
        display: flex; align-items: center;
        gap: .75rem; flex: none; width: 100%;
        padding: .3rem 0; text-align: left;
    }
    .prog-step::before {
        top: 32px; left: 15px; right: auto;
        width: 2px; height: calc(100% + 2px);
        background: #e5e7eb;
    }
    .prog-step.done::before { background: var(--navy); }
    .prog-dot { flex-shrink: 0; margin: 0; }
    .prog-label { font-size: .82rem; margin-top: 0; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="portal-wrap">
<div class="p-card">

    <div class="track-hd">
        <h2><i class="fas fa-magnifying-glass" style="color:var(--gold)"></i>&nbsp; Track Your Request</h2>
        <p>Enter your reference number to check the status of your document appointment, business permit, or blotter report.</p>
        <div class="ref-hints">
            <span class="ref-hint rh-doc"><i class="fas fa-file-lines"></i> Document — <code>APT-...</code></span>
            <span class="ref-hint rh-biz"><i class="fas fa-store"></i> Business Permit — <code>BP-...</code></span>
            <span class="ref-hint rh-blotter"><i class="fas fa-shield-halved"></i> Blotter Report — <code>CASE-...</code></span>
        </div>
    </div>

    <form id="trackForm" method="POST" action="<?php echo e(route('track.post')); ?>">
        <?php echo csrf_field(); ?>
        <div class="search-row">
            <input type="text" name="appointment_number" id="trackInput" class="form-control"
                   placeholder="APT-..., BP-..., or CASE-..."
                   value="<?php echo e(request('ref') ?? old('appointment_number') ?? (isset($number) ? $number : '')); ?>"
                   style="text-transform:uppercase;letter-spacing:.06em"
                   autocomplete="off"
                   required>
            <button type="submit" id="trackBtn" class="btn btn-primary">
                <i class="fas fa-search"></i> Track
            </button>
        </div>
        <?php $__errorArgs = ['appointment_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="form-error" style="margin-top:.4rem"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </form>

    
    <div id="trackResult" style="display:none"></div>

    
    <?php if(isset($type)): ?>
        <?php if($type && $record): ?>
            <div class="result-card" id="serverResult">
                <div class="result-header">
                    <div>
                        <div class="apt-label">Reference Number</div>
                        <div class="apt-num"><?php echo e($number); ?></div>
                        <div class="type-chip">
                            <?php if($type === 'document'): ?> <i class="fas fa-file-lines"></i> Document Appointment
                            <?php elseif($type === 'business'): ?> <i class="fas fa-store"></i> Business Permit
                            <?php else: ?> <i class="fas fa-shield-halved"></i> Blotter Report
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">
                        <?php $slug = strtolower(str_replace([' ', '/'], ['-', ''], $record->status)); ?>
                        <span class="apt-status-badge s-<?php echo e($slug); ?>">
                            <i class="fas fa-circle" style="font-size:.45rem"></i>
                            <?php echo e($record->status); ?>

                        </span>
                        <div style="font-size:.72rem;opacity:.6;text-align:right">
                            Submitted <?php echo e($record->created_at->format('M d, Y · g:i A')); ?>

                        </div>
                    </div>
                </div>
                <div class="result-body">
                    <p style="font-size:.85rem;color:#6b7280;text-align:center;padding:1rem 0">
                        <i class="fas fa-circle-info" style="color:var(--navy)"></i>
                        Your request was found. The full status details are displayed above.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="not-found" id="serverResult">
                <div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>
                <h3>Reference Not Found</h3>
                <p>No record found for <strong><?php echo e($number); ?></strong>. Please double-check your reference number and try again.</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(function () {
    var form      = document.getElementById('trackForm');
    var input     = document.getElementById('trackInput');
    var btn       = document.getElementById('trackBtn');
    var resultDiv = document.getElementById('trackResult');
    var serverRes = document.getElementById('serverResult');

    if (!form) return;
    if (serverRes) serverRes.style.display = 'none';

    if (input.value.trim()) doSearch(input.value.trim());

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        doSearch(input.value.trim());
    });

    function doSearch(number) {
        if (!number) return;
        btn.disabled  = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching…';
        resultDiv.style.display = 'none';

        axios.get('/portal/track/lookup', { params: { number: number.toUpperCase() } })
            .then(function (res) {
                resultDiv.innerHTML = renderResult(res.data);
                resultDiv.style.display = 'block';
            })
            .catch(function () {
                resultDiv.innerHTML =
                    '<div class="not-found">' +
                    '<div class="nf-icon"><i class="fas fa-wifi"></i></div>' +
                    '<h3>Connection Error</h3>' +
                    '<p>Could not reach the server. Please try again.</p>' +
                    '</div>';
                resultDiv.style.display = 'block';
            })
            .finally(function () {
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-search"></i> Track';
            });
    }

    /* ── Helpers ── */
    function esc(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function infoCell(label, value, full) {
        if (!value) return '';
        return '<div class="info-cell' + (full ? ' full-width' : '') + '">' +
            '<div class="ic-label">' + label + '</div>' +
            '<div class="ic-value">' + esc(value) + '</div>' +
            '</div>';
    }
    function statusSlug(s) {
        return s ? s.toLowerCase().replace(/\s+/g, '-').replace(/\//g, '') : '';
    }
    function statusBadgeHtml(s) {
        return '<span class="apt-status-badge s-' + statusSlug(s) + '">' +
            '<i class="fas fa-circle" style="font-size:.45rem"></i> ' + esc(s) +
            '</span>';
    }
    function typeChip(type) {
        var map = {
            document: { icon: 'fa-file-lines',    label: 'Document Appointment' },
            business: { icon: 'fa-store',          label: 'Business Permit' },
            blotter:  { icon: 'fa-shield-halved',  label: 'Blotter Report' },
        };
        var t = map[type] || { icon: 'fa-circle', label: type };
        return '<div class="type-chip"><i class="fas ' + t.icon + '"></i> ' + t.label + '</div>';
    }

    function renderStepper(d) {
        if (!d.steps || !d.steps.length || d.cancelled) return '';
        var dots = d.steps.map(function (step, i) {
            var isDone    = d.step_index !== -1 && i < d.step_index;
            var isCurrent = d.status === step;
            var cls  = isDone ? 'done' : (isCurrent ? 'current' : '');
            var icon = isDone
                ? '<i class="fas fa-check"></i>'
                : (isCurrent ? '<i class="fas fa-circle-dot"></i>' : (i + 1));
            return '<div class="prog-step ' + cls + '">' +
                '<div class="prog-dot">' + icon + '</div>' +
                '<div class="prog-label">' + esc(step) + '</div>' +
                '</div>';
        }).join('');
        return '<div class="progress-section">' +
            '<div class="ps-title"><i class="fas fa-route"></i>&nbsp; Progress</div>' +
            '<div class="progress-steps">' + dots + '</div>' +
            '</div>';
    }

    function renderTimeline(logs) {
        if (!logs || !logs.length) return '';
        var items = logs.map(function (l, i) {
            var isLast = i === logs.length - 1;
            var slug   = statusSlug(l.to);
            var who    = l.by ? '<span class="tl-who">' + esc(l.by) + '</span>' : '';
            var note   = l.note ? '<div class="tl-note">' + esc(l.note) + '</div>' : '';
            return '<div class="tl-item' + (isLast ? ' tl-last' : '') + '">' +
                '<div class="tl-dot tl-dot-' + slug + '"></div>' +
                '<div class="tl-card">' +
                    '<div class="tl-status">' + esc(l.to) + '</div>' +
                    '<div class="tl-meta"><span>' + esc(l.date) + ' · ' + esc(l.time) + '</span>' + who + '</div>' +
                    note +
                '</div>' +
                '</div>';
        }).join('');
        return '<div class="section-divider"></div>' +
            '<div class="timeline-section">' +
            '<div class="timeline-hd"><i class="fas fa-clock-rotate-left"></i> Status History</div>' +
            '<div class="timeline">' + items + '</div>' +
            '</div>';
    }

    function closedBanner(title, text, icon) {
        return '<div class="closed-banner">' +
            '<div class="cb-icon"><i class="fas ' + (icon||'fa-ban') + '"></i></div>' +
            '<div><div class="cb-title">' + title + '</div>' +
            '<div class="cb-text">' + text + '</div></div>' +
            '</div>';
    }

    /* ── Info grids per type ── */
    function infoGridDocument(d) {
        return '<div class="info-grid">' +
            infoCell('Name',           d.resident_name) +
            infoCell('Document',       d.document_type) +
            infoCell('Preferred Date', d.preferred_date) +
            infoCell('Purpose',        d.purpose) +
            infoCell('Processed By',   d.processed_by) +
            infoCell('Released On',    d.released_at) +
            infoCell('Last Updated',   d.updated_at) +
            infoCell('Staff Notes',    d.notes, true) +
            '</div>';
    }
    function infoGridBusiness(d) {
        return '<div class="info-grid">' +
            infoCell('Owner',            d.owner_name) +
            infoCell('Business Name',    d.business_name) +
            infoCell('Business Type',    d.business_type) +
            infoCell('Appointment Date', d.appointment_date) +
            infoCell('Business Address', d.business_address, true) +
            (d.permit_date ? infoCell('Permit Date',  d.permit_date)  : '') +
            (d.expiry_date ? infoCell('Expiry Date',  d.expiry_date)  : '') +
            infoCell('Last Updated',     d.updated_at) +
            '</div>';
    }
    function infoGridBlotter(d) {
        return '<div class="info-grid">' +
            infoCell('Complainant',    d.complainant_name) +
            infoCell('Incident Type',  d.incident_type) +
            infoCell('Incident Date',  d.incident_date) +
            infoCell('Last Updated',   d.updated_at) +
            infoCell('Location',       d.incident_location, true) +
            (d.respondent_name ? infoCell('Respondent', d.respondent_name) : '') +
            (d.resolution_notes ? infoCell('Staff Notes', d.resolution_notes, true) : '') +
            '</div>';
    }

    /* ── Main render ── */
    function renderResult(d) {
        if (!d.found) {
            return '<div class="not-found">' +
                '<div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>' +
                '<h3>Reference Not Found</h3>' +
                '<p>No record found for that reference number. Please double-check and try again.</p>' +
                '</div>';
        }

        var header = '<div class="result-header">' +
            '<div>' +
                '<div class="apt-label">Reference Number</div>' +
                '<div class="apt-num">' + esc(d.reference_number) + '</div>' +
                typeChip(d.type) +
            '</div>' +
            '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">' +
                statusBadgeHtml(d.status) +
                '<div style="font-size:.72rem;opacity:.6;text-align:right">Submitted ' + esc(d.created_at) + '</div>' +
            '</div>' +
            '</div>';

        var banner = '';
        if (d.cancelled) {
            if (d.type === 'blotter') {
                banner = closedBanner('This blotter case has been closed.', d.resolution_notes || 'The case has been resolved.', 'fa-shield-halved');
            } else if (d.type === 'business') {
                banner = closedBanner('This permit application has been cancelled.', 'Please visit the Barangay Hall for assistance.', 'fa-ban');
            } else {
                var cNote = d.notes || 'Please visit the barangay hall or submit a new request.';
                banner = closedBanner('This appointment has been cancelled.', cNote, 'fa-ban');
            }
        }

        var progress = renderStepper(d);

        var grid = '';
        if (d.type === 'document') grid = infoGridDocument(d);
        else if (d.type === 'business') grid = infoGridBusiness(d);
        else if (d.type === 'blotter') grid = infoGridBlotter(d);

        var timeline = renderTimeline(d.logs || []);

        return '<div class="result-card">' +
            header +
            '<div class="result-body">' +
                banner + progress + grid + timeline +
            '</div>' +
            '</div>';
    }
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/track.blade.php ENDPATH**/ ?>