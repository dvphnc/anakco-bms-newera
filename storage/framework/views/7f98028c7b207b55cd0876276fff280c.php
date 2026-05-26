<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Resident Portal'); ?> — Barangay New Era</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>
    // ── Axios CSRF interceptor (portal) ──────────────────────────────────
    axios.interceptors.request.use(function (config) {
        var meta  = document.querySelector('meta[name="csrf-token"]');
        var token = meta ? meta.getAttribute('content') : '';
        config.headers = config.headers || {};
        if (token) { config.headers['X-CSRF-TOKEN'] = token; }
        config.headers['X-Requested-With'] = 'XMLHttpRequest';
        config.headers['Accept']           = 'application/json';
        if (token && config.data instanceof FormData) {
            config.data.delete('_token');
            config.data.append('_token', token);
        }
        return config;
    });
    </script>
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
           GOVERNMENT STRIP
        ═══════════════════════════════════════════════════════ */
        /* ── Scroll progress bar ──────────────────────────────────────── */
        #scroll-progress {
            position: fixed;
            top: 0; left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 100%);
            z-index: 9999;
            transition: width .08s linear;
            box-shadow: 0 0 8px rgba(200,134,26,.5);
        }

        .gov-strip {
            background: #fff;
            border-bottom: 1px solid #e2e6ea;
            padding: 0 clamp(1rem, 4vw, 2.5rem);
        }
        .gov-strip-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 38px;
            gap: 1rem;
        }
        .gov-strip-left {
            display: flex;
            align-items: center;
            gap: .55rem;
            white-space: nowrap;
            overflow: hidden;
        }
        .gov-strip-seal {
            width: 26px; height: 26px;
            object-fit: contain;
            flex-shrink: 0;
            display: block;
        }
        .gov-strip-name {
            font-size: .7rem;
            font-weight: 600;
            color: #1a2332;
            letter-spacing: .01em;
        }
        .gov-strip-divider {
            width: 1px; height: 14px;
            background: #d1d5db;
            flex-shrink: 0;
        }
        .gov-strip-sub {
            font-size: .68rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .gov-strip-right {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-size: .68rem;
            color: #6b7280;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .gov-strip-right i { font-size: .6rem; color: var(--navy); opacity: .6; }
        @media (max-width: 640px) {
            .gov-strip-right { display: none; }
            .gov-strip-sub   { display: none; }
        }

        /* ═══════════════════════════════════════════════════════
           PORTAL FOOTER  (eGov PH–style)
        ═══════════════════════════════════════════════════════ */
        .portal-footer {
            background: linear-gradient(160deg, #07162a 0%, #0D2144 60%, #0f2850 100%);
            color: rgba(255,255,255,.55);
            margin-top: auto;
        }
        .portal-footer-body {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem clamp(1rem, 4vw, 2.5rem) 2.5rem;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 2.5rem;
            align-items: start;
        }

        /* ── Brand column: big seal + text side-by-side ── */
        .pf-brand {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 1.25rem;
        }
        .pf-big-seal {
            width: 120px;
            height: 120px;
            object-fit: contain;
            flex-shrink: 0;
            opacity: .82;
            filter: grayscale(20%);
            transition: opacity .2s;
        }
        .pf-big-seal:hover { opacity: 1; }
        .pf-brand-text { display: flex; flex-direction: column; gap: .55rem; }
        .pf-name {
            font-size: 1.05rem; font-weight: 800;
            color: #fff; line-height: 1.2;
        }
        .pf-tagline {
            font-size: .82rem; font-weight: 400;
            color: rgba(255,255,255,.62);
            line-height: 1.6; max-width: 260px;
        }
        .pf-sub {
            font-size: .72rem;
            color: rgba(255,255,255,.35);
            line-height: 1.5;
        }

        /* ── Link columns ── */
        .pf-col h5 {
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .13em;
            color: rgba(255,255,255,.9);
            margin-bottom: 1rem;
        }
        .pf-col ul { list-style: none; display: flex; flex-direction: column; gap: .5rem; }
        .pf-col ul li a {
            font-size: .82rem;
            color: rgba(255,255,255,.45);
            text-decoration: none;
            transition: color .15s;
            display: block;
        }
        .pf-col ul li a:hover { color: rgba(255,255,255,.9); }

        /* ── Developed By ── */
        .pf-devby-name {
            font-size: .88rem; font-weight: 700; color: rgba(255,255,255,.75);
            margin-bottom: .25rem;
        }
        .pf-devby-sub {
            font-size: .76rem; color: rgba(255,255,255,.38);
        }

        /* ── Bottom bar ── */
        .portal-footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07);
            padding: 1rem clamp(1rem, 4vw, 2.5rem);
        }
        .portal-footer-bottom-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center;
            justify-content: space-between; gap: 1rem; flex-wrap: wrap;
        }
        .pf-copy { font-size: .71rem; color: rgba(255,255,255,.25); }
        .pf-legal { display: flex; gap: 1.25rem; flex-wrap: wrap; }
        .pf-legal a {
            font-size: .71rem; color: rgba(255,255,255,.25);
            text-decoration: none; transition: color .15s;
        }
        .pf-legal a:hover { color: rgba(255,255,255,.65); }

        @media (max-width: 960px) {
            .portal-footer-body { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .pf-brand { grid-column: 1 / -1; }
        }
        @media (max-width: 500px) {
            .portal-footer-body { grid-template-columns: 1fr; }
            .pf-brand { flex-direction: column; align-items: flex-start; }
            .pf-big-seal { width: 90px; height: 90px; }
        }

        /* ═══════════════════════════════════════════════════════
           SPLASH SCREEN  (coin-flip — matches login page)
        ═══════════════════════════════════════════════════════ */
        #splash {
            position: fixed; inset: 0; z-index: 99999;
            background: var(--navy);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 0;
            transition: opacity 0.7s ease, visibility 0.7s ease;
        }
        #splash.splash-hide { opacity: 0; visibility: hidden; }

        .splash-coin {
            width: 140px; height: 140px;
            perspective: 600px;
            margin-bottom: 32px;
        }
        .splash-coin-inner {
            width: 100%; height: 100%;
            position: relative;
            transform-style: preserve-3d;
            animation: splashFlip 2s ease-in-out infinite;
            border-radius: 50%;
        }
        @keyframes splashFlip {
            0%   { transform: rotateY(0deg); }
            18%  { transform: rotateY(0deg); }
            48%  { transform: rotateY(180deg); }
            65%  { transform: rotateY(180deg); }
            95%  { transform: rotateY(360deg); }
            100% { transform: rotateY(360deg); }
        }
        .splash-front, .splash-back {
            position: absolute; inset: 0;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        .splash-front {
            border-radius: 50%; background: #fff;
            border: 3px solid rgba(200,134,26,0.5);
            box-shadow:
                0 0 0 9px rgba(200,134,26,0.08),
                0 0 60px rgba(200,134,26,0.2),
                0 24px 60px rgba(0,0,0,0.5);
            overflow: hidden;
        }
        .splash-front img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .splash-back {
            transform: rotateY(180deg);
            background: transparent; border: none; box-shadow: none;
            display: flex; align-items: center; justify-content: center;
        }
        .splash-back img {
            width: 100%; height: 100%; object-fit: contain; display: block;
            filter: drop-shadow(0 4px 20px rgba(0,0,0,0.6));
        }
        .splash-line {
            width: 60px; height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 20px auto 24px; border-radius: 2px;
        }
        .splash-title {
            font-size: 26px; font-weight: 800; color: #fff;
            letter-spacing: -0.01em; text-align: center;
            line-height: 1.2; margin-bottom: 6px;
        }
        .splash-title span { color: #E5A020; }
        .splash-sub {
            font-size: 11px; font-weight: 400;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase; letter-spacing: 0.2em;
            text-align: center; margin-bottom: 36px;
        }
        .splash-dots { display: flex; gap: 8px; align-items: center; }
        .splash-dots span {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--gold); opacity: 0.25;
            animation: dotPulse 1.2s ease-in-out infinite;
        }
        .splash-dots span:nth-child(2) { animation-delay: 0.2s; }
        .splash-dots span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dotPulse {
            0%, 100% { opacity: 0.2; transform: scale(0.8); }
            50%       { opacity: 1;   transform: scale(1.2); }
        }

    </style>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        /* ── Alt input matches portal .form-control ── */
        .flatpickr-input.form-control[readonly] { background: #fff; cursor: pointer; }

        /* ── Restore spacing destroyed by portal * { margin:0; padding:0 } reset ── */
        .flatpickr-calendar * { box-sizing: border-box; }
        .flatpickr-calendar {
            font-family: 'Poppins', sans-serif !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-lg) !important;
            border: 1px solid var(--border) !important;
            padding: 0 !important;
            width: 308px !important;
        }
        .flatpickr-months {
            background: var(--navy);
            border-radius: var(--radius) var(--radius) 0 0;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            height: 46px !important;
        }
        .flatpickr-months .flatpickr-month {
            background: transparent;
            color: #fff;
            height: 46px !important;
            line-height: 46px !important;
        }
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            padding: 10px !important;
            height: 46px !important;
            display: flex !important;
            align-items: center !important;
            top: 0 !important;
        }
        .flatpickr-current-month {
            padding: 0 !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            height: 46px !important;
            line-height: 46px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
        }
        /* Native select hidden — replaced by custom dropdown via JS */
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            display: none !important;
        }
        /* ── Custom header buttons (shared base) ── */
        .fp-month-btn, .fp-year-btn {
            background: none; border: none; cursor: pointer;
            color: #fff; font-weight: 700; font-size: 14px;
            font-family: 'Poppins', sans-serif;
            display: inline-flex; align-items: center; gap: 5px;
            padding: 2px 6px; border-radius: 4px;
            transition: background .15s;
        }
        .fp-month-btn:hover, .fp-year-btn:hover { background: rgba(255,255,255,.15); }

        /* ── Shared picker panel — body-fixed to escape overflow clipping ── */
        .fp-picker-panel {
            display: none;
            position: fixed;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            box-shadow: 0 8px 28px rgba(0,0,0,.18);
            z-index: 99999;
            padding: 10px 8px 12px;
            min-width: 200px;
        }
        .fp-picker-panel.open { display: block; }

        /* ── Month grid ── */
        .fp-month-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;
        }
        /* ── Year grid ── */
        .fp-year-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;
            max-height: 180px; overflow-y: auto;
        }
        /* ── Shared cell style ── */
        .fp-picker-cell {
            background: none; border: 1px solid transparent;
            border-radius: 6px; padding: 7px 4px;
            font-size: 12px; font-weight: 500;
            color: var(--navy); cursor: pointer;
            font-family: 'Poppins', sans-serif;
            text-align: center; transition: all .12s;
        }
        .fp-picker-cell:hover   { background: var(--navy-pale); border-color: var(--navy-border); }
        .fp-picker-cell.current { background: var(--navy); color: #fff; border-color: var(--navy); }

        /* ── Hide native year input + arrows (replaced by static display) ── */
        .numInputWrapper { display: none !important; }

        /* ── Year static badge ── */
        .fp-year-display {
            color: #fff; font-weight: 700; font-size: 14px;
            font-family: 'Poppins', sans-serif;
            user-select: none;
        }

        /* ══════════════════════════════════════════
           CUSTOM PORTAL SELECT DROPDOWN
        ══════════════════════════════════════════ */
        .p-select-wrap { position: relative; }
        /* Trigger button */
        .p-select-trigger {
            width: 100%; min-height: 48px;
            padding: .65rem 2.5rem .65rem 1rem;
            border: 1.5px solid #d1d5db;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: 1rem; color: var(--text);
            background: #fff;
            cursor: pointer; text-align: left;
            display: flex; align-items: center;
            transition: border-color .2s, box-shadow .2s;
            position: relative;
        }
        .p-select-trigger:focus,
        .p-select-trigger.open {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(13,33,68,.1);
        }
        .p-select-trigger .p-select-val {
            flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .p-select-trigger .p-select-val.placeholder { color: #9ca3af; }
        .p-select-trigger .p-select-arrow {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-size: 11px; pointer-events: none;
            transition: transform .2s;
        }
        .p-select-trigger.open .p-select-arrow { transform: translateY(-50%) rotate(180deg); }
        /* Dropdown panel */
        .p-select-panel {
            display: none;
            position: fixed;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            box-shadow: 0 8px 28px rgba(0,0,0,.16);
            z-index: 99999;
            overflow: hidden;
        }
        .p-select-panel.open { display: block; }
        .p-select-list {
            max-height: 240px; overflow-y: auto;
            padding: 4px 0;
        }
        .p-select-item {
            padding: 10px 14px;
            font-size: .9rem; font-family: 'Poppins', sans-serif;
            cursor: pointer; color: var(--text);
            transition: background .1s;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .p-select-item:hover  { background: var(--navy-pale); color: var(--navy); }
        .p-select-item.selected { background: var(--navy); color: #fff; font-weight: 600; }
        .p-select-item.is-placeholder { color: #9ca3af; font-style: italic; }
        /* Error state */
        .p-select-trigger.is-invalid { border-color: var(--crimson) !important; }
        .flatpickr-prev-month svg,
        .flatpickr-next-month svg { fill: rgba(255,255,255,.8) !important; }
        .flatpickr-prev-month:hover svg,
        .flatpickr-next-month:hover svg { fill: var(--gold) !important; }

        /* ── Weekday header ── */
        .flatpickr-weekdays {
            background: var(--navy-pale) !important;
            height: 34px !important;
            padding: 0 !important;
        }
        .flatpickr-weekdaycontainer {
            display: flex !important;
            width: 100% !important;
        }
        span.flatpickr-weekday {
            font-size: 11px !important;
            font-weight: 700 !important;
            color: var(--navy) !important;
            background: transparent !important;
            flex: 1 !important;
            text-align: center !important;
            line-height: 34px !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* ── Days grid ── */
        .flatpickr-days { width: 308px !important; border: none !important; }
        .dayContainer {
            width: 308px !important;
            min-width: 308px !important;
            max-width: 308px !important;
            padding: 6px 4px !important;
            gap: 0 !important;
        }
        .flatpickr-day {
            width: 39px !important;
            max-width: 39px !important;
            height: 36px !important;
            line-height: 36px !important;
            font-size: 13px !important;
            font-family: 'Poppins', sans-serif !important;
            margin: 1px 0 !important;
            border-radius: 6px !important;
            border: 1px solid transparent !important;
        }
        .flatpickr-day:hover:not(.disabled):not(.selected) {
            background: var(--navy-pale) !important;
            border-color: var(--navy-border) !important;
        }
        .flatpickr-day.selected,
        .flatpickr-day.selected:hover {
            background: var(--navy) !important;
            border-color: var(--navy) !important;
            color: #fff !important;
        }
        .flatpickr-day.today {
            border-color: var(--gold) !important;
            font-weight: 700 !important;
        }
        .flatpickr-day.today:hover:not(.selected) {
            background: var(--gold-pale) !important;
        }
        .flatpickr-day.prevMonthDay,
        .flatpickr-day.nextMonthDay { color: #c9cdd4 !important; }
        .flatpickr-day.disabled,
        .flatpickr-day.flatpickr-disabled {
            color: #d1d5db !important;
            cursor: not-allowed !important;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>


<div id="scroll-progress" aria-hidden="true"></div>



<div id="splash" role="status" aria-label="Loading portal">
    <div class="splash-coin">
        <div class="splash-coin-inner">
            <div class="splash-front">
                <img src="<?php echo e(asset('images/bne-logo.png')); ?>" alt="Barangay New Era"
                     onerror="this.style.display='none'">
            </div>
            <div class="splash-back">
                <img src="<?php echo e(asset('images/qc-seal.png')); ?>" alt="Quezon City Seal"
                     onerror="this.style.display='none'">
            </div>
        </div>
    </div>

    <div class="splash-line"></div>

    <div class="splash-title">Barangay <span>New Era</span></div>
    <div class="splash-sub">District VI &bull; Quezon City</div>

    <div class="splash-dots">
        <span></span><span></span><span></span>
    </div>
</div>


<div id="portalToast" role="status" aria-live="polite"></div>


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


<div class="drawer-backdrop" id="drawerBackdrop" onclick="closeMobileNav()"></div>
<div class="drawer-panel"    id="drawerPanel"    role="dialog" aria-modal="true" aria-label="Navigation menu">
    <div class="drawer-head">
        <div class="portal-brand">
            <div class="brand-seal">
                <?php echo $__env->make('partials._portal_seal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
        <a href="<?php echo e(route('portal.index')); ?>"><i class="fas fa-home fa-fw"></i> Home</a>
        <a href="<?php echo e(route('portal.about')); ?>"><i class="fas fa-landmark fa-fw"></i> About Us</a>
        <a href="<?php echo e(route('portal.track')); ?>"><i class="fas fa-search fa-fw"></i> Track My Status</a>
        <a href="<?php echo e(route('portal.submissions')); ?>"><i class="fas fa-folder-open fa-fw"></i> My Submissions</a>
        <div class="drawer-divider"></div>
        <a href="<?php echo e(route('portal.request')); ?>" class="drawer-cta">
            <i class="fas fa-file-arrow-up fa-fw"></i> Request a Document
        </a>
    </nav>
</div>


<div class="gov-strip" role="banner">
    <div class="gov-strip-inner">
        <div class="gov-strip-left">
            <img src="<?php echo e(asset('images/republika-seal.png')); ?>"
                 alt="Republika ng Pilipinas"
                 class="gov-strip-seal">
            <img src="<?php echo e(asset('images/qc-seal.png')); ?>"
                 alt="Quezon City"
                 class="gov-strip-seal">
            <span class="gov-strip-name">Republika ng Pilipinas</span>
            <span class="gov-strip-divider" aria-hidden="true"></span>
            <span class="gov-strip-sub">Barangay New Era — Official Digital Services Portal</span>
        </div>
        <div class="gov-strip-right">
            <i class="fas fa-lock"></i> Secure &amp; Official Government Site
        </div>
    </div>
</div>


<header class="portal-header">

    <a href="<?php echo e(route('portal.index')); ?>" class="portal-brand">
        <div class="brand-seal">
            <?php echo $__env->make('partials._portal_seal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="portal-brand-text">
            <strong>Barangay New Era</strong>
            <span>District VI, Quezon City</span>
        </div>
    </a>

    <nav class="portal-nav" aria-label="Main navigation">
        <a href="<?php echo e(route('portal.index')); ?>" class="<?php echo e(request()->routeIs('portal.index') ? 'nav-active' : ''); ?>">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="<?php echo e(route('portal.about')); ?>" class="<?php echo e(request()->routeIs('portal.about') ? 'nav-active' : ''); ?>">
            <i class="fas fa-landmark"></i> About Us
        </a>
        <a href="<?php echo e(route('portal.track')); ?>" class="<?php echo e(request()->routeIs('portal.track*') ? 'nav-active' : ''); ?>">
            <i class="fas fa-search"></i> Track Status
        </a>
        <a href="<?php echo e(route('portal.submissions')); ?>" class="<?php echo e(request()->routeIs('portal.submissions') ? 'nav-active' : ''); ?>">
            <i class="fas fa-folder-open"></i> My Submissions
        </a>
        <a href="<?php echo e(route('portal.request')); ?>" class="nav-cta <?php echo e(request()->routeIs('portal.request') || request()->routeIs('portal.store') ? 'nav-active' : ''); ?>">
            <i class="fas fa-file-arrow-up"></i> Request Document
        </a>
    </nav>

    
    <button class="cmd-trigger" onclick="openCmdPalette()" title="Quick search (Ctrl+K)" aria-label="Open quick search">
        <i class="fas fa-search"></i>
        Quick Search
        <kbd>⌃K</kbd>
    </button>

    <button class="nav-hamburger" onclick="openMobileNav()" aria-label="Open navigation" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</header>


<main class="portal-main" id="main-content">
    <?php echo $__env->yieldContent('content'); ?>
</main>


<footer class="portal-footer" role="contentinfo">
    <div class="portal-footer-body">

        
        <div class="pf-brand">
            <img src="<?php echo e(asset('images/republika-seal.png')); ?>"
                 alt="Republika ng Pilipinas"
                 class="pf-big-seal">
            <div class="pf-brand-text">
                <div class="pf-name">Barangay New Era</div>
                <p class="pf-tagline">The official digital services portal of Barangay New Era for citizen services.</p>
                <div class="pf-sub">District VI, Quezon City<br>Metro Manila, Philippines</div>
            </div>
        </div>

        
        <div class="pf-col">
            <h5>Services</h5>
            <ul>
                <li><a href="<?php echo e(route('portal.request')); ?>?type=Barangay+Clearance">Barangay Clearance</a></li>
                <li><a href="<?php echo e(route('portal.request')); ?>?type=Certificate+of+Indigency">Cert. of Indigency</a></li>
                <li><a href="<?php echo e(route('portal.request')); ?>?type=Certificate+of+Residency">Cert. of Residency</a></li>
                <li><a href="<?php echo e(route('portal.request')); ?>?type=Business+Clearance">Business Clearance</a></li>
                <li><a href="<?php echo e(route('portal.blotter')); ?>">File Blotter Report</a></li>
                <li><a href="<?php echo e(route('portal.business')); ?>">Business Permit</a></li>
            </ul>
        </div>

        
        <div class="pf-col">
            <h5>Quick Links</h5>
            <ul>
                <li><a href="<?php echo e(route('portal.index')); ?>">Home</a></li>
                <li><a href="<?php echo e(route('portal.track')); ?>">Track My Request</a></li>
                <li><a href="<?php echo e(route('portal.submissions')); ?>">My Submissions</a></li>
                <li><a href="<?php echo e(route('portal.about')); ?>">About Us</a></li>
                <li><a href="<?php echo e(route('portal.index')); ?>#faq">FAQs</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>

        
        <div class="pf-col">
            <h5>Developed By</h5>
            <div class="pf-devby-name">AnakCo</div>
            <div class="pf-devby-sub">System Developer</div>
        </div>

    </div>
    <div class="portal-footer-bottom">
        <div class="portal-footer-bottom-inner">
            <span class="pf-copy">© <?php echo e(date('Y')); ?> Barangay New Era, District VI, Quezon City. All rights reserved.</span>
            <div class="pf-legal">
                <a href="#">Privacy Policy</a>
                <a href="<?php echo e(route('portal.about')); ?>">Contact Us</a>
            </div>
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
        { icon: 'fas fa-file-arrow-up',          label: 'Request a Document',         sub: 'Start a new barangay document request',        href: '<?php echo e(route("portal.request")); ?>' },
        { icon: 'fas fa-file-shield',        label: 'Barangay Clearance',          sub: 'Certificate of good standing',                  href: '<?php echo e(route("portal.request")); ?>?type=Barangay+Clearance' },
        { icon: 'fas fa-hand-holding-heart', label: 'Certificate of Indigency',    sub: 'For government assistance programs',            href: '<?php echo e(route("portal.request")); ?>?type=Certificate+of+Indigency' },
        { icon: 'fas fa-house-circle-check', label: 'Certificate of Residency',    sub: 'Proof of residence document',                   href: '<?php echo e(route("portal.request")); ?>?type=Certificate+of+Residency' },
        { icon: 'fas fa-store',              label: 'Business Clearance',          sub: 'For business registration and renewal',         href: '<?php echo e(route("portal.request")); ?>?type=Business+Clearance' },
        { icon: 'fas fa-gavel',              label: 'File a Blotter Report',       sub: 'Report an incident to the barangay',            href: '<?php echo e(route("portal.blotter")); ?>' },
        { icon: 'fas fa-file-contract',      label: 'Business Permit Application', sub: 'Apply for new permit or renewal',               href: '<?php echo e(route("portal.business")); ?>' },
        { icon: 'fas fa-search',             label: 'Track Appointment Status',    sub: 'Look up an existing appointment number',        href: '<?php echo e(route("portal.track")); ?>' },
        { icon: 'fas fa-home',               label: 'Portal Home',                 sub: 'Back to the main portal page',                  href: '<?php echo e(route("portal.index")); ?>' },
        { icon: 'fas fa-landmark',           label: 'About Us',                    sub: 'Officials, mission, vision & contact info',     href: '<?php echo e(route("portal.about")); ?>' },
        { icon: 'fas fa-circle-question',    label: 'Frequently Asked Questions',  sub: 'Common questions about barangay documents',     href: '<?php echo e(route("portal.index")); ?>#faq' },
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
            var trackUrl = '<?php echo e(route("portal.track")); ?>?apt=' + encodeURIComponent(q.toUpperCase());
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
    <?php if(session('success')): ?> portalToast('<?php echo e(addslashes(session('success'))); ?>', 'success'); <?php endif; ?>
    <?php if(session('error')): ?>   portalToast('<?php echo e(addslashes(session('error'))); ?>',   'error');   <?php endif; ?>
    <?php if(session('warning')): ?> portalToast('<?php echo e(addslashes(session('warning'))); ?>', 'warning'); <?php endif; ?>
});

/* ════════════════════════════════════════════════
   SPLASH SCREEN
════════════════════════════════════════════════ */
(function () {
    var splash = document.getElementById('splash');
    if (!splash) return;
    setTimeout(function () {
        splash.classList.add('splash-hide');
        setTimeout(function () { splash.remove(); }, 700);
    }, 2800);
})();
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// ── Flatpickr — init all date inputs with MM/DD/YYYY display ────────
(function () {
    var MONTHS = ['January','February','March','April','May','June',
                  'July','August','September','October','November','December'];

    // ── Shared helper: build a body-fixed picker panel ───────────────
    function buildPickerPanel(cal, anchorEl, btnClass, renderBtnFn, renderGridFn, hooksFn) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = btnClass;
        renderBtnFn(btn);

        var panel = document.createElement('div');
        panel.className = 'fp-picker-panel';
        document.body.appendChild(panel);

        function positionPanel() {
            var rect = cal.getBoundingClientRect();
            panel.style.top   = (rect.bottom - 4) + 'px';
            panel.style.left  = rect.left + 'px';
            panel.style.width = rect.width + 'px';
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            // Close any other open panels
            document.querySelectorAll('.fp-picker-panel.open').forEach(function (p) {
                if (p !== panel) p.classList.remove('open');
            });
            if (panel.classList.contains('open')) {
                panel.classList.remove('open');
                return;
            }
            renderGridFn(panel);
            positionPanel();
            panel.classList.add('open');
        });

        document.addEventListener('click', function (e) {
            if (!panel.contains(e.target) && e.target !== btn) {
                panel.classList.remove('open');
            }
        });

        // Caller supplies hook registrations
        hooksFn(btn, panel);

        anchorEl.parentNode.insertBefore(btn, anchorEl);
        return { btn: btn, panel: panel };
    }

    function buildMonthDropdown(fp) {
        var cal = fp.calendarContainer;
        var nativeSel = cal.querySelector('.flatpickr-monthDropdown-months');
        if (!nativeSel) return;

        buildPickerPanel(
            cal, nativeSel, 'fp-month-btn',
            // render button label
            function (btn) {
                btn.innerHTML = MONTHS[fp.currentMonth] +
                    ' <i class="fas fa-chevron-down" style="font-size:9px;opacity:.7;margin-left:1px"></i>';
            },
            // render grid
            function (panel) {
                panel.innerHTML = '<div class="fp-month-grid"></div>';
                var grid = panel.querySelector('.fp-month-grid');
                MONTHS.forEach(function (name, idx) {
                    var cell = document.createElement('button');
                    cell.type = 'button';
                    cell.className = 'fp-picker-cell' + (idx === fp.currentMonth ? ' current' : '');
                    cell.textContent = name.slice(0, 3);
                    cell.addEventListener('click', function (e) {
                        e.stopPropagation();
                        fp.changeMonth(idx - fp.currentMonth);
                        panel.classList.remove('open');
                    });
                    grid.appendChild(cell);
                });
            },
            // hooks
            function (btn, panel) {
                fp.config.onMonthChange.push(function () {
                    btn.innerHTML = MONTHS[fp.currentMonth] +
                        ' <i class="fas fa-chevron-down" style="font-size:9px;opacity:.7;margin-left:1px"></i>';
                    panel.classList.remove('open');
                });
                fp.config.onClose.push(function () { panel.classList.remove('open'); });
            }
        );
    }

    // ── Year: static display badge (no dropdown) ─────────────────────
    function buildYearDisplay(fp) {
        var cal         = fp.calendarContainer;
        var yearWrapper = cal.querySelector('.numInputWrapper');
        if (!yearWrapper) return;

        var badge = document.createElement('span');
        badge.className   = 'fp-year-display';
        badge.textContent = fp.currentYear;

        fp.config.onYearChange.push(function () {
            badge.textContent = fp.currentYear;
        });

        yearWrapper.parentNode.insertBefore(badge, yearWrapper);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('input[type="date"]').forEach(function (el) {
            var opts = {
                dateFormat:    'Y-m-d',
                altInput:      true,
                altFormat:     'm/d/Y',
                allowInput:    true,
                disableMobile: true,
                onReady: function (sd, ds, fp) {
                    if (fp.altInput) fp.altInput.placeholder = 'mm/dd/yyyy';
                    buildMonthDropdown(fp);
                    buildYearDisplay(fp);
                },
            };
            if (el.min) opts.minDate = el.min;
            if (el.max) opts.maxDate = el.max;
            if (el.className) opts.altInputClass = el.className;
            flatpickr(el, opts);
        });
    });
})();
</script>

<script>
// ── Custom portal select dropdowns ──────────────────────────────────
(function () {
    function buildCustomSelect(native) {
        // Wrap native select in a relative container
        var wrap = document.createElement('div');
        wrap.className = 'p-select-wrap';
        native.parentNode.insertBefore(wrap, native);
        wrap.appendChild(native);
        native.style.display = 'none'; // hidden; still submits with the form

        // Build trigger button
        var trigger = document.createElement('button');
        trigger.type = 'button';
        trigger.className = 'p-select-trigger';
        trigger.setAttribute('aria-haspopup', 'listbox');

        var valSpan = document.createElement('span');
        valSpan.className = 'p-select-val placeholder';
        var arrow = document.createElement('i');
        arrow.className = 'fas fa-chevron-down p-select-arrow';
        trigger.appendChild(valSpan);
        trigger.appendChild(arrow);
        wrap.appendChild(trigger);

        // Build dropdown panel (body-fixed)
        var panel = document.createElement('div');
        panel.className = 'p-select-panel';
        var list  = document.createElement('div');
        list.className = 'p-select-list';
        panel.appendChild(list);
        document.body.appendChild(panel);

        // Sync label from native select
        function syncLabel() {
            var sel = native.options[native.selectedIndex];
            if (sel && sel.value !== '') {
                valSpan.textContent = sel.text;
                valSpan.classList.remove('placeholder');
            } else {
                valSpan.textContent = native.options[0] ? native.options[0].text : '— Select —';
                valSpan.classList.add('placeholder');
            }
        }
        syncLabel();

        // Build item list
        function renderList() {
            list.innerHTML = '';
            Array.from(native.options).forEach(function (opt) {
                var item = document.createElement('div');
                item.className = 'p-select-item'
                    + (opt.value === '' ? ' is-placeholder' : '')
                    + (opt.value !== '' && opt.value === native.value ? ' selected' : '');
                item.textContent = opt.text;
                item.dataset.value = opt.value;
                item.addEventListener('click', function (e) {
                    e.stopPropagation();
                    native.value = opt.value;
                    native.dispatchEvent(new Event('change', { bubbles: true }));
                    syncLabel();
                    closePanel();
                    // Clear error state if any
                    trigger.classList.remove('is-invalid');
                    var errEl = document.getElementById('err-' + native.id);
                    if (errEl) { errEl.style.display = 'none'; errEl.textContent = ''; }
                });
                list.appendChild(item);
            });
        }

        function positionPanel() {
            var r = trigger.getBoundingClientRect();
            panel.style.top   = r.bottom + 'px';   // fixed = viewport coords, no scrollY
            panel.style.left  = r.left + 'px';
            panel.style.width = r.width + 'px';
        }

        function openPanel() {
            // Close any other open panels
            document.querySelectorAll('.p-select-panel.open').forEach(function (p) {
                p.classList.remove('open');
            });
            document.querySelectorAll('.p-select-trigger.open').forEach(function (t) {
                t.classList.remove('open');
            });
            renderList();
            positionPanel();
            panel.classList.add('open');
            trigger.classList.add('open');
        }
        function closePanel() {
            panel.classList.remove('open');
            trigger.classList.remove('open');
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            panel.classList.contains('open') ? closePanel() : openPanel();
        });

        document.addEventListener('click', function (e) {
            if (!panel.contains(e.target) && e.target !== trigger) closePanel();
        });

        // Expose error setter for form validation
        native._setInvalid = function (msg) { trigger.classList.add('is-invalid'); };
        native._clearInvalid = function ()  { trigger.classList.remove('is-invalid'); };
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select.form-control').forEach(buildCustomSelect);
    });
})();
</script>

<script>
// ── Scroll progress bar ─────────────────────────────────────────────
(function () {
    var bar = document.getElementById('scroll-progress');
    if (!bar) return;
    function updateProgress() {
        var scrollTop    = window.scrollY || document.documentElement.scrollTop;
        var docHeight    = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var pct          = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
        bar.style.width  = Math.min(pct, 100) + '%';
    }
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
})();
</script>
</bo<?php /**PATH D:\laragon\www\anakco_bms\resources\views/layouts/portal.blade.php ENDPATH**/ ?>