@extends('layouts.portal')
@section('title', 'Resident Portal')

@push('styles')
<style>
    .hero {
        text-align: center;
        padding: 3rem 1rem 2.5rem;
    }
    .hero-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: var(--navy);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1.25rem;
    }
    .hero h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: .5rem;
    }
    .hero p {
        color: #4b5563;
        font-size: .92rem;
        max-width: 540px;
        margin: 0 auto 2rem;
    }
    .hero-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    @media (max-width: 480px) {
        .hero-actions { flex-direction: column; align-items: stretch; }
        .hero-actions .btn { justify-content: center; }
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-top: 2rem;
    }
    .service-card {
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
        border: 1.5px solid #e5e7eb;
    }
    .service-card .svc-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--radius);
        background: var(--navy-pale);
        color: var(--navy);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin: 0 auto .75rem;
    }
    .service-card h3 {
        font-size: .9rem;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: .35rem;
    }
    .service-card p {
        font-size: .78rem;
        color: #6b7280;
        line-height: 1.5;
    }

    .steps-section {
        margin-top: 2.5rem;
    }
    .steps-section h2 {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 1.25rem;
        text-align: center;
    }
    .steps-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .step-item {
        flex: 1;
        min-width: 160px;
        max-width: 200px;
        text-align: center;
    }
    .step-num {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--gold);
        color: #fff;
        font-weight: 700;
        font-size: .95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto .6rem;
    }
    .step-item p {
        font-size: .78rem;
        color: #4b5563;
        line-height: 1.5;
    }

    .notice-box {
        background: var(--gold-pale);
        border: 1.5px solid var(--gold-border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        margin-top: 2rem;
        font-size: .82rem;
        color: #78450a;
        display: flex;
        gap: .75rem;
        align-items: flex-start;
    }
    .notice-box i { margin-top: .1rem; color: var(--gold); flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="hero">
    <div class="hero-icon"><i class="fas fa-landmark"></i></div>
    <h1>Barangay New Era Resident Portal</h1>
    <p>Request official barangay documents online and track your appointment status — no need to visit the hall just to inquire.</p>
    <div class="hero-actions">
        <a href="{{ route('portal.request') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-file-plus"></i> Request a Document
        </a>
        <a href="{{ route('portal.track') }}" class="btn btn-outline btn-lg">
            <i class="fas fa-search"></i> Track My Request
        </a>
    </div>
</div>

<div class="services-grid">
    <div class="service-card">
        <div class="svc-icon"><i class="fas fa-file-shield"></i></div>
        <h3>Barangay Clearance</h3>
        <p>Certificate of good standing within the barangay for employment, permits, and other purposes.</p>
    </div>
    <div class="service-card">
        <div class="svc-icon"><i class="fas fa-hand-holding-heart"></i></div>
        <h3>Certificate of Indigency</h3>
        <p>For residents who need to avail of government assistance programs or medical financial aid.</p>
    </div>
    <div class="service-card">
        <div class="svc-icon"><i class="fas fa-house-circle-check"></i></div>
        <h3>Certificate of Residency</h3>
        <p>Proof of residence for government transactions, school enrollment, and other requirements.</p>
    </div>
    <div class="service-card">
        <div class="svc-icon"><i class="fas fa-store"></i></div>
        <h3>Business Clearance</h3>
        <p>Required for new business registration and annual renewal of business permits.</p>
    </div>
</div>

<div class="steps-section">
    <h2><i class="fas fa-list-check" style="color:var(--gold)"></i>&nbsp; How It Works</h2>
    <div class="steps-row">
        <div class="step-item">
            <div class="step-num">1</div>
            <p><strong>Fill out the form</strong><br>Provide your details and choose the document you need.</p>
        </div>
        <div class="step-item">
            <div class="step-num">2</div>
            <p><strong>Get your number</strong><br>You'll receive an appointment number after submitting.</p>
        </div>
        <div class="step-item">
            <div class="step-num">3</div>
            <p><strong>Wait for confirmation</strong><br>Staff will confirm your preferred schedule.</p>
        </div>
        <div class="step-item">
            <div class="step-num">4</div>
            <p><strong>Claim your document</strong><br>Visit the barangay hall on your confirmed date.</p>
        </div>
    </div>
</div>

<div class="notice-box">
    <i class="fas fa-circle-info fa-fw"></i>
    <div>
        <strong>Important:</strong> This portal is for scheduling only. You still need to visit the barangay hall to claim your document and present valid ID. Processing time is typically 1–3 business days. For urgent requests, please visit the hall directly.
    </div>
</div>
@endsection
