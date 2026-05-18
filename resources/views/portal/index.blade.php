@extends('layouts.portal')
@section('title', 'Resident Portal')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   HERO SECTION
══════════════════════════════════════════════════════════ */
.hero {
    background: linear-gradient(135deg, var(--navy) 0%, #1a3a6e 55%, #0a2855 100%);
    position: relative;
    overflow: hidden;
    padding: clamp(3rem, 8vw, 5.5rem) clamp(1rem, 5vw, 2.5rem);
}
/* Decorative radial glows */
.hero::before {
    content: '';
    position: absolute;
    top: -100px; right: -80px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(200,134,26,0.10) 0%, transparent 65%);
    pointer-events: none;
}
.hero::after {
    content: '';
    position: absolute;
    bottom: -80px; left: -60px;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 65%);
    pointer-events: none;
}
/* Bottom wave */
.hero-wave {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 60px;
    overflow: hidden;
    line-height: 0;
}
.hero-wave svg { display: block; width: 100%; height: 60px; }

.hero-inner {
    position: relative;
    z-index: 1;
    max-width: 1100px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}

.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: rgba(200,134,26,0.15);
    border: 1px solid rgba(200,134,26,0.35);
    border-radius: 99px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    color: var(--gold-light);
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 18px;
}

.hero-title {
    font-size: clamp(2rem, 4.5vw, 3rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    letter-spacing: -0.02em;
    margin-bottom: 18px;
}
.hero-title .gold { color: var(--gold-light); }

.hero-subtitle {
    font-size: clamp(15px, 2vw, 17px);
    color: rgba(255,255,255,0.70);
    line-height: 1.7;
    max-width: 440px;
    margin-bottom: 32px;
    font-weight: 300;
}

.hero-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.hero-stats {
    display: flex;
    gap: 2rem;
    margin-top: 2.5rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.10);
}
.hero-stat-num {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
}
.hero-stat-lbl {
    font-size: 12px;
    color: rgba(255,255,255,0.5);
    margin-top: 2px;
}

/* Hero visual (right column) */
.hero-visual {
    display: flex;
    justify-content: center;
    align-items: center;
}
.hero-app-mock {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: var(--radius-lg);
    padding: 24px;
    width: 100%;
    max-width: 320px;
    backdrop-filter: blur(8px);
}
.hero-mock-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid rgba(255,255,255,0.10);
}
.hero-mock-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; color: var(--navy); font-weight: 800;
}
.hero-mock-title { font-size: 13px; font-weight: 600; color: #fff; }
.hero-mock-sub   { font-size: 11px; color: rgba(255,255,255,0.5); }
.hero-mock-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3);
    border-radius: 99px; padding: 3px 10px;
    font-size: 11px; font-weight: 600; color: #4ade80;
}
.hero-mock-row {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px;
    background: rgba(255,255,255,0.05);
    border-radius: var(--radius-sm);
    margin-bottom: 8px;
}
.hero-mock-row:last-child { margin-bottom: 0; }
.hero-mock-row i { color: var(--gold-light); font-size: 14px; width: 18px; text-align: center; }
.hero-mock-row-text { font-size: 12px; color: rgba(255,255,255,0.75); font-weight: 500; }

/* ══════════════════════════════════════════════════════════
   SERVICES SECTION
══════════════════════════════════════════════════════════ */
.section {
    padding: clamp(3rem, 6vw, 5rem) clamp(1rem, 5vw, 2.5rem);
}
.section-inner { max-width: 1100px; margin: 0 auto; }

.section-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 10px;
}
.section-label::before {
    content: '';
    width: 24px; height: 2px;
    background: var(--gold);
    border-radius: 99px;
}
.section-title {
    font-size: clamp(1.5rem, 3.5vw, 2.25rem);
    font-weight: 800;
    color: var(--navy);
    line-height: 1.2;
    letter-spacing: -0.02em;
    margin-bottom: 10px;
}
.section-sub {
    font-size: clamp(14px, 2vw, 16px);
    color: var(--text-muted);
    max-width: 520px;
    line-height: 1.7;
    margin-bottom: 0;
}

/* Service Cards Grid */
.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
    margin-top: 2.5rem;
}
.svc-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.75rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.15s;
    box-shadow: var(--shadow-sm);
}
.svc-card:hover {
    border-color: var(--gold-border);
    box-shadow: 0 8px 28px rgba(200,134,26,0.12);
    transform: translateY(-3px);
}
.svc-icon {
    width: 52px; height: 52px;
    border-radius: var(--radius);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 1rem;
    flex-shrink: 0;
}
.svc-card h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: 6px;
    line-height: 1.3;
}
.svc-card p {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.65;
    flex: 1;
    margin-bottom: 16px;
}
.svc-card-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    color: var(--navy);
    transition: gap 0.15s;
}
.svc-card:hover .svc-card-link { gap: 9px; }
.svc-card-link i { font-size: 11px; }

