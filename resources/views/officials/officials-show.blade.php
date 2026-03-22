@extends('layouts.app')

@section('title', $official->full_name)

@section('content')

<div class="page-header no-print">
    <div>
        <h1 class="page-title">Official Profile</h1>
        <p class="page-subtitle">{{ $official->full_name }} — {{ $official->position }}</p>
    </div>
    <div class="page-actions">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-id-card"></i> Print ID Card
        </button>
        <a href="{{ route('officials.edit', $official) }}" class="btn btn-secondary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

{{-- Screen View --}}
<div class="no-print" style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:28px 20px;text-align:center">
                <div style="width:90px;height:90px;border-radius:50%;overflow:hidden;margin:0 auto 16px;border:3px solid rgba(200,134,26,0.45)">
                    @if($official->photo_path)
                        <img src="{{ asset('storage/'.$official->photo_path) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--gold),var(--gold-light));font-size:32px;font-weight:800;color:var(--navy)">
                            {{ strtoupper(substr($official->full_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="font-size:17px;font-weight:700;color:#fff;margin-bottom:6px">{{ $official->full_name }}</div>
                <div style="font-size:12px;color:rgba(229,160,32,0.85);font-weight:500;margin-bottom:10px">{{ $official->position }}</div>
                <span class="badge {{ $official->is_active ? 'badge-green' : 'badge-gray' }}">{{ $official->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
            @if($official->term_start && $official->term_end)
            @php
                $tStart = \Carbon\Carbon::parse($official->term_start);
                $tEnd   = \Carbon\Carbon::parse($official->term_end);
                $tPct   = round((min($tStart->diffInDays(now()), $tStart->diffInDays($tEnd)) / max(1,$tStart->diffInDays($tEnd))) * 100);
            @endphp
            <div style="padding:14px 16px;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--text-subtle);margin-bottom:6px">
                    <span>{{ $tStart->format('Y') }}</span><span>{{ $tEnd->format('Y') }}</span>
                </div>
                <div class="progress-bar-wrap"><div class="progress-bar" style="width:{{ $tPct }}%;background:var(--gold)"></div></div>
                <div style="font-size:10px;color:var(--text-subtle);margin-top:5px;text-align:center">{{ $tPct }}% of term served</div>
            </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header"><span class="card-title"><i class="fas fa-bolt"></i> Actions</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <button onclick="window.print()" class="btn btn-gold" style="justify-content:flex-start">
                    <i class="fas fa-id-card"></i> Print ID Card
                </button>
                <a href="{{ route('officials.edit', $official) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Profile
                </a>
                <form method="POST" action="{{ route('officials.destroy', $official) }}" onsubmit="return confirm('Delete this official?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start"><i class="fas fa-trash"></i> Delete</button>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-info-circle"></i> Official Details</span></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @php $details = [
                    ['label'=>'Full Name',  'value'=>$official->full_name],
                    ['label'=>'Position',   'value'=>$official->position],
                    ['label'=>'Committee',  'value'=>$official->committee ?? '—'],
                    ['label'=>'Status',     'value'=>$official->is_active ? 'Active' : 'Inactive'],
                    ['label'=>'Contact No.','value'=>$official->contact_number ?? '—'],
                    ['label'=>'Date Added', 'value'=>$official->created_at->format('F d, Y')],
                    ['label'=>'Term Start', 'value'=>$official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('F d, Y') : '—'],
                    ['label'=>'Term End',   'value'=>$official->term_end   ? \Carbon\Carbon::parse($official->term_end)->format('F d, Y')   : '—'],
                ]; @endphp
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

{{-- ID CARD PRINT --}}
<div class="print-only" id="id-card">
@php
    $punong    = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $termShort = ($official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—') . ' – ' . ($official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y') : '—');
    $idNumber  = 'BNE-' . str_pad($official->id, 4, '0', STR_PAD_LEFT) . '-' . date('Y');
    $validUntil = $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('M d, Y') : '—';
@endphp
<style>
@media print {
    @page { size: 2.5in 3.5in; margin: 0; }
    .no-print  { display: none !important; }
    .print-only { display: block !important; }
    .sidebar, .topbar, .watermark, .page-header { display: none !important; }
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }
}
.print-only { display: none; }
* { box-sizing: border-box; margin: 0; padding: 0; }

.id-card-wrap {
    width: 2.5in;
    height: 3.5in;
    font-family: Arial, sans-serif;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid #ccc;
}

/* WATERMARK */
.id-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 2.2in;
    height: 2.2in;
    object-fit: contain;
    opacity: 0.09;
    pointer-events: none;
    z-index: 0;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
}
.id-card-wrap {
    position: relative;
    background: #fff;
}
.id-card-wrap > *:not(.id-watermark) { position: relative; z-index: 1; }

/* TOP HEADER — navy */
.id-top {
    background: #0D2144;
    padding: 6px 8px 5px;
    display: flex;
    align-items: center;
    gap: 6px;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
.id-top img { width: 32px; height: 32px; object-fit: contain; }
.id-top-text { flex: 1; text-align: center; }
.id-top-text .rep  { font-size: 5pt; color: rgba(255,255,255,0.65); font-style: italic; }
.id-top-text .brgy { font-size: 8pt; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.03em; }
.id-top-text .city { font-size: 5pt; color: #E5A020; }

/* GOLD STRIPE */
.id-gold-stripe {
    background: #C8861A;
    text-align: center;
    padding: 2.5px 0;
    font-size: 6pt;
    font-weight: 900;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* ID NUMBER row */
.id-number-row {
    background: #0D2144;
    text-align: center;
    padding: 2px 0;
    font-family: 'Courier New', monospace;
    font-size: 7pt;
    font-weight: 700;
    color: #E5A020;
    letter-spacing: 0.1em;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}

/* PHOTO SECTION */
.id-photo-section {
    background: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px 0 5px;
    flex: 0 0 auto;
}
.id-photo-frame {
    width: 1in;
    height: 1.1in;
    border: 3px solid #0D2144;
    overflow: hidden;
    background: #e8edf5;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 900;
    color: #C8861A;
}
.id-photo-frame img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* NAME SECTION */
.id-name-section {
    background: #fff;
    text-align: center;
    padding: 5px 8px 2px;
    flex: 0 0 auto;
}
.id-hon    { font-size: 7pt; font-weight: 700; color: #0D2144; text-transform: uppercase; letter-spacing: 0.05em; }
.id-name   { font-size: 13pt; font-weight: 900; color: #0D2144; text-transform: uppercase; line-height: 1.1; }

/* SIGNATURE */
.id-sig-section {
    background: #fff;
    padding: 4px 16px 2px;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.id-sig-line  { border-top: 1.5px solid #333; width: 80%; margin: 0 auto 2px; }
.id-sig-label { text-align: center; font-size: 5.5pt; color: #555; text-transform: uppercase; letter-spacing: 0.08em; }

/* DETAILS ROW */
.id-details {
    background: #fff;
    padding: 3px 10px 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid #e5e7eb;
}
.id-det-item { text-align: center; }
.id-det-lbl  { font-size: 4.5pt; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; }
.id-det-val  { font-size: 6pt; font-weight: 700; color: #0D2144; }

/* POSITION BAR — gold bottom */
.id-position-bar {
    background: #C8861A;
    text-align: center;
    padding: 5px 8px;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
    margin-top: auto;
}
.id-position-bar .pos-text {
    font-size: 9pt;
    font-weight: 900;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.id-position-bar .pos-sub {
    font-size: 5pt;
    color: rgba(255,255,255,0.75);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-top: 1px;
}

/* VALIDITY FOOTER */
.id-footer {
    background: #0D2144;
    padding: 2px 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
.id-footer .f-lbl  { font-size: 4pt; color: rgba(255,255,255,0.45); text-transform: uppercase; display: block; }
.id-footer .f-val  { font-size: 5.5pt; color: #E5A020; font-weight: 700; }
.id-footer .f-right { text-align: right; }
</style>

<div class="id-card-wrap">

    {{-- Watermark --}}
    <img src="{{ asset('images/bne-logo.png') }}" class="id-watermark" alt="">

    {{-- Top Header --}}
    <div class="id-top">
        <img src="{{ asset('images/qc-seal.png') }}" alt="QC">
        <div class="id-top-text">
            <div class="rep"><em>Republic of the Philippines</em></div>
            <div class="brgy">Barangay New Era</div>
            <div class="city">New Era, Quezon City, Metro Manila</div>
        </div>
        <img src="{{ asset('images/bne-logo.png') }}" alt="BNE">
    </div>

    {{-- Gold type stripe --}}
    <div class="id-gold-stripe">Barangay Official ID</div>

    {{-- ID Number --}}
    <div class="id-number-row">{{ $idNumber }}</div>

    {{-- Photo + Barcode --}}
    <div class="id-photo-section" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:8px 10px 5px;">
        <div class="id-photo-frame">
            @if($official->photo_path)
                <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Photo">
            @else
                {{ strtoupper(substr($official->full_name, 0, 1)) }}
            @endif
        </div>
        {{-- Vertical barcode --}}
        @php
            $svgBars = '';
            $y = 0;
            foreach (str_split($idNumber) as $char) {
                $ascii = ord($char);
                $pattern = str_pad(decbin($ascii), 8, '0', STR_PAD_LEFT);
                foreach (str_split($pattern) as $bit) {
                    $h = $bit === '1' ? 2 : 1;
                    $svgBars .= '<rect x="0" y="' . $y . '" width="14" height="' . $h . '" fill="' . ($bit === '1' ? '#111' : '#fff') . '"/>';
                    $y += $h;
                }
                $y += 1;
            }
            $totalHeight = $y;
        @endphp
        <div style="display:flex;flex-direction:column;align-items:center;gap:2px">
            <svg width="14" height="{{ $totalHeight }}" xmlns="http://www.w3.org/2000/svg" style="max-height:1.1in">
                {!! $svgBars !!}
            </svg>
            <div style="font-family:'Courier New',monospace;font-size:3.5pt;color:#555;writing-mode:vertical-rl;transform:rotate(180deg);letter-spacing:0.04em">{{ $idNumber }}</div>
        </div>
    </div>

    {{-- Name --}}
    <div class="id-name-section">
        <div class="id-hon">Hon.</div>
        <div class="id-name">{{ $official->full_name }}</div>
    </div>

    {{-- Signature --}}
    <div class="id-sig-section">
        <div class="id-sig-line"></div>
        <div class="id-sig-label">Cardholder Signature</div>
    </div>

    {{-- Details --}}
    <div class="id-details">
        <div class="id-det-item">
            <div class="id-det-lbl">Term</div>
            <div class="id-det-val">{{ $termShort }}</div>
        </div>
        @if($official->committee)
        <div class="id-det-item">
            <div class="id-det-lbl">Committee</div>
            <div class="id-det-val" style="font-size:5.5pt">{{ $official->committee }}</div>
        </div>
        @endif
        @if($official->contact_number)
        <div class="id-det-item">
            <div class="id-det-lbl">Contact</div>
            <div class="id-det-val">{{ $official->contact_number }}</div>
        </div>
        @endif
    </div>

    {{-- Position Bar --}}
    <div class="id-position-bar">
        <div class="pos-text">{{ $official->position }}</div>
        <div class="pos-sub">Barangay New Era · Quezon City</div>
    </div>

    {{-- Footer --}}
    <div class="id-footer">
        <div>
            <span class="f-lbl">Valid Until</span>
            <span class="f-val">{{ $validUntil }}</span>
        </div>
        <div class="f-right">
            <span class="f-lbl">Status</span>
            <span class="f-val" style="color:{{ $official->is_active ? '#6EE7A0' : 'rgba(255,255,255,0.4)' }}">
                {{ $official->is_active ? '● ACTIVE' : '○ INACTIVE' }}
            </span>
        </div>
    </div>

</div>
</div>

@endsection