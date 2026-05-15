<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Resident Portal') — Barangay New Era</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        /* ══════════════════════════════════════════════════════
           DESIGN TOKENS
        ══════════════════════════════════════════════════════ */
        :root {
            --navy:           #0D2144;
            --navy-dark:      #091830;
            --navy-mid:       #162e5a;
            --navy-light:     #1e3f73;
            --navy-pale:      #e8edf5;
            --navy-border:    #b8c6de;
            --gold:           #C8861A;
            --gold-dark:      #a86d10;
            --gold-pale:      #fdf3e3;
            --gold-border:    #e8c47a;
            --crimson:        #9B1C1C;
            --crimson-pale:   #fdeaea;
            --crimson-border: #e8a0a0;
            --surface:        #ffffff;
            --bg:             #F4F6FA;
            --text:           #1a2332;
            --muted:          #6b7280;
            --border:         #e2e6ea;
            --radius:         12px;
            --radius-sm:      8px;
            --radius-lg:      16px;
            --radius-xl:      24px;
            --shadow-sm:      0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:      0 4px 16px rgba(0,0,0,.10), 0 2px 4px rgba(0,0,0,.06);
            --shadow-lg:      0 10px 40px rgba(0,0,0,.14), 0 4px 10px rgba(0,0,0,.08);
        }

        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; font-size: 16px; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            line-height: 1.6;
        }

        /* ══════════════════════════════════════════════════════
           STICKY HEADER
        ══════════════════════════════════════════════════════ */
        .portal-header {
            background: var(--navy);
            color: #fff;
            padding: 0 clamp(1rem, 4vw, 2.5rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            box-shadow: 0 2px 12px rgba(0,0,0,.28);
            position: sticky;
            top: 0;
            z-index: 200;
            gap: 1rem;
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: .65rem;
            text-decoration: none;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .portal-brand-text strong {
            display: block;
            font-size: .85rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .portal-brand-text span {
            font-size: .68rem;
            opacity: .7;
        }

        /* Desktop nav */
        .portal-nav {
            display: flex;
            align-items: center;
            gap: .2rem;
        }
        .portal-nav a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            padding: .5rem .9rem;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: color .2s, background .2s;
            white-space: nowrap;
            min-height: 40px;
        }
        .portal-nav a:hover { color: #fff; background: rgba(255,255,255,.1); }
        .portal-nav a.nav-cta {
            background: var(--gold);
            color: #fff;
            font-weight: 600;
            margin-left: .4rem;
        }
        .portal-nav a.nav-cta:hover { background: var(--gold-dark); }

        /* Hamburger button */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,.1);
            border: 1.5px solid rgba(255,255,255,.2);
            border-radius: var(--radius-sm);
            cursor: pointer;
            flex-shrink: 0;
        }
        .nav-hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: transform .25s, opacity .25s;
        }

        @media (max-width: 768px) {
            .portal-nav     { display: none; }
            .nav-hamburger  { display: flex; }
        }

        /* ══════════════════════════════════════════════════════
           MOBILE DRAWER
        ══════════════════════════════════════════════════════ */
        .drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 299;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }
        .drawer-backdrop.open { opacity: 1; pointer-events: auto; }

        .drawer-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: min(320px, 86vw);
            height: 100%;
            background: var(--navy);
            z-index: 300;
            transform: translateX(100%);
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1.25rem;
            overflow-y: auto;
        }
        .drawer-panel.open { transform: translateX(0); }

        .drawer-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .drawer-close {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,.1);
            border: none;
            border-radius: 50%;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drawer-nav a {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255,255,255,.82);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            padding: .85rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: .25rem;
            min-height: 48px;
            transition: background .2s, color .2s;
        }
        .drawer-nav a:hover { background: rgba(255,255,255,.12); color: #fff; }
        .drawer-nav a.drawer-cta {
            background: var(--gold);
            color: #fff;
            margin-top: .5rem;
            font-weight: 600;
        }
        .drawer-nav a.drawer-cta:hover { background: var(--gold-dark); }
        .drawer-divider { height: 1px; background: rgba(255,255,255,.1); margin: .75rem 0; }

        /* ══════════════════════════════════════════════════════
           MAIN & LAYOUT WRAPPERS
        ══════════════════════════════════════════════════════ */
        .portal-main { flex: 1; }

        /* Centered content wrappers — used by sub-pages */
        .portal-wrap {
            max-width: 1000px;
            margin: 0 auto;
            padding: clamp(1.5rem, 4vw, 2.5rem) clamp(1rem, 4vw, 1.5rem);
        }
        .portal-wrap-sm {
            max-width: 660px;
            margin: 0 auto;
            padding: clamp(1.5rem, 4vw, 2.5rem) clamp(1rem, 4vw, 1.5rem);
        }
        .portal-wrap-md {
            max-width: 820px;
            margin: 0 auto;
            padding: clamp(1.5rem, 4vw, 2.5rem) clamp(1rem, 4vw, 1.5rem);
        }

        /* ══════════════════════════════════════════════════════
           SHARED CARD
        ══════════════════════════════════════════════════════ */
        .p-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: clamp(1.25rem, 5vw, 2rem);
            border: 1px solid var(--border);
        }

        /* ══════════════════════════════════════════════════════
           ALERTS
        ══════════════════════════════════════════════════════ */
        .p-alert {
            padding: .85rem 1.1rem;
            border-radius: var(--radius-sm);
            font-size: .88rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .p-alert-success { background: #ecfdf5; border-color: #16a34a; color: #14532d; }
        .p-alert-error   { background: var(--crimson-pale); border-color: var(--crimson); color: var(--crimson); }
        .p-alert-warning { background: var(--gold-pale); border-color: var(--gold); color: #78450a; }

        /* ══════════════════════════════════════════════════════
           FORM ELEMENTS
        ══════════════════════════════════════════════════════ */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: .45rem;
        }
        .form-group label .req { color: var(--crimson); }
        .form-control {
            width: 100%;
            min-height: 48px;
            padding: .6rem 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            color: var(--text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(13,33,68,.1);
        }
        textarea.form-control { min-height: 100px; resize: vertical; }
        .form-error { color: var(--crimson); font-size: .78rem; margin-top: .3rem; }

        /* ══════════════════════════════════════════════════════
           BUTTONS — ALL 48px min-height
        ══════════════════════════════════════════════════════ */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            min-height: 48px;
            padding: .65rem 1.5rem;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: .92rem;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid transparent;
            text-decoration: none;
            transition: background .2s, border-color .2s, color .2s, transform .1s, box-shadow .2s;
            white-space: nowrap;
            line-height: 1;
        }
        .btn:active { transform: scale(.97); }

        .btn-primary {
            background: var(--navy);
            color: #fff;
            border-color: var(--navy);
        }
        .btn-primary:hover {
            background: var(--navy-mid);
            border-color: var(--navy-mid);
            box-shadow: 0 4px 16px rgba(13,33,68,.3);
        }
        .btn-gold {
            background: var(--gold);
            color: #fff;
            border-color: var(--gold);
        }
        .btn-gold:hover {
            background: var(--gold-dark);
            border-color: var(--gold-dark);
            box-shadow: 0 4px 16px rgba(200,134,26,.35);
        }
        .btn-outline {
            background: transparent;
            border-color: var(--navy);
            color: var(--navy);
        }
        .btn-outline:hover { background: var(--navy-pale); }

        .btn-outline-white {
            background: transparent;
            border-color: rgba(255,255,255,.6);
            color: #fff;
        }
        .btn-outline-white:hover {
            background: rgba(255,255,255,.1);
            border-color: #fff;
        }
        .btn-lg {
            min-height: 54px;
            padding: .8rem 2.25rem;
            font-size: 1rem;
            border-radius: var(--radius);
        }

        /* ══════════════════════════════════════════════════════
           TOP-CENTER TOAST
        ══════════════════════════════════════════════════════ */
        #portalToast {
            position: fixed;
            top: 72px;
            left: 50%;
            transform: translateX(-50%) translateY(-14px);
            background: var(--navy);
            color: #fff;
            padding: .8rem 1.5rem;
            border-radius: var(--radius);
            font-size: .88rem;
            font-weight: 500;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: .65rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s, transform .3s;
            max-width: min(500px, 92vw);
        }
        #portalToast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }
        #portalToast.toast-success { background: #16a34a; }
        #portalToast.toast-error   { background: var(--crimson); }
        #portalToast.toast-warning { background: var(--gold); }

        /* ══════════════════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════════════════ */
        .portal-footer { background: var(--navy-dark); color: rgba(255,255,255,.55); }

        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 3rem clamp(1rem, 4vw, 2.5rem) 0;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 2.5rem;
        }
        .footer-brand-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: .85rem;
        }
        .footer-brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .95rem;
            color: #fff;
            flex-shrink: 0;
        }
        .footer-brand-name { font-weight: 700; font-size: .9rem; color: #fff; line-height: 1.3; }
        .footer-brand-sub  { font-size: .7rem; opacity: .6; }
        .footer-about      { font-size: .79rem; line-height: 1.75; max-width: 280px; }

        .footer-col h4 {
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255,255,255,.4);
            margin-bottom: .85rem;
        }
        .footer-col a {
            display: block;
            color: rgba(255,255,255,.58);
            text-decoration: none;
            font-size: .82rem;
            padding: .3rem 0;
            transition: color .2s;
        }
        .footer-col a:hover { color: var(--gold); }

        .footer-bottom {
            max-width: 1100px;
            margin: 2rem auto 0;
            padding: 1.1rem clamp(1rem, 4vw, 2.5rem);
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            font-size: .74rem;
        }

        @media (max-width: 768px) {
            .footer-inner  { grid-template-columns: 1fr; gap: 1.75rem; }
            .footer-bottom { justify-content: center; text-align: center; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══ TOP-CENTER TOAST ═══ --}}
<div id="portalToast" role="status" aria-live="polite"></div>

{{-- ═══ MOBILE DRAWER ═══ --}}
<div class="drawer-backdrop" id="drawerBackdrop" onclick="closeMobileNav()"></div>
<div class="drawer-panel" id="drawerPanel" role="dialog" aria-modal="true" aria-label="Navigation menu">
    <div class="drawer-head">
        <div class="portal-brand">
            <div class="brand-icon"><i class="fas fa-landmark"></i></div>
            <div class="portal-brand-text">
                <strong>Barangay New Era</strong>
                <span>District VI, QC</span>
            </div>
        </div>
        <button class="drawer-close" onclick="closeMobileNav()" aria-label="Close menu">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <nav class="drawer-nav">
        <a href="{{ route('portal.index') }}"><i class="fas fa-home fa-fw"></i> Home</a>
        <a href="{{ route('portal.track') }}"><i class="fas fa-search fa-fw"></i> Track My Status</a>
        <div class="drawer-divider"></div>
        @auth
            <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt fa-fw"></i> Staff Dashboard</a>
        @else
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt fa-fw"></i> Staff Login</a>
        @endauth
        <a href="{{ route('portal.request') }}" class="drawer-cta">
            <i class="fas fa-file-plus fa-fw"></i> Request a Document
        </a>
    </nav>
