@extends('layouts.portal')
@section('title', 'Resident Portal')

@push('styles')
<style>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
/* ════════════════════════════════════════════════════════════
   SHARED SECTION UTILITIES
════════════════════════════════════════════════════════════ */
.section-wrap {
    max-width: 1100px;
    margin: 0 auto;
    padding-left:  clamp(1rem, 4vw, 2rem);
    padding-right: clamp(1rem, 4vw, 2rem);
}
.section-badge {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--gold);
    margin-bottom: .65rem;
}
.section-badge i { font-size: .65rem; }
.section-heading {
    font-size: clamp(1.55rem, 3.5vw, 2.1rem);
    font-weight: 800;
    color: var(--navy);
    line-height: 1.18;
    margin-bottom: .55rem;
    letter-spacing: -.01em;
}
.section-sub {
    color: var(--muted);
    font-size: 1rem;
    line-height: 1.7;
}

/* ════════════════════════════════════════════════════════════
   ① HERO SECTION
════════════════════════════════════════════════════════════ */
.hero {
    background:
        radial-gradient(ellipse 80% 60% at 70% 50%, rgba(200,134,26,.13) 0%, transparent 65%),
        linear-gradient(135deg, #091830 0%, #0D2144 50%, #162e5a 100%);
    position: relative;
    overflow: hidden;
}

/* Subtle grid texture */
.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px;
    pointer-events: none;
}

.hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: clamp(3.5rem, 8vw, 5.5rem) clamp(1.25rem, 4vw, 2rem) 0;
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    align-items: center;
    position: relative;
    z-index: 1;
}

/* ─ Text column ─ */
.hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    background: rgba(200,134,26,.18);
    border: 1px solid rgba(200,134,26,.38);
    color: var(--gold-light);
    padding: .35rem 1rem;
    border-radius: 999px;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: 1.35rem;
}
.hero-title {
    font-size: clamp(2rem, 5.5vw, 3.4rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
    margin-bottom: 1.15rem;
    letter-spacing: -.02em;
}
.hero-title em {
    font-style: normal;
    color: var(--gold-light);
    position: relative;
}
.hero-title em::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--gold), transparent);
    border-radius: 2px;
    opacity: .5;
}
.hero-subtitle {
    color: rgba(255,255,255,.7);
    font-size: 1.05rem;
    line-height: 1.75;
    margin-bottom: 2.25rem;
    max-width: 500px;
}
.hero-ctas {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2.75rem;
}

/* Stats strip */
.hero-stats {
    display: flex;
    gap: 0;
    border-top: 1px solid rgba(255,255,255,.1);
    padding-top: 2rem;
    margin-bottom: 0;
}
.hero-stat {
    flex: 1;
    padding-right: 1.5rem;
    border-right: 1px solid rgba(255,255,255,.1);
}
.hero-stat:last-child { border-right: none; padding-right: 0; padding-left: 1.5rem; }
.hero-stat:first-child { padding-left: 0; }
.hero-stat strong {
    display: block;
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
    margin-bottom: .1rem;
}
.hero-stat span { font-size: .75rem; color: rgba(255,255,255,.5); }

/* ─ Visual column — CSS portal mockup ─ */
.hero-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    padding-bottom: 0;
    position: relative;
}

/* Ambient glow behind the device */
.hero-visual::before {
    content: '';
    position: absolute;
    bottom: -40px;
    left: 50%;
    transform: translateX(-50%);
    width: 260px;
    height: 80px;
    background: rgba(200,134,26,.2);
    filter: blur(32px);
    border-radius: 50%;
    pointer-events: none;
}

.device-frame {
    width: 230px;
    height: 455px;
    background: #08182e;
    border-radius: 40px;
    border: 5px solid rgba(255,255,255,.16);
    box-shadow:
        0 0 0 1px rgba(255,255,255,.05),
        0 30px 80px rgba(0,0,0,.5),
        inset 0 1px 0 rgba(255,255,255,.1);
    position: relative;
    overflow: hidden;
    transform: rotate(-4deg);
    transition: transform .5s ease;
    flex-shrink: 0;
}
.device-frame:hover { transform: rotate(-1deg) scale(1.02); }

/* Notch */
.device-frame::before {
    content: '';
    position: absolute;
    top: 0; left: 50%;
    transform: translateX(-50%);
    width: 80px; height: 22px;
    background: #08182e;
    border-radius: 0 0 16px 16px;
    z-index: 10;
}
/* Reflection shimmer */
.device-frame::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 40%;
    background: linear-gradient(to bottom, rgba(255,255,255,.04), transparent);
    pointer-events: none;
    z-index: 5;
}

