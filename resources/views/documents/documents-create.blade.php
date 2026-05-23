@extends('layouts.app')
@section('title', 'Issue Document')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Issue Document</h1>
        <p class="page-subtitle">Create a new barangay certificate or clearance</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('documents.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ route('documents.store') }}" id="docCreateForm">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-circle-plus"></i> Document Details</span>
    </div>
    <div class="card-body">

        {{-- ── Beneficiary & Type ── --}}
        <div class="form-section-title">Beneficiary & Type</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Beneficiary (Resident) <span style="color:var(--crimson)">*</span></label>
                <select name="resident_id" id="resident_id" class="select2-resident @error('resident_id') is-invalid @enderror" required style="width:100%" data-placeholder="Type name to search...">
                    @if(old('resident_id') || request('resident_id'))
                        @php $sel = \App\Models\Resident::find(old('resident_id', request('resident_id'))); @endphp
                        @if($sel)<option value="{{ $sel->id }}" selected>{{ $sel->last_name }}, {{ $sel->first_name }} — {{ $sel->address }}</option>@endif
                    @else
                        <option value=""></option>
                    @endif
                </select>
                @error('resident_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Document Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Determines which certificate template is used. 'Barangay Clearance' is the most common — issued for employment, loans, and general use.">?</span>
                </label>
                <select name="document_type" class="form-control @error('document_type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    @foreach($documentTypes as $t)
                        <option value="{{ $t }}" {{ old('document_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('document_type')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        {{-- ── Requestor / Processed By ── --}}
        <div class="form-section-title">Requestor / Processed By</div>

        {{-- ── Request Details ── --}}
        <div class="form-section-title">Request Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Purpose <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Why does the resident need this document? Examples: 'Employment at SM Fairview', 'Bank loan application', 'School enrollment requirement'. This appears on the printed certificate.">?</span>
                </label>
                <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror" value="{{ old('purpose') }}" placeholder="e.g. Employment, Loan, School requirement" required>
                @error('purpose')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Pending' = received, not yet processed. 'Processing' = being prepared. 'Released' = given to the resident. 'Cancelled' = request withdrawn.">?</span>
                </label>
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    @foreach(['Pending','Processing','Released','Cancelled'] as $s)
                        <option value="{{ $s }}" {{ old('status','Pending') === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Fee (₱)
                    <span class="help-icon" data-tippy-content="Leave at ₱0 if the document is free of charge. Enter the amount collected if a fee was paid (e.g., ₱50 for Business Clearance).">?</span>
                </label>
                <input type="number" name="fee_paid" class="form-control @error('fee_paid') is-invalid @enderror" value="{{ old('fee_paid', 0) }}" min="0" step="0.01">
                @error('fee_paid')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    OR Number (if paid)
                    <span class="help-icon" data-tippy-content="Official Receipt number from the Barangay Treasurer. Only required when a fee was collected. Leave blank for free documents.">?</span>
                </label>
                <input type="text" name="or_number" class="form-control @error('or_number') is-invalid @enderror" value="{{ old('or_number') }}" placeholder="Official Receipt No.">
                @error('or_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        {{-- ── Additional Information ── --}}
        <div class="form-section-title">Additional Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Date Released
                    <span class="help-icon" data-tippy-content="Leave blank if the document hasn't been given to the resident yet. This field auto-fills to today's date when you change Status to 'Released'.">?</span>
                </label>
                <input type="date" name="released_at" class="form-control @error('released_at') is-invalid @enderror" value="{{ old('released_at') }}">
                @error('released_at')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary" id="docSubmitBtn">
        <span id="docSubmitLabel"><i class="fas fa-file-circle-plus"></i> Issue Document</span>
        <span id="docSubmitSpinner" style="display:none"><span class="doc-spin-icon"></span> Saving…</span>
    </button>
    <a href="{{ route('documents.index') }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<style>
@keyframes doc-spin { to { transform: rotate(360deg); } }
.doc-spin-icon {
    display: inline-block;
    width: 14px; height: 14px;
    border: 2px solid rgba(255,255,255,0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: doc-spin 0.7s linear infinite;
    vertical-align: middle;
    margin-right: 4px;
}
</style>
<script>
(function () {
    /* ── Gold spinner on submit ── */
    document.getElementById('docCreateForm').addEventListener('submit', function () {
        document.getElementById('docSubmitLabel').style.display  = 'none';
        document.getElementById('docSubmitSpinner').style.display = '';
        document.getElementById('docSubmitBtn').disabled = true;
    });
})();
</script>
@endpush

@endsection