</div>

{{-- ═══ STICKY HEADER ═══ --}}
<header class="portal-header">
    <a href="{{ route('portal.index') }}" class="portal-brand">
        <div class="brand-icon"><i class="fas fa-landmark"></i></div>
        <div class="portal-brand-text">
            <strong>Barangay New Era</strong>
            <span>District VI, Quezon City</span>
        </div>
    </a>

    <nav class="portal-nav" aria-label="Main navigation">
        <a href="{{ route('portal.index') }}"><i class="fas fa-home"></i> Home</a>
        <a href="{{ route('portal.track') }}"><i class="fas fa-search"></i> Track Status</a>
        @auth
            <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Staff Login</a>
        @endauth
        <a href="{{ route('portal.request') }}" class="nav-cta">
            <i class="fas fa-file-plus"></i> Request Document
        </a>
    </nav>

    <button class="nav-hamburger" onclick="openMobileNav()" aria-label="Open navigation menu" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</header>

{{-- ═══ PAGE CONTENT ═══ --}}
<main class="portal-main" id="main-content">
    @yield('content')
</main>

{{-- ═══ FOOTER ═══ --}}
<footer class="portal-footer" aria-label="Site footer">
    <div class="footer-inner">
        <div>
            <div class="footer-brand-row">
                <div class="footer-brand-icon"><i class="fas fa-landmark"></i></div>
                <div>
                    <div class="footer-brand-name">Barangay New Era</div>
                    <div class="footer-brand-sub">District VI, Quezon City</div>
                </div>
            </div>
            <p class="footer-about">Serving our residents with transparency, efficiency, and integrity. Your trusted barangay government — now online.</p>
        </div>
        <div class="footer-col">
            <h4>Services</h4>
            <a href="{{ route('portal.request') }}">Request a Document</a>
            <a href="{{ route('portal.track') }}">Track My Status</a>
        </div>
        <div class="footer-col">
            <h4>Barangay</h4>
            <a href="#">About Barangay New Era</a>
            @auth
                <a href="{{ route('dashboard') }}">Staff Dashboard</a>
            @else
                <a href="{{ route('login') }}">Staff Login</a>
            @endauth
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} Barangay New Era, District VI, Quezon City. All rights reserved.</span>
        <span>Punong Barangay: <strong style="color:rgba(255,255,255,.7)">Robert S. Romano</strong></span>
    </div>
</footer>

<script>
    /* ── Mobile Drawer ── */
    function openMobileNav() {
        document.getElementById('drawerPanel').classList.add('open');
        document.getElementById('drawerBackdrop').classList.add('open');
        document.body.style.overflow = 'hidden';
        document.querySelector('.nav-hamburger').setAttribute('aria-expanded', 'true');
    }
    function closeMobileNav() {
        document.getElementById('drawerPanel').classList.remove('open');
        document.getElementById('drawerBackdrop').classList.remove('open');
        document.body.style.overflow = '';
        document.querySelector('.nav-hamburger').setAttribute('aria-expanded', 'false');
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMobileNav();
    });

    /* ── Toast ── */
    window.portalToast = function (message, type) {
        var icons = { success: 'check-circle', error: 'exclamation-circle', warning: 'triangle-exclamation' };
        var t = document.getElementById('portalToast');
        t.className = type ? 'toast-' + type : '';
        t.innerHTML = '<i class="fas fa-' + (icons[type] || 'info-circle') + '"></i> ' + message;
        t.classList.add('show');
        clearTimeout(t._timer);
        t._timer = setTimeout(function () { t.classList.remove('show'); }, 4500);
    };

    /* ── Flash session toasts ── */
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success')) portalToast('{{ addslashes(session('success')) }}', 'success'); @endif
        @if(session('error'))   portalToast('{{ addslashes(session('error')) }}',   'error');   @endif
        @if(session('warning')) portalToast('{{ addslashes(session('warning')) }}', 'warning'); @endif
    });
</script>
@stack('scripts')
</body>
</html>
