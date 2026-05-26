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
                    $preResident   = $document->resident ?? $suggestedResident ?? null;
                    $preResidentId = $document->resident_id ?? $suggestedResident?->id;
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
                @if($suggestedResident && !$document->resident_id)
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
                    <span class="help-icon" data-tippy-content="'Pending' = not yet processed. 'Processing' = being prepared. 'Ready' = ready for pick-up. 'Released' = given to the resident. 'Cancelled' = request withdrawn.">?</span>
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
                    <select name="status" id="statusSelect" class="form-control @error('status') is-invalid @enderror">
                        @foreach(\App\Models\Document::$statuses as $s)
                            @if($s !== 'Released' || $document->status === 'Released')
                                <option value="{{ $s }}" {{ old('status', $document->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endif
                        @endforeach
                    </select>
                @endif
                @error('status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>

            {{-- ── Pickup Date — shown only when status = Ready ── --}}
            <div class="form-group" id="pickupDateGroup"
                 style="{{ old('status', $document->status) === 'Ready' ? '' : 'display:none' }}">
                <label class="form-label">
                    <i class="fas fa-calendar-check" style="color:#16a34a;font-size:11px;margin-right:4px"></i>
                    Pick-up Date
                    <span class="help-icon" data-tippy-content="Tell the resident when their document will be ready for pick-up. This date will appear in the portal tracker.">?</span>
                </label>
                @php
                    $existingPickup = null;
                    if ($document->appointment_id) {
                        $existingPickup = \App\Models\DocumentAppointment::find($document->appointment_id)?->pickup_date?->format('Y-m-d');
                    }
                @endphp
                <input type="date" name="pickup_date" id="pickupDateInput"
                       class="form-control @error('pickup_date') is-invalid @enderror"
                       value="{{ old('pickup_date', $existingPickup) }}"
                       style="border-color:#86efac">
                <div style="font-size:11.5px;color:#16a34a;margin-top:5px;display:flex;align-items:center;gap:4px">
                    <i class="fas fa-circle-info" style="font-size:10px"></i>
                    Resident will see this date highlighted in their portal tracker.
                </div>
                @error('pickup_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    Fee (₱)
                    <span class="help-icon" data-tippy-content="Enter 0 for indigent residents or free certificates. OR Number will be auto-generated when fee is greater than 0.">?</span>
                </label>
                <input type="number" id="feeInput" name="fee_paid"
                       class="form-control @error('fee_paid') is-invalid @enderror"
                       value="{{ old('fee_paid', $document->fee_paid ?? 0) }}"
                       min="0" step="0.01">
                @error('fee_paid')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;justify-content:space-between">
                    <span>
                        OR Number
                        <span class="help-icon" data-tippy-content="Official Receipt number from the cashier. Leave blank to auto-generate when fee is greater than 0.">?</span>
                    </span>
                    <button type="button" id="orAutoBtn"
                            style="display:none;font-size:11.5px;font-weight:600;color:var(--navy);
                                   background:rgba(13,33,68,0.07);border:1px solid rgba(13,33,68,0.18);
                                   border-radius:4px;padding:2px 8px;cursor:pointer;transition:.15s"
                            onclick="autoFillOrNumber()">
                        <i class="fas fa-wand-magic-sparkles" style="font-size:10px"></i> Auto-fill
                    </button>
                </label>
                <div style="position:relative">
                    <input type="text" id="orInput" name="or_number"
                           class="form-control @error('or_number') is-invalid @enderror"
                           placeholder="{{ $document->fee_paid > 0 && !$document->or_number ? 'Will be auto-generated on save' : 'e.g. OR-2026-00001' }}"
                           value="{{ old('or_number', $document->or_number) }}">
                    <span id="orAutoHint"
                          style="display:none;position:absolute;right:10px;top:50%;transform:translateY(-50%);
                                 font-size:11px;color:#9ca3af;pointer-events:none">
                        auto-generate on save
                    </span>
                </div>
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

        {{-- ── Portal Status Message (only for portal-sourced documents) ── --}}
        @if($document->source === 'portal')
        <div id="portalMessageSection" style="margin-bottom:24px">
            <div class="form-section-title">
                <i class="fas fa-comment-dots" style="font-size:12px;margin-right:5px;color:var(--gold)"></i>
                Message to Resident
            </div>
            <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:var(--radius-sm);
                        padding:12px 14px;margin-bottom:12px;font-size:12.5px;color:#92400e;
                        display:flex;align-items:flex-start;gap:8px">
                <i class="fas fa-circle-info" style="flex-shrink:0;margin-top:1px"></i>
                <span>This is a <strong>portal request</strong>. Any message you enter below will be sent to the resident by email and will also appear in their portal tracker when the status changes.</span>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label">
                    Message <span style="font-weight:400;color:var(--text-subtle);font-size:12px">— optional, only sent when status changes</span>
                </label>
                <textarea name="status_message" class="form-control" rows="3"
                          placeholder="e.g. Your document is ready for pick-up. Please bring a valid ID. Office hours: Mon–Fri, 8AM–5PM."
                          id="statusMessageArea">{{ old('status_message') }}</textarea>
            </div>
        </div>
        @endif

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
#orAutoBtn:hover { background: rgba(13,33,68,.14) !important; }
</style>
<script>
(function () {
    var _generateOrUrl = '{{ route('documents.generateOrNumber') }}';

    /* ── Submit spinner ─────────────────────────────────────── */
    document.getElementById('docEditForm').addEventListener('submit', function () {
        document.getElementById('docEditLabel').style.display   = 'none';
        document.getElementById('docEditSpinner').style.display = '';
        document.getElementById('docEditSubmitBtn').disabled = true;
    });

    /* ── OR auto-generate logic ─────────────────────────────── */
    var feeInput  = document.getElementById('feeInput');
    var orInput   = document.getElementById('orInput');
    var orBtn     = document.getElementById('orAutoBtn');
    var orHint    = document.getElementById('orAutoHint');

    function syncOrUi() {
        if (!feeInput || !orInput) return;
        var hasFee = parseFloat(feeInput.value || '0') > 0;
        var hasOr  = orInput.value.trim().length > 0;

        if (hasFee && !hasOr) {
            if (orBtn)  orBtn.style.display  = 'inline-block';
            if (orHint) orHint.style.display = 'block';
            orInput.placeholder = 'Will be auto-generated on save';
        } else {
            if (orBtn)  orBtn.style.display  = 'none';
            if (orHint) orHint.style.display = 'none';
            if (!hasOr) orInput.placeholder = 'e.g. OR-2026-00001';
        }
    }

    if (feeInput) feeInput.addEventListener('input', syncOrUi);
    if (orInput)  orInput.addEventListener('input',  syncOrUi);
    syncOrUi(); // run on page load

    window.autoFillOrNumber = function () {
        if (orBtn) { orBtn.disabled = true; orBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:10px"></i>'; }

        axios.get(_generateOrUrl)
            .then(function (res) {
                if (res.data.or_number) {
                    orInput.value = res.data.or_number;
                    orInput.dispatchEvent(new Event('input'));
                    orInput.classList.add('is-valid');
                    setTimeout(function () { orInput.classList.remove('is-valid'); }, 2000);
                }
            })
            .catch(function () {
                bmsToast('Could not generate OR number — please enter manually.', 'error');
            })
            .finally(function () {
                if (orBtn) { orBtn.disabled = false; orBtn.innerHTML = '<i class="fas fa-wand-magic-sparkles" style="font-size:10px"></i> Auto-fill'; }
            });
    };

    // Resident pre-selection is handled globally in app.blade.php
    // via the data-initial-id / data-initial-text attributes on the select.

    @if($document->source === 'portal' && $document->status !== 'Released')
    /* ── Status-change message: auto-focus textarea when status changes ─ */
    var statusSel   = document.getElementById('statusSelect');
    var msgArea     = document.getElementById('statusMessageArea');
    var origStatus  = '{{ $document->status }}';

    if (statusSel && msgArea) {
        statusSel.addEventListener('change', function () {
            if (this.value !== origStatus) {
                msgArea.closest('.form-group').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                setTimeout(function () { msgArea.focus(); }, 250);
            }
        });
    }
    @endif

}());
</script>
@endpush

@endsection
