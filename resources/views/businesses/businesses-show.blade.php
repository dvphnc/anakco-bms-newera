@extends('layouts.app')

@section('title', $business->business_name)

@section('content')

<div class="page-header no-print">
    <div>
        <h1 class="page-title">Business Profile</h1>
        <p class="page-subtitle">{{ $business->permit_number }} — {{ $business->business_name }}</p>
    </div>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Permit
        </button>
        <a href="{{ route('businesses.edit', $business) }}" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('businesses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

{{-- Screen View --}}
<div class="no-print" style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:var(--radius);background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-store" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:12px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.45);margin-bottom:4px">
                    {{ $business->permit_number }}
                </div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.3;margin-bottom:6px">
                    {{ $business->business_name }}
                </div>
                <div style="font-size:13px;color:rgba(255,255,255,0.5);margin-bottom:12px">
                    {{ $business->business_type }}
                </div>
                @php
                    $cls = match($business->status) {
                        'Active'    => 'badge-green',
                        'Expired'   => 'badge-red',
                        'Suspended' => 'badge-yellow',
                        'Cancelled' => 'badge-gray',
                        default     => 'badge-gray'
                    };
                @endphp
                <span class="badge {{ $cls }}">{{ $business->status }}</span>
            </div>

            {{-- QR Code --}}
            <div style="padding:16px 20px;border-top:1px solid var(--border);text-align:center">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">
                    QR Code
                </div>
                <a href="{{ url('/verify/business/' . $business->permit_number) }}" target="_blank" title="Click to verify this permit">
                    {!! QrCode::size(140)->generate(
                        url('/verify/business/' . $business->permit_number)
                    ) !!}
                </a>
                <div style="font-size:13px;color:var(--text-muted);margin-top:6px">
                    Scan to verify &nbsp;·&nbsp;
                    <a href="{{ url('/verify/business/' . $business->permit_number) }}" target="_blank" style="color:var(--navy);font-weight:600">
                        Open link <i class="fas fa-arrow-up-right-from-square" style="font-size:9px"></i>
                    </a>
                </div>
            </div>

            {{-- Permit validity bar --}}
            @if($business->permit_date && $business->expiry_date)
            @php
                $start   = \Carbon\Carbon::parse($business->permit_date);
                $end     = \Carbon\Carbon::parse($business->expiry_date);
                $total   = $start->diffInDays($end);
                $elapsed = $start->diffInDays(now());
                $pct     = $total > 0 ? min(100, round(($elapsed / $total) * 100)) : 100;
                $color   = $pct >= 90 ? 'var(--crimson)' : ($pct >= 70 ? 'var(--gold)' : '#16a34a');
            @endphp
            <div style="padding:14px 16px;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-subtle);margin-bottom:6px">
                    <span>Validity</span>
                    <span>{{ $end->isPast() ? 'Expired' : $end->diffForHumans() }}</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                </div>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('businesses.edit', $business) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Permit
                </a>
                @if($business->status !== 'Active')
                <form method="POST" action="{{ route('businesses.update', $business) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="Active">
                    <input type="hidden" name="business_name" value="{{ $business->business_name }}">
                    <input type="hidden" name="business_type" value="{{ $business->business_type }}">
                    <input type="hidden" name="business_address" value="{{ $business->business_address }}">
                    <input type="hidden" name="owner_name" value="{{ $business->owner_name }}">
                    <input type="hidden" name="permit_date" value="{{ $business->permit_date }}">
                    <input type="hidden" name="expiry_date" value="{{ $business->expiry_date }}">
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-check-circle"></i> Mark Active
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('businesses.destroy', $business) }}"
                      data-confirm="Delete permit for {{ $business->business_name }}? This cannot be undone."
                      data-confirm-title="Delete Business Permit"
                      data-confirm-ok="Delete">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle"></i> Permit Details</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @php
                    $details = [
                        ['label' => 'Permit No.',       'value' => $business->permit_number],
                        ['label' => 'Business Name',    'value' => $business->business_name],
                        ['label' => 'Business Type',    'value' => $business->business_type],
                        ['label' => 'Status',           'value' => $business->status],
                        ['label' => 'Owner Name',       'value' => $business->owner_name],
                        ['label' => 'Owner Contact',    'value' => $business->owner_contact ?? '—'],
                        ['label' => 'Permit Date',      'value' => $business->permit_date ? \Carbon\Carbon::parse($business->permit_date)->format('F d, Y') : '—'],
                        ['label' => 'Expiry Date',      'value' => $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date)->format('F d, Y') : '—'],
                        ['label' => 'Issued By',        'value' => $business->issuedBy->name ?? '—'],
                        ['label' => 'Date Registered',  'value' => $business->created_at->format('F d, Y')],
                    ];
                @endphp
                @foreach($details as $d)
                <div style="padding:10px 0;border-bottom:1px solid var(--border);{{ $loop->even ? 'padding-left:24px' : '' }}">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">
                        {{ $d['label'] }}
                    </div>
                    <div style="font-size:15px;color:var(--text);font-weight:500">{{ $d['value'] }}</div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">Business Address</div>
                <div style="font-size:15px;color:var(--text)">{{ $business->business_address }}</div>
            </div>
            @if($business->ownerResident)
            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Linked Resident</div>
                <a href="{{ route('residents.show', $business->ownerResident) }}"
                   style="display:flex;align-items:center;gap:10px;color:var(--navy)">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        {{ strtoupper(substr($business->ownerResident->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:14px">{{ $business->ownerResident->full_name }}</div>
                        <div class="td-muted">View resident profile →</div>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- =============================================
     PRINTABLE BUSINESS PERMIT
============================================= --}}
<div class="print-only" id="permit">
<style>
@media print {
    @page { size: letter; margin: 0.5in 0.7in; }
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    header, nav, footer, .sidebar, .topbar, .page-header { display: none !important; }
}
.print-only { display: none; }

.permit-page {
    font-family: 'Times New Roman', Times, serif;
    color: #000;
    width: 100%;
    max-width: 7.1in;
    margin: 0 auto;
}
.permit-border {
    border: 3px double #1a3a6b;
    padding: 24px 36px;
    position: relative;
}
.permit-border::before {
    content: '';
    position: absolute;
    top: 5px; left: 5px; right: 5px; bottom: 5px;
    border: 1px solid #c8861a;
    pointer-events: none;
}
.permit-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
    padding-bottom: 10px;
    border-bottom: 2px solid #1a3a6b;
}
.permit-logo { width: 68px; height: 68px; object-fit: contain; flex-shrink: 0; }
.permit-titles { text-align: center; flex: 1; padding: 0 12px; }
.permit-titles .republic  { font-size: 10pt; font-style: italic; }
.permit-titles .province  { font-size: 9.5pt; }
.permit-titles .barangay  { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.08em; color: #0d2144; }
.permit-titles .office    { font-size: 9.5pt; font-style: italic; }
.permit-titles .address   { font-size: 8.5pt; color: #444; }
.permit-type {
    text-align: center;
    font-size: 16pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #0d2144;
    text-decoration: underline;
    margin: 14px 0 4px;
}
.permit-num {
    text-align: right;
    font-size: 8.5pt;
    color: #555;
    margin-bottom: 12px;
}
.permit-body {
    display: flex;
    gap: 24px;
    margin: 12px 0;
}
.permit-details {
    flex: 1;
}
.permit-row {
    display: flex;
    border-bottom: 1px solid #e0e0e0;
    padding: 6px 0;
    font-size: 10.5pt;
}
.permit-row .lbl {
    width: 160px;
    font-weight: bold;
    color: #333;
    flex-shrink: 0;
}
.permit-row .val { flex: 1; }
.permit-qr {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.permit-qr img { width: 120px; height: 120px; }
.permit-qr .qr-label { font-size: 7.5pt; color: #555; text-align: center; }
.permit-validity {
    margin-top: 12px;
    padding: 10px 14px;
    background: #f8f9fb;
    border: 1px solid #1a3a6b;
    border-radius: 4px;
    font-size: 10pt;
}
.permit-footer {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}
.permit-conditions {
    font-size: 7.5pt;
    color: #555;
    max-width: 4in;
    line-height: 1.5;
}
.permit-sig { text-align: center; min-width: 200px; }
.permit-sig-line { border-top: 1px solid #000; margin-bottom: 3px; }
.permit-sig-name { font-size: 11pt; font-weight: bold; text-transform: uppercase; }
.permit-sig-title { font-size: 9pt; color: #333; }
.permit-watermark {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-20deg);
    opacity: 0.05;
    width: 400px; height: 400px;
    object-fit: contain;
    pointer-events: none;
}
.permit-content { position: relative; z-index: 1; }
@php
    $permitOfficialName = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $permitExpiry = $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date) : null;
    $permitDate   = $business->permit_date ? \Carbon\Carbon::parse($business->permit_date) : null;
@endphp
</style>

@php
    $permitOfficialName = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $permitExpiry = $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date) : null;
    $permitDate   = $business->permit_date ? \Carbon\Carbon::parse($business->permit_date) : null;
@endphp

<div class="permit-page">
    <div class="permit-border">
        <img src="{{ asset('images/bne-logo.png') }}" class="permit-watermark" alt="">
        <div class="permit-content">

            {{-- Header --}}
            <div class="permit-header">
                <img src="{{ asset('images/qc-seal.png') }}" class="permit-logo" alt="QC Seal">
                <div class="permit-titles">
                    <div class="republic"><em>Republic of the Philippines</em></div>
                    <div class="province">City of Quezon, National Capital Region</div>
                    <div class="barangay">Barangay New Era</div>
                    <div class="office">Office of the Punong Barangay</div>
                    <div class="address">New Era, Quezon City, Metro Manila</div>
                </div>
                <img src="{{ asset('images/bne-logo.png') }}" class="permit-logo" alt="BNE Seal">
            </div>

            <div class="permit-type">Barangay Business Clearance</div>
            <div class="permit-num">Permit No.: {{ $business->permit_number }}</div>

            {{-- Body: details + QR --}}
            <div class="permit-body">
                <div class="permit-details">
                    <div class="permit-row"><span class="lbl">Business Name:</span><span class="val"><strong>{{ $business->business_name }}</strong></span></div>
                    <div class="permit-row"><span class="lbl">Business Type:</span><span class="val">{{ $business->business_type }}</span></div>
                    <div class="permit-row"><span class="lbl">Business Address:</span><span class="val">{{ $business->business_address }}</span></div>
                    <div class="permit-row"><span class="lbl">Owner / Operator:</span><span class="val"><strong>{{ $business->owner_name }}</strong></span></div>
                    <div class="permit-row"><span class="lbl">Contact Number:</span><span class="val">{{ $business->owner_contact ?? '—' }}</span></div>
                    <div class="permit-row"><span class="lbl">Date Issued:</span><span class="val">{{ $permitDate?->format('F d, Y') ?? '—' }}</span></div>
                    <div class="permit-row"><span class="lbl">Valid Until:</span><span class="val"><strong>{{ $permitExpiry?->format('F d, Y') ?? '—' }}</strong></span></div>
                    <div class="permit-row"><span class="lbl">Status:</span><span class="val"><strong>{{ $business->status }}</strong></span></div>
                </div>
                <div class="permit-qr">
                    {!! QrCode::size(120)->generate(
                        url('/verify/business/' . $business->permit_number)
                    ) !!}
                    <div class="qr-label">Scan to verify<br>this permit</div>
                </div>
            </div>

            {{-- Validity notice --}}
            <div class="permit-validity">
                This barangay business clearance is issued to the above-named establishment and is
                <strong>valid from {{ $permitDate?->format('F d, Y') ?? '—' }} to {{ $permitExpiry?->format('F d, Y') ?? '—' }}</strong>,
                subject to compliance with all applicable barangay ordinances and regulations.
            </div>

            {{-- Footer --}}
            <div class="permit-footer">
                <div class="permit-conditions">
                    <strong>Conditions:</strong><br>
                    &#9679; This clearance is non-transferable and valid only for the business stated above.<br>
                    &#9679; Any change in business activity, ownership, or location requires a new clearance.<br>
                    &#9679; This document must be posted in a conspicuous place within the business premises.<br>
                    &#9679; Failure to comply with barangay ordinances will result in revocation of this clearance.
                </div>
                <div class="permit-sig">
                    <div style="height:52px"></div>
                    <div class="permit-sig-line"></div>
                    <div class="permit-sig-name">{{ $permitOfficialName }}</div>
                    <div class="permit-sig-title">Punong Barangay</div>
                </div>
            </div>

            {{-- Bottom strip --}}
            <div style="margin-top:14px;padding-top:10px;border-top:1px dashed #999;font-size:8pt;color:#555;display:flex;gap:32px">
                <span>O.R. No.: _______________</span>
                <span>Amount Paid: _______________</span>
                <span>Date: {{ now()->format('m/d/Y') }}</span>
                <span style="margin-left:auto">Prepared by: _______________</span>
            </div>

        </div>
    </div>
</div>
</div>

{{-- Activity Log (screen only) --}}
<div class="no-print">
</div>

@endsection