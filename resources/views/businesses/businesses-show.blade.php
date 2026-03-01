@extends('layouts.app')

@section('title', $business->business_name)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Business Profile</h1>
        <p class="page-subtitle">{{ $business->permit_number }} — {{ $business->business_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.edit', $business) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('businesses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:var(--radius);background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-store" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:rgba(255,255,255,0.45);margin-bottom:4px">
                    {{ $business->permit_number }}
                </div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.3;margin-bottom:6px">
                    {{ $business->business_name }}
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-bottom:12px">
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
                <div style="display:flex;justify-content:space-between;font-size:10px;color:var(--text-subtle);margin-bottom:6px">
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
                      onsubmit="return confirm('Delete this business permit?')">
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
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">
                        {{ $d['label'] }}
                    </div>
                    <div style="font-size:13.5px;color:var(--text);font-weight:500">{{ $d['value'] }}</div>
                </div>
                @endforeach
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">
                    Business Address
                </div>
                <div style="font-size:13.5px;color:var(--text)">{{ $business->business_address }}</div>
            </div>

            @if($business->ownerResident)
            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">
                    Linked Resident
                </div>
                <a href="{{ route('residents.show', $business->ownerResident) }}"
                   style="display:flex;align-items:center;gap:10px;color:var(--navy)">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                        {{ strtoupper(substr($business->ownerResident->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13px">{{ $business->ownerResident->full_name }}</div>
                        <div class="td-muted">View resident profile →</div>
                    </div>
                </a>
            </div>
            @endif

            @if($business->remarks)
            <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">Remarks</div>
                <div style="font-size:13px;color:var(--text-muted);background:var(--surface2);padding:12px 14px;border-radius:var(--radius-sm);border:1px solid var(--border)">
                    {{ $business->remarks }}
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection
