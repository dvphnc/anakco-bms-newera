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
            @if($official->contact_number)
            <div style="padding:12px 16px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:3px">Contact</div>
                <div style="font-size:13px;color:var(--text)">{{ $official->contact_number }}</div>
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

{{-- =============================================
     PRINTABLE ID CARD — CR80 Landscape (3.375in x 2.125in)
     Front + Back on same print
============================================= --}}
<div class="print-only" id="id-card">
@php
    $punong    = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $secretary = \App\Models\Official::where('position','Barangay Secretary')->where('is_active',true)->first()?->full_name ?? 'BARANGAY SECRETARY';
    $termStart = $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('F d, Y') : '—';
    $termEnd   = $official->term_end   ? \Carbon\Carbon::parse($official->term_end)->format('F d, Y')   : '—';
    $termShort = ($official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—') . ' – ' . ($official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y') : '—');
    $idNumber  = 'BNE-' . str_pad($official->id, 4, '0', STR_PAD_LEFT) . '-' . date('Y');
@endphp
<style>
@media print {
    @page { size: 3.375in 5in; margin: 0.1in; }
    .no-print  { display: none !important; }
    .print-only { display: block !important; }
    .sidebar, .topbar, .watermark, .page-header { display: none !important; }
}
.print-only { display: none; }
* { box-sizing: border-box; }

.id-page {
    width: 3.175in;
    font-family: Arial, sans-serif;
    color: #000;
}

/* ============ FRONT CARD ============ */
.id-front {
    width: 3.175in;
    height: 2in;
    border: 1px solid #aaa;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    background: #fff;
    margin-bottom: 0.15in;
    page-break-inside: avoid;
}

/* Top stripe — navy with gold accent */
.front-top {
    background: #0D2144;
    height: 0.52in;
    display: flex;
    align-items: center;
    padding: 0 8px;
    gap: 7px;
    position: relative;
}
.front-top::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2.5px;
    background: linear-gradient(90deg, #C8861A 0%, #F0C060 50%, #C8861A 100%);
}
.front-top img { height: 34px; width: 34px; object-fit: contain; flex-shrink: 0; }
.front-top-text { flex: 1; text-align: center; }
.front-top-text .republic { font-size: 5pt; color: rgba(255,255,255,0.65); font-style: italic; }
.front-top-text .brgy     { font-size: 9pt; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.04em; line-height: 1.1; }
.front-top-text .city     { font-size: 5.5pt; color: rgba(229,160,32,0.9); }

