<?php $__env->startSection('title', 'Resident Portal'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ─────────────────────────────────────────────
   BASE
───────────────────────────────────────────── */
body { background: #fff; }

/* ─────────────────────────────────────────────
   SHARED SECTION UTILITIES
───────────────────────────────────────────── */
.sec {
    padding: clamp(3.5rem, 7vw, 5.5rem) clamp(1rem, 5vw, 2.5rem);
}
.sec-inner     { max-width: 1140px; margin: 0 auto; }
.sec-inner-sm  { max-width: 780px;  margin: 0 auto; }
.sec-inner-md  { max-width: 960px;  margin: 0 auto; }

.sec-tag {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: .68rem; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--gold); margin-bottom: .6rem;
}
.sec-tag::before {
    content: ''; width: 18px; height: 2px;
    background: var(--gold); border-radius: 99px;
}
.sec-h2 {
    font-size: clamp(1.55rem, 3.2vw, 2.15rem);
    font-weight: 800; color: var(--navy);
    letter-spacing: -0.025em; line-height: 1.18;
    margin-bottom: .6rem;
}
.sec-h2 em { font-style: normal; color: var(--gold); }
.sec-p {
    font-size: .95rem; color: #5a6474;
    line-height: 1.78; max-width: 560px;
}

/* ─────────────────────────────────────────────
   HERO
───────────────────────────────────────────── */
.hero {
    background: #fff;
    border-bottom: 1px solid #e8ecf1;
    padding: clamp(3rem, 7vw, 5rem) clamp(1rem, 5vw, 2.5rem);
}
.hero-inner {
    max-width: 1140px; margin: 0 auto;
    display: grid; grid-template-columns: 1fr 420px;
    gap: 3.5rem; align-items: center;
}

/* Left */
.h-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(13,33,68,.06);
    border: 1px solid rgba(13,33,68,.13);
    border-radius: 99px; padding: 5px 14px;
    font-size: .7rem; font-weight: 700; color: var(--navy);
    letter-spacing: .07em; text-transform: uppercase;
    margin-bottom: 1.25rem;
}
.h-badge i { color: var(--gold); font-size: .62rem; }

.h-title {
    font-size: clamp(2.1rem, 4.5vw, 3.1rem);
    font-weight: 800; color: var(--navy);
    line-height: 1.1; letter-spacing: -0.03em;
    margin-bottom: 1rem;
}
.h-title em { font-style: normal; color: var(--gold); }

.h-desc {
    font-size: clamp(.9rem, 1.8vw, 1.02rem);
    color: #5a6474; line-height: 1.82;
    max-width: 460px; margin-bottom: 2rem; font-weight: 400;
}

.h-ctas { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 2.75rem; }

.h-stats {
    display: flex; gap: 2.75rem;
    padding-top: 2rem; border-top: 1px solid #eaecf0;
    flex-wrap: wrap;
}
.h-stat-num {
    font-size: 1.6rem; font-weight: 800; color: var(--navy);
    line-height: 1; letter-spacing: -0.025em;
}
.h-stat-num em { font-style: normal; color: var(--gold); }
.h-stat-lbl {
    font-size: .72rem; color: #9ca3af;
    margin-top: .25rem; font-weight: 500;
}

/* Right: Panel */
.h-panel {
    background: #fff;
    border: 1.5px solid #e8ecf1;
    border-radius: 18px; padding: 1.75rem;
    box-shadow: 0 8px 36px rgba(13,33,68,.08), 0 2px 8px rgba(13,33,68,.04);
}
.h-panel-head {
    display: flex; align-items: center; gap: .65rem;
    margin-bottom: 1.3rem; padding-bottom: 1.1rem;
    border-bottom: 1px solid #f0f2f5;
}
.h-panel-icon {
    width: 40px; height: 40px; border-radius: 10px;
    background: rgba(13,33,68,.07);
    display: flex; align-items: center; justify-content: center;
    color: var(--navy); font-size: .95rem; flex-shrink: 0;
}
.h-panel-head h3 { font-size: .95rem; font-weight: 700; color: var(--navy); }
.h-panel-head p  { font-size: .75rem; color: #9ca3af; margin-top: 1px; }

.h-track-form { display: flex; flex-direction: column; gap: .6rem; margin-bottom: 1.1rem; }
.h-input-wrap { position: relative; }
.h-input-wrap i {
    position: absolute; left: .9rem; top: 50%;
    transform: translateY(-50%);
    color: #b5bec9; font-size: .82rem; pointer-events: none;
}
.h-input-wrap input {
    width: 100%; height: 48px;
    padding: 0 .9rem 0 2.5rem;
    border: 1.5px solid #dde1e8; border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: .88rem; color: var(--text); background: #fafbfc;
    transition: border-color .2s, box-shadow .2s;
}
.h-input-wrap input:focus {
    outline: none; border-color: var(--navy); background: #fff;
    box-shadow: 0 0 0 3px rgba(13,33,68,.08);
}
.h-input-wrap input::placeholder { color: #b5bec9; }
.h-track-btn {
    height: 48px; width: 100%;
    background: var(--navy); color: #fff;
    border: none; border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-size: .9rem; font-weight: 600; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    transition: background .2s, box-shadow .2s;
}
.h-track-btn:hover { background: var(--navy-mid); box-shadow: 0 4px 14px rgba(13,33,68,.25); }

.h-or {
    display: flex; align-items: center; gap: .65rem;
    font-size: .7rem; color: #c0c9d4; font-weight: 600;
    text-transform: uppercase; letter-spacing: .08em; margin: .15rem 0;
}
.h-or::before, .h-or::after { content: ''; flex: 1; height: 1px; background: #eaecf0; }

.h-shortcuts { display: flex; flex-direction: column; gap: .4rem; }
.h-shortcut {
    display: flex; align-items: center; gap: .65rem;
    padding: .65rem .85rem; border: 1px solid #eaecf0;
    border-radius: 10px; text-decoration: none; background: #fafbfc;
    transition: border-color .15s, background .15s;
}
.h-shortcut:hover { border-color: rgba(200,134,26,.4); background: #fffdf7; }
.h-s-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; flex-shrink: 0;
}
.h-s-body { flex: 1; min-width: 0; }
.h-s-body strong { display: block; font-size: .82rem; font-weight: 600; color: var(--navy); }
.h-s-body span   { font-size: .72rem; color: #9ca3af; }
.h-s-arrow { color: #d1d5db; font-size: .7rem; transition: color .15s, transform .15s; }
.h-shortcut:hover .h-s-arrow { color: var(--gold); transform: translateX(2px); }

/* ─────────────────────────────────────────────
   THE PROBLEM
───────────────────────────────────────────── */
.prob-sec { background: #f7f9fc; border-top: 1px solid #eaecf0; border-bottom: 1px solid #eaecf0; }

.prob-cards {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem; margin-top: 2.5rem;
}
.prob-card {
    background: #fff; border: 1.5px solid #eceef3;
    border-radius: 14px; padding: 1.65rem 1.4rem;
    transition: box-shadow .2s;
}
.prob-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.07); }
.prob-icon {
    width: 50px; height: 50px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 1.05rem;
}
.prob-card h3 { font-size: 1rem; font-weight: 700; color: var(--navy); margin-bottom: .45rem; }
.prob-card p  { font-size: .84rem; color: #6b7280; line-height: 1.72; }

/* ─────────────────────────────────────────────
   SOLUTION BANNER
───────────────────────────────────────────── */
.sol-banner {
    background: linear-gradient(110deg, var(--navy-dark) 0%, var(--navy) 55%, #183a70 100%);
    padding: clamp(2.75rem, 5.5vw, 4.5rem) clamp(1rem, 5vw, 2.5rem);
    text-align: center; position: relative; overflow: hidden;
}
.sol-banner::before {
    content: ''; position: absolute; top: -60px; right: -60px;
    width: 340px; height: 340px;
    background: radial-gradient(circle, rgba(200,134,26,.1) 0%, transparent 65%);
    pointer-events: none;
}
.sol-banner::after {
    content: ''; position: absolute; bottom: -50px; left: 6%;
    width: 260px; height: 260px;
    background: radial-gradient(circle, rgba(255,255,255,.03) 0%, transparent 65%);
    pointer-events: none;
}
.sol-inner { position: relative; z-index: 1; max-width: 780px; margin: 0 auto; }
.sol-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(200,134,26,.2); border: 1px solid rgba(200,134,26,.35);
    border-radius: 99px; padding: 5px 16px;
    font-size: .7rem; font-weight: 700; color: var(--gold-light);
    letter-spacing: .09em; text-transform: uppercase; margin-bottom: 1.4rem;
}
.sol-badge i { font-size: .65rem; }
.sol-h2 {
    font-size: clamp(1.5rem, 3.5vw, 2.25rem);
    font-weight: 800; color: #fff; line-height: 1.32;
    letter-spacing: -0.02em; margin-bottom: 1.1rem;
}
.sol-quote {
    font-size: .92rem; color: rgba(255,255,255,.48);
    line-height: 1.8; font-style: italic;
}

/* ─────────────────────────────────────────────
   FEATURES / SERVICES
───────────────────────────────────────────── */
.feat-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 1.15rem; margin-top: 2.75rem;
}
.feat-card {
    background: #fff; border: 1.5px solid #eceef3;
    border-radius: 14px; padding: 1.65rem 1.4rem;
    text-decoration: none; display: flex; flex-direction: column;
    transition: border-color .2s, box-shadow .2s, transform .15s;
    position: relative; overflow: hidden;
}
.feat-card::after {
    content: ''; position: absolute; top: 0; left: 0;
    width: 100%; height: 3px; border-radius: 14px 14px 0 0;
    opacity: 0; transition: opacity .2s;
}
.feat-card:hover {
    border-color: rgba(200,134,26,.3);
    box-shadow: 0 8px 28px rgba(200,134,26,.1);
    transform: translateY(-3px);
}
.feat-card:hover::after { opacity: 1; }
.feat-card.fc-navy::after  { background: var(--navy); }
.feat-card.fc-gold::after  { background: var(--gold); }
.feat-card.fc-green::after { background: #16a34a; }
.feat-card.fc-blue::after  { background: #2563eb; }
.feat-card.fc-red::after   { background: var(--crimson); }
.feat-card.fc-teal::after  { background: #0e7490; }

.feat-icon {
    width: 52px; height: 52px; border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; margin-bottom: 1rem;
}
.fc-navy  .feat-icon { background: rgba(13,33,68,.07);  color: var(--navy); }
.fc-gold  .feat-icon { background: rgba(200,134,26,.1); color: var(--gold); }
.fc-green .feat-icon { background: rgba(22,163,74,.09); color: #16a34a; }
.fc-blue  .feat-icon { background: rgba(37,99,235,.08); color: #2563eb; }
.fc-red   .feat-icon { background: rgba(155,28,28,.07); color: var(--crimson); }
.fc-teal  .feat-icon { background: rgba(14,116,144,.07);color: #0e7490; }

.feat-card h3 { font-size: .95rem; font-weight: 700; color: var(--navy); margin-bottom: .4rem; line-height: 1.3; }
.feat-card p  { font-size: .82rem; color: #6b7280; line-height: 1.67; flex: 1; margin-bottom: .9rem; }
.feat-cta {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .76rem; font-weight: 700; color: var(--navy);
    text-transform: uppercase; letter-spacing: .05em;
    transition: gap .15s, color .15s;
}
.feat-card:hover .feat-cta { gap: 9px; color: var(--gold); }
.feat-cta i { font-size: .6rem; }

/* ─────────────────────────────────────────────
   HOW IT WORKS
───────────────────────────────────────────── */
.how-sec { background: #f7f9fc; border-top: 1px solid #eaecf0; border-bottom: 1px solid #eaecf0; }

.how-steps {
    display: grid; grid-template-columns: repeat(4, 1fr);
    gap: 0; margin-top: 3rem; position: relative;
}
.how-steps::before {
    content: ''; position: absolute;
    top: 30px; left: calc(12.5% + 14px); right: calc(12.5% + 14px);
    height: 1px;
    background: linear-gradient(90deg, rgba(200,134,26,.2), rgba(200,134,26,.55), rgba(200,134,26,.2));
    z-index: 0;
}
.how-step { text-align: center; padding: 0 1.2rem; position: relative; z-index: 1; }
.how-num {
    width: 60px; height: 60px; border-radius: 50%;
    background: var(--navy); color: #fff;
    font-size: 1.1rem; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.2rem;
    border: 3px solid #f7f9fc;
    box-shadow: 0 0 0 3px rgba(200,134,26,.35), 0 4px 18px rgba(13,33,68,.15);
    position: relative; z-index: 1;
}
.how-step h4 { font-size: .92rem; font-weight: 700; color: var(--navy); margin-bottom: .45rem; }
.how-step p  { font-size: .81rem; color: #6b7280; line-height: 1.68; }

/* ─────────────────────────────────────────────
   TRUST & SECURITY
───────────────────────────────────────────── */
.trust-sec { background: #fff; }
.trust-inner {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 5rem; align-items: center;
}
.trust-shield {
    width: 76px; height: 76px; border-radius: 50%;
    background: linear-gradient(135deg, var(--navy), var(--navy-mid));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.85rem; color: #fff; margin-bottom: 1.5rem;
    box-shadow: 0 8px 28px rgba(13,33,68,.22);
}
.trust-left h2 {
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    font-weight: 800; color: var(--navy);
    letter-spacing: -0.02em; margin-bottom: .7rem;
}
.trust-left p { font-size: .92rem; color: #5a6474; line-height: 1.78; margin-bottom: 1.5rem; }
.trust-dpa {
    display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(13,33,68,.05); border: 1px solid rgba(13,33,68,.12);
    border-radius: 99px; padding: 6px 16px;
    font-size: .75rem; font-weight: 700; color: var(--navy);
}
.trust-dpa i { color: var(--gold); font-size: .68rem; }

.trust-boxes {
    display: grid; grid-template-columns: 1fr 1fr; gap: .85rem;
}
.trust-box {
    background: #f7f9fc; border: 1.5px solid #eceef3;
    border-radius: 14px; padding: 1.25rem 1.1rem;
    transition: border-color .15s, box-shadow .15s;
}
.trust-box:hover { border-color: rgba(13,33,68,.2); box-shadow: 0 4px 16px rgba(13,33,68,.07); }
.trust-box-icon {
    width: 38px; height: 38px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: .88rem; margin-bottom: .8rem;
}
.trust-box h4 { font-size: .83rem; font-weight: 700; color: var(--navy); margin-bottom: .3rem; line-height: 1.3; }
.trust-box p  { font-size: .75rem; color: #9ca3af; line-height: 1.58; }

/* ─────────────────────────────────────────────
   FAQ
───────────────────────────────────────────── */
.faq-sec { background: #f7f9fc; border-top: 1px solid #eaecf0; border-bottom: 1px solid #eaecf0; }
.faq-list { display: flex; flex-direction: column; gap: .6rem; margin-top: 2.25rem; }
.faq-item {
    background: #fff; border: 1.5px solid #eceef3;
    border-radius: 12px; overflow: hidden; transition: border-color .15s;
}
.faq-item.open { border-color: #c3cedf; }
.faq-btn {
    width: 100%;
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    padding: 1.05rem 1.25rem; background: none; border: none;
    font-family: 'Poppins', sans-serif;
    font-size: .9rem; font-weight: 600; color: var(--navy);
    cursor: pointer; text-align: left; min-height: 58px;
    transition: background .1s;
}
.faq-btn:hover { background: #fafbfc; }
.faq-icon {
    width: 26px; height: 26px; border-radius: 50%;
    background: #f0f2f5; color: var(--navy); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: .68rem;
    transition: transform .22s, background .15s, color .15s;
}
.faq-item.open .faq-icon { transform: rotate(45deg); background: var(--navy); color: #fff; }
.faq-body {
    display: none; border-top: 1px solid #f0f2f5;
    padding: .85rem 1.25rem 1.15rem;
    font-size: .88rem; color: #5a6474; line-height: 1.78;
}
.faq-item.open .faq-body { display: block; }

/* ─────────────────────────────────────────────
   CTA BAND
───────────────────────────────────────────── */
.cta-band {
    background: linear-gradient(110deg, var(--navy-dark) 0%, var(--navy) 60%, #1b3c72 100%);
    padding: clamp(3rem, 6vw, 5.5rem) clamp(1rem, 5vw, 2.5rem);
    text-align: center; position: relative; overflow: hidden;
}
.cta-band::before {
    content: ''; position: absolute; top: -80px; right: -60px;
    width: 380px; height: 380px;
    background: radial-gradient(circle, rgba(200,134,26,.1) 0%, transparent 65%);
    pointer-events: none;
}
.cta-band::after {
    content: ''; position: absolute; bottom: -60px; left: 8%;
    width: 280px; height: 280px;
    background: radial-gradient(circle, rgba(255,255,255,.03) 0%, transparent 65%);
    pointer-events: none;
}
.cta-band-inner { position: relative; z-index: 1; max-width: 620px; margin: 0 auto; }
.cta-band h2 {
    font-size: clamp(1.6rem, 3.5vw, 2.35rem);
    font-weight: 800; color: #fff;
    margin-bottom: .75rem; letter-spacing: -0.025em;
}
.cta-band p { font-size: .95rem; color: rgba(255,255,255,.52); line-height: 1.82; margin-bottom: 2.25rem; }
.cta-band-btns { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }

/* ─────────────────────────────────────────────
   RESPONSIVE
───────────────────────────────────────────── */
@media (max-width: 960px) {
    .hero-inner   { grid-template-columns: 1fr; gap: 2.5rem; }
    .h-panel      { max-width: 480px; }
    .trust-inner  { grid-template-columns: 1fr; gap: 2.75rem; }
}
@media (max-width: 740px) {
    .feat-grid    { grid-template-columns: 1fr 1fr; }
    .prob-cards   { grid-template-columns: 1fr; }
    .trust-boxes  { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 540px) {
    .feat-grid    { grid-template-columns: 1fr; }
    .trust-boxes  { grid-template-columns: 1fr; }
    .h-ctas       { flex-direction: column; }
    .h-ctas .btn  { width: 100%; justify-content: center; }
    .cta-band-btns            { flex-direction: column; align-items: stretch; }
    .cta-band-btns .btn       { width: 100%; justify-content: center; }
}
@media (max-width: 640px) {
    .how-steps { grid-template-columns: 1fr; gap: 0; }
    .how-steps::before { display: none; }
    .how-step {
        display: flex; align-items: flex-start;
        gap: 1.1rem; text-align: left; padding: 0 0 2.25rem; position: relative;
    }
    .how-step:last-child { padding-bottom: 0; }
    .how-step:not(:last-child)::after {
        content: ''; position: absolute;
        left: 29px; top: 60px; bottom: 0;
        width: 1px; background: rgba(200,134,26,.28);
    }
    .how-num { flex-shrink: 0; margin: 0; }
    .how-step-body { flex: 1; padding-top: 14px; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="hero" aria-label="Resident Portal Home">
    <div class="hero-inner">

        
        <div>
            <div class="h-badge">
                <i class="fas fa-shield-halved"></i>
                Official Barangay Digital Portal
            </div>
            <h1 class="h-title">
                Your Barangay<br>
                Services, <em>Online.</em>
            </h1>
            <p class="h-desc">
                Access official barangay documents and services anytime, anywhere — no more
                unnecessary trips to the barangay hall for simple requests.
            </p>
            <div class="h-ctas">
                <a href="<?php echo e(route('portal.request')); ?>" class="btn btn-gold btn-lg">
                    <i class="fas fa-file-plus"></i> Request a Document
                </a>
                <a href="<?php echo e(route('portal.track')); ?>" class="btn btn-outline btn-lg">
                    <i class="fas fa-search"></i> Track My Request
                </a>
            </div>
            <div class="h-stats">
                <div>
                    <div class="h-stat-num"><em>4</em></div>
                    <div class="h-stat-lbl">Document Types</div>
                </div>
                <div>
                    <div class="h-stat-num">1<span style="font-size:.85rem;color:#c9d0d9;font-weight:500">–</span>3</div>
                    <div class="h-stat-lbl">Business Days</div>
                </div>
                <div>
                    <div class="h-stat-num"><em>100%</em></div>
                    <div class="h-stat-lbl">Free Service</div>
                </div>
                <div>
                    <div class="h-stat-num">24/7</div>
                    <div class="h-stat-lbl">Online Access</div>
                </div>
            </div>
        </div>

        
        <div class="h-panel">
            <div class="h-panel-head">
                <div class="h-panel-icon"><i class="fas fa-magnifying-glass"></i></div>
                <div>
                    <h3>Track Your Request</h3>
                    <p>Enter your appointment number</p>
                </div>
            </div>

            <form class="h-track-form" action="<?php echo e(route('portal.track.post')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="h-input-wrap">
                    <i class="fas fa-hashtag"></i>
                    <input type="text"
                           name="appointment_number"
                           placeholder="e.g. APT-20260520-AB12"
                           autocomplete="off"
                           spellcheck="false"
                           aria-label="Appointment number">
                </div>
                <button type="submit" class="h-track-btn">
                    <i class="fas fa-search"></i> Check Status
                </button>
            </form>

            <div class="h-or">or start a new request</div>

            <div class="h-shortcuts">
                <a href="<?php echo e(route('portal.request')); ?>" class="h-shortcut">
                    <div class="h-s-icon" style="background:rgba(13,33,68,.07);color:var(--navy)">
                        <i class="fas fa-file-plus"></i>
                    </div>
                    <div class="h-s-body">
                        <strong>Request a Document</strong>
                        <span>Clearance, Indigency, Residency</span>
                    </div>
                    <i class="fas fa-chevron-right h-s-arrow"></i>
                </a>
                <a href="<?php echo e(route('portal.blotter')); ?>" class="h-shortcut">
                    <div class="h-s-icon" style="background:rgba(155,28,28,.07);color:var(--crimson)">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <div class="h-s-body">
                        <strong>File a Blotter Report</strong>
                        <span>Report an incident to the barangay</span>
                    </div>
                    <i class="fas fa-chevron-right h-s-arrow"></i>
                </a>
                <a href="<?php echo e(route('portal.business')); ?>" class="h-shortcut">
                    <div class="h-s-icon" style="background:rgba(14,116,144,.07);color:#0e7490">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="h-s-body">
                        <strong>Business Permit</strong>
                        <span>New application or renewal</span>
                    </div>
                    <i class="fas fa-chevron-right h-s-arrow"></i>
                </a>
            </div>
        </div>

    </div>
</section>


<section class="sec prob-sec" id="the-problem">
    <div class="sec-inner">
        <div class="sec-tag">The Problem</div>
        <h2 class="sec-h2">Barangay Services, <em>Simplified.</em></h2>
        <p class="sec-p">Residents deserve better access to barangay services. No more unnecessary trips, lost paperwork, or confusing processes.</p>

        <div class="prob-cards">
            <div class="prob-card">
                <div class="prob-icon" style="background:rgba(239,68,68,.08);color:#ef4444">
                    <i class="fas fa-users-clock"></i>
                </div>
                <h3>Long Queues</h3>
                <p>Hours spent waiting at the barangay hall for a document that takes only minutes to prepare and sign.</p>
            </div>
            <div class="prob-card">
                <div class="prob-icon" style="background:rgba(234,88,12,.08);color:#ea580c">
                    <i class="fas fa-route"></i>
                </div>
                <h3>Multiple Trips Required</h3>
                <p>Residents must make repeated visits — once to request, again to follow up, and yet again to claim their document.</p>
            </div>
            <div class="prob-card">
                <div class="prob-icon" style="background:rgba(202,138,4,.08);color:#ca8a04">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                <h3>No Real-time Updates</h3>
                <p>No way to check document status without visiting in person, leaving residents uncertain about their request.</p>
            </div>
        </div>
    </div>
</section>


<section class="sol-banner">
    <div class="sol-inner">
        <div class="sol-badge">
            <i class="fas fa-circle-check"></i> The Solution
        </div>
        <h2 class="sol-h2">
            Barangay New Era Portal brings all barangay services into one secure, easy-to-use platform.
        </h2>
        <p class="sol-quote">"No more waiting in line. Your barangay is in your hands."</p>
    </div>
</section>


<section class="sec" id="services">
    <div class="sec-inner">
        <div class="sec-tag">Features</div>
        <h2 class="sec-h2">Everything You Need, <em>In One Place.</em></h2>
        <p class="sec-p">All barangay document services available digitally — secure, free, and accessible to every resident of Barangay New Era.</p>

        <div class="feat-grid">

            <a href="<?php echo e(route('portal.request')); ?>?type=Barangay+Clearance" class="feat-card fc-navy">
                <div class="feat-icon"><i class="fas fa-file-shield"></i></div>
                <h3>Barangay Clearance</h3>
                <p>Certificate of good standing for employment applications, business permits, loans, and other official requirements.</p>
                <div class="feat-cta">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('portal.request')); ?>?type=Certificate+of+Indigency" class="feat-card fc-gold">
                <div class="feat-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3>Certificate of Indigency</h3>
                <p>For residents who need to avail of government assistance programs, PhilHealth, or medical financial aid.</p>
                <div class="feat-cta">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('portal.request')); ?>?type=Certificate+of+Residency" class="feat-card fc-green">
                <div class="feat-icon"><i class="fas fa-house-circle-check"></i></div>
                <h3>Certificate of Residency</h3>
                <p>Proof of residence for school enrollment, government transactions, and other official document requirements.</p>
                <div class="feat-cta">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('portal.request')); ?>?type=Business+Clearance" class="feat-card fc-blue">
                <div class="feat-icon"><i class="fas fa-building"></i></div>
                <h3>Business Clearance</h3>
                <p>Required for new business registration and the annual renewal of business permits operating within the barangay.</p>
                <div class="feat-cta">Request Now <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('portal.blotter')); ?>" class="feat-card fc-red">
                <div class="feat-icon"><i class="fas fa-gavel"></i></div>
                <h3>File a Blotter Report</h3>
                <p>Report an incident to the barangay online. Our Peace &amp; Order committee will follow up and facilitate mediation.</p>
                <div class="feat-cta">File Report <i class="fas fa-arrow-right"></i></div>
            </a>

            <a href="<?php echo e(route('portal.business')); ?>" class="feat-card fc-teal">
                <div class="feat-icon"><i class="fas fa-file-contract"></i></div>
                <h3>Business Permit Application</h3>
                <p>Apply for a new business permit or annual renewal online. A barangay inspector will review and process your application.</p>
                <div class="feat-cta">Apply Now <i class="fas fa-arrow-right"></i></div>
            </a>

        </div>
    </div>
</section>


<section class="sec how-sec" id="how-it-works">
    <div class="sec-inner">
        <div class="sec-tag">How It Works</div>
        <h2 class="sec-h2">Get Started in <em>4 Easy Steps.</em></h2>
        <p class="sec-p">From request to release — the entire process is designed to be as simple as possible for every resident.</p>

        <div class="how-steps">
            <div class="how-step">
                <div class="how-num">1</div>
                <div class="how-step-body">
                    <h4>Fill the Form</h4>
                    <p>Provide your personal details and choose the barangay document you need. No account required.</p>
                </div>
            </div>
            <div class="how-step">
                <div class="how-num">2</div>
                <div class="how-step-body">
                    <h4>Get Your Number</h4>
                    <p>You'll receive a unique appointment number immediately after submitting your request online.</p>
                </div>
            </div>
            <div class="how-step">
                <div class="how-num">3</div>
                <div class="how-step-body">
                    <h4>Wait for Confirmation</h4>
                    <p>Barangay staff will review and confirm your preferred schedule within 1–2 business days.</p>
                </div>
            </div>
            <div class="how-step">
                <div class="how-num">4</div>
                <div class="how-step-body">
                    <h4>Claim Your Document</h4>
                    <p>Visit the Barangay Hall on your confirmed date with a valid ID to claim your document for free.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="sec trust-sec" id="privacy-security">
    <div class="sec-inner">
        <div class="trust-inner">

            
            <div class="trust-left">
                <div class="trust-shield" aria-hidden="true">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h2>Your Data is Safe With Us.</h2>
                <p>
                    We are committed to protecting the privacy and security of every resident's information.
                    Your data is used exclusively for processing your barangay document requests and is
                    managed only by official Barangay New Era staff.
                </p>
                <div class="trust-dpa">
                    <i class="fas fa-certificate"></i>
                    Data Privacy Act of 2012 Compliant
                </div>
            </div>

            
            <div class="trust-boxes">
                <div class="trust-box">
                    <div class="trust-box-icon" style="background:rgba(37,99,235,.08);color:#2563eb">
                        <i class="fas fa-landmark-flag"></i>
                    </div>
                    <h4>Government-Administered Platform</h4>
                    <p>Officially operated by Barangay New Era — a unit of the Philippine local government.</p>
                </div>
                <div class="trust-box">
                    <div class="trust-box-icon" style="background:rgba(22,163,74,.08);color:#16a34a">
                        <i class="fas fa-file-shield"></i>
                    </div>
                    <h4>Data Privacy Compliance</h4>
                    <p>All data handled under Republic Act 10173 — the Data Privacy Act of the Philippines.</p>
                </div>
                <div class="trust-box">
                    <div class="trust-box-icon" style="background:rgba(200,134,26,.09);color:var(--gold)">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h4>Encrypted Transactions</h4>
                    <p>All form submissions are protected with end-to-end HTTPS encryption and CSRF protection.</p>
                </div>
                <div class="trust-box">
                    <div class="trust-box-icon" style="background:rgba(13,33,68,.07);color:var(--navy)">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4>Staff-Only Data Access</h4>
                    <p>Only verified and authorized barangay personnel can view or process your submitted information.</p>
                </div>
            </div>

        </div>
    </div>
</section>


<section class="sec faq-sec" id="faq">
    <div class="sec-inner-md">
        <div style="text-align:center;margin-bottom:2.5rem">
            <div class="sec-tag" style="justify-content:center">FAQ</div>
            <h2 class="sec-h2" style="text-align:center">Frequently Asked <em>Questions.</em></h2>
            <p class="sec-p" style="margin:0 auto;text-align:center">Everything you need to know about the Barangay New Era Resident Portal.</p>
        </div>

        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>How long does document processing take?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Processing typically takes <strong>1–3 business days</strong> from the date your request is confirmed by barangay staff. Barangay Clearance is usually ready within 1 business day. You will need to visit the hall on your confirmed schedule to personally claim your document.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Is the Barangay New Era Portal free to use?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Yes. The portal is a <strong>free public service</strong> provided by Barangay New Era to all residents. There are no fees for requesting, processing, or claiming any document through this portal.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What valid IDs are accepted when claiming my document?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Any government-issued photo ID is accepted: <strong>PhilSys National ID, Passport, Driver's License, SSS/GSIS/Pag-IBIG ID, Voter's ID, or PhilHealth ID</strong>. The name on your ID must match the name provided in your request.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>Can someone else claim my document on my behalf?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    A representative may claim on your behalf, but they must present a <strong>Special Power of Attorney (SPA)</strong>, their own valid ID, and a photocopy of the requesting resident's valid ID.
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-btn" onclick="toggleFaq(this)" aria-expanded="false">
                    <span>What if my appointment number is not found?</span>
                    <div class="faq-icon"><i class="fas fa-plus"></i></div>
                </button>
                <div class="faq-body">
                    Double-check that you entered the appointment number exactly as shown on your confirmation page (e.g., <code style="background:#f0f2f5;padding:2px 7px;border-radius:5px;font-size:.85em">APT-20260507-AB12</code>). If the issue persists, please visit the Barangay Hall directly during office hours: Monday–Friday, 8:00 AM – 5:00 PM.
                </div>
            </div>
        </div>
    </div>
</section>


<section class="cta-band">
    <div class="cta-band-inner">
        <h2>Start Using the Portal Today.</h2>
        <p>
            Used by residents of Barangay New Era. Submit your request online in under 3 minutes —
            no account needed, no queues, and your barangay is now always within reach.
        </p>
        <div class="cta-band-btns">
            <a href="<?php echo e(route('portal.request')); ?>" class="btn btn-gold btn-lg">
                <i class="fas fa-file-plus"></i> Request a Document
            </a>
            <a href="<?php echo e(route('portal.track')); ?>" class="btn btn-outline-white btn-lg">
                <i class="fas fa-search"></i> Track Existing Request
            </a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/portal/index.blade.php ENDPATH**/ ?>