<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Resident Portal') — Barangay New Era</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --navy:         #0D2144;
            --navy-dark:    #091830;
            --navy-pale:    #e8edf5;
            --navy-border:  #b8c6de;
            --gold:         #C8861A;
            --gold-pale:    #fdf3e3;
            --gold-border:  #e8c47a;
            --crimson:      #9B1C1C;
            --crimson-pale: #fdeaea;
            --crimson-border:#e8a0a0;
            --radius:       10px;
            --radius-sm:    6px;
            --radius-lg:    14px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            color: #1a2332;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ---- HEADER ---- */
        .portal-header {
            background: var(--navy);
            color: #fff;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .portal-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            color: #fff;
        }
        .portal-brand .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }
        .portal-brand-text strong {
            display: block;
            font-size: .85rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .portal-brand-text span {
            font-size: .7rem;
            opacity: .75;
        }
        .portal-nav a {
            color: rgba(255,255,255,.8);
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            margin-left: 1.5rem;
            transition: color .2s;
        }
        .portal-nav a:hover { color: var(--gold); }

        /* ---- MAIN ---- */
        .portal-main {
            flex: 1;
            max-width: 900px;
            width: 100%;
            margin: 2.5rem auto;
            padding: 0 1.25rem;
        }

        /* ---- FOOTER ---- */
        .portal-footer {
            background: var(--navy-dark);
            color: rgba(255,255,255,.5);
            text-align: center;
            padding: 1rem;
            font-size: .75rem;
        }

        /* ---- CARD ---- */
        .p-card {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            padding: 2rem;
        }

        /* ---- ALERTS ---- */
        .p-alert {
            padding: .75rem 1rem;
            border-radius: var(--radius-sm);
            font-size: .85rem;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }
        .p-alert-success {
            background: #ecfdf5;
            border-color: #16a34a;
            color: #14532d;
        }
        .p-alert-error {
            background: var(--crimson-pale);
            border-color: var(--crimson);
            color: var(--crimson);
        }

        /* ---- FORM ELEMENTS ---- */
        .form-group { margin-bottom: 1.25rem; }
        .form-group label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: var(--navy);
            margin-bottom: .4rem;
        }
        .form-group label .req { color: var(--crimson); }
        .form-control {
            width: 100%;
            padding: .55rem .85rem;
            border: 1.5px solid #d1d5db;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: .85rem;
            transition: border-color .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(13,33,68,.08);
        }
        .form-error {
            color: var(--crimson);
            font-size: .76rem;
            margin-top: .3rem;
        }

        /* ---- BUTTONS ---- */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .6rem 1.4rem;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: opacity .2s, transform .1s;
        }
        .btn:active { transform: scale(.98); }
        .btn-primary { background: var(--navy); color: #fff; }
        .btn-primary:hover { opacity: .9; }
        .btn-gold { background: var(--gold); color: #fff; }
        .btn-gold:hover { opacity: .9; }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--navy);
            color: var(--navy);
        }
        .btn-outline:hover { background: var(--navy-pale); }
        .btn-lg { padding: .8rem 2rem; font-size: .95rem; }
    </style>
    @stack('styles')
</head>
<body>

<header class="portal-header">
    <a href="{{ route('portal.index') }}" class="portal-brand">
        <div class="brand-icon"><i class="fas fa-landmark"></i></div>
        <div class="portal-brand-text">
            <strong>Barangay New Era</strong>
            <span>District VI, Quezon City</span>
        </div>
    </a>
    <nav class="portal-nav">
        <a href="{{ route('portal.request') }}"><i class="fas fa-file-plus"></i> Request Document</a>
        <a href="{{ route('portal.track') }}"><i class="fas fa-search"></i> Track Status</a>
        @auth
            <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        @else
            <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Staff Login</a>
        @endauth
    </nav>
</header>

<main class="portal-main">
    @if(session('success'))
        <div class="p-alert p-alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-alert p-alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    @yield('content')
</main>

<footer class="portal-footer">
    &copy; {{ date('Y') }} Barangay New Era, District VI, Quezon City. All rights reserved.
</footer>

@stack('scripts')
</body>
</html>
