@extends('layouts.portal')
@section('title', 'Schedule a Business Permit Appointment')

@push('styles')
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

    .info-note {
        display: flex; gap: .65rem; align-items: flex-start;
        background: #eff6ff; border: 1px solid #bfdbfe;
        border-radius: var(--radius-sm); padding: .75rem 1rem;
        font-size: .8rem; color: #1e40af; margin-bottom: 1.25rem;
        line-height: 1.5;
    }
    .info-note i { flex-shrink: 0; margin-top: .1rem; }
</style>
@endpush

@section('content')
<div class="portal-wrap-md">
<div class="p-card">

    <div class="page-hd">
        <h2><i class="fas fa-calendar-check" style="color:var(--gold)"></i>&nbsp; Schedule a Business Permit Appointment</h2>
        <p>Book your barangay business clearance appointment online. All fields marked with <span style="color:var(--crimson)">*</span> are required.</p>
    </div>

    <div class="info-note">
        <i class="fas fa-circle-info"></i>
        <span>Processing a business permit requires a physical visit to the Barangay Hall for document submission and inspection. Use this form to schedule your preferred appointment date and we'll have staff ready for you.</span>
    </div>

    <div id="form-error-banner" class="p-alert p-alert-error" style="display:none">
        <i class="fas fa-exclamation-circle"></i>
        <span id="form-error-text">Please correct the errors below.</span>
    </div>

    <form id="businessForm" novalidate>
        @csrf

        <div class="section-label">Owner / Applicant Information</div>
        <div class="form-row">
            <div class="form-group">
                <label for="owner_name">Owner's Full Name <span class="req">*</span></label>
                <input type="text" id="owner_name" name="owner_name" class="form-control"
                       placeholder="Last, First Middle" autocomplete="name">
                <div class="form-error" id="err-owner_name" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number <span class="req">*</span></label>
                <input type="text" id="contact_number" name="contact_number" class="form-control"
                       placeholder="09XXXXXXXXX" autocomplete="tel">
                <div class="form-error" id="err-contact_number" style="display:none"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email Address <span style="color:#9ca3af;font-weight:400">(optional — for appointment confirmation)</span></label>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="you@example.com" autocomplete="email">
            <div class="form-error" id="err-email" style="display:none"></div>
        </div>

        <div class="section-label">Business Details</div>
        <div class="form-row">
            <div class="form-group">
                <label for="business_name">Business Name <span class="req">*</span></label>
                <input type="text" id="business_name" name="business_name" class="form-control"
                       placeholder="Registered business name">
                <div class="form-error" id="err-business_name" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="business_type">Business Type <span class="req">*</span></label>
                <select id="business_type" name="business_type" class="form-control">
                    <option value="">— Select type —</option>
                    @foreach($businessTypes as $bt)
                        <option value="{{ $bt }}">{{ $bt }}</option>
                    @endforeach
                </select>
                <div class="form-error" id="err-business_type" style="display:none"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="business_address">Business Address <span class="req">*</span></label>
            <input type="text" id="business_address" name="business_address" class="form-control"
                   placeholder="Full address of the business within Barangay New Era">
            <div class="form-error" id="err-business_address" style="display:none"></div>
        </div>
        <div class="form-group">
            <label for="purpose">Purpose / Additional Notes <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
            <input type="text" id="purpose" name="purpose" class="form-control"
                   placeholder="e.g., New registration, annual renewal…">
            <div class="form-error" id="err-purpose" style="display:none"></div>
        </div>

        <div class="section-label">Appointment Schedule</div>
        <div class="form-row">
            <div class="form-group">
                <label for="preferred_date">Preferred Appointment Date <span class="req">*</span></label>
                <input type="date" id="preferred_date" name="preferred_date" class="form-control"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <div class="form-error" id="err-preferred_date" style="display:none"></div>
                <div style="font-size:.75rem;color:#9ca3af;margin-top:.35rem">
                    <i class="fas fa-clock" style="font-size:.7rem"></i>
                    Office hours: Monday – Friday, 8:00 AM – 5:00 PM
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('portal.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <i class="fas fa-calendar-check" id="submitIcon"></i>
                <span class="btn-submit-spinner" id="submitSpinner" style="display:none"></span>
                <span id="submitLabel">Schedule Appointment</span>
            </button>
        </div>

        <p class="disclaimer">
            By submitting this form, you confirm that all information provided is accurate and complete.<br>
            Please bring your valid ID and required documents on your appointment date.
        </p>
    </form>

</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    ['portal_name','portal_contact','portal_email'].forEach(function (k) { localStorage.removeItem(k); });

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

    document.getElementById('businessForm').addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

        var btn     = document.getElementById('submitBtn');
        var icon    = document.getElementById('submitIcon');
        var spinner = document.getElementById('submitSpinner');
        var label   = document.getElementById('submitLabel');
        btn.disabled = true;
        icon.style.display    = 'none';
        spinner.style.display = 'inline-block';
        label.textContent     = 'Scheduling…';

        var fd = new FormData(document.getElementById('businessForm'));

        axios.post('{{ route('portal.business.store') }}', fd, {
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
            label.textContent     = 'Schedule Appointment';

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
@endpush
