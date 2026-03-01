@extends('layouts.app')

@section('title', 'Add Official')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Add Official</h1>
        <p class="page-subtitle">Register a barangay official or staff member</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('officials.store') }}" enctype="multipart/form-data">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Official Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Personal Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="full_name" class="form-control"
                       value="{{ old('full_name') }}" placeholder="e.g. Juan dela Cruz" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control"
                       value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX">
            </div>
        </div>

        <div class="form-section-title">Position & Committee</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Position <span style="color:var(--crimson)">*</span></label>
                <select name="position" class="form-control" required>
                    <option value="">Select Position</option>
                    @foreach($positions as $p)
                        <option value="{{ $p }}" {{ old('position') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Committee</label>
                <select name="committee" class="form-control">
                    <option value="">None / N/A</option>
                    @foreach($committees as $c)
                        <option value="{{ $c }}" {{ old('committee') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-section-title">Term & Status</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Term Start <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_start" class="form-control"
                       value="{{ old('term_start') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Term End <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_end" class="form-control"
                       value="{{ old('term_end') }}" required>
            </div>
            <div class="form-group" style="justify-content:flex-end;padding-bottom:4px">
                <label class="form-label">&nbsp;</label>
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    Currently Active
                </label>
            </div>
        </div>

        <div class="form-section-title">Photo</div>
        <div class="form-group">
            <label class="form-label">Official Photo</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
            <span style="font-size:11px;color:var(--text-subtle);margin-top:3px">JPG, PNG. Max 2MB.</span>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Official
    </button>
    <a href="{{ route('officials.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection