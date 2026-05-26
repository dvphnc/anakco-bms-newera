<?php $__env->startSection('title', 'About Us'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ══════════════════════════════════════════════════════════
   ABOUT HERO
══════════════════════════════════════════════════════════ */
.about-hero {
    background: linear-gradient(135deg, var(--navy) 0%, #1a3a6e 55%, #0a2855 100%);
    position: relative;
    overflow: hidden;
    padding: clamp(3rem, 7vw, 5rem) clamp(1rem, 5vw, 2.5rem);
}
.about-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -60px;
    width: 460px; height: 460px;
    background: radial-gradient(circle, rgba(200,134,26,.10) 0%, transparent 65%);
    pointer-events: none;
}
.about-hero-inner {
    position: relative; z-index: 1;
    max-width: 860px; margin: 0 auto; text-align: center;
}
.about-hero-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(200,134,26,.15); border: 1px solid rgba(200,134,26,.35);
    border-radius: 99px; padding: 5px 16px;
    font-size: 11.5px; font-weight: 700; color: var(--gold-light);
    letter-spacing: .08em; text-transform: uppercase; margin-bottom: 20px;
}
.about-hero h1 {
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 800; color: #fff;
    line-height: 1.15; letter-spacing: -.02em; margin-bottom: 18px;
}
.about-hero h1 span { color: var(--gold-light); }
.about-hero p {
    font-size: clamp(15px, 2vw, 17px); color: rgba(255,255,255,.72);
    line-height: 1.75; max-width: 600px; margin: 0 auto 2rem;
}
.about-hero-wave {
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 54px; overflow: hidden; line-height: 0;
}
.about-hero-wave svg { display: block; width: 100%; height: 54px; }

/* Stat pills row */
.hero-pill-row {
    display: flex; justify-content: center; gap: 1rem;
    flex-wrap: wrap; margin-top: .5rem;
}
.hero-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.15);
    border-radius: 99px; padding: 8px 18px;
    font-size: 13px; font-weight: 600; color: rgba(255,255,255,.85);
}
.hero-pill i { color: var(--gold-light); font-size: 12px; }

/* ══════════════════════════════════════════════════════════
   SHARED SECTION
══════════════════════════════════════════════════════════ */
.about-section {
    padding: clamp(3rem, 6vw, 4.5rem) clamp(1rem, 5vw, 2.5rem);
}
.about-inner { max-width: 1000px; margin: 0 auto; }

.section-chip {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .14em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 10px;
}
.section-chip::before {
    content: ''; width: 20px; height: 2px;
    background: var(--gold); border-radius: 99px;
}
.section-h2 {
    font-size: clamp(1.5rem, 3.5vw, 2.25rem);
    font-weight: 800; color: var(--navy);
    line-height: 1.2; letter-spacing: -.02em; margin-bottom: 10px;
}
.section-lead {
    font-size: clamp(14px, 1.8vw, 16px);
    color: var(--muted); line-height: 1.75; max-width: 660px; margin-bottom: 0;
}

/* ══════════════════════════════════════════════════════════
   OVERVIEW GRID
══════════════════════════════════════════════════════════ */
.overview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem; margin-top: 2.5rem;
    align-items: start;
}
@media (max-width: 760px) { .overview-grid { grid-template-columns: 1fr; } }

.overview-body p {
    font-size: 15px; color: #374151; line-height: 1.8;
    margin-bottom: 1rem;
}
.overview-body p:last-child { margin-bottom: 0; }

