<?php $__env->startSection('title', 'Resident Portal'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ══════════════════════════════════════════════════════════════
   SECTION UTILITIES
══════════════════════════════════════════════════════════════ */
.section-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 4vw, 2rem);
}
.section-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--gold);
    margin-bottom: .75rem;
}
.section-title {
    font-size: clamp(1.5rem, 3.5vw, 2rem);
    font-weight: 800;
    color: var(--navy);
    line-height: 1.2;
    margin-bottom: .6rem;
}
.section-sub {
    color: var(--muted);
    font-size: 1rem;
    line-height: 1.7;
    max-width: 580px;
}

/* ══════════════════════════════════════════════════════════════
   ① HERO — full-width navy gradient, 2-col layout
══════════════════════════════════════════════════════════════ */
.hero-section {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy) 45%, var(--navy-light) 100%);
    position: relative;
    overflow: hidden;
}

/* Background radial glow */
.hero-section::before {
    content: '';
    position: absolute;
    top: -120px;
    right: -80px;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(200,134,26,.18) 0%, transparent 65%);
    pointer-events: none;
}

.hero-grid {
    max-width: 1100px;
    margin: 0 auto;
    padding: clamp(3.5rem, 8vw, 5.5rem) clamp(1.25rem, 4vw, 2rem) clamp(4rem, 8vw, 6rem);
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 3.5rem;
    align-items: center;
    position: relative;
    z-index: 1;
}

/* Left text column */
.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(200,134,26,.18);
    border: 1px solid rgba(200,134,26,.38);
    color: var(--gold);
    padding: .35rem 1rem;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 1.25rem;
}
.hero-headline {
    font-size: clamp(2rem, 5vw, 3.25rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.12;
    margin-bottom: 1.1rem;
    letter-spacing: -.01em;
}
.hero-headline em {
    font-style: normal;
    color: var(--gold);
}
.hero-subtext {
    color: rgba(255,255,255,.72);
    font-size: 1.05rem;
    line-height: 1.75;
    margin-bottom: 2.25rem;
    max-width: 480px;
}
.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

/* Hero stats bar */
.hero-stats {
    display: flex;
    gap: 2.5rem;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(255,255,255,.12);
    flex-wrap: wrap;
}
.hero-stat strong {
    display: block;
    font-size: 1.55rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
}
.hero-stat span {
    font-size: .78rem;
    color: rgba(255,255,255,.55);
    font-weight: 400;
}

/* Right column — CSS phone mockup */
.hero-visual {
    display: flex;
    justify-content: center;
    align-items: center;
}
.phone-mockup {
    width: 240px;
    height: 460px;
    background: #0a1c38;
    border-radius: 38px;
    border: 5px solid rgba(255,255,255,.14);
    box-shadow:
        0 0 0 1px rgba(255,255,255,.06),
        0 32px 80px rgba(0,0,0,.45),
        inset 0 1px 0 rgba(255,255,255,.08);
    position: relative;
    overflow: hidden;
    transform: rotate(-5deg) translateY(-8px);
    transition: transform .4s ease;
}
.phone-mockup:hover { transform: rotate(-3deg) translateY(-14px); }

/* Phone notch */
.phone-mockup::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 90px;
    height: 24px;
    background: #0a1c38;
    border-radius: 0 0 18px 18px;
    z-index: 10;
}

.phone-screen {
    padding: 34px 14px 14px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
    overflow: hidden;
}

/* Mini header bar inside phone */
.phone-topbar {
    background: var(--gold);
    border-radius: 10px;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}