/* Gold ribbon */
.front-ribbon {
    background: linear-gradient(135deg, #B8720A, #C8861A, #E5A020, #C8861A, #B8720A);
    text-align: center;
    padding: 2px 0;
    font-size: 6pt;
    font-weight: 800;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.14em;
}

/* Body */
.front-body {
    display: flex;
    padding: 7px 8px 6px;
    gap: 8px;
    height: calc(2in - 0.52in - 0.2in - 0.28in);
}

/* Photo */
.front-photo {
    width: 62px;
    height: 75px;
    border-radius: 3px;
    overflow: hidden;
    border: 2px solid #0D2144;
    flex-shrink: 0;
    background: linear-gradient(135deg, #C8861A, #E5A020);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 900;
    color: #fff;
    position: relative;
}
.front-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }
.front-photo-label {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(13,33,68,0.85);
    font-size: 4.5pt;
    color: #fff;
    text-align: center;
    padding: 1px 0;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* Info */
.front-info { flex: 1; min-width: 0; }
.front-name     { font-size: 8.5pt; font-weight: 900; color: #0D2144; text-transform: uppercase; line-height: 1.15; margin-bottom: 1px; }
.front-position { font-size: 7pt; font-weight: 700; color: #C8861A; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 4px; }
.front-divider  { border: none; border-top: 1px solid #e5e7eb; margin: 3px 0; }

.f-row { display: flex; gap: 4px; margin-bottom: 3px; }
.f-lbl { font-size: 5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; min-width: 40px; padding-top: 0.5px; }
.f-val { font-size: 6.5pt; color: #1f2937; font-weight: 600; flex: 1; line-height: 1.2; }

/* Bottom strip */
.front-bottom {
    background: #0D2144;
    height: 0.28in;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 8px;
    position: absolute;
    bottom: 0; left: 0; right: 0;
}
.front-bottom .idnum { font-family: 'Courier New', monospace; font-size: 6.5pt; color: rgba(255,255,255,0.85); letter-spacing: 0.08em; }
.front-bottom .valid { font-size: 5.5pt; color: rgba(229,160,32,0.9); text-align: right; }
.front-bottom .valid span { display: block; font-size: 4.5pt; color: rgba(255,255,255,0.4); text-transform: uppercase; }

/* Diagonal watermark */
.wm {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-20deg);
    width: 1.2in; height: 1.2in;
    opacity: 0.04;
    object-fit: contain;
    pointer-events: none;
}

/* ============ BACK CARD ============ */
.id-back {
    width: 3.175in;
    height: 2in;
    border: 1px solid #aaa;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    background: #fff;
    page-break-inside: avoid;
}

.back-top {
    background: #0D2144;
    height: 0.32in;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}
.back-top::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, #C8861A, #F0C060, #C8861A);
}
.back-top-text { font-size: 7pt; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 0.12em; }

.back-body { padding: 7px 10px; display: flex; gap: 10px; }

.back-sig-col { flex: 1; }
.back-note { font-size: 5.5pt; color: #555; line-height: 1.5; margin-bottom: 6px; }
.back-sig-box { border: 1px solid #ccc; border-radius: 3px; height: 0.45in; margin-bottom: 4px; padding: 3px 5px; }
.back-sig-box .sig-lbl { font-size: 4.5pt; color: #aaa; text-transform: uppercase; letter-spacing: 0.06em; }
.back-sig-box .sig-line { border-top: 1px solid #ccc; margin-top: 18px; }

.back-official-col { text-align: center; min-width: 1in; }
.back-official-photo {
    width: 46px; height: 52px;
    border: 1.5px solid #0D2144;
    border-radius: 3px;
    margin: 0 auto 3px;
    background: #f5f7fa;
    display: flex; align-items: center; justify-content: center;
    font-size: 5pt; color: #aaa; text-align: center; line-height: 1.3;
}
.back-punong-sig { border-top: 1px solid #333; width: 80%; margin: 12px auto 2px; }
.back-punong-name { font-size: 6pt; font-weight: 800; color: #0D2144; text-transform: uppercase; }
.back-punong-title { font-size: 5pt; color: #666; }

.back-footer {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: #f5f7fa;
    border-top: 1px solid #e5e7eb;
    padding: 3px 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.back-footer .contact { font-size: 5pt; color: #666; }
.back-footer .logo { width: 18px; height: 18px; object-fit: contain; opacity: 0.5; }
.back-footer .notice { font-size: 4.5pt; color: #aaa; text-align: right; }
</style>

<div class="id-page">

    {{-- FRONT --}}
    <div class="id-front">
        <img src="{{ asset('images/bne-logo.png') }}" class="wm" alt="">

        <div class="front-top">
            <img src="{{ asset('images/qc-seal.png') }}" alt="QC">
            <div class="front-top-text">
                <div class="republic"><em>Republic of the Philippines</em></div>
                <div class="brgy">Barangay New Era</div>
                <div class="city">New Era, Quezon City, Metro Manila</div>
            </div>
            <img src="{{ asset('images/bne-logo.png') }}" alt="BNE">
        </div>

        <div class="front-ribbon">Official Identification Card</div>

        <div class="front-body">
            <div>
                <div class="front-photo">
                    @if($official->photo_path)
                        <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Photo">
                    @else
                        {{ strtoupper(substr($official->full_name, 0, 1)) }}
                    @endif
                    <div class="front-photo-label">Photo</div>
                </div>
            </div>

            <div class="front-info">
                <div class="front-name">{{ $official->full_name }}</div>
                <div class="front-position">{{ $official->position }}</div>
                <hr class="front-divider">
                @if($official->committee)
                <div class="f-row">
                    <span class="f-lbl">Committee</span>
                    <span class="f-val">{{ $official->committee }}</span>
                </div>
                @endif
                <div class="f-row">
                    <span class="f-lbl">Term</span>
                    <span class="f-val">{{ $termShort }}</span>
                </div>
                @if($official->contact_number)
                <div class="f-row">
                    <span class="f-lbl">Contact</span>
                    <span class="f-val">{{ $official->contact_number }}</span>
                </div>
                @endif
                <div class="f-row">
                    <span class="f-lbl">Status</span>
                    <span class="f-val" style="color:{{ $official->is_active ? '#16a34a' : '#6b7280' }};font-weight:800">
                        {{ $official->is_active ? '● ACTIVE' : '○ INACTIVE' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="front-bottom">
            <div class="idnum">{{ $idNumber }}</div>
            <div class="valid">
                <span>Valid Until</span>
                {{ $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('M d, Y') : '—' }}
            </div>
        </div>
    </div>

    {{-- BACK --}}
    <div class="id-back">
        <div class="back-top">
            <div class="back-top-text">Barangay New Era — Official ID</div>
        </div>

        <div class="back-body">
            <div class="back-sig-col">
                <div class="back-note">
                    This card is the property of Barangay New Era, Quezon City.
                    If found, please return to the nearest barangay hall or call the barangay hotline.
                    This card is non-transferable and valid only during the term indicated.
                </div>
                <div class="back-sig-box">
                    <div class="sig-lbl">Bearer's Signature</div>
                    <div class="sig-line"></div>
                </div>
                <div style="font-size:5pt;color:#aaa;text-align:center">Sign above your printed name</div>
                <div style="margin-top:6px;font-size:5pt;color:#555">
                    <strong>In case of emergency, contact:</strong><br>
                    Barangay New Era Hall<br>
                    New Era, Quezon City
                </div>
            </div>

            <div class="back-official-col">
                <div style="font-size:5pt;color:#aaa;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:3px">Issued By</div>
                <div class="back-official-photo">OFFICIAL<br>PHOTO</div>
                <div style="height:16px"></div>
                <div class="back-punong-sig"></div>
                <div class="back-punong-name">{{ $punong }}</div>
                <div class="back-punong-title">Punong Barangay</div>
            </div>
        </div>

        <div class="back-footer">
            <div class="contact">
                <strong>Barangay New Era</strong><br>
                New Era, Quezon City | District VI
            </div>
            <img src="{{ asset('images/bne-logo.png') }}" class="logo" alt="">
            <div class="notice">
                NOT TRANSFERABLE<br>
                GOVERNMENT ISSUED ID
            </div>
        </div>
    </div>

</div>
</div>

@endsection