.info-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.info-card-header {
    background: var(--navy);
    padding: 14px 20px;
    font-size: 13px; font-weight: 700;
    color: rgba(255,255,255,.9); letter-spacing: .02em;
    display: flex; align-items: center; gap: 8px;
}
.info-card-header i { color: var(--gold-light); }
.info-row {
    display: flex; gap: 12px; padding: 13px 20px;
    border-bottom: 1px solid var(--border);
    font-size: 14px; align-items: flex-start;
}
.info-row:last-child { border-bottom: none; }
.info-row-label {
    min-width: 130px; font-weight: 600; color: var(--navy);
    flex-shrink: 0;
}
.info-row-val { color: #374151; flex: 1; }

/* ══════════════════════════════════════════════════════════
   MISSION / VISION
══════════════════════════════════════════════════════════ */
.mv-section { background: #f0f4ff; border-top: 1px solid #dce8f8; border-bottom: 1px solid #dce8f8; }
.mv-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 2rem; margin-top: 2.5rem;
}
@media (max-width: 680px) { .mv-grid { grid-template-columns: 1fr; } }
.mv-card {
    background: #fff; border-radius: var(--radius-lg);
    padding: 2rem 1.75rem;
    border: 1.5px solid #dce8f8;
    box-shadow: var(--shadow-sm);
}
.mv-card-icon {
    width: 56px; height: 56px; border-radius: var(--radius);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; margin-bottom: 1.1rem;
}
.mv-card h3 {
    font-size: 18px; font-weight: 800; color: var(--navy);
    margin-bottom: 12px; letter-spacing: -.01em;
}
.mv-card p { font-size: 15px; color: #374151; line-height: 1.8; }

/* ══════════════════════════════════════════════════════════
   OFFICIALS
══════════════════════════════════════════════════════════ */
.officials-section { background: #fff; }

/* Punong row — full-width hero card */
.punong-card {
    background: linear-gradient(135deg, var(--navy) 0%, #1e3f73 100%);
    border-radius: var(--radius-lg);
    padding: 2rem 2.25rem;
    display: flex; align-items: center; gap: 2rem;
    margin-bottom: 2rem; color: #fff;
    box-shadow: 0 8px 32px rgba(13,33,68,.22);
}
@media (max-width: 600px) { .punong-card { flex-direction: column; text-align: center; } }
.punong-avatar {
    width: 90px; height: 90px; flex-shrink: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), var(--gold-light));
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800; color: var(--navy);
    border: 4px solid rgba(255,255,255,.2);
    box-shadow: 0 0 0 4px rgba(200,134,26,.3);
}
.punong-info small {
    display: block; font-size: 11px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
    color: var(--gold-light); margin-bottom: 6px;
}
.punong-info h3 {
    font-size: clamp(1.25rem, 3vw, 1.75rem);
    font-weight: 800; letter-spacing: -.01em; margin-bottom: 6px;
}
.punong-info p { font-size: 13px; color: rgba(255,255,255,.65); }
.punong-badge {
    margin-left: auto; flex-shrink: 0;
    background: rgba(200,134,26,.2); border: 1.5px solid rgba(200,134,26,.4);
    border-radius: 10px; padding: 10px 18px; text-align: center;
}
@media (max-width: 600px) { .punong-badge { margin-left: 0; } }
.punong-badge-num { font-size: 22px; font-weight: 800; color: var(--gold-light); }
.punong-badge-lbl { font-size: 11px; color: rgba(255,255,255,.55); }

/* Officers grid (Secretary, Treasurer) */
.officers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem; margin-bottom: 2rem;
}
.officer-card {
    background: #f8faff; border: 1.5px solid #dce8f8;
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    display: flex; align-items: center; gap: 1rem;
}
.officer-avatar {
    width: 52px; height: 52px; flex-shrink: 0;
    border-radius: 50%; font-size: 18px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
}
.officer-info small {
    font-size: 11px; font-weight: 600; letter-spacing: .06em;
    text-transform: uppercase; color: var(--muted); display: block;
}
.officer-info strong { font-size: 14px; font-weight: 700; color: var(--navy); display: block; margin-top: 2px; }

/* Kagawads grid */
.section-sub-h {
    font-size: 12px; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: var(--muted);
    margin-bottom: 1.25rem; padding-bottom: 10px;
    border-bottom: 2px solid var(--border);
}
.kagawads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}
.kag-card {
    background: var(--surface);
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 1.1rem 1.25rem;
    transition: border-color .2s, box-shadow .2s, transform .15s;
}
.kag-card:hover {
    border-color: var(--gold-border);
    box-shadow: 0 4px 16px rgba(200,134,26,.1);
    transform: translateY(-2px);
}
.kag-header {
    display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.kag-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--navy-pale); color: var(--navy);
    font-size: 14px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.kag-name { font-size: 13px; font-weight: 700; color: var(--navy); line-height: 1.35; }
