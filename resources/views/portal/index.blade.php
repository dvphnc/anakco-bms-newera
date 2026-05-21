@extends('layouts.portal')
@section('title', 'Resident Portal')

@push('styles')
<style>
body { background: #fff; }

/* ─────────────────────────────────────────────
   SCROLL ANIMATIONS
───────────────────────────────────────────── */
[data-ao] {
    opacity: 0;
    transition: opacity .65s ease, transform .65s ease;
}
[data-ao="fade-up"]    { transform: translateY(32px); }
[data-ao="fade-down"]  { transform: translateY(-24px); }
[data-ao="fade-left"]  { transform: translateX(32px); }
[data-ao="fade-right"] { transform: translateX(-32px); }
[data-ao="zoom-in"]    { transform: scale(.93); }
[data-ao].ao-visible {
    opacity: 1;
    transform: translateY(0) translateX(0) scale(1);
}
[data-ao-delay="1"] { transition-delay: .08s; }
[data-ao-delay="2"] { transition-delay: .16s; }
[data-ao-delay="3"] { transition-delay: .24s; }
[data-ao-delay="4"] { transition-delay: .32s; }
[data-ao-delay="5"] { transition-delay: .40s; }
[data-ao-delay="6"] { transition-delay: .48s; }

/* Hero items animate on load (not scroll) */
.h-anim {
    opacity: 0;
    transform: translateY(22px);
    animation: heroIn .6s ease forwards;
}
@keyframes heroIn {
    to { opacity: 1; transform: translateY(0); }
}
.h-anim-1 { animation-delay: .1s; }
.h-anim-2 { animation-delay: .22s; }
.h-anim-3 { animation-delay: .34s; }
.h-anim-4 { animation-delay: .46s; }
.h-anim-5 { animation-delay: .58s; }
.h-panel-anim {
    opacity: 0;
    transform: translateX(28px);
    animation: panelIn .7s ease forwards;
    animation-delay: .3s;
}
@keyframes panelIn {
    to { opacity: 1; transform: translateX(0); }
}

/* ─────────────────────────────────────────────
   SHARED UTILITIES
───────────────────────────────────────────── */
.sec { padding: clamp(3.5rem, 7vw, 5.5rem) clamp(1rem, 5vw, 2.5rem); }
.sec-inner    { max-width: 1140px; margin: 0 auto; }
.sec-inner-sm { max-width: 760px;  margin: 0 auto; }
.sec-inner-md { max-width: 960px;  margin: 0 auto; }
.sec-tag {
    display: inline-flex; align-items: center; gap: 7px;
    font-size: .68rem; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--gold); margin-bottom: .55rem;
}
.sec-tag::before {
    content: ''; width: 20px; height: 2px;
    background: var(--gold); border-radius: 99px;
}
.sec-h2 {
    font-size: clamp(1.55rem, 3.2vw, 2.2rem);
    font-weight: 800; color: var(--navy);
    letter-spacing: -0.028em; line-height: 1.18; margin-bottom: .55rem;
}
.sec-h2 em { font-style: normal; color: var(--gold); }
.sec-lead {
    font-size: .93rem; color: #5a6474;
    line-height: 1.8; max-width: 540px;
}

/* ─────────────────────────────────────────────
   HERO
───────────────────────────────────────────── */
.hero {
    background: linear-gradient(140deg, #07162a 0%, #0D2144 50%, #14305e 100%);
    min-height: 90vh;
    display: flex; align-items: center;
    padding: clamp(5rem, 10vw, 7rem) clamp(1rem, 5vw, 2.5rem)
             clamp(3.5rem, 6vw, 5rem);
    position: relative; overflow: hidden;
}
.hero-deco-a {
    position: absolute; top: -120px; right: -80px;
    width: 580px; height: 580px; border-radius: 50%;
    background: radial-gradient(circle, rgba(200,134,26,.09) 0%, transparent 68%);
    pointer-events: none;
}
.hero-deco-b {
    position: absolute; bottom: -140px; left: -60px;
    width: 440px; height: 440px; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,.03) 0%, transparent 65%);
    pointer-events: none;
}
.hero::after {
    content: ''; position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
    background-size: 48px 48px; pointer-events: none;
}
.hero-inner {
    position: relative; z-index: 1;
    max-width: 1140px; margin: 0 auto; width: 100%;
    display: grid; grid-template-columns: 1fr 400px;
    gap: 4rem; align-items: center;
}

/* ── Hero Left ── */
.h-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(200,134,26,.15); border: 1px solid rgba(200,134,26,.3);
    border-radius: 99px; padding: 5px 14px;
    font-size: .68rem; font-weight: 700; color: #e0a843;
    letter-spacing: .1em; text-transform: uppercase; margin-bottom: 1.4rem;
}
.h-eyebrow i { font-size: .6rem; }

