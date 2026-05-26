@extends('layouts.app')
@section('title', 'Edit Business Permit')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Business Permit</h1>
        <p class="page-subtitle">{{ $business->permit_number }} — {{ $business->business_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.show', $business) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ route('businesses.update', $business) }}">
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
        @php $currentStatus = old('status', $business->getRawOriginal('status')); @endphp
        <div class="form-grid-3 mb-6">

            {{-- Status always visible --}}
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

            {{-- Fee — always visible --}}
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

            {{-- OR Number — always visible --}}
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

        {{-- Permit Date + Expiry Date — only when status = Active --}}
        <div id="permitDatesGroup" class="form-grid-2 mb-6"
             style="{{ $currentStatus === 'Active' ? '' : 'display:none' }}">
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar-check" style="color:#16a34a;font-size:11px;margin-right:4px"></i>
                    Permit Date <span style="color:var(--crimson)">*</span>
                </label>
                <input type="date" name="permit_date"
                       class="form-control @error('permit_date') is-invalid @enderror"
                       value="{{ old('permit_date', $business->permit_date?->format('Y-m-d')) }}"
                       style="border-color:#86efac">
                @error('permit_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-calendar-xmark" style="color:#dc2626;font-size:11px;margin-right:4px"></i>
                    Expiry Date <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Most permits are issued for 1 year. The system alerts you 30 days before expiry.">?</span>
                </label>
                <input type="date" name="expiry_date"
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
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Changes</button>
    <a href="{{ route('businesses.show', $business) }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<script>
$('#owner_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#owner_name').val(fullName);
});
$('#owner_resident_id').on('select2:clear', function() {
    $('#owner_name').val('');
});
</script>
@endpush

@endsection
