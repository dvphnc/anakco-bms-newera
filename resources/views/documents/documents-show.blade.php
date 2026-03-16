@extends('layouts.app')

@section('title', $document->doc_number)

@section('content')

<div class="page-header no-print">
    <div>
        <h1 class="page-title">Document Details</h1>
        <p class="page-subtitle">{{ $document->doc_number }} — {{ $document->document_type }}</p>
    </div>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Print Certificate
        </button>
        <a href="{{ route('documents.edit', $document) }}" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
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
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-file-alt" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div class="td-mono" style="color:rgba(255,255,255,0.5);font-size:11px;margin-bottom:4px">{{ $document->doc_number }}</div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.3;margin-bottom:10px">{{ $document->document_type }}</div>
                @php
                    $cls = match($document->status) {
                        'Released'   => 'badge-green',
                        'Processing' => 'badge-blue',
                        'Pending'    => 'badge-yellow',
                        'Cancelled'  => 'badge-gray',
                        default      => 'badge-gray'
                    };
                @endphp
                <span class="badge {{ $cls }}">{{ $document->status }}</span>
            </div>
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Resident</div>
                <a href="{{ route('residents.show', $document->resident) }}"
                   style="display:flex;align-items:center;gap:10px;color:var(--navy)">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;color:#fff;font-size:13px">
                        {{ strtoupper(substr($document->resident->first_name ?? 'R', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13px">{{ $document->resident->full_name ?? '—' }}</div>
                        <div class="td-muted">{{ $document->resident->purok->name ?? '' }}</div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('documents.edit', $document) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Document
                </a>
                @if($document->status === 'Pending' || $document->status === 'Processing')
                <form method="POST" action="{{ route('documents.update', $document) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="Released">
                    <input type="hidden" name="resident_id" value="{{ $document->resident_id }}">
                    <input type="hidden" name="document_type" value="{{ $document->document_type }}">
                    <input type="hidden" name="purpose" value="{{ $document->purpose }}">
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-circle-check"></i> Mark as Released
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('documents.destroy', $document) }}"
                      onsubmit="return confirm('Delete this document?')">
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
            <span class="card-title"><i class="fas fa-circle-info"></i> Document Information</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @php
                    $details = [
                        ['label'=>'Document No.',  'value'=>$document->doc_number],
                        ['label'=>'Document Type', 'value'=>$document->document_type],
                        ['label'=>'Status',        'value'=>$document->status],
                        ['label'=>'Purpose',       'value'=>$document->purpose ?? '—'],
                        ['label'=>'Fee',           'value'=>($document->fee_paid ?? 0) > 0 ? '₱'.number_format($document->fee_paid,2) : 'Free'],
                        ['label'=>'Issued By',     'value'=>$document->issuedBy->name ?? '—'],
                        ['label'=>'Date Requested','value'=>$document->created_at->format('F d, Y')],
                        ['label'=>'Date Released', 'value'=>$document->released_at?->format('F d, Y') ?? '—'],
                    ];
                @endphp
                @foreach($details as $d)
                <div style="padding:10px 0;border-bottom:1px solid var(--border);{{ $loop->even ? 'padding-left:24px' : '' }}">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">{{ $d['label'] }}</div>
                    <div style="font-size:13.5px;color:var(--text);font-weight:500">{{ $d['value'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- =============================================
     PRINTABLE CERTIFICATE
============================================= --}}
<div class="print-only" id="certificate">
<style>
@media print {
    @page {
        size: letter;
        margin: 0.5in 0.7in;
    }
    .no-print { display: none !important; }
    .print-only { display: block !important; }
    header, nav, footer, .sidebar, .topbar, .page-header { display: none !important; }
}
.print-only { display: none; }

.cert-page {
    font-family: 'Times New Roman', Times, serif;
    color: #000;
    width: 100%;
    max-width: 7in;
    margin: 0 auto;
    position: relative;
}

/* Decorative border */
.cert-border {
    border: 3px double #1a3a6b;
    padding: 32px 40px;
    position: relative;
}
.cert-border::before {
    content: '';
    position: absolute;
    top: 5px; left: 5px; right: 5px; bottom: 5px;
    border: 1px solid #c8861a;
    pointer-events: none;
}

/* Header */
.cert-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 12px;
    border-bottom: 2px solid #1a3a6b;
}
.cert-logo {
    width: 75px;
    height: 75px;
    object-fit: contain;
    flex-shrink: 0;
}
.cert-logo-placeholder {
    width: 75px;
    height: 75px;
    border: 2px solid #1a3a6b;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 9px;
    text-align: center;
    color: #1a3a6b;
    flex-shrink: 0;
}
.cert-titles {
    text-align: center;
    flex: 1;
    padding: 0 16px;
}
.cert-republic {
    font-size: 10pt;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}
.cert-province {
    font-size: 9pt;
    margin-bottom: 2px;
}
.cert-barangay {
    font-size: 17pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #0d2144;
    margin-bottom: 2px;
}
.cert-address {
    font-size: 8.5pt;
    color: #444;
}
.cert-docnum {
    font-size: 8pt;
    text-align: right;
    color: #555;
    margin-top: 6px;
}

/* Document type title */
.cert-type-title {
    text-align: center;
    margin: 20px 0 6px;
    font-size: 17pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.12em;
    color: #0d2144;
    text-decoration: underline;
}

/* Body */
.cert-body {
    font-size: 11pt;
    line-height: 1.9;
    text-align: justify;
    margin: 16px 0 20px;
}
.cert-body .resident-name {
    font-weight: bold;
    text-transform: uppercase;
    text-decoration: underline;
    font-size: 12pt;
}
.cert-body .highlight {
    font-weight: bold;
}

/* Footer */
.cert-footer {
    margin-top: 28px;
}
.cert-issued-at {
    font-size: 10pt;
    margin-bottom: 24px;
    font-style: italic;
}
.cert-sig-area {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 8px;
}
.cert-sig-left {
    font-size: 9pt;
    color: #444;
}
.cert-sig-right {
    text-align: center;
    min-width: 220px;
}
.cert-sig-line {
    border-top: 1px solid #000;
    margin-bottom: 4px;
    width: 100%;
}
.cert-punong {
    font-size: 11pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.cert-punong-title {
    font-size: 9pt;
    color: #333;
}
.cert-or {
    margin-top: 24px;
    padding-top: 10px;
    border-top: 1px dashed #999;
    font-size: 8.5pt;
    color: #555;
    display: flex;
    gap: 32px;
}
.cert-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    opacity: 0.04;
    width: 280px;
    height: 280px;
    object-fit: contain;
    pointer-events: none;
    z-index: 0;
}
.cert-content-wrap {
    position: relative;
    z-index: 1;
}
</style>

@php
    $resident   = $document->resident;
    $purok      = $resident?->purok?->name ?? 'Barangay New Era';
    $address    = $resident?->address ?? 'Barangay New Era, Quezon City';
    $fullName   = $resident?->full_name ?? '—';
    $issuedDate = $document->released_at ?? $document->created_at;
    $officialName = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';

    // Build certificate body based on document type
    $certBody = match($document->document_type) {
        'Barangay Clearance' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, of legal age, ".
            ($resident?->civil_status ? strtolower($resident->civil_status) . ', ' : '').
            "Filipino citizen, and a <span class='highlight'>bonafide resident</span> of <span class='highlight'>$address</span>, ".
            "has been known to be of good moral character and has no derogatory record on file in this barangay as of this date.",

        'Certificate of Indigency' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, of legal age, ".
            "a resident of <span class='highlight'>$address</span>, ".
            "belongs to an <span class='highlight'>indigent family</span> in this barangay. ".
            "This certification is issued upon the request of the aforementioned person ".
            "for the purpose of <span class='highlight'>".e($document->purpose ?? 'whatever legal purpose it may serve')."</span>.",

        'Certificate of Residency' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, of legal age, ".
            "is a <span class='highlight'>bonafide resident</span> of <span class='highlight'>$address</span>, ".
            "Barangay New Era, Quezon City. ".
            "He/She has been residing in this barangay for a considerable period of time and is known to the undersigned.",

        'Good Moral Character' =>
            "This is to certify that <span class='resident-name'>$fullName</span>, of legal age, ".
            "a resident of <span class='highlight'>$address</span>, ".
            "is personally known to us and is a person of <span class='highlight'>good moral character, good standing, and reputable member</span> ".
            "of this community. No derogatory record has been filed against him/her in this office.",

        'Business Clearance' =>
            "This is to certify that the business establishment owned/operated by ".
            "<span class='resident-name'>$fullName</span>, located at <span class='highlight'>$address</span>, ".
            "has been granted <span class='highlight'>barangay clearance</span> to operate within the jurisdiction ".
            "of Barangay New Era, Quezon City.",

        'Certificate of Live Birth' =>
            "This is to certify that based on records available in this barangay, ".
            "<span class='resident-name'>$fullName</span> ".
            "is a resident of <span class='highlight'>$address</span>, ".
            "Barangay New Era, Quezon City.",

        default =>
            "This is to certify that <span class='resident-name'>$fullName</span>, ".
            "a resident of <span class='highlight'>$address</span>, ".
            "has requested this certification for the purpose of ".
            "<span class='highlight'>".e($document->purpose ?? 'whatever legal purpose it may serve')."</span>.",
    };
@endphp

<div class="cert-page">
    <div class="cert-border">

        {{-- Watermark --}}
        <img src="{{ asset('images/bne-logo.png') }}" class="cert-watermark" alt="">

        <div class="cert-content-wrap">

            {{-- Header --}}
            <div class="cert-header">
                <img src="{{ asset('images/qc-seal.png') }}" class="cert-logo" alt="Quezon City Seal">
                <div class="cert-titles">
                    <div class="cert-republic"><em>Republic of the Philippines</em></div>
                    <div class="cert-province">City of Quezon, National Capital Region</div>
                    <div class="cert-barangay">Barangay New Era</div>
                    <div class="cert-office" style="font-size:10pt;font-style:italic">Office of the Punong Barangay</div>
                    <div class="cert-address">New Era, Quezon City, Metro Manila</div>
                </div>
                <img src="{{ asset('images/bne-logo.png') }}" class="cert-logo" alt="Barangay New Era Seal">
            </div>

            <div class="cert-docnum">Doc No.: {{ $document->doc_number }}</div>

            {{-- Document Type Title --}}
            <div class="cert-type-title">{{ $document->document_type }}</div>

            <div style="text-align:center;font-size:9.5pt;letter-spacing:0.15em;color:#555;margin-bottom:8px">
                ✦ &nbsp; TO WHOM IT MAY CONCERN &nbsp; ✦
            </div>

            {{-- Certificate Body --}}
            <div class="cert-body">
                <p style="text-indent:48px">{!! $certBody !!}</p>

                <p style="text-indent:48px;margin-top:12px">
                    This certification is issued upon the request of the above-named person
                    for the purpose of <span class="highlight">{{ $document->purpose ?? 'whatever legal purpose it may serve' }}</span>
                    and is valid only for the purpose stated herein.
                </p>
            </div>

            {{-- Footer --}}
            <div class="cert-footer">
                <div class="cert-issued-at">
                    Issued this <span class="highlight">{{ $issuedDate->format('jS') }} day of {{ $issuedDate->format('F, Y') }}</span>
                    at Barangay New Era, Quezon City.
                </div>

                <div class="cert-sig-area">
                    {{-- Left: photo + thumbmark boxes --}}
                    <div style="display:flex;gap:10px;align-items:flex-end">
                        <div style="display:flex;flex-direction:column;align-items:center">
                            @if($resident?->photo_path)
                                <img src="{{ asset('storage/'.$resident->photo_path) }}"
                                     style="width:75px;height:90px;object-fit:cover;border:1px solid #000;display:block">
                            @else
                                <div style="width:75px;height:90px;border:1px solid #000;display:flex;align-items:center;justify-content:center;font-size:7pt;text-align:center;color:#666;line-height:1.3">
                                    APPLICANT<br>PHOTO
                                </div>
                            @endif
                            <div style="font-size:6.5pt;margin-top:3px;text-transform:uppercase;letter-spacing:0.04em;text-align:center;width:75px">Applicant Photo</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:center">
                            <div style="width:75px;height:90px;border:1px solid #000;display:flex;align-items:center;justify-content:center;font-size:7pt;text-align:center;color:#666;line-height:1.3">
                                APPLICANT<br>THUMBMARK
                            </div>
                            <div style="font-size:6.5pt;margin-top:3px;text-transform:uppercase;letter-spacing:0.04em;text-align:center;width:75px">Applicant Thumbmark</div>
                        </div>
                        <div style="display:flex;flex-direction:column;justify-content:flex-end;padding-bottom:20px;margin-left:6px">
                            <div style="font-size:8.5pt;margin-bottom:28px">Applicant's Signature:</div>
                            <div style="border-top:1px solid #000;width:150px"></div>
                        </div>
                    </div>
                    {{-- Right: punong signature --}}
                    <div style="text-align:center;min-width:200px">
                        <div style="height:64px"></div>
                        <div style="border-top:1px solid #000;margin-bottom:3px"></div>
                        <div class="cert-punong">{{ $officialName }}</div>
                        <div class="cert-punong-title">Punong Barangay</div>
                    </div>
                </div>
                <div style="margin-top:16px;font-size:8pt;color:#555;border-top:1px solid #ccc;padding-top:8px">
                    <div style="margin-bottom:3px">Community Tax Certificate No.: _______________ &nbsp;&nbsp; Issued at: _______________ &nbsp;&nbsp; Date: _______________</div>
                    <div style="font-style:italic;color:#777;font-size:7.5pt">
                        &#9679; This certification document is not valid without the official barangay dry seal and Punong Barangay signature/stamp.<br>
                        &#9679; Officials and applicants who will submit false certification or documents shall be held liable for administrative/criminal liabilities.
                    </div>
                </div>

                <div class="cert-or">
                    <span>O.R. No.: _______________</span>
                    <span>Amount Paid: ₱{{ number_format($document->fee_paid ?? 0, 2) }}</span>
                    <span>Date: {{ $issuedDate->format('m/d/Y') }}</span>
                    <span style="margin-left:auto">Prepared by: _______________</span>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

@endsection