@extends('layouts.app')

@section('title', 'Edit Blotter Case')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Blotter Case</h1>
        <p class="page-subtitle">{{ $blotter->case_number }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.show', $blotter) }}" class="btn btn-secondary">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('blotter.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('blotter.update', $blotter) }}">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-triangle-exclamation"></i> Incident Details</span>
        <span class="td-mono">{{ $blotter->case_number }}</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Incident Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label>
                <select name="incident_type" class="form-control" required>
                    @foreach(['Physical Assault','Theft/Robbery','Verbal Abuse','Domestic Violence','Property Dispute','Noise Complaint','Trespassing','Vandalism','Threat','Others'] as $t)
                        <option value="{{ $t }}" {{ old('incident_type', $blotter->incident_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="incident_date" class="form-control"
                       value="{{ old('incident_date', $blotter->incident_date?->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Time</label>
                <input type="time" name="incident_time" class="form-control"
                       value="{{ old('incident_time', $blotter->incident_time) }}">
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Location <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="location" class="form-control"
                   value="{{ old('location', $blotter->location) }}" required>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Narrative <span style="color:var(--crimson)">*</span></label>
            <textarea name="narrative" class="form-control" rows="5" required>{{ old('narrative', $blotter->narrative) }}</textarea>
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
                <label class="form-label">Complainant (Resident)</label>
                <select name="complainant_resident_id" class="form-control">
                    <option value="">None</option>
                    @foreach($residents as $r)
                        <option value="{{ $r->id }}" {{ old('complainant_resident_id', $blotter->complainant_resident_id) == $r->id ? 'selected' : '' }}>
                            {{ $r->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Name (if not resident)</label>
                <input type="text" name="complainant_name" class="form-control"
                       value="{{ old('complainant_name', $blotter->complainant_name) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Address</label>
                <input type="text" name="complainant_address" class="form-control"
                       value="{{ old('complainant_address', $blotter->complainant_address) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Contact</label>
                <input type="text" name="complainant_contact" class="form-control"
                       value="{{ old('complainant_contact', $blotter->complainant_contact) }}">
            </div>
        </div>

        <div class="form-section-title">Respondent</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Respondent (Resident)</label>
                <select name="respondent_resident_id" class="form-control">
                    <option value="">None</option>
                    @foreach($residents as $r)
                        <option value="{{ $r->id }}" {{ old('respondent_resident_id', $blotter->respondent_resident_id) == $r->id ? 'selected' : '' }}>
                            {{ $r->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Name (if not resident)</label>
                <input type="text" name="respondent_name" class="form-control"
                       value="{{ old('respondent_name', $blotter->respondent_name) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Address</label>
                <input type="text" name="respondent_address" class="form-control"
                       value="{{ old('respondent_address', $blotter->respondent_address) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Contact</label>
                <input type="text" name="respondent_contact" class="form-control"
                       value="{{ old('respondent_contact', $blotter->respondent_contact) }}">
            </div>
        </div>

    </div>
</div>

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clipboard-list"></i> Case Management</span>
    </div>
    <div class="card-body">
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Active','Under Investigation','Settled','Closed','Referred to Court'] as $s)
                        <option value="{{ $s }}" {{ old('status', $blotter->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date Settled</label>
                <input type="date" name="settled_at" class="form-control"
                       value="{{ old('settled_at', $blotter->settled_at?->format('Y-m-d')) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Referred To</label>
                <input type="text" name="referred_to" class="form-control"
                       value="{{ old('referred_to', $blotter->referred_to) }}">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Action Taken / Resolution</label>
            <textarea name="action_taken" class="form-control" rows="4">{{ old('action_taken', $blotter->action_taken) }}</textarea>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('blotter.show', $blotter) }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
