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

        {{-- RESIDENT & TYPE --}}
        <div class="form-section-title">Resident & Type</div>
        <div class="form-grid-2 mb-6">

            {{-- Resident -- shown as read-only display + hidden input --}}
            <div class="form-group">
                <label class="form-label">Resident</label>
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        {{ strtoupper(substr($document->resident->first_name ?? 'R', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600">{{ $document->resident->full_name ?? '—' }}</div>
                        <div class="td-muted">{{ $document->resident->purok->name ?? '' }} — {{ $document->resident->address ?? '' }}</div>
                    </div>
                </div>
                {{-- Keep resident_id in form so update() validation passes --}}
                <input type="hidden" name="resident_id" value="{{ $document->resident_id }}">
            </div>

            <div class="form-group">
                <label class="form-label">Document Type <span style="color:var(--crimson)">*</span></label>
                <select name="document_type" class="form-control" required>
                    @foreach([
                        'Barangay Clearance',
                        'Certificate of Residency',
                        'Certificate of Indigency',
                        'Good Moral Character',
                        'Business Clearance',
                        'Certificate of Live Birth',
                        'Other',
                    ] as $t)
                        <option value="{{ $t }}" {{ old('document_type', $document->document_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- REQUEST DETAILS --}}
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
                <input type="number" name="fee_paid" class="form-control"
                       value="{{ old('fee_paid', $document->fee_paid ?? 0) }}" min="0" step="0.01">
            </div>
            <div class="form-group">
                <label class="form-label">Date Released</label>
                <input type="date" name="released_at" class="form-control"
                       value="{{ old('released_at', $document->released_at?->format('Y-m-d')) }}">
            </div>
        </div>

        {{-- ISSUED BY --}}
        <div class="form-section-title">Issued By</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Issuing Officer</label>
                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600">{{ $document->issuedBy->name ?? auth()->user()->name }}</div>
                        <div class="td-muted">{{ $document->issuedBy->role ?? auth()->user()->role }} — Issued on {{ $document->created_at->format('F d, Y') }}</div>
                    </div>
                </div>
                <input type="hidden" name="issued_by" value="{{ $document->issued_by ?? auth()->id() }}">
            </div>

            <div class="form-group">
                <label class="form-label">Document Number</label>
                <div style="padding:10px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);font-size:13.5px;font-family:monospace;color:var(--text-muted)">
                    {{ $document->doc_number }}
                </div>
            </div>
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