.h-title {
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    font-weight: 800; color: #fff;
    line-height: 1.08; letter-spacing: -0.035em; margin-bottom: 1.25rem;
}
.h-title em { font-style: normal; color: var(--gold); }

.h-desc {
    font-size: clamp(.9rem, 1.7vw, 1.03rem);
    color: rgba(255,255,255,.6);
    line-height: 1.85; max-width: 460px; margin-bottom: 2.25rem;
}
.h-actions { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 2.5rem; }
.h-trust {
    display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;
    padding-top: 2rem; border-top: 1px solid rgba(255,255,255,.1);
}
.h-trust-item {
    display: flex; align-items: center; gap: .45rem;
    font-size: .75rem; color: rgba(255,255,255,.5); font-weight: 500;
}
.h-trust-item i { color: var(--gold); opacity: .85; font-size: .65rem; }

/* ── Hero Right Panel ── */
.h-panel {
    background: #fff; border-radius: 20px; padding: 1.85rem;
    box-shadow: 0 24px 64px rgba(0,0,0,.28), 0 4px 12px rgba(0,0,0,.12);
}
.h-panel-label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .12em; color: var(--gold); margin-bottom: 1.25rem;
}
.h-services { display: flex; flex-direction: column; gap: .45rem; margin-bottom: 1.35rem; }
.h-svc {
    display: flex; align-items: center; gap: .7rem; padding: .7rem .85rem;
    border-radius: 10px; border: 1.5px solid #edf0f5; text-decoration: none;
    background: #fafbfd; transition: border-color .15s, background .15s, box-shadow .15s;
}
.h-svc:hover {
    border-color: rgba(200,134,26,.35); background: #fffdf7;
    box-shadow: 0 2px 10px rgba(200,134,26,.1);
}
.h-svc-ico {
    width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: .8rem;
}
.h-svc-body { flex: 1; min-width: 0; }
.h-svc-body strong { display: block; font-size: .82rem; font-weight: 600; color: var(--navy); }
.h-svc-body span   { font-size: .71rem; color: #9ca3af; }
.h-svc-arr { color: #d1d5db; font-size: .68rem; flex-shrink: 0; transition: color .15s, transform .15s; }
.h-svc:hover .h-svc-arr { color: var(--gold); transform: translateX(3px); }

.h-divider {
    display: flex; align-items: center; gap: .65rem;
    font-size: .68rem; color: #c9cfd9; font-weight: 600;
    text-transform: uppercase; letter-spacing: .08em; margin-bottom: 1.1rem;
}
.h-divider::before, .h-divider::after { content: ''; flex: 1; height: 1px; background: #edf0f5; }

.h-track-form { display: flex; flex-direction: column; gap: .55rem; }
.h-inp-wrap { position: relative; }
.h-inp-wrap i {
    position: absolute; left: .9rem; top: 50%;
    transform: translateY(-50%); color: #b5bec9; font-size: .8rem; pointer-events: none;
}
.h-inp-wrap input {
    width: 100%; height: 48px; padding: 0 .9rem 0 2.4rem;
    border: 1.5px solid #dde1e8; border-radius: 10px;
    font-family: 'Poppins', sans-serif; font-size: .86rem; color: var(--text);
    background: #fafbfc; transition: border-color .2s, box-shadow .2s;
}
.h-inp-wrap input:focus {
    outline: none; border-color: var(--navy); background: #fff;
    box-shadow: 0 0 0 3px rgba(13,33,68,.08);
}
.h-inp-wrap input::placeholder { color: #b5bec9; }
.h-track-btn {
    height: 48px; background: var(--navy); color: #fff; border: none;
    border-radius: 10px; font-family: 'Poppins', sans-serif;
    font-size: .88rem; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    transition: background .2s, box-shadow .2s, transform .15s;
}
.h-track-btn:hover {
    background: var(--navy-mid);
    box-shadow: 0 4px 16px rgba(13,33,68,.28);
    transform: translateY(-1px);
}
.h-track-btn:active { transform: translateY(0); }

/* ─────────────────────────────────────────────
   STATS STRIP
───────────────────────────────────────────── */
.stats-strip {
    background: #fff; border-top: 1px solid #e8ecf1; border-bottom: 1px solid #e8ecf1;
    padding: 0 clamp(1rem, 5vw, 2.5rem);
}
.stats-strip-inner {
    max-width: 1140px; margin: 0 auto;
    display: grid; grid-template-columns: repeat(4, 1fr);
}
.stat-item {
    padding: 1.75rem 1.5rem;
    display: flex; flex-direction: column; align-items: center;
    text-align: center; gap: .3rem; position: relative;
}
.stat-item:not(:last-child)::after {
    content: ''; position: absolute; right: 0; top: 20%; bottom: 20%;
    width: 1px; background: #e8ecf1;
}
.stat-num {
    font-size: 1.9rem; font-weight: 800; color: var(--navy);
    line-height: 1; letter-spacing: -0.035em;
}
.stat-num em { font-style: normal; color: var(--gold); }
.stat-lbl { font-size: .73rem; color: #9ca3af; font-weight: 500; }

/* ─────────────────────────────────────────────
   SERVICES
───────────────────────────────────────────── */
.services-sec { background: #f8fafd; border-bottom: 1px solid #eaecf0; }
.svc-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1rem; margin-top: 2.75rem;
}
.svc-card {
    background: #fff; border: 1.5px solid #eceef3;
    border-radius: 16px; padding: 1.75rem 1.5rem;
    text-decoration: none; display: flex; flex-direction: column;
    transition: border-color .25s, box-shadow .25s, transform .25s;
    position: relative; overflow: hidden;
}
.svc-card::before {
    content: ''; position: absolute; top: 0; left: 0;
    width: 100%; height: 3px; border-radius: 16px 16px 0 0;
    opacity: 0; transition: opacity .25s;
}
.svc-card:hover { border-color: #d4dae8; box-shadow: 0 10px 36px rgba(13,33,68,.1); transform: translateY(-5px); }
.svc-card:hover::before { opacity: 1; }
.sc-navy::before  { background: var(--navy); }
.sc-gold::before  { background: var(--gold); }
.sc-green::before { background: #16a34a; }
.sc-blue::before  { background: #2563eb; }
.sc-red::before   { background: #dc2626; }
.sc-teal::before  { background: #0e7490; }
.svc-icon {
    width: 52px; height: 52px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 1.1rem; flex-shrink: 0;
    transition: transform .25s;
}
.svc-card:hover .svc-icon { transform: scale(1.1) rotate(-3deg); }
.sc-navy  .svc-icon { background: rgba(13,33,68,.07);  color: var(--navy); }
.sc-gold  .svc-icon { background: rgba(200,134,26,.1); color: var(--gold); }
.sc-green .svc-icon { background: rgba(22,163,74,.09); color: #16a34a; }
.sc-blue  .svc-icon { background: rgba(37,99,235,.08); color: #2563eb; }
.sc-red   .svc-icon { background: rgba(220,38,38,.07); color: #dc2626; }
.sc-teal  .svc-icon { background: rgba(14,116,144,.07);color: #0e7490; }
.svc-card h3 { font-size: .97rem; font-weight: 700; color: var(--navy); margin-bottom: .4rem; line-height: 1.3; }
.svc-card p  { font-size: .82rem; color: #6b7280; line-height: 1.7; flex: 1; margin-bottom: 1.1rem; }
.svc-link {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .75rem; font-weight: 700; color: var(--navy);
    text-transform: uppercase; letter-spacing: .06em;
    transition: gap .2s, color .2s;
}
.svc-card:hover .svc-link { gap: 9px; color: var(--gold); }
.svc-link i { font-size: .58rem; }

/* ─────────────────────────────────────────────
   HOW IT WORKS
───────────────────────────────────────────── */
.how-sec { background: #fff; }
.how-steps {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 0; margin-top: 3.25rem; position: relative;
}
.how-steps::before {
    content: ''; position: absolute;
    top: 27px; left: calc(12.5% + 18px); right: calc(12.5% + 18px);
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(200,134,26,.4) 20%, rgba(200,134,26,.4) 80%, transparent);
    z-index: 0;
}
.how-step { text-align: center; padding: 0 1.25rem; position: relative; z-index: 1; }
.how-num {
    width: 56px; height: 56px; border-radius: 50%;
    background: var(--navy); color: #fff;
    font-size: 1rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.35rem;
    box-shadow: 0 0 0 5px #fff, 0 0 0 6px rgba(200,134,26,.3), 0 6px 20px rgba(13,33,68,.18);
    position: relative; z-index: 2;
    transition: transform .3s cubic-bezier(.34,1.56,.64,1),
                background .3s ease,
                box-shadow .3s ease,
                color .3s ease;
}
/* Ping ring on hover */
.how-num::after {
    content: '';
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid var(--gold);
    opacity: 0;
    transform: scale(.8);
    transition: opacity .3s ease, transform .3s ease;
}
.how-step:hover .how-num {
    background: var(--gold);
    color: #fff;
    transform: scale(1.12) translateY(-3px);
    box-shadow: 0 0 0 5px #fff, 0 0 0 7px rgba(200,134,26,.45), 0 12px 32px rgba(200,134,26,.35);
}
.how-step:hover .how-num::after {
    opacity: 1;
    transform: scale(1.22);
    animation: how-ping .7s ease-out infinite;
}
@keyframes how-ping {
    0%   { transform: scale(1.1); opacity: .8; }
    100% { transform: scale(1.5); opacity: 0;  }
}
.how-step h4 { font-size: .92rem; font-weight: 700; color: var(--navy); margin-bottom: .45rem; line-height: 1.3; transition: color .3s ease; }
.how-step p  { font-size: .81rem; color: #6b7280; line-height: 1.72; }
.how-step:hover h4 { color: var(--gold); }

/* ─────────────────────────────────────────────
   TRUST & PRIVACY
───────────────────────────────────────────── */
.trust-sec { background: #f8fafd; border-top: 1px solid #eaecf0; border-bottom: 1px solid #eaecf0; }
.trust-inner {
    display: grid; grid-template-columns: 1fr 1.1fr;
    gap: 5rem; align-items: center;
}
.trust-shield {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; color: #fff; margin-bottom: 1.5rem;
    box-shadow: 0 8px 28px rgba(13,33,68,.2);
    transition: transform .3s, box-shadow .3s;
}
.trust-shield:hover { transform: scale(1.07); box-shadow: 0 12px 36px rgba(13,33,68,.3); }
.trust-left h2 {
    font-size: clamp(1.4rem, 3vw, 2rem);
    font-weight: 800; color: var(--navy);
    letter-spacing: -0.025em; margin-bottom: .7rem; line-height: 1.18;
}
.trust-left p  { font-size: .92rem; color: #5a6474; line-height: 1.8; margin-bottom: 1.5rem; }
.trust-dpa {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(13,33,68,.06); border: 1px solid rgba(13,33,68,.13);
    border-radius: 99px; padding: 6px 16px;
    font-size: .74rem; font-weight: 700; color: var(--navy);
}
.trust-dpa i { color: var(--gold); font-size: .65rem; }
.trust-boxes { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
.trust-box {
    background: #fff; border: 1.5px solid #eceef3;
    border-radius: 14px; padding: 1.3rem 1.15rem;
    transition: border-color .2s, box-shadow .2s, transform .2s;
}
.trust-box:hover { border-color: #c3cedf; box-shadow: 0 6px 22px rgba(13,33,68,.08); transform: translateY(-3px); }
.trust-box-ico {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; margin-bottom: .8rem;
}
.trust-box h4 { font-size: .83rem; font-weight: 700; color: var(--navy); margin-bottom: .3rem; line-height: 1.3; }
.trust-box p  { font-size: .74rem; color: #9ca3af; line-height: 1.6; }

/* ─────────────────────────────────────────────
   FAQ
───────────────────────────────────────────── */
.faq-sec { background: #fff; }
.faq-list { display: flex; flex-direction: column; gap: .5rem; margin-top: 2.5rem; }
.faq-item {
    border: 1.5px solid #eceef3; border-radius: 12px;
    background: #fff; overflow: hidden; transition: border-color .2s, box-shadow .2s;
}
.faq-item.open { border-color: #c3cedf; box-shadow: 0 4px 18px rgba(13,33,68,.06); }
.faq-btn {
    width: 100%; display: flex; align-items: center;
    justify-content: space-between; gap: 12px;
    padding: 1.1rem 1.3rem; background: none; border: none;
    font-family: 'Poppins', sans-serif;
    font-size: .9rem; font-weight: 600; color: var(--navy);
    cursor: pointer; text-align: left; min-height: 58px; transition: background .1s;
}
.faq-btn:hover { background: #fafbfd; }
.faq-icon {
    width: 28px; height: 28px; border-radius: 50%;
    background: #f0f2f5; color: var(--navy); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: .68rem;
    transition: transform .25s, background .2s, color .2s;
}
.faq-item.open .faq-icon { transform: rotate(45deg); background: var(--navy); color: #fff; }
.faq-body {
    max-height: 0; overflow: hidden;
    transition: max-height .35s ease, padding .35s ease;
    font-size: .88rem; color: #5a6474; line-height: 1.8;
    padding: 0 1.3rem;
}
.faq-item.open .faq-body {
    max-height: 300px;
    padding: .9rem 1.3rem 1.2rem;
    border-top: 1px solid #f0f2f5;
}

/* ─────────────────────────────────────────────
   CTA BAND
───────────────────────────────────────────── */
.cta-band {
    background: linear-gradient(135deg, #07162a 0%, #0D2144 55%, #14305e 100%);
    padding: clamp(3.5rem, 7vw, 6rem) clamp(1rem, 5vw, 2.5rem);
    text-align: center; position: relative; overflow: hidden;
}
.cta-band::before {
    content: ''; position: absolute; top: -100px; right: -80px;
    width: 460px; height: 460px;
    background: radial-gradient(circle, rgba(200,134,26,.1) 0%, transparent 65%);
    pointer-events: none;
}
.cta-band::after {
    content: ''; position: absolute; bottom: -80px; left: 5%;
    width: 340px; height: 340px;
    background: radial-gradient(circle, rgba(255,255,255,.03) 0%, transparent 65%);
    pointer-events: none;
}
.cta-band-inner { position: relative; z-index: 1; max-width: 600px; margin: 0 auto; }
.cta-band h2 {
    font-size: clamp(1.65rem, 4vw, 2.5rem);
    font-weight: 800; color: #fff;
    letter-spacing: -0.03em; margin-bottom: .8rem; line-height: 1.12;
}
.cta-band h2 em { font-style: normal; color: var(--gold); }
.cta-band p { font-size: .95rem; color: rgba(255,255,255,.5); line-height: 1.85; margin-bottom: 2.25rem; }
.cta-btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }

/* ─────────────────────────────────────────────
   RESPONSIVE — TABLET
───────────────────────────────────────────── */
@media (max-width: 980px) {
    .hero { min-height: unset; padding-top: 5rem; padding-bottom: 4rem; }
    .hero-inner  { grid-template-columns: 1fr; gap: 2.5rem; }
    .h-panel     { max-width: 520px; }
    .trust-inner { grid-template-columns: 1fr; gap: 2.75rem; }
    .svc-grid    { grid-template-columns: repeat(2, 1fr); }
}

/* ─────────────────────────────────────────────
   RESPONSIVE — MOBILE
───────────────────────────────────────────── */
@media (max-width: 640px) {
    .hero { padding-top: 4.5rem; padding-bottom: 3rem; }
    .hero-inner { gap: 2rem; }

    .h-eyebrow { font-size: .62rem; padding: 4px 12px; margin-bottom: 1rem; }
    .h-title   { font-size: 2rem; margin-bottom: 1rem; }
    .h-desc    { font-size: .88rem; margin-bottom: 1.75rem; }
    .h-actions { flex-direction: column; gap: .6rem; }
    .h-actions .btn { width: 100%; justify-content: center; }
    .h-trust   { gap: 1rem; padding-top: 1.5rem; }
    .h-trust-item { font-size: .7rem; }

    .h-panel   { padding: 1.35rem; border-radius: 14px; }

    .stats-strip-inner { grid-template-columns: repeat(2, 1fr); }
    .stat-item:nth-child(2)::after { display: none; }
    .stat-item { padding: 1.25rem 1rem; }
    .stat-num  { font-size: 1.55rem; }
    .stat-lbl  { font-size: .68rem; }

    .svc-grid  { grid-template-columns: 1fr; gap: .85rem; margin-top: 2rem; }

    .how-steps { grid-template-columns: 1fr; gap: 0; }
    .how-steps::before { display: none; }
    .how-step {
        display: flex; align-items: flex-start; gap: 1rem;
        text-align: left; padding: 0 0 2rem; position: relative;
    }
    .how-step:last-child { padding-bottom: 0; }
    .how-step:not(:last-child)::after {
        content: ''; position: absolute; left: 27px; top: 56px; bottom: 0;
        width: 1px; background: rgba(200,134,26,.25);
    }
    .how-num { flex-shrink: 0; margin: 0; width: 48px; height: 48px; font-size: .9rem;
               box-shadow: 0 0 0 4px #fff, 0 0 0 5px rgba(200,134,26,.3); }
    .how-step-body { flex: 1; padding-top: 10px; }
    .how-step h4 { font-size: .88rem; }

    .trust-inner { gap: 2rem; }
    .trust-boxes { grid-template-columns: 1fr; gap: .7rem; }

    .faq-btn { font-size: .84rem; padding: .9rem 1rem; min-height: 52px; }
    .faq-body { font-size: .84rem; }
    .faq-item.open .faq-body { padding: .75rem 1rem 1rem; }

    .cta-band h2 { font-size: 1.55rem; }
    .cta-btns { flex-direction: column; align-items: stretch; }
    .cta-btns .btn { width: 100%; justify-content: center; }

    .sec { padding: clamp(2.5rem, 6vw, 4rem) clamp(1rem, 5vw, 1.5rem); }
    .sec-h2  { font-size: 1.5rem; }
    .sec-lead { font-size: .88rem; }
}

@media (max-width: 400px) {
    .h-title { font-size: 1.8rem; }
    .stat-num { font-size: 1.35rem; }
}
</style>
@endpush

@section('content')

{{-- ════════════════════════════════════════════
     HERO
════════════════════════════════════════════ --}}
<section class="hero" aria-label="Barangay New Era Resident Portal">
    <div class="hero-deco-a" aria-hidden="true"></div>
    <div class="hero-deco-b" aria-hidden="true"></div>
    <div class="hero-inner">

        {{-- Left --}}
        <div>
            <div class="h-eyebrow h-anim h-anim-1">
                <i class="fas fa-shield-halved"></i>
                Official Government Digital Portal
            </div>
            <h1 class="h-title h-anim h-anim-2">
                Your Barangay,<br>
                Always <em>Within Reach.</em>
            </h1>
            <p class="h-desc h-anim h-anim-3">
                Access official barangay documents and services online —
                no queues, no repeated trips, completely free for every resident
                of Barangay New Era.
            </p>
            <div class="h-actions h-anim h-anim-4">
                <a href="{{ route('portal.request') }}" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="{{ route('portal.track') }}" class="btn btn-outline-white btn-lg">
                    <i class="fas fa-search"></i> Track My Request
                </a>
            </div>
            <div class="h-trust h-anim h-anim-5">
                <div class="h-trust-item"><i class="fas fa-lock"></i> End-to-end secure</div>
                <div class="h-trust-item"><i class="fas fa-circle-check"></i> 100% Free Service</div>
                <div class="h-trust-item"><i class="fas fa-certificate"></i> DPA 2012 Compliant</div>
            </div>
        </div>

        {{-- Right: Quick Access Panel --}}
        <div class="h-panel h-panel-anim">
            <div class="h-panel-label">Quick Access</div>
            <div class="h-services">
                <a href="{{ route('portal.request') }}" class="h-svc">
                    <div class="h-svc-ico" style="background:rgba(13,33,68,.07);color:var(--navy)">
                        <i class="fas fa-file-shield"></i>
                    </div>
                    <div class="h-svc-body">
                        <strong>Request a Document</strong>
                        <span>Clearance, Indigency, Residency</span>
                    </div>
                    <i class="fas fa-chevron-right h-svc-arr"></i>
                </a>
                <a href="{{ route('portal.blotter') }}" class="h-svc">
                    <div class="h-svc-ico" style="background:rgba(220,38,38,.07);color:#dc2626">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="h-svc-body">
                        <strong>File a Blotter Report</strong>
                        <span>Report an incident online</span>
                    </div>
                    <i class="fas fa-chevron-right h-svc-arr"></i>
                </a>
                <a href="{{ route('portal.business') }}" class="h-svc">
                    <div class="h-svc-ico" style="background:rgba(14,116,144,.07);color:#0e7490">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="h-svc-body">
                        <strong>Business Permit</strong>
                        <span>New application or renewal</span>
                    </div>
                    <i class="fas fa-chevron-right h-svc-arr"></i>
                </a>
            </div>
            <div class="h-divider">or track existing request</div>
            <form class="h-track-form" action="{{ route('portal.track.post') }}" method="POST">
                @csrf
                <div class="h-inp-wrap">
                    <i class="fas fa-hashtag"></i>
                    <input type="text" name="appointment_number"
                           placeholder="e.g. APT-20260520-AB12"
                           autocomplete="off" spellcheck="false"
                           aria-label="Appointment number">
                </div>
                <button type="submit" class="h-track-btn">
                    <i class="fas fa-search"></i> Check Status
                </button>
            </form>
        </div>

    </div>
</section>

{{-- ════════════════════════════════════════════
     STATS STRIP
════════════════════════════════════════════ --}}
<div class="stats-strip">
    <div class="stats-strip-inner">
        <div class="stat-item" data-ao="fade-up" data-ao-delay="1">
            <div class="stat-num" data-count="4"><em>4</em></div>
            <div class="stat-lbl">Document Types Available</div>
        </div>
        <div class="stat-item" data-ao="fade-up" data-ao-delay="2">
            <div class="stat-num">1<span style="font-size:.9rem;color:#c9d0d9;font-weight:500">–</span>3</div>
            <div class="stat-lbl">Business Days to Process</div>
        </div>
        <div class="stat-item" data-ao="fade-up" data-ao-delay="3">
            <div class="stat-num"><em>100%</em></div>
            <div class="stat-lbl">Free for All Residents</div>
        </div>
        <div class="stat-item" data-ao="fade-up" data-ao-delay="4">
            <div class="stat-num">24<span style="font-size:.9rem;color:#c9d0d9;font-weight:400">/7</span></div>
            <div class="stat-lbl">Online Accessibility</div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════
     SERVICES
════════════════════════════════════════════ --}}
<section class="sec services-sec" id="services">
    <div class="sec-inner">
        <div data-ao="fade-up">
            <div class="sec-tag">Services</div>
            <h2 class="sec-h2">What Can We Help <em>You With?</em></h2>
            <p class="sec-lead">All barangay services, now available digitally. Secure, official, and accessible to every resident of Barangay New Era.</p>
        </div>

        <div class="svc-grid">
            <a href="{{ route('portal.request') }}?type=Barangay+Clearance" class="svc-card sc-navy" data-ao="fade-up" data-ao-delay="1">
                <div class="svc-icon"><i class="fas fa-file-shield"></i></div>
                <h3>Barangay Clearance</h3>
                <p>Certificate of good standing for employment, loans, business permits, and other official requirements.</p>
                <div class="svc-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('portal.request') }}?type=Certificate+of+Indigency" class="svc-card sc-gold" data-ao="fade-up" data-ao-delay="2">
                <div class="svc-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3>Certificate of Indigency</h3>
                <p>For residents availing government assistance programs, PhilHealth, or medical financial aid.</p>
                <div class="svc-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('portal.request') }}?type=Certificate+of+Residency" class="svc-card sc-green" data-ao="fade-up" data-ao-delay="3">
                <div class="svc-icon"><i class="fas fa-house-circle-check"></i></div>
                <h3>Certificate of Residency</h3>
                <p>Proof of residence for school enrollment, government transactions, and official requirements.</p>
                <div class="svc-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('portal.request') }}?type=Business+Clearance" class="svc-card sc-blue" data-ao="fade-up" data-ao-delay="1">
                <div class="svc-icon"><i class="fas fa-building"></i></div>
                <h3>Business Clearance</h3>
                <p>Required for new business registration and annual renewal of permits within the barangay.</p>
                <div class="svc-link">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('portal.blotter') }}" class="svc-card sc-red" data-ao="fade-up" data-ao-delay="2">
                <div class="svc-icon"><i class="fas fa-gavel"></i></div>
                <h3>File a Blotter Report</h3>
                <p>Report an incident online. Our Peace &amp; Order committee will follow up and facilitate mediation.</p>
                <div class="svc-link">File Report <i class="fas fa-arrow-right"></i></div>
            </a>
            <a href="{{ route('portal.business') }}" class="svc-card sc-teal" data-ao="fade-up" data-ao-delay="3">
                <div class="svc-icon"><i class="fas fa-file-contract"></i></div>
                <h3>Business Permit Application</h3>
                <p>Apply for a new business permit or annual renewal online. Staff will review and process your application.</p>
                <div class="svc-link">Apply Now <i class="fas fa-arrow-right"></i></div>
            </a>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════ --}}
<section class="sec how-sec" id="how-it-works">
    <div class="sec-inner">
        <div style="text-align:center" data-ao="fade-up">
            <div class="sec-tag" style="justify-content:center">Process</div>
            <h2 class="sec-h2" style="text-align:center">How It <em>Works.</em></h2>
            <p class="sec-lead" style="margin:0 auto;text-align:center">From submission to release — simple and transparent.</p>
        </div>
        <div class="how-steps">
            <div class="how-step" data-ao="fade-up" data-ao-delay="1">
                <div class="how-num">1</div>
                <div class="how-step-body">
                    <h4>Fill the Form</h4>
                    <p>Provide your personal details and select the service you need. No account required.</p>
                </div>
            </div>
            <div class="how-step" data-ao="fade-up" data-ao-delay="2">
                <div class="how-num">2</div>
                <div class="how-step-body">
                    <h4>Get Your Reference Number</h4>
                    <p>Receive a unique reference number immediately after submitting your request online.</p>
                </div>
            </div>
            <div class="how-step" data-ao="fade-up" data-ao-delay="3">
                <div class="how-num">3</div>
                <div class="how-step-body">
                    <h4>Await Confirmation</h4>
                    <p>Barangay staff will review and confirm your preferred schedule within 1–2 business days.</p>
                </div>
            </div>
            <div class="how-step" data-ao="fade-up" data-ao-delay="4">
                <div class="how-num">4</div>
                <div class="how-step-body">
                    <h4>Claim Your Document</h4>
                    <p>Visit the Barangay Hall on your confirmed date with a valid ID to claim your document.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     TRUST & PRIVACY
════════════════════════════════════════════ --}}
<section class="sec trust-sec" id="privacy">
    <div class="sec-inner">
        <div class="trust-inner">
            <div class="trust-left" data-ao="fade-right">
                <div class="trust-shield" aria-hidden="true">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h2>Your Data is Safe With Us.</h2>
                <p>We are committed to protecting the privacy and security of every resident's information. Your data is used exclusively for processing your requests and is accessible only by authorized Barangay New Era personnel.</p>
                <div class="trust-dpa">
                    <i class="fas fa-certificate"></i>
                    Data Privacy Act of 2012 Compliant
                </div>
            </div>
            <div class="trust-boxes">
                <div class="trust-box" data-ao="zoom-in" data-ao-delay="1">
                    <div class="trust-box-ico" style="background:rgba(37,99,235,.08);color:#2563eb"><i class="fas fa-landmark-flag"></i></div>
                    <h4>Government Platform</h4>
                    <p>Officially operated by Barangay New Era, a unit of Philippine local government.</p>
                </div>
                <div class="trust-box" data-ao="zoom-in" data-ao-delay="2">
                    <div class="trust-box-ico" style="background:rgba(22,163,74,.08);color:#16a34a"><i class="fas fa-file-shield"></i></div>
                    <h4>Data Privacy</h4>
                    <p>All data handled under Republic Act 10173 — Data Privacy Act of the Philippines.</p>
                </div>
                <div class="trust-box" data-ao="zoom-in" data-ao-delay="3">
                    <div class="trust-box-ico" style="background:rgba(200,134,26,.09);color:var(--gold)"><i class="fas fa-lock"></i></div>
                    <h4>Encrypted Transactions</h4>
                    <p>All form submissions are protected with HTTPS encryption and CSRF protection.</p>
                </div>
                <div class="trust-box" data-ao="zoom-in" data-ao-delay="4">
                    <div class="trust-box-ico" style="background:rgba(13,33,68,.07);color:var(--navy)"><i class="fas fa-user-shield"></i></div>
                    <h4>Staff-Only Access</h4>
                    <p>Only verified and authorized barangay personnel can view or process your information.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     FAQ
════════════════════════════════════════════ --}}
<section class="sec faq-sec" id="faq">
    <div class="sec-inner-md">
        <div style="text-align:center;margin-bottom:2.5rem" data-ao="fade-up">
            <div class="sec-tag" style="justify-content:center">FAQ</div>
            <h2 class="sec-h2" style="text-align:center">Frequently Asked <em>Questions.</em></h2>
            <p class="sec-lead" style="margin:0 auto;text-align:center">Everything you need to know about the Barangay New Era Resident Portal.</p>
        </div>
        <div class="faq-list">
            <div class="faq-item" data-ao="fade-up" data-ao-delay="1">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How long does document processing take?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">Processing typically takes <strong>1–3 business days</strong> from the date your request is confirmed. Barangay Clearance is usually ready within 1 business day. You will need to visit the hall on your confirmed schedule to personally claim your document.</div>
            </div>
            <div class="faq-item" data-ao="fade-up" data-ao-delay="2">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Is the portal free to use?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">Yes. This portal is a <strong>free public service</strong> provided by Barangay New Era to all residents. There are no fees for requesting, processing, or claiming any document through this portal.</div>
            </div>
            <div class="faq-item" data-ao="fade-up" data-ao-delay="3">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What valid IDs are accepted when claiming my document?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">Any government-issued photo ID is accepted: <strong>PhilSys National ID, Passport, Driver's License, SSS/GSIS/Pag-IBIG ID, Voter's ID, or PhilHealth ID</strong>. The name on your ID must match the name provided in your request.</div>
            </div>
            <div class="faq-item" data-ao="fade-up" data-ao-delay="4">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Can someone else claim my document on my behalf?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">A representative may claim on your behalf but must present a <strong>Special Power of Attorney (SPA)</strong>, their own valid ID, and a photocopy of the requesting resident's valid ID.</div>
            </div>
            <div class="faq-item" data-ao="fade-up" data-ao-delay="5">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What if my reference number is not found?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">Double-check that you entered the number exactly as shown on your confirmation page (e.g., <code style="background:#f0f2f5;padding:2px 7px;border-radius:5px;font-size:.85em">APT-20260520-AB12</code>). If the issue persists, visit the Barangay Hall: <strong>Monday–Friday, 8:00 AM – 5:00 PM</strong>.</div>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     CTA BAND
════════════════════════════════════════════ --}}
<section class="cta-band">
    <div class="cta-band-inner" data-ao="fade-up">
        <h2>Get Started <em>Today.</em></h2>
        <p>Submit your request in under 3 minutes — no account needed, no queues, completely free.</p>
        <div class="cta-btns">
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
/* ═══════════════════════════════════════
   SCROLL ANIMATIONS  (IntersectionObserver)
═══════════════════════════════════════ */
(function () {
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('ao-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('[data-ao]').forEach(function (el) {
        observer.observe(el);
    });
})();

/* ═══════════════════════════════════════
   FAQ ACCORDION  (smooth max-height)
═══════════════════════════════════════ */
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
