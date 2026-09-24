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
                <div style="font-size:18px;font-weight:700;color:#fff;line-height:1.2">
                    {{ $resident->full_name }}
                </div>
                <div style="font-size:14px;color:rgba(255,255,255,0.7);margin-top:5px">
                    {{ $resident->purok->name ?? '—' }}
                </div>
                <div style="margin-top:12px">
                    <span class="badge {{ $resident->residency_badge }}">{{ $resident->residency_label }}</span>
                    @if($resident->residency_status !== 'Active' && $resident->status_effective_date)
                        <div style="font-size:12px;color:rgba(255,255,255,0.7);margin-top:6px">
                            since {{ $resident->status_effective_date->format('m/d/Y') }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Classifications --}}
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin-bottom:10px">
                    Classifications
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px">
                    @if($resident->is_voter)       <span class="badge badge-green">Voter</span>        @endif
                    @if($resident->is_senior)      <span class="badge badge-yellow">Senior Citizen</span> @endif
                    @if($resident->is_pwd)         <span class="badge badge-blue">PWD</span>           @endif
                    @if($resident->is_solo_parent) <span class="badge badge-orange">Solo Parent</span> @endif
                    @if($resident->is_4ps)         <span class="badge badge-gold">4Ps</span>           @endif
                    @if(!$resident->is_voter && !$resident->is_senior && !$resident->is_pwd && !$resident->is_solo_parent && !$resident->is_4ps)
                        <span style="font-size:13px;color:var(--text-subtle)">None</span>
                    @endif
                </div>
            </div>

            {{-- Quick stats --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--border)">
                <div style="padding:16px;text-align:center;border-right:1px solid var(--border)">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">{{ $resident->age ?? '—' }}</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px">Age</div>
                </div>
                <div style="padding:16px;text-align:center">
                    <div style="font-size:22px;font-weight:700;color:var(--navy)">{{ $resident->years_of_residency ?? '—' }}</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px">Yrs. Residency</div>
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
                @php
                    // Reversing a death record is a correction — Admins only (enforced server-side too)
                    $canChangeStatus = $resident->residency_status !== 'Deceased' || auth()->user()->isAdmin();
                @endphp
                @if($canChangeStatus)
                    <button type="button" class="btn btn-secondary" style="justify-content:flex-start" onclick="openStatusModal()">
                        <i class="fas fa-heart-pulse" style="color:#16a34a"></i>
                        Update Status
                    </button>
                @else
                    <span title="Only an Administrator can change the status of a resident recorded as deceased.">
                        <button type="button" class="btn btn-secondary" style="justify-content:flex-start;width:100%" disabled>
                            <i class="fas fa-lock" style="color:var(--text-subtle)"></i>
                            Update Status
                        </button>
                    </span>
                @endif
                <a href="{{ route('residents.edit', $resident) }}"
                   class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i>
                    Edit Profile
                </a>
                <form method="POST" action="{{ route('residents.destroy', $resident) }}"
                      data-confirm="Delete {{ $resident->full_name }}? This action cannot be undone."
                      data-confirm-title="Delete Resident"
                      data-confirm-ok="Delete Permanently">
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
                            <span style="background:var(--gold);color:#fff;border-radius:99px;padding:2px 8px;font-size:13px;margin-left:5px">
                                {{ $resident->documents->count() }}
                            </span>
                        @endif
                    </button>
                    <button class="tab-btn" onclick="switchTab(event,'tab-blotter')">
                        Blotter
                        @if($resident->blotterCases->count())
                            <span style="background:var(--crimson-mid);color:#fff;border-radius:99px;padding:2px 8px;font-size:13px;margin-left:5px">
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
                                ['label' => 'Date of Birth',   'value' => $resident->birthdate?->format('m/d/Y')],
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
                                ['label' => 'Registered',      'value' => $resident->created_at->format('m/d/Y')],
                                ['label' => 'Last Updated',     'value' => $resident->updated_at->format('m/d/Y')],
                            ];
                        @endphp
                        @foreach($details as $detail)
                        <div style="padding:12px 0;border-bottom:1px solid var(--border);{{ $loop->iteration % 2 === 0 ? 'padding-left:24px' : '' }}">
                            <div style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-subtle);margin-bottom:3px">
                                {{ $detail['label'] }}
                            </div>
                            <div style="font-size:15px;color:var(--text);font-weight:500;line-height:1.4">
                                {{ $detail['value'] }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Status history (Task 1.4) --}}
                    @if($resident->statusLogs->isNotEmpty())
                    <div style="margin-top:24px">
                        <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-muted);margin-bottom:6px">
                            <i class="fas fa-clock-rotate-left"></i> Status History
                        </div>
                        @foreach($resident->statusLogs as $log)
                        <div style="display:flex;gap:14px;padding:11px 0;border-bottom:1px solid var(--border)">
                            <div style="flex-shrink:0;width:88px;font-size:13px;font-weight:600;color:var(--navy);padding-top:2px">
                                {{ $log->effective_date->format('m/d/Y') }}
                            </div>
                            <div style="flex:1;min-width:0">
                                <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                                    <span class="badge {{ \App\Enums\ResidencyStatus::badgeFor($log->from_status) }}">{{ $log->from_label }}</span>
                                    <i class="fas fa-arrow-right" style="font-size:11px;color:var(--text-subtle)"></i>
                                    <span class="badge {{ \App\Enums\ResidencyStatus::badgeFor($log->to_status) }}">{{ $log->to_label }}</span>
                                </div>
                                @if($log->moved_to)
                                    <div style="font-size:13px;color:var(--text);margin-top:5px">Moved to: {{ $log->moved_to }}</div>
                                @endif
                                @if($log->remarks)
                                    <div style="font-size:13px;color:var(--text-muted);margin-top:3px">{{ $log->remarks }}</div>
                                @endif
                                <div style="font-size:12px;color:var(--text-subtle);margin-top:5px">
                                    Recorded by {{ $log->changedBy->name ?? 'a removed user' }} · {{ $log->created_at->format('m/d/Y g:i A') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Documents Tab --}}
                <div class="tab-pane" id="tab-documents">
                    @forelse($resident->documents as $doc)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div class="td-mono">{{ $doc->doc_number }}</div>
                            <div style="font-weight:600;font-size:14px;margin-top:2px">{{ $doc->document_type }}</div>
                            <div class="td-muted" style="margin-top:2px">{{ $doc->created_at->format('m/d/Y') }}</div>
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
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-bottom:1px solid var(--border)">
                        <div>
                            <div class="td-mono">{{ $case->case_number }}</div>
                            <div style="font-weight:600;font-size:14px;margin-top:2px">{{ $case->incident_type }}</div>
                            <div class="td-muted" style="margin-top:2px">{{ $case->incident_date?->format('m/d/Y') }}</div>
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