.phone-topbar-dot {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(255,255,255,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .45rem;
    color: #fff;
}
.phone-topbar-text {
    font-size: .52rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}

/* Mini service rows inside phone */
.phone-row {
    background: rgba(255,255,255,.07);
    border-radius: 8px;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.phone-row-icon {
    width: 26px;
    height: 26px;
    border-radius: 7px;
    background: rgba(200,134,26,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .58rem;
    color: var(--gold);
    flex-shrink: 0;
}
.phone-row-lines { flex: 1; }
.phone-row-lines span {
    display: block;
    height: 5px;
    border-radius: 3px;
    background: rgba(255,255,255,.25);
    margin-bottom: 3px;
}
.phone-row-lines span:last-child { width: 60%; background: rgba(255,255,255,.12); }

/* Status badge inside phone */
.phone-status {
    background: rgba(22,163,74,.2);
    border-radius: 6px;
    padding: 6px 10px;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.phone-status-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #4ade80;
}
.phone-status-text {
    font-size: .48rem;
    color: #4ade80;
    font-weight: 600;
}

/* Bottom wave separator */
.hero-wave {
    display: block;
    width: 100%;
    height: clamp(40px, 6vw, 70px);
    margin-bottom: -2px;
    background: var(--bg);
    clip-path: ellipse(55% 100% at 50% 100%);
}

/* Hero responsive */
@media (max-width: 900px) {
    .hero-grid { grid-template-columns: 1fr; gap: 2.5rem; text-align: center; }
    .hero-subtext { margin-left: auto; margin-right: auto; }
    .hero-actions { justify-content: center; }
    .hero-stats { justify-content: center; gap: 1.75rem; }
    .hero-visual { justify-content: center; }
    .phone-mockup { transform: rotate(0deg); }
    .phone-mockup:hover { transform: translateY(-8px); }
}
@media (max-width: 480px) {
    .hero-actions { flex-direction: column; align-items: stretch; }
    .hero-stats   { gap: 1.25rem; }
    .hero-visual  { display: none; }
}

/* ══════════════════════════════════════════════════════════════
   ② SERVICE HUB — tile grid
══════════════════════════════════════════════════════════════ */
.services-section {
    background: var(--bg);
    padding: clamp(3rem, 7vw, 5rem) 0;
}
.services-header {
    text-align: center;
    margin-bottom: 2.5rem;
}
.services-header .section-sub { margin: 0 auto; }

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
    gap: 1.25rem;
}
.service-tile {
    background: #fff;
    border-radius: var(--radius-lg);
    padding: 1.75rem 1.5rem;
    border: 1.5px solid var(--border);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: .85rem;
    transition: transform .2s, box-shadow .2s, border-color .2s;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
}
.service-tile::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--navy), var(--gold));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s ease;
}
.service-tile:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--navy-border);
}
.service-tile:hover::after { transform: scaleX(1); }

.tile-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius);
    background: var(--navy-pale);
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: background .2s, color .2s;
}
.service-tile:hover .tile-icon {
    background: var(--navy);
    color: #fff;
}
.tile-body { flex: 1; }
.tile-body h3 {
    font-size: .95rem;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: .35rem;
    line-height: 1.3;
}
.tile-body p {
    font-size: .82rem;
    color: var(--muted);
    line-height: 1.6;
}
.tile-cta {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .82rem;
    font-weight: 700;
    color: var(--gold);
    text-decoration: none;
    transition: gap .2s;
}
.service-tile:hover .tile-cta { gap: .65rem; }

@media (max-width: 600px) {
    .services-grid { grid-template-columns: 1fr; }
}

/* ══════════════════════════════════════════════════════════════
   ③ HOW IT WORKS — horizontal desktop / vertical mobile
══════════════════════════════════════════════════════════════ */
.steps-section {
    background: #fff;
    padding: clamp(3rem, 7vw, 5rem) 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}
.steps-header {
    text-align: center;
    margin-bottom: 3rem;
}

/* Desktop: horizontal row with connecting line */
.steps-row {
    display: flex;
    gap: 0;
    align-items: flex-start;
    position: relative;
}
.steps-row::before {
    content: '';
    position: absolute;
    top: 28px;
    left: calc(12.5% + 28px);
    right: calc(12.5% + 28px);
    height: 2px;
    background: linear-gradient(90deg, var(--navy-border), var(--gold-border));
    z-index: 0;
}
.step-item {
    flex: 1;
    text-align: center;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}
.step-num {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: var(--navy);
    color: #fff;
    font-weight: 800;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.1rem;
    box-shadow: 0 4px 16px rgba(13,33,68,.25);
    border: 3px solid #fff;
    outline: 3px solid var(--navy-border);
    position: relative;
    z-index: 1;
}
.step-item.step-active .step-num {
    background: var(--gold);
    box-shadow: 0 4px 16px rgba(200,134,26,.4);
    outline-color: var(--gold-border);
}
.step-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--navy-pale);
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .85rem;
    margin: 0 auto .6rem;
}
.step-title {
    font-size: .88rem;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: .35rem;
}
.step-desc {
    font-size: .79rem;
    color: var(--muted);
    line-height: 1.6;
    max-width: 160px;
    margin: 0 auto;
}

