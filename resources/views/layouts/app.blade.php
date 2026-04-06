<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BMS') — Barangay New Era</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
            --crimson-pale:  rgba(155,28,28,0.07);
            --crimson-border:rgba(155,28,28,0.18);
            --bg:            #EEF1F6;
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
            --radius:        10px;
            --radius-sm:     6px;
            --radius-lg:     14px;
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
        }

        a { text-decoration:none; color:inherit; }
        button { font-family:inherit; cursor:pointer; }
        ul { list-style:none; }

        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:var(--border2); border-radius:99px; }

        /* =============================================
           SIDEBAR
        ============================================= */
        .sidebar {
            width:var(--sidebar-w);
            height:100vh;
            background:var(--sidebar-bg);
            display:flex; flex-direction:column;
            position:fixed; top:0; left:0;
            z-index:300; overflow:hidden;
            transition:transform 0.3s ease;
        }

        .sidebar::before {
            content:'';
            position:absolute; top:-60px; left:-60px;
            width:300px; height:300px;
            background:radial-gradient(circle, rgba(200,134,26,0.07) 0%, transparent 65%);
            pointer-events:none; z-index:0;
        }

        .sidebar-brand {
            display:flex; align-items:center; gap:12px;
            padding:18px 18px 16px;
            border-bottom:1px solid rgba(255,255,255,0.07);
            flex-shrink:0; position:relative; z-index:1;
        }

        .brand-logo {
            width:44px; height:44px; border-radius:50%;
            overflow:hidden; flex-shrink:0;
            border:2px solid rgba(200,134,26,0.4);
            background:#fff;
            box-shadow:0 0 0 3px rgba(200,134,26,0.08);
        }

        .brand-logo img { width:100%; height:100%; object-fit:cover; display:block; }

        .brand-text h1 {
            font-size:13px; font-weight:700;
            color:#fff; line-height:1.2; letter-spacing:0.01em;
        }

        .brand-text span { font-size:10px; color:rgba(229,160,32,0.85); font-weight:300; }

        .sidebar-nav { flex:1; overflow-y:auto; padding:8px 0 16px; position:relative; z-index:1; }

        .nav-section-label {
            font-size:9px; font-weight:600;
            letter-spacing:0.15em; text-transform:uppercase;
            color:rgba(255,255,255,0.22); padding:16px 18px 5px;
        }

        .nav-item {
            display:flex; align-items:center; gap:10px;
            padding:8px 18px;
            color:rgba(255,255,255,0.52);
            font-size:12.5px; font-weight:400;
            border-left:3px solid transparent;
            transition:all 0.15s ease;
        }

        .nav-item:hover {
            color:rgba(255,255,255,0.88);
            background:rgba(255,255,255,0.04);
            border-left-color:rgba(200,134,26,0.45);
        }

        .nav-item.active {
            color:var(--gold-light);
            background:rgba(200,134,26,0.1);
            border-left-color:var(--gold-light);
            font-weight:600;
        }

        .nav-item i { width:16px; text-align:center; font-size:12px; flex-shrink:0; opacity:0.75; }
        .nav-item.active i { opacity:1; }

        .sidebar-footer {
            padding:13px 18px;
            border-top:1px solid rgba(255,255,255,0.07);
            flex-shrink:0; background:rgba(0,0,0,0.18);
            position:relative; z-index:1;
        }

        .sidebar-user { display:flex; align-items:center; gap:10px; }

        .user-avatar {
            width:33px; height:33px; border-radius:50%;
            background:linear-gradient(135deg, var(--gold), var(--gold-light));
            display:flex; align-items:center; justify-content:center;
            font-size:13px; font-weight:700; color:var(--navy); flex-shrink:0;
        }

        .user-name { font-size:12.5px; font-weight:600; color:#fff; line-height:1.2; }
        .user-role { font-size:10px; color:rgba(229,160,32,0.85); font-weight:300; }

        /* Mobile overlay */
        .sidebar-overlay {
            display:none; position:fixed;
            inset:0; background:rgba(0,0,0,0.5);
            z-index:299; backdrop-filter:blur(2px);
        }

        /* =============================================
           TOPBAR
        ============================================= */
        .topbar {
            height:var(--topbar-h);
            background:var(--surface);
            border-bottom:1px solid var(--border);
            display:flex; align-items:center; justify-content:space-between;
            padding:0 24px;
            flex-shrink:0;
            box-shadow:var(--shadow-sm);
            position:relative; z-index:100;
        }

        .topbar::after {
            content:'';
            position:absolute; bottom:0; left:0; right:0; height:2px;
            background:linear-gradient(90deg, var(--navy) 0%, var(--gold) 40%, transparent 75%);
            opacity:0.4;
        }

        .topbar-left { display:flex; align-items:center; gap:14px; }

        .menu-toggle {
            background:none; border:none;
            color:var(--text-muted); font-size:18px;
            padding:6px; cursor:pointer;
            display:none;
        }

        .topbar-title {
            font-size:18px; font-weight:700;
            color:var(--navy); line-height:1.2; letter-spacing:-0.01em;
        }

        .topbar-subtitle { font-size:11.5px; color:var(--text-subtle); font-weight:300; }
        .topbar-right { display:flex; align-items:center; gap:10px; }

        .topbar-date {
            display:flex; align-items:center; gap:7px;
            font-size:12px; color:var(--text-muted);
            background:var(--surface2); padding:6px 14px;
            border-radius:var(--radius-sm); border:1px solid var(--border);
        }

        .topbar-date i { color:var(--gold); font-size:12px; }
        .topbar-divider { width:1px; height:22px; background:var(--border); }

        .topbar-btn {
            display:flex; align-items:center; gap:7px;
            padding:7px 16px; background:var(--surface);
            border:1px solid var(--border); border-radius:var(--radius-sm);
            color:var(--text-muted); font-size:12.5px; font-weight:500;
            font-family:'Poppins',sans-serif; transition:all 0.15s; cursor:pointer;
        }

        .topbar-btn:hover {
            color:var(--crimson);
            border-color:var(--crimson-border);
            background:var(--crimson-pale);
        }

        /* =============================================
           MAIN WRAPPER + WATERMARK
        ============================================= */
        .main-wrapper {
            margin-left:var(--sidebar-w);
            flex:1; display:flex; flex-direction:column;
            height:100vh; overflow:hidden; position:relative;
        }

        .watermark {
            position:fixed; bottom:-50px; right:-50px;
            width:440px; height:440px;
            background-image:url("{{ asset('images/bne-logo.png') }}");
            background-size:contain; background-repeat:no-repeat;
            background-position:center;
            opacity:0.04; pointer-events:none; z-index:0;
        }

        .main-content {
            flex:1; overflow-y:auto; padding:24px 28px;
            position:relative; z-index:1;
        }

        /* =============================================
           ALERTS
        ============================================= */
        .alert {
            display:flex; align-items:flex-start; gap:12px;
            padding:14px 16px; border-radius:var(--radius);
            margin-bottom:20px; font-size:13.5px;
        }

        .alert-success { background:rgba(22,101,52,0.07); border:1px solid rgba(22,101,52,0.18); color:#14532D; }
        .alert-error   { background:var(--crimson-pale); border:1px solid var(--crimson-border); color:var(--crimson); }
        .alert-icon    { font-size:15px; flex-shrink:0; margin-top:2px; }
        .alert-message { flex:1; color:var(--text); }
        .alert-message strong { display:block; margin-bottom:4px; }
        .alert-list    { margin-top:6px; padding-left:16px; list-style:disc; }
        .alert-list li { margin-bottom:2px; font-size:13px; }
        .alert-close   { background:none; border:none; color:var(--text-subtle); font-size:14px; padding:2px; flex-shrink:0; }
        .alert-close:hover { color:var(--text); }

        /* =============================================
           PAGE HEADER
        ============================================= */
        .page-header {
            display:flex; align-items:flex-start;
            justify-content:space-between;
            margin-bottom:24px; gap:16px; flex-wrap:wrap;
        }

        .page-title {
            font-size:22px; font-weight:700;
            color:var(--navy); line-height:1.2; letter-spacing:-0.01em;
        }

        .page-subtitle { font-size:12px; color:var(--text-muted); margin-top:2px; font-weight:300; }
        .page-actions  { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }

        /* =============================================
           BUTTONS
        ============================================= */
        .btn {
            display:inline-flex; align-items:center; gap:7px;
            padding:8px 18px; border-radius:var(--radius-sm);
            font-size:12.5px; font-weight:600;
            border:1px solid transparent; cursor:pointer;
            transition:all 0.15s; font-family:'Poppins',sans-serif;
            white-space:nowrap;
        }

        .btn-primary   { background:var(--navy); color:#fff; border-color:var(--navy); }
        .btn-primary:hover { background:var(--navy-mid); box-shadow:0 4px 12px rgba(13,33,68,0.2); }

        .btn-gold      { background:var(--gold); color:#fff; border-color:var(--gold); }
        .btn-gold:hover { background:var(--gold-light); box-shadow:var(--shadow-gold); }

        .btn-secondary { background:var(--surface); color:var(--text); border-color:var(--border); }
        .btn-secondary:hover { border-color:var(--navy); color:var(--navy); background:var(--navy-pale); }

        .btn-danger    { background:var(--crimson-pale); color:var(--crimson); border-color:var(--crimson-border); }
        .btn-danger:hover { background:rgba(155,28,28,0.12); }

        .btn-sm   { padding:5px 12px; font-size:11.5px; }
        .btn-icon { padding:7px; aspect-ratio:1; justify-content:center; }

        /* =============================================
           CARDS
        ============================================= */
        .card { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden; box-shadow:var(--shadow-sm); }

        .card-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:14px 20px; border-bottom:1px solid var(--border);
            background:var(--surface2); border-left:3px solid var(--gold);
        }

        .card-title {
            font-size:12px; font-weight:700; color:var(--navy);
            letter-spacing:0.03em; text-transform:uppercase;
            display:flex; align-items:center; gap:8px;
        }

        .card-title i { color:var(--gold); font-size:13px; }
        .card-body { padding:22px; }

        /* =============================================
           TABLE
        ============================================= */
        .table-responsive { overflow-x:auto; -webkit-overflow-scrolling:touch; }

        table { width:100%; border-collapse:collapse; font-size:13px; min-width:600px; }

        thead th {
            padding:10px 16px; text-align:left;
            font-size:10px; font-weight:700;
            text-transform:uppercase; letter-spacing:0.1em;
            color:var(--navy); background:var(--surface2);
            border-bottom:2px solid var(--border); white-space:nowrap;
        }

        tbody tr { border-bottom:1px solid var(--border); transition:background 0.1s; }
        tbody tr:last-child { border-bottom:none; }
        tbody tr:hover { background:var(--navy-pale); }
        tbody td { padding:12px 16px; color:var(--text); vertical-align:middle; }
        .td-muted { color:var(--text-muted); font-size:12px; }
        .td-mono  { font-family:'Courier New',monospace; color:var(--text-muted); font-size:12px; }

        /* =============================================
           BADGES
        ============================================= */
        .badge {
            display:inline-flex; align-items:center;
            padding:3px 10px; border-radius:99px;
            font-size:11px; font-weight:600;
            white-space:nowrap; letter-spacing:0.02em;
        }

        .badge-green  { background:rgba(22,101,52,0.1);  color:#14532D; }
        .badge-red    { background:var(--crimson-pale);  color:var(--crimson); }
        .badge-blue   { background:var(--navy-pale);     color:var(--navy); }
        .badge-yellow { background:var(--gold-glow);     color:#7A4F0A; }
        .badge-orange { background:rgba(194,65,12,0.08); color:#9A3412; }
        .badge-gray   { background:var(--surface3);      color:var(--text-muted); }
        .badge-gold   { background:var(--gold-pale);     color:#92600A; }
        .badge-navy   { background:var(--navy-pale);     color:var(--navy); }

        /* =============================================
           FORMS
        ============================================= */
        .form-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:18px; }
        .form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:18px; }
        .form-group  { display:flex; flex-direction:column; gap:6px; }

        .form-label {
            font-size:10.5px; font-weight:700;
            text-transform:uppercase; letter-spacing:0.09em;
            color:var(--text-muted);
        }

        .form-control {
            width:100%; padding:9px 13px;
            background:var(--surface); border:1px solid var(--border);
            border-radius:var(--radius-sm);
            color:var(--text); font-size:13px;
            font-family:'Poppins',sans-serif; outline:none;
            transition:border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus { border-color:var(--navy); box-shadow:0 0 0 3px var(--navy-pale); }
        .form-control::placeholder { color:var(--text-subtle); font-weight:300; }
        select.form-control option { background:var(--surface); color:var(--text); }
        textarea.form-control { resize:vertical; min-height:100px; }

        .form-check { display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; color:var(--text); }
        .form-check input[type="checkbox"] { width:15px; height:15px; accent-color:var(--navy); cursor:pointer; }

        .form-section-title {
            font-size:11px; font-weight:700; color:var(--navy);
            letter-spacing:0.07em; text-transform:uppercase;
            padding-bottom:10px;
            border-bottom:1px solid var(--gold-border);
            margin-bottom:18px;
        }

        .form-actions {
            display:flex; align-items:center; gap:10px;
            margin-top:24px; padding-top:20px;
            border-top:1px solid var(--border); flex-wrap:wrap;
        }

        /* =============================================
           STAT CARDS
        ============================================= */
        .stat-card {
            background:var(--surface); border:1px solid var(--border);
            border-radius:var(--radius-lg); padding:20px;
            display:flex; align-items:center; gap:16px;
            box-shadow:var(--shadow-sm);
            transition:border-color 0.2s, box-shadow 0.2s, transform 0.15s;
        }

        .stat-card:hover { border-color:var(--gold-border); box-shadow:var(--shadow-gold); transform:translateY(-2px); }

        .stat-icon { width:48px; height:48px; border-radius:var(--radius); display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
        .stat-info { flex:1; min-width:0; }
        .stat-number { font-size:30px; font-weight:700; color:var(--navy); line-height:1; }
        .stat-label  { font-size:11.5px; color:var(--text-muted); margin-top:3px; font-weight:400; }

        /* =============================================
           GRIDS
        ============================================= */
        .grid-2 { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }
        .grid-3 { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
        .grid-4 { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; }
        .mb-4 { margin-bottom:16px; }
        .mb-6 { margin-bottom:24px; }
        .mb-8 { margin-bottom:32px; }
        .mt-6 { margin-top:24px; }

        /* =============================================
           TABS
        ============================================= */
        .tab-nav { display:flex; gap:2px; background:var(--surface2); padding:4px; border-radius:var(--radius); border:1px solid var(--border); flex-wrap:wrap; margin-bottom:22px; }
        .tab-btn { padding:7px 16px; border-radius:var(--radius-sm); font-size:12.5px; font-weight:500; color:var(--text-muted); background:none; border:none; cursor:pointer; transition:all 0.15s; font-family:'Poppins',sans-serif; }
        .tab-btn:hover { color:var(--navy); }
        .tab-btn.active { background:var(--surface); color:var(--navy); font-weight:700; border:1px solid var(--border); box-shadow:var(--shadow-sm); }
        .tab-pane { display:none; }
        .tab-pane.active { display:block; }

        /* =============================================
           EMPTY STATE
        ============================================= */
        .empty-state { text-align:center; padding:52px 24px; color:var(--text-muted); }
        .empty-state i { font-size:38px; opacity:0.15; display:block; margin-bottom:14px; color:var(--navy); }
        .empty-state p { font-size:13.5px; font-weight:300; }

        /* =============================================
           FILTER BAR
        ============================================= */
        .filter-bar { display:flex; gap:10px; flex-wrap:wrap; align-items:flex-end; }
        .filter-bar .form-group { min-width:150px; }
        .filter-bar .form-group.flex-1 { flex:1; min-width:200px; }

        /* =============================================
           PROGRESS BARS
        ============================================= */
        .progress-bar-wrap { background:var(--surface3); border-radius:99px; height:7px; overflow:hidden; }
        .progress-bar { height:100%; border-radius:99px; transition:width 0.7s ease; }

        /* =============================================
           GOLD RULE
        ============================================= */
        .gold-rule { height:1px; background:linear-gradient(90deg, var(--gold-border), transparent); margin:20px 0; }

        /* =============================================
           PRINT UTILITIES
        ============================================= */
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            .sidebar, .topbar, .main-wrapper > .watermark { display: none !important; }
            .main-content { padding: 0 !important; }
        }
        .print-only { display: none; }
        
        /* =============================================
           RESPONSIVE — MOBILE & TABLET
        ============================================= */

        /* Tablet: 768px–1100px */
        @media (max-width: 1100px) {
            .grid-4 { grid-template-columns: repeat(2, 1fr); }
            .grid-3 { grid-template-columns: repeat(2, 1fr); }
        }

        /* Mobile & small tablet: ≤768px */
        @media (max-width: 768px) {

            body { overflow:auto; }

            /* Sidebar hidden off-screen, slides in */
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }

            .sidebar.sidebar-open {
                transform: translateX(0);
                box-shadow: 4px 0 30px rgba(0,0,0,0.35);
            }

            .sidebar-overlay.active {
                display: block;
            }

            /* Main wrapper fills full width */
            .main-wrapper {
                margin-left: 0;
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }

            /* Topbar */
            .topbar {
                padding: 0 16px;
                position: sticky; top: 0;
            }

            .menu-toggle { display: flex; }

            .topbar-date { display: none; }
            .topbar-divider { display: none; }
            .topbar-subtitle { display: none; }

            /* Main content */
            .main-content {
                padding: 16px;
                overflow: visible;
            }

            /* Grids go single column */
            .grid-4 { grid-template-columns: 1fr 1fr; gap: 12px; }
            .grid-3 { grid-template-columns: 1fr; gap: 12px; }
            .grid-2 { grid-template-columns: 1fr; gap: 12px; }

            /* Forms collapse to single column */
            .form-grid-2 { grid-template-columns: 1fr; }
            .form-grid-3 { grid-template-columns: 1fr; }

            /* Stat cards smaller */
            .stat-card { padding: 14px; }
            .stat-number { font-size: 22px; }
            .stat-icon { width: 40px; height: 40px; font-size: 16px; }

            /* Page header stacks */
            .page-header { flex-direction: column; }
            .page-title { font-size: 18px; }

            /* Card header wraps */
            .card-header { flex-wrap: wrap; gap: 8px; }

            /* Filter bar full width */
            .filter-bar .form-group { min-width: 100%; }

            /* Profile layouts collapse */
            [style*="grid-template-columns:300px"],
            [style*="grid-template-columns:280px"] {
                display: flex !important;
                flex-direction: column !important;
            }

            /* Table stays scrollable */
            .table-responsive { overflow-x: auto; }
        }

        /* Very small phones */
        @media (max-width: 480px) {
            .grid-4 { grid-template-columns: 1fr; }
            .page-actions { width: 100%; }
            .page-actions .btn { flex: 1; justify-content: center; }
            .topbar-title { font-size: 15px; }
        }

    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

    @include('partials._sidebar')

    <div class="main-wrapper">
        <div class="watermark"></div>
        @include('partials._topbar')
        <main class="main-content">
            @include('partials._alerts')
            @yield('content')
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
        if (menuToggle) {
            menuToggle.addEventListener('click', openSidebar);
        }
    </script>

    @stack('scripts')
    
</body>
</html>