{{-- ── Update Status dialog (Task 1.4) ─────────────────────────────────── --}}
@if($canChangeStatus)
@php $statusErrors = $errors->hasAny(['to_status', 'effective_date', 'moved_to', 'remarks']); @endphp
<div id="statusModal" role="dialog" aria-modal="true" aria-labelledby="statusModalTitle"
     style="display:{{ $statusErrors ? 'flex' : 'none' }};position:fixed;inset:0;background:rgba(0,0,0,0.45);
            z-index:9980;align-items:center;justify-content:center;backdrop-filter:blur(2px)"
     onclick="if (event.target === this) closeStatusModal()">
    <form method="POST" action="{{ route('residents.status.update', $resident) }}" id="statusForm"
          style="background:var(--surface);border-radius:var(--radius-lg);padding:26px 26px 22px;
                 max-width:460px;width:92%;max-height:92vh;overflow-y:auto;
                 box-shadow:0 20px 60px rgba(0,0,0,0.22);border:1px solid var(--border)">
        @csrf
        @method('PATCH')

        <div id="statusModalTitle" style="font-size:16px;font-weight:700;color:var(--text)">Update Status</div>
        <div style="font-size:13px;color:var(--text-muted);margin:4px 0 18px">
            {{ $resident->full_name }} is currently <strong>{{ $resident->residency_label }}</strong>.
        </div>

        <div class="form-group mb-4">
            <label class="form-label">New status</label>
            <div class="status-options">
                @foreach(\App\Enums\ResidencyStatus::cases() as $case)
                    @php $isCurrent = $case->value === $resident->residency_status; @endphp
                    <label class="status-option {{ $isCurrent ? 'is-current' : '' }}">
                        <input type="radio" name="to_status" value="{{ $case->value }}"
                               @checked(old('to_status') === $case->value) @disabled($isCurrent)>
                        <span class="badge {{ $case->badgeClass() }}">{{ $case->label() }}</span>
                        <span class="status-option-fil">{{ $case->filipino() }}</span>
                        @if($isCurrent)<span class="status-option-current">current</span>@endif
                    </label>
                @endforeach
            </div>
            @error('to_status')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="statusEffectiveDate" id="statusDateLabel">Date</label>
            <input type="date" name="effective_date" id="statusEffectiveDate"
                   class="form-control @error('effective_date') is-invalid @enderror"
                   value="{{ old('effective_date', now()->toDateString()) }}"
                   min="{{ $resident->birthdate->toDateString() }}" max="{{ now()->toDateString() }}" required>
            @error('effective_date')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
        </div>

        <div class="form-group mb-4" id="statusMovedToGroup" style="display:none">
            <label class="form-label" for="statusMovedTo">Moved to <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="moved_to" id="statusMovedTo" maxlength="255"
                   class="form-control @error('moved_to') is-invalid @enderror"
                   value="{{ old('moved_to') }}" placeholder="e.g. Brgy. Bagong Silangan, Quezon City">
            @error('moved_to')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
        </div>

        <div class="form-group mb-4">
            <label class="form-label" for="statusRemarks">Remarks <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></label>
            <textarea name="remarks" id="statusRemarks" rows="2" maxlength="1000"
                      class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks') }}</textarea>
            @error('remarks')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
        </div>

        <div id="statusDeceasedNote"
             style="display:none;font-size:13px;color:#854d0e;background:#fef9c3;border:1px solid #fde047;
                    border-radius:var(--radius-sm);padding:9px 12px;margin-bottom:14px">
            <i class="fas fa-triangle-exclamation"></i>
            Once recorded, only an Administrator can undo a death record.
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px">
            <button type="button" class="btn btn-secondary" onclick="closeStatusModal()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="statusSubmitBtn" disabled>
                <i class="fas fa-check"></i> <span>Save Status</span>
            </button>
        </div>
    </form>
