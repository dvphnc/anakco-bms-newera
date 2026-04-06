@extends('layouts.app')
@section('title', 'Edit Blotter Case')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Blotter Case</h1>
        <p class="page-subtitle">{{ $blotter->case_number }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.show', $blotter) }}" class="btn btn-secondary"><i class="fas fa-eye"></i> View</a>
        <a href="{{ route('blotter.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
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
                <label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label>
                <select name="incident_type" class="form-control" required>
                    @foreach(['Noise Complaint','Physical Assault','Verbal Abuse','Theft','Trespassing','Domestic Dispute','Property Damage','Threat','Other'] as $t)
                        <option value="{{ $t }}" {{ old('incident_type', $blotter->incident_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="incident_date" class="form-control" value="{{ old('incident_date', $blotter->incident_date?->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Active','Under Investigation','Mediated','Settled','Closed','Referred to Higher Authority'] as $s)
                        <option value="{{ $s }}" {{ old('status', $blotter->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group mb-6">
            <label class="form-label">Incident Location <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="incident_location" class="form-control" value="{{ old('incident_location', $blotter->incident_location) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Incident Details <span style="color:var(--crimson)">*</span></label>
            <textarea name="incident_details" class="form-control" rows="5" required>{{ old('incident_details', $blotter->incident_details) }}</textarea>
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
                <input type="text" name="complainant_name" id="complainant_name" class="form-control" value="{{ old('complainant_name', $blotter->complainant_name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident <span style="font-size:10px;color:var(--text-subtle)">(optional)</span></label>
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

        <div class="form-section-title">Respondent</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Respondent Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="respondent_name" id="respondent_name" class="form-control" value="{{ old('respondent_name', $blotter->respondent_name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Linked Resident <span style="font-size:10px;color:var(--text-subtle)">(optional)</span></label>
                <select name="respondent_resident_id" id="respondent_resident_id" class="select2-resident" style="width:100%" data-placeholder="Search registered resident...">
                    <option value=""></option>
                    @if(isset($blotter->respondent_resident_id) && $blotter->respondent_resident_id)
                        @php $rr = \App\Models\Resident::find(old('respondent_resident_id', $blotter->respondent_resident_id)); @endphp
                        @if($rr)<option value="{{ $rr->id }}" selected>{{ $rr->last_name }}, {{ $rr->first_name }} — {{ $rr->address }}</option>@endif
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Address</label>
                <input type="text" name="respondent_address" id="respondent_address" class="form-control" value="{{ old('respondent_address', $blotter->respondent_address) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Contact</label>
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
            <label class="form-label">Resolution Notes</label>
            <textarea name="resolution_notes" class="form-control" rows="3">{{ old('resolution_notes', $blotter->resolution_notes) }}</textarea>
        </div>
        @if($blotter->file_path)
        <div style="margin-bottom:16px;padding:12px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);display:flex;align-items:center;gap:12px">
            <i class="fas fa-file" style="font-size:20px;color:var(--navy);flex-shrink:0"></i>
            <div style="flex:1"><div style="font-size:13px;font-weight:600">{{ $blotter->file_original_name ?? 'Attached File' }}</div><div style="font-size:11px;color:var(--text-muted)">Currently attached</div></div>
            <a href="{{ asset('storage/' . $blotter->file_path) }}" target="_blank" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i> View</a>
            <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--crimson);cursor:pointer"><input type="checkbox" name="remove_attachment" value="1"> Remove</label>
        </div>
        @endif
        <div class="form-group">
            <label class="form-label">{{ $blotter->file_path ? 'Replace Attachment' : 'Supporting Document' }}</label>
            <div style="border:2px dashed var(--border);border-radius:var(--radius);padding:24px;text-align:center;cursor:pointer" id="dropZone">
                <i class="fas fa-cloud-arrow-up" style="font-size:28px;color:var(--text-muted);margin-bottom:8px;display:block"></i>
                <div style="font-size:13px;color:var(--text-muted);margin-bottom:6px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div style="font-size:11px;color:var(--text-subtle)">JPG, PNG, PDF, DOC — Max 5MB</div>
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

// File upload
document.getElementById('dropZone').addEventListener('click', () => document.getElementById('fileInput').click());
</script>
@endpush

@endsection