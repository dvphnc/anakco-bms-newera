@extends('layouts.app')
@section('title', 'Edit Blotter Case')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Blotter Case</h1>
        <p class="page-subtitle">{{ $blotter->case_number }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.show', $blotter) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ route('blotter.update', $blotter) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-exclamation-triangle"></i> Incident Details</span>
        <span class="td-mono">{{ $blotter->case_number }}</span>
    </div>
    <div class="card-body">
        <div class="form-section-title">Incident Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Incident Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Select the category that best describes the complaint or incident. Choose 'Other' if none of the options fit, and describe fully in the Incident Details field below.">?</span>
                </label>
                <select name="incident_type" class="form-control @error('incident_type') is-invalid @enderror" required>
                    @foreach(['Noise Complaint','Physical Assault','Verbal Abuse','Theft','Trespassing','Domestic Dispute','Property Damage','Threat','Other'] as $t)
                        <option value="{{ $t }}" {{ old('incident_type', $blotter->incident_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('incident_type')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Incident Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror" value="{{ old('incident_date', $blotter->incident_date?->format('Y-m-d')) }}" required>
                @error('incident_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Active' = newly filed. 'Under Investigation' = Barangay is looking into it. 'Mediated' = parties have met. 'Settled' = issue resolved. 'Referred' = escalated to police or court.">?</span>
                </label>
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    @foreach(['Active','Under Investigation','Mediated','Settled','Closed','Referred to Higher Authority'] as $s)
                        <option value="{{ $s }}" {{ old('status', $blotter->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">
                Incident Location <span style="color:var(--crimson)">*</span>
                <span class="help-icon" data-tippy-content="Be as specific as possible. Include the Purok number and a landmark. Example: 'Purok 3, in front of Aling Nena's store near the basketball court.'">?</span>
            </label>
            <input type="text" name="incident_location" class="form-control @error('incident_location') is-invalid @enderror" value="{{ old('incident_location', $blotter->incident_location) }}" required>
            @error('incident_location')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">
                Incident Details <span style="color:var(--crimson)">*</span>
                <span class="help-icon" data-tippy-content="Write a factual, detailed account of what happened. Include the date and time, what was said or done, and who was present. This becomes the official record.">?</span>
            </label>
            <textarea name="incident_details" class="form-control @error('incident_details') is-invalid @enderror" rows="5" required>{{ old('incident_details', $blotter->incident_details) }}</textarea>
            @error('incident_details')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-users"></i> Parties Involved</span>
    </div>
    <div class="card-body">
        <div class="form-section-title">Complainant</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Complainant Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="complainant_name" id="complainant_name" class="form-control @error('complainant_name') is-invalid @enderror" value="{{ old('complainant_name', $blotter->complainant_name) }}" required>
                @error('complainant_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident <span style="font-size:12px;color:var(--text-subtle)">(optional — auto-fills name)</span></label>
                <select name="complainant_resident_id" id="complainant_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    @if($blotter->complainant_resident_id)
                        @php $cr = \App\Models\Resident::find(old('complainant_resident_id', $blotter->complainant_resident_id)); @endphp
                        @if($cr)<option value="{{ $cr->id }}" selected>{{ $cr->last_name }}, {{ $cr->first_name }} — {{ $cr->address }}</option>@endif
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Address</label>
                <input type="text" name="complainant_address" id="complainant_address" class="form-control" value="{{ old('complainant_address', $blotter->complainant_address) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Contact</label>
                <input type="text" name="complainant_contact" class="form-control" value="{{ old('complainant_contact', $blotter->complainant_contact) }}">
            </div>
        </div>

        <div class="form-section-title">Accused</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Accused Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="respondent_name" id="respondent_name" class="form-control @error('respondent_name') is-invalid @enderror" value="{{ old('respondent_name', $blotter->respondent_name) }}" required>
                @error('respondent_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident <span style="font-size:12px;color:var(--text-subtle)">(optional — auto-fills name)</span></label>
                <select name="respondent_resident_id" id="respondent_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    @if(isset($blotter->respondent_resident_id) && $blotter->respondent_resident_id)
                        @php $rr = \App\Models\Resident::find(old('respondent_resident_id', $blotter->respondent_resident_id)); @endphp
                        @if($rr)<option value="{{ $rr->id }}" selected>{{ $rr->last_name }}, {{ $rr->first_name }} — {{ $rr->address }}</option>@endif
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Accused Address</label>
                <input type="text" name="respondent_address" id="respondent_address" class="form-control" value="{{ old('respondent_address', $blotter->respondent_address) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Accused Contact</label>
                <input type="text" name="respondent_contact" class="form-control" value="{{ old('respondent_contact', $blotter->respondent_contact) }}">
            </div>
        </div>
    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clipboard-list"></i> Resolution & Attachments</span>
    </div>
    <div class="card-body">
        <div class="form-group mb-6">
            <label class="form-label">
                Resolution Notes
                <span class="help-icon" data-tippy-content="Describe what action was taken: who mediated, what the parties agreed to, or why the case was referred. Leave blank if the case is newly filed.">?</span>
            </label>
            <textarea name="resolution_notes" class="form-control" rows="3">{{ old('resolution_notes', $blotter->resolution_notes) }}</textarea>
        </div>
        @if($blotter->file_path)
        <div style="margin-bottom:16px;padding:12px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);display:flex;align-items:center;gap:12px">
            <i class="fas fa-file" style="font-size:20px;color:var(--navy);flex-shrink:0"></i>
            <div style="flex:1"><div style="font-size:14px;font-weight:600">{{ $blotter->file_original_name ?? 'Attached File' }}</div><div style="font-size:13px;color:var(--text-muted)">Currently attached</div></div>
            <a href="{{ asset('storage/' . $blotter->file_path) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View</a>
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--crimson);cursor:pointer"><input type="checkbox" name="remove_attachment" value="1"> Remove</label>
        </div>
        @endif
        <div class="form-group">
            <label class="form-label">{{ $blotter->file_path ? 'Replace Attachment' : 'Supporting Document' }}</label>
            <div style="border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center;cursor:pointer" id="dropZone">
                <i class="fas fa-cloud-arrow-up" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:6px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div style="font-size:13px;color:var(--text-subtle)">JPG, PNG, PDF, DOC — Max 5MB</div>
                <input type="file" id="fileInput" name="attachment" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" style="display:none">
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Changes</button>
    <a href="{{ route('blotter.show', $blotter) }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<script>
// Auto-fill complainant name/address when resident selected
$('#complainant_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const address  = parts[1] ? parts[1].trim() : '';
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#complainant_name').val(fullName);
    $('#complainant_address').val(address);
});
$('#complainant_resident_id').on('select2:clear', function() {
    $('#complainant_name').val('');
    $('#complainant_address').val('');
});

// Auto-fill respondent name/address when resident selected
$('#respondent_resident_id').on('select2:select', function(e) {
    const text = e.params.data.text;
    const parts = text.split(' — ');
    const namePart = parts[0].trim();
    const address  = parts[1] ? parts[1].trim() : '';
    const nameParts = namePart.split(', ');
    const fullName  = nameParts.length > 1 ? nameParts[1] + ' ' + nameParts[0] : namePart;
    $('#respondent_name').val(fullName);
    $('#respondent_address').val(address);
});
$('#respondent_resident_id').on('select2:clear', function() {
    $('#respondent_name').val('');
    $('#respondent_address').val('');
});

// File upload — click + full drag-and-drop support
(function () {
    var zone  = document.getElementById('dropZone');
    var input = document.getElementById('fileInput');

    zone.addEventListener('click', function () { input.click(); });

    zone.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor    = 'var(--navy)';
        zone.style.backgroundColor = 'rgba(13,33,68,.04)';
    });

    zone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
    });

    zone.addEventListener('drop', function (e) {
        e.preventDefault();
        e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';

        var files = e.dataTransfer.files;
        if (files.length > 0) {
            // Transfer the dropped file to the real <input type="file">
            var dt = new DataTransfer();
            dt.items.add(files[0]);
            input.files = dt.files;

            // Show file name feedback inside the drop zone
            var label = zone.querySelector('.drop-feedback');
            if (! label) {
                label = document.createElement('div');
                label.className = 'drop-feedback';
                label.style.cssText = 'margin-top:8px;font-size:13px;font-weight:600;color:var(--navy)';
                zone.appendChild(label);
            }
            label.textContent = '✔ ' + files[0].name;
        }
    });

    // Also show file name when using the browse button
    input.addEventListener('change', function () {
        if (input.files.length > 0) {
            var label = zone.querySelector('.drop-feedback');
            if (! label) {
                label = document.createElement('div');
                label.className = 'drop-feedback';
                label.style.cssText = 'margin-top:8px;font-size:13px;font-weight:600;color:var(--navy)';
                zone.appendChild(label);
            }
            label.textContent = '✔ ' + input.files[0].name;
        }
    });
}());
</script>
@endpush

@endsection