<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 — Session Expired | BMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Poppins',sans-serif; background:#EEF1F6; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px; }
        .error-card { background:#fff; border-radius:16px; box-shadow:0 4px 24px rgba(0,0,0,0.08); padding:56px 48px; text-align:center; max-width:520px; width:100%; border:1px solid #DDE2EA; }
        .error-icon { width:96px; height:96px; background:rgba(13,33,68,0.06); border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 28px; font-size:40px; color:#0D2144; }
        .error-code { font-size:80px; font-weight:800; color:#0D2144; line-height:1; letter-spacing:-4px; margin-bottom:12px; }
        .error-code span { color:#C8861A; }
        .error-title { font-size:22px; font-weight:700; color:#0D2144; margin-bottom:10px; }
        .error-msg { font-size:14px; color:#4B5563; line-height:1.7; margin-bottom:32px; font-weight:300; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none; transition:all 0.15s; font-family:'Poppins',sans-serif; cursor:pointer; border:none; }
        .btn-primary { background:#0D2144; color:#fff; margin-right:10px; }
        .btn-primary:hover { background:#163160; }
        .btn-secondary { background:#fff; color:#0F1924; border:1px solid #DDE2EA; }
        .btn-secondary:hover { border-color:#0D2144; color:#0D2144; }
        .barangay-label { margin-top:36px; padding-top:24px; border-top:1px solid #EEF1F6; font-size:11px; color:#9CA3AF; letter-spacing:0.05em; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon"><i class="fas fa-clock-rotate-left"></i></div>
        <div class="error-code">4<span>1</span>9</div>
        <div class="error-title">Session Expired</div>
        <div class="error-msg">
            Your session has expired due to inactivity. Please refresh the page and try again.
            Your data may not have been saved.
        </div>
        <div>
            <button onclick="location.reload()" class="btn btn-primary"><i class="fas fa-rotate-right"></i> Refresh Page</button>
            <a href="{{ url('/') }}" class="btn btn-secondary"><i class="fas fa-house"></i> Dashboard</a>
        </div>
        <div class="barangay-label">BARANGAY NEW ERA MANAGEMENT SYSTEM</div>
    </div>
</body>
</html>