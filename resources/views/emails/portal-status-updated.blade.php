<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
  @if($newStatus === 'Submitted') Request Received
  @elseif($newStatus === 'Processing') Now Processing
  @elseif($newStatus === 'Ready') Ready for Pick-up
  @elseif($newStatus === 'Released') Document Released
  @else Request Status Update
  @endif
  — Barangay New Era
</title>
<style>
  body { margin:0; padding:0; font-family:'Segoe UI',Arial,sans-serif; background:#f3f4f6; color:#1a2332; }
  .wrap { max-width:580px; margin:32px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,.08); }
  .hdr { background:#0D2144; padding:28px 32px; text-align:center; }
  .hdr-logo { font-size:13px; font-weight:700; color:rgba(255,255,255,.6); letter-spacing:.08em; text-transform:uppercase; }
  .hdr h1 { margin:8px 0 0; font-size:20px; font-weight:700; color:#fff; }
  .body { padding:32px; }
  .status-chip { display:inline-flex; align-items:center; gap:8px; padding:8px 18px; border-radius:20px; font-size:14px; font-weight:700; margin:4px 0 20px; }
  .chip-submitted  { background:#fdf3e3; color:#8a5e10; border:1px solid #e8c47a; }
  .chip-processing { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
  .chip-ready      { background:#ecfdf5; color:#166534; border:1px solid #86efac; }
  .chip-released   { background:#f0fdf4; color:#15803d; border:1px solid #86efac; }
  .chip-cancelled  { background:#fef2f2; color:#b91c1c; border:1px solid #fca5a5; }
  .chip-default    { background:#e8edf5; color:#0D2144; border:1px solid #c7d2e2; }
  .detail-box { background:#f8f9fa; border:1px solid #e2e6ea; border-radius:8px; padding:18px 20px; margin:20px 0; }
  .detail-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid #eee; font-size:14px; }
  .detail-row:last-child { border-bottom:none; }
  .detail-label { color:#6b7280; font-weight:500; }
  .detail-value { color:#1a2332; font-weight:600; text-align:right; max-width:60%; }
  .note-box { border-radius:8px; padding:14px 18px; margin:20px 0; font-size:14px; line-height:1.6; }
  .note-staff   { background:#fdf3e3; border:1px solid #e8c47a; border-left:4px solid #C8861A; }
  .note-staff strong  { color:#8a5e10; display:block; margin-bottom:4px; font-size:12px; text-transform:uppercase; letter-spacing:.06em; }
  .note-ready   { background:#ecfdf5; border:1px solid #86efac; border-left:4px solid #16a34a; }
  .note-ready strong  { color:#166534; display:block; margin-bottom:4px; font-size:12px; text-transform:uppercase; letter-spacing:.06em; }
  .note-submit  { background:#eff6ff; border:1px solid #bfdbfe; border-left:4px solid #2563eb; }
  .note-submit strong { color:#1e40af; display:block; margin-bottom:4px; font-size:12px; text-transform:uppercase; letter-spacing:.06em; }
  .note-release { background:#f0fdf4; border:1px solid #86efac; border-left:4px solid #15803d; }
  .note-release strong { color:#15803d; display:block; margin-bottom:4px; font-size:12px; text-transform:uppercase; letter-spacing:.06em; }
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

  {{-- ── Header ─────────────────────────────────────────────────────── --}}
  <div class="hdr">
    <div class="hdr-logo">Barangay New Era · District VI, Quezon City</div>
    <h1>
      @if($type === 'document')
        @if($newStatus === 'Submitted') Request Received
        @elseif($newStatus === 'Processing') Now Processing Your Request
        @elseif($newStatus === 'Ready') Ready for Pick-up
        @elseif($newStatus === 'Released') Document Released
        @else Document Request Update
        @endif
      @elseif($type === 'blotter')
        Blotter Report Update
      @else
        Business Permit Request Update
      @endif
    </h1>
  </div>

  {{-- ── Body ──────────────────────────────────────────────────────── --}}
  <div class="body">
    <p style="font-size:15px;margin-bottom:4px">Hello, <strong>{{ $residentName }}</strong>.</p>
    <p style="font-size:14px;color:#6b7280;margin-top:4px">
      @if($newStatus === 'Submitted')
        Thank you for submitting your request through the Barangay New Era Resident Portal.
      @elseif($newStatus === 'Processing')
        Your
        @if($type === 'document') document request
        @elseif($type === 'blotter') blotter report
        @else business permit request
        @endif
        is now being processed by our staff.
      @elseif($newStatus === 'Ready')
        Great news — your
        @if($type === 'document') document
        @elseif($type === 'blotter') blotter
        @else permit
        @endif
        is ready for collection.
      @elseif($newStatus === 'Released')
        Your
        @if($type === 'document') document
        @elseif($type === 'blotter') case record
        @else permit
        @endif
        has been officially released. Thank you.
      @else
        Your
        @if($type === 'document') document request
        @elseif($type === 'blotter') blotter report
        @else business permit request
        @endif
        status has been updated:
      @endif
    </p>

    {{-- Status chip --}}
    @php
      $chipCls = match($newStatus) {
        'Submitted'  => 'chip-submitted',
        'Processing' => 'chip-processing',
        'Ready'      => 'chip-ready',
        'Released'   => 'chip-released',
        'Cancelled'  => 'chip-cancelled',
        default      => 'chip-default',
      };
      $chipIcon = match($newStatus) {
        'Submitted'  => '📋',
        'Processing' => '⚙️',
        'Ready'      => '📦',
        'Released'   => '✅',
        'Cancelled'  => '❌',
        default      => '🔄',
      };
    @endphp
    <div class="status-chip {{ $chipCls }}">
      {{ $chipIcon }} {{ $newStatus }}
    </div>

    {{-- Detail box --}}
    <div class="detail-box">
      <div class="detail-row">
        <span class="detail-label">Reference No.</span>
        <span class="detail-value" style="font-family:monospace;color:#0D2144;font-size:15px">{{ $requestNumber }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Name</span>
        <span class="detail-value">{{ $residentName }}</span>
      </div>
      <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-value">{{ $newStatus }}</span>
      </div>
      @if($preferredDate)
      <div class="detail-row">
        <span class="detail-label">{{ $newStatus === 'Ready' ? 'Pick-up Date' : 'Preferred Date' }}</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($preferredDate)->format('F d, Y') }}</span>
      </div>
      @endif
      @if($newStatus === 'Ready' && isset($feePaid))
      <div class="detail-row">
        <span class="detail-label">Document Fee</span>
        <span class="detail-value" style="color:#15803d;font-weight:700">
          {{ $feePaid > 0 ? '₱' . number_format($feePaid, 2) : 'Free' }}
        </span>
      </div>
      @endif
      <div class="detail-row">
        <span class="detail-label">Date Updated</span>
        <span class="detail-value">{{ now()->format('F d, Y \a\t h:i A') }}</span>
      </div>
    </div>

    {{-- Status-specific context blocks --}}
    @if($newStatus === 'Submitted')
    <div class="note-box note-submit">
      <strong>📋 What Happens Next?</strong>
      Our staff will review your request and begin processing it. You will receive another email when your document is ready for pick-up.
      Keep your reference number safe — you can use it to track your request anytime at the Barangay Portal.
    </div>
    @endif

    @if($newStatus === 'Processing')
    <div class="note-box note-staff">
      <strong>⚙️ In Progress</strong>
      Our staff are currently preparing your document. You will be notified by email once it is ready for collection. Estimated completion time depends on the document type.
    </div>
    @endif

    @if($newStatus === 'Ready')
    <div class="note-box note-ready">
      <strong>📦 Ready for Pick-up</strong>
      Please proceed to the Barangay Hall during office hours (<strong>Monday–Friday, 8:00 AM – 5:00 PM</strong>).
      Bring at least <strong>one (1) valid government-issued ID</strong>.
      @if($preferredDate)
        Your scheduled pick-up date is <strong>{{ \Carbon\Carbon::parse($preferredDate)->format('F d, Y') }}</strong>.
      @endif
      @if(isset($feePaid) && $feePaid > 0)
        <br>Please prepare the document fee of <strong style="color:#15803d">₱{{ number_format($feePaid, 2) }}</strong>.
      @elseif(isset($feePaid) && $feePaid == 0)
        <br>This document is issued <strong>free of charge</strong>.
      @endif
    </div>
    @endif

    @if($newStatus === 'Released')
    <div class="note-box note-release">
      <strong>✅ Transaction Complete</strong>
      Your document has been officially released and collected. Please keep it in a safe place. If you need another copy in the future, you may file a new request through the portal.
    </div>
    @endif

    {{-- Admin note (shown for any status if notes are present) --}}
    @if($notes)
    <div class="note-box note-staff">
      <strong>📋 Note from Barangay Staff</strong>
      {{ $notes }}
    </div>
    @endif

    {{-- CTA --}}
    <div class="cta">
      <a href="{{ url('/portal/track?apt='.urlencode($requestNumber)) }}">
        Track Your Request
      </a>
    </div>

    <p style="font-size:13px;color:#9ca3af;text-align:center;margin-top:12px">
      Reference: <strong style="color:#0D2144;font-family:monospace">{{ $requestNumber }}</strong>
    </p>
  </div>

  {{-- ── Footer ──────────────────────────────────────────────────────── --}}
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
