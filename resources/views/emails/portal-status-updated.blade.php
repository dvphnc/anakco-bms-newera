<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Barangay New Era — Status Update</title>
<!--[if mso]>
<noscript>
<xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml>
</noscript>
<![endif]-->
</head>
<body style="margin:0;padding:0;background:#eef2f7;font-family:'Segoe UI',Arial,sans-serif;-webkit-font-smoothing:antialiased;">

@php
  /* ── Banner config ── */
  $bannerGrad = match($newStatus) {
    'Submitted'  => '#1e40af',
    'Processing' => '#0369a1',
    'Ready'      => '#15803d',
    'Released'   => '#166534',
    'Cancelled'  => '#4b5563',
    default      => '#0D2144',
  };
  $bannerIcon = match($newStatus) {
    'Submitted'  => '📋', 'Processing' => '⚙️',
    'Ready'      => '📦', 'Released'   => '✅',
    'Cancelled'  => '✖',  default      => '🔄',
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
  $typeLabel = match($type) {
    'blotter'  => 'Blotter Report',
    'business' => 'Business Permit',
    default    => 'Document Request',
  };
  $statusColor = match($newStatus) {
    'Released'   => '#16a34a',
    'Cancelled'  => '#dc2626',
    'Ready'      => '#C8861A',
    'Submitted'  => '#C8861A',
    default      => '#6b7280',
  };
@endphp

{{-- ── Outer wrapper ── --}}
<table width="100%" cellpadding="0" cellspacing="0" border="0"
       style="background:#eef2f7;padding:32px 16px 48px;">
  <tr>
    <td align="center">

      {{-- ── Shell (max 560px) ── --}}
      <table width="560" cellpadding="0" cellspacing="0" border="0"
             style="max-width:560px;width:100%;border-radius:16px;overflow:hidden;
                    background:#ffffff;box-shadow:0 8px 32px rgba(0,0,0,.13);">

        {{-- ── HEADER ── --}}
        <tr>
          <td style="background:#0D2144;padding:20px 28px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                {{-- Left seal --}}
                <td width="52" valign="middle" style="padding-right:12px;">
                  <div style="width:52px;height:52px;background:rgba(255,255,255,.10);
                              border:2px solid rgba(255,255,255,.20);border-radius:50%;
                              text-align:center;line-height:52px;font-size:24px;">🏛️</div>
                </td>
                {{-- Center text --}}
                <td valign="middle" align="center" style="padding:0 8px;">
                  <div style="font-size:10px;font-weight:700;color:rgba(255,255,255,.55);
                              letter-spacing:.10em;text-transform:uppercase;margin-bottom:4px;">
                    Official Communication
                  </div>
                  <div style="font-size:18px;font-weight:700;color:#ffffff;
                              letter-spacing:.01em;margin-bottom:2px;">
                    Barangay New Era
                  </div>
                  <div style="font-size:11px;color:rgba(255,255,255,.45);">
                    District VI, Quezon City &middot; Resident Portal
                  </div>
                </td>
                {{-- Right seal --}}
                <td width="52" valign="middle" style="padding-left:12px;">
                  <div style="width:52px;height:52px;background:rgba(255,255,255,.10);
                              border:2px solid rgba(255,255,255,.20);border-radius:50%;
                              text-align:center;line-height:52px;font-size:24px;">⚖️</div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── STATUS BANNER ── --}}
        <tr>
          <td style="background:{{ $bannerGrad }};padding:20px 28px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                {{-- Icon circle --}}
                <td width="54" valign="middle" style="padding-right:16px;">
                  <div style="width:50px;height:50px;background:rgba(255,255,255,.18);
                              border-radius:50%;text-align:center;line-height:50px;font-size:20px;">
                    {{ $bannerIcon }}
                  </div>
                </td>
                {{-- Title + sub + ref --}}
                <td valign="middle">
                  <div style="font-size:17px;font-weight:700;color:#ffffff;margin-bottom:3px;">
                    {{ $bannerTitle }}
                  </div>
                  <div style="font-size:12px;color:rgba(255,255,255,.78);margin-bottom:7px;">
                    {{ $bannerSub }}
                  </div>
                  <span style="display:inline-block;background:rgba(255,255,255,.15);
                               border:1px solid rgba(255,255,255,.30);border-radius:99px;
                               padding:3px 10px;font-size:11px;font-family:monospace;
                               color:rgba(255,255,255,.92);letter-spacing:.04em;">
                    {{ $requestNumber }}
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        {{-- ── BODY ── --}}
        <tr>
          <td style="background:#ffffff;padding:28px 28px 8px;">

            {{-- Greeting --}}
            <p style="font-size:15px;color:#374151;margin:0 0 18px;">
              Hello, <strong style="color:#0D2144;">{{ $residentName }}</strong>.
            </p>

            {{-- Detail table --}}
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;margin-bottom:20px;">

              {{-- Reference No. --}}
              <tr style="background:#fafbfc;">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;width:130px;border-bottom:1px solid #f3f4f6;
                           vertical-align:middle;">
                  Reference No.
                </td>
                <td style="padding:11px 16px;font-size:14px;font-weight:700;font-family:monospace;
                           color:#0D2144;text-align:right;border-bottom:1px solid #f3f4f6;
                           vertical-align:middle;">
                  {{ $requestNumber }}
                </td>
              </tr>

              {{-- Request Type --}}
              <tr style="background:#ffffff;">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;border-bottom:1px solid #f3f4f6;
                           vertical-align:middle;">
                  Request Type
                </td>
                <td style="padding:11px 16px;font-size:13px;font-weight:500;color:#1f2937;
                           text-align:right;border-bottom:1px solid #f3f4f6;vertical-align:middle;">
                  {{ $typeLabel }}
                </td>
              </tr>

              {{-- Applicant --}}
              <tr style="background:#fafbfc;">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;border-bottom:1px solid #f3f4f6;
                           vertical-align:middle;">
                  Applicant
                </td>
                <td style="padding:11px 16px;font-size:13px;font-weight:500;color:#1f2937;
                           text-align:right;border-bottom:1px solid #f3f4f6;vertical-align:middle;">
                  {{ $residentName }}
                </td>
              </tr>

              {{-- Status --}}
              <tr style="background:#ffffff;">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;
                           {{ $preferredDate || (isset($feePaid) && $feePaid !== null) ? 'border-bottom:1px solid #f3f4f6;' : '' }}
                           vertical-align:middle;">
                  Status
                </td>
                <td style="padding:11px 16px;font-size:13px;font-weight:700;color:{{ $statusColor }};
                           text-align:right;
                           {{ $preferredDate || (isset($feePaid) && $feePaid !== null) ? 'border-bottom:1px solid #f3f4f6;' : '' }}
                           vertical-align:middle;">
                  {{ $newStatus }}
                </td>
              </tr>

              @if($preferredDate)
              {{-- Preferred / Pick-up Date --}}
              <tr style="background:#fafbfc;">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;
                           {{ (isset($feePaid) && $feePaid !== null) ? 'border-bottom:1px solid #f3f4f6;' : '' }}
                           vertical-align:middle;">
                  {{ $newStatus === 'Ready' ? 'Pick-up Date' : 'Preferred Date' }}
                </td>
                <td style="padding:11px 16px;font-size:13px;font-weight:500;color:#1f2937;
                           text-align:right;
                           {{ (isset($feePaid) && $feePaid !== null) ? 'border-bottom:1px solid #f3f4f6;' : '' }}
                           vertical-align:middle;">
                  {{ \Carbon\Carbon::parse($preferredDate)->format('m/d/Y') }}
                </td>
              </tr>
              @endif

              @if(isset($feePaid) && $feePaid !== null && in_array($newStatus, ['Ready','Released']))
              {{-- Document Fee --}}
              <tr style="background:{{ $preferredDate ? '#ffffff' : '#fafbfc' }};">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;vertical-align:middle;">
                  Document Fee
                </td>
                <td style="padding:11px 16px;font-size:13px;font-weight:700;vertical-align:middle;
                           color:{{ $feePaid > 0 ? '#16a34a' : '#9ca3af' }};text-align:right;">
                  {{ $feePaid > 0 ? '₱'.number_format($feePaid, 2) : 'Free' }}
                </td>
              </tr>
              @endif

              {{-- Date Updated --}}
              <tr style="background:#fafbfc;">
                <td style="padding:11px 16px;font-size:10.5px;font-weight:700;text-transform:uppercase;
                           letter-spacing:.07em;color:#9ca3af;vertical-align:middle;">
                  Date Updated
                </td>
                <td style="padding:11px 16px;font-size:13px;font-weight:400;color:#9ca3af;
                           text-align:right;vertical-align:middle;">
                  {{ now()->format('m/d/Y \a\t h:i A') }}
                </td>
              </tr>

            </table><!-- /.details -->

          </td>
        </tr>

        {{-- ── STATUS NOTES ── --}}
        @if($newStatus === 'Submitted')
        <tr>
          <td style="background:#ffffff;padding:0 28px 16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#eff6ff;border:1px solid #bfdbfe;
                          border-left:4px solid #2563eb;border-radius:8px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;line-height:1.65;color:#374151;">
                  <span style="display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.07em;color:#1e40af;margin-bottom:7px;">
                    📋 What Happens Next?
                  </span>
                  Our staff will review your {{ strtolower($typeLabel) }} and begin processing it.
                  You will receive another email once it is ready. Keep your reference number safe —
                  you can use it to track your request anytime at the Barangay Portal.
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

        @if($newStatus === 'Processing')
        <tr>
          <td style="background:#ffffff;padding:0 28px 16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#fffbeb;border:1px solid #fde68a;
                          border-left:4px solid #C8861A;border-radius:8px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;line-height:1.65;color:#374151;">
                  <span style="display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.07em;color:#92400e;margin-bottom:7px;">
                    ⚙️ In Progress
                  </span>
                  Our staff are currently working on your {{ strtolower($typeLabel) }}.
                  You will be notified by email as soon as it is ready for collection.
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

        @if($newStatus === 'Ready')
        <tr>
          <td style="background:#ffffff;padding:0 28px 16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#f0fdf4;border:1px solid #86efac;
                          border-left:4px solid #16a34a;border-radius:8px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;line-height:1.65;color:#374151;">
                  <span style="display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.07em;color:#166534;margin-bottom:7px;">
                    📦 Ready for Collection
                  </span>
                  Please proceed to the <strong>Barangay Hall</strong> during office hours
                  (<strong>Mon–Fri, 8:00 AM – 5:00 PM</strong>). Bring at least
                  <strong>one (1) valid government-issued ID</strong>.
                  @if($preferredDate)
                    Your scheduled pick-up date is
                    <strong>{{ \Carbon\Carbon::parse($preferredDate)->format('m/d/Y') }}</strong>.
                  @endif
                  @if(isset($feePaid) && $feePaid > 0)
                    <br><br>Please prepare the document fee of
                    <strong style="color:#15803d;">₱{{ number_format($feePaid, 2) }}</strong>.
                  @elseif(isset($feePaid) && $feePaid == 0)
                    <br><br>This document is issued <strong>free of charge</strong>.
                  @endif
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

        @if($newStatus === 'Released')
        <tr>
          <td style="background:#ffffff;padding:0 28px 16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#f0fdf4;border:1px solid #86efac;
                          border-left:4px solid #16a34a;border-radius:8px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;line-height:1.65;color:#374151;">
                  <span style="display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.07em;color:#166534;margin-bottom:7px;">
                    ✅ Transaction Complete
                  </span>
                  Your {{ strtolower($typeLabel) }} has been officially released. Please keep it in a safe place.
                  If you need another copy in the future, you may file a new request through the portal.
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

        @if($newStatus === 'Cancelled')
        <tr>
          <td style="background:#ffffff;padding:0 28px 16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#f9fafb;border:1px solid #e5e7eb;
                          border-left:4px solid #9ca3af;border-radius:8px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;line-height:1.65;color:#374151;">
                  <span style="display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.07em;color:#4b5563;margin-bottom:7px;">
                    ✖ Request Not Processed
                  </span>
                  This request has been cancelled. If you believe this is an error or would like to re-apply,
                  please visit the Barangay Hall or submit a new request through the portal.
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

        {{-- Admin note --}}
        @if($notes)
        <tr>
          <td style="background:#ffffff;padding:0 28px 16px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                   style="background:#f0f4f8;border:1px solid #c7d2e2;
                          border-left:4px solid #0D2144;border-radius:8px;">
              <tr>
                <td style="padding:14px 16px;font-size:13px;line-height:1.65;color:#374151;">
                  <span style="display:block;font-size:10.5px;font-weight:700;text-transform:uppercase;
                               letter-spacing:.07em;color:#0D2144;margin-bottom:7px;">
                    📌 Note from Barangay Staff
                  </span>
                  {{ $notes }}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

        {{-- ── CTA Button ── --}}
        <tr>
          <td style="background:#ffffff;padding:20px 28px 8px;text-align:center;">
            <a href="{{ url('/portal/track?apt='.urlencode($requestNumber)) }}"
               style="display:inline-block;padding:13px 36px;background:#0D2144;color:#ffffff;
                      text-decoration:none;border-radius:8px;font-weight:700;font-size:14px;
                      letter-spacing:.01em;">
              Track Your Request &rarr;
            </a>
            <p style="margin:10px 0 0;font-size:12px;color:#9ca3af;">
              Reference: <span style="font-family:monospace;font-weight:700;color:#0D2144;">{{ $requestNumber }}</span>
            </p>
          </td>
        </tr>

        {{-- ── FOOTER ── --}}
        <tr>
          <td style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:18px 28px;
                     text-align:center;font-size:11px;color:#9ca3af;line-height:1.8;">
            <strong style="color:#6b7280;">Barangay New Era</strong>
            &mdash; New Era, Quezon City, Metro Manila<br>
            Office Hours: Mon&ndash;Fri, 8:00 AM &ndash; 5:00 PM
            &nbsp;&middot;&nbsp;
            Punong Barangay: <strong style="color:#6b7280;">Robert S. Romano</strong><br>
            This is an automated notification. Do not reply to this email.<br>
            <a href="{{ url('/portal') }}"
               style="color:#0D2144;text-decoration:none;">portal.barangaynewera.gov.ph</a>
          </td>
        </tr>

      </table><!-- /.shell -->

    </td>
  </tr>
</table><!-- /outer -->

</body>
</html>
