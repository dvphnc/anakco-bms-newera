@extends('layouts.app')

@section('title', 'Edit Resident')
@section('page-title', 'Residents')
@section('page-subtitle', 'Edit resident record')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Resident</h1>
        <p class="page-subtitle">{{ $resident->full_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('residents.show', $resident) }}" class="btn btn-secondary">
            <i class="fas fa-eye"></i> View Profile
        </a>
        <a href="{{ route('residents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('residents.update', $resident) }}" enctype="multipart/form-data">
@csrf @method('PUT')

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
                       value="{{ old('last_name', $resident->last_name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">First Name <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="first_name" class="form-control"
                       value="{{ old('first_name', $resident->first_name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Middle Name</label>
                <input type="text" name="middle_name" class="form-control"
                       value="{{ old('middle_name', $resident->middle_name) }}">
            </div>
        </div>

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Date of Birth <span style="color:var(--crimson-mid)">*</span></label>
                <input type="date" name="birthdate" class="form-control"
                       value="{{ old('birthdate', $resident->birthdate?->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Gender <span style="color:var(--crimson-mid)">*</span></label>
                <select name="gender" class="form-control" required>
                    @foreach(['Male','Female'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $resident->gender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Civil Status <span style="color:var(--crimson-mid)">*</span></label>
                <select name="civil_status" class="form-control" required>
                    @foreach(['Single','Married','Widowed','Separated','Annulled'] as $s)
                        <option value="{{ $s }}" {{ old('civil_status', $resident->civil_status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control"
                       value="{{ old('nationality', $resident->nationality) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control"
                       value="{{ old('religion', $resident->religion) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="{{ old('contact_number', $resident->contact_number) }}">
            </div>
        </div>

        <div class="form-section-title">Address</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson-mid)">*</span></label>
                <select name="purok_id" class="form-control" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id', $resident->purok_id) == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Household</label>
                <select name="household_id" class="form-control">
                    <option value="">No Household</option>
                    @foreach($households as $hh)
                        <option value="{{ $hh->id }}" {{ old('household_id', $resident->household_id) == $hh->id ? 'selected' : '' }}>
                            {{ $hh->household_number }} — {{ $hh->household_head }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Full Address <span style="color:var(--crimson-mid)">*</span></label>
                <input type="text" name="address" class="form-control"
                       value="{{ old('address', $resident->address) }}" required>
            </div>
        </div>

        <div class="form-section-title">Residency</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Residency Status</label>
                <select name="residency_status" class="form-control">
                    @foreach(['Active','Deceased','Transferred'] as $s)
                        <option value="{{ $s }}" {{ old('residency_status', $resident->residency_status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Years of Residency</label>
                <input type="number" name="years_of_residency" class="form-control"
                       value="{{ old('years_of_residency', $resident->years_of_residency) }}" min="0">
            </div>
            <div class="form-group">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control"
                       value="{{ old('occupation', $resident->occupation) }}">
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
                <input type="checkbox" name="is_voter" value="1"
                    {{ old('is_voter', $resident->is_voter) ? 'checked' : '' }}>
                <span><strong>Registered Voter</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_pwd" value="1"
                    {{ old('is_pwd', $resident->is_pwd) ? 'checked' : '' }}>
                <span><strong>Person with Disability (PWD)</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_senior" value="1"
                    {{ old('is_senior', $resident->is_senior) ? 'checked' : '' }}>
                <span><strong>Senior Citizen (60+)</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_solo_parent" value="1"
                    {{ old('is_solo_parent', $resident->is_solo_parent) ? 'checked' : '' }}>
                <span><strong>Solo Parent</strong></span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_4ps" value="1"
                    {{ old('is_4ps', $resident->is_4ps) ? 'checked' : '' }}>
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
        <div style="display:flex;align-items:flex-start;gap:24px">
            {{-- Current photo --}}
            @if($resident->photo_path)
            <div style="flex-shrink:0">
                <div style="font-size:11px;color:var(--text-muted);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em">Current Photo</div>
                <img src="{{ asset('storage/'.$resident->photo_path) }}"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
            </div>
            @endif
            <div class="form-group" style="flex:1">
                <label class="form-label">Upload New Photo</label>
                <input type="file" name="photo_path" class="form-control" accept="image/*">
                <div style="font-size:11px;color:var(--text-subtle);margin-top:4px">
                    Leave blank to keep the current photo.
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('residents.show', $resident) }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