/* ══════════════════════════════════════════════════════════
   HOW IT WORKS
══════════════════════════════════════════════════════════ */
.steps-section {
    background: var(--surface2);
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}

/* Desktop: horizontal with connecting line */
.steps-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    position: relative;
    margin-top: 2.5rem;
}
.steps-row::before {
    content: '';
    position: absolute;
    top: 28px;
    left: calc(12.5% + 14px);
    right: calc(12.5% + 14px);
    height: 2px;
    background: linear-gradient(90deg, var(--gold), var(--gold-border));
    z-index: 0;
}
.step-item {
    text-align: center;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}
.step-num {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--navy);
    color: #fff;
    font-size: 18px;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.1rem;
    border: 3px solid var(--surface2);
    box-shadow: 0 0 0 3px var(--gold);
    position: relative;
    z-index: 1;
}
.step-item h4 {
    font-size: 15px;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: 6px;
}
.step-item p {
    font-size: 13px;
    color: var(--text-muted);
    line-height: 1.6;
}

/* Mobile: vertical timeline */
@media (max-width: 700px) {
    .steps-row {
        grid-template-columns: 1fr;
        gap: 0;
        margin-top: 2rem;
    }
    .steps-row::before { display: none; }
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        text-align: left;
        padding: 0 0 2rem 0;
        position: relative;
    }
    .step-item:last-child { padding-bottom: 0; }
    /* Vertical connector */
    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 27px;
        top: 56px;
        bottom: 0;
        width: 2px;
        background: linear-gradient(180deg, var(--gold), var(--gold-border));
    }
    .step-num {
        flex-shrink: 0;
        margin: 0;
    }
    .step-item .step-body { flex: 1; padding-top: 12px; }
}

/* ══════════════════════════════════════════════════════════
   TRUST / SAFETY SECTION
══════════════════════════════════════════════════════════ */
.trust-section {
    background: linear-gradient(135deg, #f0f4ff 0%, #e8f4fd 100%);
    border-top: 1px solid #dce8f8;
    border-bottom: 1px solid #dce8f8;
}
.trust-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    align-items: center;
}
.trust-badge {
    width: 80px; height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    display: flex; align-items: center; justify-content: center;
    font-size: 34px; color: #fff;
    margin-bottom: 1.25rem;
    box-shadow: 0 8px 24px rgba(37,99,235,0.25);
}
.trust-title {
    font-size: clamp(1.4rem, 3vw, 1.8rem);
    font-weight: 800;
    color: var(--navy);
    margin-bottom: 10px;
    letter-spacing: -0.01em;
}
.trust-sub {
    font-size: 15px;
    color: var(--text-muted);
    line-height: 1.7;
    margin-bottom: 1.5rem;
}
.trust-points { display: flex; flex-direction: column; gap: 12px; }
.trust-point {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 15px;
    color: var(--text);
    line-height: 1.5;
}
.trust-point i {
    width: 24px; height: 24px;
    border-radius: 50%;
    background: rgba(29,78,216,0.10);
    color: #1d4ed8;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px;
    flex-shrink: 0;
    margin-top: 2px;
}
.trust-visual {
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border);
}
.trust-visual-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: var(--radius);
    margin-bottom: 8px;
    background: var(--surface2);
    font-size: 14px;
    color: var(--text);
    font-weight: 500;
}
.trust-visual-row:last-child { margin-bottom: 0; }
.trust-visual-row i {
    font-size: 15px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
}

/* ══════════════════════════════════════════════════════════
   FAQ ACCORDION
══════════════════════════════════════════════════════════ */
.faq-list { margin-top: 2rem; display: flex; flex-direction: column; gap: 8px; }
.faq-item {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: border-color 0.15s;
}
.faq-item.open { border-color: var(--navy-border); }
.faq-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 18px 20px;
    background: none;
    border: none;
    font-family: 'Poppins', sans-serif;
    font-size: 15px;
    font-weight: 600;
    color: var(--navy);
    cursor: pointer;
    text-align: left;
    min-height: 60px;
    transition: background 0.1s;
}
.faq-btn:hover { background: var(--surface2); }
.faq-icon {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--navy-pale);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px;
    color: var(--navy);
    flex-shrink: 0;
    transition: transform 0.2s, background 0.15s;
}
.faq-item.open .faq-icon {
    transform: rotate(45deg);
    background: var(--navy);
    color: #fff;
}
.faq-body {
    display: none;
    padding: 0 20px 18px;
    font-size: 15px;
    color: var(--text-muted);
    line-height: 1.7;
}
.faq-item.open .faq-body { display: block; }

