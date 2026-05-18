<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Status Update — Barangay New Era</title>
<style>
  body { margin:0; padding:0; font-family:'Segoe UI',Arial,sans-serif; background:#f3f4f6; color:#1a2332; }
  .wrap { max-width:580px; margin:32px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); }
  .hdr { background:#0D2144; padding:28px 32px; text-align:center; }
  .hdr-logo { font-size:13px; font-weight:700; color:rgba(255,255,255,.6); letter-spacing:.08em; text-transform:uppercase; }
  .hdr h1 { margin:8px 0 0; font-size:20px; font-weight:700; color:#fff; }
  .body { padding:32px; }
  .status-chip { display:inline-block; padding:6px 16px; border-radius:6px; font-size:14px; font-weight:700; background:#e8edf5; color:#0D2144; margin:4px 0 20px; }
  .detail-box { background:#f8f9fa; border:1px solid #e2e6ea; border-radius:8px; padding:18px 20px; margin:20px 0; }
  .detail-row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #eee; font-size:14px; }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { color:#6b7280; font-weight:500; }
  .detail-value { color:#1a2332; font-weight:600; text-align:right; max-width:60%; }
  .note-box { background:#fdf3e3; border:1px solid #e8c47a; border-left:4px solid #C8861A; border-radius:8px; padding:14px 18px; margin:20px 0; font-size:14px; line-height:1.6; }
  .note-box strong { color:#8a5e10; display:block; margin-bottom:4px; font-size:12px; text-transform:uppercase; letter-spacing:.06em; }
  .cta { text-align:center; margin:28px 0 8px; }
  .cta a { display:inline-block; padding:13px 32px; background:#0D2144; color:#fff; text-decoration:none; border-radius:8px; font-weight:600; font-size:14px; }
  .ftr { background:#f8f9fa; border-top:1px solid #e2e6ea; padding:20px 32px; text-align:center; font-size:12px; color:#9ca3af; line-height:1.8; }
  .ftr a { color:#0D2144; text-decoration:none; }
  @media (max-width:600px) {
    .body { padding:20px; }
    .detail-row { flex-direction:column; gap:2px; }
    .detail-value { text-align:left; max-width:100%; }
  }
</style>
</head>
<body>
<div class="wrap">

  <div class="hdr">
    <div class="hdr-logo">Barangay New Era · District VI, Quezon City</div>
    <h1>
      @if($type === 'document') Document Request Update
      @elseif($type === 'blotter') Blotter Report Update
      @else Business Permit Request Update
      @endif
    </h1>
  </div>

  <div class="body">
    <p style="font-size:15px;margin-bottom:4px">Hello, <strong>{{ $residentName }}</strong>.</p>
    <p style="font-size:14px;color:#6b7280;margin-top:4px">
      Your
      @if($type === 'document') document request
      @elseif($type === 'blotter') blotter report
      @else business permit request
      @endif
      has been updated:
    </p>

    <div class="status-chip">
      @if($newStatus === 'Approved' || $newStatus === 'Resolved' || $newStatus === 'Released')
        ✅
      @elseif($newStatus === 'Cancelled' || $newStatus === 'Rejected' || $newStatus === 'Dismissed')
        ❌
      @else
        🔄
      @endif
      {{ $newStatus }}
    </div>

    <div class="detail-box">
      <div class="detail-row">
        <span class="detail-label">Reference No.</span>
        <span class="detail-value" style="font-family:monospace;color:#0D2144">{{ $requestNumber }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Name</span>
        <span class="detail-value">{{ $residentName }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">New Status</span>
        <span class="detail-value">{{ $newStatus }}</span>
      </div>
      @if($preferredDate)
      <div class="detail-row">
        <span class="detail-label">Pickup Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($preferredDate)->format('F d, Y') }}</span>
      </div>
      @endif
      <div class="detail-row">
        <span class="detail-label">Date Updated</span>
        <span class="detail-value">{{ now()->format('F d, Y \a\t h:i A') }}</span>
      </div>
    </div>

    @if($notes)
    <div class="note-box">
      <strong><i>📋</i> Note from Barangay Staff</strong>
      {{ $notes }}
    </div>
    @endif

    @if($newStatus === 'Ready')
    <div class="note-box" style="background:#ecfdf5;border-color:#86efac;border-left-color:#2e6b47;">
      <strong style="color:#2e6b47">📦 Ready for Pickup</strong>
      Your document is ready! Please proceed to the Barangay Hall during office hours (Mon–Fri, 8AM–5PM) and bring at least <strong>one (1) valid government-issued ID</strong>.
      @if($preferredDate) Your preferred pickup date is <strong>{{ \Carbon\Carbon::parse($preferredDate)->format('F d, Y') }}</strong>. @endif
    </div>
    @endif

    <div class="cta">
      <a href="{{ url('/portal/track?apt='.urlencode($requestNumber)) }}">
        Track Your Request Status
      </a>
    </div>

    <p style="font-size:13px;color:#9ca3af;text-align:center;margin-top:12px">
      Reference: <strong style="color:#0D2144">{{ $requestNumber }}</strong>
    </p>
  </div>

  <div class="ftr">
    <strong style="color:#0D2144">Barangay New Era</strong><br>
    District VI, Quezon City · Office Hours: Mon–Fri, 8:00 AM – 5:00 PM<br>
    Punong Barangay: <strong>Robert S. Romano</strong><br><br>
    This is an automated notification. Do not reply to this email.<br>
    <a href="{{ url('/portal') }}">portal.barangaynewera.gov.ph</a>
  </div>

</div>
</body>
</html>
