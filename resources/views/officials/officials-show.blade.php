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
                <div style="font-size:13px;color:rgba(229,160,32,0.85);font-weight:500;margin-bottom:10px">{{ $official->position }}</div>
                <span class="badge {{ $official->is_active ? 'badge-green' : 'badge-gray' }}">{{ $official->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
            @if($official->term_start && $official->term_end)
            @php
                $tStart = \Carbon\Carbon::parse($official->term_start);
                $tEnd   = \Carbon\Carbon::parse($official->term_end);
                $tPct   = round((min($tStart->diffInDays(now()), $tStart->diffInDays($tEnd)) / max(1,$tStart->diffInDays($tEnd))) * 100);
            @endphp
            <div style="padding:14px 16px;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:space-between;font-size:13px;color:var(--text-subtle);margin-bottom:6px">
                    <span>{{ $tStart->format('Y') }}</span><span>{{ $tEnd->format('Y') }}</span>
                </div>
                <div class="progress-bar-wrap"><div class="progress-bar" style="width:{{ $tPct }}%;background:var(--gold)"></div></div>
                <div style="font-size:13px;color:var(--text-subtle);margin-top:5px;text-align:center">{{ $tPct }}% of term served</div>
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
                <form method="POST" action="{{ route('officials.destroy', $official) }}"
                      data-confirm="Delete {{ $official->full_name }}? This will remove their profile and ID card."
                      data-confirm-title="Delete Official"
                      data-confirm-ok="Delete">
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
                    ['label'=>'Date Added', 'value'=>$official->created_at->format('m/d/Y')],
                    ['label'=>'Term Start', 'value'=>$official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('m/d/Y') : '—'],
                    ['label'=>'Term End',   'value'=>$official->term_end   ? \Carbon\Carbon::parse($official->term_end)->format('m/d/Y')   : '—'],
                ]; @endphp
                @foreach($details as $d)
                <div style="padding:10px 0;border-bottom:1px solid var(--border);{{ $loop->even ? 'padding-left:24px' : '' }}">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">{{ $d['label'] }}</div>
                    <div style="font-size:15px;color:var(--text);font-weight:500">{{ $d['value'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ID CARD PRINT --}}
<div class="print-only" id="id-card">
@php
    $punong     = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $termShort  = ($official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—') . ' – ' . ($official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y') : '—');
    $idNumber   = 'BNE-' . str_pad($official->id, 4, '0', STR_PAD_LEFT) . '-' . date('Y');
    $validUntil = $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('m/d/Y') : '—';

    // Code 39 barcode pattern for each character
    $code39 = [
        '0'=>'101001101101','1'=>'110100101011','2'=>'101100101011',
        '3'=>'110110010101','4'=>'101001101011','5'=>'110100110101',
        '6'=>'101100110101','7'=>'101001011011','8'=>'110100101101',
        '9'=>'101100101101','-'=>'101011010011','*'=>'100101101101',
        'B'=>'110010101011','E'=>'110010110101','N'=>'110101001011',
        'A'=>'110101001011','C'=>'101011001011','D'=>'110101100101',
    ];
    $default = '101010110101';

    // Build vertical barcode
    $svgBars = '';
    $y = 0;
    $barW = 22;

    // Start quiet zone
    $y += 6;

    // Start char *
    $pattern = $code39['*'] ?? $default;
    foreach (str_split($pattern) as $i => $bit) {
        $h = $bit === '1' ? ($i % 3 === 0 ? 3.5 : 2) : 1;
        $svgBars .= '<rect x="0" y="' . $y . '" width="' . $barW . '" height="' . $h . '" fill="' . ($bit === '1' ? '#0D2144' : '#C8D8E8') . '"/>';
        $y += $h + 0.5;
    }
    $y += 3;

    foreach (str_split(strtoupper($idNumber)) as $char) {
        $pattern = $code39[$char] ?? $default;
        foreach (str_split($pattern) as $i => $bit) {
            $h = $bit === '1' ? ($i % 3 === 0 ? 3.5 : 2) : 1;
            $svgBars .= '<rect x="0" y="' . $y . '" width="' . $barW . '" height="' . $h . '" fill="' . ($bit === '1' ? '#0D2144' : '#C8D8E8') . '"/>';
            $y += $h + 0.5;
        }
        $y += 3;
    }

    // Stop char *
    $pattern = $code39['*'] ?? $default;
    foreach (str_split($pattern) as $i => $bit) {
        $h = $bit === '1' ? ($i % 3 === 0 ? 3.5 : 2) : 1;
        $svgBars .= '<rect x="0" y="' . $y . '" width="' . $barW . '" height="' . $h . '" fill="' . ($bit === '1' ? '#0D2144' : '#C8D8E8') . '"/>';
        $y += $h + 0.5;
    }

    $y += 6;
    $bcHeight = $y;
@endphp
<style>
@media print {
    @page { size: 2.5in 3.5in; margin: 0; }
    .no-print   { display: none !important; }
    .print-only { display: block !important; }
    .sidebar, .topbar, .watermark, .page-header { display: none !important; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
}
.print-only { display: none; }
* { box-sizing: border-box; margin: 0; padding: 0; }

.idc {
    width: 2.5in;
    height: 3.5in;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    font-family: Arial, sans-serif;
    border: 1px solid #bbb;
    background: #fff;
}

/* NAVY HEADER */
.idc-header {
    background: #0D2144;
    padding: 5px 8px 4px;
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
    -webkit-print-color-adjust: exact;
}
.idc-header img { width: 28px; height: 28px; object-fit: contain; }
.idc-header-txt { flex: 1; text-align: center; }
.idc-header-txt .r { font-size: 4.5pt; color: rgba(255,255,255,0.55); font-style: italic; }
.idc-header-txt .b { font-size: 7.5pt; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.03em; }
.idc-header-txt .c { font-size: 4.5pt; color: #E5A020; }

/* GOLD STRIPE */
.idc-gold {
    background: #C8861A;
    text-align: center;
    padding: 2px 0;
    font-size: 5.5pt;
    font-weight: 900;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    flex-shrink: 0;
    -webkit-print-color-adjust: exact;
}

/* ID NUMBER */
.idc-idnum {
    background: #0D2144;
    text-align: center;
    padding: 1.5px 0;
    font-family: 'Courier New', monospace;
    font-size: 6.5pt;
    font-weight: 700;
    color: #E5A020;
    letter-spacing: 0.1em;
    flex-shrink: 0;
    -webkit-print-color-adjust: exact;
}

/* MIDDLE SECTION — photo + barcode + watermark */
.idc-middle {
    flex: 0 0 1.18in;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 6px 10px;
    background: #fff;
    overflow: hidden;
}

/* Big watermark behind everything */
.idc-wm {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 1.3in; height: 1.3in;
    opacity: 0.1;
    object-fit: contain;
    pointer-events: none;
    z-index: 0;
    -webkit-print-color-adjust: exact !important;
}

.idc-photo {
    width: 0.9in;
    height: 1.05in;
    border: 2.5px solid #0D2144;
    border-radius: 3px;
    overflow: hidden;
    background: #dce4f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 900;
    color: #C8861A;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.idc-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }

.idc-barcode-unused { display: none; }

/* NAME SECTION */
.idc-name {
    flex-shrink: 0;
    text-align: center;
    padding: 3px 8px 2px;
    background: #fff;
    border-top: 1px solid #e5e7eb;
}
.idc-hon  { font-size: 6.5pt; font-weight: 700; color: #0D2144; text-transform: uppercase; }
.idc-nm   { font-size: 10pt; font-weight: 900; color: #0D2144; text-transform: uppercase; line-height: 1.1; }

/* SIGNATURE */
.idc-sig {
    flex-shrink: 0;
    padding: 2px 16px 2px;
    background: #fff;
}
.idc-sig-line  { border-top: 1px solid #333; width: 80%; margin: 0 auto 2px; }
.idc-sig-label { text-align: center; font-size: 5pt; color: #777; text-transform: uppercase; letter-spacing: 0.08em; }

/* DETAILS ROW */
.idc-details {
    flex-shrink: 0;
    padding: 3px 8px;
    background: #fff;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-around;
}
.idc-det { text-align: center; }
.idc-det-lbl { font-size: 4pt; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.06em; display: block; }
.idc-det-val { font-size: 5.5pt; font-weight: 700; color: #0D2144; display: block; }

/* POSITION BAR */
.idc-pos {
    flex-shrink: 0;
    background: #C8861A;
    text-align: center;
    padding: 3px 8px;
    margin-top: auto;
    -webkit-print-color-adjust: exact;
}
.idc-pos-txt { font-size: 8.5pt; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.05em; }
.idc-pos-sub { font-size: 4.5pt; color: rgba(255,255,255,0.75); text-transform: uppercase; letter-spacing: 0.08em; }

/* FOOTER */
.idc-footer {
    flex-shrink: 0;
    background: #0D2144;
    padding: 4px 8px 5px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    -webkit-print-color-adjust: exact;
}
.idc-foot-lbl  { font-size: 4pt; color: rgba(255,255,255,0.4); text-transform: uppercase; display: block; margin-bottom:1px; }
.idc-foot-val  { font-size: 5.5pt; color: #E5A020; font-weight: 700; display: block; }
</style>

<div class="idc">

    {{-- Header --}}
    <div class="idc-header">
        <img src="{{ asset('images/qc-seal.png') }}" alt="QC">
        <div class="idc-header-txt">
            <div class="r"><em>Republic of the Philippines</em></div>
            <div class="b">Barangay New Era</div>
            <div class="c">New Era, Quezon City, Metro Manila</div>
        </div>
        <img src="{{ asset('images/bne-logo.png') }}" alt="BNE">
    </div>

    {{-- Gold stripe --}}
    <div class="idc-gold">✦ &nbsp; Barangay Official ID &nbsp; ✦</div>

    {{-- ID Number --}}
    <div class="idc-idnum">{{ $idNumber }}</div>

    {{-- Photo + Watermark + Barcode --}}
    <div class="idc-middle">
        <img src="{{ asset('images/bne-logo.png') }}" class="idc-wm" alt=""
             style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:1.8in;height:1.8in;opacity:0.1;object-fit:contain;pointer-events:none;z-index:0;-webkit-print-color-adjust:exact!important;print-color-adjust:exact!important;">

        <div class="idc-photo">
            @if($official->photo_path)
                <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Photo">
            @else
                {{ strtoupper(substr($official->full_name, 0, 1)) }}
            @endif
        </div>

        <div style="display:flex;flex-direction:row;align-items:center;position:relative;z-index:1;">
            <svg width="22" height="{{ $bcHeight }}" xmlns="http://www.w3.org/2000/svg"
                 style="display:block;max-height:1.05in;">
                <rect x="0" y="0" width="22" height="{{ $bcHeight }}" fill="#fff"/>
                {!! $svgBars !!}
            </svg>
            <div style="font-family:'Courier New',monospace;font-size:3.8pt;color:#0D2144;writing-mode:vertical-rl;transform:rotate(180deg);letter-spacing:0.06em;font-weight:800;margin-left:3px;padding:2px 0;">{{ $idNumber }}</div>
        </div>
    </div>

    {{-- Name --}}
    <div class="idc-name">
        <div class="idc-hon">Hon.</div>
        <div class="idc-nm">{{ $official->full_name }}</div>
    </div>

    {{-- Signature --}}
    <div class="idc-sig">
        <div class="idc-sig-line"></div>
        <div class="idc-sig-label">Cardholder Signature</div>
    </div>

    {{-- Details --}}
    <div class="idc-details">
        <div class="idc-det">
            <span class="idc-det-lbl">Term</span>
            <span class="idc-det-val">{{ $termShort }}</span>
        </div>
        @if($official->committee)
        <div class="idc-det">
            <span class="idc-det-lbl">Committee</span>
            <span class="idc-det-val" style="font-size:5pt">{{ $official->committee }}</span>
        </div>
        @endif
        @if($official->contact_number)
        <div class="idc-det">
            <span class="idc-det-lbl">Contact</span>
            <span class="idc-det-val">{{ $official->contact_number }}</span>
        </div>
        @endif
    </div>

    {{-- Position Bar --}}
    <div class="idc-pos">
        <div class="idc-pos-txt">{{ $official->position }}</div>
        <div class="idc-pos-sub">Barangay New Era · Quezon City</div>
    </div>

    {{-- Footer --}}
    <div class="idc-footer">
        <div>
            <span class="idc-foot-lbl">Valid Until</span>
            <span class="idc-foot-val">{{ $validUntil }}</span>
        </div>
        <div style="text-align:right">
            <span class="idc-foot-lbl">Status</span>
            <span class="idc-foot-val" style="color:{{ $official->is_active ? '#6EE7A0' : 'rgba(255,255,255,0.4)' }}">
                {{ $official->is_active ? '● ACTIVE' : '○ INACTIVE' }}
            </span>
        </div>
    </div>

</div>
</div>

@endsection