@extends('layouts.app')

@section('title', 'Issue Business Permit')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Issue Business Permit</h1>
        <p class="page-subtitle">Register a new business in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('businesses.store') }}">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Business Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Business Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_name" class="form-control"
                       value="{{ old('business_name') }}"
                       placeholder="e.g. Juan's Sari-Sari Store" required>
            </div>
            <div class="form-group">
                <label class="form-label">Business Type <span style="color:var(--crimson)">*</span></label>
                <select name="business_type" class="form-control" required>
                    <option value="">Select Type</option>
                    @foreach($businessTypes as $t)
                        <option value="{{ $t }}" {{ old('business_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Business Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="business_address" class="form-control"
                       value="{{ old('business_address') }}"
                       placeholder="Full address of business location" required>
            </div>
        </div>

        <div class="form-section-title">Owner Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Owner Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="owner_name" class="form-control"
                       value="{{ old('owner_name') }}" placeholder="Full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Owner Contact</label>
                <input type="text" name="owner_contact" class="form-control"
                       value="{{ old('owner_contact') }}" placeholder="09XX XXX XXXX">
            </div>
            <div class="form-group">
                <label class="form-label">Owner (Resident)</label>
                <select name="owner_resident_id" class="form-control">
                    <option value="">Select if registered resident</option>
                    @foreach($residents as $r)
                        <option value="{{ $r->id }}" {{ old('owner_resident_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-section-title">Permit Details</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Permit Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="permit_date" class="form-control"
                       value="{{ old('permit_date', date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Expiry Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="expiry_date" class="form-control"
                       value="{{ old('expiry_date', date('Y-m-d', strtotime('+1 year'))) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status','Active') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3"
                      placeholder="Optional notes...">{{ old('remarks') }}</textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Issue Permit
    </button>
    <a href="{{ route('businesses.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
