<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Barangay New Era BMS</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --gold:      #C8861A;
            --gold-light:#E5A020;
            --navy:      #0D2144;
            --navy-mid:  #163160;
            --crimson:   #9B1C1C;
            --crimson-pale: rgba(155,28,28,0.07);
            --crimson-border: rgba(155,28,28,0.18);
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html, body { min-height:100%; }

        body {
            font-family:'Poppins', sans-serif;
            background:var(--navy);
            min-height:100vh;
            display:flex;
        }

        /* ── SLIDESHOW BACKGROUND ─────────────────────────────────────── */
        .slideshow-bg {
            position:absolute; inset:0; z-index:0; pointer-events:none;
        }

        .slide {
            position:absolute; inset:0;
            background-size:cover; background-position:center;
            opacity:0;
            animation: slideFade 25s infinite;
        }

        /* 5 slides × 5 s each = 25 s cycle
           Each slide fades in at 0 %, holds until 20 %, fades out by 24 % */
        @keyframes slideFade {
            0%   { opacity:0; }
            4%   { opacity:1; }
            20%  { opacity:1; }
            24%  { opacity:0; }
            100% { opacity:0; }
        }

        .slide:nth-child(1) { animation-delay:  0s; background-image:url('/images/login-slides/slide1.jpg'); }
        .slide:nth-child(2) { animation-delay:  5s; background-image:url('/images/login-slides/slide2.jpg'); }
        .slide:nth-child(3) { animation-delay: 10s; background-image:url('/images/login-slides/slide3.jpg'); }
        .slide:nth-child(4) { animation-delay: 15s; background-image:url('/images/login-slides/slide4.jpg'); }
        .slide:nth-child(5) { animation-delay: 20s; background-image:url('/images/login-slides/slide5.jpg'); }

        /* Dark overlay so text stays readable over any photo */
        .slideshow-overlay {
            position:absolute; inset:0; z-index:1; pointer-events:none;
            background:rgba(13,33,68,0.93);
        }

        /* ── SPLASH SCREEN ────────────────────────────────────────────── */
        #splash {
            position:fixed; inset:0; z-index:9999;
            background:var(--navy);
            display:flex; flex-direction:column;
            align-items:center; justify-content:center; gap:0;
            transition:opacity 0.7s ease, visibility 0.7s ease;
        }

        #splash.hide {
            opacity:0;
            visibility:hidden;
        }

        /* Splash coin — larger than login coin */
        .splash-coin {
            width:140px; height:140px;
            perspective:600px;
            margin-bottom:32px;
        }

        .splash-coin-inner {
            width:100%; height:100%;
            position:relative;
            transform-style:preserve-3d;
            animation:splashFlip 2s ease-in-out infinite;
            border-radius:50%;
        }

        @keyframes splashFlip {
            0%   { transform:rotateY(0deg); }
            18%  { transform:rotateY(0deg); }
            48%  { transform:rotateY(180deg); }
            65%  { transform:rotateY(180deg); }
            95%  { transform:rotateY(360deg); }
            100% { transform:rotateY(360deg); }
        }

        .splash-front,
        .splash-back {
            position:absolute; inset:0;
            backface-visibility:hidden;
            -webkit-backface-visibility:hidden;
        }

        .splash-front {
            border-radius:50%;
            background:#fff;
            border:3px solid rgba(200,134,26,0.5);
            box-shadow:
                0 0 0 9px rgba(200,134,26,0.08),
                0 0 60px rgba(200,134,26,0.2),
                0 24px 60px rgba(0,0,0,0.5);
            overflow:hidden;
        }

        .splash-front img {
            width:100%; height:100%;
            object-fit:cover; display:block;
        }

        .splash-back {
            transform:rotateY(180deg);
            background:transparent;
            border:none; box-shadow:none;
            display:flex; align-items:center; justify-content:center;
        }

        .splash-back img {
            width:100%; height:100%;
            object-fit:contain; display:block;
            filter:drop-shadow(0 4px 20px rgba(0,0,0,0.6));
        }

        /* Splash text */
        .splash-title {
            font-size:26px; font-weight:800;
            color:#fff; letter-spacing:-0.01em;
            text-align:center; line-height:1.2;
            margin-bottom:6px;
        }

        .splash-title span { color:var(--gold-light); }

        .splash-sub {
            font-size:11px; font-weight:400;
            color:rgba(255,255,255,0.35);
            text-transform:uppercase; letter-spacing:0.2em;
            text-align:center; margin-bottom:36px;
        }

        /* Animated loading dots */
        .splash-dots {
            display:flex; gap:8px; align-items:center;
        }

        .splash-dots span {
            width:7px; height:7px; border-radius:50%;
            background:var(--gold);
            opacity:0.25;
            animation:dotPulse 1.2s ease-in-out infinite;
        }

        .splash-dots span:nth-child(2) { animation-delay:0.2s; }
        .splash-dots span:nth-child(3) { animation-delay:0.4s; }

        @keyframes dotPulse {
            0%, 100% { opacity:0.2; transform:scale(0.8); }
            50%       { opacity:1;   transform:scale(1.2); }
        }

        /* Gold accent line under coin */
        .splash-line {
            width:60px; height:2px;
            background:linear-gradient(90deg, transparent, var(--gold), transparent);
            margin:20px auto 24px;
            border-radius:2px;
        }

        /* ---- LEFT PANEL ---- */
        .left-panel {
            flex:1;
            background:var(--navy);
            display:flex; flex-direction:column;
            align-items:center; justify-content:center;
            position:relative; overflow:hidden;
            padding:60px 52px;
        }

        .left-panel::before,
        .left-panel::after { display:none; }

        .left-content {
            position:relative; z-index:3;
            text-align:center; max-width:420px;
        }

        /* ── COIN FLIP ──────────────────────────────────────────────────── */
        .coin-flip {
            width:115px; height:115px;
            margin:0 auto 24px;
            perspective:500px;
        }

        .coin-inner {
            width:100%; height:100%;
            position:relative;
            transform-style:preserve-3d;
            animation:coinFlip 6s ease-in-out infinite;
            border-radius:50%;
        }

        /* Pause on front → fast spin to back → pause → fast spin to front */
        @keyframes coinFlip {
            0%   { transform:rotateY(0deg); }
            18%  { transform:rotateY(0deg); }      /* hold front face */
            42%  { transform:rotateY(180deg); }    /* flip to back   */
            60%  { transform:rotateY(180deg); }    /* hold back face */
            84%  { transform:rotateY(360deg); }    /* flip to front  */
            100% { transform:rotateY(360deg); }    /* brief pause    */
        }

        /* Front face — circular seal frame (BNE logo) */
        .coin-front {
            position:absolute; inset:0;
            backface-visibility:hidden;
            -webkit-backface-visibility:hidden;
            border-radius:50%;
            background:#fff;
            border:3px solid rgba(200,134,26,0.45);
            box-shadow:
                0 0 0 7px rgba(200,134,26,0.07),
                0 24px 60px rgba(0,0,0,0.45);
            overflow:hidden;
        }

        .coin-front img {
            width:100%; height:100%;
            object-fit:cover; display:block;
        }

        /* Back face — no circle clip; QC seal displays in its natural shape */
        .coin-back {
            position:absolute; inset:0;
            backface-visibility:hidden;
            -webkit-backface-visibility:hidden;
            transform:rotateY(180deg);
            background:transparent;
            border:none; box-shadow:none;
            display:flex; align-items:center; justify-content:center;
        }

        .coin-back img {
            width:100%; height:100%;
            object-fit:contain; display:block;
            filter:drop-shadow(0 4px 18px rgba(0,0,0,0.55));
        }

        .left-eyebrow {
            font-size:9.5px; font-weight:700;
            text-transform:uppercase; letter-spacing:0.22em;
            color:rgba(229,160,32,0.8);
            margin-bottom:10px; display:block;
        }

        .left-content h1 {
            font-size:36px; font-weight:800;
            color:#fff; line-height:1.1;
            letter-spacing:-0.01em; margin-bottom:6px;
        }

        .left-content h1 .accent { color:var(--gold-light); }

        .left-content .tagline {
            font-size:12.5px; color:rgba(255,255,255,0.4);
            font-weight:300; margin-bottom:32px; line-height:1.7;
        }

        .gold-divider {
            display:flex; align-items:center; gap:12px; margin-bottom:32px;
        }

        .gold-divider::before, .gold-divider::after {
            content:''; flex:1; height:1px;
        }

        .gold-divider::before { background:linear-gradient(90deg, transparent, rgba(200,134,26,0.5)); }
        .gold-divider::after  { background:linear-gradient(90deg, rgba(200,134,26,0.5), transparent); }
        .gold-divider i { color:var(--gold); font-size:10px; opacity:0.7; }

        .info-cards { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; width:100%; }

        .info-card {
            background:rgba(255,255,255,0.04);
            border:1px solid rgba(255,255,255,0.07);
            border-radius:10px; padding:14px 10px;
            text-align:center; transition:border-color 0.2s;
        }

        .info-card:hover { border-color:rgba(200,134,26,0.3); }
        .info-card i { font-size:16px; color:var(--gold-light); margin-bottom:6px; display:block; opacity:0.85; }
        .info-label { font-size:9px; text-transform:uppercase; letter-spacing:0.13em; color:rgba(255,255,255,0.3); margin-bottom:4px; }
        .info-value { font-size:15px; font-weight:700; color:rgba(255,255,255,0.8); }

        .left-footer {
            position:absolute; bottom:22px;
            font-size:10.5px; color:rgba(255,255,255,0.18);
            font-weight:300; text-align:center; z-index:3;
            padding:0 20px;
        }

        /* ---- RIGHT PANEL ---- */
        .right-panel {
            width:450px; flex-shrink:0;
            background:#fff;
            display:flex; flex-direction:column;
            align-items:center; justify-content:center;
            padding:56px 44px; position:relative; overflow-y:auto;
        }

        .right-panel::before {
            content:'';
            position:absolute; top:0; left:0; right:0; height:4px;
            background:linear-gradient(90deg, var(--navy) 0%, var(--gold) 50%, var(--navy) 100%);
        }

        .form-container { width:100%; max-width:340px; }

        .form-header { margin-bottom:30px; text-align:center; }

        .form-eyebrow {
            font-size:9.5px; font-weight:700;
            text-transform:uppercase; letter-spacing:0.2em;
            color:var(--gold); margin-bottom:8px; display:block;
        }

        .form-header h2 {
            font-size:26px; font-weight:800;
            color:var(--navy); line-height:1.2;
            letter-spacing:-0.01em; margin-bottom:6px;
        }

        .form-header p { font-size:12.5px; color:#6B7280; font-weight:300; line-height:1.6; }

        .field-group { margin-bottom:16px; }

        .field-label {
            display:block; font-size:10px; font-weight:700;
            text-transform:uppercase; letter-spacing:0.12em;
            color:#374151; margin-bottom:7px;
        }

        .field-wrapper { position:relative; }

        .field-icon {
            position:absolute; left:13px; top:50%;
            transform:translateY(-50%);
            color:#C4C9D4; font-size:13px; pointer-events:none;
        }

        .field-input {
            width:100%; padding:11px 14px 11px 40px;
            border:1.5px solid #E5E7EB; border-radius:7px;
            font-family:'Poppins',sans-serif;
            font-size:13.5px; color:#111827;
            background:#FAFAFA; outline:none;
            transition:border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field-input:focus {
            border-color:var(--navy);
            box-shadow:0 0 0 3px rgba(13,33,68,0.08);
            background:#fff;
        }

        .field-input::placeholder { color:#D1D5DB; font-weight:300; }
        .field-input.is-invalid { border-color:var(--crimson); }

        .field-error {
            font-size:11.5px; color:var(--crimson);
            margin-top:5px; font-weight:500;
            display:flex; align-items:center; gap:5px;
        }

        .form-row {
            display:flex; align-items:center;
            justify-content:space-between; margin-bottom:20px; gap:10px;
        }

        .remember-label {
            display:flex; align-items:center; gap:7px;
            font-size:12.5px; color:#4B5563; cursor:pointer; font-weight:400;
        }

        .remember-label input[type="checkbox"] { width:14px; height:14px; accent-color:var(--navy); cursor:pointer; }

        .forgot-link { font-size:12.5px; color:var(--gold); font-weight:500; transition:color 0.15s; white-space:nowrap; }
        .forgot-link:hover { color:var(--gold-light); }

        .submit-btn {
            width:100%; padding:13px;
            background:var(--navy); color:#fff;
            border:none; border-radius:7px;
            font-family:'Poppins',sans-serif;
            font-size:13.5px; font-weight:700;
            letter-spacing:0.03em; cursor:pointer;
            transition:all 0.2s;
            display:flex; align-items:center; justify-content:center; gap:9px;
            position:relative; overflow:hidden;
        }

        .submit-btn::after {
            content:'';
            position:absolute; bottom:0; left:0; right:0; height:3px;
            background:linear-gradient(90deg, var(--gold), var(--gold-light));
            opacity:0; transition:opacity 0.2s;
        }

        .submit-btn:hover {
            background:var(--navy-mid);
            box-shadow:0 8px 24px rgba(13,33,68,0.28);
            transform:translateY(-1px);
        }

        .submit-btn:hover::after { opacity:1; }

        .login-alert {
            border-radius:7px; padding:12px 16px; margin-bottom:18px;
            font-size:12.5px; font-weight:400;
            display:flex; align-items:center; gap:10px;
        }

        .login-alert.error { background:var(--crimson-pale); border:1px solid var(--crimson-border); color:var(--crimson); }
        .login-alert.success { background:rgba(22,101,52,0.07); border:1px solid rgba(22,101,52,0.18); color:#14532D; }

        .form-divider { display:flex; align-items:center; gap:12px; margin-top:26px; }
        .form-divider::before, .form-divider::after { content:''; flex:1; height:1px; background:#E5E7EB; }
        .form-divider span { font-size:10px; color:#9CA3AF; font-weight:400; white-space:nowrap; letter-spacing:0.06em; }

        .right-footer { position:absolute; bottom:20px; font-size:10.5px; color:#D1D5DB; font-weight:300; text-align:center; padding:0 20px; }

        /* =============================================
           RESPONSIVE LOGIN
        ============================================= */

        /* Tablet: hide left panel, right panel expands */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .right-panel {
                width: 100%;
                min-height: 100vh;
                padding: 40px 24px;
            }
            .right-footer { position: static; margin-top: 32px; }
        }

        /* Mobile: tighter padding */
        @media (max-width: 480px) {
            .right-panel { padding: 32px 20px; }
            .form-container { max-width: 100%; }
            .form-header h2 { font-size: 22px; }
            .form-row { flex-direction: column; align-items: flex-start; gap: 10px; }
        }

    </style>
</head>
<body>

    <!-- LEFT PANEL (hidden on mobile) -->
    <div class="left-panel">

        {{-- ── Slideshow background (5 slides) ──────────────────────────
             Add your photos to:  public/images/login-slides/
             Name them:  slide1.jpg  slide2.jpg  slide3.jpg  slide4.jpg  slide5.jpg
             Recommended size: 1200 × 900 px or larger, landscape orientation.
        ──────────────────────────────────────────────────────────────── --}}
        <div class="slideshow-bg">
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
            <div class="slide"></div>
        </div>
        <div class="slideshow-overlay"></div>

        <div class="left-content">

            {{-- ── Coin flip: front = BNE logo, back = QC seal ── --}}
            <div class="coin-flip">
                <div class="coin-inner">
                    <div class="coin-front">
                        <img src="/images/bne-logo.png" alt="Barangay New Era Logo"
                             onerror="this.style.display='none'">
                    </div>
                    <div class="coin-back">
                        <img src="/images/qc-seal.png" alt="Quezon City Seal"
                             onerror="this.style.display='none'">
                    </div>
                </div>
            </div>

            <span class="left-eyebrow">Official Barangay Portal</span>
            <h1>Barangay <span class="accent">New Era</span></h1>
            <p class="tagline">District VI, Quezon City<br>Founded January 2, 1981</p>

            <div class="gold-divider">
                <i class="fas fa-circle-dot"></i>
            </div>

            <div class="info-cards">
                <div class="info-card">
                    <i class="fas fa-users"></i>
                    <div class="info-label">Residents</div>
                    <div class="info-value">BNE</div>
                </div>
                <div class="info-card">
                    <i class="fas fa-file-certificate"></i>
                    <div class="info-label">e-Services</div>
                    <div class="info-value">BMS</div>
                </div>
                <div class="info-card">
                    <i class="fas fa-landmark"></i>
                    <div class="info-label">Est.</div>
                    <div class="info-value">1981</div>
                </div>
            </div>
        </div>

        <div class="left-footer">
            Barangay New Era Management System &mdash; For Authorized Personnel Only
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="right-panel">
        <div class="form-container">

            <div class="form-header">
                <span class="form-eyebrow">Secure Access Portal</span>
                <h2>Sign In</h2>
                <p>Enter your credentials to access<br>the Barangay Management System.</p>
            </div>

            @if (session('status'))
                <div class="login-alert success">
                    <i class="fas fa-circle-check"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="login-alert error">
                    <i class="fas fa-triangle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field-group">
                    <label class="field-label" for="email">Email Address</label>
                    <div class="field-wrapper">
                        <i class="fas fa-envelope field-icon"></i>
                        <input type="email" id="email" name="email"
                               class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="you@barangay.gov.ph"
                               required autofocus autocomplete="email">
                    </div>
                    @error('email')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrapper">
                        <i class="fas fa-lock field-icon"></i>
                        <input type="password" id="password" name="password"
                               class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="••••••••"
                               required autocomplete="current-password">
                    </div>
                    @error('password')
                        <div class="field-error"><i class="fas fa-circle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-right-to-bracket"></i>
                    Sign In
                </button>
            </form>

            <div class="form-divider">
                <span>Authorized Personnel Only</span>
            </div>

        </div>

        <div class="right-footer">
            &copy; {{ date('Y') }} Barangay New Era, District VI, Quezon City
        </div>
    </div>

</body>
</html>