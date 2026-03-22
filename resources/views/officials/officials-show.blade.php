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
    $punong   = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $termShort = ($official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—') . ' – ' . ($official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y') : '—');
    $idNumber = 'BNE-' . str_pad($official->id, 4, '0', STR_PAD_LEFT) . '-' . date('Y');
    $validUntil = $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('M d, Y') : '—';
@endphp
<style>
@media print {
    @page { size: 3.5in 4.8in; margin: 0.1in; }
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

.id-page { width: 3.3in; font-family: Arial, sans-serif; }

/* ===== FRONT ===== */
.id-front {
    width: 3.3in;
    height: 2.1in;
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    margin-bottom: 0.12in;
    /* Realistic card - subtle shadow effect via border */
    border: 0.5px solid rgba(0,0,0,0.15);
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
    /* Main background - deep navy gradient */
    background: linear-gradient(160deg, #0A1D3E 0%, #0D2144 40%, #122A56 70%, #0A1D3E 100%);
}

/* Diagonal geometric accent shapes */
.front-geo-1 {
    position: absolute;
    top: -20px; right: -20px;
    width: 100px; height: 100px;
    background: rgba(200,134,26,0.12);
    border-radius: 50%;
}
.front-geo-2 {
    position: absolute;
    bottom: -30px; left: -15px;
    width: 80px; height: 80px;
    background: rgba(200,134,26,0.07);
    border-radius: 50%;
}
.front-geo-3 {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 100%;
    background: repeating-linear-gradient(
        -45deg,
        transparent,
        transparent 18px,
        rgba(255,255,255,0.012) 18px,
        rgba(255,255,255,0.012) 19px
    );
}

/* Gold top accent bar */
.front-accent {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #8B5E0A, #C8861A, #F0C060, #E5A020, #C8861A, #8B5E0A);
}

/* Header */
.front-header {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    padding: 8px 10px 6px;
    gap: 7px;
    border-bottom: 1px solid rgba(200,134,26,0.2);
}
.front-header img { height: 30px; width: 30px; object-fit: contain; flex-shrink: 0; filter: drop-shadow(0 1px 2px rgba(0,0,0,0.3)); }
.fh-text { flex: 1; text-align: center; }
.fh-text .rep  { font-size: 5pt; color: rgba(255,255,255,0.5); font-style: italic; }
.fh-text .brgy { font-size: 8.5pt; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 0.04em; text-shadow: 0 1px 3px rgba(0,0,0,0.4); }
.fh-text .city { font-size: 5pt; color: rgba(200,134,26,0.85); }

/* Badge */
.front-badge {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 2px 0;
    background: linear-gradient(135deg, rgba(200,134,26,0.25), rgba(200,134,26,0.15));
    border-top: 1px solid rgba(200,134,26,0.3);
    border-bottom: 1px solid rgba(200,134,26,0.3);
    font-size: 5.5pt;
    font-weight: 800;
    color: #F0C060;
    text-transform: uppercase;
    letter-spacing: 0.18em;
}

/* Body */
.front-body {
    position: relative;
    z-index: 2;
    display: flex;
    padding: 8px 10px 30px;
    gap: 9px;
}

/* Photo */
.front-photo-wrap { flex-shrink: 0; }
.front-photo {
    width: 58px; height: 70px;
    border-radius: 4px;
    overflow: hidden;
    border: 2px solid rgba(200,134,26,0.6);
    box-shadow: 0 2px 8px rgba(0,0,0,0.4), inset 0 0 0 1px rgba(255,255,255,0.1);
    background: linear-gradient(135deg, #1a3a6b, #0D2144);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 900; color: #C8861A;
}
.front-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }

/* Info */
.front-info { flex: 1; min-width: 0; }
.fi-name     { font-size: 8pt; font-weight: 900; color: #fff; text-transform: uppercase; line-height: 1.15; text-shadow: 0 1px 2px rgba(0,0,0,0.3); }
.fi-position { font-size: 6.5pt; font-weight: 700; color: #F0C060; text-transform: uppercase; letter-spacing: 0.05em; margin: 2px 0 5px; }
.fi-divider  { border: none; border-top: 1px solid rgba(200,134,26,0.25); margin-bottom: 5px; }

.fi-row { display: flex; gap: 4px; margin-bottom: 3.5px; align-items: flex-start; }
.fi-lbl { font-size: 4.5pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.4); min-width: 38px; padding-top: 0.5px; }
.fi-val { font-size: 6pt; color: rgba(255,255,255,0.85); font-weight: 600; flex: 1; line-height: 1.2; }
.fi-val.active { color: #6EE7A0; }

/* Bottom bar */
.front-bottom {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 0.26in;
    background: rgba(0,0,0,0.35);
    border-top: 1px solid rgba(200,134,26,0.2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 10px;
    z-index: 2;
    backdrop-filter: blur(2px);
}
.fb-idnum { font-family: 'Courier New', monospace; font-size: 6pt; color: rgba(200,134,26,0.9); letter-spacing: 0.1em; }
.fb-valid { text-align: right; }
.fb-valid .v-lbl  { font-size: 4pt; color: rgba(255,255,255,0.35); text-transform: uppercase; letter-spacing: 0.06em; display: block; }
.fb-valid .v-date { font-size: 6pt; color: rgba(200,134,26,0.85); font-weight: 700; }

/* Watermark */
.front-wm {
    position: absolute;
    bottom: 10px; right: 8px;
    width: 55px; height: 55px;
    opacity: 0.06;
    object-fit: contain;
    z-index: 1;
}

/* ===== BACK ===== */
.id-back {
    width: 3.3in;
    height: 2.1in;
    border-radius: 8px;
    overflow: hidden;
    border: 0.5px solid rgba(0,0,0,0.12);
    background: linear-gradient(160deg, #0A1D3E 0%, #0D2144 50%, #0A1D3E 100%);
    position: relative;
}

/* Stripe pattern */
.back-stripe {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: repeating-linear-gradient(
        90deg,
        transparent,
        transparent 24px,
        rgba(255,255,255,0.015) 24px,
        rgba(255,255,255,0.015) 25px
    );
}

/* Magnetic stripe */
.back-mag {
    background: linear-gradient(180deg, #111 0%, #1a1a1a 40%, #222 100%);
    height: 0.28in;
    margin-top: 0.22in;
    position: relative;
    z-index: 2;
    box-shadow: 0 1px 3px rgba(0,0,0,0.5);
}

/* Content area */
.back-content {
    position: relative;
    z-index: 2;
    padding: 7px 10px;
    display: flex;
    gap: 10px;
}

.back-left { flex: 1; }
.back-note {
    font-size: 5pt;
    color: rgba(255,255,255,0.45);
    line-height: 1.55;
    margin-bottom: 7px;
}
.back-sig {
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 3px;
    height: 0.38in;
    background: rgba(255,255,255,0.04);
    padding: 3px 6px;
}
.back-sig .s-lbl { font-size: 4pt; color: rgba(255,255,255,0.3); text-transform: uppercase; letter-spacing: 0.06em; }
.back-sig .s-line { border-top: 1px solid rgba(255,255,255,0.15); margin-top: 16px; }

.back-right { min-width: 0.95in; text-align: center; }
.back-punong-lbl { font-size: 4.5pt; color: rgba(200,134,26,0.7); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 3px; }
.back-punong-sig-line { border-top: 1px solid rgba(255,255,255,0.3); width: 80%; margin: 20px auto 2px; }
.back-punong-name  { font-size: 5.5pt; font-weight: 800; color: #fff; text-transform: uppercase; }
.back-punong-title { font-size: 4.5pt; color: rgba(200,134,26,0.8); }

/* Bottom footer */
.back-footer {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(0,0,0,0.3);
    border-top: 1px solid rgba(200,134,26,0.15);
    padding: 3px 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 2;
}
.bf-left  { font-size: 4.5pt; color: rgba(255,255,255,0.35); }
.bf-logo  { width: 16px; height: 16px; object-fit: contain; opacity: 0.35; }
.bf-right { font-size: 4pt; color: rgba(200,134,26,0.5); text-align: right; text-transform: uppercase; letter-spacing: 0.04em; }

/* Gold accent bottom */
.back-accent-bottom {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2.5px;
    background: linear-gradient(90deg, #8B5E0A, #C8861A, #F0C060, #C8861A, #8B5E0A);
}
</style>

<div class="id-page">

    {{-- FRONT --}}
    <div class="id-front">
        <div class="front-accent"></div>
        <div class="front-geo-1"></div>
        <div class="front-geo-2"></div>
        <div class="front-geo-3"></div>
        <img src="{{ asset('images/bne-logo.png') }}" class="front-wm" alt="">

        <div class="front-header">
            <img src="{{ asset('images/qc-seal.png') }}" alt="QC">
            <div class="fh-text">
                <div class="rep"><em>Republic of the Philippines</em></div>
                <div class="brgy">Barangay New Era</div>
                <div class="city">New Era, Quezon City, Metro Manila</div>
            </div>
            <img src="{{ asset('images/bne-logo.png') }}" alt="BNE">
        </div>

        <div class="front-badge">✦ &nbsp; Official Identification Card &nbsp; ✦</div>

        <div class="front-body">
            <div class="front-photo-wrap">
                <div class="front-photo">
                    @if($official->photo_path)
                        <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Photo">
                    @else
                        {{ strtoupper(substr($official->full_name, 0, 1)) }}
                    @endif
                </div>
            </div>
            <div class="front-info">
                <div class="fi-name">{{ $official->full_name }}</div>
                <div class="fi-position">{{ $official->position }}</div>
                <div class="fi-divider"></div>
                @if($official->committee)
                <div class="fi-row">
                    <span class="fi-lbl">Committee</span>
                    <span class="fi-val">{{ $official->committee }}</span>
                </div>
                @endif
                <div class="fi-row">
                    <span class="fi-lbl">Term</span>
                    <span class="fi-val">{{ $termShort }}</span>
                </div>
                @if($official->contact_number)
                <div class="fi-row">
                    <span class="fi-lbl">Contact</span>
                    <span class="fi-val">{{ $official->contact_number }}</span>
                </div>
                @endif
                <div class="fi-row">
                    <span class="fi-lbl">Status</span>
                    <span class="fi-val {{ $official->is_active ? 'active' : '' }}">
                        {{ $official->is_active ? '● ACTIVE' : '○ INACTIVE' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="front-bottom">
            <div class="fb-idnum">{{ $idNumber }}</div>
            <div class="fb-valid">
                <span class="v-lbl">Valid Until</span>
                <span class="v-date">{{ $validUntil }}</span>
            </div>
        </div>
    </div>

    {{-- BACK --}}
    <div class="id-back">
        <div class="back-accent-bottom"></div>
        <div class="back-stripe"></div>
        <div class="back-mag"></div>

        <div class="back-content">
            <div class="back-left">
                <div class="back-note">
                    This card is the official property of Barangay New Era, Quezon City.
                    It is non-transferable and valid only during the indicated term.
                    If found, please return to the nearest Barangay Hall.
                </div>
                <div class="back-sig">
                    <div class="s-lbl">Bearer's Signature</div>
                    <div class="s-line"></div>
                </div>
            </div>

            <div class="back-right">
                <div class="back-punong-lbl">Issued By</div>
                <div style="height:20px"></div>
                <div class="back-punong-sig-line"></div>
                <div class="back-punong-name">{{ $punong }}</div>
                <div class="back-punong-title">Punong Barangay</div>
            </div>
        </div>

        <div class="back-footer">
            <div class="bf-left">Barangay New Era · District VI, Quezon City</div>
            <img src="{{ asset('images/bne-logo.png') }}" class="bf-logo" alt="">
            <div class="bf-right">Not Transferable<br>Gov't Issued ID</div>
        </div>
    </div>

</div>
</div>

@endsection