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
                <label class="form-label">Last Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                       value="{{ old('last_name', $resident->last_name) }}" required>
                @error('last_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">First Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                       value="{{ old('first_name', $resident->first_name) }}" required>
                @error('first_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Middle Name
                    <span class="help-icon" data-tippy-content="Enter the middle name as it appears on official IDs. Leave blank if the resident has none (e.g., illegitimate).">?</span>
                </label>
                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror"
                       value="{{ old('middle_name', $resident->middle_name) }}">
                @error('middle_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Date of Birth <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror"
                       value="{{ old('birthdate', $resident->birthdate?->format('Y-m-d')) }}" required>
                @error('birthdate')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Gender <span style="color:var(--crimson)">*</span></label>
                <select name="gender" id="s2Gender" class="form-control @error('gender') is-invalid @enderror" required>
                    @foreach(['Male','Female'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $resident->gender) === $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
                @error('gender')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Civil Status <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Choose 'Annulled' if the marriage was legally annulled — not 'Single'. This affects eligibility for certain programs.">?</span>
                </label>
                <select name="civil_status" id="s2CivilStatus" class="form-control @error('civil_status') is-invalid @enderror" required>
                    @foreach(['Single','Married','Widowed','Separated','Annulled'] as $s)
                        <option value="{{ $s }}" {{ old('civil_status', $resident->civil_status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('civil_status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nationality</label>
                <input type="text" name="nationality" class="form-control @error('nationality') is-invalid @enderror"
                       value="{{ old('nationality', $resident->nationality) }}">
                @error('nationality')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Religion</label>
                <input type="text" name="religion" class="form-control @error('religion') is-invalid @enderror"
                       value="{{ old('religion', $resident->religion) }}">
                @error('religion')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                       value="{{ old('contact_number', $resident->contact_number) }}">
                @error('contact_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email_address" class="form-control @error('email_address') is-invalid @enderror"
                       value="{{ old('email_address', $resident->email_address) }}" placeholder="e.g. juan@email.com">
                @error('email_address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Address</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Purok <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the Purok (zone/neighborhood) where the resident currently lives. Contact the Secretary if you're unsure which Purok an address falls under.">?</span>
                </label>
                <select name="purok_id" id="s2Purok" class="form-control @error('purok_id') is-invalid @enderror" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id', $resident->purok_id) == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
                @error('purok_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Household
                    <span class="help-icon" data-tippy-content="Optional. Link this resident to a registered household to track family size and relationships. Leave blank if the household is not yet registered.">?</span>
                </label>
                <select name="household_id" id="s2Household" class="form-control @error('household_id') is-invalid @enderror">
                    <option value="">No Household</option>
                    @foreach($households as $hh)
                        <option value="{{ $hh->id }}" {{ old('household_id', $resident->household_id) == $hh->id ? 'selected' : '' }}>
                            {{ $hh->household_number }} — {{ $hh->household_head }}
                        </option>
                    @endforeach
                </select>
                @error('household_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label">Full Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address', $resident->address) }}" required>
                @error('address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Residency</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Residency Status
                    <span class="help-icon" data-tippy-content="'Active' = currently residing in the barangay. 'Transferred' = moved to another area. 'Deceased' = has passed away. Changing this affects who appears in active resident counts.">?</span>
                </label>
                <select name="residency_status" id="s2ResidencyStatus" class="form-control @error('residency_status') is-invalid @enderror">
                    @foreach(['Active','Deceased','Transferred'] as $s)
                        <option value="{{ $s }}" {{ old('residency_status', $resident->residency_status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('residency_status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Years of Residency
                    <span class="help-icon" data-tippy-content="Total number of continuous years the resident has lived in Barangay New Era. Required for Barangay Clearance and other certificates.">?</span>
                </label>
                <input type="number" name="years_of_residency" class="form-control @error('years_of_residency') is-invalid @enderror"
                       value="{{ old('years_of_residency', $resident->years_of_residency) }}" min="0">
                @error('years_of_residency')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Occupation</label>
                <input type="text" name="occupation" class="form-control @error('occupation') is-invalid @enderror"
                       value="{{ old('occupation', $resident->occupation) }}">
                @error('occupation')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
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
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:6px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em">Current Photo</div>
                <img src="{{ asset('storage/'.$resident->photo_path) }}"
                     id="current-photo-preview"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                <div style="margin-top:10px">
                    <label style="display:inline-flex;align-items:center;gap:7px;cursor:pointer;
                                  font-size:13px;color:var(--danger,#c0392b);user-select:none">
                        <input type="checkbox" name="remove_photo" value="1" id="remove-photo-cb"
                               style="accent-color:var(--danger,#c0392b);width:15px;height:15px;cursor:pointer"
                               {{ old('remove_photo') ? 'checked' : '' }}>
                        Remove photo
                    </label>
                </div>
            </div>
            @endif
            <div class="form-group" style="max-width:260px">
                <label class="form-label">Upload New Photo</label>
                {{-- Drop zone (idle state) --}}
                <label for="photo-upload" id="photoDropZone" style="display:block;border:2px dashed var(--border);border-radius:var(--radius);padding:18px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s">
                    <i class="fas fa-camera" style="font-size:22px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                    <div style="font-size:13px;color:var(--text-muted);margin-bottom:3px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                    <div style="font-size:12px;color:var(--text-subtle)">JPG, PNG, WEBP — max 2MB</div>
                </label>
                <input type="file" name="photo_path" id="photo-upload" accept="image/*" style="display:none">
                {{-- Selected state card (shown when a file is chosen) --}}
                <div id="photoSelectedCard" style="display:none;border:2px solid var(--navy);border-radius:var(--radius);padding:12px 16px;align-items:center;gap:14px">
                    <img id="photoDropPreview" src="" alt="" style="width:56px;height:56px;border-radius:50%;object-fit:cover;flex-shrink:0;border:2px solid var(--border)">
                    <div style="flex:1;min-width:0">
                        <div id="photoDropName" style="font-size:13px;font-weight:600;color:var(--navy);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"></div>
                        <div style="font-size:11px;color:var(--text-subtle);margin-top:2px">Ready to upload</div>
                    </div>
                    <button type="button" onclick="clearResidentPhoto()" style="flex-shrink:0;background:none;border:1px solid var(--danger,#e53e3e);border-radius:var(--radius-sm,6px);color:var(--danger,#c0392b);font-size:12px;cursor:pointer;padding:5px 12px;white-space:nowrap"><i class="fas fa-times"></i> Remove</button>
                </div>
                <div style="font-size:11px;color:var(--text-subtle);margin-top:6px"><i class="fas fa-circle-info" style="color:var(--navy);opacity:0.4"></i> Leave blank to keep the current photo.</div>
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

@push('scripts')
<script>
$(function () {
    /* ── Photo remove/upload mutual exclusion ── */
    var $removeCb   = $('#remove-photo-cb');
    var $photoInput = $('#photo-upload');
    if ($removeCb.length && $photoInput.length) {
        $removeCb.on('change', function () {
            if (this.checked) {
                clearResidentPhoto();
                $('#current-photo-preview').css('opacity', '0.35');
            } else {
                $('#current-photo-preview').css('opacity', '1');
            }
        });
        /* showResidentPhoto is called by the native change listener in the IIFE below */
        $photoInput.on('change', function () {
            if (this.value) $removeCb.prop('checked', false).trigger('change');
        });
    }

    const s2 = { dropdownParent: $('body'), width: '100%' };

    $('#s2Gender').select2($.extend({}, s2, { placeholder: 'Select Gender', allowClear: false }));
    $('#s2CivilStatus').select2($.extend({}, s2, { placeholder: 'Select Status', allowClear: false }));
    $('#s2ResidencyStatus').select2($.extend({}, s2, { placeholder: 'Select Status', allowClear: false }));

    /* Long lists — searchable */
    $('#s2Purok').select2($.extend({}, s2, {
        placeholder: 'Select Purok',
        allowClear: true
    }));
    $('#s2Household').select2($.extend({}, s2, {
        placeholder: 'No Household',
        allowClear: true
    }));
});

/* ── Photo drag-and-drop ── */
(function () {
    var zone  = document.getElementById('photoDropZone');
    var input = document.getElementById('photo-upload');
    if (!zone || !input) return;

    /* click-to-browse handled natively by <label for="photo-upload"> */

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
        if (files.length) {
            var dt = new DataTransfer(); dt.items.add(files[0]); input.files = dt.files;
            showResidentPhoto(files[0]);
            var cb = document.getElementById('remove-photo-cb');
            if (cb && cb.checked) { cb.checked = false; var prev = document.getElementById('current-photo-preview'); if (prev) prev.style.opacity = ''; }
        }
    });
    /* Unconditional change handler — not guarded by remove-photo-cb existence */
    input.addEventListener('change', function () {
        if (!this.files[0]) return;
        var cb = document.getElementById('remove-photo-cb');
        if (cb && cb.checked) { cb.checked = false; var prev = document.getElementById('current-photo-preview'); if (prev) prev.style.opacity = ''; }
        showResidentPhoto(this.files[0]);
    });
}());

function showResidentPhoto(file) {
    var reader = new FileReader();
    reader.onload = function (ev) {
        document.getElementById('photoDropPreview').src = ev.target.result;
        document.getElementById('photoDropPreview').style.display = 'block';
        document.getElementById('photoDropIcon').style.display = 'none';
        document.getElementById('photoDropHint').style.display = 'none';
        document.getElementById('photoDropSub').style.display  = 'none';
        document.getElementById('photoDropName').textContent = '✔ ' + file.name;
        document.getElementById('photoDropName').style.display = 'block';
        document.getElementById('photoClearBtn').style.display = 'block';
        var z = document.getElementById('photoDropZone');
        z.style.borderStyle = 'solid'; z.style.borderColor = 'var(--navy)';
    };
    reader.readAsDataURL(file);
}
function clearResidentPhoto() {
    var input = document.getElementById('photo-upload');
    if (input) input.value = '';
    document.getElementById('photoDropPreview').style.display = 'none';
    document.getElementById('photoDropPreview').src = '';
    document.getElementById('photoDropIcon').style.display = 'block';
    document.getElementById('photoDropHint').style.display = 'block';
    document.getElementById('photoDropSub').style.display  = 'block';
    document.getElementById('photoDropName').style.display = 'none';
    document.getElementById('photoClearBtn').style.display = 'none';
    var z = document.getElementById('photoDropZone');
    z.style.borderStyle = ''; z.style.borderColor = '';
}
</script>
@endpush
