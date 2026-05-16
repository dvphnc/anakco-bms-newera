<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'BMS'); ?> — Barangay New Era</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">
    <link rel="stylesheet" href="https://unpkg.com/shepherd.js@11/dist/css/shepherd.css">

    <style>
        :root {
            --gold:          #C8861A;
            --gold-light:    #E5A020;
            --gold-pale:     #FEF3DC;
            --gold-glow:     rgba(200,134,26,0.12);
            --gold-border:   rgba(200,134,26,0.28);
            --navy:          #0D2144;
            --navy-mid:      #163160;
            --navy-light:    #1E4080;
            --navy-pale:     rgba(13,33,68,0.06);
            --navy-border:   rgba(13,33,68,0.15);
            --crimson:       #9B1C1C;
            --crimson-mid:   #9B1C1C;
            --crimson-pale:  rgba(155,28,28,0.07);
            --crimson-border:rgba(155,28,28,0.18);
            --bg:            #F3F5F8;
            --surface:       #FFFFFF;
            --surface2:      #F7F9FB;
            --surface3:      #ECF0F5;
            --border:        #DDE2EA;
            --border2:       #C8CDD8;
            --text:          #0F1924;
            --text-muted:    #4B5563;
            --text-subtle:   #9CA3AF;
            --sidebar-bg:    #0D2144;
            --sidebar-w:     270px;
            --topbar-h:      64px;
            --radius:        12px;
            --radius-sm:     8px;
            --radius-lg:     16px;
            --shadow-sm:     0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md:     0 4px 12px rgba(0,0,0,0.08);
            --shadow-gold:   0 4px 20px rgba(200,134,26,0.14);
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { height:100%; }

        body {
            font-family:'Poppins', sans-serif;
            background:var(--bg);
            color:var(--text);
            font-size:14px;
            line-height:1.6;
            display:flex;
            overflow:hidden;
            -webkit-font-smoothing:antialiased;
            -moz-osx-font-smoothing:grayscale;
            text-rendering:optimizeLegibility;
        }

        a { text-decoration:none; color:inherit; }
        button { font-family:inherit; cursor:pointer; }
        ul { list-style:none; }

        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:var(--border2); border-radius:99px; }

        .sidebar {
            width:var(--sidebar-w); height:100vh; background:var(--sidebar-bg);
            display:flex; flex-direction:column; position:fixed; top:0; left:0;
            z-index:300; overflow:hidden; transition:transform 0.3s ease;
        }
        .sidebar::before {
            content:''; position:absolute; top:-60px; left:-60px;
            width:300px; height:300px;
            background:radial-gradient(circle, rgba(200,134,26,0.07) 0%, transparent 65%);
            pointer-events:none; z-index:0;
        }
        .sidebar-brand {
            display:flex; align-items:center; gap:12px; padding:18px 18px 16px;
            border-bottom:1px solid rgba(255,255,255,0.07); flex-shrink:0; position:relative; z-index:1;
        }
        .brand-logo {
            width:44px; height:44px; border-radius:50%; overflow:hidden; flex-shrink:0;
            border:2px solid rgba(200,134,26,0.4); background:#fff; box-shadow:0 0 0 3px rgba(200,134,26,0.08);
        }
        .brand-logo img { width:100%; height:100%; object-fit:cover; display:block; }
        .brand-text h1 { font-size:13px; font-weight:700; color:#fff; line-height:1.2; letter-spacing:0.01em; }
        .brand-text span { font-size:10px; color:rgba(229,160,32,0.85); font-weight:300; }
        .sidebar-nav { flex:1; overflow-y:auto; padding:8px 0 16px; position:relative; z-index:1; }
        .nav-section-label {
            font-size:9px; font-weight:600; letter-spacing:0.15em; text-transform:uppercase;
            color:rgba(255,255,255,0.22); padding:16px 18px 5px;
        }
        .nav-item {
            display:flex; align-items:center; gap:10px; padding:8px 18px;
            color:rgba(255,255,255,0.52); font-size:13px; font-weight:400;
            border-left:3px solid transparent; transition:all 0.15s ease;
        }
        .nav-item:hover { color:rgba(255,255,255,0.88); background:rgba(255,255,255,0.04); border-left-color:rgba(200,134,26,0.45); }
        .nav-item.active { color:var(--gold-light); background:rgba(200,134,26,0.1); border-left-color:var(--gold-light); font-weight:600; }
        .nav-item i { width:16px; text-align:center; font-size:12px; flex-shrink:0; opacity:0.75; }
        .nav-item.active i { opacity:1; }
        .sidebar-footer {
            padding:0; border-top:1px solid rgba(255,255,255,0.07);
            flex-shrink:0; background:rgba(0,0,0,0.18); position:relative; z-index:1;
        }
        .sidebar-health {
            padding:9px 18px 8px; border-bottom:1px solid rgba(255,255,255,0.06);
            display:flex; flex-direction:column; gap:4px;
        }
        .sh-label {
            font-size:9px; font-weight:600; text-transform:uppercase; letter-spacing:.06em;
            color:rgba(255,255,255,0.28); margin-bottom:2px;
        }
        .sh-row { display:flex; align-items:center; gap:6px; font-size:10.5px; color:rgba(255,255,255,0.45); }
        .sh-dot {
            width:6px; height:6px; border-radius:50%; flex-shrink:0;
        }
        .sh-dot.ok    { background:#22c55e; box-shadow:0 0 5px rgba(34,197,94,.55); }
        .sh-dot.warn  { background:#f59e0b; box-shadow:0 0 5px rgba(245,158,11,.45); }
        .sh-dot.offline { background:#ef4444; box-shadow:0 0 5px rgba(239,68,68,.5); }
        .sidebar-user { display:flex; align-items:center; gap:10px; padding:10px 18px; }
        .user-avatar {
            width:33px; height:33px; border-radius:50%;
            background:linear-gradient(135deg, var(--gold), var(--gold-light));
            display:flex; align-items:center; justify-content:center;
            font-size:13px; font-weight:700; color:var(--navy); flex-shrink:0;
        }
        .user-name { font-size:13px; font-weight:600; color:#fff; line-height:1.2; }
        .user-role { font-size:10px; color:rgba(229,160,32,0.85); font-weight:300; }
        .sidebar-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:299; backdrop-filter:blur(2px); }

        .topbar {
            height:var(--topbar-h); background:var(--surface); border-bottom:1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between; padding:0 24px;
            flex-shrink:0; box-shadow:var(--shadow-sm); position:relative; z-index:100;
        }
        .topbar::after {
            content:''; position:absolute; bottom:0; left:0; right:0; height:2px;
            background:linear-gradient(90deg, var(--navy) 0%, var(--gold) 40%, transparent 75%); opacity:0.4;
        }
        .topbar-left { display:flex; align-items:center; gap:14px; }
        .menu-toggle { background:none; border:none; color:var(--text-muted); font-size:18px; padding:6px; cursor:pointer; display:none; }
        .topbar-title { font-size:15px; font-weight:700; color:var(--navy); line-height:1.2; letter-spacing:-0.01em; }
        .topbar-subtitle { font-size:11px; color:var(--text-subtle); font-weight:300; }
        .topbar-right { display:flex; align-items:center; gap:10px; }
        .topbar-date {
            display:flex; align-items:center; gap:7px; font-size:13px; color:var(--text-muted);
            background:var(--surface2); padding:6px 14px; border-radius:var(--radius-sm); border:1px solid var(--border);
        }
        .topbar-date i { color:var(--gold); font-size:12px; }
        .topbar-divider { width:1px; height:22px; background:var(--border); }
        .topbar-btn {
            display:flex; align-items:center; gap:7px; padding:7px 16px; background:var(--surface);
            border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text-muted);
            font-size:13px; font-weight:500; font-family:'Poppins',sans-serif; transition:all 0.15s; cursor:pointer;
        }
        .topbar-btn:hover { color:var(--crimson); border-color:var(--crimson-border); background:var(--crimson-pale); }

        .main-wrapper { margin-left:var(--sidebar-w); flex:1; display:flex; flex-direction:column; height:100vh; overflow:hidden; position:relative; }
        .watermark {
            position:fixed; bottom:-50px; right:-50px; width:440px; height:440px;
            background-image:url("<?php echo e(asset('images/bne-logo.png')); ?>");
            background-size:contain; background-repeat:no-repeat; background-position:center;
            opacity:0.04; pointer-events:none; z-index:0;
        }
        .main-content { flex:1; overflow-y:auto; padding:24px 28px; position:relative; z-index:1; }

        .alert { display:flex; align-items:flex-start; gap:12px; padding:14px 16px; border-radius:var(--radius); margin-bottom:20px; font-size:13.5px; }
        .alert-success { background:rgba(22,101,52,0.07); border:1px solid rgba(22,101,52,0.18); color:#14532D; }
        .alert-error   { background:var(--crimson-pale); border:1px solid var(--crimson-border); color:var(--crimson); }
        .alert-icon    { font-size:15px; flex-shrink:0; margin-top:2px; }
        .alert-message { flex:1; color:var(--text); }
        .alert-message strong { display:block; margin-bottom:4px; }
        .alert-list    { margin-top:6px; padding-left:16px; list-style:disc; }
        .alert-list li { margin-bottom:2px; font-size:13px; }
        .alert-close   { background:none; border:none; color:var(--text-subtle); font-size:14px; padding:2px; flex-shrink:0; }
        .alert-close:hover { color:var(--text); }

        .page-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; gap:16px; flex-wrap:wrap; }
        .page-title { font-size:18px; font-weight:700; color:var(--navy); line-height:1.2; letter-spacing:-0.01em; }
        .page-subtitle { font-size:12px; color:var(--text-muted); margin-top:2px; font-weight:300; }
        .page-actions  { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
        .page-breadcrumb { display:flex; align-items:center; gap:6px; font-size:13px; color:var(--text-muted); margin-top:5px; }
        .page-breadcrumb a { color:var(--text-muted); transition:color .15s; }
        .page-breadcrumb a:hover { color:var(--navy); text-decoration:underline; }
        .page-breadcrumb .bc-sep { font-size:9px; opacity:0.5; }

        .btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; min-height:44px; border-radius:var(--radius-sm); font-size:13px; font-weight:600; border:1px solid transparent; cursor:pointer; transition:all 0.15s; font-family:'Poppins',sans-serif; white-space:nowrap; }
        .btn:focus-visible { outline:2px solid var(--gold); outline-offset:2px; box-shadow:0 0 0 4px rgba(200,134,26,0.18); }
        .btn-primary:focus-visible,
        .btn-gold:focus-visible { outline-color:var(--navy); box-shadow:0 0 0 4px var(--navy-pale); }
        input[type="checkbox"]:focus-visible,
        input[type="radio"]:focus-visible { outline:2px solid var(--navy); outline-offset:2px; }
        .btn-primary   { background:var(--navy); color:#fff; border-color:var(--navy); }
        .btn-primary:hover { background:var(--navy-mid); box-shadow:0 4px 12px rgba(13,33,68,0.2); }
        .btn-gold      { background:var(--gold); color:#fff; border-color:var(--gold); }
        .btn-gold:hover { background:var(--gold-light); box-shadow:var(--shadow-gold); }
        .btn-secondary { background:var(--surface); color:var(--text); border-color:var(--border); }
        .btn-secondary:hover { border-color:var(--navy); color:var(--navy); background:var(--navy-pale); }
        .btn-danger    { background:var(--crimson-pale); color:var(--crimson); border-color:var(--crimson-border); }
        .btn-danger:hover { background:rgba(155,28,28,0.12); }
        .btn-sm   { padding:6px 13px; font-size:12px; min-height:34px; }
        .btn-icon { padding:9px; aspect-ratio:1; justify-content:center; position:relative; min-height:unset; }
        .btn-icon[title]::after {
            content: attr(title);
            position: absolute;
            bottom: calc(100% + 6px);
            left: 50%;
            transform: translateX(-50%);
            background: var(--navy);
            color: #fff;
            font-size: 11px;
            font-weight: 500;
            padding: 4px 9px;
            border-radius: var(--radius-sm);
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity .15s;
            z-index: 200;
        }
        .btn-icon[title]:hover::after { opacity: 1; }

        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-sm); }
        .card-header { display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid var(--border); background:var(--surface2); border-left:3px solid var(--gold); }
        .card-title { font-size:13px; font-weight:700; color:var(--navy); letter-spacing:0.03em; text-transform:uppercase; display:flex; align-items:center; gap:8px; }
        .card-title i { color:var(--gold); font-size:14px; }
        .card-body { padding:22px; }

        .table-responsive { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        table { width:100%; border-collapse:collapse; font-size:13px; min-width:600px; }
        thead th { padding:9px 14px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--navy); background:var(--surface2); border-bottom:2px solid var(--border2); white-space:nowrap; }
        tbody tr { border-bottom:1px solid var(--border); transition:background 0.1s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover { background:var(--navy-pale); }
        tbody td { padding:11px 14px; color:var(--text); vertical-align:middle; font-size:13px; line-height:1.55; }
        .td-muted { color:var(--text-muted); font-size:12px; line-height:1.5; }
        .td-mono  { font-family:'Courier New',monospace; color:var(--text-muted); font-size:12px; }

        .badge { display:inline-flex; align-items:center; padding:4px 11px; border-radius:99px; font-size:12px; font-weight:600; white-space:nowrap; letter-spacing:0.02em; }
        .badge-green  { background:rgba(22,101,52,0.1);  color:#14532D; }
        .badge-red    { background:var(--crimson-pale);  color:var(--crimson); }
        .badge-blue   { background:var(--navy-pale);     color:var(--navy); }
        .badge-yellow { background:var(--gold-glow);     color:#7A4F0A; }
        .badge-orange { background:rgba(194,65,12,0.08); color:#9A3412; }
        .badge-gray   { background:var(--surface3);      color:var(--text-muted); }
        .badge-gold   { background:var(--gold-pale);     color:#92600A; }
        .badge-navy   { background:var(--navy-pale);     color:var(--navy); }

        .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
        .form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:18px; }
        .form-group  { display:flex; flex-direction:column; gap:7px; }
        .form-label { font-size:13px; font-weight:600; color:var(--text-muted); display:flex; align-items:center; gap:5px; }
        .form-control { width:100%; padding:11px 14px; min-height:48px; background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-sm); color:var(--text); font-size:14px; font-family:'Poppins',sans-serif; outline:none; transition:border-color 0.15s, box-shadow 0.15s; }
        .form-control:focus,
        .form-control:focus-visible { border-color:var(--navy); box-shadow:0 0 0 3px var(--navy-pale); outline:none; }
        .form-control::placeholder { color:var(--text-subtle); font-weight:300; }
        select.form-control option { background:var(--surface); color:var(--text); }
        textarea.form-control { resize:vertical; min-height:100px; }
        .form-check { display:flex; align-items:center; gap:9px; cursor:pointer; font-size:14px; color:var(--text); line-height:1.5; }
        .form-check input[type="checkbox"] { width:17px; height:17px; accent-color:var(--navy); cursor:pointer; flex-shrink:0; }
        .form-section-title { font-size:13px; font-weight:700; color:var(--navy); letter-spacing:0.04em; text-transform:uppercase; padding-bottom:10px; border-bottom:1px solid var(--gold-border); margin-bottom:18px; }
        .form-actions { display:flex; align-items:center; gap:10px; margin-top:24px; padding-top:20px; border-top:1px solid var(--border); flex-wrap:wrap; }

        .stat-card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); padding:22px; display:flex; align-items:center; gap:18px; box-shadow:var(--shadow-sm); transition:border-color 0.2s, box-shadow 0.2s, transform 0.15s; }
        .stat-card:hover { border-color:var(--gold-border); box-shadow:var(--shadow-gold); transform:translateY(-2px); }
        .stat-icon { width:52px; height:52px; border-radius:var(--radius); display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
        .stat-info { flex:1; min-width:0; }
        .stat-number { font-size:30px; font-weight:700; color:var(--navy); line-height:1; }
        .stat-label  { font-size:13px; color:var(--text-muted); margin-top:4px; font-weight:400; }

        .grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }
        .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
        .mb-4 { margin-bottom:16px; }
        .mb-6 { margin-bottom:24px; }
        .mb-8 { margin-bottom:32px; }
        .mt-6 { margin-top:24px; }

        .tab-nav { display:flex; gap:2px; background:var(--surface2); padding:4px; border-radius:var(--radius); border:1px solid var(--border); flex-wrap:wrap; margin-bottom:22px; }
        .tab-btn { padding:7px 16px; border-radius:var(--radius-sm); font-size:13px; font-weight:500; color:var(--text-muted); background:none; border:none; cursor:pointer; transition:all 0.15s; font-family:'Poppins',sans-serif; }
        .tab-btn:hover { color:var(--navy); }
        .tab-btn.active { background:var(--surface); color:var(--navy); font-weight:700; border:1px solid var(--border); box-shadow:var(--shadow-sm); }
        .tab-pane { display:none; }
        .tab-pane.active { display:block; }

        .empty-state { text-align:center; padding:52px 24px; color:var(--text-muted); }
        .empty-state i { font-size:42px; opacity:0.15; display:block; margin-bottom:16px; color:var(--navy); }
        .empty-state p { font-size:15px; font-weight:300; line-height:1.7; }
        .empty-state a { color:var(--navy); font-weight:600; text-decoration:underline; }

        .filter-bar { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; }
        .filter-bar .form-group { min-width:150px; }
        .filter-bar .form-group.flex-1 { flex:1; min-width:200px; }

        .progress-bar-wrap { background:var(--surface3); border-radius:99px; height:7px; overflow:hidden; }
        .progress-bar { height:100%; border-radius:99px; transition:width 0.7s ease; }

        .gold-rule { height:1px; background:linear-gradient(90deg, var(--gold-border), transparent); margin:20px 0; }

        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .sidebar, .topbar, .main-wrapper > .watermark { display: none !important; }
            .main-content { padding: 0 !important; }
        }
        .print-only { display: none; }

        @media (max-width: 1100px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            body { overflow:auto; }
            .sidebar { transform: translateX(-100%); box-shadow: none; }
            .sidebar.sidebar-open { transform: translateX(0); box-shadow: 4px 0 30px rgba(0,0,0,0.35); }
            .sidebar-overlay.active { display: block; }
            .main-wrapper { margin-left: 0; height: auto; min-height: 100vh; overflow: visible; }
            .topbar { padding: 0 16px; position: sticky; top: 0; }
            .menu-toggle { display: flex; }
            .topbar-date { display: none; }
            .topbar-divider { display: none; }
            .topbar-subtitle { display: none; }
            .main-content { padding: 16px; overflow: visible; }
            .grid-4 { grid-template-columns: 1fr 1fr; gap: 12px; }
            .grid-3 { grid-template-columns: 1fr; gap: 12px; }
            .grid-2 { grid-template-columns: 1fr; gap: 12px; }
            .form-grid-2 { grid-template-columns: 1fr; }
            .form-grid-3 { grid-template-columns: 1fr; }
            .stat-card { padding: 14px; }
            .stat-number { font-size: 22px; }
            .stat-icon { width: 40px; height: 40px; font-size: 16px; }
            .page-header { flex-direction: column; }
            .page-title { font-size: 18px; }
            .card-header { flex-wrap: wrap; gap: 8px; }
            .filter-bar .form-group { min-width: 100%; }
            [style*="grid-template-columns:300px"],
            [style*="grid-template-columns:280px"] { display: flex !important; flex-direction: column !important; }
            .table-responsive { overflow-x: auto; }
        }

        @media (max-width: 480px) {
            .grid-4 { grid-template-columns: 1fr; }
            .page-actions { width: 100%; }
            .page-actions .btn { flex: 1; justify-content: center; }
            .topbar-title { font-size: 15px; }
        }

        /* =============================================
           FORM ERROR HIGHLIGHTING
        ============================================= */
        .is-invalid {
            border-color: var(--crimson) !important;
            box-shadow: 0 0 0 3px rgba(155,28,28,0.1) !important;
        }
        .invalid-feedback {
            font-size: 12px;
            color: var(--crimson);
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .form-control.is-invalid:focus {
            border-color: var(--crimson) !important;
            box-shadow: 0 0 0 3px rgba(155,28,28,0.12) !important;
        }
        .is-valid {
            border-color: #16a34a !important;
        }

        /* =============================================
           SELECT2 — Matches BMS form-control exactly
        ============================================= */
        .select2-container { width: 100% !important; }
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            background: var(--surface) !important;
            padding: 0 32px 0 13px !important;
            display: flex !important;
            align-items: center !important;
            font-family: 'Poppins', sans-serif !important;
            font-size: 14px !important;
            color: var(--text) !important;
            transition: border-color 0.15s, box-shadow 0.15s !important;
            box-shadow: none !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--navy) !important;
            box-shadow: 0 0 0 3px var(--navy-pale) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--text) !important;
            line-height: normal !important;
            padding: 0 !important;
            font-size: 14px !important;
            font-family: 'Poppins', sans-serif !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: var(--text-subtle) !important;
            font-weight: 300 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important; width: 28px !important; right: 4px !important; top: 0 !important; position: absolute !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--text-subtle) transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent var(--text-subtle) transparent !important;
            border-width: 0 4px 5px 4px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear {
            font-size: 16px !important; color: var(--text-muted) !important;
            margin-right: 6px !important; font-weight: 400 !important; line-height: 1 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear:hover { color: var(--crimson) !important; }
        .select2-dropdown {
            border: 1px solid var(--border) !important; border-radius: var(--radius-sm) !important;
            box-shadow: var(--shadow-md) !important; font-family: 'Poppins', sans-serif !important;
            font-size: 13px !important; z-index: 9999 !important; background: var(--surface) !important;
        }
        .select2-container--default .select2-search--dropdown {
            padding: 8px !important; border-bottom: 1px solid var(--border) !important; background: var(--surface2) !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--border) !important; border-radius: var(--radius-sm) !important;
            padding: 7px 10px !important; font-family: 'Poppins', sans-serif !important;
            font-size: 12.5px !important; color: var(--text) !important; outline: none !important;
            width: 100% !important; background: var(--surface) !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--navy) !important; box-shadow: 0 0 0 3px var(--navy-pale) !important;
        }
        /* Always show the search box — override Select2's hide mechanism */
        .select2-search--dropdown.select2-search--hide {
            display: block !important;
        }
        .select2-search--dropdown.select2-search--hide .select2-search__field {
            display: block !important;
        }
        .select2-results__options { max-height: 240px !important; overflow-y: auto !important; }
        .select2-container--default .select2-results__option {
            padding: 8px 12px !important; font-size: 12.5px !important;
            color: var(--text) !important; font-family: 'Poppins', sans-serif !important; line-height: 1.4 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] { background: var(--navy) !important; color: #fff !important; }
        .select2-container--default .select2-results__option[aria-selected=true] { background: var(--navy-pale) !important; color: var(--navy) !important; font-weight: 600 !important; }
        .select2-results__message, .select2-container--default .select2-results__option--disabled {
            color: var(--text-muted) !important; font-size: 12px !important;
            padding: 10px 12px !important; text-align: center !important; font-style: italic !important;
        }

        /* ── Select2 Multi-select ─────────────────────────────────────── */
        .select2-container--default .select2-selection--multiple {
            min-height: 38px !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-sm) !important;
            background: var(--surface) !important;
            padding: 3px 8px !important;
            cursor: pointer !important;
            transition: border-color .15s, box-shadow .15s !important;
            box-shadow: none !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple,
        .select2-container--default.select2-container--open .select2-selection--multiple {
            border-color: var(--navy) !important;
            box-shadow: 0 0 0 3px var(--navy-pale) !important;
            outline: none !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            padding: 0 !important; display: flex !important; flex-wrap: wrap !important;
            align-items: center !important; gap: 3px !important; min-height: 30px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__placeholder {
            color: var(--text-subtle) !important; font-weight: 300 !important;
            font-size: 14px !important; font-family: 'Poppins', sans-serif !important;
            padding: 0 4px !important; line-height: 30px !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--navy) !important; border: none !important;
            border-radius: 4px !important; color: #fff !important;
            font-size: 12px !important; font-weight: 500 !important;
            padding: 2px 6px 2px 8px !important; margin: 0 !important;
            display: inline-flex !important; align-items: center !important; gap: 5px !important;
            line-height: 1.4 !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255,255,255,.55) !important; font-size: 14px !important;
            font-weight: 300 !important; background: none !important; border: none !important;
            padding: 0 !important; margin: 0 !important; line-height: 1 !important;
            order: 2 !important; cursor: pointer !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff !important; background: transparent !important;
        }
        .select2-container--default .select2-selection--multiple .select2-search--inline .select2-search__field {
            font-family: 'Poppins', sans-serif !important; font-size: 14px !important;
            color: var(--text) !important; margin: 2px 0 !important; min-width: 60px !important;
        }
        /* No-results friendly message */
        .select2-container--default .select2-results__option.select2-results__message {
            display: flex !important; flex-direction: column !important;
            align-items: center !important; gap: 4px !important;
            padding: 18px 12px !important; font-style: normal !important; font-size: 12.5px !important;
        }

        /* ── Select2 height sync with 48px targets ─────────────────── */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            padding: 0 36px 0 14px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .select2-container--default .select2-selection--multiple {
            min-height: 48px !important;
        }

        /* ── Help / Tooltip icon ──────────────────────────────────── */
        .help-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 18px; height: 18px; border-radius: 50%;
            background: var(--navy-pale); color: var(--navy);
            font-size: 10px; font-weight: 700; cursor: help;
            border: 1px solid var(--navy-border); flex-shrink: 0;
            vertical-align: middle; transition: background .15s;
        }
        .help-icon:hover { background: var(--navy); color: #fff; }

        /* ── Chart insight strip ──────────────────────────────────── */
        .chart-insight {
            margin-top: 12px; padding: 10px 14px;
            background: var(--surface2); border-radius: var(--radius-sm);
            border-left: 3px solid var(--gold);
            font-size: 13px; color: var(--text-muted); line-height: 1.6;
        }
        .chart-insight strong { color: var(--navy); }

        /* ── Onboarding tour override (Shepherd) ──────────────────── */
        .shepherd-element { font-family: 'Poppins', sans-serif !important; z-index: 10000 !important; }
        .shepherd-content { border-radius: var(--radius-lg) !important; border: 1px solid var(--border) !important; box-shadow: 0 20px 60px rgba(0,0,0,0.18) !important; padding: 0 !important; overflow: hidden !important; }
        .shepherd-header { background: var(--navy) !important; padding: 16px 20px !important; }
        .shepherd-title { font-size: 15px !important; font-weight: 700 !important; color: #fff !important; }
        .shepherd-cancel-icon { color: rgba(255,255,255,.6) !important; font-size: 18px !important; }
        .shepherd-text { padding: 18px 20px !important; font-size: 14px !important; color: var(--text-muted) !important; line-height: 1.7 !important; }
        .shepherd-footer { padding: 12px 20px 16px !important; border-top: 1px solid var(--border) !important; display: flex !important; justify-content: space-between !important; gap: 10px !important; }
        .shepherd-button { font-family: 'Poppins', sans-serif !important; font-size: 13px !important; font-weight: 600 !important; border-radius: var(--radius-sm) !important; padding: 8px 18px !important; cursor: pointer !important; border: none !important; min-height: 38px !important; }
        .shepherd-button-primary { background: var(--navy) !important; color: #fff !important; }
        .shepherd-button-primary:hover { background: var(--navy-mid) !important; }
        .shepherd-button-secondary { background: var(--surface) !important; color: var(--text-muted) !important; border: 1px solid var(--border) !important; }
        .shepherd-has-cancel-icon .shepherd-cancel-icon { color: rgba(255,255,255,.7) !important; background: none !important; }
        .shepherd-modal-overlay-container { z-index: 9999 !important; }

        /* ── Alert item: bump text sizes ─────────────────────────── */
        .alert { font-size: 14px; }
        .alert-list li { font-size: 13.5px; }

        /* ── Validation feedback upgrade ──────────────────────────── */
        .invalid-feedback { font-size: 13px; }

    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <?php echo $__env->make('partials._sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main-wrapper">
        <div class="watermark"></div>
        <?php echo $__env->make('partials._topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <main class="main-content">
            <?php echo $__env->make('partials._alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('sidebar-open');
            document.getElementById('sidebarOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('sidebar-open');
            document.getElementById('sidebarOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }
        const menuToggle = document.getElementById('menuToggle');
        if (menuToggle) { menuToggle.addEventListener('click', openSidebar); }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy.umd.min.js"></script>
    <script src="https://unpkg.com/shepherd.js@11/dist/js/shepherd.min.js"></script>
    <script>
    $(document).ready(function() {
        $('body').on('init.select2', function() {});

        /* ── Force search visible in every Select2 dropdown on open ──────── */
        $(document).on('select2:open', function () {
            // setTimeout 0 lets Select2 finish hiding before we override
            setTimeout(function () {
                var wrap = document.querySelector(
                    '.select2-container--open .select2-search--dropdown'
                );
                if (!wrap) return;
                wrap.classList.remove('select2-search--hide');
                wrap.style.removeProperty('display');
                var field = wrap.querySelector('.select2-search__field');
                if (field) {
                    field.style.removeProperty('display');
                    field.focus();
                }
            }, 0);
        });

        $('.select2-resident').each(function() {
            if ($(this).data('select2')) return;
            $(this).select2({
                ajax: {
                    url: '/select2/residents',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) { return { q: params.term }; },
                    processResults: function(data) { return { results: data.results }; },
                    cache: true,
                    error: function() {
                        var el = document.createElement('div');
                        el.className = 'alert alert-danger';
                        el.style.cssText = 'position:fixed;top:72px;right:16px;z-index:9999;min-width:300px;box-shadow:0 4px 16px rgba(0,0,0,0.15)';
                        el.innerHTML = '<i class="fas fa-exclamation-circle"></i> Resident search failed. Check your connection and try again.';
                        document.body.appendChild(el);
                        setTimeout(function() { el.remove(); }, 5000);
                    }
                },
                minimumInputLength: 1,
                placeholder: $(this).data('placeholder') || 'Type to search resident...',
                allowClear: true,
                language: {
                    inputTooShort: function() { return 'Type at least 1 character to search...'; },
                    searching: function() { return 'Searching...'; },
                    noResults: function() { return 'No residents found'; }
                }
            });
        });
    });
    </script>

    <script>
    // ── Tippy.js: init all [data-tippy-content] and .help-icon ──────────
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof tippy !== 'undefined') {
            tippy('[data-tippy-content]', {
                theme: 'bms',
                placement: 'top',
                arrow: true,
                maxWidth: 280,
                interactive: false,
                appendTo: document.body,
            });
        }
    });
    </script>
    <style>
    /* Tippy BMS theme */
    .tippy-box[data-theme~='bms'] {
        background: var(--navy);
        color: #fff;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        line-height: 1.6;
        border-radius: var(--radius-sm);
        box-shadow: 0 8px 24px rgba(0,0,0,0.18);
    }
    .tippy-box[data-theme~='bms'] .tippy-arrow { color: var(--navy); }
    .tippy-box[data-theme~='bms'] .tippy-content { padding: 8px 12px; }
    </style>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <div id="bmsConfirmModal"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.45);z-index:9990;
                align-items:center;justify-content:center;backdrop-filter:blur(2px)">
        <div style="background:var(--surface);border-radius:var(--radius-lg);padding:28px 28px 24px;
                    max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.22);
                    border:1px solid var(--border)">
            <div style="display:flex;gap:16px;margin-bottom:20px;align-items:flex-start">
                <div id="bmsConfirmIcon"
                     style="width:46px;height:46px;border-radius:var(--radius);flex-shrink:0;
                            background:var(--crimson-pale);display:flex;align-items:center;
                            justify-content:center">
                    <i id="bmsConfirmIconI" class="fas fa-triangle-exclamation"
                       style="color:var(--crimson);font-size:20px"></i>
                </div>
                <div>
                    <div id="bmsConfirmTitle"
                         style="font-size:15px;font-weight:700;color:var(--text);margin-bottom:5px">
                        Confirm Action
                    </div>
                    <div id="bmsConfirmMsg"
                         style="font-size:13px;color:var(--text-muted);line-height:1.5">
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button onclick="bmsConfirmCancel()" class="btn btn-secondary">Cancel</button>
                <button id="bmsConfirmOk" class="btn btn-danger">
                    <i id="bmsConfirmOkIcon" class="fas fa-trash"></i>
                    <span id="bmsConfirmOkText">Delete</span>
                </button>
            </div>
        </div>
    </div>

    <script>
    // ── Axios: CSRF + session-expiry interceptor ─────────────────────────
    (function () {
        const match = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
        if (match) axios.defaults.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(match[1]);
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        axios.defaults.headers.common['Accept'] = 'application/json';
        axios.interceptors.response.use(null, function (error) {
            if (error.response && (error.response.status === 401 || error.response.status === 419)) {
                window.location.href = '/login';
            }
            return Promise.reject(error);
        });
    })();

    // ── Toast notification ───────────────────────────────────────────────
    function bmsToast(message, type) {
        type = type || 'success';
        var palettes = {
            success: { bg:'#f0fdf4', border:'#86efac', text:'#14532d', icon:'fa-check-circle' },
            error:   { bg:'#fef2f2', border:'#fca5a5', text:'#7f1d1d', icon:'fa-exclamation-circle' },
            warning: { bg:'#fffbeb', border:'#fcd34d', text:'#78350f', icon:'fa-triangle-exclamation' },
        };
        var c = palettes[type] || palettes.success;
        var el = document.createElement('div');
        el.style.cssText = 'position:fixed;top:76px;right:20px;z-index:9998;min-width:300px;max-width:420px;' +
            'padding:13px 16px;border-radius:var(--radius);border:1px solid ' + c.border + ';' +
            'background:' + c.bg + ';color:' + c.text + ';font-size:14px;font-weight:500;' +
            'box-shadow:0 6px 24px rgba(0,0,0,0.13);display:flex;align-items:center;gap:10px;' +
            "font-family:'Poppins',sans-serif;animation:toastSlideIn .22s ease;";
        el.innerHTML = '<i class="fas ' + c.icon + '" style="flex-shrink:0;font-size:16px"></i>' +
                       '<span style="flex:1;line-height:1.45">' + message + '</span>' +
                       '<button onclick="this.parentNode.remove()" style="background:none;border:none;cursor:pointer;' +
                       'color:inherit;opacity:.5;font-size:14px;padding:0 0 0 8px;line-height:1">' +
                       '<i class="fas fa-times"></i></button>';
        document.body.appendChild(el);
        setTimeout(function () {
            el.style.transition = 'opacity .3s,transform .3s';
            el.style.opacity = '0';
            el.style.transform = 'translateX(16px)';
            setTimeout(function () { el.remove(); }, 350);
        }, 4500);
    }

    // ── Global Confirmation Modal ────────────────────────────────────────
    let _bmsCallback = null;

    function bmsConfirmCancel() {
        document.getElementById('bmsConfirmModal').style.display = 'none';
        _bmsCallback = null;
    }

    document.getElementById('bmsConfirmOk').addEventListener('click', function () {
        bmsConfirmCancel();
        if (_bmsCallback) _bmsCallback();
    });

    document.getElementById('bmsConfirmModal').addEventListener('click', function (e) {
        if (e.target === this) bmsConfirmCancel();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') bmsConfirmCancel();
    });

    // Programmatic confirm — for Axios-driven actions that need a confirmation dialog
    function bmsConfirm(options, onConfirm) {
        document.getElementById('bmsConfirmTitle').textContent     = options.title   || 'Confirm Action';
        document.getElementById('bmsConfirmMsg').textContent       = options.message || '';
        document.getElementById('bmsConfirmOkText').textContent    = options.ok      || 'Confirm';
        var isDanger = options.type !== 'safe';
        document.getElementById('bmsConfirmOkIcon').className      = 'fas ' + (isDanger ? 'fa-trash' : 'fa-check');
        document.getElementById('bmsConfirmOk').className          = isDanger ? 'btn btn-danger' : 'btn btn-primary';
        document.getElementById('bmsConfirmIcon').style.background = isDanger ? 'var(--crimson-pale)' : 'var(--navy-pale)';
        document.getElementById('bmsConfirmIconI').style.color     = isDanger ? 'var(--crimson)' : 'var(--navy)';
        document.getElementById('bmsConfirmIconI').className       = 'fas ' + (isDanger ? 'fa-triangle-exclamation' : 'fa-circle-info');
        document.getElementById('bmsConfirmModal').style.display   = 'flex';
        _bmsCallback = onConfirm;
    }

    // data-confirm="..." on <form> elements — intercept submit
    document.addEventListener('submit', function (e) {
        const msg = e.target.dataset.confirm;
        if (!msg || e.target.dataset.confirmed === '1') return;
        e.preventDefault();
        const isDanger    = !e.target.dataset.confirmType || e.target.dataset.confirmType === 'danger';
        const btnLabel    = e.target.dataset.confirmOk   || 'Confirm';
        const titleLabel  = e.target.dataset.confirmTitle || 'Confirm Action';
        const iconClass   = e.target.dataset.confirmIcon  || (isDanger ? 'fa-trash' : 'fa-check');
        const iconColor   = isDanger ? 'var(--crimson)' : 'var(--navy)';
        const iconBg      = isDanger ? 'var(--crimson-pale)' : 'var(--navy-pale)';
        document.getElementById('bmsConfirmTitle').textContent      = titleLabel;
        document.getElementById('bmsConfirmMsg').textContent        = msg;
        document.getElementById('bmsConfirmOkText').textContent     = btnLabel;
        document.getElementById('bmsConfirmOkIcon').className       = 'fas ' + iconClass;
        document.getElementById('bmsConfirmIcon').style.background  = iconBg;
        document.getElementById('bmsConfirmIconI').style.color      = iconColor;
        document.getElementById('bmsConfirmIconI').className        = 'fas ' + (isDanger ? 'fa-triangle-exclamation' : 'fa-circle-info');
        document.getElementById('bmsConfirmOk').className           = isDanger ? 'btn btn-danger' : 'btn btn-primary';
        document.getElementById('bmsConfirmModal').style.display    = 'flex';
        const form = e.target;
        _bmsCallback = function () {
            form.dataset.confirmed = '1';
            form.submit();
        };
    });

    // ── Double-submit prevention ────────────────────────────────────────
    document.addEventListener('submit', function(e) {
        const form = e.target;
        // Skip: already confirmed (confirm modal re-submits), or opted out
        if (form.dataset.confirmed === '1' || form.dataset.noDisable) return;
        const btn = form.querySelector('button[type="submit"]');
        if (!btn) return;
        btn.disabled = true;
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving…';
    }, true);
    // Re-enable on back-button restore (bfcache)
    window.addEventListener('pageshow', function(e) {
        if (e.persisted) {
            document.querySelectorAll('button[type="submit"]').forEach(function(btn) {
                btn.disabled = false;
                if (btn.dataset.originalHtml) btn.innerHTML = btn.dataset.originalHtml;
            });
        }
    });

    // ── Alert auto-dismiss (success/warning only — errors stay) ─────────
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.alert-success, .alert-warning').forEach(function (el) {
            const bar = document.createElement('div');
            bar.style.cssText = 'position:absolute;bottom:0;left:0;height:2px;background:currentColor;opacity:0.3;border-radius:0 0 var(--radius) var(--radius);width:100%;transform-origin:left;animation:alertShrink 5s linear forwards';
            el.style.position = 'relative';
            el.style.overflow = 'hidden';
            el.appendChild(bar);
            setTimeout(function () {
                el.style.transition = 'opacity 0.4s, max-height 0.4s, margin 0.4s, padding 0.4s';
                el.style.opacity = '0';
                el.style.maxHeight = '0';
                el.style.marginBottom = '0';
                el.style.paddingTop = '0';
                el.style.paddingBottom = '0';
                setTimeout(() => el.remove(), 450);
            }, 5000);
        });
    });
    </script>
    <style>
    @keyframes alertShrink  { from { width:100%; } to { width:0%; } }
    @keyframes toastSlideIn { from { opacity:0; transform:translateX(16px); } to { opacity:1; transform:translateX(0); } }
    .alert-warning { background:var(--gold-pale); border:1px solid var(--gold-border); color:#78450a; }
    </style>

    
    <div id="cmdPalette"
         role="dialog" aria-modal="true" aria-label="Command palette"
         style="display:none;position:fixed;inset:0;z-index:10500;
                background:rgba(9,20,40,0.65);backdrop-filter:blur(5px);
                align-items:flex-start;justify-content:center;
                padding-top:clamp(60px,10vh,120px)">
        <div id="cmdBox"
             style="background:var(--surface);border-radius:var(--radius-lg);
                    width:90%;max-width:620px;border:1px solid var(--border2);
                    box-shadow:0 32px 80px rgba(0,0,0,0.32);overflow:hidden;
                    animation:cmdSlideDown .18s ease">

            
            <div style="display:flex;align-items:center;gap:12px;
                        padding:16px 20px;border-bottom:1px solid var(--border)">
                <i id="cmdSpinner" class="fas fa-search"
                   style="color:var(--gold);font-size:16px;flex-shrink:0;width:18px;text-align:center"></i>
                <input id="cmdInput" type="text"
                       placeholder="Search residents, documents, blotter, businesses…"
                       autocomplete="off" spellcheck="false"
                       style="flex:1;border:none;outline:none;font-size:16px;
                              font-family:'Poppins',sans-serif;color:var(--text);
                              background:transparent;caret-color:var(--gold)">
                <kbd style="background:var(--surface3);border:1px solid var(--border);
                            border-radius:6px;padding:2px 9px;font-size:11px;
                            color:var(--text-subtle);font-family:monospace;
                            flex-shrink:0;cursor:pointer"
                     onclick="closeCmdPalette()">Esc</kbd>
            </div>

            
            <div id="cmdResults" style="max-height:420px;overflow-y:auto"></div>

            
            <div style="padding:9px 18px;border-top:1px solid var(--border);
                        background:var(--surface2);display:flex;flex-wrap:wrap;
                        gap:14px;align-items:center">
                <span class="cmd-hint"><kbd>↑↓</kbd> Navigate</span>
                <span class="cmd-hint"><kbd>↵</kbd> Open</span>
                <span class="cmd-hint"><kbd>Esc</kbd> Close</span>
                <span style="flex:1"></span>
                <span style="font-size:11px;color:var(--text-subtle);display:flex;align-items:center;gap:5px">
                    <kbd style="background:var(--navy);color:#fff;border:none;
                                border-radius:4px;padding:1px 6px;font-family:monospace;font-size:10px">Ctrl</kbd>
                    <kbd style="background:var(--navy);color:#fff;border:none;
                                border-radius:4px;padding:1px 6px;font-family:monospace;font-size:10px">K</kbd>
                    to open anywhere
                </span>
            </div>
        </div>
    </div>

    <style>
    @keyframes cmdSlideDown {
        from { opacity:0; transform:translateY(-12px) scale(0.98); }
        to   { opacity:1; transform:translateY(0) scale(1); }
    }
    .cmd-hint {
        font-size:11px; color:var(--text-subtle);
        display:flex; align-items:center; gap:4px;
    }
    .cmd-hint kbd {
        background:var(--surface); border:1px solid var(--border);
        border-radius:4px; padding:1px 6px;
        font-family:monospace; font-size:11px;
    }
    .cmd-section {
        padding:8px 18px 4px;
        font-size:10px; font-weight:700; letter-spacing:0.12em;
        text-transform:uppercase; color:var(--text-subtle);
        background:var(--surface2); border-bottom:1px solid var(--border);
    }
    .cmd-item {
        display:flex; align-items:center; gap:12px;
        padding:11px 18px; cursor:pointer;
        transition:background .1s; text-decoration:none;
        color:inherit; border-bottom:1px solid var(--border);
    }
    .cmd-item:last-child { border-bottom:none; }
    .cmd-item.cmd-selected,
    .cmd-item:hover { background:var(--navy-pale); }
    .cmd-item.cmd-selected .cmd-item-title { color:var(--navy); }
    .cmd-item-icon {
        width:34px; height:34px; border-radius:var(--radius-sm);
        display:flex; align-items:center; justify-content:center;
        font-size:13px; flex-shrink:0;
    }
    .cmd-item-title {
        font-size:14px; font-weight:600; color:var(--text);
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .cmd-item-sub {
        font-size:12px; color:var(--text-muted);
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .cmd-empty {
        padding:40px 24px; text-align:center;
        font-size:14px; color:var(--text-muted);
    }
    .cmd-empty i { font-size:32px; opacity:.12; display:block; margin-bottom:12px; color:var(--navy); }
    .cmd-loading {
        padding:24px; text-align:center; font-size:13px; color:var(--text-muted);
    }
    </style>

    <script>
    // ══ Ctrl+K Command Palette ═══════════════════════════════════════════
    (function () {
        const palette   = document.getElementById('cmdPalette');
        const cmdBox    = document.getElementById('cmdBox');
        const cmdInput  = document.getElementById('cmdInput');
        const cmdResults= document.getElementById('cmdResults');
        const cmdSpinner= document.getElementById('cmdSpinner');
        let searchTimer = null;
        let activeIdx   = -1;
        let resultLinks = [];

        // ── Quick actions shown when palette opens with empty input ──────
        const quickActions = [
            <?php if(auth()->guard()->check()): ?>
            { title:'Add New Resident',  sub:'Create a resident record',    url:'<?php echo e(route("residents.create")); ?>',   icon:'fa-user-plus',   color:'var(--navy)' },
            { title:'Issue Document',    sub:'Barangay clearance, indigency…',url:'<?php echo e(route("documents.create")); ?>',  icon:'fa-file-circle-plus',color:'var(--gold)' },
            { title:'File Blotter Case', sub:'Record an incident or complaint',url:'<?php echo e(route("blotter.create")); ?>',   icon:'fa-gavel',       color:'#9B1C1C' },
            { title:'Register Business', sub:'Add a business permit record',  url:'<?php echo e(route("businesses.create")); ?>', icon:'fa-store',       color:'#166534' },
            { title:'View Dashboard',    sub:'Overview, analytics, appointments',url:'<?php echo e(route("dashboard")); ?>',      icon:'fa-gauge-high',  color:'var(--navy)' },
            { title:'Reports & Analytics',sub:'Population, demographics, services',url:'<?php echo e(route("reports.index")); ?>',icon:'fa-chart-bar',   color:'var(--gold)' },
            { title:'Manage Users',      sub:'Accounts, roles, access control',url:'<?php echo e(route("users.index")); ?>',      icon:'fa-users-gear',  color:'var(--navy)' },
            <?php endif; ?>
        ];

        window.openCmdPalette = function () {
            palette.style.display = 'flex';
            cmdInput.value = '';
            activeIdx = -1;
            renderQuickActions();
            requestAnimationFrame(() => cmdInput.focus());
        };

        window.closeCmdPalette = function () {
            palette.style.display = 'none';
            clearTimeout(searchTimer);
        };

        function renderQuickActions() {
            let html = '<div class="cmd-section">Quick Actions</div>';
            quickActions.forEach((a, i) => {
                html += `<a href="${a.url}" class="cmd-item" data-idx="${i}">
                    <div class="cmd-item-icon" style="background:${a.color}18;color:${a.color}">
                        <i class="fas ${a.icon}"></i>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div class="cmd-item-title">${a.title}</div>
                        <div class="cmd-item-sub">${a.sub}</div>
                    </div>
                    <i class="fas fa-arrow-right" style="color:var(--text-subtle);font-size:11px;opacity:0.4"></i>
                </a>`;
            });
            cmdResults.innerHTML = html;
            indexItems();
        }

        function renderLoading() {
            cmdResults.innerHTML = '<div class="cmd-loading"><i class="fas fa-spinner fa-spin" style="margin-right:8px;color:var(--gold)"></i>Searching…</div>';
        }

        function renderSearchResults(data) {
            if (!data.results || data.results.length === 0) {
                cmdResults.innerHTML = `<div class="cmd-empty"><i class="fas fa-magnifying-glass"></i>No results for "<strong>${data.query}</strong>"</div>`;
                indexItems(); return;
            }
            const groups = {};
            data.results.forEach(r => { if (!groups[r.type]) groups[r.type] = []; groups[r.type].push(r); });
            let html = '';
            let idx = 0;
            for (const [type, items] of Object.entries(groups)) {
                html += `<div class="cmd-section">${type}s</div>`;
                items.forEach(item => {
                    html += `<a href="${item.url}" class="cmd-item" data-idx="${idx++}">
                        <div class="cmd-item-icon" style="background:${item.color}18;color:${item.color}">
                            <i class="fas ${item.icon}"></i>
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="cmd-item-title">${item.title}</div>
                            <div class="cmd-item-sub">${item.subtitle}</div>
                        </div>
                        <span class="badge ${item.badge_class}" style="flex-shrink:0">${item.badge}</span>
                    </a>`;
                });
            }
            html += `<div style="padding:9px 18px;font-size:12px;color:var(--text-subtle);
                                  background:var(--surface2);border-top:1px solid var(--border);
                                  text-align:center">
                ${data.total} result${data.total !== 1 ? 's' : ''} for "<strong style="color:var(--text)">${data.query}</strong>"
            </div>`;
            cmdResults.innerHTML = html;
            activeIdx = -1;
            indexItems();
        }

        function indexItems() {
            resultLinks = Array.from(cmdResults.querySelectorAll('.cmd-item'));
        }

        function setActive(n) {
            resultLinks.forEach(el => el.classList.remove('cmd-selected'));
            if (n >= 0 && n < resultLinks.length) {
                resultLinks[n].classList.add('cmd-selected');
                resultLinks[n].scrollIntoView({ block:'nearest' });
            }
            activeIdx = n;
        }

        // ── Input handler ────────────────────────────────────────────────
        cmdInput.addEventListener('input', function () {
            const q = this.value.trim();
            clearTimeout(searchTimer);
            activeIdx = -1;
            if (q.length < 2) { renderQuickActions(); return; }
            renderLoading();
            cmdSpinner.className = 'fas fa-spinner fa-spin';
            cmdSpinner.style.color = 'var(--gold)';
            searchTimer = setTimeout(() => {
                fetch(`/search?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    cmdSpinner.className = 'fas fa-search';
                    renderSearchResults(data);
                })
                .catch(() => {
                    cmdSpinner.className = 'fas fa-search';
                    cmdResults.innerHTML = '<div class="cmd-empty"><i class="fas fa-wifi"></i>Search unavailable — check your connection.</div>';
                });
            }, 280);
        });

        // ── Keyboard navigation ──────────────────────────────────────────
        cmdInput.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActive(Math.min(activeIdx + 1, resultLinks.length - 1));
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActive(Math.max(activeIdx - 1, 0));
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (activeIdx >= 0 && resultLinks[activeIdx]) {
                    resultLinks[activeIdx].click();
                } else if (resultLinks.length > 0) {
                    resultLinks[0].click();
                }
            }
        });

        // ── Global keyboard shortcut ─────────────────────────────────────
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (palette.style.display === 'none') { openCmdPalette(); }
                else { closeCmdPalette(); }
            }
            if (e.key === 'Escape' && palette.style.display !== 'none') {
                closeCmdPalette();
            }
        });

        // ── Click backdrop to close ──────────────────────────────────────
        palette.addEventListener('click', function (e) {
            if (!cmdBox.contains(e.target)) closeCmdPalette();
        });
    })();
    </script>
</body>
</html><?php /**PATH D:\laragon\www\anakco_bms\resources\views/layouts/app.blade.php ENDPATH**/ ?>