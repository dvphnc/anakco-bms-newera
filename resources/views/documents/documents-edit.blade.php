@extends('layouts.app')
@section('title', 'Edit Document')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Document</h1>
        <p class="page-subtitle">{{ $document->doc_number }} — {{ $document->document_type }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ route('documents.update', $document) }}" id="docEditForm">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-pen"></i> Document Details</span>
        <span class="td-mono">{{ $document->doc_number }}</span>
    </div>
    <div class="card-body">

        {{-- ── Resident & Type ── --}}
        <div class="form-section-title">Resident & Type</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Resident <span style="color:var(--crimson)">*</span></label>
                @php
                    $preResident = $document->resident ?? $suggestedResident ?? null;
                    $preResidentId = $document->resident ? $document->resident_id : ($suggestedResident?->id);
                @endphp
                <select name="resident_id" id="resident_id"
                        class="select2-resident @error('resident_id') is-invalid @enderror"
                        required style="width:100%"
                        data-placeholder="Search resident by name..."
                        data-initial-id="{{ $preResidentId }}"
                        data-initial-text="{{ $preResident ? $preResident->last_name.', '.$preResident->first_name.' — '.($preResident->address ?? '') : '' }}">
                    @if($preResident && $preResidentId)
                        <option value="{{ $preResidentId }}" selected>
                            {{ $preResident->last_name }}, {{ $preResident->first_name }}
                            @if($preResident->address) — {{ $preResident->address }} @endif
                        </option>
                    @endif
                </select>
                @if($suggestedResident && !$document->resident)
                    <div style="font-size:11.5px;color:#b45309;margin-top:5px;display:flex;align-items:center;gap:5px">
                        <i class="fas fa-circle-info"></i>
                        Auto-matched from portal name "<strong>{{ $document->resident_name_portal }}</strong>" — please verify before saving.
                    </div>
                @endif
                @error('resident_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Document Type <span style="color:var(--crimson)">*</span>
                    <span class="help-icon" data-tippy-content="Each type generates a different certificate layout. Choose the type that matches what the resident is requesting.">?</span>
                </label>
                <select name="document_type" class="form-control @error('document_type') is-invalid @enderror" required>
                    @foreach($documentTypes as $t)
                        <option value="{{ $t }}" {{ old('document_type', $document->document_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('document_type')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        {{-- ── Request Details ── --}}
        <div class="form-section-title">Request Details</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purpose <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="purpose" class="form-control @error('purpose') is-invalid @enderror" value="{{ old('purpose', $document->purpose) }}" required>
                @error('purpose')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Status
                    <span class="help-icon" data-tippy-content="'Pending' = not yet processed. 'Processing' = being prepared. 'Released' = given to the resident. 'Cancelled' = request withdrawn.">?</span>
                </label>
                @if($document->status === 'Released')
                    {{-- Released documents are immutable — lock the status field --}}
                    <div style="padding:9px 12px;background:var(--surface2);border:1px solid var(--border);
                                border-radius:var(--radius-sm);font-size:13.5px;color:var(--text-muted);
                                display:flex;align-items:center;gap:8px">
                        <i class="fas fa-lock" style="font-size:11px;color:#9ca3af"></i>
                        Released — <span style="font-style:italic;font-size:12px">status is locked after release</span>
                    </div>
                    <input type="hidden" name="status" value="Released">
                @else
                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                        @foreach(\App\Models\Document::$statuses as $s)
                            @if($s !== 'Released' || $document->status === 'Released')
                                <option value="{{ $s }}" {{ old('status', $document->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endif
                        @endforeach
                    </select>
                @endif
                @error('status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Fee (₱)
                    <span class="help-icon" data-tippy-content="Enter 0 for indigent residents or free certificates.">?</span>
                </label>
                <input type="number" name="fee_paid" class="form-control @error('fee_paid') is-invalid @enderror" value="{{ old('fee_paid', $document->fee_paid ?? 0) }}" min="0" step="0.01">
                @error('fee_paid')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    OR Number
                    <span class="help-icon" data-tippy-content="Official Receipt number from the cashier. Fill this in when the fee has been paid.">?</span>
                </label>
                <input type="text" name="or_number" class="form-control @error('or_number') is-invalid @enderror" placeholder="e.g. OR-2026-00001" value="{{ old('or_number', $document->or_number) }}">
                @error('or_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Date Released
                    <span class="help-icon" data-tippy-content="The date the document was physically given to the resident. Auto-fills to today when Status is set to Released.">?</span>
                </label>
                <input type="date" name="released_at" class="form-control @error('released_at') is-invalid @enderror" value="{{ old('released_at', $document->released_at?->format('Y-m-d')) }}">
                @error('released_at')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        {{-- ── Issued By ── --}}
        <div class="form-section-title">Issued By</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Issuing Officer</label>
                <div style="display:flex;align-items:center;gap:10px;
                            padding:10px 14px;min-height:58px;
                            background:var(--surface2);border:1px solid var(--border);
                            border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;
                                background:linear-gradient(135deg,var(--navy),var(--navy-mid));
                                display:flex;align-items:center;justify-content:center;
                                font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600">{{ $document->issuedBy->name ?? auth()->user()->name }}</div>
                        <div class="td-muted">{{ $document->issuedBy->role ?? auth()->user()->role }} — Issued on {{ $document->created_at->format('m/d/Y') }}</div>
                    </div>
                </div>
                <input type="hidden" name="issued_by" value="{{ $document->issued_by ?? auth()->id() }}">
            </div>
            <div class="form-group">
                <label class="form-label">Document Number</label>
                <div style="display:flex;align-items:center;gap:10px;
                            padding:10px 14px;min-height:58px;
                            background:var(--surface2);border:1px solid var(--border);
                            border-radius:var(--radius);font-size:13.5px">
                    <div style="width:34px;height:34px;border-radius:50%;
                                background:rgba(200,134,26,0.12);border:1.5px solid rgba(200,134,26,0.35);
                                display:flex;align-items:center;justify-content:center;
                                font-size:14px;flex-shrink:0;color:var(--gold)">
                        <i class="fas fa-hashtag"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-family:monospace;color:var(--navy);font-size:14px;letter-spacing:.03em">
                            {{ $document->doc_number }}
                        </div>
                        <div class="td-muted">{{ $document->document_type }} · {{ $document->created_at->format('m/d/Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary" id="docEditSubmitBtn">
        <span id="docEditLabel"><i class="fas fa-floppy-disk"></i> Save Changes</span>
        <span id="docEditSpinner" style="display:none"><span class="doc-spin-icon"></span> Saving…</span>
    </button>
    <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary">Cancel</a>
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
    // ── Submit spinner ────────────────────────────────────────────
    document.getElementById('docEditForm').addEventListener('submit', function () {
        document.getElementById('docEditLabel').style.display   = 'none';
        document.getElementById('docEditSpinner').style.display = '';
        document.getElementById('docEditSubmitBtn').disabled = true;
    });

    // ── Resident Select2 pre-selection ────────────────────────────
    // Global init (app.blade.php) runs in $(document).ready.
    // We wait for it to finish, then call val().trigger('change')
    // so Select2 renders the pre-populated <option selected>.
    $(document).ready(function () {
        setTimeout(function () {
            var $sel = $('#resident_id');
            var preId = $sel.data('initial-id');
            if (preId && $sel.find('option[value="' + preId + '"]').length) {
                $sel.val(String(preId)).trigger('change');
            }
        }, 80);
    });
})();
</script>
@endpush

@endsection
