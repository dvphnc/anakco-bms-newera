@extends('layouts.portal')
@section('title', 'Request Submitted')

@push('styles')
<style>
    .confirm-wrap { text-align: center; padding: 1rem 0; }
    .confirm-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #ecfdf5;
        color: #16a34a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.25rem;
        border: 3px solid #16a34a;
    }
    .confirm-wrap h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .4rem;
    }
    .confirm-wrap p {
        color: #6b7280;
        font-size: .88rem;
        max-width: 440px;
        margin: 0 auto 1.5rem;
    }

    .apt-number-box {
        display: inline-block;
        background: var(--navy);
        color: #fff;
        border-radius: var(--radius);
        padding: .9rem 2rem;
        margin-bottom: 1.5rem;
    }
    .apt-number-box .label {
        font-size: .72rem;
        opacity: .7;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: .25rem;
    }
    .apt-number-box .number {
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: .1em;
        color: var(--gold);
    }

    .detail-table {
        width: 100%;
        max-width: 480px;
        margin: 0 auto 1.75rem;
        border-collapse: collapse;
        text-align: left;
    }
    .detail-table td {
        padding: .5rem .75rem;
        font-size: .83rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-table td:first-child {
        color: #9ca3af;
        font-weight: 500;
        width: 40%;
    }
    .detail-table td:last-child { color: var(--navy); font-weight: 600; }

    .status-badge {
        display: inline-block;
        padding: .25rem .75rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 600;
        background: var(--gold-pale);
        color: var(--gold);
        border: 1px solid var(--gold-border);
    }

    .confirm-actions {
        display: flex;
        gap: .75rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .save-notice {
        font-size: .75rem;
        color: #9ca3af;
        margin-top: 1.25rem;
        background: #f9fafb;
        border-radius: var(--radius-sm);
        padding: .7rem 1rem;
    }
</style>
@endpush

@section('content')
<div class="p-card">
    <div class="confirm-wrap">
        <div class="confirm-icon"><i class="fas fa-check"></i></div>
        <h2>Request Submitted Successfully!</h2>
        <p>Your document request has been received. Save your appointment number below — you will need it to track your status.</p>

        <div class="apt-number-box">
            <div class="label">Your Appointment Number</div>
            <div class="number">{{ $appointment->appointment_number }}</div>
        </div>

        <table class="detail-table">
            <tr>
                <td>Name</td>
                <td>{{ $appointment->resident_name }}</td>
            </tr>
            <tr>
                <td>Document</td>
                <td>{{ $appointment->document_type }}</td>
            </tr>
            <tr>
                <td>Preferred Date</td>
                <td>{{ $appointment->preferred_date->format('F j, Y') }}</td>
            </tr>
            @if($appointment->purpose)
            <tr>
                <td>Purpose</td>
                <td>{{ $appointment->purpose }}</td>
            </tr>
            @endif
            <tr>
                <td>Status</td>
                <td><span class="status-badge">{{ $appointment->status }}</span></td>
            </tr>
        </table>

        <div class="confirm-actions">
            <a href="{{ route('portal.track') }}?apt={{ $appointment->appointment_number }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Track My Status
            </a>
            <a href="{{ route('portal.index') }}" class="btn btn-outline">
                <i class="fas fa-home"></i> Back to Portal
            </a>
        </div>

        <div class="save-notice">
            <i class="fas fa-bookmark"></i>
            <strong>Tip:</strong> Screenshot or note down your appointment number. Staff will confirm your schedule within 1–2 business days. Bring a valid ID when claiming your document.
        </div>
    </div>
</div>
@endsection