.device-screen {
    padding: 30px 14px 14px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 7px;
    overflow: hidden;
}

/* Mini top bar inside device */
.ds-topbar {
    background: linear-gradient(135deg, var(--gold) 0%, #e09830 100%);
    border-radius: 10px;
    padding: 9px 11px;
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 2px;
}
.ds-topbar-seal {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: .45rem; color: #fff;
}
.ds-topbar-info { flex: 1; }
.ds-topbar-info b { display: block; font-size: .5rem; font-weight: 700; color: #fff; }
.ds-topbar-info s { font-size: .42rem; color: rgba(255,255,255,.75); text-decoration: none; }

/* Service row item inside device */
.ds-row {
    background: rgba(255,255,255,.07);
    border-radius: 8px;
    padding: 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    border: 1px solid rgba(255,255,255,.05);
}
.ds-row-icon {
    width: 26px; height: 26px;
    border-radius: 7px;
    background: rgba(200,134,26,.22);
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem;
    color: var(--gold-light);
    flex-shrink: 0;
}
.ds-row-lines { flex: 1; }
.ds-row-lines b { display: block; height: 5px; border-radius: 3px; background: rgba(255,255,255,.22); margin-bottom: 3px; }
.ds-row-lines s { display: block; height: 4px; border-radius: 3px; background: rgba(255,255,255,.1); width: 55%; }
.ds-row-arrow {
    width: 16px; height: 16px;
    border-radius: 50%;
    background: rgba(200,134,26,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: .42rem;
    color: var(--gold-light);
}

/* Appointment status card */
.ds-status {
    background: rgba(22,163,74,.15);
    border: 1px solid rgba(22,163,74,.25);
    border-radius: 8px;
    padding: 8px 10px;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 7px;
}
.ds-status-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; flex-shrink: 0; }
.ds-status-text { flex: 1; }
.ds-status-text b { display: block; font-size: .48rem; color: #4ade80; font-weight: 700; }
.ds-status-text s { display: block; font-size: .42rem; color: rgba(255,255,255,.5); }

/* Bottom wave cutting into bg */
.hero-wave {
    width: 100%;
    height: clamp(48px, 7vw, 80px);
    background: var(--bg);
    margin-top: 0;
    clip-path: ellipse(55% 100% at 50% 100%);
    position: relative;
    z-index: 0;
}

/* ─ Hero responsive ─ */
@media (max-width: 920px) {
    .hero-inner {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 2.5rem;
        padding-bottom: 1rem;
    }
    .hero-subtitle { margin-left: auto; margin-right: auto; }
    .hero-ctas     { justify-content: center; }
    .hero-stats    { justify-content: center; }
    .hero-visual   { display: none; }
}
@media (max-width: 500px) {
    .hero-ctas     { flex-direction: column; align-items: stretch; }
    .hero-stats    { gap: .5rem; }
    .hero-stat { border-right: none; padding: .5rem 0; border-bottom: 1px solid rgba(255,255,255,.08); }
    .hero-stat:last-child { border-bottom: none; }
    .hero-stats { flex-direction: column; }
}

/* ════════════════════════════════════════════════════════════
   ② SERVICE HUB
════════════════════════════════════════════════════════════ */
.services-section {
    background: var(--bg);
    padding: clamp(3.5rem, 7vw, 5.5rem) 0;
}
.services-header {
    text-align: center;
    margin-bottom: 2.75rem;
}
.services-header .section-sub { margin: 0 auto; max-width: 560px; }

.service-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
}
@media (max-width: 900px) { .service-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 500px) { .service-grid { grid-template-columns: 1fr; } }

.service-card {
    background: var(--surface);
    border-radius: var(--radius-lg);
    padding: 1.75rem 1.5rem 1.5rem;
    border: 1.5px solid var(--border);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    position: relative;
    overflow: hidden;
    transition: transform .25s, box-shadow .25s, border-color .25s;
    text-decoration: none;
    color: inherit;
}
.service-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--navy), var(--gold));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s ease;
}
.service-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: var(--navy-border);
}
.service-card:hover::before { transform: scaleX(1); }

.svc-icon-wrap {
    width: 58px; height: 58px;
    border-radius: var(--radius);
    background: var(--navy-pale);
    color: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.45rem;
    transition: background .25s, color .25s, transform .25s;
}
.service-card:hover .svc-icon-wrap {
    background: var(--navy);
    color: #fff;
    transform: scale(1.08);
}
.svc-body { flex: 1; }
.svc-body h3 {
    font-size: .95rem;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: .4rem;
    line-height: 1.3;
}
.svc-body p {
    font-size: .82rem;
    color: var(--muted);
    line-height: 1.65;
}
.svc-apply {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    font-size: .82rem;
    font-weight: 700;
    color: var(--gold);
    text-decoration: none;
    transition: gap .2s;
}
.service-card:hover .svc-apply { gap: .7rem; }
.svc-apply i { font-size: .72rem; }