/* ══════════════════════════════════════════════════════════
   BOTTOM CTA BANNER
══════════════════════════════════════════════════════════ */
.cta-banner {
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    padding: clamp(2.5rem, 6vw, 4.5rem) clamp(1rem, 5vw, 2.5rem);
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-banner::before {
    content: '';
    position: absolute;
    top: -100px; right: -100px;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(200,134,26,0.10) 0%, transparent 60%);
    pointer-events: none;
}
.cta-banner-inner { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; }
.cta-banner h2 {
    font-size: clamp(1.5rem, 4vw, 2.25rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
    letter-spacing: -0.01em;
}
.cta-banner p {
    font-size: clamp(14px, 2vw, 16px);
    color: rgba(255,255,255,0.65);
    line-height: 1.7;
    margin-bottom: 2rem;
    font-weight: 300;
}
.cta-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

/* ══════════════════════════════════════════════════════════
   NOTICE BOX
══════════════════════════════════════════════════════════ */
.notice-box {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: var(--gold-pale);
    border: 1.5px solid var(--gold-border);
    border-left: 4px solid var(--gold);
    border-radius: var(--radius);
    padding: 16px 18px;
    font-size: 14px;
    color: #78450a;
    line-height: 1.6;
    margin-top: 2rem;
}
.notice-box i { color: var(--gold); flex-shrink: 0; margin-top: 2px; }

/* ══════════════════════════════════════════════════════════
   RESPONSIVE OVERRIDES
══════════════════════════════════════════════════════════ */
@media (max-width: 900px) {
    .hero-inner    { grid-template-columns: 1fr; }
    .hero-visual   { display: none; }
    .hero-subtitle { max-width: 100%; }
    .trust-grid    { grid-template-columns: 1fr; }
    .trust-visual  { display: none; }
}

@media (max-width: 600px) {
    .services-grid { grid-template-columns: 1fr; }
    .hero-stats    { gap: 1.25rem; }
    .hero-actions  { flex-direction: column; }
    .hero-actions .btn { width: 100%; }
    .cta-actions   { flex-direction: column; align-items: stretch; }
    .cta-actions .btn { width: 100%; }
}
</style>
@endpush

@section('content')

{{-- ═══════════ HERO ═══════════ --}}
<section class="hero" aria-label="Welcome">
    <div class="hero-inner">
        <div class="hero-content">
            <div class="hero-eyebrow">
                <i class="fas fa-shield-halved"></i>
                Official Barangay Digital Services
            </div>
            <h1 class="hero-title">
                Your Barangay<br>
                Services. <span class="gold">Digitized.</span>
            </h1>
            <p class="hero-subtitle">
                Request official documents and track your appointment online — no need to visit the barangay hall just to inquire. Fast, simple, and secure.
            </p>
            <div class="hero-actions">
                <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="{{ route('portal.track') }}" class="btn btn-white-outline btn-lg">
                    <i class="fas fa-search"></i> Track My Status
                </a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">4</div>
                    <div class="hero-stat-lbl">Document Types</div>
                </div>
                <div>
                    <div class="hero-stat-num">1–3</div>
                    <div class="hero-stat-lbl">Business Days</div>
                </div>
                <div>
                    <div class="hero-stat-num">100%</div>
                    <div class="hero-stat-lbl">Free Service</div>
                </div>
            </div>
        </div>

        {{-- App mockup (hidden on mobile) --}}
        <div class="hero-visual" aria-hidden="true">
            <div class="hero-app-mock">
                <div class="hero-mock-header">
                    <div class="hero-mock-avatar">B</div>
                    <div style="flex:1">
                        <div class="hero-mock-title">Barangay New Era</div>
                        <div class="hero-mock-sub">Resident Portal</div>
                    </div>
                    <span class="hero-mock-chip"><i class="fas fa-circle" style="font-size:6px"></i> Online</span>
                </div>
                <div class="hero-mock-row">
                    <i class="fas fa-file-shield"></i>
                    <span class="hero-mock-row-text">Barangay Clearance</span>
                    <span style="font-size:11px;color:rgba(255,255,255,0.4);margin-left:auto">Ready</span>
                </div>
                <div class="hero-mock-row">
                    <i class="fas fa-hand-holding-heart"></i>
                    <span class="hero-mock-row-text">Certificate of Indigency</span>
                    <span style="font-size:11px;color:rgba(255,255,255,0.4);margin-left:auto">Pending</span>
                </div>
                <div class="hero-mock-row">
                    <i class="fas fa-house-circle-check"></i>
                    <span class="hero-mock-row-text">Certificate of Residency</span>
                    <span style="font-size:11px;color:rgba(255,255,255,0.4);margin-left:auto">Released</span>
                </div>
                <div style="margin-top:16px;padding:12px 14px;background:rgba(200,134,26,0.15);border-radius:var(--radius-sm);border:1px solid rgba(200,134,26,0.3)">
                    <div style="font-size:11px;color:rgba(200,160,32,0.9);font-weight:600;margin-bottom:3px">
                        <i class="fas fa-clock"></i> Processing Time
                    </div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.8)">1–3 business days after confirmation</div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero-wave">
        <svg viewBox="0 0 1440 60" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,60 L0,30 Q360,0 720,30 Q1080,60 1440,30 L1440,60 Z" fill="#F0F4F8"/>
        </svg>
    </div>
