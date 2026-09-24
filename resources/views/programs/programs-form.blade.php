@extends('layouts.app')
@section('title', $program->exists ? 'Edit Program' : 'New Program')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $program->exists ? 'Edit Program' : 'New Assistance Program' }}</h1>
        <p class="page-subtitle">Set how often each household or resident may claim</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('programs.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<form method="POST" action="{{ $program->exists ? route('programs.update', $program) : route('programs.store') }}">
@csrf
@if($program->exists) @method('PUT') @endif

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-hand-holding-heart"></i> Program</span>
    </div>
    <div class="card-body">
        <div class="form-grid-2 mb-6">
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label" for="name">Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" id="name" maxlength="255" required
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $program->name) }}" placeholder="e.g. Relief Pack — Typhoon Kristine">
                @error('name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="progType">Type <span style="color:var(--crimson)">*</span></label>
                <select name="type" id="progType" class="form-control @error('type') is-invalid @enderror" required>
                    @foreach(\App\Models\AssistanceProgram::TYPES as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $program->type ?? 'relief') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Claims allowed
                    <span class="help-icon" data-tippy-content="Per household: one claim covers the whole household (usual for relief packs). Per resident: each person has their own entitlement (e.g. senior citizen aid).">?</span>
                </label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="number" name="max_claims" min="1" max="100" required style="width:80px"
                           class="form-control @error('max_claims') is-invalid @enderror"
                           value="{{ old('max_claims', $program->max_claims) }}">
                    <span class="td-muted">per</span>
                    <select name="claim_scope" id="progScope" class="form-control @error('claim_scope') is-invalid @enderror" style="flex:1">
                        <option value="household" @selected(old('claim_scope', $program->claim_scope) === 'household')>household</option>
                        <option value="resident"  @selected(old('claim_scope', $program->claim_scope) === 'resident')>resident</option>
                    </select>
                </div>
                @error('max_claims')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
                <span id="scopeHint" style="font-size:12px;color:var(--text-subtle);margin-top:4px"></span>
            </div>
            <div class="form-group">
                <label class="form-label" for="startsOn">Starts on</label>
                <input type="date" name="starts_on" id="startsOn" class="form-control @error('starts_on') is-invalid @enderror"
                       value="{{ old('starts_on', $program->starts_on?->format('Y-m-d')) }}">
                @error('starts_on')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label" for="endsOn">Ends on <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></label>
                <input type="date" name="ends_on" id="endsOn" class="form-control @error('ends_on') is-invalid @enderror"
                       value="{{ old('ends_on', $program->ends_on?->format('Y-m-d')) }}">
                @error('ends_on')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="grid-column:span 2">
                <label class="form-label" for="desc">Notes <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></label>
                <textarea name="description" id="desc" rows="2" maxlength="1000"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description', $program->description) }}</textarea>
            </div>
        </div>

        <label class="form-check">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $program->is_active))>
            <span><strong>Active</strong> — accepting claims during the period above</span>
        </label>
    </div>
</div>

@php
    // Rows to show: what was just submitted (after a validation error), else what's saved
    $supplyRows = old('supplies', $program->relationLoaded('supplies')
        ? $program->supplies->map(fn ($s) => ['relief_supply_id' => $s->id, 'quantity_per_claim' => $s->pivot->quantity_per_claim])->all()
        : []);
    $bdrrmUrl = route('committees.show', 'bdrrm').'#relief-supplies';
@endphp
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-boxes-stacked"></i> Each claim uses <span style="font-weight:400;color:var(--text-subtle)">(optional)</span></span>
    </div>
    <div class="card-body">
        <p class="td-muted" style="margin:0 0 14px">
            Link this program to the <a href="{{ $bdrrmUrl }}">BDRRM relief supplies</a> and each claim takes its items out of stock.
            A claim is refused once stock runs out, and voiding a claim puts its items back.
            Leave empty for programs that don't hand out stocked items (e.g. cash aid).
        </p>

        @if($supplies->isEmpty())
            <div style="display:flex;gap:8px;align-items:center;font-size:13px;padding:9px 12px;border-radius:var(--radius-sm);background:var(--surface2);color:var(--text-muted)">
                <i class="fas fa-circle-info"></i>
                <span>No relief supplies recorded yet. Add them under <a href="{{ $bdrrmUrl }}">Committees → BDRRM → Relief Supplies</a> first.</span>
            </div>
        @else
            <div id="supplyRows">
                @foreach($supplyRows as $i => $row)
                    @include('programs.partials.supply-row', ['i' => $i, 'row' => $row])
                @endforeach
            </div>
            @error('supplies')<span class="invalid-feedback" style="display:block"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            <button type="button" id="addSupplyRow" class="btn btn-secondary btn-sm" style="margin-top:4px">
                <i class="fas fa-plus"></i> Add supply item
            </button>
            <template id="supplyRowTemplate">
                @include('programs.partials.supply-row', ['i' => '__I__', 'row' => []])
            </template>
        @endif
    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary"><i class="fas fa-floppy-disk"></i> {{ $program->exists ? 'Save Changes' : 'Create Program' }}</button>
    <a href="{{ route('programs.index') }}" class="btn btn-secondary">Cancel</a>
</div>
</form>

@push('scripts')
<script>
(function () {
    const type = document.getElementById('progType');
    const scope = document.getElementById('progScope');
    const hint = document.getElementById('scopeHint');
    const isNew = {{ $program->exists ? 'false' : 'true' }};
    let scopeTouched = false;

    // New programs: relief is usually per household, everything else per resident
    type.addEventListener('change', () => {
        if (isNew && !scopeTouched) scope.value = type.value === 'relief' ? 'household' : 'resident';
        explain();
    });
    scope.addEventListener('change', () => { scopeTouched = true; explain(); });

    function explain() {
        hint.textContent = scope.value === 'household'
            ? 'One claim covers everyone in the household — other members are blocked once it is used.'
            : 'Each resident can claim on their own.';
    }
    explain();

    // "Each claim uses" rows
    const rows = document.getElementById('supplyRows');
    const tpl  = document.getElementById('supplyRowTemplate');
    if (rows && tpl) {
        let next = Date.now();   // unique row keys; the server keeps them so errors match rows
        document.getElementById('addSupplyRow').addEventListener('click', () => {
            rows.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__I__', next++));
            rows.lastElementChild.querySelector('select').focus();
        });
        rows.addEventListener('click', e => {
            const btn = e.target.closest('.remove-supply-row');
            if (btn) btn.closest('.supply-row').remove();
        });
    }
})();
</script>
@endpush

@endsection
