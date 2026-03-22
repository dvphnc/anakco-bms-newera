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

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:28px 20px;text-align:center">
                <div style="width:90px;height:90px;border-radius:50%;overflow:hidden;margin:0 auto 16px;border:3px solid rgba(200,134,26,0.45);box-shadow:0 0 0 5px rgba(200,134,26,0.08)">
                    @if($official->photo_path)
                        <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Photo"
                             style="width:100%;height:100%;object-fit:cover;display:block">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--gold),var(--gold-light));font-size:32px;font-weight:800;color:var(--navy)">
                            {{ strtoupper(substr($official->full_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="font-size:17px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px">{{ $official->full_name }}</div>
                <div style="font-size:12px;color:rgba(229,160,32,0.85);font-weight:500;margin-bottom:10px">{{ $official->position }}</div>
                <span class="badge {{ $official->is_active ? 'badge-green' : 'badge-gray' }}">
                    {{ $official->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            @if($official->term_start && $official->term_end)
            @php
                $tStart   = \Carbon\Carbon::parse($official->term_start);
                $tEnd     = \Carbon\Carbon::parse($official->term_end);
                $tTotal   = max(1, $tStart->diffInDays($tEnd));
                $tElapsed = min($tTotal, $tStart->diffInDays(now()));
                $tPct     = round(($tElapsed / $tTotal) * 100);
            @endphp
            <div style="padding:14px 16px;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--text-subtle);margin-bottom:6px">
                    <span>{{ $tStart->format('Y') }}</span>
                    <span>{{ $tEnd->format('Y') }}</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:{{ $tPct }}%;background:var(--gold)"></div>
                </div>
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
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- RIGHT --}}
    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-info-circle"></i> Official Details</span></div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @php
                    $details = [
                        ['label'=>'Full Name',  'value'=>$official->full_name],
                        ['label'=>'Position',   'value'=>$official->position],
                        ['label'=>'Committee',  'value'=>$official->committee ?? '—'],
                        ['label'=>'Status',     'value'=>$official->is_active ? 'Active' : 'Inactive'],
                        ['label'=>'Contact No.','value'=>$official->contact_number ?? '—'],
                        ['label'=>'Date Added', 'value'=>$official->created_at->format('F d, Y')],
                        ['label'=>'Term Start', 'value'=>$official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('F d, Y') : '—'],
                        ['label'=>'Term End',   'value'=>$official->term_end   ? \Carbon\Carbon::parse($official->term_end)->format('F d, Y')   : '—'],
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
     PRINTABLE ID CARD
============================================= --}}
<div class="print-only" id="id-card">
@php
    $punong    = \App\Models\Official::where('position','Punong Barangay')->where('is_active',true)->first()?->full_name ?? 'PUNONG BARANGAY';
    $termStart = $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('F d, Y') : '—';
    $termEnd   = $official->term_end   ? \Carbon\Carbon::parse($official->term_end)->format('F d, Y')   : '—';
    $idNumber  = 'BNE-OFF-' . str_pad($official->id, 4, '0', STR_PAD_LEFT) . '-' . date('Y');
@endphp
<style>
@media print {
    @page { size: 3.375in 5.375in; margin: 0; }
    .no-print  { display: none !important; }
    .print-only { display: block !important; }
    .sidebar, .topbar, .watermark, .page-header { display: none !important; }
}
.print-only { display: none; }

* { box-sizing: border-box; }

.id-wrap {
    width: 3.375in;
    height: 5.375in;
    font-family: Arial, sans-serif;
    overflow: hidden;
    position: relative;
    background: #fff;
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
}

.id-header {
    background: linear-gradient(135deg, #0D2144 0%, #163160 100%);
    padding: 9px 11px 12px;
    display: flex;
    align-items: center;
    gap: 7px;
    position: relative;
}
.id-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #C8861A, #E5A020, #C8861A);
}
.id-header img { width: 36px; height: 36px; object-fit: contain; flex-shrink: 0; }
.id-header-text { flex: 1; text-align: center; }
.id-header-text .republic { font-size: 6pt; color: rgba(255,255,255,0.65); font-style: italic; }
.id-header-text .brgy     { font-size: 9.5pt; font-weight: 800; color: #fff; text-transform: uppercase; letter-spacing: 0.03em; }
.id-header-text .office   { font-size: 5.5pt; color: rgba(229,160,32,0.85); font-style: italic; }
.id-header-text .addr     { font-size: 5pt; color: rgba(255,255,255,0.45); }

.id-type-bar {
    background: #C8861A;
    text-align: center;
    padding: 3.5px 0;
    font-size: 7.5pt;
    font-weight: 800;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.14em;
}

.id-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    padding: 12px 14px 8px;
    position: relative;
}

.id-watermark {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 140px; height: 140px;
    opacity: 0.04;
    object-fit: contain;
    pointer-events: none;
}

.id-photo-row {
    display: flex;
    gap: 12px;
    margin-bottom: 10px;
    align-items: flex-start;
}
.id-photo {
    width: 80px;
    height: 95px;
    border-radius: 5px;
    overflow: hidden;
    border: 2.5px solid #0D2144;
    box-shadow: 0 2px 6px rgba(13,33,68,0.18);
    flex-shrink: 0;
    background: linear-gradient(135deg, #C8861A, #E5A020);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    font-weight: 800;
    color: #0D2144;
}
.id-photo img { width: 100%; height: 100%; object-fit: cover; display: block; }

.id-name-block { flex: 1; padding-top: 4px; }
.id-name     { font-size: 10.5pt; font-weight: 800; color: #0D2144; text-transform: uppercase; line-height: 1.2; }
.id-position { font-size: 8pt; color: #C8861A; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 3px; }
.id-divider  { border: none; border-top: 1.5px solid #e5e7eb; margin: 3px 0 6px; }

.id-field { display: flex; margin-bottom: 5px; gap: 6px; }
.id-field .lbl { font-size: 6pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #9ca3af; min-width: 52px; padding-top: 1px; }
.id-field .val { font-size: 7.5pt; color: #1f2937; font-weight: 600; flex: 1; line-height: 1.3; }

.id-idnum {
    margin-top: auto;
    text-align: center;
    padding: 5px 0 3px;
    border-top: 1px dashed #e5e7eb;
}
.id-idnum .num { font-family: 'Courier New', monospace; font-size: 7.5pt; font-weight: 700; color: #555; letter-spacing: 0.06em; }
.id-idnum .lbl { font-size: 5.5pt; color: #aaa; text-transform: uppercase; letter-spacing: 0.08em; }

.id-footer {
    background: linear-gradient(135deg, #0D2144, #163160);
    padding: 7px 12px 8px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}
.sig-area { text-align: center; }
.sig-line { border-top: 1px solid rgba(255,255,255,0.35); width: 100px; margin: 0 auto 2px; }
.sig-name  { font-size: 6pt; font-weight: 700; color: #fff; text-transform: uppercase; }
.sig-title { font-size: 5pt; color: rgba(229,160,32,0.85); }
.validity { text-align: right; }
.validity .v-lbl  { font-size: 5.5pt; color: rgba(255,255,255,0.45); text-transform: uppercase; }
.validity .v-date { font-size: 7pt; color: #C8861A; font-weight: 700; }
.validity .v-note { font-size: 4.5pt; color: rgba(255,255,255,0.25); margin-top: 3px; }
</style>

<div class="id-wrap">
    {{-- Header --}}
    <div class="id-header">
        <img src="{{ asset('images/qc-seal.png') }}" alt="QC">
        <div class="id-header-text">
            <div class="republic"><em>Republic of the Philippines</em></div>
            <div class="brgy">Barangay New Era</div>
            <div class="office">Office of the Punong Barangay</div>
            <div class="addr">New Era, Quezon City, Metro Manila</div>
        </div>
        <img src="{{ asset('images/bne-logo.png') }}" alt="BNE">
    </div>

    {{-- Type bar --}}
    <div class="id-type-bar">Official Identification Card</div>

    {{-- Body --}}
    <div class="id-body">
        <img src="{{ asset('images/bne-logo.png') }}" class="id-watermark" alt="">

        {{-- Photo + Name --}}
        <div class="id-photo-row">
            <div class="id-photo">
                @if($official->photo_path)
                    <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Photo">
                @else
                    {{ strtoupper(substr($official->full_name, 0, 1)) }}
                @endif
            </div>
            <div class="id-name-block">
                <div class="id-name">{{ $official->full_name }}</div>
                <div class="id-position">{{ $official->position }}</div>
                <hr class="id-divider">
                @if($official->committee)
                <div class="id-field">
                    <span class="lbl">Committee</span>
                    <span class="val">{{ $official->committee }}</span>
                </div>
                @endif
                <div class="id-field">
                    <span class="lbl">Status</span>
                    <span class="val" style="color:{{ $official->is_active ? '#16a34a' : '#6b7280' }}">
                        {{ $official->is_active ? '● Active' : '○ Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Fields --}}
        <div class="id-field">
            <span class="lbl">Term</span>
            <span class="val">{{ $termStart }} – {{ $termEnd }}</span>
        </div>
        @if($official->contact_number)
        <div class="id-field">
            <span class="lbl">Contact</span>
            <span class="val">{{ $official->contact_number }}</span>
        </div>
        @endif
        <div class="id-field">
            <span class="lbl">Barangay</span>
            <span class="val">New Era, Quezon City</span>
        </div>

        {{-- Signature box --}}
        <div style="margin-top:10px;border:1px solid #e5e7eb;border-radius:4px;padding:8px 10px;background:#f8f9fb">
            <div style="font-size:5.5pt;color:#aaa;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:16px">Bearer's Signature</div>
            <div style="border-top:1px solid #ccc;width:100%"></div>
        </div>

        {{-- ID Number --}}
        <div class="id-idnum">
            <div class="lbl">ID Number</div>
            <div class="num">{{ $idNumber }}</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="id-footer">
        <div class="sig-area">
            <div style="height:20px"></div>
            <div class="sig-line"></div>
            <div class="sig-name">{{ $punong }}</div>
            <div class="sig-title">Punong Barangay</div>
        </div>
        <div class="validity">
            <div class="v-lbl">Valid Until</div>
            <div class="v-date">{{ $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('M d, Y') : '—' }}</div>
            <div class="v-note">NOT TRANSFERABLE</div>
        </div>
    </div>
</div>
</div>

@endsection