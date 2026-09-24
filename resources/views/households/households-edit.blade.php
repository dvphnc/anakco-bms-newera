@extends('layouts.app')
@section('title', 'Edit Household')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Household</h1>
        <p class="page-subtitle">{{ $household->household_number }} — {{ $household->household_head ?? 'no members yet' }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('households.show', $household) }}" class="btn btn-secondary"><i class="fas fa-eye"></i> View</a>
        <a href="{{ route('households.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ route('households.update', $household) }}">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household Information</span>
        <span class="td-mono">{{ $household->household_number }}</span>
    </div>
    <div class="card-body">

        {{-- Worked out from the members (Task 1.2) — shown for reference, not editable --}}
        <div class="form-section-title">From the members</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">
                    Household Head
                    <span class="help-icon" data-tippy-content="Change the head from the household's page (Make head), or by setting a member's relationship to 'Head'.">?</span>
                </label>
                <div class="form-control" style="background:var(--surface2)">{{ $household->household_head ?? '—' }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Living Members</label>
                <div class="form-control" style="background:var(--surface2)">{{ $household->family_size }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">Voter Household</label>
                <div class="form-control" style="background:var(--surface2)">{{ $household->is_voter_household ? 'Yes' : 'No' }}</div>
            </div>
        </div>

        <div class="form-section-title">Location</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson)">*</span></label>
                <select name="purok_id" id="s2Purok" class="form-control @error('purok_id') is-invalid @enderror" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id', $household->purok_id) == $purok->id ? 'selected' : '' }}>{{ $purok->name }}</option>
                    @endforeach
                </select>
                @error('purok_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="address" id="hh_address" class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address', $household->address) }}" required>
                @error('address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> Save Changes</button>
    <a href="{{ route('households.show', $household) }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<script>
/* Purok — searchable Select2 */
$('#s2Purok').select2({
    dropdownParent: $('body'),
    width: '100%',
    placeholder: 'Select Purok',
    allowClear: false
});
</script>
@endpush

@endsection
