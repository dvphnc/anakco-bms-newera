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
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Permit Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="permit_date" class="form-control @error('permit_date') is-invalid @enderror" value="{{ old('permit_date', $business->permit_date ? \Carbon\Carbon::parse($business->permit_date)->format('Y-m-d') : '') }}" required>
                @error('permit_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Expiry Date <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="The date this permit becomes invalid. The system will automatically alert you 30 days before expiry and mark overdue permits in red. Most permits are issued for 1 year.">?</span>
                </label>
                <input type="date" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" value="{{ old('expiry_date', $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date)->format('Y-m-d') : '') }}" required>
                @error('expiry_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Active' = currently operating with valid permit. 'Expired' = permit past its expiry date. 'Suspended' = temporarily stopped by Barangay order. 'Cancelled' = permit revoked.">?</span>
                </label>
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $business->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $business->remarks) }}</textarea>
        </div>
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
