@extends('layouts.app')

@section('title', $official->full_name)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Official Profile</h1>
        <p class="page-subtitle">{{ $official->full_name }} — {{ $official->position }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('officials.edit', $official) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

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
                <div style="font-size:17px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px">
                    {{ $official->full_name }}
                </div>
                <div style="font-size:12px;color:rgba(229,160,32,0.85);font-weight:500;margin-bottom:10px">
                    {{ $official->position }}
                </div>
                <span class="badge {{ $official->is_active ? 'badge-green' : 'badge-gray' }}">
                    {{ $official->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            {{-- Term bar --}}
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
                <div style="font-size:10px;color:var(--text-subtle);margin-top:5px;text-align:center">
                    {{ $tPct }}% of term served
                </div>
            </div>
            @endif

            @if($official->contact_number)
            <div style="padding:12px 16px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:3px">Contact</div>
                <div style="font-size:13px;color:var(--text)">{{ $official->contact_number }}</div>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('officials.edit', $official) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Profile
                </a>
                <form method="POST" action="{{ route('officials.destroy', $official) }}"
                      onsubmit="return confirm('Delete this official?')">
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
            <span class="card-title"><i class="fas fa-info-circle"></i> Official Details</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @php
                    $details = [
                        ['label' => 'Full Name',     'value' => $official->full_name],
                        ['label' => 'Position',      'value' => $official->position],
                        ['label' => 'Committee',     'value' => $official->committee ?? '—'],
                        ['label' => 'Status',        'value' => $official->is_active ? 'Active' : 'Inactive'],
                        ['label' => 'Contact No.',   'value' => $official->contact_number ?? '—'],
                        ['label' => 'Date Added',    'value' => $official->created_at->format('F d, Y')],
                        ['label' => 'Term Start',    'value' => $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('F d, Y') : '—'],
                        ['label' => 'Term End',      'value' => $official->term_end   ? \Carbon\Carbon::parse($official->term_end)->format('F d, Y')   : '—'],
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
        </div>
    </div>

</div>

@endsection