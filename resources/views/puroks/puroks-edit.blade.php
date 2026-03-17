@extends('layouts.app')

@section('title', 'Edit Purok')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit {{ $purok->name }}</h1>
        <p class="page-subtitle">Assign a leader and update purok details</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('puroks.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('puroks.update', $purok) }}">
@csrf @method('PUT')

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start">

    {{-- LEFT: Main Form --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-location-dot"></i> {{ $purok->name }}</span>
            <span style="font-size:12px;color:var(--text-muted)">{{ $purok->residents_count }} residents</span>
        </div>
        <div class="card-body">

            <div class="form-section-title">Purok Leader</div>
            <div class="form-group mb-6">
                <label class="form-label">Assign Leader</label>
                <select name="leader_id" class="form-control" id="leaderSelect">
                    <option value="">— No leader assigned —</option>
                    @foreach($residents as $resident)
                        <option value="{{ $resident->id }}"
                            {{ old('leader_id', $purok->leader_id) == $resident->id ? 'selected' : '' }}
                            data-contact="{{ $resident->contact_number }}"
                            data-address="{{ $resident->address }}">
                            {{ $resident->last_name }}, {{ $resident->first_name }}
                            {{ $resident->middle_name ? $resident->middle_name[0].'.' : '' }}
                            — {{ $resident->purok->name ?? '' }}
                        </option>
                    @endforeach
                </select>
                <div style="font-size:11px;color:var(--text-muted);margin-top:4px">
                    Only active residents of this purok are shown
                </div>
            </div>

            {{-- Leader preview card --}}
            <div id="leaderPreview" style="display:{{ $purok->leader ? 'block' : 'none' }};margin-bottom:20px">
                <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--navy-pale);border:1px solid rgba(13,33,68,0.15);border-radius:var(--radius)">
                    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:14px;flex-shrink:0" id="leaderAvatar">
                        {{ $purok->leader ? strtoupper(substr($purok->leader->first_name, 0, 1)) : '' }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13.5px" id="leaderName">
                            {{ $purok->leader?->full_name ?? '' }}
                        </div>
                        <div class="td-muted" id="leaderContact">
                            {{ $purok->leader?->contact_number ?? '' }}
                        </div>
                    </div>
                    <span class="badge badge-green" style="margin-left:auto">Selected</span>
                </div>
            </div>

            <div class="form-section-title">Purok Details</div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"
                    placeholder="Brief description of this purok (location, landmarks, etc.)">{{ old('description', $purok->description) }}</textarea>
            </div>

        </div>
    </div>

    {{-- RIGHT: Current Residents --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-users"></i> Residents</span>
            <span class="badge badge-navy">{{ $purok->residents_count }}</span>
        </div>
        <div style="max-height:420px;overflow-y:auto">
            @forelse($residents as $r)
            <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid var(--border)">
                <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:11px;flex-shrink:0">
                    {{ strtoupper(substr($r->first_name, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $r->full_name }}
                        @if($purok->leader_id == $r->id)
                            <span class="badge badge-gold" style="font-size:9px;margin-left:4px">Leader</span>
                        @endif
                    </div>
                    <div class="td-muted" style="font-size:11px">{{ $r->contact_number ?? '—' }}</div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No active residents in this purok</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

<div class="form-actions" style="margin-top:20px">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('puroks.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const select  = document.getElementById('leaderSelect');
    const preview = document.getElementById('leaderPreview');
    const avatar  = document.getElementById('leaderAvatar');
    const name    = document.getElementById('leaderName');
    const contact = document.getElementById('leaderContact');

    select.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (this.value) {
            const text = opt.text.split('—')[0].trim();
            avatar.textContent  = text.charAt(0).toUpperCase();
            name.textContent    = text;
            contact.textContent = opt.dataset.contact || 'No contact on file';
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
});
</script>
@endpush

@endsection
