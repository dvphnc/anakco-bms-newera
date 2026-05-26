@extends('layouts.app')
@section('title', 'Edit Business Permit')
@section('content')

@php $currentStatus = old('status', $business->getRawOriginal('status')); @endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Business Permit</h1>
        <p class="page-subtitle">{{ $business->permit_number }} — {{ $business->business_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.show', $business) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

{{-- ── Issue Permit CTA Banner (Pending / For Review only) ──────────── --}}
@if(in_array($currentStatus, ['Pending', 'For Review']))
<div id="issuePermitBanner"
     style="display:flex;align-items:center;gap:14px;
            background:linear-gradient(135deg,#064e3b,#065f46);
            border-radius:var(--radius);padding:14px 20px;margin-bottom:20px;
            border:1px solid #059669;color:#fff">
    <div style="width:44px;height:44px;border-radius:var(--radius-sm);
                background:rgba(255,255,255,0.12);display:flex;align-items:center;
                justify-content:center;flex-shrink:0">
        <i class="fas fa-stamp" style="font-size:20px;color:#6ee7b7"></i>
    </div>
    <div style="flex:1;min-width:0">
        <div style="font-weight:700;font-size:14px;margin-bottom:2px">Ready to issue this permit?</div>
        <div style="font-size:12.5px;color:rgba(255,255,255,0.75)">
            Status is currently <strong>{{ $currentStatus }}</strong>.
            Click <em>Issue Permit</em> to set it Active with today's date and a 1-year validity.
        </div>
    </div>
    <button type="button" id="issuePermitBtn" onclick="issuePermitNow()"
            style="flex-shrink:0;background:#059669;color:#fff;border:1.5px solid #6ee7b7;
                   border-radius:var(--radius-sm);padding:8px 20px;font-size:13px;font-weight:700;
                   cursor:pointer;transition:background .15s;white-space:nowrap;
                   display:inline-flex;align-items:center;gap:7px">
        <i class="fas fa-stamp" style="font-size:11px"></i> Issue Permit
    </button>
</div>
@endif

<form method="POST" action="{{ route('businesses.update', $business) }}" id="bizEditForm">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Information</span>
        <span class="td-mono">{{ $business->permit_number }}</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Business Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Business Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_name" class="form-control @error('business_name') is-invalid @enderror" value="{{ old('business_name', $business->business_name) }}" required>
                @error('business_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Business Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the category that best describes what this business does. Choose 'Other' if none applies — you can add more detail in Remarks.">?</span>
                </label>
                <select name="business_type" class="form-control @error('business_type') is-invalid @enderror" required>
                    @foreach($businessTypes as $t)
                        <option value="{{ $t }}" {{ old('business_type', $business->business_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('business_type')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Business Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_address" id="business_address" class="form-control @error('business_address') is-invalid @enderror" value="{{ old('business_address', $business->business_address) }}" required>
                @error('business_address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Owner Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Owner Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="owner_name" id="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name', $business->owner_name) }}" required>
                @error('owner_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Owner Contact</label>
                <input type="text" name="owner_contact" class="form-control @error('owner_contact') is-invalid @enderror" value="{{ old('owner_contact', $business->owner_contact) }}">
                @error('owner_contact')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Link to Resident
                    <span class="help-icon" data-tippy-content="Optional. Search for the owner in the resident registry. Selecting a resident will automatically fill in their name. Useful for tracking which residents own businesses.">?</span>
                </label>
                <select name="owner_resident_id" id="owner_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    @if($business->owner_resident_id)
                        @php $or = \App\Models\Resident::find(old('owner_resident_id', $business->owner_resident_id)); @endphp
                        @if($or)<option value="{{ $or->id }}" selected>{{ $or->last_name }}, {{ $or->first_name }} — {{ $or->address }}</option>@endif
                    @endif
                </select>
            </div>
        </div>

        <div class="form-section-title">Permit Details</div>
        <div class="form-grid-3 mb-6">

            {{-- Status --}}
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Pending' = awaiting review. 'For Review' = under staff assessment. 'Active' = permit issued. 'Expired' = past expiry. 'Suspended' = temporarily halted. 'Cancelled' = permit revoked.">?</span>
                </label>
                <select name="status" id="statusSelect" class="form-control @error('status') is-invalid @enderror">
                    @foreach(['Pending','For Review','Active','Expired','Suspended','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ $currentStatus === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>

            {{-- Fee --}}
            <div class="form-group">
                <label class="form-label">
                    Fee (₱)
                    <span class="help-icon" data-tippy-content="Permit fee collected. OR Number will be auto-generated when fee is greater than 0.">?</span>
                </label>
                <input type="number" id="feeInput" name="fee_paid"
                       class="form-control @error('fee_paid') is-invalid @enderror"
                       value="{{ old('fee_paid', $business->fee_paid ?? 0) }}"
                       min="0" step="0.01">
                @error('fee_paid')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>

            {{-- OR Number --}}
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;justify-content:space-between">
                    <span>
                        OR Number
                        <span class="help-icon" data-tippy-content="Official Receipt number. Leave blank to auto-generate when fee is greater than 0.">?</span>
                    </span>
                    <button type="button" id="orAutoBtn"
                            style="display:none;font-size:11.5px;font-weight:600;color:var(--navy);
                                   background:rgba(13,33,68,0.07);border:1px solid rgba(13,33,68,0.18);
                                   border-radius:4px;padding:2px 8px;cursor:pointer;transition:.15s"
                            onclick="autoFillOrNumber()">
                        <i class="fas fa-wand-magic-sparkles" style="font-size:10px"></i> Auto-fill
                    </button>
                </label>
                <div style="position:relative">
                    <input type="text" id="orInput" name="or_number"
                           class="form-control @error('or_number') is-invalid @enderror"
                           placeholder="e.g. OR-2026-00001"
                           value="{{ old('or_number', $business->or_number) }}">
                    <span id="orAutoHint"
                          style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                 font-size:11px;color:#9ca3af;pointer-events:none">
                        auto-generate on save
                    </span>
                </div>
                @error('or_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Permit Date + Expiry Date — visible when status needs dates (Active/Expired/Suspended/Cancelled) --}}
        @php $showDates = in_array($currentStatus, ['Active','Expired','Suspended','Cancelled']); @endphp
        <div id="permitDatesGroup" class="form-grid-2 mb-6"
             style="{{ $showDates ? '' : 'display:none' }}">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar-check" style="color:#16a34a;font-size:11px;margin-right:4px"></i>
                    Permit Date <span id="permitDateRequired" style="color:var(--crimson)">*</span>
                </label>
                <input type="date" id="permitDateInput" name="permit_date"
                       class="form-control @error('permit_date') is-invalid @enderror"
                       value="{{ old('permit_date', $business->permit_date?->format('Y-m-d')) }}"
                       style="border-color:#86efac">
                @error('permit_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar-xmark" style="color:#dc2626;font-size:11px;margin-right:4px"></i>
                    Expiry Date <span id="expiryDateRequired" style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Most permits are issued for 1 year. The system alerts you 30 days before expiry.">?</span>
                </label>
                <input type="date" id="expiryDateInput" name="expiry_date"
                       class="form-control @error('expiry_date') is-invalid @enderror"
                       value="{{ old('expiry_date', $business->expiry_date?->format('Y-m-d')) }}"
                       style="border-color:#86efac">
                @error('expiry_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $business->remarks) }}</textarea>
        </div>

        {{-- ── Portal Message — portal applications only ── --}}
        @if($business->source === 'portal')
        <div id="portalMessageSection">
            <div class="form-section-title">
                <i class="fas fa-comment-dots" style="font-size:12px;margin-right:5px;color:var(--gold)"></i>
                Message to Applicant
            </div>
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:var(--radius-sm);
                        padding:12px 14px;margin-bottom:12px;font-size:12.5px;color:#92400e;
                        display:flex;align-items:flex-start;gap:8px">
                <i class="fas fa-circle-info" style="flex-shrink:0;margin-top:1px"></i>
                <span>This is a <strong>portal application</strong>. Any message you enter below will be sent to the applicant by email and appear in their portal tracker when the status changes.</span>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label">
                    Message <span style="font-weight:400;color:var(--text-subtle);font-size:12px">— optional, only sent when status changes</span>
                </label>
                <textarea name="status_message" id="statusMessageArea" class="form-control" rows="3"
                          placeholder="e.g. Your permit application is now under review. We will contact you within 3–5 business days.">{{ old('status_message') }}</textarea>
            </div>
        </div>
        @endif

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary" id="bizEditSubmitBtn">
        <span id="bizEditLabel">
            <i class="fas fa-floppy-disk"></i>
            <span id="saveBtnText">Save Changes</span>
        </span>
        <span id="bizEditSpinner" style="display:none"><span class="biz-spin-icon"></span> Saving…</span>
    </button>
    <a href="{{ route('businesses.show', $business) }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<style>
@keyframes biz-spin { to { transform: rotate(360deg); } }
.biz-spin-icon {
    display: inline-block;
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: biz-spin 0.7s linear infinite;
    vertical-align: middle;
    margin-right: 4px;
}
#orAutoBtn:hover      { background: rgba(13,33,68,.14) !important; }
#issuePermitBtn:hover { background: #047857 !important; }
</style>
<script>
(function () {
    var _generateOrUrl = '{{ route('documents.generateOrNumber') }}';
    var statusSel   = document.getElementById('statusSelect');
    var datesGroup  = document.getElementById('permitDatesGroup');
    var permitInput = document.getElementById('permitDateInput');
    var expiryInput = document.getElementById('expiryDateInput');
    var msgArea     = document.getElementById('statusMessageArea');
    var origStatus  = '{{ $business->getRawOriginal('status') }}';
    var DATES_STATUSES = ['Active','Expired','Suspended','Cancelled'];

    /* ── Submit spinner ─────────────────────────────────────── */
    document.getElementById('bizEditForm').addEventListener('submit', function () {
        document.getElementById('bizEditLabel').style.display   = 'none';
        document.getElementById('bizEditSpinner').style.display = '';
        document.getElementById('bizEditSubmitBtn').disabled = true;
    });

    /* ── OR auto-generate logic ─────────────────────────────── */
    var feeInput = document.getElementById('feeInput');
    var orInput  = document.getElementById('orInput');
    var orBtn    = document.getElementById('orAutoBtn');
    var orHint   = document.getElementById('orAutoHint');

    function syncOrUi() {
        var fee   = parseFloat(feeInput ? feeInput.value : 0) || 0;
        var orVal = orInput ? orInput.value.trim() : '';
        if (fee > 0 && !orVal) {
            if (orBtn)  orBtn.style.display  = 'inline-block';
            if (orHint) orHint.style.display = 'block';
            if (orInput) orInput.placeholder = 'Will be auto-generated on save';
        } else {
            if (orBtn)  orBtn.style.display  = 'none';
            if (orHint) orHint.style.display = 'none';
            if (orInput && !orInput.value.trim()) orInput.placeholder = 'e.g. OR-2026-00001';
        }
    }
    if (feeInput) feeInput.addEventListener('input', syncOrUi);
    if (orInput)  orInput.addEventListener('input',  syncOrUi);
    syncOrUi();

    window.autoFillOrNumber = function () {
        if (orBtn) { orBtn.disabled = true; orBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i>'; }
        axios.get(_generateOrUrl)
            .then(function (res) {
                if (res.data.or_number) {
                    orInput.value = res.data.or_number;
                    orInput.dispatchEvent(new Event('input'));
                    orInput.classList.add('is-valid');
                    setTimeout(function () { orInput.classList.remove('is-valid'); }, 2000);
                }
            })
            .catch(function () { bmsToast('Could not generate OR number — please enter manually.', 'error'); })
            .finally(function () {
                if (orBtn) { orBtn.disabled = false; orBtn.innerHTML = '<i class="fas fa-wand-magic-sparkles" style="font-size:10px"></i> Auto-fill'; }
            });
    };

    /* ── Status-dependent permit dates + save button label ──── */
    function syncStatusUi() {
        if (!statusSel) return;
        var s = statusSel.value;
        var showDates = DATES_STATUSES.indexOf(s) !== -1;
        if (datesGroup) datesGroup.style.display = showDates ? '' : 'none';

        // Update save button text
        var saveTxt = document.getElementById('saveBtnText');
        if (saveTxt) {
            if (s === 'Active' && origStatus !== 'Active') {
                saveTxt.textContent = 'Issue Permit';
                document.getElementById('bizEditLabel').innerHTML =
                    '<i class="fas fa-stamp"></i> Issue Permit';
            } else {
                document.getElementById('bizEditLabel').innerHTML =
                    '<i class="fas fa-floppy-disk"></i> Save Changes';
            }
        }
    }

    if (statusSel) {
        statusSel.addEventListener('change', function () {
            syncStatusUi();
            // Auto-focus message area when status changes (portal only)
            if (msgArea && statusSel.value !== origStatus) {
                msgArea.closest('.form-group').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                setTimeout(function () { msgArea.focus(); }, 250);
            }
        });
    }
    syncStatusUi();

    /* ── Issue Permit Now (banner button) ───────────────────── */
    window.issuePermitNow = function () {
        // 1. Switch status to Active
        if (statusSel) {
            statusSel.value = 'Active';
            statusSel.dispatchEvent(new Event('change'));
        }

        // 2. Auto-fill permit_date = today, expiry_date = +1 year
        var today = new Date();
        var pad   = function (n) { return String(n).padStart(2, '0'); };
        var todayStr = today.getFullYear() + '-' + pad(today.getMonth() + 1) + '-' + pad(today.getDate());
        var nextYear = new Date(today);
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        var nextYearStr = nextYear.getFullYear() + '-' + pad(nextYear.getMonth() + 1) + '-' + pad(nextYear.getDate());

        if (permitInput && !permitInput.value) permitInput.value = todayStr;
        if (expiryInput && !expiryInput.value) expiryInput.value = nextYearStr;

        // 3. Hide the banner
        var banner = document.getElementById('issuePermitBanner');
        if (banner) banner.style.display = 'none';

        // 4. Scroll to and highlight the dates
        if (datesGroup) {
            datesGroup.style.display = '';
            datesGroup.scrollIntoView({ behavior: 'smooth', block: 'center' });
            [permitInput, expiryInput].forEach(function (el) {
                if (!el) return;
                el.style.transition = 'box-shadow .3s';
                el.style.boxShadow  = '0 0 0 3px rgba(5,150,105,0.35)';
                setTimeout(function () { el.style.boxShadow = ''; }, 1800);
            });
        }

        bmsToast('Permit dates pre-filled — review and click Issue Permit to save.', 'success');
    };

    /* ── Owner resident auto-fill ─────────────────────────────── */
    $('#owner_resident_id').on('select2:select', function(e) {
        var text  = e.params.data.text;
        var parts = text.split(' — ');
        var namePart  = parts[0].trim();
        var nameParts = namePart.split(', ');
        var fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
        $('#owner_name').val(fullName);
    });
    $('#owner_resident_id').on('select2:clear', function() {
        $('#owner_name').val('');
    });

}());
</script>
@endpush

@endsection
