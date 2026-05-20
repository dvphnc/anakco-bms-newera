@extends('layouts.portal')
@section('title', 'Resident Portal')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   PAGE BACKGROUND
══════════════════════════════════════════════════════════ */
body { background: #f7f8fa; }

/* ══════════════════════════════════════════════════════════
   HERO — eGov-style: light bg, functional split layout
══════════════════════════════════════════════════════════ */
.hero {
    background: #fff;
    border-bottom: 1px solid #e5e9f0;
    padding: clamp(2.5rem, 6vw, 4rem) clamp(1rem, 5vw, 2.5rem);
}
.hero-inner {
    max-width: 1120px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    align-items: center;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(13,33,68,.06);
    border: 1px solid rgba(13,33,68,.12);
    border-radius: 99px;
    padding: 5px 14px;
    font-size: .72rem;
    font-weight: 700;
    color: var(--navy);
    letter-spacing: .06em;
    text-transform: uppercase;
    margin-bottom: 1.1rem;
}
.hero-badge i { color: var(--gold); font-size: .65rem; }
.hero-title {
    font-size: clamp(1.85rem, 4vw, 2.75rem);
    font-weight: 800;
    color: var(--navy);
    line-height: 1.15;
    letter-spacing: -0.025em;
    margin-bottom: .85rem;
}
.hero-title .accent { color: var(--gold); }
.hero-desc {
    font-size: clamp(.9rem, 1.8vw, 1rem);
    color: #4b5563;
    line-height: 1.75;
    max-width: 480px;
    margin-bottom: 2rem;
    font-weight: 400;
}
.hero-ctas {
    display: flex;
    gap: .75rem;
    flex-wrap: wrap;
    margin-bottom: 2.25rem;
}
.hero-pills {
    display: flex;
    align-items: center;
    gap: .5rem;
    flex-wrap: wrap;
    padding-top: 1.75rem;
    border-top: 1px solid #e5e9f0;
}
.hero-pills-label {
    font-size: .72rem;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .08em;
    white-space: nowrap;
}
.hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--navy-pale);
    border: 1px solid var(--navy-border);
    border-radius: 99px;
    padding: 4px 12px;
    font-size: .74rem;
    font-weight: 600;
    color: var(--navy);
    text-decoration: none;
    transition: background .15s, border-color .15s;
}
.hero-pill:hover { background: var(--navy); color: #fff; border-color: var(--navy); }
.hero-pill i { font-size: .65rem; }

/* ── Track Panel (right side) ── */
.track-panel {
    background: var(--surface);
    border: 1.5px solid #e5e9f0;
    border-radius: var(--radius-lg);
    padding: 1.75rem;
    box-shadow: 0 4px 20px rgba(13,33,68,.07);
}
.track-panel-head {
    display: flex;
    align-items: center;
    gap: .65rem;
    margin-bottom: 1.25rem;
}
.track-panel-icon {
    width: 38px; height: 38px;
    border-radius: var(--radius-sm);
    background: rgba(13,33,68,.07);
    display: flex; align-items: center; justify-content: center;
    color: var(--navy);
    font-size: .9rem;
    flex-shrink: 0;
}
.track-panel-head h3 {
    font-size: .95rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.2;
}
.track-panel-head span {
    font-size: .75rem;
    color: var(--muted);
}
.track-form { display: flex; flex-direction: column; gap: .65rem; margin-bottom: 1.25rem; }
.track-input-wrap { position: relative; }
.track-input-wrap i {
    position: absolute;
    left: .85rem; top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: .85rem;
    pointer-events: none;
}
.track-input-wrap input {
    width: 100%;
    height: 48px;
    padding: 0 .85rem 0 2.5rem;
    border: 1.5px solid #d1d5db;
    border-radius: var(--radius-sm);
    font-family: 'Poppins', sans-serif;
    font-size: .88rem;
    color: var(--text);
    background: #fafafa;
    transition: border-color .2s, box-shadow .2s;
    letter-spacing: .02em;
}
.track-input-wrap input:focus {
    outline: none;
    border-color: var(--navy);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(13,33,68,.08);
}
.track-input-wrap input::placeholder { color: #b0b8c4; }
.track-btn {
    height: 46px;
    background: var(--navy);
    color: #fff;
    border: none;
    border-radius: var(--radius-sm);
    font-family: 'Poppins', sans-serif;
    font-size: .88rem;
    font-weight: 600;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    gap: .5rem;
    transition: background .2s, box-shadow .2s;
    width: 100%;
}
.track-btn:hover { background: var(--navy-mid); box-shadow: 0 4px 14px rgba(13,33,68,.25); }
.track-divider {
    display: flex; align-items: center; gap: .65rem;
    font-size: .72rem; color: #9ca3af; font-weight: 600;
    text-transform: uppercase; letter-spacing: .08em;
    margin: .5rem 0;
}
.track-divider::before, .track-divider::after {
    content: ''; flex: 1; height: 1px; background: #e5e9f0;
}
.track-shortcuts { display: flex; flex-direction: column; gap: .45rem; }
.track-shortcut {
    display: flex; align-items: center; gap: .65rem;
    padding: .65rem .85rem;
    border: 1px solid #e5e9f0;
    border-radius: var(--radius-sm);
    text-decoration: none;
    transition: border-color .15s, background .15s, transform .1s;
    background: #fafafa;
}
.track-shortcut:hover { border-color: var(--gold-border); background: var(--gold-pale); transform: translateX(2px); }
.track-shortcut-icon {
    width: 32px; height: 32px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem;
    flex-shrink: 0;
}
.track-shortcut-text { flex: 1; min-width: 0; }
.track-shortcut-text strong { display: block; font-size: .82rem; font-weight: 600; color: var(--navy); }
.track-shortcut-text span { font-size: .72rem; color: var(--muted); }
.track-shortcut i.arrow { color: #d1d5db; font-size: .75rem; }
.track-shortcut:hover i.arrow { color: var(--gold); }

/* ══════════════════════════════════════════════════════════
   SERVICES DIRECTORY
══════════════════════════════════════════════════════════ */
.page-section {
    padding: clamp(2.5rem, 5vw, 4rem) clamp(1rem, 5vw, 2.5rem);
}
.page-section-inner { max-width: 1120px; margin: 0 auto; }

.section-header { margin-bottom: 2rem; }
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: .6rem;
}
.section-tag::before { content: ''; width: 20px; height: 2px; background: var(--gold); border-radius: 99px; }
.section-h2 {
    font-size: clamp(1.35rem, 3vw, 1.9rem);
    font-weight: 800;
    color: var(--navy);
    letter-spacing: -0.02em;
    line-height: 1.2;
    margin-bottom: .5rem;
}
.section-p { font-size: .92rem; color: #6b7280; line-height: 1.7; max-width: 560px; }

/* Service directory tiles */
.svc-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}
.svc-tile {
    background: #fff;
    border: 1.5px solid #e5e9f0;
    border-radius: var(--radius);
    padding: 1.4rem 1.35rem 1.25rem;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    gap: 0;
    transition: border-color .2s, box-shadow .15s, transform .15s;
    position: relative;
    overflow: hidden;
}
.svc-tile::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 3px; height: 100%;
    border-radius: 3px 0 0 3px;
    transition: opacity .2s;
    opacity: 0;
}
.svc-tile:hover {
    border-color: rgba(13,33,68,.2);
    box-shadow: 0 4px 18px rgba(13,33,68,.08);
    transform: translateY(-2px);
}
.svc-tile:hover::before { opacity: 1; }
.svc-tile-icon-wrap {
    width: 46px; height: 46px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    margin-bottom: .9rem;
    flex-shrink: 0;
}
.svc-tile h3 { font-size: .95rem; font-weight: 700; color: var(--navy); margin-bottom: .4rem; line-height: 1.3; }
.svc-tile p { font-size: .8rem; color: #6b7280; line-height: 1.6; flex: 1; margin-bottom: .85rem; }
.svc-tile-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .78rem;
    font-weight: 700;
    color: var(--navy);
    text-transform: uppercase;
    letter-spacing: .04em;
    transition: gap .15s;
}
.svc-tile:hover .svc-tile-link { gap: 8px; }
.svc-tile-link i { font-size: .65rem; }

/* Color variants */
.svc-navy  .svc-tile-icon-wrap { background: rgba(13,33,68,.07);  color: var(--navy); }
.svc-gold  .svc-tile-icon-wrap { background: rgba(200,134,26,.1); color: var(--gold); }
.svc-green .svc-tile-icon-wrap { background: rgba(22,101,52,.08); color: #16a34a; }
.svc-blue  .svc-tile-icon-wrap { background: rgba(37,99,235,.07); color: #2563eb; }
.svc-red   .svc-tile-icon-wrap { background: rgba(155,28,28,.07); color: var(--crimson); }
.svc-teal  .svc-tile-icon-wrap { background: rgba(14,116,144,.07);color: #0e7490; }
.svc-navy::before  { background: var(--navy); }
.svc-gold::before  { background: var(--gold); }
.svc-green::before { background: #16a34a; }
.svc-blue::before  { background: #2563eb; }
.svc-red::before   { background: var(--crimson); }
.svc-teal::before  { background: #0e7490; }

/* ══════════════════════════════════════════════════════════
   ANNOUNCEMENT / NOTICE BAR
══════════════════════════════════════════════════════════ */
.announce-bar {
    background: #fff;
    border-top: 1px solid #e5e9f0;
    border-bottom: 1px solid #e5e9f0;
    padding: 1rem clamp(1rem, 5vw, 2.5rem);
}
.announce-inner {
    max-width: 1120px;
    margin: 0 auto;
    display: flex;
    align-items: flex-start;
    gap: .85rem;
}
.announce-icon {
    width: 34px; height: 34px;
    border-radius: var(--radius-sm);
    background: var(--gold-pale);
    border: 1px solid var(--gold-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold);
    font-size: .85rem;
    flex-shrink: 0;
    margin-top: 1px;
}
.announce-body { flex: 1; min-width: 0; }
.announce-body strong { font-size: .85rem; color: var(--navy); display: block; margin-bottom: .15rem; }
.announce-body p { font-size: .8rem; color: #6b7280; line-height: 1.6; margin: 0; }

/* ══════════════════════════════════════════════════════════
   HOW IT WORKS
══════════════════════════════════════════════════════════ */
.steps-section { background: var(--navy); }
.steps-section .section-tag { color: rgba(200,134,26,.85); }
.steps-section .section-tag::before { background: rgba(200,134,26,.7); }
.steps-section .section-h2 { color: #fff; }
.steps-section .section-p { color: rgba(255,255,255,.55); max-width: 480px; }

.steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
    margin-top: 2.5rem;
    position: relative;
}
.steps-grid::before {
    content: '';
    position: absolute;
    top: 27px;
    left: calc(12.5% + 13px);
    right: calc(12.5% + 13px);
    height: 1px;
    background: linear-gradient(90deg, rgba(200,134,26,.3), rgba(200,134,26,.6), rgba(200,134,26,.3));
    z-index: 0;
}
.step-card {
    text-align: center;
    padding: 0 1.25rem;
    position: relative;
    z-index: 1;
}
.step-circle {
    width: 54px; height: 54px;
    border-radius: 50%;
    background: var(--navy-mid);
    border: 2px solid rgba(200,134,26,.45);
    color: var(--gold);
    font-size: 1rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.1rem;
    position: relative;
    z-index: 1;
    box-shadow: 0 0 0 5px rgba(13,33,68,.6), 0 4px 16px rgba(0,0,0,.3);
}
.step-card h4 { font-size: .9rem; font-weight: 700; color: #fff; margin-bottom: .45rem; }
.step-card p  { font-size: .78rem; color: rgba(255,255,255,.5); line-height: 1.65; }

/* Mobile vertical steps */
@media (max-width: 640px) {
    .steps-grid { grid-template-columns: 1fr; gap: 0; }
    .steps-grid::before { display: none; }
    .step-card {
        display: flex; align-items: flex-start;
        gap: 1.1rem; text-align: left; padding: 0 0 2rem; position: relative;
    }
    .step-card:last-child { padding-bottom: 0; }
    .step-card:not(:last-child)::after {
        content: ''; position: absolute;
        left: 26px; top: 54px; bottom: 0;
        width: 1px; background: rgba(200,134,26,.25);
    }
    .step-circle { flex-shrink: 0; margin: 0; }
    .step-card-body { flex: 1; padding-top: 14px; }
}

/* ══════════════════════════════════════════════════════════
   QUICK STATS BAR
══════════════════════════════════════════════════════════ */
.stats-bar {
    background: #fff;
    border-bottom: 1px solid #e5e9f0;
}
.stats-bar-inner {
    max-width: 1120px;
    margin: 0 auto;
    padding: 1.5rem clamp(1rem, 5vw, 2.5rem);
    display: flex;
    align-items: center;
    justify-content: space-around;
    gap: 1rem;
    flex-wrap: wrap;
}
.stat-item { text-align: center; }
.stat-num { font-size: 1.6rem; font-weight: 800; color: var(--navy); line-height: 1; letter-spacing: -0.03em; }
.stat-num .stat-accent { color: var(--gold); }
.stat-lbl { font-size: .72rem; color: #9ca3af; margin-top: .25rem; font-weight: 500; }
.stat-sep { width: 1px; height: 40px; background: #e5e9f0; }
@media (max-width: 480px) { .stat-sep { display: none; } }

/* ══════════════════════════════════════════════════════════
   FAQ
══════════════════════════════════════════════════════════ */
.faq-list { display: flex; flex-direction: column; gap: .6rem; margin-top: 2rem; }
.faq-item {
    background: #fff;
    border: 1.5px solid #e5e9f0;
    border-radius: var(--radius);
    overflow: hidden;
    transition: border-color .15s;
}
.faq-item.open { border-color: var(--navy-border); }
.faq-btn {
    width: 100%;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 1rem 1.25rem;
    background: none; border: none;
    font-family: 'Poppins', sans-serif;
    font-size: .9rem; font-weight: 600;
    color: var(--navy); cursor: pointer;
    text-align: left; min-height: 58px;
    transition: background .1s;
}
.faq-btn:hover { background: #fafafa; }
.faq-icon {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; color: var(--navy);
    flex-shrink: 0;
    transition: transform .2s, background .15s, color .15s;
}
.faq-item.open .faq-icon { transform: rotate(45deg); background: var(--navy); color: #fff; }
.faq-body {
    display: none;
    padding: 0 1.25rem 1.1rem;
    font-size: .88rem; color: #4b5563; line-height: 1.75;
    border-top: 1px solid #f3f4f6;
    padding-top: .85rem;
}
.faq-item.open .faq-body { display: block; }

/* ══════════════════════════════════════════════════════════
   CTA BANNER
══════════════════════════════════════════════════════════ */
.cta-band {
    background: linear-gradient(100deg, var(--navy-dark) 0%, var(--navy) 60%, #1a3a6e 100%);
    padding: clamp(2.5rem, 5vw, 4rem) clamp(1rem, 5vw, 2.5rem);
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-band::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(200,134,26,.09) 0%, transparent 65%);
    pointer-events: none;
}
.cta-band::after {
    content: '';
    position: absolute;
    bottom: -60px; left: 5%;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(255,255,255,.03) 0%, transparent 65%);
    pointer-events: none;
}
.cta-band-inner { position: relative; z-index: 1; max-width: 620px; margin: 0 auto; }
.cta-band h2 {
    font-size: clamp(1.5rem, 3.5vw, 2.1rem);
    font-weight: 800; color: #fff;
    margin-bottom: .7rem; letter-spacing: -0.02em;
}
.cta-band p { font-size: .95rem; color: rgba(255,255,255,.55); line-height: 1.75; margin-bottom: 2rem; }
.cta-band-btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }

/* ══════════════════════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════════════════════ */
@media (max-width: 900px) {
    .hero-inner { grid-template-columns: 1fr; gap: 2rem; }
    .track-panel { max-width: 480px; }
    .svc-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
    .svc-grid { grid-template-columns: 1fr; }
    .hero-ctas { flex-direction: column; }
    .hero-ctas .btn { width: 100%; justify-content: center; }
    .cta-band-btns { flex-direction: column; align-items: stretch; }
    .cta-band-btns .btn { width: 100%; justify-content: center; }
}
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════════ --}}
<section class="hero" aria-label="Barangay New Era Resident Portal">
    <div class="hero-inner">

        {{-- Left: Headline + CTAs --}}
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-shield-halved"></i>
                Official Barangay Digital Services
            </div>
            <h1 class="hero-title">
                Your Barangay<br>
                Services, <span class="accent">Online.</span>
            </h1>
            <p class="hero-desc">
                Request official documents and track your appointment status without leaving home. Fast, free, and accessible to all residents of Barangay New Era.
            </p>
            <div class="hero-ctas">
                <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="{{ route('portal.about') }}" class="btn btn-outline btn-lg">
                    <i class="fas fa-landmark"></i> About the Barangay
                </a>
            </div>
            <div class="hero-pills">
                <span class="hero-pills-label">Services:</span>
                <a href="{{ route('portal.request') }}?type=Barangay+Clearance" class="hero-pill"><i class="fas fa-file-shield"></i> Clearance</a>
                <a href="{{ route('portal.request') }}?type=Certificate+of+Indigency" class="hero-pill"><i class="fas fa-hand-holding-heart"></i> Indigency</a>
                <a href="{{ route('portal.request') }}?type=Certificate+of+Residency" class="hero-pill"><i class="fas fa-house-circle-check"></i> Residency</a>
                <a href="{{ route('portal.blotter') }}" class="hero-pill"><i class="fas fa-gavel"></i> Blotter</a>
                <a href="{{ route('portal.business') }}" class="hero-pill"><i class="fas fa-store"></i> Business</a>
            </div>
        </div>

        {{-- Right: Track Panel --}}
        <div class="track-panel" role="complementary" aria-label="Track your appointment">
            <div class="track-panel-head">
                <div class="track-panel-icon"><i class="fas fa-magnifying-glass"></i></div>
                <div>
                    <h3>Track Your Request</h3>
                    <span>Enter your appointment number</span>
                </div>
            </div>

            <form class="track-form" action="{{ route('portal.track.post') }}" method="POST">
                @csrf
                <div class="track-input-wrap">
                    <i class="fas fa-hashtag"></i>
                    <input type="text"
                           name="appointment_number"
                           placeholder="e.g. APT-20260520-AB12"
                           autocomplete="off"
                           spellcheck="false"
                           aria-label="Appointment number">
                </div>
                <button type="submit" class="track-btn">
                    <i class="fas fa-search"></i> Check Status
                </button>
            </form>

            <div class="track-divider">or start a new request</div>

            <div class="track-shortcuts">
                <a href="{{ route('portal.request') }}" class="track-shortcut">
                    <div class="track-shortcut-icon" style="background:rgba(13,33,68,.07);color:var(--navy)">
                        <i class="fas fa-file-plus"></i>
                    </div>
                    <div class="track-shortcut-text">
                        <strong>Request a Document</strong>
                        <span>Clearance, Indigency, Residency</span>
                    </div>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <a href="{{ route('portal.blotter') }}" class="track-shortcut">
                    <div class="track-shortcut-icon" style="background:rgba(155,28,28,.07);color:var(--crimson)">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="track-shortcut-text">
                        <strong>File a Blotter Report</strong>
                        <span>Report an incident to the barangay</span>
                    </div>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
                <a href="{{ route('portal.business') }}" class="track-shortcut">
                    <div class="track-shortcut-icon" style="background:rgba(14,116,144,.07);color:#0e7490">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="track-shortcut-text">
                        <strong>Business Permit</strong>
                        <span>New application or renewal</span>
                    </div>
                    <i class="fas fa-chevron-right arrow"></i>
                </a>
            </div>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     QUICK STATS BAR
════════════════════════════════════════════════════════ --}}
<div class="stats-bar" aria-hidden="true">
    <div class="stats-bar-inner">
        <div class="stat-item">
            <div class="stat-num"><span class="stat-accent">4</span></div>
            <div class="stat-lbl">Document Types</div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-item">
            <div class="stat-num">1<span style="font-size:.9rem;color:#9ca3af">–</span>3</div>
            <div class="stat-lbl">Business Days</div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-item">
            <div class="stat-num"><span class="stat-accent">100%</span></div>
            <div class="stat-lbl">Free Service</div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-item">
            <div class="stat-num">24/7</div>
            <div class="stat-lbl">Online Requests</div>
        </div>
        <div class="stat-sep"></div>
        <div class="stat-item">
            <div class="stat-num"><span class="stat-accent">0</span></div>
            <div class="stat-lbl">Walk-In Required</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     ANNOUNCEMENT BAR
════════════════════════════════════════════════════════ --}}
<div class="announce-bar">
    <div class="announce-inner">
        <div class="announce-icon" aria-hidden="true"><i class="fas fa-bullhorn"></i></div>
        <div class="announce-body">
            <strong><i class="fas fa-circle-info" style="font-size:.7rem;margin-right:4px;color:var(--gold)"></i> Reminder: This portal is for scheduling &amp; inquiry only.</strong>
            <p>You must visit the Barangay Hall in person to claim your document. Please bring a valid government-issued ID on your confirmed schedule date. Office hours: Monday–Friday, 8:00 AM – 5:00 PM.</p>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     SERVICES DIRECTORY
════════════════════════════════════════════════════════ --}}
<section class="page-section" id="services" style="background:#f7f8fa;">
    <div class="page-section-inner">
        <div class="section-header">
            <div class="section-tag">Available Services</div>
            <h2 class="section-h2">What Can We Help You With?</h2>
            <p class="section-p">All barangay services are available online. Select a service below to get started.</p>
        </div>

        <div class="svc-grid">

            <a href="{{ route('portal.request') }}?type=Barangay+Clearance" class="svc-tile svc-navy">
                <div class="svc-tile-icon-wrap"><i class="fas fa-file-shield"></i></div>
                <h3>Barangay Clearance</h3>
                <p>Certificate of good standing for employment applications, business permits, loans, and other official requirements.</p>
                <div class="svc-tile-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('portal.request') }}?type=Certificate+of+Indigency" class="svc-tile svc-gold">
                <div class="svc-tile-icon-wrap"><i class="fas fa-hand-holding-heart"></i></div>
                <h3>Certificate of Indigency</h3>
                <p>For residents who need to avail of government assistance programs, PhilHealth, or medical financial aid.</p>
                <div class="svc-tile-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('portal.request') }}?type=Certificate+of+Residency" class="svc-tile svc-green">
                <div class="svc-tile-icon-wrap"><i class="fas fa-house-circle-check"></i></div>
                <h3>Certificate of Residency</h3>
                <p>Proof of residence for school enrollment, government transactions, and other official document requirements.</p>
                <div class="svc-tile-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('portal.request') }}?type=Business+Clearance" class="svc-tile svc-blue">
                <div class="svc-tile-icon-wrap"><i class="fas fa-building"></i></div>
                <h3>Business Clearance</h3>
                <p>Required for new business registration and the annual renewal of business permits within the barangay.</p>
                <div class="svc-tile-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('portal.blotter') }}" class="svc-tile svc-red">
                <div class="svc-tile-icon-wrap"><i class="fas fa-gavel"></i></div>
                <h3>File a Blotter Report</h3>
                <p>Report an incident online. Our Peace &amp; Order committee will follow up and facilitate barangay mediation.</p>
                <div class="svc-tile-link">File Report <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="{{ route('portal.business') }}" class="svc-tile svc-teal">
                <div class="svc-tile-icon-wrap"><i class="fas fa-file-contract"></i></div>
                <h3>Business Permit Application</h3>
                <p>Apply for a new business permit or renewal online. A barangay inspector will review and process your application.</p>
                <div class="svc-tile-link">Apply Now <i class="fas fa-arrow-right"></i></div>
            </a>

        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════════════ --}}
<section class="page-section steps-section" id="how-it-works">
    <div class="page-section-inner">
        <div class="section-header">
            <div class="section-tag">Simple Process</div>
            <h2 class="section-h2">How to Use This Portal</h2>
            <p class="section-p">From request to release — designed to be as simple as possible for every resident.</p>
        </div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-circle">1</div>
                <div class="step-card-body">
                    <h4>Fill the Form</h4>
                    <p>Provide your personal details and choose the document you need. No account required.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-circle">2</div>
                <div class="step-card-body">
                    <h4>Get Your Number</h4>
                    <p>You'll receive a unique appointment number immediately after submitting your request.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-circle">3</div>
                <div class="step-card-body">
                    <h4>Wait for Confirmation</h4>
                    <p>Barangay staff will confirm your preferred schedule within 1–2 business days.</p>
                </div>
            </div>
            <div class="step-card">
                <div class="step-circle">4</div>
                <div class="step-card-body">
                    <h4>Claim Your Document</h4>
                    <p>Visit the Barangay Hall on your confirmed date with a valid ID to claim your document.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     FAQ
════════════════════════════════════════════════════════ --}}
<section class="page-section" id="faq" style="background:#f7f8fa;">
    <div class="page-section-inner" style="max-width:780px">
        <div class="section-header">
            <div class="section-tag">Support</div>
            <h2 class="section-h2">Frequently Asked Questions</h2>
            <p class="section-p">Find quick answers to the most common questions about our services.</p>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How long does document processing take?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Processing typically takes <strong>1–3 business days</strong> from the date your request is confirmed by barangay staff. Barangay Clearance is usually ready within 1 business day. You will need to visit the hall on your confirmed schedule to claim your document.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What valid IDs are accepted when claiming?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Any government-issued ID is accepted: <strong>PhilSys National ID, Passport, Driver's License, SSS/GSIS/Pag-IBIG ID, Voter's ID, or PhilHealth ID</strong>. The name on your ID must match the name you provided in your request.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Is there a processing fee?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    All barangay document services are <strong>completely free of charge</strong> for residents of Barangay New Era. There are no fees for requesting, processing, or claiming your documents through this portal.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Can someone else claim my document on my behalf?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    For most documents, the requesting resident must be present when claiming. If another person is claiming on your behalf, they must present a <strong>Special Power of Attorney (SPA)</strong> along with their own valid ID and a copy of the resident's valid ID.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What if my appointment number is not found?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Double-check that you entered the complete appointment number exactly as shown on your confirmation page (e.g., <code style="background:#f3f4f6;padding:2px 7px;border-radius:4px;font-size:.85em">APT-20260507-AB12</code>). If the problem persists, please visit the Barangay Hall directly during office hours.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Can I request a document if I am not a resident of Barangay New Era?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Barangay documents can only be issued to <strong>registered residents</strong> of Barangay New Era. If you recently moved to the barangay, please visit the hall first to register as a resident before requesting any documents.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CTA BAND
════════════════════════════════════════════════════════ --}}
<section class="cta-band">
    <div class="cta-band-inner">
        <h2>Ready to Get Started?</h2>
        <p>Submit your request online in under 3 minutes. No account needed — just fill out the form and we'll handle the rest.</p>
        <div class="cta-band-btns">
            <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                <i class="fas fa-file-plus"></i> Request a Document
            </a>
            <a href="{{ route('portal.track') }}" class="btn btn-outline-white btn-lg">
                <i class="fas fa-search"></i> Track Existing Request
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function toggleFaq(btn) {
    var item   = btn.closest('.faq-item');
    var isOpen = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(function (el) {
        el.classList.remove('open');
        el.querySelector('.faq-btn').setAttribute('aria-expanded', 'false');
    });
    if (!isOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
    }
}
</script>
@endpush