.kag-position { font-size: 11px; color: var(--muted); }
.kag-committee {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(13,33,68,.06); border-radius: 99px;
    padding: 4px 10px; font-size: 11px; font-weight: 600;
    color: var(--navy);
}
.kag-committee i { font-size: 10px; color: var(--gold); }

/* SK card */
.sk-row { margin-bottom: 2rem; }
.sk-card {
    background: linear-gradient(135deg, #7c3aed14, #a855f714);
    border: 1.5px solid #c4b5fd55;
    border-radius: var(--radius); padding: 1.1rem 1.5rem;
    display: flex; align-items: center; gap: 1rem;
}
.sk-avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #fff; font-size: 18px; font-weight: 800;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.sk-info small { font-size: 11px; font-weight: 600; letter-spacing: .06em;
    text-transform: uppercase; color: #7c3aed; display: block; }
.sk-info strong { font-size: 14px; font-weight: 700; color: var(--navy); }
.sk-info span { font-size: 12px; color: var(--muted); }

/* ══════════════════════════════════════════════════════════
   CONTACT SECTION
══════════════════════════════════════════════════════════ */
.contact-section { background: var(--navy); }
.contact-section .section-chip { color: var(--gold-light); }
.contact-section .section-chip::before { background: var(--gold-light); }
.contact-section .section-h2 { color: #fff; }
.contact-section .section-lead { color: rgba(255,255,255,.60); }

.contact-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem; margin-top: 2.5rem;
}
.contact-item {
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
    border-radius: var(--radius); padding: 1.5rem;
    transition: background .2s;
}
.contact-item:hover { background: rgba(255,255,255,.10); }
.contact-item-icon {
    width: 48px; height: 48px; border-radius: var(--radius-sm);
    background: rgba(200,134,26,.18); border: 1px solid rgba(200,134,26,.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; color: var(--gold-light); margin-bottom: 1rem;
}
.contact-item h4 { font-size: 13px; font-weight: 700; color: rgba(255,255,255,.5);
    text-transform: uppercase; letter-spacing: .08em; margin-bottom: 6px; }
.contact-item p { font-size: 15px; font-weight: 600; color: #fff; line-height: 1.5; }
.contact-item small { font-size: 12px; color: rgba(255,255,255,.45); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="about-hero">
    <div class="about-hero-inner">
        <div class="about-hero-eyebrow">
            <i class="fas fa-landmark"></i>
            About Barangay New Era
        </div>
        <h1>Serving Our Community<br><span>Since Day One.</span></h1>
        <p>
            Barangay New Era is a proud urban barangay of Quezon City, District VI, dedicated to
            delivering quality governance, public safety, health, and social services to every resident.
        </p>
        <div class="hero-pill-row">
            <span class="hero-pill"><i class="fas fa-users"></i> 14,987 Residents</span>
            <span class="hero-pill"><i class="fas fa-map-pin"></i> District VI, Quezon City</span>
            <span class="hero-pill"><i class="fas fa-envelope"></i> Postal Code 1107</span>
            <span class="hero-pill"><i class="fas fa-calendar-check"></i> Term 2023–2026</span>
        </div>
    </div>
    <div class="about-hero-wave">
        <svg viewBox="0 0 1440 54" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,54 L0,27 Q360,0 720,27 Q1080,54 1440,27 L1440,54 Z" fill="#F0F4F8"/>
        </svg>
    </div>
</section>


<section class="about-section">
    <div class="about-inner">
        <div class="section-chip">Who We Are</div>
        <h2 class="section-h2">A Community Built on Service &amp; Integrity.</h2>
        <p class="section-lead">We are committed to transparent, accountable, and people-centered local governance.</p>

        <div class="overview-grid">
            <div class="overview-body">
                <p>
                    <strong>Barangay New Era</strong> is an urban barangay located in <strong>District VI of Quezon City</strong>,
                    National Capital Region (NCR). It carries the postal code <strong>1107</strong> and is home to
                    approximately <strong>14,987 residents</strong> as of the 2024 census — making it the
                    52<sup>nd</sup> most populous barangay among the 142 barangays of Quezon City.
                </p>
                <p>
                    As an officially classified urban barangay, New Era is characterized by a higher population
                    density and a built-up community environment. The barangay hall serves as the seat of local
                    governance and is accessible to all residents for public services, document requests,
                    blotter filings, and community concerns.
                </p>
                <p>
                    The barangay is led by <strong>Punong Barangay Robert S. Romano</strong> and a full
                    Sangguniang Barangay composed of seven (7) kagawads for the <strong>2023–2026 term</strong>
                    under Republic Act 12232.
                </p>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i> Barangay Quick Facts
                </div>
                <div class="info-row">
                    <span class="info-row-label">Barangay</span>
                    <span class="info-row-val">New Era</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">City</span>
                    <span class="info-row-val">Quezon City</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">District</span>
                    <span class="info-row-val">District VI</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">Region</span>
                    <span class="info-row-val">NCR (National Capital Region)</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">Postal Code</span>
                    <span class="info-row-val">1107</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">Classification</span>
                    <span class="info-row-val">Urban</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">Population</span>
                    <span class="info-row-val">14,987 <small style="color:var(--muted)">(2024 census)</small></span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">Hall Contact</span>
                    <span class="info-row-val">(02) 5186818</span>
                </div>
                <div class="info-row">
                    <span class="info-row-label">Current Term</span>
                    <span class="info-row-val">2023 – 2026</span>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="about-section mv-section">
    <div class="about-inner">
        <div class="section-chip">Our Direction</div>
        <h2 class="section-h2">Mission &amp; Vision</h2>

        <div class="mv-grid">
            <div class="mv-card">
                <div class="mv-card-icon" style="background:rgba(13,33,68,.08);color:var(--navy)">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>
                    To deliver efficient, transparent, and people-centered barangay services that uphold
                    the dignity and rights of every resident of Barangay New Era — ensuring accessible
                    governance, public safety, health, livelihood support, and community welfare for all.
                </p>
            </div>
            <div class="mv-card">
                <div class="mv-card-icon" style="background:rgba(200,134,26,.10);color:var(--gold)">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>
                    A progressive, united, and resilient Barangay New Era — where every resident lives
                    in a safe, healthy, and prosperous community guided by principled leadership,
                    sustainable development, and genuine public service.
                </p>
            </div>
        </div>
    </div>
</section>


<section class="about-section officials-section">
    <div class="about-inner">
        <div class="section-chip">Leadership</div>
        <h2 class="section-h2">Our Elected Officials</h2>
        <p class="section-lead" style="margin-bottom:2.5rem">
            Barangay New Era is led by the following officials for the <strong>2023–2026 term</strong> under Republic Act 12232.
        </p>

        
        <div class="punong-card">
            <div class="punong-avatar">R</div>
            <div class="punong-info">
                <small>Punong Barangay</small>
                <h3>Robert Silva Romano</h3>
                <p><i class="fas fa-phone" style="margin-right:5px"></i>(02) 5186818 &nbsp;|&nbsp; <i class="fas fa-map-pin" style="margin-right:5px"></i>Barangay Hall, New Era, QC</p>
            </div>
            <div class="punong-badge">
                <div class="punong-badge-num">2023</div>
                <div class="punong-badge-lbl">Term Started</div>
            </div>
        </div>

        
        <div class="section-sub-h">Administrative Officers</div>
        <div class="officers-grid" style="margin-bottom:2rem">
            <div class="officer-card">
                <div class="officer-avatar" style="background:#dbeafe;color:#1d4ed8">J</div>
                <div class="officer-info">
                    <small>Barangay Secretary</small>
                    <strong>Josephine Anthony Flores</strong>
                </div>
            </div>
            <div class="officer-card">
                <div class="officer-avatar" style="background:#dcfce7;color:#16a34a">N</div>
                <div class="officer-info">
                    <small>Barangay Treasurer</small>
                    <strong>Nessie Dela Cruz Tayag</strong>
                </div>
            </div>
        </div>

        
        <div class="section-sub-h">SK Chairperson</div>
        <div class="sk-row">
            <div class="sk-card">
                <div class="sk-avatar">A</div>
                <div class="sk-info">
                    <small>SK Chairperson</small>
                    <strong>Ariel Caballero Madriaga</strong><br>
                    <span>Committee: BDRRM</span>
                </div>
            </div>
        </div>

        
        <div class="section-sub-h">Sangguniang Barangay Members (Kagawads)</div>
        <div class="kagawads-grid">
            <?php
            $kagawads = [
                ['name' => 'Salvador Lapid Enriquez',     'committee' => 'Peace & Order',     'icon' => 'fa-shield-halved'],
                ['name' => 'Euler Astete Moreno',          'committee' => 'Environment',        'icon' => 'fa-leaf'],
                ['name' => 'Joel Antonio Tamayo',          'committee' => 'Livelihood',         'icon' => 'fa-briefcase'],
                ['name' => 'Freddie Cayabyab Marcial',     'committee' => 'Infrastructure',     'icon' => 'fa-road'],
                ['name' => 'Twinkle Besas Pineda-Corpuz',  'committee' => 'Health',             'icon' => 'fa-heartbeat'],
                ['name' => 'Alfredo Layco Sicat',          'committee' => 'Transport & Comm.',  'icon' => 'fa-bus'],
                ['name' => 'Medel Reyes Sulpico',          'committee' => 'Education',          'icon' => 'fa-graduation-cap'],
            ];
            ?>

            <?php $__currentLoopData = $kagawads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="kag-card">
                <div class="kag-header">
                    <div class="kag-avatar"><?php echo e(strtoupper(substr($kag['name'], 0, 1))); ?></div>
                    <div>
                        <div class="kag-name"><?php echo e($kag['name']); ?></div>
                        <div class="kag-position">Kagawad</div>
                    </div>
                </div>
                <span class="kag-committee">
                    <i class="fas <?php echo e($kag['icon']); ?>"></i>
                    <?php echo e($kag['committee']); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="about-section contact-section">
    <div class="about-inner">
        <div class="section-chip">Get in Touch</div>
        <h2 class="section-h2">Visit or Contact Us.</h2>
        <p class="section-lead">Our barangay hall is open during regular government office hours. You may also use the portal for online requests.</p>

        <div class="contact-grid">
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-map-pin"></i></div>
                <h4>Address</h4>
                <p>Barangay New Era</p>
                <small>District VI, Quezon City, NCR 1107</small>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                <h4>Telephone</h4>
                <p>(02) 5186818</p>
                <small>Barangay Hall direct line</small>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
                <h4>Office Hours</h4>
                <p>Mon – Fri, 8:00 AM – 5:00 PM</p>
                <small>Closed on national holidays</small>
            </div>
            <div class="contact-item">
                <div class="contact-item-icon"><i class="fas fa-laptop"></i></div>
                <h4>Online Services</h4>
                <p>Available 24 / 7</p>
                <small>Request documents anytime via this portal</small>
            </div>
        </div>
    </div>
</section>


<section style="background:#F0F4F8;padding:clamp(2rem,5vw,3.5rem) clamp(1rem,5vw,2.5rem)">
    <div style="max-width:680px;margin:0 auto;text-align:center">
        <div class="section-chip" style="justify-content:center">Ready to Get Started?</div>
        <h2 style="font-size:clamp(1.4rem,3.5vw,2rem);font-weight:800;color:var(--navy);margin:10px 0 12px;letter-spacing:-.01em">
            Use Our Online Portal for Faster Service.
        </h2>
        <p style="font-size:15px;color:var(--muted);line-height:1.7;margin-bottom:1.75rem">
            Request documents, file a blotter report, or apply for a business permit — all without leaving your home.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
            <a href="<?php echo e(route('portal.request')); ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-file-arrow-up"></i> Request a Document
            </a>
            <a href="<?php echo e(route('portal.index')); ?>" class="btn btn-outline btn-lg">
                <i class="fas fa-home"></i> Back to Portal
            </a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\portal\about.blade.php ENDPATH**/ ?>