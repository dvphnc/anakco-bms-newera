<?php $__env->startSection('title', 'File a Blotter Report'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .page-hd { margin-bottom: 1.75rem; }
    .page-hd h2 { font-size: 1.25rem; font-weight: 700; color: var(--navy); margin-bottom: .25rem; }
    .page-hd p  { font-size: .82rem; color: #6b7280; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    .section-label {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: #9ca3af; margin: 1.5rem 0 .75rem;
    }
    .section-label:first-child { margin-top: 0; }

    .form-actions {
        display: flex; justify-content: flex-end;
        gap: .75rem; margin-top: 1.75rem;
        padding-top: 1.25rem; border-top: 1px solid #f0f0f0;
    }
    @media (max-width: 600px) {
        .form-actions { flex-direction: column; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
    .disclaimer {
        font-size: .75rem; color: #9ca3af;
        text-align: center; margin-top: 1rem; line-height: 1.6;
    }
    .btn-submit-spinner {
        display: inline-block; width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,.35); border-top-color: #fff;
        border-radius: 50%; animation: req-spin .7s linear infinite; flex-shrink: 0;
    }
    @keyframes req-spin { to { transform: rotate(360deg); } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="portal-wrap-md">
<div class="p-card">

    <div class="page-hd">
        <h2><i class="fas fa-gavel" style="color:var(--gold)"></i>&nbsp; File a Blotter Report</h2>
        <p>Submit a blotter report online. All fields marked with <span style="color:var(--crimson)">*</span> are required. A barangay staff member will follow up with you.</p>
    </div>

    <div id="form-error-banner" class="p-alert p-alert-error" style="display:none">
        <i class="fas fa-exclamation-circle"></i>
        <span id="form-error-text">Please correct the errors below.</span>
    </div>

    <form id="blotterForm" novalidate>
        <?php echo csrf_field(); ?>

        <div class="section-label">Complainant Information</div>
        <div class="form-row">
            <div class="form-group">
                <label for="complainant_name">Full Name <span class="req">*</span></label>
                <input type="text" id="complainant_name" name="complainant_name" class="form-control"
                       placeholder="Last, First Middle" autocomplete="name">
                <div class="form-error" id="err-complainant_name" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number <span class="req">*</span></label>
                <input type="text" id="contact_number" name="contact_number" class="form-control"
                       placeholder="09XXXXXXXXX" autocomplete="tel">
                <div class="form-error" id="err-contact_number" style="display:none"></div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address <span style="color:#9ca3af;font-weight:400">(optional — for status notifications)</span></label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="you@example.com" autocomplete="email">
                <div class="form-error" id="err-email" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="address">Home Address <span class="req">*</span></label>
                <input type="text" id="address" name="address" class="form-control"
                       placeholder="Purok, Street, Barangay New Era">
                <div class="form-error" id="err-address" style="display:none"></div>
            </div>
        </div>

        <div class="section-label">Incident Details</div>
        <div class="form-row">
            <div class="form-group">
                <label for="incident_type">Incident Type <span class="req">*</span></label>
                <select id="incident_type" name="incident_type" class="form-control">
                    <option value="">— Select type —</option>
                    <?php $__currentLoopData = $incidentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($it); ?>"><?php echo e($it); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="form-error" id="err-incident_type" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="incident_date">Incident Date <span class="req">*</span></label>
                <input type="date" id="incident_date" name="incident_date" class="form-control"
                       max="<?php echo e(now()->format('Y-m-d')); ?>">
                <div class="form-error" id="err-incident_date" style="display:none"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="incident_location">Incident Location <span class="req">*</span></label>
            <input type="text" id="incident_location" name="incident_location" class="form-control"
                   placeholder="Exact location where the incident occurred">
            <div class="form-error" id="err-incident_location" style="display:none"></div>
        </div>
        <div class="form-group">
            <label for="incident_description">Incident Description <span class="req">*</span></label>
            <textarea id="incident_description" name="incident_description" class="form-control" rows="4"
                      placeholder="Describe the incident in detail. Include what happened, when, and who was involved."></textarea>
            <div class="form-error" id="err-incident_description" style="display:none"></div>
        </div>
        <div class="form-group">
            <label for="respondent_name">Respondent / Suspect Name <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
            <input type="text" id="respondent_name" name="respondent_name" class="form-control"
                   placeholder="Name of person being complained against (if known)">
            <div class="form-error" id="err-respondent_name" style="display:none"></div>
        </div>

        <div class="form-actions">
            <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <i class="fas fa-paper-plane" id="submitIcon"></i>
                <span class="btn-submit-spinner" id="submitSpinner" style="display:none"></span>
                <span id="submitLabel">Submit Blotter Report</span>
            </button>
        </div>

        <p class="disclaimer">
            By submitting this form, you confirm that all information provided is truthful and accurate.<br>
            Filing a false blotter report is a punishable offense. A barangay official will contact you for follow-up.
        </p>
    </form>

</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    var LS_NAME    = 'portal_name';
    var LS_CONTACT = 'portal_contact';
    var LS_EMAIL   = 'portal_email';

    function clearErrors() {
        document.querySelectorAll('.form-error[id^="err-"]').forEach(function (el) {
            el.style.display = 'none'; el.textContent = '';
        });
        document.querySelectorAll('.form-control').forEach(function (el) {
            el.style.borderColor = '';
        });
        document.getElementById('form-error-banner').style.display = 'none';
    }

    function showError(field, msg) {
        var errEl = document.getElementById('err-' + field);
        if (errEl) { errEl.textContent = msg; errEl.style.display = 'block'; }
        var input = document.getElementById(field);
        if (input) input.style.borderColor = 'var(--crimson)';
    }

    document.addEventListener('DOMContentLoaded', function () {
        var name    = localStorage.getItem(LS_NAME);
        var contact = localStorage.getItem(LS_CONTACT);
        var email   = localStorage.getItem(LS_EMAIL);
        if (name)    document.getElementById('complainant_name').value = name;
        if (contact) document.getElementById('contact_number').value   = contact;
        if (email)   document.getElementById('email').value            = email;
    });

    document.getElementById('blotterForm').addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

        var btn     = document.getElementById('submitBtn');
        var icon    = document.getElementById('submitIcon');
        var spinner = document.getElementById('submitSpinner');
        var label   = document.getElementById('submitLabel');
        btn.disabled = true;
        icon.style.display    = 'none';
        spinner.style.display = 'inline-block';
        label.textContent     = 'Submitting…';

        var name    = document.getElementById('complainant_name').value.trim();
        var contact = document.getElementById('contact_number').value.trim();
        var email   = document.getElementById('email').value.trim();
        if (name)    localStorage.setItem(LS_NAME, name);
        if (contact) localStorage.setItem(LS_CONTACT, contact);
        if (email)   localStorage.setItem(LS_EMAIL, email);
        else         localStorage.removeItem(LS_EMAIL);

        var fd = new FormData(document.getElementById('blotterForm'));

        axios.post('<?php echo e(route('portal.blotter.store')); ?>', fd, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) {
            if (res.data && res.data.redirect) {
                window.location.href = res.data.redirect;
            }
        })
        .catch(function (err) {
            btn.disabled = false;
            icon.style.display    = 'inline-block';
            spinner.style.display = 'none';
            label.textContent     = 'Submit Blotter Report';

            if (err.response && err.response.status === 422) {
                var errors = err.response.data.errors || {};
                var first  = true;
                Object.keys(errors).forEach(function (field) {
                    var msg = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    showError(field, msg);
                    if (first) {
                        var el = document.getElementById(field);
                        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        first = false;
                    }
                });
                var banner = document.getElementById('form-error-banner');
                banner.style.display = 'flex';
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                portalToast('Something went wrong. Please try again.', 'error');
            }
        });
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\portal\blotter-request.blade.php ENDPATH**/ ?>