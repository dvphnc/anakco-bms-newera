<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
  @if($newStatus === 'Submitted') Request Received
  @elseif($newStatus === 'Processing') Now Processing
  @elseif($newStatus === 'Ready') Ready for Pick-up
  @elseif($newStatus === 'Released') Request Released
  @elseif($newStatus === 'Cancelled') Request Cancelled
  @else Status Update
  @endif
  — Barangay New Era
</title>
<style>
  /* ── Reset ─────────────────────────────────────────── */
  * { box-sizing:border-box; margin:0; padding:0; }
  body {
    font-family:'Segoe UI',Arial,sans-serif;
    background:#eef2f7;
    color:#1a2332;
    -webkit-font-smoothing:antialiased;
    padding:32px 16px 48px;
  }

  /* ── Outer shell ────────────────────────────────────── */
  .shell {
    max-width:560px;
    margin:0 auto;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 8px 32px rgba(0,0,0,.13);
  }

  /* ── Header ─────────────────────────────────────────── */
  .hdr {
    background:#0D2144;
    padding:22px 28px;
    display:flex;
    align-items:center;
    gap:14px;
  }
  .hdr-seal {
    width:52px; height:52px;
    background:rgba(255,255,255,.10);
    border:2px solid rgba(255,255,255,.20);
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:24px;
    flex-shrink:0;
  }
  .hdr-text { flex:1; text-align:center; }
  .hdr-text .label {
    font-size:10px; font-weight:700;
    color:rgba(255,255,255,.55);
    letter-spacing:.10em;
    text-transform:uppercase;
  }
  .hdr-text h1 {
    font-size:17px; font-weight:700;
    color:#fff; margin:3px 0 1px;
    letter-spacing:.01em;
  }
  .hdr-text .sub {
    font-size:11px;
    color:rgba(255,255,255,.45);
  }

  /* ── Card ───────────────────────────────────────────── */
  .card { background:#fff; position:relative; overflow:hidden; }

  /* ── Status banner ──────────────────────────────────── */
  .banner {
    padding:20px 28px;
    display:flex;
    align-items:center;
    gap:16px;
  }
  .banner.submitted  { background:linear-gradient(135deg,#1e40af,#1d4ed8); }
  .banner.processing { background:linear-gradient(135deg,#0369a1,#0284c7); }
  .banner.ready      { background:linear-gradient(135deg,#15803d,#16a34a); }
  .banner.released   { background:linear-gradient(135deg,#166534,#15803d); }
  .banner.cancelled  { background:linear-gradient(135deg,#6b7280,#4b5563); }
  .banner.default    { background:linear-gradient(135deg,#0D2144,#1a3a6e); }

  .banner-icon {
    width:50px; height:50px;
    background:rgba(255,255,255,.18);
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:20px; color:#fff;
    flex-shrink:0;
  }
  .banner-text h2 { font-size:17px; font-weight:700; color:#fff; }
  .banner-text p  { font-size:12px; color:rgba(255,255,255,.78); margin-top:3px; }

  /* ── Ref badge ──────────────────────────────────────── */
  .ref-badge {
    display:inline-block;
    background:rgba(255,255,255,.15);
    border:1px solid rgba(255,255,255,.30);
    border-radius:99px;
    padding:3px 10px;
    font-size:11px;
    font-family:monospace;
    color:rgba(255,255,255,.92);
    margin-top:6px;
    letter-spacing:.04em;
  }

  /* ── Body ───────────────────────────────────────────── */
  .body { padding:28px 28px 20px; }
  .greeting { font-size:15px; margin-bottom:14px; color:#374151; }
  .greeting strong { color:#0D2144; }

  /* ── Detail table ───────────────────────────────────── */
  .details { margin:0 0 20px; border:1px solid #e5e7eb; border-radius:10px; overflow:hidden; }
  .detail-row {
    display:flex;
    align-items:flex-start;
    padding:11px 16px;
    border-bottom:1px solid #f3f4f6;
    gap:12px;
  }
  .detail-row:last-child { border-bottom:none; }
  .detail-row:nth-child(odd)  { background:#fafbfc; }
  .detail-row:nth-child(even) { background:#fff; }
  .detail-label {
    font-size:10.5px; font-weight:700;
    text-transform:uppercase; letter-spacing:.07em;
    color:#9ca3af;
    flex-shrink:0;
    width:120px;
    padding-top:1px;
  }
  .detail-value {
    font-size:13px; font-weight:500;
    color:#1f2937;
    flex:1;
    text-align:right;
  }
  .detail-value.mono  { font-family:monospace; color:#0D2144; font-weight:700; font-size:14px; }
  .detail-value.gold  { color:#C8861A; font-weight:700; }
  .detail-value.green { color:#16a34a; font-weight:700; }
  .detail-value.red   { color:#dc2626; font-weight:700; }
  .detail-value.muted { color:#9ca3af; }

  /* ── Note boxes ─────────────────────────────────────── */
  .note {
    border-radius:8px;
    padding:14px 16px;
    margin:0 0 16px;
    font-size:13px;
    line-height:1.65;
    color:#374151;
  }
  .note-title {
    font-size:10.5px; font-weight:700;
    text-transform:uppercase; letter-spacing:.07em;
    margin-bottom:7px;
    display:block;
  }
  .note.blue   { background:#eff6ff; border:1px solid #bfdbfe; border-left:4px solid #2563eb; }
  .note.blue   .note-title { color:#1e40af; }
  .note.amber  { background:#fffbeb; border:1px solid #fde68a; border-left:4px solid #C8861A; }
  .note.amber  .note-title { color:#92400e; }
  .note.green  { background:#f0fdf4; border:1px solid #86efac; border-left:4px solid #16a34a; }
  .note.green  .note-title { color:#166534; }
  .note.navy   { background:#f0f4f8; border:1px solid #c7d2e2; border-left:4px solid #0D2144; }
  .note.navy   .note-title { color:#0D2144; }
  .note.gray   { background:#f9fafb; border:1px solid #e5e7eb; border-left:4px solid #9ca3af; }
  .note.gray   .note-title { color:#4b5563; }

  /* ── CTA ─────────────────────────────────────────────── */
  .cta { text-align:center; margin:24px 0 8px; }
  .cta a {
    display:inline-block;
    padding:13px 36px;
    background:#0D2144;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    font-weight:700;
    font-size:14px;
    letter-spacing:.01em;
  }
  .cta-sub {
    text-align:center;
    font-size:12px;
    color:#9ca3af;
    margin-top:10px;
  }
  .cta-sub span { font-family:monospace; font-weight:700; color:#0D2144; }

  /* ── Watermark ───────────────────────────────────────── */
  .wm {
    position:absolute;
    top:50%; left:50%;
    transform:translate(-50%,-50%) rotate(-20deg);
    font-size:120px;
    opacity:.03;
    pointer-events:none;
    z-index:0;
    white-space:nowrap;
    color:#0D2144;
    font-weight:900;
    letter-spacing:.05em;
  }
  .body, .details, .note, .cta, .cta-sub { position:relative; z-index:1; }

  /* ── Footer ──────────────────────────────────────────── */
  .ftr {
    background:#f8fafc;
    border-top:1px solid #e5e7eb;
    padding:16px 28px;
    text-align:center;
    font-size:11px;
    color:#9ca3af;
    line-height:1.75;
  }
  .ftr strong { color:#6b7280; }
  .ftr a { color:#0D2144; text-decoration:none; }

  @media (max-width:600px) {
    body { padding:16px 8px 32px; }
    .hdr { flex-direction:column; text-align:center; gap:10px; }
    .body { padding:20px 18px; }
    .detail-row { flex-direction:column; gap:2px; }
    .detail-value { text-align:left; }
    .detail-label { width:auto; }
  }
</style>
</head>
<body>

@php
  /* ── Banner config ── */
  $bannerClass = match($newStatus) {
    'Submitted'  => 'submitted',
    'Processing' => 'processing',
    'Ready'      => 'ready',
    'Released'   => 'released',
    'Cancelled'  => 'cancelled',
    default      => 'default',
  };
  $bannerIcon = match($newStatus) {
    'Submitted'  => '📋',
    'Processing' => '⚙️',
    'Ready'      => '📦',
    'Released'   => '✅',
    'Cancelled'  => '✖',
    default      => '🔄',
  };
  $bannerTitle = match(true) {
    $type === 'document' && $newStatus === 'Submitted'  => 'Request Received',
    $type === 'document' && $newStatus === 'Processing' => 'Now Processing Your Request',
    $type === 'document' && $newStatus === 'Ready'      => 'Your Document is Ready',
    $type === 'document' && $newStatus === 'Released'   => 'Document Released',
    $type === 'blotter'  && $newStatus === 'Submitted'  => 'Blotter Report Received',
    $type === 'blotter'  && $newStatus === 'Processing' => 'Blotter Under Review',
    $type === 'blotter'  && $newStatus === 'Released'   => 'Blotter Case Activated',
    $type === 'business' && $newStatus === 'Submitted'  => 'Application Received',
    $type === 'business' && $newStatus === 'Processing' => 'Permit Under Review',
    $type === 'business' && $newStatus === 'Released'   => 'Business Permit Issued',
    $newStatus === 'Cancelled' => 'Request Cancelled',
    default => 'Status Updated',
  };
  $bannerSub = match(true) {
    $newStatus === 'Submitted'  => 'Your submission has been logged in our system',
    $newStatus === 'Processing' => 'Our staff are actively working on your request',
    $newStatus === 'Ready'      => 'Proceed to the Barangay Hall to collect',
    $newStatus === 'Released'   => 'Your request has been completed successfully',
    $newStatus === 'Cancelled'  => 'This request was not processed',
    default => 'Please see the details below',
  };

  /* ── Type label ── */
  $typeLabel = match($type) {
    'blotter'  => 'Blotter Report',
    'business' => 'Business Permit',
    default    => 'Document Request',
  };
@endphp

<div class="shell">

  {{-- ━━━ Header ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
  <div class="hdr">
    <div class="hdr-seal" title="Quezon City Seal">🏛️</div>
    <div class="hdr-text">
      <div class="label">Official Communication</div>
      <h1>Barangay New Era</h1>
      <div class="sub">District VI, Quezon City · Resident Portal</div>
    </div>
    <div class="hdr-seal" title="BNE Logo">⚖️</div>
  </div>

  {{-- ━━━ Card ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
  <div class="card">

    {{-- Watermark --}}
    <div class="wm">BNE</div>

    {{-- Status Banner --}}
    <div class="banner {{ $bannerClass }}">
      <div class="banner-icon">{{ $bannerIcon }}</div>
      <div class="banner-text">
        <h2>{{ $bannerTitle }}</h2>
        <p>{{ $bannerSub }}</p>
        <span class="ref-badge">{{ $requestNumber }}</span>
      </div>
    </div>

    {{-- Body --}}
    <div class="body">
      <p class="greeting">Hello, <strong>{{ $residentName }}</strong>.</p>

      {{-- Detail table --}}
      <div class="details">
        <div class="detail-row">
          <span class="detail-label">Reference No.</span>
          <span class="detail-value mono">{{ $requestNumber }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Request Type</span>
          <span class="detail-value">{{ $typeLabel }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Applicant</span>
          <span class="detail-value">{{ $residentName }}</span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Status</span>
          <span class="detail-value
            @if($newStatus === 'Released') green
            @elseif($newStatus === 'Cancelled') red
            @elseif(in_array($newStatus, ['Ready','Submitted'])) gold
            @else muted
            @endif">
            {{ $newStatus }}
          </span>
        </div>
        @if($preferredDate)
        <div class="detail-row">
          <span class="detail-label">{{ $newStatus === 'Ready' ? 'Pick-up Date' : 'Preferred Date' }}</span>
          <span class="detail-value">{{ \Carbon\Carbon::parse($preferredDate)->format('F d, Y') }}</span>
        </div>
        @endif
        @if(isset($feePaid) && $feePaid !== null && ($newStatus === 'Ready' || $newStatus === 'Released'))
        <div class="detail-row">
          <span class="detail-label">Document Fee</span>
          <span class="detail-value {{ $feePaid > 0 ? 'green' : 'muted' }}">
            {{ $feePaid > 0 ? '₱'.number_format($feePaid, 2) : 'Free' }}
          </span>
        </div>
        @endif
        <div class="detail-row">
          <span class="detail-label">Date Updated</span>
          <span class="detail-value muted">{{ now()->format('F d, Y \a\t h:i A') }}</span>
        </div>
      </div>

      {{-- Status-specific notes --}}
      @if($newStatus === 'Submitted')
      <div class="note blue">
        <span class="note-title">📋 What Happens Next?</span>
        Our staff will review your {{ strtolower($typeLabel) }} and begin processing it. You will receive another email once it is ready.
        Keep your reference number safe — you can use it to track your request anytime at the Barangay Portal.
      </div>
      @endif

      @if($newStatus === 'Processing')
      <div class="note amber">
        <span class="note-title">⚙️ In Progress</span>
        Our staff are currently working on your {{ strtolower($typeLabel) }}.
        You will be notified by email as soon as it is ready for collection.
      </div>
      @endif

      @if($newStatus === 'Ready')
      <div class="note green">
        <span class="note-title">📦 Ready for Collection</span>
        Please proceed to the <strong>Barangay Hall</strong> during office hours
        (<strong>Mon–Fri, 8:00 AM – 5:00 PM</strong>). Bring at least
        <strong>one (1) valid government-issued ID</strong>.
        @if($preferredDate)
          Your scheduled pick-up date is
          <strong>{{ \Carbon\Carbon::parse($preferredDate)->format('F d, Y') }}</strong>.
        @endif
        @if(isset($feePaid) && $feePaid > 0)
          <br><br>Please prepare the document fee of
          <strong style="color:#15803d">₱{{ number_format($feePaid, 2) }}</strong>.
        @elseif(isset($feePaid) && $feePaid == 0)
          <br><br>This document is issued <strong>free of charge</strong>.
        @endif
      </div>
      @endif

      @if($newStatus === 'Released')
      <div class="note green">
        <span class="note-title">✅ Transaction Complete</span>
        Your {{ strtolower($typeLabel) }} has been officially released. Please keep it in a safe place.
        If you need another copy in the future, you may file a new request through the portal.
      </div>
      @endif

      @if($newStatus === 'Cancelled')
      <div class="note gray">
        <span class="note-title">✖ Request Not Processed</span>
        This request has been cancelled. If you believe this is an error or would like to re-apply,
        please visit the Barangay Hall or submit a new request through the portal.
      </div>
      @endif

      {{-- Admin note --}}
      @if($notes)
      <div class="note navy">
        <span class="note-title">📌 Note from Barangay Staff</span>
        {{ $notes }}
      </div>
      @endif

      {{-- CTA --}}
      <div class="cta">
        <a href="{{ url('/portal/track?apt='.urlencode($requestNumber)) }}">
          Track Your Request &rarr;
        </a>
      </div>
      <p class="cta-sub">Reference: <span>{{ $requestNumber }}</span></p>
    </div>

    {{-- Footer --}}
    <div class="ftr">
      <strong>Barangay New Era</strong> — New Era, Quezon City, Metro Manila<br>
      Office Hours: Mon–Fri, 8:00 AM – 5:00 PM &nbsp;·&nbsp;
      Punong Barangay: <strong>Robert S. Romano</strong><br>
      This is an automated notification. Do not reply to this email.<br>
      <a href="{{ url('/portal') }}">portal.barangaynewera.gov.ph</a>
    </div>

  </div><!-- /.card -->

</div><!-- /.shell -->
</body>
</html>
