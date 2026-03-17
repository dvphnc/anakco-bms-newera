<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permit Verification — Barangay New Era</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .verify-wrap { width: 100%; max-width: 480px; }

        /* Header bar */
        .verify-header {
            background: #0D2144;
            border-radius: 16px 16px 0 0;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .verify-header img { width: 52px; height: 52px; object-fit: contain; }
        .verify-header-text h1 { font-size: 16px; font-weight: 700; color: #fff; }
        .verify-header-text p { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; }

        /* Main card */
        .verify-card {
            background: #fff;
            border-radius: 0 0 16px 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }

        /* Status banner */
        .status-banner {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .status-banner.valid   { background: linear-gradient(135deg, #16a34a, #15803d); }
        .status-banner.expired { background: linear-gradient(135deg, #dc2626, #b91c1c); }
        .status-banner.suspended { background: linear-gradient(135deg, #d97706, #b45309); }
        .status-banner.notfound { background: linear-gradient(135deg, #6b7280, #4b5563); }

        .status-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; color: #fff; flex-shrink: 0;
        }
        .status-text h2 { font-size: 18px; font-weight: 700; color: #fff; }
        .status-text p  { font-size: 12px; color: rgba(255,255,255,0.8); margin-top: 2px; }

        /* Watermark */
        .verify-card { position: relative; overflow: hidden; }
        .verify-watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-20deg);
            width: 500px; height: 400px;
            object-fit: contain;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }
        .details, .verify-footer, .not-found { position: relative; z-index: 1; }

        /* Details */
        .details { padding: 16px 24px; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #f5f5f5;
            gap: 12px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9ca3af;
            flex-shrink: 0;
            width: 130px;
        }
        .detail-value {
            font-size: 13px;
            font-weight: 500;
            color: #1f2937;
            text-align: right;
        }
        .detail-value.highlight { color: #0D2144; font-weight: 700; }
        .detail-value.expired-text { color: #dc2626; font-weight: 700; }
        .detail-value.valid-text { color: #16a34a; font-weight: 700; }

        /* Footer */
        .verify-footer {
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            padding: 14px 24px;
            text-align: center;
            font-size: 10.5px;
            color: #9ca3af;
            line-height: 1.6;
        }
        .verify-footer strong { color: #6b7280; }

        /* Not found */
        .not-found {
            padding: 32px 24px;
            text-align: center;
        }
        .not-found i { font-size: 48px; color: #d1d5db; margin-bottom: 12px; }
        .not-found h3 { font-size: 16px; color: #374151; margin-bottom:6px; }
        .not-found p { font-size: 12px; color: #9ca3af; }

        .permit-num-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 99px;
            padding: 3px 10px;
            font-size: 10px;
            font-family: monospace;
            color: rgba(255,255,255,0.9);
            margin-top: 4px;
        }
    </style>
</head>
<body>

<div class="verify-wrap">

    {{-- Header --}}
    <div class="verify-header">
        <img src="{{ asset('images/qc-seal.png') }}" alt="QC Seal" style="width:48px;height:48px;object-fit:contain;flex-shrink:0">
        <div class="verify-header-text" style="flex:1">
            <h1>Barangay New Era</h1>
            <p>Official Business Permit Verification</p>
            <span class="permit-num-badge">{{ $permitNumber }}</span>
        </div>
        <img src="{{ asset('images/bne-logo.png') }}" alt="BNE Logo" style="width:48px;height:48px;object-fit:contain;flex-shrink:0">
    </div>

    <div class="verify-card">

        @if(!$business)
        {{-- Not Found --}}
        <div class="status-banner notfound">
            <div class="status-icon"><i class="fas fa-question"></i></div>
            <div class="status-text">
                <h2>Permit Not Found</h2>
                <p>No record matches this permit number</p>
            </div>
        </div>
        <div class="not-found">
            <i class="fas fa-file-circle-xmark"></i>
            <h3>Invalid or Unregistered Permit</h3>
            <p>The permit number <strong>{{ $permitNumber }}</strong> does not exist in our records. This document may be fraudulent.</p>
        </div>

        @else
        @php
            $expiry    = $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date) : null;
            $isValid   = $business->status === 'Active' && $expiry && $expiry->isFuture();
            $isExpired = $business->status === 'Expired' || ($expiry && $expiry->isPast());
            $bannerClass = $isValid ? 'valid' : ($isExpired ? 'expired' : 'suspended');
            $icon = $isValid ? 'fa-circle-check' : ($isExpired ? 'fa-circle-xmark' : 'fa-circle-exclamation');
            $statusText = $isValid ? 'VERIFIED — AUTHENTIC PERMIT' : ($isExpired ? 'EXPIRED PERMIT' : strtoupper($business->status) . ' PERMIT');
            $statusSub  = $isValid
                ? 'This business permit is valid and authentic'
                : ($isExpired ? 'This permit has expired and is no longer valid' : 'This permit is currently ' . strtolower($business->status));
        @endphp

        {{-- Status Banner --}}
        <div class="status-banner {{ $bannerClass }}">
            <div class="status-icon"><i class="fas {{ $icon }}"></i></div>
            <div class="status-text">
                <h2>{{ $statusText }}</h2>
                <p>{{ $statusSub }}</p>
            </div>
        </div>



        {{-- Watermark --}}
        <img src="{{ asset('images/bne-logo.png') }}" class="verify-watermark" alt="">

        {{-- Details --}}
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Permit No.</span>
                <span class="detail-value highlight">{{ $business->permit_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Business Name</span>
                <span class="detail-value highlight">{{ $business->business_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Business Type</span>
                <span class="detail-value">{{ $business->business_type }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Owner</span>
                <span class="detail-value">{{ $business->owner_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Address</span>
                <span class="detail-value">{{ $business->business_address }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date Issued</span>
                <span class="detail-value">{{ $business->permit_date ? \Carbon\Carbon::parse($business->permit_date)->format('F d, Y') : '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Valid Until</span>
                <span class="detail-value {{ $isExpired ? 'expired-text' : 'valid-text' }}">
                    {{ $expiry?->format('F d, Y') ?? '—' }}
                    @if($isValid) &nbsp;<i class="fas fa-check-circle"></i> @endif
                    @if($isExpired) &nbsp;<i class="fas fa-times-circle"></i> @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value {{ $isValid ? 'valid-text' : 'expired-text' }}">
                    {{ $business->status }}
                </span>
            </div>
        </div>

        @endif

        {{-- Footer --}}
        <div class="verify-footer">
            <strong>Barangay New Era</strong> — New Era, Quezon City, Metro Manila<br>
            Verified on {{ now()->format('F d, Y \a\t h:i A') }}<br>
            This verification is issued by the official Barangay New Era Management System.
        </div>

    </div>

</div>

</body>
</html>