</div>
@endif

@endsection

@push('styles')
<style>
    .status-options { display:flex; flex-direction:column; gap:6px; }
    .status-option  { display:flex; align-items:center; gap:10px; padding:9px 12px; cursor:pointer;
                      border:1px solid var(--border); border-radius:var(--radius-sm); transition:border-color .15s, background .15s; }
    .status-option:hover        { border-color:var(--navy); }
    .status-option.is-selected  { border-color:var(--navy); background:var(--navy-pale); }
    .status-option.is-current   { opacity:.55; cursor:not-allowed; }
    .status-option.is-current:hover { border-color:var(--border); }
    .status-option-fil     { font-size:13px; color:var(--text-muted); }
    .status-option-current { margin-left:auto; font-size:11px; font-weight:600; text-transform:uppercase;
                             letter-spacing:.06em; color:var(--text-subtle); }
</style>
@endpush

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

    /* ── Update Status dialog ─────────────────────────────────────────── */
    (function () {
        const modal = document.getElementById('statusModal');
        if (!modal) return;   // not rendered: resident is Deceased and user is not an Admin

        const DATE_LABELS = {
            Active:      'Date returned or corrected',
            Deceased:    'Date of death',
            Transferred: 'Date moved out',
        };
        const radios  = modal.querySelectorAll('input[name="to_status"]');
        const movedTo = document.getElementById('statusMovedTo');
        const submit  = document.getElementById('statusSubmitBtn');

        function sync() {
            const checked = modal.querySelector('input[name="to_status"]:checked');
            const value   = checked ? checked.value : null;

            radios.forEach(r => r.closest('.status-option').classList.toggle('is-selected', r.checked));
            document.getElementById('statusMovedToGroup').style.display = value === 'Transferred' ? '' : 'none';
            movedTo.required = value === 'Transferred';
            document.getElementById('statusDeceasedNote').style.display = value === 'Deceased' ? '' : 'none';
            document.getElementById('statusDateLabel').textContent = value ? DATE_LABELS[value] : 'Date';
            submit.disabled = !value;
        }

        window.openStatusModal  = function () { modal.style.display = 'flex'; sync(); };
        window.closeStatusModal = function () { modal.style.display = 'none'; };

        radios.forEach(r => r.addEventListener('change', sync));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeStatusModal(); });

        // Prevent a double submit while the request is in flight
        document.getElementById('statusForm').addEventListener('submit', function () {
            submit.disabled = true;
            submit.querySelector('i').className = 'fas fa-spinner fa-spin';
            submit.querySelector('span').textContent = 'Saving…';
        });

        sync();   // restores state when the dialog re-opens after a validation error
    })();
</script>
@endpush
