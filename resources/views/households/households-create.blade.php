@extends('layouts.app')
@section('title', 'Add Household')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Add Household</h1>
        <p class="page-subtitle">Register a new household in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('households.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ route('households.store') }}">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household Information</span>
    </div>
    <div class="card-body">

        <div style="font-size:13px;color:var(--text-muted);background:var(--surface2);border:1px solid var(--border);
                    border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:18px">
            <i class="fas fa-circle-info" style="color:var(--navy)"></i>
            Households are usually created automatically when you register a resident. Add one here only to set it up
            before anyone is registered. Residents at this address will join it automatically; the head, family size and
            voter status are worked out from them.
        </div>

        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson)">*</span></label>
                <select name="purok_id" id="s2Purok" class="form-control @error('purok_id') is-invalid @enderror" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id') == $purok->id ? 'selected' : '' }}>{{ $purok->name }}</option>
                    @endforeach
                </select>
                @error('purok_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Address <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="address" id="hh_address" class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address') }}" placeholder="House No., Street" required>
                @error('address')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-house-circle-plus"></i> Add Household</button>
    <a href="{{ route('households.index') }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<script>
/* Purok — searchable Select2 */
$('#s2Purok').select2({
    dropdownParent: $('body'),
    width: '100%',
    placeholder: 'Select Purok',
    allowClear: true
});
</script>
@endpush

@endsection