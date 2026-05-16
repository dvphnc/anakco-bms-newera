@extends('layouts.app')

@section('title', 'Puroks')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Puroks & Leaders</h1>
        <p class="page-subtitle">Manage purok assignments and leaders in Barangay New Era</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-location-dot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $puroks->count() }}</div>
            <div class="stat-label">Total Puroks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $puroks->whereNotNull('leader_id')->count() }}</div>
            <div class="stat-label">With Leaders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-circle-exclamation"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $puroks->whereNull('leader_id')->count() }}</div>
            <div class="stat-label">No Leader Assigned</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ $puroks->sum('residents_count') }}</div>
            <div class="stat-label">Total Residents</div>
        </div>
    </div>
</div>

{{-- Puroks Grid --}}
@php $totalResidents = $puroks->sum('residents_count') ?: 1; @endphp
<div class="grid-3">
    @foreach($puroks as $purok)
    @php $pct = round(($purok->residents_count / $totalResidents) * 100); @endphp
    <div class="card">
        <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:20px;display:flex;align-items:center;gap:14px">
            <div style="width:48px;height:48px;border-radius:var(--radius);background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-location-dot" style="font-size:20px;color:var(--gold-light)"></i>
            </div>
            <div style="min-width:0">
                <div style="font-size:15px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $purok->name }}</div>
                <div style="font-size:13px;color:rgba(255,255,255,0.5);margin-top:2px">
                    {{ $purok->residents_count }} {{ Str::plural('resident', $purok->residents_count) }}
                </div>
            </div>
            <div style="margin-left:auto">
                <a href="{{ route('puroks.edit', $purok) }}"
                   class="btn btn-sm" style="background:rgba(255,255,255,0.1);color:#fff;border:1px solid rgba(255,255,255,0.2)">
                    <i class="fas fa-pen"></i> Edit
                </a>
            </div>
        </div>

        <div style="padding:16px 20px">
            {{-- Leader --}}
            <div style="margin-bottom:12px">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">
                    Purok Leader
                </div>
                @if($purok->leader)
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                            @if($purok->leader->photo_path)
                                <img src="{{ asset('storage/'.$purok->leader->photo_path) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                            @else
                                {{ strtoupper(substr($purok->leader->first_name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:14px">{{ $purok->leader->full_name }}</div>
                            <div class="td-muted">{{ $purok->leader->contact_number ?? 'No contact' }}</div>
                        </div>
                        <span class="badge badge-green" style="margin-left:auto">Assigned</span>
                    </div>
                @else
                    <div style="display:flex;align-items:center;gap:10px;padding:10px;background:var(--surface2);border-radius:var(--radius-sm);border:1px dashed var(--border)">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted)">
                            <i class="fas fa-user-slash" style="font-size:14px"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;color:var(--text-muted)">No leader assigned</div>
                            <a href="{{ route('puroks.edit', $purok) }}" style="font-size:13px;color:var(--navy)">
                                Assign now →
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            @if($purok->description)
            <div style="font-size:13px;color:var(--text-muted);padding-top:10px;border-top:1px solid var(--border)">
                {{ $purok->description }}
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@endsection