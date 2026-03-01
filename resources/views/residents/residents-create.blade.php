@extends('layouts.app')

@section('title', 'Register Resident')
@section('page-title', 'Residents')
@section('page-subtitle', 'Register a new resident')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Register Resident</h1>
        <p class="page-subtitle">Add a new resident to Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('residents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<form method="POST" action="{{ route('residents.store') }}" enctype="multipart/form-data">
@csrf

{{-- Personal Information --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user"></i> Personal Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Full Name</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Last Name <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="last_name" class="form-control"
                       value="{{ old('last_name') }}" placeholder="e.g. Santos" required>
            </div>
            <div class="form-group">
                <label class="form-label">First Name <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="first_name" class="form-control"
                       value="{{ old('first_name') }}" placeholder="e.g. Juan" required>
            </div>
            <div class="form-group">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control"
                       value="{{ old('middle_name') }}" placeholder="e.g. Dela Cruz">
            </div>
        </div>

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Date of Birth <span style="color:var(--crimson-mid)">*</span></label>
                <input type="date" name="birthdate" class="form-control"
                       value="{{ old('birthdate') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Gender <span style="color:var(--crimson-mid)">*</span></label>
                <select name="gender" class="form-control" required>
                    <option value="">Select Gender</option>
                    <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Civil Status <span style="color:var(--crimson-mid)">*</span></label>
                <select name="civil_status" class="form-control" required>
                    <option value="">Select Status</option>
                    @foreach(['Single','Married','Widowed','Separated','Annulled'] as $s)
                        <option value="{{ $s }}" {{ old('civil_status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control"
                       value="{{ old('nationality', 'Filipino') }}" placeholder="Filipino">
            </div>
            <div class="form-group">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control"
                       value="{{ old('religion') }}" placeholder="e.g. Roman Catholic">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX">
            </div>
        </div>

        <div class="form-section-title">Address</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson-mid)">*</span></label>
                <select name="purok_id" class="form-control" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id') == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Household</label>
                <select name="household_id" class="form-control">
                    <option value="">Select Household (optional)</option>
                    @foreach($households as $hh)
                        <option value="{{ $hh->id }}" {{ old('household_id') == $hh->id ? 'selected' : '' }}>
                            {{ $hh->household_number }} — {{ $hh->household_head }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Full Address <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="address" class="form-control"
                       value="{{ old('address') }}"
                       placeholder="House No., Street, Barangay New Era, QC" required>
            </div>
        </div>

        <div class="form-section-title">Residency</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Residency Status</label>
                <select name="residency_status" class="form-control">
                    @foreach(['Active','Deceased','Transferred'] as $s)
                        <option value="{{ $s }}" {{ old('residency_status', 'Active') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Years of Residency</label>
                <input type="number" name="years_of_residency" class="form-control"
                       value="{{ old('years_of_residency') }}" min="0" placeholder="0">
            </div>
            <div class="form-group">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control"
                       value="{{ old('occupation') }}" placeholder="e.g. Teacher, Vendor">
            </div>
        </div>

    </div>
</div>

{{-- Classifications --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-tags"></i> Classifications</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px">
            <label class="form-check">
                <input type="checkbox" name="is_voter" value="1" {{ old('is_voter') ? 'checked' : '' }}>
                <span><strong>Registered Voter</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_pwd" value="1" {{ old('is_pwd') ? 'checked' : '' }}>
                <span><strong>Person with Disability (PWD)</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_senior" value="1" {{ old('is_senior') ? 'checked' : '' }}>
                <span><strong>Senior Citizen (60+)</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_solo_parent" value="1" {{ old('is_solo_parent') ? 'checked' : '' }}>
                <span><strong>Solo Parent</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_4ps" value="1" {{ old('is_4ps') ? 'checked' : '' }}>
                <span><strong>4Ps Beneficiary</strong></span>
            </label>
        </div>
    </div>
</div>

{{-- Photo --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-camera"></i> Photo</span>
    </div>
    <div class="card-body">
        <div class="form-group" style="max-width:360px">
            <label class="form-label">Upload Photo (optional)</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
            <div style="font-size:11px;color:var(--text-subtle);margin-top:4px">
                JPG, PNG or WEBP. Max 2MB.
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Register Resident
    </button>
    <a href="{{ route('residents.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
