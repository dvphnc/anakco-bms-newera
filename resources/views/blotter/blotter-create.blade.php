@extends('layouts.app')

@section('title', 'File Blotter Case')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">File Blotter Case</h1>
        <p class="page-subtitle">Record a new incident or complaint</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('blotter.store') }}">
@csrf

{{-- Incident Details --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-triangle-exclamation"></i> Incident Details</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Incident Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Incident Type <span style="color:var(--crimson)">*</span></label>
                <select name="incident_type" class="form-control" required>
                    <option value="">Select Type</option>
                    @foreach(['Physical Assault','Theft/Robbery','Verbal Abuse','Domestic Violence','Property Dispute','Noise Complaint','Trespassing','Vandalism','Threat','Others'] as $t)
                        <option value="{{ $t }}" {{ old('incident_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Date <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="incident_date" class="form-control"
                       value="{{ old('incident_date', date('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Incident Time</label>
                <input type="time" name="incident_time" class="form-control"
                       value="{{ old('incident_time') }}">
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Location / Place of Incident <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="location" class="form-control"
                   value="{{ old('location') }}"
                   placeholder="e.g. Purok 3, near the basketball court" required>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Narrative / Incident Description <span style="color:var(--crimson)">*</span></label>
            <textarea name="narrative" class="form-control" rows="5"
                      placeholder="Describe what happened in detail..." required>{{ old('narrative') }}</textarea>
        </div>

    </div>
</div>

{{-- Parties Involved --}}
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
                    <option value="">Select Resident (optional)</option>
                    @foreach($residents as $r)
                        <option value="{{ $r->id }}"
                            {{ old('complainant_resident_id', request('complainant_resident_id')) == $r->id ? 'selected' : '' }}>
                            {{ $r->full_name }}
                        </option>
                    @endforeach
                </select>
                <span style="font-size:11px;color:var(--text-subtle);margin-top:3px">
                    Select if complainant is a registered resident.
                </span>
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Name (if not resident)</label>
                <input type="text" name="complainant_name" class="form-control"
                       value="{{ old('complainant_name') }}"
                       placeholder="Full name of complainant">
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Address</label>
                <input type="text" name="complainant_address" class="form-control"
                       value="{{ old('complainant_address') }}" placeholder="Address">
            </div>
            <div class="form-group">
                <label class="form-label">Complainant Contact</label>
                <input type="text" name="complainant_contact" class="form-control"
                       value="{{ old('complainant_contact') }}" placeholder="09XX XXX XXXX">
            </div>
        </div>

        <div class="form-section-title">Respondent</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Respondent (Resident)</label>
                <select name="respondent_resident_id" class="form-control">
                    <option value="">Select Resident (optional)</option>
                    @foreach($residents as $r)
                        <option value="{{ $r->id }}" {{ old('respondent_resident_id') == $r->id ? 'selected' : '' }}>
                            {{ $r->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Name (if not resident)</label>
                <input type="text" name="respondent_name" class="form-control"
                       value="{{ old('respondent_name') }}"
                       placeholder="Full name of respondent">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Address</label>
                <input type="text" name="respondent_address" class="form-control"
                       value="{{ old('respondent_address') }}" placeholder="Address">
            </div>
            <div class="form-group">
                <label class="form-label">Respondent Contact</label>
                <input type="text" name="respondent_contact" class="form-control"
                       value="{{ old('respondent_contact') }}" placeholder="09XX XXX XXXX">
            </div>
        </div>

    </div>
</div>

{{-- Case Management --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clipboard-list"></i> Case Management</span>
    </div>
    <div class="card-body">

        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Case Status</label>
                <select name="status" class="form-control">
                    @foreach(['Active','Under Investigation','Settled','Closed','Referred to Court'] as $s)
                        <option value="{{ $s }}" {{ old('status','Active') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date Settled</label>
                <input type="date" name="settled_at" class="form-control"
                       value="{{ old('settled_at') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Referred To</label>
                <input type="text" name="referred_to" class="form-control"
                       value="{{ old('referred_to') }}"
                       placeholder="e.g. PNP, DSWD, Court">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Action Taken / Resolution</label>
            <textarea name="action_taken" class="form-control" rows="4"
                      placeholder="Describe the action taken or resolution...">{{ old('action_taken') }}</textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-gavel"></i> File Case
    </button>
    <a href="{{ route('blotter.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
