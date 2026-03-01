@extends('layouts.app')

@section('title', $household->household_number)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Household Profile</h1>
        <p class="page-subtitle">{{ $household->household_number }} — {{ $household->household_head }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('households.edit', $household) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('households.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    {{-- LEFT: Info card --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-house" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:11px;color:rgba(255,255,255,0.5);margin-bottom:4px;text-transform:uppercase;letter-spacing:0.1em">
                    {{ $household->household_number }}
                </div>
                <div style="font-size:16px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px">
                    {{ $household->household_head }}
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.55)">
                    {{ $household->purok->name ?? '—' }}
                </div>
                <div style="margin-top:12px">
                    <span class="badge {{ $household->status === 'Active' ? 'badge-green' : 'badge-gray' }}">
                        {{ $household->status }}
                    </span>
                </div>
            </div>

            {{-- Stats --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)">
                <div style="padding:14px 16px;text-align:center;border-right:1px solid var(--border)">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">
                        {{ $household->residents->count() }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted)">Members</div>
                </div>
                <div style="padding:14px 16px;text-align:center">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">
                        {{ $household->residents->where('is_voter',true)->count() }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted)">Voters</div>
                </div>
            </div>
        </div>

        {{-- Utilities --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-plug"></i> Utilities</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
                @php
                    $utilities = [
                        ['label'=>'Electricity','icon'=>'fa-bolt','val'=>$household->has_electricity],
                        ['label'=>'Water Supply','icon'=>'fa-droplet','val'=>$household->has_water],
                        ['label'=>'Internet','icon'=>'fa-wifi','val'=>$household->has_internet],
                        ['label'=>'4Ps Beneficiary','icon'=>'fa-hand-holding-heart','val'=>$household->is_4ps_beneficiary],
                    ];
                @endphp
                @foreach($utilities as $u)
                <div style="display:flex;align-items:center;justify-content:space-between">
                    <span style="font-size:13px;color:var(--text-muted)">
                        <i class="fas {{ $u['icon'] }}" style="width:16px;color:var(--gold)"></i>
                        {{ $u['label'] }}
                    </span>
                    <span class="badge {{ $u['val'] ? 'badge-green' : 'badge-gray' }}">
                        {{ $u['val'] ? 'Yes' : 'No' }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('households.edit', $household) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Household
                </a>
                <form method="POST" action="{{ route('households.destroy', $household) }}"
                      onsubmit="return confirm('Delete this household?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Details card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-circle-info"></i> Household Details</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                    @php
                        $details = [
                            ['label'=>'Household No.',  'value'=>$household->household_number],
                            ['label'=>'Household Head', 'value'=>$household->household_head],
                            ['label'=>'Purok',          'value'=>$household->purok->name ?? '—'],
                            ['label'=>'Status',         'value'=>$household->status],
                            ['label'=>'Housing Type',   'value'=>$household->housing_type ?? '—'],
                            ['label'=>'Structure Type', 'value'=>$household->structure_type ?? '—'],
                            ['label'=>'Monthly Income', 'value'=>$household->monthly_income ? '₱'.number_format($household->monthly_income,2) : '—'],
                            ['label'=>'Registered',     'value'=>$household->created_at->format('F d, Y')],
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
                @if($household->address)
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">Address</div>
                    <div style="font-size:13.5px;color:var(--text)">{{ $household->address }}</div>
                </div>
                @endif
                @if($household->notes)
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">Notes</div>
                    <div style="font-size:13px;color:var(--text-muted)">{{ $household->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Members --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-users"></i> Household Members</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $household->residents->count() }} member(s)</span>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Classifications</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($household->residents as $resident)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:13px">{{ $resident->full_name }}</div>
                                <div class="td-muted">{{ $resident->civil_status }}</div>
                            </td>
                            <td>{{ $resident->age ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $resident->gender === 'Male' ? 'badge-blue' : 'badge-orange' }}">
                                    {{ $resident->gender }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;flex-wrap:wrap;gap:4px">
                                    @if($resident->is_voter)   <span class="badge badge-green" style="font-size:10px">Voter</span> @endif
                                    @if($resident->is_senior)  <span class="badge badge-yellow" style="font-size:10px">Senior</span> @endif
                                    @if($resident->is_pwd)     <span class="badge badge-blue" style="font-size:10px">PWD</span> @endif
                                    @if($resident->is_4ps)     <span class="badge badge-gold" style="font-size:10px">4Ps</span> @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $resident->residency_status === 'Active' ? 'badge-green' : 'badge-gray' }}">
                                    {{ $resident->residency_status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('residents.show', $resident) }}" class="btn btn-secondary btn-sm btn-icon">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state" style="padding:28px">
                                    <i class="fas fa-users"></i>
                                    <p>No members linked to this household.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection
