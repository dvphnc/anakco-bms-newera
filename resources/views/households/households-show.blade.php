@extends('layouts.app')

@section('title', $household->household_number)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Household Profile</h1>
        <p class="page-subtitle">{{ $household->household_number }} — {{ $household->household_head ?? "—" }}</p>
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

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-house" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.5);margin-bottom:4px;text-transform:uppercase;letter-spacing:0.1em">
                    {{ $household->household_number }}
                </div>
                <div style="font-size:16px;font-weight:700;color:#fff;line-height:1.2;margin-bottom:6px">
                    {{ $household->household_head ?? "—" }}
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.55)">
                    {{ $household->purok->name ?? "—" }}
                </div>
            </div>

            {{-- Stats --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)">
                <div style="padding:14px 16px;text-align:center;border-right:1px solid var(--border)">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">
                        {{ $household->family_size ?? 0 }}
                    </div>
                    <div style="font-size:13px;color:var(--text-muted)">Living Members</div>
                </div>
                <div style="padding:14px 16px;text-align:center">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">
                        {{ $household->residents->count() }}
                    </div>
                    <div style="font-size:13px;color:var(--text-muted)">All-time Members</div>
                </div>
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
                      data-confirm="Delete household {{ $household->household_number }}? This cannot be undone."
                      data-confirm-title="Delete Household"
                      data-confirm-ok="Delete">
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

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-circle-info"></i> Household Details</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                    @php
                        $details = [
                            ['label'=>'Household No.',  'value'=>$household->household_number],
                            ['label'=>'Household Head', 'value'=>$household->household_head ?? '—'],
                            ['label'=>'Purok',          'value'=>$household->purok->name ?? '—'],
                            ['label'=>'Living Members', 'value'=>$household->family_size ?? '—'],
                            ['label'=>'Voter Household','value'=>$household->is_voter_household ? 'Yes' : 'No'],
                            ['label'=>'Registered',     'value'=>$household->created_at->format('m/d/Y')],
                        ];
                    @endphp
                    @foreach($details as $d)
                    <div style="padding:10px 0;border-bottom:1px solid var(--border);{{ $loop->even ? 'padding-left:24px' : '' }}">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">
                            {{ $d['label'] }}
                        </div>
                        <div style="font-size:15px;color:var(--text);font-weight:500">{{ $d['value'] }}</div>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border)">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:4px">Address</div>
                    <div style="font-size:15px;color:var(--text)">{{ $household->address }}</div>
                </div>
            </div>
        </div>

        {{-- Members --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-users"></i> Household Members</span>
                <span style="font-size:13px;color:var(--text-muted)">{{ $household->residents->count() }} member(s)</span>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($household->residents as $resident)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:14px">
                                    {{ $resident->full_name }}
                                    @if($resident->id === $household->head_resident_id)
                                        <span class="badge badge-navy" style="margin-left:4px">Head</span>
                                    @endif
                                </div>
                                <div class="td-muted">
                                    {{ $resident->relationship_to_head ?? $resident->civil_status }}
                                    @if($resident->household_assignment === 'manual')
                                        · <span title="Staff chose this household; it won't change automatically">assigned manually</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $resident->age ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $resident->gender === 'Male' ? 'badge-blue' : 'badge-orange' }}">
                                    {{ $resident->gender }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $resident->residency_badge }}">
                                    {{ $resident->residency_label }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:6px;justify-content:flex-end">
                                    @if($resident->residency_status === 'Active' && $resident->id !== $household->head_resident_id)
                                        <form method="POST" action="{{ route('households.head', $household) }}"
                                              data-confirm="Make {{ $resident->full_name }} the head of this household?"
                                              data-confirm-title="Change Household Head"
                                              data-confirm-ok="Make Head"
                                              data-confirm-type="safe">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="resident_id" value="{{ $resident->id }}">
                                            <button type="submit" class="btn btn-secondary btn-sm" title="Make household head">
                                                <i class="fas fa-crown" style="color:var(--gold)"></i> Make head
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('residents.show', $resident) }}" class="btn btn-secondary btn-sm btn-icon" title="View profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5">
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