/* ════════════════════════════════════════════════════════════
   ③ HOW IT WORKS
════════════════════════════════════════════════════════════ */
.steps-section {
    background: var(--surface);
    padding: clamp(3.5rem, 7vw, 5.5rem) 0;
    border-top: 1px solid var(--border);
    border-bottom: 1px solid var(--border);
}
.steps-header {
    text-align: center;
    margin-bottom: 3.5rem;
}
.steps-header .section-sub { margin: 0 auto; max-width: 520px; }

/* Desktop: horizontal row */
.steps-row {
    display: flex;
    align-items: flex-start;
    position: relative;
=======
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

=======
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

>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
}
.steps-row::before {
    content: '';
    position: absolute;
    top: 28px;
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    left: calc(12.5% + 28px);
    right: calc(12.5% + 28px);
    height: 2px;
    background: linear-gradient(90deg, var(--navy-border), var(--gold-border), var(--navy-border));
    z-index: 0;
}
.step-item {
    flex: 1;
=======
=======
>>>>>>> Stashed changes
    left: calc(12.5% + 14px);
    right: calc(12.5% + 14px);
    height: 2px;
    background: linear-gradient(90deg, var(--gold), var(--gold-border));
    z-index: 0;
}
.step-item {
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    text-align: center;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}
<<<<<<< Updated upstream
<<<<<<< Updated upstream
.step-circle {
=======
.step-num {
>>>>>>> Stashed changes
=======
.step-num {
>>>>>>> Stashed changes
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--navy);
    color: #fff;
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    font-size: 1.15rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.25rem;
    box-shadow: 0 4px 18px rgba(13,33,68,.28);
    border: 3px solid var(--bg);
    outline: 2.5px solid var(--navy-border);
    position: relative;
    z-index: 1;
    transition: background .25s, box-shadow .25s, transform .25s;
}
.step-item:hover .step-circle {
    background: var(--gold);
    outline-color: var(--gold-border);
    box-shadow: 0 6px 22px rgba(200,134,26,.4);
    transform: scale(1.1);
}
.step-icon-sm {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--navy-pale);
    color: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem;
    margin: 0 auto .75rem;
}
.step-title {
    font-size: .9rem;
    font-weight: 700;
    color: var(--navy);
    margin-bottom: .4rem;
    line-height: 1.3;
}
.step-desc {
    font-size: .8rem;
    color: var(--muted);
    line-height: 1.65;
    max-width: 160px;
    margin: 0 auto;
=======
=======
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
}

/* Mobile: vertical timeline */
@media (max-width: 700px) {
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    .steps-row { flex-direction: column; gap: 0; }
    .steps-row::before { display: none; }

=======
=======
>>>>>>> Stashed changes
    .steps-row {
        grid-template-columns: 1fr;
        gap: 0;
        margin-top: 2rem;
    }
    .steps-row::before { display: none; }
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    .step-item {
        display: flex;
        align-items: flex-start;
        gap: 1.25rem;
        text-align: left;
        padding: 0 0 2rem 0;
        position: relative;
    }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    .step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 56px; left: 27px;
        width: 2px;
        bottom: 0;
        background: linear-gradient(to bottom, var(--navy-border), rgba(0,0,0,0));
    }
    .step-left { flex-shrink: 0; }
    .step-circle { margin: 0; }
    .step-icon-sm { display: none; }
    .step-body { flex: 1; padding-top: .9rem; }
    .step-desc { max-width: none; }
}
@media (min-width: 701px) {
    .step-left { display: contents; }
    .step-body { display: contents; }
}

/* ════════════════════════════════════════════════════════════
   ④ TRUST & SECURITY
════════════════════════════════════════════════════════════ */
.trust-section {
    background: linear-gradient(135deg, #091830 0%, var(--navy) 50%, #162e5a 100%);
    padding: clamp(3.5rem, 7vw, 5.5rem) 0;
    position: relative;
    overflow: hidden;
}
.trust-section::before {
    content: '';
    position: absolute;
    bottom: -100px; right: -80px;
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(200,134,26,.1) 0%, transparent 65%);
    pointer-events: none;
}
.trust-section::after {
    content: '';
    position: absolute;
    top: -60px; left: -60px;
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(30,63,115,.6) 0%, transparent 65%);
    pointer-events: none;
}

.trust-grid {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 4.5rem;
    align-items: center;
    position: relative;
    z-index: 1;
}

.trust-visual {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    flex-shrink: 0;
}
.trust-shield-wrap {
    width: 130px; height: 130px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
    border: 2px solid rgba(255,255,255,.14);
    display: flex; align-items: center; justify-content: center;
    font-size: 3.25rem;
    color: rgba(255,255,255,.9);
    box-shadow: 0 0 40px rgba(200,134,26,.1), inset 0 1px 0 rgba(255,255,255,.1);
}
.trust-certified {
    background: rgba(200,134,26,.2);
    border: 1px solid rgba(200,134,26,.4);
    color: var(--gold-light);
    padding: .3rem .85rem;
    border-radius: 999px;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    white-space: nowrap;
}

.trust-text { max-width: 600px; }
.trust-eyebrow { color: var(--gold); margin-bottom: .65rem; }
.trust-text h2 {
    font-size: clamp(1.4rem, 3.5vw, 2rem);
    font-weight: 800; color: #fff;
    margin-bottom: .65rem; line-height: 1.2;
}
.trust-text > p {
    color: rgba(255,255,255,.68);
    font-size: 1rem; line-height: 1.75;
    margin-bottom: 2rem;
}
.trust-points {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .85rem 1.5rem;
}
.trust-point {
    display: flex; align-items: flex-start; gap: .65rem;
    font-size: .86rem; color: rgba(255,255,255,.75);
    line-height: 1.55;
}
.trust-check {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: rgba(200,134,26,.22);
    display: flex; align-items: center; justify-content: center;
    font-size: .58rem; color: var(--gold-light);
    flex-shrink: 0; margin-top: .1rem;
}

@media (max-width: 800px) {
    .trust-grid   { grid-template-columns: 1fr; gap: 2.5rem; text-align: center; }
    .trust-text > p { margin-left: auto; margin-right: auto; }
    .trust-points { grid-template-columns: 1fr; }
    .trust-point  { text-align: left; }
}
@media (max-width: 500px) {
    .trust-visual { flex-direction: row; justify-content: center; }
}

/* ════════════════════════════════════════════════════════════
   ⑤ FAQ ACCORDION
════════════════════════════════════════════════════════════ */
.faq-section {
    background: var(--bg);
    padding: clamp(3.5rem, 7vw, 5.5rem) 0;
}
.faq-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 4rem;
    align-items: start;
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 clamp(1rem, 4vw, 2rem);
}
.faq-sidebar .section-sub { font-size: .9rem; margin-top: .5rem; }
.faq-sidebar .btn { margin-top: 1.5rem; }

.faq-list {
    display: flex;
    flex-direction: column;
    gap: .55rem;
}
.faq-item {
    background: var(--surface);
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    overflow: hidden;
    transition: border-color .2s, box-shadow .2s;
}
.faq-item.open {
    border-color: var(--navy-border);
    box-shadow: var(--shadow-sm);
}
.faq-trigger {
    width: 100%; background: none; border: none;
    padding: 1.1rem 1.25rem;
    display: flex; align-items: center; justify-content: space-between; gap: 1rem;
    cursor: pointer; text-align: left;
    font-family: 'Poppins', sans-serif;
    font-size: .92rem; font-weight: 600;
    color: var(--navy);
    min-height: 56px;
    transition: background .2s;
}
.faq-trigger:hover { background: var(--navy-pale); }
.faq-plus {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--navy-pale);
    color: var(--navy);
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem;
    flex-shrink: 0;
    transition: background .2s, transform .3s;
}
.faq-item.open .faq-plus {
    background: var(--navy);
    color: #fff;
    transform: rotate(45deg);
}
.faq-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height .38s cubic-bezier(.4,0,.2,1);
}
.faq-body-inner {
    padding: .25rem 1.25rem 1.25rem;
    font-size: .88rem; color: var(--muted);
    line-height: 1.75;
    border-top: 1px solid var(--border);
}
.faq-body-inner a { color: var(--navy); font-weight: 600; }

@media (max-width: 768px) {
    .faq-layout { grid-template-columns: 1fr; gap: 2rem; }
}

