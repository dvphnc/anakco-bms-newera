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
                <label class="form-label">Last Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                       value="{{ old('last_name') }}" placeholder="e.g. Santos" required>
                @error('last_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">First Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name') }}" placeholder="e.g. Juan" required>
                @error('first_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Middle Name
                    <span class="help-icon" data-tippy-content="Enter the middle name as it appears on official IDs. Leave blank if the resident has none (e.g., illegitimate).">?</span>
                </label>
                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                       value="{{ old('middle_name') }}" placeholder="e.g. Dela Cruz">
                @error('middle_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Date of Birth <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                       value="{{ old('birthdate') }}" required>
                @error('birthdate')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Gender <span style="color:var(--crimson)">*</span></label>
                <select name="gender" id="s2Gender" class="form-control @error('gender') is-invalid @enderror" required>
                    <option value="">Select Gender</option>
                    <option value="Male"   {{ old('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('gender')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Civil Status <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Current marital status. 'Annulled' means a marriage was legally voided by court. This affects eligibility for some barangay programs.">?</span>
                </label>
                <select name="civil_status" id="s2CivilStatus" class="form-control @error('civil_status') is-invalid @enderror" required>
                    <option value="">Select Status</option>
                    @foreach(['Single','Married','Widowed','Separated','Annulled'] as $s)
                        <option value="{{ $s }}" {{ old('civil_status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('civil_status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror"
                       value="{{ old('nationality', 'Filipino') }}" placeholder="Filipino">
                @error('nationality')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror"
                       value="{{ old('religion') }}" placeholder="e.g. Roman Catholic">
                @error('religion')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                       value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX">
                @error('contact_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email_address" class="form-control @error('email_address') is-invalid @enderror"
                       value="{{ old('email_address') }}" placeholder="e.g. juan@email.com">
                @error('email_address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Address</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Purok <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the zone or neighborhood (Purok) where this resident currently lives. Contact the Barangay Secretary if you are unsure which Purok applies.">?</span>
                </label>
                <select name="purok_id" id="s2Purok" class="form-control @error('purok_id') is-invalid @enderror" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id') == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
                @error('purok_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            @include('residents.partials.relationship-field', ['resident' => null])
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Full Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address') }}"
                       placeholder="House No., Street, Barangay New Era, QC" required>
                @error('address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            @include('residents.partials.household-assignment', ['resident' => null])
        </div>

        <div class="form-section-title">Residency</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="New residents are recorded as Alive. To record a death or a move, open the resident's profile and use Update Status — it asks for the date so the history stays accurate.">?</span>
                </label>
                <div class="form-control" style="display:flex;align-items:center;gap:8px;background:var(--surface2)">
                    <span class="badge badge-green">Alive</span>
                    <span style="font-size:12px;color:var(--text-subtle)">New residents start as Alive</span>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">
                    Years of Residency
                    <span class="help-icon" data-tippy-content="How many years this person has been continuously living in Barangay New Era. Used for residency certificates. Enter 0 if newly arrived.">?</span>
                </label>
                <input type="number" name="years_of_residency" class="form-control @error('years_of_residency') is-invalid @enderror"
                       value="{{ old('years_of_residency') }}" min="0" placeholder="0">
                @error('years_of_residency')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control @error('occupation') is-invalid @enderror"
                       value="{{ old('occupation') }}" placeholder="e.g. Teacher, Vendor">
                @error('occupation')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

    </div>
</div>

{{-- Classifications --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-tags"></i> Classifications</span>
        <span class="help-icon" data-tippy-content="Check all that apply. Classifications help generate targeted reports and identify eligible residents for government programs like OSCA, PWD, and 4Ps benefits." style="font-size:13px;width:22px;height:22px">?</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px">
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
        @error('is_voter')<span class="invalid-feedback" style="margin-top:10px"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror

        @include('residents.partials.voter-details', ['resident' => null])
    </div>
</div>

{{-- Photo --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-camera"></i> Photo</span>
    </div>
    <div class="card-body">
        <div class="form-group" style="max-width:360px">
            <label class="form-label">Upload Photo <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
            {{-- Drop zone (idle state) --}}
            <label for="photoFileInput" id="photoDropZone" style="display:block;border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s">
                <i class="fas fa-camera" style="font-size:26px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:3px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div style="font-size:12px;color:var(--text-subtle)">JPG, PNG, WEBP — max 2MB</div>
            </label>
            <input type="file" id="photoFileInput" name="photo_path" accept="image/*" style="display:none">
            {{-- Selected state card --}}
            <div id="photoSelectedCard" style="display:none;border:2px solid var(--navy);border-radius:var(--radius);padding:12px 16px;align-items:center;gap:14px">
                <img id="photoDropPreview" src="" alt="" style="width:56px;height:56px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--border)">
                <div style="flex:1;min-width:0">
                    <div id="photoDropName" style="font-size:13px;font-weight:600;color:var(--navy);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                    <div style="font-size:11px;color:var(--text-subtle);margin-top:2px">Ready to upload</div>
                </div>
                <button type="button" onclick="clearResidentPhoto()" style="flex-shrink:0;background:none;border:1px solid var(--danger,#e53e3e);border-radius:var(--radius-sm,6px);color:var(--danger,#c0392b);font-size:12px;cursor:pointer;padding:5px 12px;white-space:nowrap"><i class="fas fa-times"></i> Remove</button>
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

@push('scripts')
<script>
$(function () {
    const s2 = { dropdownParent: $('body'), width: '100%' };

    $('#s2Gender').select2($.extend({}, s2, { placeholder: 'Select Gender', allowClear: false }));
    $('#s2CivilStatus').select2($.extend({}, s2, { placeholder: 'Select Status', allowClear: false }));

    /* Long lists — searchable */
    $('#s2Purok').select2($.extend({}, s2, {
        placeholder: 'Select Purok',
        allowClear: true
    }));
});

/* ── Photo drag-and-drop ── */
(function () {
    var zone  = document.getElementById('photoDropZone');
    var input = document.getElementById('photoFileInput');
    if (!zone || !input) return;

    /* click-to-browse is handled natively by <label for="photoFileInput"> — no JS click handler needed */

    zone.addEventListener('dragover', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = 'var(--navy)';
        zone.style.backgroundColor = 'rgba(13,33,68,.04)';
    });
    zone.addEventListener('dragleave', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
    });
    zone.addEventListener('drop', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
        var files = e.dataTransfer.files;
        if (files.length) { var dt = new DataTransfer(); dt.items.add(files[0]); input.files = dt.files; showResidentPhoto(files[0]); }
    });
    input.addEventListener('change', function () { if (input.files[0]) showResidentPhoto(input.files[0]); });

    function showResidentPhoto(file) {
        var reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('photoDropPreview').src = ev.target.result;
            document.getElementById('photoDropName').textContent = file.name;
            document.getElementById('photoDropZone').style.display = 'none';
            document.getElementById('photoSelectedCard').style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}());
function clearResidentPhoto() {
    document.getElementById('photoFileInput').value = '';
    document.getElementById('photoDropPreview').src = '';
    document.getElementById('photoDropZone').style.display = 'block';
    document.getElementById('photoSelectedCard').style.display = 'none';
}
</script>
@endpush
