@extends('layouts.app')
@section('title', 'Edit Document')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Document</h1>
        <p class="page-subtitle">{{ $document->doc_number }} — {{ $document->document_type }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('documents.show', $document) }}" class="btn btn-secondary"><i class="fas fa-eye"></i> View</a>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
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
                <select name="resident_id" id="resident_id" class="select2-resident @error('resident_id') is-invalid @enderror" required style="width:100%" data-placeholder="Search resident by name...">
                    @if($document->resident)
                        <option value="{{ $document->resident_id }}" selected>
                            {{ $document->resident->last_name }}, {{ $document->resident->first_name }} — {{ $document->resident->address }}
                        </option>
                    @endif
                </select>
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

        {{-- ── Requestor / Processed By ── --}}
        @php
            /* Determine initial representative state:
               A representative exists when requestor_name is set AND differs from the resident's own name.
               On validation failure, respect old('is_representative'). */
            $residentFullName  = $document->resident?->full_name ?? '';
            $savedReqName      = $document->requestor_name ?? '';
            $isRep             = old('is_representative') !== null
                                    ? (bool) old('is_representative')
                                    : ($savedReqName !== '' && $savedReqName !== $residentFullName);
        @endphp

        <div class="form-section-title">Requestor / Processed By</div>

        {{-- Representative toggle --}}
        <div class="form-group mb-3">
            <div style="display:flex;align-items:center;gap:12px;padding:13px 16px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);cursor:pointer" onclick="document.getElementById('isRepCheck').click()">
                <input type="hidden" name="is_representative" value="0">
                <input type="checkbox" name="is_representative" id="isRepCheck" value="1"
                       style="width:17px;height:17px;accent-color:var(--navy);cursor:pointer;flex-shrink:0;pointer-events:none"
                       {{ $isRep ? 'checked' : '' }}>
                <div style="pointer-events:none">
                    <div style="font-size:14px;font-weight:500;color:var(--text)">A <strong>representative</strong> is picking up / requesting this document</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:1px">Check this if someone other than the resident collected the document (e.g. child, spouse, attorney).</div>
                </div>
            </div>
        </div>

        {{-- Self-pickup info pill --}}
        <div id="selfPickupInfo" style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:rgba(200,134,26,0.08);border:1px solid rgba(200,134,26,0.25);border-radius:var(--radius);margin-bottom:20px{{ $isRep ? ';display:none!important' : '' }}">
            <i class="fas fa-circle-check" style="color:var(--navy);font-size:14px"></i>
            <span style="font-size:13px;color:var(--navy)">Will be picked up by: <strong id="selfPickupName">{{ $residentFullName ?: 'the resident' }}</strong></span>
        </div>

        {{-- Representative fields --}}
        <div id="repPanel" style="{{ $isRep ? '' : 'display:none;' }}margin-bottom:8px">
            <div class="form-grid-2 mb-2" style="margin-top:16px">
                <div class="form-group">
                    <label class="form-label">Representative Name <span style="color:var(--crimson)">*</span></label>
                    <input type="text" name="requestor_name" id="requestorName"
                           class="form-control @error('requestor_name') is-invalid @enderror"
                           value="{{ old('requestor_name', $document->requestor_name) }}"
                           placeholder="Full name of the person picking up">
                    @error('requestor_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Relationship to Resident <span style="color:var(--crimson)">*</span></label>
                    <select name="requestor_relationship" id="requestorRelationship"
                            class="form-control select2-rel @error('requestor_relationship') is-invalid @enderror">
                        <option value="">Select Relationship</option>
                        @foreach($relationships as $rel)
                            <option value="{{ $rel }}"
                                {{ old('requestor_relationship', $document->requestor_relationship) === $rel ? 'selected' : '' }}>
                                {{ $rel }}
                            </option>
                        @endforeach
                    </select>
                    @error('requestor_relationship')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">
                        Representative Contact
                        <span class="help-icon" data-tippy-content="Optional — phone or email to notify the representative when the document is ready.">?</span>
                    </label>
                    <input type="text" name="requestor_contact"
                           class="form-control @error('requestor_contact') is-invalid @enderror"
                           value="{{ old('requestor_contact', $document->requestor_contact) }}"
                           placeholder="Phone or email (optional)">
                    @error('requestor_contact')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                </div>
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
                <select name="status" class="form-control @error('status') is-invalid @enderror">
                    @foreach(\App\Models\Document::$statuses as $s)
                        <option value="{{ $s }}" {{ old('status', $document->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
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
    var repCheck = document.getElementById('isRepCheck');
    var repPanel = document.getElementById('repPanel');
    var selfInfo = document.getElementById('selfPickupInfo');
    var selfName = document.getElementById('selfPickupName');
    var resSel   = document.getElementById('resident_id');

    function syncToggle() {
        var checked = repCheck.checked;
        repPanel.style.display = checked ? '' : 'none';
        selfInfo.style.display = checked ? 'none' : '';
    }

    function syncResidentLabel() {
        if (!resSel) return;
        var opt = resSel.options[resSel.selectedIndex];
        if (opt && opt.value) {
            selfName.textContent = opt.text.split('—')[0].trim();
        } else {
            selfName.textContent = 'the resident';
        }
    }

    repCheck.addEventListener('change', syncToggle);

    if (resSel) {
        $(resSel).on('select2:select',   syncResidentLabel);
        $(resSel).on('select2:unselect', function () { selfName.textContent = 'the resident'; });
        // Populate label from pre-selected option on page load
        syncResidentLabel();
    }

    $('#requestorRelationship').select2({
        placeholder: 'Select relationship',
        allowClear: true,
        minimumResultsForSearch: Infinity,
        width: '100%',
        dropdownParent: $('#repPanel')
    });

    document.getElementById('docEditForm').addEventListener('submit', function (e) {
        if (repCheck.checked) {
            var rName = document.querySelector('[name="requestor_name"]').value.trim();
            var rRel  = document.querySelector('[name="requestor_relationship"]').value;
            if (!rName || !rRel) {
                e.preventDefault();
                return;
            }
        }
        document.getElementById('docEditLabel').style.display   = 'none';
        document.getElementById('docEditSpinner').style.display = '';
        document.getElementById('docEditSubmitBtn').disabled = true;
    });

    // Init
    syncToggle();
})();
</script>
@endpush

@endsection