/* Mobile: vertical timeline */
@media (max-width: 700px) {
    .steps-row {
        flex-direction: column;
        gap: 0;
        align-items: stretch;
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
    /* Vertical line */
    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 56px;
        left: 27px;
        width: 2px;
        bottom: 0;
        background: linear-gradient(to bottom, var(--navy-border), transparent);
    }
    .step-left { flex-shrink: 0; display: flex; flex-direction: column; align-items: center; }
    .step-num  { margin: 0; }
    .step-icon { margin: .5rem 0 0; display: none; }
    .step-body { flex: 1; padding-top: .85rem; }
    .step-desc { max-width: none; }
}
@media (min-width: 701px) {
    .step-left  { display: contents; }
    .step-body  { display: contents; }
}

/* ══════════════════════════════════════════════════════════════
   ④ TRUST & SECURITY
══════════════════════════════════════════════════════════════ */
.trust-section {
    background: linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 100%);
    padding: clamp(3rem, 7vw, 5rem) 0;
    position: relative;
    overflow: hidden;
}
.trust-section::before {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -80px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(200,134,26,.12) 0%, transparent 65%);
    pointer-events: none;
}
.trust-grid {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 4rem;
    align-items: center;
    position: relative;
    z-index: 1;
}
.trust-icon-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.trust-shield {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    border: 2px solid rgba(255,255,255,.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #fff;
}
.trust-badge {
    background: rgba(200,134,26,.2);
    border: 1px solid rgba(200,134,26,.4);
    color: var(--gold);
    padding: .3rem .85rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    white-space: nowrap;
}

.trust-content h2 {
    font-size: clamp(1.4rem, 3.5vw, 1.9rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: .65rem;
    line-height: 1.2;
}
.trust-content p {
    color: rgba(255,255,255,.7);
    font-size: 1rem;
    line-height: 1.75;
    margin-bottom: 1.75rem;
    max-width: 540px;
}
.trust-points {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .85rem;
}
.trust-point {
    display: flex;
    align-items: flex-start;
    gap: .65rem;
    color: rgba(255,255,255,.8);
    font-size: .85rem;
    line-height: 1.55;
}
.trust-point-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(200,134,26,.25);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .6rem;
    color: var(--gold);
    flex-shrink: 0;
    margin-top: .12rem;
}

@media (max-width: 768px) {
    .trust-grid    { grid-template-columns: 1fr; gap: 2rem; text-align: center; }
    .trust-content p { margin-left: auto; margin-right: auto; }
    .trust-points  { grid-template-columns: 1fr; gap: .65rem; }
    .trust-point   { text-align: left; }
}
@media (max-width: 480px) {
    .trust-icon-wrap { flex-direction: row; justify-content: center; }
}

/* ══════════════════════════════════════════════════════════════
   ⑤ FAQ ACCORDION
══════════════════════════════════════════════════════════════ */
.faq-section {
    background: var(--bg);
    padding: clamp(3rem, 7vw, 5rem) 0;
}
.faq-header {
    text-align: center;
    margin-bottom: 2.5rem;
}
.faq-list {
    display: flex;
    flex-direction: column;
    gap: .65rem;
    max-width: 760px;
    margin: 0 auto;
}
.faq-item {
    background: #fff;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}
.faq-item.open {
    border-color: var(--navy-border);
    box-shadow: var(--shadow-sm);
}
.faq-btn {
    width: 100%;
    background: none;
    border: none;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    cursor: pointer;
    text-align: left;
    font-family: 'Poppins', sans-serif;
    font-size: .92rem;
    font-weight: 600;
    color: var(--navy);
    min-height: 56px;
}
.faq-btn:hover { background: var(--navy-pale); }
.faq-icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: var(--navy-pale);
    color: var(--navy);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    flex-shrink: 0;
    transition: background .2s, transform .3s;
}
.faq-item.open .faq-icon {
    background: var(--navy);
    color: #fff;
    transform: rotate(45deg);
}
.faq-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s cubic-bezier(.4,0,.2,1);
}
.faq-body-inner {
    padding: .25rem 1.25rem 1.25rem;
    font-size: .88rem;
    color: var(--muted);
    line-height: 1.75;
    border-top: 1px solid var(--border);
}

