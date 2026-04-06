@extends('layouts.app')
@section('title', 'Edit Business Permit')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Business Permit</h1>
        <p class="page-subtitle">{{ $business->permit_number }} — {{ $business->business_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.show', $business) }}" class="btn btn-secondary"><i class="fas fa-eye"></i> View</a>
        <a href="{{ route('businesses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
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
                <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $business->business_name) }}" required>
                @error('business_name')<span style="font-size:11px;color:var(--crimson);margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Business Type <span style="color:var(--crimson)">*</span></label>
                <select name="business_type" class="form-control" required>
                    @foreach($businessTypes as $t)
                        <option value="{{ $t }}" {{ old('business_type', $business->business_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Business Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_address" id="business_address" class="form-control" value="{{ old('business_address', $business->business_address) }}" required>
            </div>
        </div>

        <div class="form-section-title">Owner Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Owner Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="owner_name" id="owner_name" class="form-control" value="{{ old('owner_name', $business->owner_name) }}" required>
                @error('owner_name')<span style="font-size:11px;color:var(--crimson);margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Owner Contact</label>
                <input type="text" name="owner_contact" class="form-control" value="{{ old('owner_contact', $business->owner_contact) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Link to Resident <span style="font-size:10px;color:var(--text-subtle)">(optional)</span></label>
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
                <input type="date" name="permit_date" class="form-control" value="{{ old('permit_date', $business->permit_date ? \Carbon\Carbon::parse($business->permit_date)->format('Y-m-d') : '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date)->format('Y-m-d') : '') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $business->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
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
</script>
@endpush

@endsection