</section>

{{-- ═══════════ SERVICES ═══════════ --}}
<section class="section" id="services">
    <div class="section-inner">
        <div class="section-label">What We Offer</div>
        <h2 class="section-title">Government Services, Simplified.</h2>
        <p class="section-sub">All barangay document services available in one place. Request online and claim at the hall.</p>

        <div class="services-grid">
            <div class="svc-card">
                <div class="svc-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
                    <i class="fas fa-file-shield"></i>
                </div>
                <h3>Barangay Clearance</h3>
                <p>Certificate of good standing within the barangay for employment, permits, loans, and other purposes.</p>
                <a href="{{ route('portal.request') }}" class="svc-card-link">
                    Request Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="svc-card">
                <div class="svc-icon" style="background:rgba(200,134,26,0.10);color:var(--gold)">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Certificate of Indigency</h3>
                <p>For residents who need to avail of government assistance programs or medical financial aid.</p>
                <a href="{{ route('portal.request') }}" class="svc-card-link">
                    Request Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="svc-card">
                <div class="svc-icon" style="background:rgba(22,101,52,0.10);color:#16a34a">
                    <i class="fas fa-house-circle-check"></i>
                </div>
                <h3>Certificate of Residency</h3>
                <p>Proof of residence for government transactions, school enrollment, and other official requirements.</p>
                <a href="{{ route('portal.request') }}" class="svc-card-link">
                    Request Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="svc-card">
                <div class="svc-icon" style="background:rgba(37,99,235,0.08);color:#2563eb">
                    <i class="fas fa-store"></i>
                </div>
                <h3>Business Clearance</h3>
                <p>Required for new business registration and annual renewal of business permits within the barangay.</p>
                <a href="{{ route('portal.request') }}" class="svc-card-link">
                    Request Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ HOW IT WORKS ═══════════ --}}