/* ════════════════════════════════════════════════════════════
   ⑥ BRANDED ACTION BLOCK  (CTA)
════════════════════════════════════════════════════════════ */
.cta-section {
    background: linear-gradient(135deg, #091830 0%, var(--navy) 50%, #1e4080 100%);
    padding: clamp(3.5rem, 7vw, 6rem) 0;
    position: relative;
    overflow: hidden;
}
.cta-section::before {
    content: '';
    position: absolute;
    top: -100px; right: -80px;
    width: 480px; height: 480px;
    background: radial-gradient(circle, rgba(200,134,26,.12) 0%, transparent 65%);
    pointer-events: none;
}
.cta-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 3rem;
    align-items: center;
    position: relative;
    z-index: 1;
}
.cta-text .section-badge { color: var(--gold); }
.cta-title {
    font-size: clamp(1.6rem, 4vw, 2.4rem);
    font-weight: 800; color: #fff;
    line-height: 1.15; margin-bottom: .75rem;
    letter-spacing: -.01em;
}
.cta-sub {
    color: rgba(255,255,255,.65);
    font-size: 1rem; line-height: 1.75;
    margin-bottom: 2rem;
    max-width: 480px;
}
.cta-actions { display: flex; gap: 1rem; flex-wrap: wrap; }

/* Floating mini portal card on the right */
.cta-card {
    background: rgba(255,255,255,.07);
    border: 1.5px solid rgba(255,255,255,.12);
    border-radius: var(--radius-xl);
    padding: 1.75rem;
    backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.cta-card-header {
    display: flex; align-items: center; gap: .75rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(255,255,255,.1);
}
.cta-card-icon {
    width: 44px; height: 44px;
    border-radius: var(--radius-sm);
    background: var(--gold);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #fff;
}
.cta-card-header h4 { color: #fff; font-size: .9rem; font-weight: 700; line-height: 1.2; }
.cta-card-header p  { color: rgba(255,255,255,.5); font-size: .72rem; }

.cta-card-stat {
    display: flex; align-items: center; gap: .65rem;
    padding: .55rem .75rem;
    border-radius: var(--radius-sm);
    background: rgba(255,255,255,.05);
    border: 1px solid rgba(255,255,255,.07);
}
.cta-card-stat i { color: var(--gold-light); font-size: .85rem; width: 16px; }
.cta-card-stat span { font-size: .8rem; color: rgba(255,255,255,.7); }
.cta-card-stat strong { color: #fff; font-weight: 700; }

@media (max-width: 800px) {
    .cta-grid { grid-template-columns: 1fr; text-align: center; }
    .cta-sub  { margin-left: auto; margin-right: auto; }
    .cta-actions { justify-content: center; }
    .cta-card { display: none; }
}

/* ════════════════════════════════════════════════════════════
   NOTICE BAR
════════════════════════════════════════════════════════════ */
.notice-bar {
    background: var(--gold-pale);
    border-top: 2.5px solid var(--gold-border);
    padding: .9rem clamp(1rem, 4vw, 2rem);
    display: flex;
    align-items: flex-start;
    gap: .75rem;
    font-size: .84rem;
    color: #78450a;
    line-height: 1.65;
    max-width: 100%;
}
.notice-bar i { color: var(--gold); flex-shrink: 0; margin-top: .15rem; }
=======
=======
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
</style>
@endpush

@section('content')

<<<<<<< Updated upstream
<<<<<<< Updated upstream
{{-- ════════════════════════════════════════════════════════════
     ① HERO
════════════════════════════════════════════════════════════ --}}
<section class="hero" aria-labelledby="hero-heading">
    <div class="hero-inner">

        {{-- Text ─────────────────────────────────────── --}}
        <div class="hero-text">
            <div class="hero-eyebrow">
                <i class="fas fa-shield-halved"></i>
                Official Digital Service — Barangay New Era
            </div>

            <h1 class="hero-title" id="hero-heading">
                Your Barangay Services.<br>
                <em>Digitized.</em>
            </h1>

            <p class="hero-subtitle">
                Request official barangay documents online and track your appointment — no need to visit the hall just to inquire.
                Fast, free, and secure for every resident of New Era.
            </p>

            <div class="hero-ctas">
                <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="{{ route('portal.track') }}" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-search"></i> Track My Request
                </a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <strong>4</strong>
                    <span>Document types</span>
                </div>
                <div class="hero-stat">
                    <strong>1–3</strong>
                    <span>Business days</span>
                </div>
                <div class="hero-stat">
                    <strong>100%</strong>
                    <span>Free for residents</span>
                </div>
                <div class="hero-stat">
                    <strong>24/7</strong>
                    <span>Online access</span>
                </div>
            </div>
        </div>

        {{-- Device mockup ──────────────────────────── --}}
        <div class="hero-visual" aria-hidden="true">
            <div class="device-frame">
                <div class="device-screen">
                    <div class="ds-topbar">
                        <div class="ds-topbar-seal"><i class="fas fa-landmark"></i></div>
                        <div class="ds-topbar-info">
                            <b>Barangay New Era</b>
                            <s>Resident Portal</s>
                        </div>
                    </div>

                    <div class="ds-row">
                        <div class="ds-row-icon"><i class="fas fa-file-shield"></i></div>
                        <div class="ds-row-lines"><b></b><s></s></div>
                        <div class="ds-row-arrow"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="ds-row">
                        <div class="ds-row-icon"><i class="fas fa-hand-holding-heart"></i></div>
                        <div class="ds-row-lines"><b></b><s></s></div>
                        <div class="ds-row-arrow"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="ds-row">
                        <div class="ds-row-icon"><i class="fas fa-house-circle-check"></i></div>
                        <div class="ds-row-lines"><b></b><s></s></div>
                        <div class="ds-row-arrow"><i class="fas fa-chevron-right"></i></div>
                    </div>
                    <div class="ds-row">
                        <div class="ds-row-icon"><i class="fas fa-store"></i></div>
                        <div class="ds-row-lines"><b></b><s></s></div>
                        <div class="ds-row-arrow"><i class="fas fa-chevron-right"></i></div>
                    </div>

                    <div class="ds-status">
                        <div class="ds-status-dot"></div>
                        <div class="ds-status-text">
                            <b>APT-20260515 · Ready</b>
                            <s>Claim at barangay hall</s>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="hero-wave" aria-hidden="true"></div>
</section>


{{-- ════════════════════════════════════════════════════════════
     ② SERVICE HUB
════════════════════════════════════════════════════════════ --}}
<section class="services-section" aria-labelledby="services-heading">
    <div class="section-wrap">
        <div class="services-header">
            <div class="section-badge"><i class="fas fa-grid-2"></i> Digital Services</div>
            <h2 class="section-heading" id="services-heading">What Can We Help You With?</h2>
            <p class="section-sub">Choose from our four available barangay documents. All requests are processed within 1–3 business days at no cost.</p>
        </div>

        <div class="service-grid">
            {{-- Card 1 --}}
            <div class="service-card">
                <div class="svc-icon-wrap"><i class="fas fa-file-shield"></i></div>
                <div class="svc-body">
                    <h3>Barangay Clearance</h3>
                    <p>Certificate of good standing within the barangay — required for employment, government permits, and official transactions.</p>
                </div>
                <a href="{{ route('portal.request') }}?type=Barangay+Clearance" class="svc-apply">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card 2 --}}
            <div class="service-card">
                <div class="svc-icon-wrap"><i class="fas fa-hand-holding-heart"></i></div>
                <div class="svc-body">
                    <h3>Certificate of Indigency</h3>
                    <p>For residents who need to avail of government assistance, medical financial aid, or social welfare programs.</p>
                </div>
                <a href="{{ route('portal.request') }}?type=Certificate+of+Indigency" class="svc-apply">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card 3 --}}
            <div class="service-card">
                <div class="svc-icon-wrap"><i class="fas fa-house-circle-check"></i></div>
                <div class="svc-body">
                    <h3>Certificate of Residency</h3>
                    <p>Official proof of residence required for government transactions, school enrollment, and other legal requirements.</p>
                </div>
                <a href="{{ route('portal.request') }}?type=Certificate+of+Residency" class="svc-apply">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            {{-- Card 4 --}}
            <div class="service-card">
                <div class="svc-icon-wrap"><i class="fas fa-store"></i></div>
                <div class="svc-body">
                    <h3>Business Clearance</h3>
                    <p>Required barangay clearance for new business registrations and the annual renewal of business permits.</p>
                </div>
                <a href="{{ route('portal.request') }}?type=Business+Clearance" class="svc-apply">
                    Apply Now <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ════════════════════════════════════════════════════════════
     ③ HOW IT WORKS
