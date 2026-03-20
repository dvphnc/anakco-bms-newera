@extends('layouts.app')

@section('title', $resident->full_name)
@section('page-title', 'Residents')
@section('page-subtitle', 'Resident profile')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Resident Profile</h1>
        <p class="page-subtitle">Viewing record of {{ $resident->full_name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('residents.edit', $resident) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('residents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="grid-2" style="grid-template-columns:300px 1fr;align-items:start">

    {{-- LEFT: Profile card --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Photo + Name card --}}
        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:28px 20px;text-align:center">
                <div style="width:90px;height:90px;border-radius:50%;margin:0 auto 14px;overflow:hidden;border:3px solid rgba(255,255,255,0.2);background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center">
                    @if($resident->photo_path)
                        <img src="{{ asset('storage/'.$resident->photo_path) }}"
                             style="width:100%;height:100%;object-fit:cover">
                    @else
                        <span style="font-size:32px;font-weight:700;color:#fff">
                            {{ strtoupper(substr($resident->first_name,0,1)) }}
                        </span>
                    @endif
                </div>
                <div style="font-size:17px;font-weight:700;color:#fff;line-height:1.2">
                    {{ $resident->full_name }}
                </div>
                <div style="font-size:12px;color:rgba(255,255,255,0.6);margin-top:4px">
                    {{ $resident->purok->name ?? '—' }}
                </div>
                <div style="margin-top:12px">
                    @php
                        $cls = match($resident->residency_status) {
                            'Active'      => 'badge-green',
                            'Deceased'    => 'badge-gray',
                            'Transferred' => 'badge-yellow',
                            default       => 'badge-gray'
                        };
                    @endphp
                    <span class="badge {{ $cls }}">{{ $resident->residency_status }}</span>
                </div>
            </div>

            {{-- Classifications --}}
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--text-muted);margin-bottom:10px">
                    Classifications
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px">
                    @if($resident->is_voter)       <span class="badge badge-green">Voter</span>        @endif
                    @if($resident->is_senior)      <span class="badge badge-yellow">Senior Citizen</span> @endif
                    @if($resident->is_pwd)         <span class="badge badge-blue">PWD</span>           @endif
                    @if($resident->is_solo_parent) <span class="badge badge-orange">Solo Parent</span> @endif
                    @if($resident->is_4ps)         <span class="badge badge-gold">4Ps</span>           @endif
                    @if(!$resident->is_voter && !$resident->is_senior && !$resident->is_pwd && !$resident->is_solo_parent && !$resident->is_4ps)
                        <span style="font-size:12px;color:var(--text-subtle)">None</span>
                    @endif
                </div>
            </div>

            {{-- Quick stats --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)">
                <div style="padding:14px 16px;text-align:center;border-right:1px solid var(--border)">
                    <div style="font-size:20px;font-weight:700;color:var(--navy)">{{ $resident->age ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">Age</div>
                </div>
                <div style="padding:14px 16px;text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--navy)">{{ $resident->years_of_residency ?? '—' }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">Yrs. Residency</div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Quick Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('documents.create', ['resident_id' => $resident->id]) }}"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-file-circle-plus" style="color:var(--gold)"></i>
                    Issue Document
                </a>
                <a href="{{ route('blotter.create', ['complainant_resident_id' => $resident->id]) }}"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-gavel" style="color:var(--crimson-mid)"></i>
                    File Blotter Case
                </a>
                <a href="{{ route('residents.edit', $resident) }}"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i>
                    Edit Profile
                </a>
                <form method="POST" action="{{ route('residents.destroy', $resident) }}"
                      onsubmit="return confirm('Delete this resident?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete Record
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- RIGHT: Details tabs --}}
    <div>
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-id-card"></i> Resident Details</span>
                <span class="td-mono">ID #{{ str_pad($resident->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="card-body">

                {{-- Tabs --}}
                <div class="tab-nav" id="profileTabs">
                    <button class="tab-btn active" onclick="switchTab(event,'tab-personal')">Personal</button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-documents')">
                        Documents
                        @if($resident->documents->count())
                            <span style="background:var(--gold);color:#fff;border-radius:99px;padding:1px 7px;font-size:10px;margin-left:4px">
                                {{ $resident->documents->count() }}
                            </span>
                        @endif
                    </button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-blotter')">
                        Blotter
                        @if($resident->blotterCases->count())
                            <span style="background:var(--crimson-mid);color:#fff;border-radius:99px;padding:1px 7px;font-size:10px;margin-left:4px">
                                {{ $resident->blotterCases->count() }}
                            </span>
                        @endif
                    </button>
                </div>

                {{-- Personal Tab --}}
                <div class="tab-pane active" id="tab-personal">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                        @php
                            $details = [
                                ['label' => 'Last Name',       'value' => $resident->last_name],
                                ['label' => 'First Name',      'value' => $resident->first_name],
                                ['label' => 'Middle Name',     'value' => $resident->middle_name ?: '—'],
                                ['label' => 'Date of Birth',   'value' => $resident->birthdate?->format('F d, Y')],
                                ['label' => 'Age',             'value' => $resident->age ? $resident->age . ' years old' : '—'],
                                ['label' => 'Gender',          'value' => $resident->gender],
                                ['label' => 'Civil Status',    'value' => $resident->civil_status],
                                ['label' => 'Nationality',     'value' => $resident->nationality ?: '—'],
                                ['label' => 'Religion',        'value' => $resident->religion ?: '—'],
                                ['label' => 'Occupation',      'value' => $resident->occupation ?: '—'],
                                ['label' => 'Contact Number',  'value' => $resident->contact_number ?: '—'],
                                ['label' => 'Purok',           'value' => $resident->purok->name ?? '—'],
                                ['label' => 'Household',       'value' => $resident->household?->household_number . ' — ' . ($resident->household?->household_head) ?: '—'],
                                ['label' => 'Address',         'value' => $resident->address],
                                ['label' => 'Yrs of Residency','value' => $resident->years_of_residency ? $resident->years_of_residency . ' years' : '—'],
                                ['label' => 'Registered',      'value' => $resident->created_at->format('F d, Y')],
                            ];
                        @endphp
                        @foreach($details as $detail)
                        <div style="padding:10px 0;border-bottom:1px solid var(--border);{{ $loop->iteration % 2 === 0 ? 'padding-left:24px' : '' }}">
                            <div style="font-size:10.5px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">
                                {{ $detail['label'] }}
                            </div>
                            <div style="font-size:13.5px;color:var(--text);font-weight:500">
                                {{ $detail['value'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Documents Tab --}}
                <div class="tab-pane" id="tab-documents">
                    @forelse($resident->documents as $doc)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div class="td-mono">{{ $doc->doc_number }}</div>
                            <div style="font-weight:600;font-size:13px">{{ $doc->document_type }}</div>
                            <div class="td-muted">{{ $doc->created_at->format('M d, Y') }}</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            @php
                                $cls = match($doc->status) {
                                    'Released'   => 'badge-green',
                                    'Processing' => 'badge-blue',
                                    'Cancelled'  => 'badge-gray',
                                    default      => 'badge-yellow'
                                };
                            @endphp
                            <span class="badge {{ $cls }}">{{ $doc->status }}</span>
                            <a href="{{ route('documents.show', $doc) }}" class="btn btn-secondary btn-sm btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-file-certificate"></i>
                        <p>No documents issued yet.</p>
                    </div>
                    @endforelse
                </div>

                {{-- Blotter Tab --}}
                <div class="tab-pane" id="tab-blotter">
                    @forelse($resident->blotterCases as $case)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div class="td-mono">{{ $case->case_number }}</div>
                            <div style="font-weight:600;font-size:13px">{{ $case->incident_type }}</div>
                            <div class="td-muted">{{ $case->incident_date?->format('M d, Y') }}</div>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px">
                            <span class="badge {{ $case->status_badge }}">{{ $case->status }}</span>
                            <a href="{{ route('blotter.show', $case) }}" class="btn btn-secondary btn-sm btn-icon">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-gavel"></i>
                        <p>No blotter cases on record.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

</div>


@endsection

@push('scripts')
<script>
    function switchTab(e, tabId) {
        // Remove active from all buttons and panes
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        // Activate clicked
        e.currentTarget.classList.add('active');
        document.getElementById(tabId).classList.add('active');
    }
</script>
@endpush
