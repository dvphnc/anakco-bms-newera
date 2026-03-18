@extends('layouts.app')

@section('title', $blotter->case_number)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Blotter Case</h1>
        <p class="page-subtitle">{{ $blotter->case_number }} — {{ $blotter->incident_type }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.edit', $blotter) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('blotter.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-gavel" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:10px;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.5);margin-bottom:4px">
                    {{ $blotter->case_number }}
                </div>
                <div style="font-size:15px;font-weight:700;color:#fff;margin-bottom:10px">
                    {{ $blotter->incident_type }}
                </div>
                @php
                    $cls = match($blotter->status) {
                        'Active'                       => 'badge-red',
                        'Under Investigation'          => 'badge-yellow',
                        'Mediated'                     => 'badge-blue',
                        'Settled'                      => 'badge-green',
                        'Closed'                       => 'badge-gray',
                        'Referred to Higher Authority' => 'badge-orange',
                        default                        => 'badge-gray'
                    };
                @endphp
                <span class="badge {{ $cls }}">{{ $blotter->status }}</span>
            </div>
            <div style="padding:14px 20px;border-top:1px solid var(--border)">
                <div style="display:flex;flex-direction:column;gap:10px">
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Incident Date</div>
                        <div style="font-size:13px;font-weight:500;color:var(--text)">
                            {{ $blotter->incident_date ? \Carbon\Carbon::parse($blotter->incident_date)->format('F d, Y') : '—' }}
                        </div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Location</div>
                        <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->incident_location ?? '—' }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Filed By</div>
                        <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->filedBy->name ?? '—' }}</div>
                    </div>
                    @if($blotter->settled_at)
                    <div>
                        <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">Settled On</div>
                        <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->settled_at->format('F d, Y') }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Attachment --}}
        @if($blotter->file_path)
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-paperclip"></i> Attachment</span>
            </div>
            <div class="card-body">
                @php $isImage = in_array(strtolower($blotter->file_type ?? ''), ['jpg','jpeg','png','gif']); @endphp
                @if($isImage)
                    <img src="{{ asset('storage/'.$blotter->file_path) }}"
                         style="width:100%;border-radius:var(--radius);border:1px solid var(--border)"
                         alt="Attachment">
                @endif
                <div style="display:flex;align-items:center;gap:10px;{{ $isImage ? 'margin-top:10px' : '' }}">
                    <i class="fas {{ $isImage ? 'fa-image' : 'fa-file-pdf' }}" style="font-size:18px;color:var(--navy)"></i>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:12px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $blotter->file_original_name ?? 'Attached File' }}
                        </div>
                        <div style="font-size:11px;color:var(--text-muted)">{{ strtoupper($blotter->file_type ?? '') }}</div>
                    </div>
                    <a href="{{ asset('storage/'.$blotter->file_path) }}" target="_blank" class="btn btn-secondary btn-sm">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('blotter.edit', $blotter) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Case
                </a>
                @if(in_array($blotter->status, ['Active','Under Investigation']))
                <form method="POST" action="{{ route('blotter.update', $blotter) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="Settled">
                    <input type="hidden" name="incident_type" value="{{ $blotter->incident_type }}">
                    <input type="hidden" name="incident_date" value="{{ $blotter->incident_date?->format('Y-m-d') }}">
                    <input type="hidden" name="incident_location" value="{{ $blotter->incident_location }}">
                    <input type="hidden" name="incident_details" value="{{ $blotter->incident_details }}">
                    <input type="hidden" name="complainant_name" value="{{ $blotter->complainant_name }}">
                    <input type="hidden" name="respondent_name" value="{{ $blotter->respondent_name }}">
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-handshake"></i> Mark as Settled
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('blotter.destroy', $blotter) }}"
                      onsubmit="return confirm('Delete this case?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete Case
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Incident Details --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-lines"></i> Incident Details</span>
            </div>
            <div class="card-body">
                <div style="font-size:13.5px;color:var(--text);line-height:1.8;white-space:pre-line">
                    {{ $blotter->incident_details ?? 'No details recorded.' }}
                </div>
            </div>
        </div>

        {{-- Parties --}}
        <div class="grid-2">
            {{-- Complainant --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-user"></i> Complainant</span>
                </div>
                <div class="card-body">
                    @if($blotter->complainantResident)
                        <a href="{{ route('residents.show', $blotter->complainantResident) }}"
                           style="display:flex;align-items:center;gap:10px;margin-bottom:14px;color:var(--navy)">
                            <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;color:#fff">
                                {{ strtoupper(substr($blotter->complainantResident->first_name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px">{{ $blotter->complainantResident->full_name }}</div>
                                <div class="td-muted">Registered Resident</div>
                            </div>
                        </a>
                    @else
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-muted)">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13px">{{ $blotter->complainant_name ?? '—' }}</div>
                                <div class="td-muted">External</div>
                            </div>
                        </div>
                    @endif
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @if($blotter->complainant_address)
                        <div>
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Address</div>
                            <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->complainant_address }}</div>
                        </div>
                        @endif
                        @if($blotter->complainant_contact)
                        <div>
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Contact</div>
                            <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->complainant_contact }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Respondent --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title"><i class="fas fa-user-slash"></i> Respondent</span>
                </div>
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--text-muted)">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div style="font-weight:600;font-size:13px">{{ $blotter->respondent_name ?? '—' }}</div>
                            <div class="td-muted">Respondent</div>
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        @if($blotter->respondent_address)
                        <div>
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Address</div>
                            <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->respondent_address }}</div>
                        </div>
                        @endif
                        @if($blotter->respondent_contact)
                        <div>
                            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Contact</div>
                            <div style="font-size:13px;color:var(--text-muted)">{{ $blotter->respondent_contact }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Resolution --}}
        @if($blotter->resolution_notes)
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-clipboard-check"></i> Resolution Notes</span>
            </div>
            <div class="card-body">
                <div style="font-size:13.5px;color:var(--text);line-height:1.8;white-space:pre-line">
                    {{ $blotter->resolution_notes }}
                </div>
            </div>
        </div>
        @endif

        {{-- Activity Log --}}
        @include('partials._activity-log', ['record' => $blotter])

    </div>

</div>

@endsection