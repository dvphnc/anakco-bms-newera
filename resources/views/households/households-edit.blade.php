@extends('layouts.app')

@section('title', 'Edit Household')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Household</h1>
        <p class="page-subtitle">{{ $household->household_number }} — {{ $household->household_head }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('households.show', $household) }}" class="btn btn-secondary">
            <i class="fas fa-eye"></i> View
        </a>
        <a href="{{ route('households.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
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

        <div class="form-section-title">Basic Details</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Household Head <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="household_head" class="form-control"
                       value="{{ old('household_head', $household->household_head) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson)">*</span></label>
                <select name="purok_id" class="form-control" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id', $household->purok_id) == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    @foreach(['Active','Inactive'] as $s)
                        <option value="{{ $s }}" {{ old('status', $household->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Address <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="address" class="form-control"
                   value="{{ old('address', $household->address) }}" required>
        </div>

        <div class="form-section-title">Housing Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Housing Type</label>
                <select name="housing_type" class="form-control">
                    <option value="">Select Type</option>
                    @foreach(['Owned','Rented','Shared','Informal Settler'] as $t)
                        <option value="{{ $t }}" {{ old('housing_type', $household->housing_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Structure Type</label>
                <select name="structure_type" class="form-control">
                    <option value="">Select Type</option>
                    @foreach(['Concrete','Semi-Concrete','Wood','Light Materials'] as $t)
                        <option value="{{ $t }}" {{ old('structure_type', $household->structure_type) === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Monthly Income (approx.)</label>
                <input type="number" name="monthly_income" class="form-control"
                       value="{{ old('monthly_income', $household->monthly_income) }}" min="0" step="0.01">
            </div>
        </div>

        <div class="form-section-title">Utilities</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
            <label class="form-check">
                <input type="checkbox" name="has_electricity" value="1" {{ old('has_electricity', $household->has_electricity) ? 'checked' : '' }}>
                <span>Electricity</span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="has_water" value="1" {{ old('has_water', $household->has_water) ? 'checked' : '' }}>
                <span>Water Supply</span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="has_internet" value="1" {{ old('has_internet', $household->has_internet) ? 'checked' : '' }}>
                <span>Internet</span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_4ps_beneficiary" value="1" {{ old('is_4ps_beneficiary', $household->is_4ps_beneficiary) ? 'checked' : '' }}>
                <span>4Ps Beneficiary</span>
            </label>
        </div>

        <div class="form-group">
            <label class="form-label">Notes / Remarks</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $household->notes) }}</textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('households.show', $household) }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