════════════════════════════════════════════════════════════ --}}
<section class="steps-section" aria-labelledby="steps-heading">
    <div class="section-wrap">
        <div class="steps-header">
            <div class="section-badge"><i class="fas fa-list-check"></i> Simple Process</div>
            <h2 class="section-heading" id="steps-heading">How It Works</h2>
            <p class="section-sub">Four easy steps — done entirely online. You only visit the barangay hall <em>once</em> to claim your document.</p>
        </div>

        <div class="steps-row">
            <div class="step-item">
                <div class="step-left">
                    <div class="step-circle">1</div>
                    <div class="step-icon-sm"><i class="fas fa-file-pen"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Fill Out the Form</div>
                    <div class="step-desc">Provide your personal details, choose the document type, and set a preferred pick-up date.</div>
                </div>
            </div>

            <div class="step-item">
                <div class="step-left">
                    <div class="step-circle">2</div>
                    <div class="step-icon-sm"><i class="fas fa-hashtag"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Receive Your Number</div>
                    <div class="step-desc">Instantly receive a unique appointment number. Save it — you'll need it to track your request.</div>
                </div>
            </div>

            <div class="step-item">
                <div class="step-left">
                    <div class="step-circle">3</div>
                    <div class="step-icon-sm"><i class="fas fa-bell"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Staff Confirms</div>
                    <div class="step-desc">Barangay staff will process your request and confirm your schedule within 1–2 business days.</div>
                </div>
            </div>

            <div class="step-item">
                <div class="step-left">
                    <div class="step-circle">4</div>
                    <div class="step-icon-sm"><i class="fas fa-id-card"></i></div>
                </div>
                <div class="step-body">
                    <div class="step-title">Claim Your Document</div>
                    <div class="step-desc">Visit the barangay hall on your confirmed date. Bring a valid ID and your appointment number.</div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ════════════════════════════════════════════════════════════
     ④ TRUST & SECURITY
