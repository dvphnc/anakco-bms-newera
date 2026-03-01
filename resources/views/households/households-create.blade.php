@extends('layouts.app')

@section('title', 'Add Household')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Add Household</h1>
        <p class="page-subtitle">Register a new household in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('households.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('households.store') }}">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Details</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Household Head <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="household_head" class="form-control"
                       value="{{ old('household_head') }}"
                       placeholder="Full name of household head" required>
            </div>
            <div class="form-group">
                <label class="form-label">Purok <span style="color:var(--crimson)">*</span></label>
                <select name="purok_id" class="form-control" required>
                    <option value="">Select Purok</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" {{ old('purok_id') == $purok->id ? 'selected' : '' }}>
                            {{ $purok->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="Active"   {{ old('status','Active') === 'Active'   ? 'selected' : '' }}>Active</option>
                    <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="form-group mb-6">
            <label class="form-label">Address <span style="color:var(--crimson)">*</span></label>
            <input type="text" name="address" class="form-control"
                   value="{{ old('address') }}"
                   placeholder="Full address" required>
        </div>

        <div class="form-section-title">Housing Information</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Housing Type</label>
                <select name="housing_type" class="form-control">
                    <option value="">Select Type</option>
                    @foreach(['Owned','Rented','Shared','Informal Settler'] as $t)
                        <option value="{{ $t }}" {{ old('housing_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Structure Type</label>
                <select name="structure_type" class="form-control">
                    <option value="">Select Type</option>
                    @foreach(['Concrete','Semi-Concrete','Wood','Light Materials'] as $t)
                        <option value="{{ $t }}" {{ old('structure_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Monthly Income (approx.)</label>
                <input type="number" name="monthly_income" class="form-control"
                       value="{{ old('monthly_income') }}" placeholder="0.00" min="0" step="0.01">
            </div>
        </div>

        <div class="form-section-title">Utilities</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px mb-6">
            <label class="form-check">
                <input type="checkbox" name="has_electricity" value="1" {{ old('has_electricity') ? 'checked' : '' }}>
                <span>Electricity</span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="has_water" value="1" {{ old('has_water') ? 'checked' : '' }}>
                <span>Water Supply</span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="has_internet" value="1" {{ old('has_internet') ? 'checked' : '' }}>
                <span>Internet</span>
            </label>
            <label class="form-check">
                <input type="checkbox" name="is_4ps_beneficiary" value="1" {{ old('is_4ps_beneficiary') ? 'checked' : '' }}>
                <span>4Ps Beneficiary</span>
            </label>
        </div>

        <div class="form-group mt-6">
            <label class="form-label">Notes / Remarks</label>
            <textarea name="notes" class="form-control" rows="3"
                      placeholder="Optional notes about this household...">{{ old('notes') }}</textarea>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-house-circle-plus"></i> Add Household
    </button>
    <a href="{{ route('households.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
