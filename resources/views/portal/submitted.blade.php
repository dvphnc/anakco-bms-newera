@extends('layouts.portal')
@section('title', 'Request Submitted')

@push('styles')
<style>
.confirm-wrap {
    text-align: center;
    padding: clamp(2rem, 6vw, 3.5rem) 1.5rem;
}
.confirm-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: #ecfdf5;
    border: 2px solid #86efac;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: #16a34a;
    margin: 0 auto 1.5rem;
    animation: pop-in .4s cubic-bezier(.34,1.56,.64,1);
}
@keyframes pop-in { from { transform: scale(.4); opacity: 0; } }
.confirm-number {
    display: inline-block;
    font-family: 'Courier New', monospace;
    font-size: 1.4rem; font-weight: 700;
    color: var(--navy);
    background: var(--navy-pale);
    border: 1.5px dashed var(--navy-border);
    border-radius: var(--radius-sm);
    padding: .45rem 1.25rem;
    margin: .75rem 0 1.5rem;
    letter-spacing: .06em;
    cursor: pointer;
}
.confirm-number::after {
    content: ' 📋';
    font-size: .8em;
}
.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .6rem .5rem;
    text-align: left;
    background: #f8f9fa;
    border: 1px solid #e2e6ea;
    border-radius: var(--radius-sm);
    padding: 1rem 1.25rem;
    margin: 1.25rem 0 1.75rem;
    font-size: .84rem;
}
.detail-grid dt { color: #6b7280; font-weight: 500; }
.detail-grid dd { color: var(--navy); font-weight: 600; }
@media (max-width: 500px) {
    .detail-grid { grid-template-columns: 1fr; gap: .3rem; }
    .detail-grid dt { margin-top: .4rem; }
    .detail-grid dt:first-child { margin-top: 0; }
}
.copy-tip {
    font-size: .75rem; color: #9ca3af;
    margin-top: .3rem; margin-bottom: 1.25rem;
}
.confirm-actions {
    display: flex; gap: .75rem; justify-content: center;
    flex-wrap: wrap; margin-top: .5rem;
}
</style>
@endpush

@section('content')
<div class="portal-wrap-sm">
<div class="p-card">
    <div class="confirm-wrap">

        <div class="confirm-icon"><i class="fas fa-check"></i></div>

        <h2 style="font-size:1.3rem;font-weight:700;color:var(--navy);margin-bottom:.4rem">
            @if($type === 'blotter') Blotter Report Submitted
            @else Business Permit Request Submitted
            @endif
        </h2>
        <p style="font-size:.9rem;color:#6b7280;max-width:440px;margin:0 auto .75rem">
            @if($type === 'blotter')
                Your blotter report has been received. A barangay staff member will contact you for follow-up.
            @else
                Your business permit application has been received and is now under review.
            @endif
        </p>

        {{-- Reference number --}}
        <div id="refNum"
             class="confirm-number"
             title="Click to copy"
             onclick="copyRef('{{ $number }}')">{{ $number }}</div>
        <div class="copy-tip" id="copyTip">Click the number above to copy</div>

        {{-- Summary --}}
        <dl class="detail-grid">
            @if($type === 'blotter')
                <dt>Complainant</dt>
                <dd>{{ $record->complainant_name }}</dd>
                <dt>Incident Type</dt>
                <dd>{{ $record->incident_type }}</dd>
                <dt>Incident Date</dt>
                <dd>{{ $record->incident_date->format('F d, Y') }}</dd>
                <dt>Status</dt>
                <dd><span style="color:#c8861a;font-weight:700">Pending</span></dd>
                <dt>Submitted</dt>
                <dd>{{ $record->created_at->format('M d, Y g:i A') }}</dd>
                @if($record->email)
                    <dt>Notification</dt>
                    <dd>Will be sent to {{ $record->email }}</dd>
                @endif
            @else
                <dt>Owner</dt>
                <dd>{{ $record->owner_name }}</dd>
                <dt>Business Name</dt>
                <dd>{{ $record->business_name }}</dd>
                <dt>Business Type</dt>
                <dd>{{ $record->business_type }}</dd>
                <dt>Status</dt>
                <dd><span style="color:#c8861a;font-weight:700">Pending</span></dd>
                <dt>Submitted</dt>
                <dd>{{ $record->created_at->format('M d, Y g:i A') }}</dd>
                @if($record->email)
                    <dt>Notification</dt>
                    <dd>Will be sent to {{ $record->email }}</dd>
                @endif
            @endif
        </dl>

        <div class="p-alert p-alert-warning" style="text-align:left">
            <i class="fas fa-triangle-exclamation"></i>
            <span>
                <strong>Save your reference number:</strong> {{ $number }}<br>
                You will need this number for follow-up inquiries. Visit the Barangay Hall (Mon–Fri, 8AM–5PM) if needed.
            </span>
        </div>

        <div class="confirm-actions">
            <a href="{{ route('portal.index') }}" class="btn btn-outline">
                <i class="fas fa-home"></i> Back to Portal
            </a>
            @if($type === 'blotter')
                <a href="{{ route('portal.blotter') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> File Another Report
                </a>
            @else
                <a href="{{ route('portal.business') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Submit Another
                </a>
            @endif
        </div>

    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function copyRef(num) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(num).then(function () {
            document.getElementById('copyTip').textContent = '✓ Copied to clipboard!';
            setTimeout(function () {
                document.getElementById('copyTip').textContent = 'Click the number above to copy';
            }, 2500);
        });
    } else {
        var el = document.getElementById('refNum');
        var range = document.createRange();
        range.selectNodeContents(el);
        window.getSelection().removeAllRanges();
        window.getSelection().addRange(range);
        document.execCommand('copy');
        window.getSelection().removeAllRanges();
        document.getElementById('copyTip').textContent = '✓ Copied!';
    }
}
</script>
@endpush
