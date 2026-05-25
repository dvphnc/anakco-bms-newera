@extends('layouts.portal')
@section('title', 'Request a Document')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
<style>
    .page-hd { margin-bottom: 1.75rem; }
    .page-hd h2 {
        font-size: 1.25rem; font-weight: 700;
        color: var(--navy); margin-bottom: .25rem;
    }
    .page-hd p { font-size: .82rem; color: #6b7280; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 600px) { .form-row { grid-template-columns: 1fr; } }

    .step-indicator {
        display: flex; gap: .5rem; margin-bottom: 1.75rem;
    }
    .step-dot {
        display: flex; align-items: center; gap: .5rem;
        font-size: .78rem; color: #9ca3af; font-weight: 500;
    }
    .step-dot .dot {
        width: 24px; height: 24px; border-radius: 50%;
        background: #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700;
    }
    .step-dot.active { color: var(--navy); }
    .step-dot.active .dot { background: var(--navy); color: #fff; }
    .step-divider { flex: 1; height: 2px; background: #e5e7eb; align-self: center; }

    .section-label {
        font-size: .75rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
        color: #9ca3af;
        margin: 1.5rem 0 .75rem;
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

    /* Axios submit spinner */
    .btn-submit-spinner {
        display: inline-block;
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,.35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: req-spin .7s linear infinite;
        flex-shrink: 0;
    }
    @keyframes req-spin { to { transform: rotate(360deg); } }

    /* Select2 portal theme overrides */
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 1.5px solid #d1d5db !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
        display: flex; align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px !important;
        padding-left: 1rem !important;
        color: var(--text);
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
        right: 10px !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: var(--navy) !important;
        box-shadow: 0 0 0 3px rgba(13,33,68,.1) !important;
        outline: none !important;
    }
    .select2-dropdown {
        border: 1.5px solid var(--navy) !important;
        border-radius: 8px !important;
        font-family: 'Poppins', sans-serif;
        font-size: .92rem;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--navy) !important;
    }
    .select2-container { width: 100% !important; }
</style>
@endpush

@section('content')
<div class="portal-wrap-md">
<div class="p-card">

    <div class="step-indicator">
        <div class="step-dot active"><div class="dot">1</div> Fill Form</div>
        <div class="step-divider"></div>
        <div class="step-dot"><div class="dot">2</div> Confirmation</div>
        <div class="step-divider"></div>
        <div class="step-dot"><div class="dot">3</div> Claim Document</div>
    </div>

    <div class="page-hd">
        <h2><i class="fas fa-file-arrow-up" style="color:var(--gold)"></i>&nbsp; Request a Barangay Document</h2>
        <p>Fill in your details below. All fields marked with <span style="color:var(--crimson)">*</span> are required.</p>
    </div>

    {{-- Inline error banner (shown by JS on 422) --}}
    <div id="form-error-banner" class="p-alert p-alert-error" style="display:none">
        <i class="fas fa-exclamation-circle"></i>
        <span id="form-error-text">Please correct the errors below.</span>
    </div>

    <form id="docRequestForm" novalidate>
        @csrf

        <div class="section-label">Personal Information</div>
        <div class="form-row">
            <div class="form-group">
                <label for="resident_name">Full Name <span class="req">*</span></label>
                <input type="text" id="resident_name" name="resident_name" class="form-control"
                       placeholder="Last, First Middle" autocomplete="name">
                <div class="form-error" id="err-resident_name" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number <span class="req">*</span></label>
                <input type="text" id="contact_number" name="contact_number" class="form-control"
                       placeholder="09XXXXXXXXX" autocomplete="tel">
                <div class="form-error" id="err-contact_number" style="display:none"></div>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address <span style="color:#9ca3af;font-weight:400">(optional — for status notifications)</span></label>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="you@example.com" autocomplete="email">
            <div class="form-error" id="err-email" style="display:none"></div>
        </div>

        <div class="section-label">Document Request</div>
        <div class="form-row">
            <div class="form-group">
                <label for="document_type">Document Type <span class="req">*</span></label>
                <select id="document_type" name="document_type">
                    <option value="">— Select document —</option>
                    @foreach($documentTypes as $dt)
                        <option value="{{ $dt }}">{{ $dt }}</option>
                    @endforeach
                </select>
                <div class="form-error" id="err-document_type" style="display:none"></div>
            </div>
            <div class="form-group">
                <label for="preferred_date">Preferred Pick-up Date <span class="req">*</span></label>
                <input type="date" id="preferred_date" name="preferred_date" class="form-control"
                       min="{{ now()->addDay()->format('Y-m-d') }}">
                <div class="form-error" id="err-preferred_date" style="display:none"></div>
                <div style="font-size:.72rem;color:#9ca3af;margin-top:4px">
                    <i class="fas fa-circle-info" style="color:#9ca3af"></i>
                    This is your requested timeframe. The actual pick-up date will be confirmed by barangay staff.
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="purpose">Purpose / Reason for Request</label>
            <textarea id="purpose" name="purpose" class="form-control" rows="2"
                      placeholder="e.g., For employment purposes, school enrollment, etc."></textarea>
            <div class="form-error" id="err-purpose" style="display:none"></div>
        </div>

        <div class="section-label">Pick-up / Representative</div>

        {{-- Representative toggle --}}
        <input type="hidden" name="is_representative" id="isRepHidden" value="0">
        <label id="repToggle" for="isRepCheck" style="
            display:flex; align-items:flex-start; gap:.75rem;
            background:#f9fafb; border:1.5px solid #e5e7eb; border-radius:8px;
            padding:.85rem 1rem; cursor:pointer; margin-bottom:1rem;
            transition: border-color .15s, background .15s;">
            <input type="checkbox" id="isRepCheck" value="1" style="margin-top:2px;accent-color:var(--navy);width:16px;height:16px;flex-shrink:0">
            <div>
                <div style="font-size:.88rem;font-weight:600;color:var(--navy)">
                    A <strong>representative</strong> is picking up / requesting this document
                </div>
                <div style="font-size:.75rem;color:#6b7280;margin-top:2px">
                    Check this if someone other than the resident will claim the document (e.g. child, spouse, attorney).
                </div>
            </div>
        </label>

        {{-- Representative fields — shown only when checkbox is checked --}}
        <div id="repFields" style="display:none">
            <div class="form-row">
                <div class="form-group">
                    <label for="requestor_name">Representative Name <span class="req">*</span></label>
                    <input type="text" id="requestor_name" name="requestor_name" class="form-control"
                           placeholder="Full name of the person picking up">
                    <div class="form-error" id="err-requestor_name" style="display:none"></div>
                </div>
                <div class="form-group">
                    <label for="requestor_relationship">Relationship to Resident <span class="req">*</span></label>
                    <select id="requestor_relationship" name="requestor_relationship" class="form-control" style="height:48px;border:1.5px solid #d1d5db;border-radius:8px;font-family:'Poppins',sans-serif;font-size:1rem;padding:0 1rem;background:#fff">
                        <option value="">Select relationship</option>
                        @foreach(['Son','Daughter','Parent / Guardian','Spouse','Sibling','Cousin','Nephew / Niece','Legal Guardian','Attorney-in-Fact (SPA)','Other'] as $rel)
                            <option value="{{ $rel }}">{{ $rel }}</option>
                        @endforeach
                    </select>
                    <div class="form-error" id="err-requestor_relationship" style="display:none"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="requestor_contact">
                    Representative Contact
                    <span style="color:#9ca3af;font-weight:400">(optional — phone or email)</span>
                </label>
                <input type="text" id="requestor_contact" name="requestor_contact" class="form-control"
                       placeholder="Phone or email (optional)">
                <div class="form-error" id="err-requestor_contact" style="display:none"></div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('portal.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" id="submitBtn" class="btn btn-primary">
                <i class="fas fa-paper-plane" id="submitIcon"></i>
                <span class="btn-submit-spinner" id="submitSpinner" style="display:none"></span>
                <span id="submitLabel">Submit Request</span>
            </button>
        </div>

        <p class="disclaimer">
            By submitting this form, you confirm that all information provided is accurate.<br>
            Processing time is 1–3 business days. You will need to present a valid ID when claiming.
        </p>
    </form>

</div>{{-- /.p-card --}}
</div>{{-- /.portal-wrap-md --}}
@endsection

@push('scripts')
{{-- jQuery + Select2 (portal layout doesn't include these) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
(function () {
    // Clear any previously-saved personal info so the form always starts blank
    ['portal_name','portal_contact','portal_email'].forEach(function (k) { localStorage.removeItem(k); });

    /* ── Error helpers ── */
    function clearErrors() {
        document.querySelectorAll('.form-error[id^="err-"]').forEach(function (el) {
            el.style.display = 'none';
            el.textContent   = '';
        });
        document.querySelectorAll('.form-control, .select2-selection--single').forEach(function (el) {
            el.style.borderColor = '';
        });
        document.getElementById('form-error-banner').style.display = 'none';
    }

    function showError(field, msg) {
        var errEl = document.getElementById('err-' + field);
        if (errEl) {
            errEl.textContent   = msg;
            errEl.style.display = 'block';
        }
        var input = document.getElementById(field);
        if (input) input.style.borderColor = 'var(--crimson)';
        // For Select2, target the rendered element
        if (field === 'document_type') {
            var s2 = document.querySelector('.select2-selection--single');
            if (s2) s2.style.borderColor = 'var(--crimson)';
        }
    }

    /* ── Representative checkbox toggle ── */
    document.addEventListener('DOMContentLoaded', function () {
        var repCheck  = document.getElementById('isRepCheck');
        var repHidden = document.getElementById('isRepHidden');
        var repFields = document.getElementById('repFields');
        var repToggle = document.getElementById('repToggle');

        function syncRep() {
            var checked = repCheck.checked;
            repHidden.value = checked ? '1' : '0';
            repFields.style.display = checked ? '' : 'none';
            repToggle.style.borderColor = checked ? 'var(--navy)' : '#e5e7eb';
            repToggle.style.background  = checked ? 'rgba(13,33,68,.04)' : '#f9fafb';
            // Clear errors on hide
            if (!checked) {
                ['requestor_name','requestor_relationship','requestor_contact'].forEach(function (f) {
                    var el = document.getElementById('err-' + f);
                    if (el) { el.style.display = 'none'; el.textContent = ''; }
                    var inp = document.getElementById(f);
                    if (inp) inp.style.borderColor = '';
                });
            }
        }
        repCheck.addEventListener('change', syncRep);
        syncRep(); // run on load
    });

    document.addEventListener('DOMContentLoaded', function () {

        /* Init Select2 */
        $('#document_type').select2({
            placeholder: '— Select document —',
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: false,
        });

        /* Pre-select from URL ?type= param */
        var params    = new URLSearchParams(window.location.search);
        var typeParam = params.get('type');
        if (typeParam) {
            $('#document_type').val(typeParam).trigger('change');
        }

        /* Clear Select2 border on change */
        $('#document_type').on('change', function () {
            var s2 = document.querySelector('.select2-selection--single');
            if (s2) s2.style.borderColor = '';
            var errEl = document.getElementById('err-document_type');
            if (errEl) { errEl.style.display = 'none'; errEl.textContent = ''; }
        });
    });

    /* ── Axios submit ── */
    document.getElementById('docRequestForm').addEventListener('submit', function (e) {
        e.preventDefault();
        clearErrors();

        var btn     = document.getElementById('submitBtn');
        var icon    = document.getElementById('submitIcon');
        var spinner = document.getElementById('submitSpinner');
        var label   = document.getElementById('submitLabel');

        btn.disabled       = true;
        icon.style.display = 'none';
        spinner.style.display = 'inline-block';
        label.textContent  = 'Submitting…';

        var fd = new FormData(document.getElementById('docRequestForm'));

        axios.post('{{ route('portal.store') }}', fd, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function (res) {
            if (res.data && res.data.redirect) {
                window.location.href = res.data.redirect;
            }
        })
        .catch(function (err) {
            btn.disabled       = false;
            icon.style.display = 'inline-block';
            spinner.style.display = 'none';
            label.textContent  = 'Submit Request';

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