<section class="section steps-section" id="how-it-works">
    <div class="section-inner">
        <div class="section-label">Simple Process</div>
        <h2 class="section-title">Get Started in 4 Easy Steps.</h2>
        <p class="section-sub">From request to release — the entire process is designed to be as simple as possible for every resident.</p>

        <div class="steps-row">
            <div class="step-item">
                <div class="step-num">1</div>
                <div class="step-body">
                    <h4>Fill the Form</h4>
                    <p>Provide your personal details and choose the barangay document you need online.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">2</div>
                <div class="step-body">
                    <h4>Get Your Number</h4>
                    <p>You'll receive an appointment number immediately after submitting your request.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">3</div>
                <div class="step-body">
                    <h4>Wait for Confirmation</h4>
                    <p>Barangay staff will confirm your preferred schedule within 1–2 business days.</p>
                </div>
            </div>
            <div class="step-item">
                <div class="step-num">4</div>
                <div class="step-body">
                    <h4>Claim Your Document</h4>
                    <p>Visit the barangay hall on your confirmed date with a valid ID to claim your document.</p>
                </div>
            </div>
        </div>

        <div class="notice-box">
            <i class="fas fa-circle-info fa-fw"></i>
            <div>
                <strong>Important:</strong> This portal is for <em>scheduling only</em>. You still need to visit the barangay hall in person to claim your document and present valid ID. For urgent requests, please visit the hall directly during office hours.
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ TRUST / SAFETY ═══════════ --}}
<section class="section trust-section" id="safety">
    <div class="section-inner">
        <div class="trust-grid">
            <div>
                <div class="trust-badge" aria-hidden="true">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h2 class="trust-title">Your Data is Safe With Us.</h2>
                <p class="trust-sub">
                    We are committed to protecting the privacy and security of every resident's information. Your data is used only for processing your barangay document requests.
                </p>
                <div class="trust-points">
                    <div class="trust-point">
                        <i class="fas fa-check"></i>
                        Personal data used exclusively for document processing
                    </div>
                    <div class="trust-point">
                        <i class="fas fa-check"></i>
                        Information protected under the Data Privacy Act of 2012
                    </div>
                    <div class="trust-point">
                        <i class="fas fa-check"></i>
                        No third-party sharing of resident information
                    </div>
                    <div class="trust-point">
                        <i class="fas fa-check"></i>
                        Managed by official Barangay New Era staff only
                    </div>
                </div>
            </div>

            <div class="trust-visual" aria-hidden="true">
                <div style="font-size:12px;font-weight:700;letter-spacing:0.10em;text-transform:uppercase;color:var(--text-subtle);margin-bottom:14px">
                    System Protections
                </div>
                <div class="trust-visual-row">
                    <i class="fas fa-lock" style="color:#1d4ed8"></i>
                    HTTPS Secure Connection
                </div>
                <div class="trust-visual-row">
                    <i class="fas fa-database" style="color:#16a34a"></i>
                    Encrypted Data Storage
                </div>
                <div class="trust-visual-row">
                    <i class="fas fa-user-shield" style="color:var(--gold)"></i>
                    Staff-Only Data Access
                </div>
                <div class="trust-visual-row">
                    <i class="fas fa-file-contract" style="color:var(--navy)"></i>
                    Data Privacy Act Compliant
                </div>
                <div class="trust-visual-row">
                    <i class="fas fa-eye-slash" style="color:#7c3aed"></i>
                    No Third-Party Sharing
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ FAQ ═══════════ --}}
<section class="section" id="faq">
    <div class="section-inner" style="max-width:760px">
        <div class="section-label">Support</div>
        <h2 class="section-title">Frequently Asked Questions.</h2>

        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)">
                    <span>How long does processing take?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Processing typically takes <strong>1–3 business days</strong> from the date your request is confirmed by barangay staff. For Barangay Clearance, it is usually ready within 1 business day. You will need to visit the hall on your confirmed schedule to claim.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)">
                    <span>What ID do I need to bring?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Any government-issued ID is accepted: <strong>PhilSys National ID, Passport, Driver's License, SSS/GSIS/Pag-IBIG ID, Voter's ID, or PhilHealth ID</strong>. Make sure your name on the ID matches the name you provided in your request.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)">
                    <span>Is there a processing fee?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Barangay documents are provided <strong>free of charge</strong> to all residents of Barangay New Era. There are no fees for requesting or claiming your documents through this portal.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)">
                    <span>Can I request on behalf of someone else?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    For most documents, the <strong>requesting resident must be present</strong> when claiming. If another person is claiming on your behalf, they must present a <strong>Special Power of Attorney (SPA)</strong> along with their own valid ID and a copy of the resident's valid ID.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)">
                    <span>What if my appointment number is not found?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Double-check that you entered the complete appointment number (e.g., <code style="background:#f3f4f6;padding:1px 6px;border-radius:4px">APT-20260507-AB12</code>). Appointment numbers are case-sensitive and should be entered exactly as shown on your confirmation page. If the problem persists, please visit the barangay hall directly.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════ BOTTOM CTA ═══════════ --}}
<section class="cta-banner">
    <div class="cta-banner-inner">
        <h2>Ready to Request Your Document?</h2>
        <p>Start your request online in under 3 minutes. No account needed — just fill out the form and we'll handle the rest.</p>
        <div class="cta-actions">
            <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-file-plus"></i> Request a Document
            </a>
            <a href="{{ route('portal.track') }}" class="btn btn-white-outline btn-lg">
                <i class="fas fa-search"></i> Track Existing Request
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function toggleFaq(btn) {
    const item = btn.closest('.faq-item');
    const isOpen = item.classList.contains('open');
    // Close all
    document.querySelectorAll('.faq-item.open').forEach(function (el) {
        el.classList.remove('open');
    });
    // Open clicked if it wasn't open
    if (!isOpen) item.classList.add('open');
}
</script>
@endpush
