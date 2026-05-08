@extends('layouts.app')

@section('title', 'Edit Official')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Official</h1>
        <p class="page-subtitle">{{ $official->full_name }} — {{ $official->position }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('officials.update', $official) }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Official Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Personal Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
                       value="{{ old('full_name', $official->full_name) }}" required>
                @error('full_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                       value="{{ old('contact_number', $official->contact_number) }}">
                @error('contact_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Position & Committee</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Position <span style="color:var(--crimson)">*</span></label>
                <select name="position" class="form-control @error('position') is-invalid @enderror" required>
                    @foreach($positions as $p)
                        <option value="{{ $p }}" {{ old('position', $official->position) === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
                @error('position')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Committee</label>
                <select name="committee" class="form-control @error('committee') is-invalid @enderror">
                    <option value="">None / N/A</option>
                    @foreach($committees as $c)
                        <option value="{{ $c }}" {{ old('committee', $official->committee) === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                @error('committee')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Term & Status</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Term Start <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_start" class="form-control @error('term_start') is-invalid @enderror"
                       value="{{ old('term_start', $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y-m-d') : '') }}" required>
                @error('term_start')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Term End <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_end" class="form-control @error('term_end') is-invalid @enderror"
                       value="{{ old('term_end', $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y-m-d') : '') }}" required>
                @error('term_end')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="justify-content:flex-end;padding-bottom:4px">
                <label class="form-label">&nbsp;</label>
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $official->is_active) ? 'checked' : '' }}>
                    Currently Active
                </label>
            </div>
        </div>

        <div class="form-section-title">Photo</div>
        <div class="form-group">
            @if($official->photo_path)
            <div style="margin-bottom:10px">
                <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Current Photo"
                     style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                <div style="font-size:13px;color:var(--text-muted);margin-top:4px">Current photo</div>
            </div>
            @endif
            <label class="form-label">Upload New Photo</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('officials.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection