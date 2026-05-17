@extends('layouts.portal')
@section('title', 'Request a Document')

@push('styles')
<style>
    .page-hd {
        margin-bottom: 1.75rem;
    }
    .page-hd h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .25rem;
    }
    .page-hd p {
        font-size: .82rem;
        color: #6b7280;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 600px) {
        .form-row { grid-template-columns: 1fr; }
    }

    .step-indicator {
        display: flex;
        gap: .5rem;
        margin-bottom: 1.75rem;
    }
    .step-dot {
        display: flex;
        align-items: center;
        gap: .5rem;
        font-size: .78rem;
        color: #9ca3af;
        font-weight: 500;
    }
    .step-dot .dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 700;
    }
    .step-dot.active { color: var(--navy); }
    .step-dot.active .dot { background: var(--navy); color: #fff; }
    .step-divider { flex: 1; height: 2px; background: #e5e7eb; align-self: center; }

    .section-label {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #9ca3af;
        margin: 1.5rem 0 .75rem;
    }
    .section-label:first-child { margin-top: 0; }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 1.75rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f0f0f0;
    }
    @media (max-width: 600px) {
        .form-actions { flex-direction: column; }
        .form-actions .btn { width: 100%; justify-content: center; }
    }
    .disclaimer {
        font-size: .75rem;
        color: #9ca3af;
        text-align: center;
        margin-top: 1rem;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="portal-wrap">
<div class="p-card">
    <div class="step-indicator">
        <div class="step-dot active"><div class="dot">1</div> Fill Form</div>
        <div class="step-divider"></div>
        <div class="step-dot"><div class="dot">2</div> Confirmation</div>
        <div class="step-divider"></div>
        <div class="step-dot"><div class="dot">3</div> Claim Document</div>
    </div>

    <div class="page-hd">
        <h2><i class="fas fa-file-plus" style="color:var(--gold)"></i>&nbsp; Request a Barangay Document</h2>
        <p>Fill in your details below. All fields marked with <span style="color:var(--crimson)">*</span> are required.</p>
    </div>

    @if ($errors->any())
        <div class="p-alert p-alert-error">
            <i class="fas fa-exclamation-circle"></i>
            Please correct the errors below before submitting.
        </div>
    @endif

    <form method="POST" action="{{ route('portal.store') }}">
        @csrf

        <div class="section-label">Personal Information</div>
        <div class="form-row">
            <div class="form-group">
                <label>Full Name <span class="req">*</span></label>
                <input type="text" name="resident_name" class="form-control"
                       value="{{ old('resident_name') }}" placeholder="Last, First Middle" required>
                @error('resident_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Contact Number <span class="req">*</span></label>
                <input type="text" name="contact_number" class="form-control"
                       value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX" required>
                @error('contact_number') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Email Address <span style="color:#9ca3af;font-weight:400">(optional — for status notifications)</span></label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email') }}" placeholder="you@example.com">
            @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="section-label">Document Request</div>
        <div class="form-row">
            <div class="form-group">
                <label>Document Type <span class="req">*</span></label>
                <select name="document_type" class="form-control" required>
                    <option value="">— Select document —</option>
                    @foreach($documentTypes as $dt)
                        <option value="{{ $dt }}" {{ old('document_type') === $dt ? 'selected' : '' }}>{{ $dt }}</option>
                    @endforeach
                </select>
                @error('document_type') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Preferred Pick-up Date <span class="req">*</span></label>
                <input type="date" name="preferred_date" class="form-control"
                       value="{{ old('preferred_date') }}"
                       min="{{ now()->addDay()->format('Y-m-d') }}" required>
                @error('preferred_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Purpose / Reason for Request</label>
            <textarea name="purpose" class="form-control" rows="2"
                      placeholder="e.g., For employment purposes, school enrollment, etc.">{{ old('purpose') }}</textarea>
            @error('purpose') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('portal.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Submit Request
            </button>
        </div>

        <p class="disclaimer">
            By submitting this form, you confirm that all information provided is accurate.<br>
            Processing time is 1–3 business days. You will need to present a valid ID when claiming.
        </p>
    </form>
<<<<<<< Updated upstream
</div>{{-- /.p-card --}}
</div>{{-- /.portal-wrap --}}
=======
</div>
</div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
@endsection
