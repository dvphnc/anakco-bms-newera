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
        /* ═══════════════════════════════════════════════════════
           DESIGN TOKENS
        ═══════════════════════════════════════════════════════ */
        :root {
            --navy:           #0D2144;
            --navy-dark:      #091830;
            --navy-mid:       #162e5a;
            --navy-light:     #1e3f73;
            --navy-pale:      #e8edf5;
            --navy-border:    #b8c6de;
            --gold:           #C8861A;
            --gold-dark:      #a86d10;
            --gold-light:     #e0a843;
            --gold-pale:      #fdf3e3;
            --gold-border:    #e8c47a;
            --crimson:        #9B1C1C;
            --crimson-pale:   #fdeaea;
            --surface:        #ffffff;
            --bg:             #F3F4F6;
            --text:           #1a2332;
            --muted:          #6b7280;
            --border:         #e2e6ea;
            --radius:         12px;
            --radius-sm:      8px;
            --radius-lg:      16px;
            --radius-xl:      24px;
            --shadow-sm:      0 1px 3px rgba(0,0,0,.08);
            --shadow-md:      0 4px 16px rgba(0,0,0,.10), 0 2px 4px rgba(0,0,0,.06);
            --shadow-lg:      0 12px 40px rgba(0,0,0,.15), 0 4px 10px rgba(0,0,0,.08);
            --shadow-xl:      0 24px 64px rgba(0,0,0,.18);
        }

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
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.6;
        }

        /* ═══════════════════════════════════════════════════════
           STICKY HEADER
        ═══════════════════════════════════════════════════════ */
        .portal-header {
            background: var(--navy);
            color: #fff;
            padding: 0 clamp(1rem, 4vw, 2.5rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 68px;
            box-shadow: 0 2px 16px rgba(0,0,0,.3);
            position: sticky;
            top: 0;
            z-index: 200;
            gap: 1rem;
        }

        /* ─── Brand / Seal ─── */
        .portal-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            color: #fff;
            flex-shrink: 0;
        }
        .brand-seal {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .brand-seal svg { width: 42px; height: 42px; }
        .portal-brand-text strong {
            display: block;
            font-size: .88rem;
            font-weight: 700;
            line-height: 1.2;
            white-space: nowrap;
        }
        .portal-brand-text span {
            font-size: .68rem;
            opacity: .65;
            white-space: nowrap;
        }

        /* ─── System Health indicator ─── */
        .sys-health {
            display: flex;
            align-items: center;
            gap: .4rem;
            font-size: .72rem;
            font-weight: 500;
            color: rgba(255,255,255,.65);
            white-space: nowrap;
            padding: .3rem .7rem;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 999px;
            background: rgba(255,255,255,.05);
        }
        .sys-health-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 6px #22c55e;
            animation: pulse-green 2.5s ease-in-out infinite;
            flex-shrink: 0;
        }
        @keyframes pulse-green {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .7; transform: scale(1.3); }
        }
        @media (max-width: 900px) { .sys-health { display: none; } }

        /* ─── Desktop nav ─── */
        .portal-nav {
            display: flex;
            align-items: center;
            gap: .15rem;
        }
        .portal-nav a {
            color: rgba(255,255,255,.78);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            padding: .5rem .9rem;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            transition: color .2s, background .2s;
            white-space: nowrap;
            min-height: 40px;
        }
        .portal-nav a:hover { color: #fff; background: rgba(255,255,255,.1); }
        .portal-nav a.nav-active { color: #fff; background: rgba(255,255,255,.12); }
        .portal-nav a.nav-cta {
            background: var(--gold);
            color: #fff;
            font-weight: 600;
            margin-left: .5rem;
            border: 1.5px solid transparent;
        }
        .portal-nav a.nav-cta:hover {
            background: var(--gold-dark);
            box-shadow: 0 4px 14px rgba(200,134,26,.4);
        }

        /* ─── Ctrl+K command trigger ─── */
        .cmd-trigger {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: var(--radius-sm);
            color: rgba(255,255,255,.6);
            font-size: .75rem;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;
            padding: .35rem .75rem;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s, color .2s, border-color .2s;
            min-height: 34px;
        }
        .cmd-trigger:hover {
            background: rgba(255,255,255,.12);
            color: rgba(255,255,255,.9);
            border-color: rgba(255,255,255,.3);
        }
        .cmd-trigger kbd {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 4px;
            padding: .05rem .3rem;
            font-size: .68rem;
            font-family: inherit;
            line-height: 1.4;
        }
        @media (max-width: 1060px) { .cmd-trigger { display: none; } }

        /* ─── Hamburger ─── */
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
        }
        @media (max-width: 768px) {
            .portal-nav    { display: none; }
            .nav-hamburger { display: flex; }
        }

        /* ═══════════════════════════════════════════════════════
           MOBILE DRAWER
        ═══════════════════════════════════════════════════════ */
        .drawer-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.52);
            z-index: 299;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }
        .drawer-backdrop.open { opacity: 1; pointer-events: auto; }

        .drawer-panel {
            position: fixed;
            top: 0; right: 0;
            width: min(320px, 88vw);
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
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .drawer-close {
            width: 36px; height: 36px;
            background: rgba(255,255,255,.1);
            border: none; border-radius: 50%;
            color: #fff; font-size: 1rem;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .drawer-nav a {
            display: flex;
            align-items: center;
            gap: .75rem;
            color: rgba(255,255,255,.8);
            text-decoration: none;
            font-size: .9rem;
            font-weight: 500;
            padding: .85rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: .2rem;
            min-height: 48px;
            transition: background .2s, color .2s;
        }
        .drawer-nav a:hover { background: rgba(255,255,255,.12); color: #fff; }
        .drawer-nav a.drawer-cta {
            background: var(--gold);
            color: #fff; font-weight: 600;
            margin-top: .75rem;
        }
        .drawer-nav a.drawer-cta:hover { background: var(--gold-dark); }
        .drawer-divider { height: 1px; background: rgba(255,255,255,.1); margin: .75rem 0; }

        /* ═══════════════════════════════════════════════════════
           COMMAND PALETTE
        ═══════════════════════════════════════════════════════ */
        .portal-cmd-overlay {
            position: fixed;
            inset: 0;
            background: rgba(9,24,48,.72);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 8000;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: clamp(60px, 12vh, 120px);
            animation: cmdFadeIn .18s ease;
        }
        @keyframes cmdFadeIn { from { opacity: 0; } }

        .portal-cmd-box {
            background: #fff;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            width: min(640px, 94vw);
            overflow: hidden;
            animation: cmdSlideDown .22s cubic-bezier(.4,0,.2,1);
        }
        @keyframes cmdSlideDown {
            from { opacity: 0; transform: translateY(-16px) scale(.97); }
        }

        .cmd-input-row {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1.5px solid var(--border);
        }
        .cmd-input-row .cmd-search-icon {
            color: var(--muted);
            font-size: 1rem;
            flex-shrink: 0;
        }
        #portalCmdInput {
            flex: 1;
            border: none;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            color: var(--text);
            background: transparent;
        }
        #portalCmdInput::placeholder { color: #9ca3af; }
        .cmd-esc {
            background: #f3f4f6;
            border: 1px solid #e2e6ea;
            border-radius: 5px;
            padding: .15rem .45rem;
            font-size: .7rem;
            color: var(--muted);
            font-family: inherit;
            cursor: pointer;
        }

        .cmd-results { max-height: 360px; overflow-y: auto; }
        .cmd-section-head {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--muted);
            padding: .75rem 1.25rem .35rem;
        }
        .cmd-item {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .7rem 1.25rem;
            cursor: pointer;
            transition: background .15s;
        }
        .cmd-item:hover, .cmd-item.cmd-selected {
            background: var(--navy-pale);
        }
        .cmd-item-icon {
            width: 36px; height: 36px;
            border-radius: var(--radius-sm);
            background: var(--navy-pale);
            color: var(--navy);
            display: flex; align-items: center; justify-content: center;
            font-size: .88rem;
            flex-shrink: 0;
        }
        .cmd-item:hover .cmd-item-icon, .cmd-item.cmd-selected .cmd-item-icon {
            background: var(--navy);
            color: #fff;
        }
        .cmd-item-body { flex: 1; min-width: 0; }
        .cmd-item-body strong {
            display: block;
            font-size: .88rem;
            font-weight: 600;
            color: var(--navy);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .cmd-item-body span {
            font-size: .75rem;
            color: var(--muted);
        }
        .cmd-item-arrow { color: #d1d5db; font-size: .75rem; }
        .cmd-item:hover .cmd-item-arrow, .cmd-item.cmd-selected .cmd-item-arrow {
            color: var(--navy);
        }
        .cmd-track-hint {
            padding: .5rem 1.25rem;
            font-size: .75rem;
            color: var(--muted);
            background: #fafafa;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: .4rem;
        }
        .cmd-empty {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--muted);
            font-size: .88rem;
        }

        /* ═══════════════════════════════════════════════════════
           MAIN LAYOUT
        ═══════════════════════════════════════════════════════ */
        .portal-main { flex: 1; }

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

        /* ═══════════════════════════════════════════════════════
           SHARED COMPONENTS
        ═══════════════════════════════════════════════════════ */
        .p-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            padding: clamp(1.25rem, 5vw, 2rem);
            border: 1px solid var(--border);
        }
        .p-alert {
            padding: .85rem 1.1rem;
            border-radius: var(--radius-sm);
            font-size: .88rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
            display: flex; align-items: center; gap: .6rem;
        }
        .p-alert-success { background: #ecfdf5; border-color: #16a34a; color: #14532d; }
        .p-alert-error   { background: var(--crimson-pale); border-color: var(--crimson); color: var(--crimson); }
        .p-alert-warning { background: var(--gold-pale); border-color: var(--gold); color: #78450a; }

        /* ─── Form ─── */
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
            padding: .65rem 1rem;
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

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex;
            align-items: center; justify-content: center;
            gap: .5rem;
            min-height: 48px;
            padding: .65rem 1.6rem;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: .92rem;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid transparent;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
            line-height: 1;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary {
            background: var(--navy); color: #fff; border-color: var(--navy);
        }
        .btn-primary:hover {
            background: var(--navy-mid); border-color: var(--navy-mid);
            box-shadow: 0 4px 16px rgba(13,33,68,.3);
        }
        .btn-gold {
            background: var(--gold); color: #fff; border-color: var(--gold);
        }
        .btn-gold:hover {
            background: var(--gold-dark); border-color: var(--gold-dark);
            box-shadow: 0 4px 16px rgba(200,134,26,.4);
        }
        .btn-outline {
            background: transparent; border-color: var(--navy); color: var(--navy);
        }
        .btn-outline:hover { background: var(--navy-pale); }
        .btn-outline-white {
            background: transparent; border-color: rgba(255,255,255,.55); color: #fff;
        }
        .btn-outline-white:hover { background: rgba(255,255,255,.1); border-color: #fff; }
        .btn-lg { min-height: 54px; padding: .8rem 2.25rem; font-size: 1rem; border-radius: var(--radius); }

        /* ─── Top-center Toast ─── */
        #portalToast {
            position: fixed;
            top: 76px; left: 50%;
            transform: translateX(-50%) translateY(-16px);
            background: var(--navy); color: #fff;
            padding: .8rem 1.6rem;
            border-radius: var(--radius);
            font-size: .88rem; font-weight: 500;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
            display: flex; align-items: center; gap: .65rem;
            opacity: 0; pointer-events: none;
            transition: opacity .3s, transform .3s;
            max-width: min(520px, 92vw);
        }
        #portalToast.show {
            opacity: 1; transform: translateX(-50%) translateY(0); pointer-events: auto;
        }
        #portalToast.toast-success { background: #16a34a; }
        #portalToast.toast-error   { background: var(--crimson); }
        #portalToast.toast-warning { background: var(--gold); }

        /* ═══════════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════════ */
        .portal-footer { background: var(--navy-dark); color: rgba(255,255,255,.55); }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3.5rem clamp(1rem, 4vw, 2.5rem) 0;
            display: grid;
            grid-template-columns: 1.7fr 1fr 1fr 1.2fr auto;
            gap: 2rem 2.5rem;
            align-items: start;
        }

        /* Seal + brand column */
        .footer-brand { display: flex; flex-direction: column; gap: .85rem; }
        .footer-brand-top {
            display: flex; align-items: center; gap: .85rem;
        }
        .footer-seal-sm {
            width: 48px; height: 48px; flex-shrink: 0;
        }
        .footer-brand-name { font-weight: 700; font-size: .95rem; color: #fff; line-height: 1.25; }
        .footer-brand-sub  { font-size: .72rem; opacity: .6; }
        .footer-about      { font-size: .8rem; line-height: 1.75; max-width: 260px; }
        .footer-legal-badge {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(200,134,26,.15);
            border: 1px solid rgba(200,134,26,.3);
            color: var(--gold-light);
            padding: .3rem .75rem;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 600;
            margin-top: .25rem;
            width: fit-content;
        }

        /* Footer link columns */
        .footer-col h4 {
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            margin-bottom: 1rem; padding-bottom: .5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .footer-col a {
            display: flex; align-items: center; gap: .5rem;
            color: rgba(255,255,255,.55);
            text-decoration: none; font-size: .82rem;
            padding: .3rem 0;
            transition: color .2s;
        }
        .footer-col a:hover { color: var(--gold-light); }
        .footer-col a i { font-size: .72rem; width: 14px; opacity: .6; }

        /* Republic seal column — standalone visual anchor */
        .footer-republic {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-left: 1.5rem;
            border-left: 1px solid rgba(255,255,255,.08);
        }
        .footer-republic-img {
            width: 148px;
            height: 148px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: .45;
            transition: opacity .3s;
        }
        .footer-republic:hover .footer-republic-img { opacity: .7; }

        /* System Health widget */
        .footer-health {
            display: flex;
            flex-direction: column;
            gap: .55rem;
        }
        .footer-health h4 {
            font-size: .72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            margin-bottom: .45rem; padding-bottom: .5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .health-row {
            display: flex;
            align-items: center;
            gap: .55rem;
            font-size: .78rem;
            color: rgba(255,255,255,.55);
            line-height: 1.4;
        }
        .health-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .health-dot.ok      { background: #22c55e; box-shadow: 0 0 5px rgba(34,197,94,.5); }
        .health-dot.warn    { background: #f59e0b; box-shadow: 0 0 5px rgba(245,158,11,.5); }
        .health-dot.offline { background: #ef4444; box-shadow: 0 0 5px rgba(239,68,68,.5);  }
        .health-label { color: rgba(255,255,255,.75); font-weight: 500; }

        .footer-bottom {
            max-width: 1100px;
            margin: 2rem auto 0;
            padding: 1.1rem clamp(1rem, 4vw, 2.5rem);
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center; justify-content: space-between;
            gap: 1rem; flex-wrap: wrap;
            font-size: .74rem;
        }
        .footer-pb {
            display: flex; align-items: center; gap: .45rem;
            color: rgba(255,255,255,.5);
        }
        .footer-pb strong { color: rgba(255,255,255,.75); }

        @media (max-width: 1100px) {
            .footer-inner { grid-template-columns: 1.7fr 1fr 1fr 1.2fr; }
            .footer-republic { display: none; }
        }
        @media (max-width: 900px) {
            .footer-inner { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
        @media (max-width: 560px) {
            .footer-inner { grid-template-columns: 1fr; }
            .footer-bottom { justify-content: center; text-align: center; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══ TOAST ═══ --}}
<div id="portalToast" role="status" aria-live="polite"></div>

{{-- ═══ COMMAND PALETTE ═══ --}}
<div id="portalCmdOverlay" class="portal-cmd-overlay" style="display:none" role="dialog" aria-modal="true" aria-label="Quick search">
    <div class="portal-cmd-box">
        <div class="cmd-input-row">
            <i class="fas fa-search cmd-search-icon"></i>
            <input id="portalCmdInput"
                   type="text"
                   placeholder="Search services or type an appointment number…"
                   autocomplete="off"
                   spellcheck="false">
            <button class="cmd-esc" onclick="closeCmdPalette()">ESC</button>
        </div>
        <div class="cmd-results" id="portalCmdResults"></div>
        <div class="cmd-track-hint">
            <i class="fas fa-lightbulb" style="color:var(--gold)"></i>
            Tip: Type <strong style="color:var(--navy)">APT-</strong> to quickly look up an appointment number
        </div>
    </div>
</div>

{{-- ═══ MOBILE DRAWER ═══ --}}
<div class="drawer-backdrop" id="drawerBackdrop" onclick="closeMobileNav()"></div>
<div class="drawer-panel"    id="drawerPanel"    role="dialog" aria-modal="true" aria-label="Navigation menu">
    <div class="drawer-head">
        <div class="portal-brand">
            <div class="brand-seal">
                @include('partials._portal_seal')
            </div>
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
        <div class="brand-seal">
            @include('partials._portal_seal')
        </div>
        <div class="portal-brand-text">
            <strong>Barangay New Era</strong>
            <span>District VI, Quezon City</span>
        </div>
    </a>

    {{-- System health pill --}}
    <div class="sys-health" title="All portal systems operational">
        <div class="sys-health-dot"></div>
        Online &amp; Secure
    </div>

    <nav class="portal-nav" aria-label="Main navigation">
        <a href="{{ route('portal.index') }}" class="{{ request()->routeIs('portal.index') ? 'nav-active' : '' }}">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="{{ route('portal.track') }}" class="{{ request()->routeIs('portal.track*') ? 'nav-active' : '' }}">
            <i class="fas fa-search"></i> Track Status
        </a>
        @auth
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        @else
            <a href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt"></i> Staff Login
            </a>
        @endauth
        <a href="{{ route('portal.request') }}" class="nav-cta">
            <i class="fas fa-file-plus"></i> Request Document
        </a>
    </nav>

    {{-- Ctrl+K trigger --}}
    <button class="cmd-trigger" onclick="openCmdPalette()" title="Quick search (Ctrl+K)" aria-label="Open quick search">
        <i class="fas fa-search"></i>
        Quick Search
        <kbd>⌃K</kbd>
    </button>

    <button class="nav-hamburger" onclick="openMobileNav()" aria-label="Open navigation" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</header>

{{-- ═══ PAGE CONTENT ═══ --}}
<main class="portal-main" id="main-content">
    @yield('content')
</main>

{{-- ═══ FOOTER ═══ --}}
@php
    /* System Health — sampled once per page render */
    $ftrDbOk = true;
    try { \DB::connection()->getPdo(); } catch (\Exception $e) { $ftrDbOk = false; }

    $ftrBackupDir = storage_path('app/backups');
    $ftrFiles     = array_merge(
        glob($ftrBackupDir . '/*.sql') ?: [],
        glob($ftrBackupDir . '/*.gz')  ?: []
    );
    $ftrLastTs    = $ftrFiles ? max(array_map('filemtime', $ftrFiles)) : null;
    $ftrBackupAgo = $ftrLastTs
        ? \Carbon\Carbon::createFromTimestamp($ftrLastTs)->diffForHumans()
        : 'No backup found';
    $ftrBackupOk  = $ftrLastTs && (time() - $ftrLastTs < 86400 * 7);
@endphp

<footer class="portal-footer" aria-label="Site footer">
    <div class="footer-inner">

        {{-- ① Brand + Contact ──────────────────── --}}
        <div class="footer-brand">
            <div class="footer-brand-top">
                <div class="footer-seal-sm">
                    @include('partials._portal_seal')
                </div>
                <div>
                    <div class="footer-brand-name">Barangay New Era</div>
                    <div class="footer-brand-sub">District VI, Quezon City</div>
                </div>
            </div>
            <p class="footer-about">
                Serving our residents with transparency, efficiency, and integrity.
                Your trusted barangay government — now digital.
            </p>
            {{-- Contact snippet --}}
            <div style="margin-top:.65rem;display:flex;flex-direction:column;gap:.3rem">
                <span style="font-size:.75rem;color:rgba(255,255,255,.45);display:flex;align-items:center;gap:.45rem">
                    <i class="fas fa-map-marker-alt" style="color:var(--gold);width:12px"></i>
                    New Era, District VI, Quezon City
                </span>
                <span style="font-size:.75rem;color:rgba(255,255,255,.45);display:flex;align-items:center;gap:.45rem">
                    <i class="fas fa-clock" style="color:var(--gold);width:12px"></i>
                    Mon–Fri &nbsp;8:00 AM – 5:00 PM
                </span>
            </div>
            <div class="footer-legal-badge" style="margin-top:.85rem">
                <i class="fas fa-shield-halved"></i>
                RA 10173 Compliant · Data Privacy Act
            </div>
        </div>

        {{-- ② Services ───────────────────────────── --}}
        <div class="footer-col">
            <h4>Services</h4>
            <a href="{{ route('portal.request') }}?type=Barangay+Clearance">
                <i class="fas fa-file-shield"></i> Barangay Clearance
            </a>
            <a href="{{ route('portal.request') }}?type=Certificate+of+Indigency">
                <i class="fas fa-hand-holding-heart"></i> Certificate of Indigency
            </a>
            <a href="{{ route('portal.request') }}?type=Certificate+of+Residency">
                <i class="fas fa-house-circle-check"></i> Certificate of Residency
            </a>
            <a href="{{ route('portal.request') }}?type=Business+Clearance">
                <i class="fas fa-store"></i> Business Clearance
            </a>
        </div>

        {{-- ③ Official Links ──────────────────────── --}}
        <div class="footer-col">
            <h4>Official Links</h4>
            <a href="{{ route('portal.index') }}"><i class="fas fa-home"></i> Portal Home</a>
            <a href="{{ route('portal.request') }}"><i class="fas fa-file-plus"></i> Request a Document</a>
            <a href="{{ route('portal.track') }}"><i class="fas fa-search"></i> Track My Status</a>
            @auth
                <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Staff Dashboard</a>
            @else
                <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Staff Login</a>
            @endauth
            <a href="{{ route('portal.index') }}#faq"><i class="fas fa-circle-question"></i> FAQ</a>
        </div>

        {{-- ④ System Health ────────────────────────── --}}
        <div class="footer-health">
            <h4>System Health</h4>

            <div class="health-row">
                <div class="health-dot {{ $ftrDbOk ? 'ok' : 'offline' }}"></div>
                <div>
                    <span class="health-label">Database</span><br>
                    <span>{{ $ftrDbOk ? 'Online &amp; Connected' : 'Connection Error' }}</span>
                </div>
            </div>

            <div class="health-row">
                <div class="health-dot {{ $ftrBackupOk ? 'ok' : 'warn' }}"></div>
                <div>
                    <span class="health-label">Last Backup</span><br>
                    <span>{{ $ftrBackupAgo }}</span>
                </div>
            </div>

            <div class="health-row">
                <div class="health-dot ok"></div>
                <div>
                    <span class="health-label">Portal</span><br>
                    <span>Operational</span>
                </div>
            </div>
        </div>

        {{-- ⑤ Republika Seal — visual anchor ─────────── --}}
        <div class="footer-republic">
            <img src="{{ asset('images/republika-seal.png') }}"
                 alt="Seal of the Republic of the Philippines"
                 class="footer-republic-img">
        </div>
    </div>

    <div class="footer-bottom" style="max-width:1200px">
        <span>&copy; {{ date('Y') }} Barangay New Era, District VI, Quezon City. All rights reserved.</span>
        <div class="footer-pb">
            <i class="fas fa-user-tie" style="color:var(--gold);font-size:.8rem"></i>
            Punong Barangay: <strong>Robert S. Romano</strong>
        </div>
    </div>
</footer>

<script>
/* ════════════════════════════════════════════════
   MOBILE DRAWER
════════════════════════════════════════════════ */
function openMobileNav() {
    document.getElementById('drawerPanel').classList.add('open');
    document.getElementById('drawerBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
    document.querySelector('.nav-hamburger')?.setAttribute('aria-expanded', 'true');
}
function closeMobileNav() {
    document.getElementById('drawerPanel').classList.remove('open');
    document.getElementById('drawerBackdrop').classList.remove('open');
    document.body.style.overflow = '';
    document.querySelector('.nav-hamburger')?.setAttribute('aria-expanded', 'false');
}

/* ════════════════════════════════════════════════
   COMMAND PALETTE
════════════════════════════════════════════════ */
(function () {
    var ACTIONS = [
        { icon: 'fas fa-file-plus',          label: 'Request a Document',         sub: 'Start a new barangay document request',        href: '{{ route("portal.request") }}' },
        { icon: 'fas fa-file-shield',        label: 'Barangay Clearance',          sub: 'Certificate of good standing',                  href: '{{ route("portal.request") }}?type=Barangay+Clearance' },
        { icon: 'fas fa-hand-holding-heart', label: 'Certificate of Indigency',    sub: 'For government assistance programs',            href: '{{ route("portal.request") }}?type=Certificate+of+Indigency' },
        { icon: 'fas fa-house-circle-check', label: 'Certificate of Residency',    sub: 'Proof of residence document',                   href: '{{ route("portal.request") }}?type=Certificate+of+Residency' },
        { icon: 'fas fa-store',              label: 'Business Clearance',          sub: 'For business registration and renewal',         href: '{{ route("portal.request") }}?type=Business+Clearance' },
        { icon: 'fas fa-search',             label: 'Track Appointment Status',    sub: 'Look up an existing appointment number',        href: '{{ route("portal.track") }}' },
        { icon: 'fas fa-home',               label: 'Portal Home',                 sub: 'Back to the main portal page',                  href: '{{ route("portal.index") }}' },
        { icon: 'fas fa-circle-question',    label: 'Frequently Asked Questions',  sub: 'Common questions about barangay documents',     href: '{{ route("portal.index") }}#faq' },
    ];

    var overlay, input, results, selectedIdx = -1;

    document.addEventListener('DOMContentLoaded', function () {
        overlay = document.getElementById('portalCmdOverlay');
        input   = document.getElementById('portalCmdInput');
        results = document.getElementById('portalCmdResults');
        renderItems('');
    });

    window.openCmdPalette = function () {
        overlay.style.display = 'flex';
        input.value = '';
        selectedIdx = -1;
        renderItems('');
        requestAnimationFrame(function () { input.focus(); });
    };
    window.closeCmdPalette = function () {
        overlay.style.display = 'none';
    };

    document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            overlay && overlay.style.display === 'none' ? openCmdPalette() : closeCmdPalette();
        }
        if (!overlay || overlay.style.display === 'none') return;
        var items = results.querySelectorAll('.cmd-item');
        if (e.key === 'Escape')    { closeCmdPalette(); }
        if (e.key === 'ArrowDown') { e.preventDefault(); selectedIdx = Math.min(selectedIdx + 1, items.length - 1); highlightItem(items); }
        if (e.key === 'ArrowUp')   { e.preventDefault(); selectedIdx = Math.max(selectedIdx - 1, 0); highlightItem(items); }
        if (e.key === 'Enter' && selectedIdx >= 0 && items[selectedIdx]) {
            window.location.href = items[selectedIdx].dataset.href;
        }
    });

    /* Close on backdrop click */
    document.addEventListener('click', function (e) {
        if (overlay && e.target === overlay) closeCmdPalette();
    });

    function highlightItem(items) {
        items.forEach(function (el, i) {
            el.classList.toggle('cmd-selected', i === selectedIdx);
        });
        if (items[selectedIdx]) items[selectedIdx].scrollIntoView({ block: 'nearest' });
    }

    function esc(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function renderItems(q) {
        q = (q || '').trim();
        var ql = q.toLowerCase();

        var html = '';

        /* If it looks like an appointment number, put a track shortcut first */
        if (/^apt/i.test(q) || /^\d{4,}/.test(q)) {
            var trackUrl = '{{ route("portal.track") }}?apt=' + encodeURIComponent(q.toUpperCase());
            html += '<div class="cmd-section-head">Track Appointment</div>';
            html += '<div class="cmd-item" data-href="' + esc(trackUrl) + '">'
                + '<div class="cmd-item-icon"><i class="fas fa-search"></i></div>'
                + '<div class="cmd-item-body"><strong>Track: ' + esc(q.toUpperCase()) + '</strong><span>Look up this appointment number</span></div>'
                + '<i class="fas fa-arrow-right cmd-item-arrow"></i></div>';
        }

        var filtered = ql
            ? ACTIONS.filter(function (a) {
                return a.label.toLowerCase().includes(ql) || a.sub.toLowerCase().includes(ql);
              })
            : ACTIONS;

        if (filtered.length) {
            html += '<div class="cmd-section-head">' + (ql ? 'Results' : 'Quick Actions') + '</div>';
            filtered.forEach(function (a) {
                html += '<div class="cmd-item" data-href="' + esc(a.href) + '">'
                    + '<div class="cmd-item-icon"><i class="' + a.icon + '"></i></div>'
                    + '<div class="cmd-item-body"><strong>' + esc(a.label) + '</strong><span>' + esc(a.sub) + '</span></div>'
                    + '<i class="fas fa-arrow-right cmd-item-arrow"></i></div>';
            });
        } else if (!html) {
            html = '<div class="cmd-empty"><i class="fas fa-face-sad-tear" style="font-size:1.5rem;opacity:.3;display:block;margin-bottom:.5rem"></i>No results found for "' + esc(q) + '"</div>';
        }

        results.innerHTML = html;
        selectedIdx = -1;

        results.querySelectorAll('.cmd-item').forEach(function (el) {
            el.addEventListener('click', function () { window.location.href = el.dataset.href; });
            el.addEventListener('mouseenter', function () {
                var items = results.querySelectorAll('.cmd-item');
                items.forEach(function (i) { i.classList.remove('cmd-selected'); });
                el.classList.add('cmd-selected');
                selectedIdx = Array.from(items).indexOf(el);
            });
        });
    }

    /* Live search as user types */
    document.addEventListener('input', function (e) {
        if (e.target === input) renderItems(input.value);
    });
})();

/* ════════════════════════════════════════════════
   TOAST
════════════════════════════════════════════════ */
window.portalToast = function (message, type) {
    var icons = { success: 'check-circle', error: 'exclamation-circle', warning: 'triangle-exclamation' };
    var t = document.getElementById('portalToast');
    t.className = type ? 'toast-' + type : '';
    t.innerHTML = '<i class="fas fa-' + (icons[type] || 'info-circle') + '"></i> ' + message;
    t.classList.add('show');
    clearTimeout(t._timer);
    t._timer = setTimeout(function () { t.classList.remove('show'); }, 4500);
};

document.addEventListener('DOMContentLoaded', function () {
    @if(session('success')) portalToast('{{ addslashes(session('success')) }}', 'success'); @endif
    @if(session('error'))   portalToast('{{ addslashes(session('error')) }}',   'error');   @endif
    @if(session('warning')) portalToast('{{ addslashes(session('warning')) }}', 'warning'); @endif
});
</script>
@stack('scripts')
</body>
</html>
