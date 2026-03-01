@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Document</h1>
        <p class="page-subtitle">{{ $document->doc_number }} — {{ $document->document_type }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('documents.update', $document) }}">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-pen"></i> Document Details</span>
        <span class="td-mono">{{ $document->doc_number }}</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Resident & Type</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Resident <span style="color:var(--crimson)">*</span></label>
                <select name="resident_id" class="form-control" required>
                    <option value="">Select Resident</option>
                    @foreach($residents as $resident)
                        <option value="{{ $resident->id }}"
                            {{ old('resident_id', $document->resident_id) == $resident->id ? 'selected' : '' }}>
                            {{ $resident->full_name }} — {{ $resident->purok->name ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Document Type <span style="color:var(--crimson)">*</span></label>
                <select name="document_type" class="form-control" required>
                    @foreach([
                        'Barangay Clearance',
                        'Certificate of Residency',
                        'Certificate of Indigency',
                        'Business Clearance',
                        'Certificate of Good Moral',
                        'Barangay ID',
                        'First Time Job Seeker',
                    ] as $t)
                        <option value="{{ $t }}" {{ old('document_type', $document->document_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-section-title">Request Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purpose <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="purpose" class="form-control"
                       value="{{ old('purpose', $document->purpose) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Pending','Processing','Released','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status', $document->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Fee (₱)</label>
                <input type="number" name="fee" class="form-control"
                       value="{{ old('fee', $document->fee) }}" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label class="form-label">OR Number</label>
                <input type="text" name="or_number" class="form-control"
                       value="{{ old('or_number', $document->or_number) }}">
            </div>
        </div>

        <div class="form-section-title">Additional Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Issued By</label>
                <input type="text" name="issued_by" class="form-control"
                       value="{{ old('issued_by', $document->issued_by) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Date Released</label>
                <input type="date" name="date_released" class="form-control"
                       value="{{ old('date_released', $document->date_released?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $document->remarks) }}</textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