/* ══════════════════════════════════════════════════════════════
   ⑥ BOTTOM CTA BANNER
══════════════════════════════════════════════════════════════ */
.cta-section {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-mid) 100%);
    padding: clamp(3rem, 7vw, 5rem) 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-section::before {
    content: '';
    position: absolute;
    top: -80px;
    right: -60px;
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(200,134,26,.14) 0%, transparent 65%);
    pointer-events: none;
}
.cta-inner {
    position: relative;
    z-index: 1;
}
.cta-inner .section-eyebrow { color: var(--gold); }
.cta-title {
    font-size: clamp(1.5rem, 4vw, 2.25rem);
    font-weight: 800;
    color: #fff;
    margin-bottom: .75rem;
    line-height: 1.2;
}
.cta-sub {
    color: rgba(255,255,255,.68);
    font-size: 1rem;
    margin-bottom: 2rem;
    max-width: 520px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.7;
}
.cta-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* ══════════════════════════════════════════════════════════════
   NOTICE BAR
══════════════════════════════════════════════════════════════ */
.notice-bar {
    background: var(--gold-pale);
    border-top: 2px solid var(--gold-border);
    padding: .85rem 1.25rem;
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    font-size: .83rem;
    color: #78450a;
    line-height: 1.6;
}
.notice-bar i { color: var(--gold); flex-shrink: 0; margin-top: .12rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="hero-section" aria-label="Welcome to Barangay New Era Resident Portal">
    <div class="hero-grid">

        
        <div class="hero-text">
            <div class="hero-eyebrow">
                <i class="fas fa-shield-halved"></i>
                Official Digital Services — Barangay New Era
            </div>

            <h1 class="hero-headline">
                Barangay Services.<br>
                <em>Simplified.</em>
            </h1>

            <p class="hero-subtext">
                Request official barangay documents online and track your appointment — no need to visit the hall just to inquire.
                Fast, free, and secure.
            </p>

            <div class="hero-actions">
                <a href="<?php echo e(route('portal.request')); ?>" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="<?php echo e(route('portal.track')); ?>" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-search"></i> Track My Request
                </a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>4</strong>
                    <span>Document types available</span>
                </div>
                <div class="hero-stat">
                    <strong>1–3</strong>
                    <span>Business days processing</span>
                </div>
                <div class="hero-stat">
                    <strong>100%</strong>
                    <span>Free for all residents</span>
                </div>
            </div>
        </div>

        
        <div class="hero-visual" aria-hidden="true">
            <div class="phone-mockup">
                <div class="phone-screen">
                    <div class="phone-topbar">
                        <div class="phone-topbar-dot"><i class="fas fa-landmark"></i></div>
                        <div class="phone-topbar-text">
                            Barangay New Era<br>
                            <span style="opacity:.7;font-size:.45rem">Resident Portal</span>
                        </div>
                    </div>
                    <div class="phone-row">
                        <div class="phone-row-icon"><i class="fas fa-file-shield"></i></div>
                        <div class="phone-row-lines"><span></span><span></span></div>
                    </div>
                    <div class="phone-row">
                        <div class="phone-row-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <div class="phone-row-lines"><span></span><span></span></div>
                    </div>
                    <div class="phone-row">
                        <div class="phone-row-icon"><i class="fas fa-house-circle-check"></i></div>
                        <div class="phone-row-lines"><span></span><span></span></div>
                    </div>
                    <div class="phone-row">
                        <div class="phone-row-icon"><i class="fas fa-store"></i></div>
                        <div class="phone-row-lines"><span></span><span></span></div>
                    </div>
                    <div class="phone-status">
                        <div class="phone-status-dot"></div>
                        <div class="phone-status-text">APT-20260515 · Ready for Pickup</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    
    <div class="hero-wave" aria-hidden="true"></div>
</section>



<section class="services-section" aria-label="Available document services">
    <div class="section-inner">
        <div class="services-header">
            <div class="section-eyebrow"><i class="fas fa-grid-2"></i> Digital Services</div>
            <h2 class="section-title">What Can We Help You With?</h2>
            <p class="section-sub">Choose from our available barangay documents. All requests are processed within 1–3 business days.</p>
        </div>

        <div class="services-grid">
            <div class="service-tile">
                <div class="tile-icon"><i class="fas fa-file-shield"></i></div>
                <div class="tile-body">
                    <h3>Barangay Clearance</h3>
                    <p>Certificate of good standing within the barangay — required for employment, permits, and government transactions.</p>
                </div>
                <a href="<?php echo e(route('portal.request')); ?>?type=Barangay+Clearance" class="tile-cta">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="service-tile">
                <div class="tile-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <div class="tile-body">
                    <h3>Certificate of Indigency</h3>
                    <p>For residents who need to avail of government assistance programs or medical financial aid.</p>
                </div>
                <a href="<?php echo e(route('portal.request')); ?>?type=Certificate+of+Indigency" class="tile-cta">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="service-tile">
                <div class="tile-icon"><i class="fas fa-house-circle-check"></i></div>
                <div class="tile-body">
                    <h3>Certificate of Residency</h3>
                    <p>Proof of residence for government transactions, school enrollment, and other official requirements.</p>
                </div>
                <a href="<?php echo e(route('portal.request')); ?>?type=Certificate+of+Residency" class="tile-cta">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="service-tile">
                <div class="tile-icon"><i class="fas fa-store"></i></div>
                <div class="tile-body">
                    <h3>Business Clearance</h3>
                    <p>Required for new business registration and the annual renewal of business permits in the barangay.</p>
                </div>
                <a href="<?php echo e(route('portal.request')); ?>?type=Business+Clearance" class="tile-cta">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>



<section class="steps-section" aria-label="How the request process works">
    <div class="section-inner">
        <div class="steps-header">
            <div class="section-eyebrow"><i class="fas fa-list-check"></i> Simple Process</div>
            <h2 class="section-title">How It Works</h2>
            <p class="section-sub">Four easy steps — done entirely online. You only need to visit the hall once to claim your document.</p>
        </div>

        <div class="steps-row">
            <div class="step-item step-active">
                <div class="step-left">
                    <div class="step-num">1</div>
                    <div class="step-icon"><i class="fas fa-file-pen"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Fill Out the Form</div>
                    <div class="step-desc">Provide your personal details, choose the document type, and set a preferred pick-up date.</div>
                </div>
            </div>

            <div class="step-item">
                <div class="step-left">
                    <div class="step-num">2</div>
                    <div class="step-icon"><i class="fas fa-hashtag"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Get Your Number</div>
                    <div class="step-desc">Instantly receive a unique appointment number — save it to track your request status anytime.</div>
                </div>
            </div>

            <div class="step-item">
                <div class="step-left">
                    <div class="step-num">3</div>
                    <div class="step-icon"><i class="fas fa-bell"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Staff Confirms</div>
                    <div class="step-desc">Barangay staff will process and confirm your schedule within 1–2 business days.</div>
                </div>
            </div>

            <div class="step-item">
                <div class="step-left">
                    <div class="step-num">4</div>
                    <div class="step-icon"><i class="fas fa-id-card"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Claim Your Document</div>
                    <div class="step-desc">Visit the barangay hall on your confirmed date and present a valid ID to claim your document.</div>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="trust-section" aria-label="Data privacy and security">
    <div class="section-inner">
        <div class="trust-grid">
            <div class="trust-icon-wrap">
                <div class="trust-shield"><i class="fas fa-shield-halved"></i></div>
                <div class="trust-badge"><i class="fas fa-lock"></i> Secure</div>
            </div>
            <div class="trust-content">
                <div class="section-eyebrow" style="color:var(--gold)">
                    <i class="fas fa-user-shield"></i> Your Data is Protected
                </div>
                <h2>We Take Your Privacy Seriously</h2>
                <p>
                    All information you submit through this portal is handled in strict accordance with the Republic Act 10173 (Data Privacy Act of 2012).
                    Your data is used solely to process your document request and is never shared with third parties.
                </p>
                <div class="trust-points">
                    <div class="trust-point">
                        <div class="trust-point-icon"><i class="fas fa-check"></i></div>
                        <span>Data encrypted in transit and at rest</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-point-icon"><i class="fas fa-check"></i></div>
                        <span>Compliant with RA 10173 (Data Privacy Act)</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-point-icon"><i class="fas fa-check"></i></div>
                        <span>No third-party data sharing</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-point-icon"><i class="fas fa-check"></i></div>
                        <span>Accessed only by authorized barangay staff</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-point-icon"><i class="fas fa-check"></i></div>
                        <span>Request data purged after document release</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-point-icon"><i class="fas fa-check"></i></div>
                        <span>Official government-operated service</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="faq-section" aria-label="Frequently asked questions">
    <div class="section-inner">
        <div class="faq-header">
            <div class="section-eyebrow"><i class="fas fa-circle-question"></i> FAQ</div>
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="section-sub" style="margin:0 auto">Everything you need to know about requesting documents through this portal.</p>
        </div>

        <div class="faq-list" role="list">

            <div class="faq-item" role="listitem">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Who can use this portal?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body" role="region">
                    <div class="faq-body-inner">
                        Any resident of <strong>Barangay New Era, District VI, Quezon City</strong> may use this portal to request official barangay documents. You will need to present a valid government-issued ID when claiming your document at the barangay hall.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How long will my document take to process?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body" role="region">
                    <div class="faq-body-inner">
                        Most documents are processed within <strong>1–3 business days</strong> from submission. After processing, barangay staff will confirm your pick-up date via this portal's tracker. For urgent requests, please visit the barangay hall directly.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Is there a fee for requesting documents online?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body" role="region">
                    <div class="faq-body-inner">
                        Using this portal is completely <strong>free of charge</strong>. Standard barangay document fees (if applicable) are collected at the hall upon claiming. Please bring exact change.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What ID should I bring when claiming?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body" role="region">
                    <div class="faq-body-inner">
                        Any valid government-issued photo ID is accepted — PhilSys (National ID), passport, driver's license, SSS/GSIS, Voter's ID, PRC ID, or Senior Citizen ID. Your appointment number is also required.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How do I track the status of my request?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body" role="region">
                    <div class="faq-body-inner">
                        After submitting, you will receive a unique <strong>appointment number</strong> (e.g., APT-20260515-AB12). Use the <a href="<?php echo e(route('portal.track')); ?>" style="color:var(--navy);font-weight:600">Track My Status</a> page and enter this number to check your current status at any time.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>



<section class="cta-section" aria-label="Get started">
    <div class="section-inner">
        <div class="cta-inner">
            <div class="section-eyebrow"><i class="fas fa-rocket"></i> Get Started Today</div>
            <h2 class="cta-title">Ready to Request Your Document?</h2>
            <p class="cta-sub">
                Join hundreds of New Era residents who've already gone digital.
                It takes less than 2 minutes to submit your request.
            </p>
            <div class="cta-actions">
                <a href="<?php echo e(route('portal.request')); ?>" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="<?php echo e(route('portal.track')); ?>" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-search"></i> Track Existing Request
                </a>
            </div>
        </div>
    </div>
</section>



<div class="notice-bar" role="note">
    <i class="fas fa-circle-info fa-fw"></i>
    <div>
        <strong>Important:</strong> This portal is for scheduling only. You must visit the barangay hall in person to claim your document and present a valid ID.
        For urgent requests, please visit <strong>Barangay New Era Hall</strong> directly during office hours (Mon–Fri, 8:00 AM – 5:00 PM).
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
/* ── FAQ Accordion ── */
function toggleFaq(btn) {
    var item  = btn.closest('.faq-item');
    var body  = item.querySelector('.faq-body');
    var isOpen = item.classList.contains('open');

    /* Close all open items */
    document.querySelectorAll('.faq-item.open').forEach(function (el) {
        el.classList.remove('open');
        el.querySelector('.faq-body').style.maxHeight = '0';
        el.querySelector('.faq-btn').setAttribute('aria-expanded', 'false');
    });

    /* Open clicked item if it wasn't already open */
    if (!isOpen) {
        item.classList.add('open');
        body.style.maxHeight = body.scrollHeight + 'px';
        btn.setAttribute('aria-expanded', 'true');
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/index.blade.php ENDPATH**/ ?>