════════════════════════════════════════════════════════════ --}}
<section class="trust-section" aria-labelledby="trust-heading">
    <div class="section-wrap">
        <div class="trust-grid">

            <div class="trust-visual">
                <div class="trust-shield-wrap">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="trust-certified">
                    <i class="fas fa-lock"></i>&nbsp; RA 10173 Compliant
                </div>
            </div>

            <div class="trust-text">
                <div class="section-badge trust-eyebrow">
                    <i class="fas fa-user-shield"></i> Data Privacy
                </div>
                <h2 id="trust-heading">Your Data is Safe With Us</h2>
                <p>
                    All information submitted through this portal is handled in strict accordance with
                    Republic Act 10173 — the Data Privacy Act of 2012. Your data is used solely to
                    process your document request and is never sold or shared with third parties.
                </p>
                <div class="trust-points">
                    <div class="trust-point">
                        <div class="trust-check"><i class="fas fa-check"></i></div>
                        <span>Data encrypted in transit and at rest</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-check"><i class="fas fa-check"></i></div>
                        <span>RA 10173 (Data Privacy Act) compliant</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-check"><i class="fas fa-check"></i></div>
                        <span>No third-party data sharing, ever</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-check"><i class="fas fa-check"></i></div>
                        <span>Accessed only by authorized barangay staff</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-check"><i class="fas fa-check"></i></div>
                        <span>Request data purged after document release</span>
                    </div>
                    <div class="trust-point">
                        <div class="trust-check"><i class="fas fa-check"></i></div>
                        <span>Official government-operated digital service</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ════════════════════════════════════════════════════════════
     ⑤ FAQ ACCORDION
════════════════════════════════════════════════════════════ --}}
<section class="faq-section" id="faq" aria-labelledby="faq-heading">
    <div class="faq-layout">

        {{-- Sidebar ─────────────── --}}
        <div class="faq-sidebar">
            <div class="section-badge"><i class="fas fa-circle-question"></i> FAQ</div>
            <h2 class="section-heading" id="faq-heading">Frequently Asked Questions</h2>
            <p class="section-sub">Everything you need to know about requesting documents through this portal.</p>
            <a href="{{ route('portal.request') }}" class="btn btn-primary">
                <i class="fas fa-file-plus"></i> Request Now
            </a>
        </div>

        {{-- Accordion list ──────── --}}
        <div class="faq-list" role="list">

            <div class="faq-item" role="listitem">
                <button class="faq-trigger" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Who can use this portal?</span>
                    <div class="faq-plus"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    <div class="faq-body-inner">
                        Any resident of <strong>Barangay New Era, District VI, Quezon City</strong> may use this portal to request official barangay documents.
                        You will need to present a valid government-issued ID when claiming your document at the barangay hall.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-trigger" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How long does processing take?</span>
                    <div class="faq-plus"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    <div class="faq-body-inner">
                        Most documents are processed within <strong>1–3 business days</strong> from submission.
                        Staff will confirm your pick-up date via this portal's tracker. For urgent requests, please visit the barangay hall directly during office hours.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-trigger" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Is there a fee for requesting documents online?</span>
                    <div class="faq-plus"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    <div class="faq-body-inner">
                        Using this portal is <strong>completely free of charge</strong>. Standard barangay document fees (if applicable per ordinance) are collected at the hall upon claiming. Please bring exact change.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-trigger" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What valid IDs are accepted when claiming?</span>
                    <div class="faq-plus"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    <div class="faq-body-inner">
                        Any valid government-issued photo ID is accepted: PhilSys (National ID), Passport, Driver's License, SSS/GSIS ID, Voter's ID, PRC ID, or Senior Citizen ID.
                        Your <strong>appointment number</strong> is also required.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-trigger" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How do I track the status of my request?</span>
                    <div class="faq-plus"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    <div class="faq-body-inner">
                        After submitting, you'll receive a unique <strong>appointment number</strong> (e.g., APT-20260515-AB12).
                        Visit the <a href="{{ route('portal.track') }}">Track My Status</a> page and enter this number to check real-time status anytime, from any device.
                        You can also press <kbd style="background:#f0f0f0;border:1px solid #d1d5db;border-radius:4px;padding:1px 5px;font-size:.85em">Ctrl+K</kbd> on this page and type your appointment number directly.
                    </div>
                </div>
            </div>

            <div class="faq-item" role="listitem">
                <button class="faq-trigger" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What if my request is cancelled or denied?</span>
                    <div class="faq-plus"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    <div class="faq-body-inner">
                        If your request is cancelled, the reason will appear in the tracker. Common reasons include incomplete information or ineligibility.
                        You may resubmit a new request or visit the barangay hall directly for assistance.
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ════════════════════════════════════════════════════════════
     ⑥ BRANDED ACTION BLOCK
