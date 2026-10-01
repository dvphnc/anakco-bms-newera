@extends('layouts.app')
@section('title', $pabahay->exists ? 'Edit Pabahay' : 'New Pabahay')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $pabahay->exists ? 'Edit Pabahay' : 'New Pabahay' }}</h1>
        <p class="page-subtitle">A housing block for ministers' families. You add its units next.</p>
    </div>
    <div class="page-actions">
        <a href="{{ $pabahay->exists ? route('pabahays.show', $pabahay) : route('pabahays.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ $pabahay->exists ? route('pabahays.update', $pabahay) : route('pabahays.store') }}">
@csrf
@if($pabahay->exists) @method('PUT') @endif

<div class="card mb-6">
    <div class="card-header"><span class="card-title"><i class="fas fa-house-chimney"></i> Pabahay details</span></div>
    <div class="card-body">
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label" for="name">Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" id="name" maxlength="100" required class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $pabahay->name) }}" placeholder="e.g. Pabahay A">
                @error('name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="purok_id">Purok <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></label>
                <select name="purok_id" id="purok_id" class="form-control @error('purok_id') is-invalid @enderror">
                    <option value="">Not set</option>
                    @foreach($puroks as $purok)
                        <option value="{{ $purok->id }}" @selected((string) old('purok_id', $pabahay->purok_id) === (string) $purok->id)>{{ $purok->name }}</option>
                    @endforeach
                </select>
                @error('purok_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label" for="location">Location <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></label>
                <input type="text" name="location" id="location" maxlength="255" class="form-control @error('location') is-invalid @enderror"
                       value="{{ old('location', $pabahay->location) }}" placeholder="Street or landmark">
                @error('location')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label" for="notes">Notes <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></label>
                <textarea name="notes" id="notes" rows="2" maxlength="1000" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $pabahay->notes) }}</textarea>
                @error('notes')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        @if($pabahay->exists)
            <label class="form-check">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $pabahay->is_active))>
                <span><strong>Active</strong>. Turn off to hide it from the resident form (only possible when nobody lives there).</span>
            </label>
        @endif
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> {{ $pabahay->exists ? 'Save Changes' : 'Create Pabahay' }}</button>
    <a href="{{ $pabahay->exists ? route('pabahays.show', $pabahay) : route('pabahays.index') }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@endsection