════════════════════════════════════════════════════════════ --}}
<section class="cta-section" aria-label="Start using the portal">
    <div class="section-wrap">
        <div class="cta-grid">

            <div class="cta-text">
                <div class="section-badge"><i class="fas fa-rocket"></i> Get Started Today</div>
                <h2 class="cta-title">Start Using Barangay New Era Today</h2>
                <p class="cta-sub">
                    Join hundreds of New Era residents who've already gone digital.
                    Submit your first document request in under 2 minutes — no account needed.
                </p>
                <div class="cta-actions">
                    <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                        <i class="fas fa-file-plus"></i> Request a Document
                    </a>
                    <a href="{{ route('portal.track') }}" class="btn btn-outline-white btn-lg">
                        <i class="fas fa-search"></i> Track Existing Request
                    </a>
                </div>
            </div>

            {{-- Floating info card (hidden on mobile) --}}
            <div class="cta-card" aria-hidden="true">
                <div class="cta-card-header">
                    <div class="cta-card-icon"><i class="fas fa-landmark"></i></div>
                    <div>
                        <h4>Barangay New Era</h4>
                        <p>Digital Service Platform</p>
                    </div>
                </div>
                <div class="cta-card-stat">
                    <i class="fas fa-file-alt"></i>
                    <span>Documents available: <strong>4 types</strong></span>
                </div>
                <div class="cta-card-stat">
                    <i class="fas fa-clock"></i>
                    <span>Processing time: <strong>1–3 days</strong></span>
                </div>
                <div class="cta-card-stat">
                    <i class="fas fa-circle-dollar-to-slot"></i>
                    <span>Cost: <strong>Free for all residents</strong></span>
                </div>
                <div class="cta-card-stat">
                    <i class="fas fa-calendar-check"></i>
                    <span>Portal available: <strong>24/7 online</strong></span>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- NOTICE BAR --}}
<div class="notice-bar" role="note">
    <i class="fas fa-circle-info fa-fw"></i>
    <div>
        <strong>Important:</strong> This portal is for scheduling and tracking only. You must visit the
        <strong>Barangay New Era Hall</strong> in person to claim your document and present a valid ID.
        Office hours: <strong>Mon–Fri, 8:00 AM – 5:00 PM</strong>. For urgent requests, please visit the hall directly.
    </div>
</div>
=======
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
=======
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
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

@endsection

@push('scripts')
<script>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
/* ─── FAQ Accordion ─── */
function toggleFaq(btn) {
    var item  = btn.closest('.faq-item');
    var body  = item.querySelector('.faq-body');
    var isOpen = item.classList.contains('open');

    document.querySelectorAll('.faq-item.open').forEach(function (el) {
        el.classList.remove('open');
        el.querySelector('.faq-body').style.maxHeight = '0';
        el.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
    });

    if (!isOpen) {
        item.classList.add('open');
        body.style.maxHeight = body.scrollHeight + 'px';
        btn.setAttribute('aria-expanded', 'true');
    }
}

/* ─── Pre-fill request form from URL query param ─── */
document.addEventListener('DOMContentLoaded', function () {
    var params = new URLSearchParams(window.location.search);
    var type   = params.get('type');
    if (type) {
        var sel = document.querySelector('select[name="document_type"]');
        if (sel) {
            Array.from(sel.options).forEach(function (o) {
                if (o.value === type) o.selected = true;
            });
        }
    }
});
=======
=======
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
</script>
@endpush
