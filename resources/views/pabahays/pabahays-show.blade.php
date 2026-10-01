@extends('layouts.app')
@section('title', $pabahay->name)
@section('content')

@php
    $people = $units->sum(fn ($u) => $u->residents->count());
    $occupied = $units->filter(fn ($u) => $u->residents->isNotEmpty())->count();
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $pabahay->name }}
            @unless($pabahay->is_active)<span class="badge badge-gray" style="font-size:12px;vertical-align:middle">Off</span>@endunless
        </h1>
        <p class="page-subtitle">
            {{ $pabahay->location ?: 'No location set' }}@if($pabahay->purok) · {{ $pabahay->purok->name }}@endif
        </p>
    </div>
    <div class="page-actions">
        <a href="{{ route('pabahays.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> All Pabahay</a>
        <a href="{{ route('pabahays.edit', $pabahay) }}" class="btn btn-primary"><i class="fas fa-pen"></i> Edit</a>
    </div>
</div>

<div class="grid-3 mb-6">
    @foreach([['Units', $units->count(), 'fa-door-open'], ['Occupied units', $occupied.' of '.$units->count(), 'fa-house-user'], ['Living residents', $people, 'fa-users']] as [$label, $value, $icon])
        <div class="card" style="padding:16px 20px;display:flex;align-items:center;gap:14px">
            <i class="fas {{ $icon }}" style="font-size:20px;color:var(--gold);width:26px;text-align:center"></i>
            <div>
                <div style="font-size:22px;font-weight:700;color:var(--navy);line-height:1.1">{{ $value }}</div>
                <div class="td-muted" style="font-size:12px">{{ $label }}</div>
            </div>
        </div>
    @endforeach
</div>

@if($pabahay->notes)
    <p class="td-muted mb-6" style="margin-top:-8px">{{ $pabahay->notes }}</p>
@endif

<div class="card mb-6">
    <div class="card-header"><span class="card-title"><i class="fas fa-plus"></i> Add units</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('pabahays.units.store', $pabahay) }}" style="display:flex;gap:10px;align-items:flex-start;flex-wrap:wrap">
            @csrf
            <div style="flex:1;min-width:260px">
                <input type="text" name="unit_numbers" maxlength="1000" required class="form-control @error('unit_numbers') is-invalid @enderror"
                       value="{{ old('unit_numbers') }}" placeholder="One unit (A-1) or several separated by commas (A-1, A-2, A-3)" aria-label="Unit numbers">
                @error('unit_numbers')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-door-open"></i> Units</span>
        <span class="td-muted">Families are assigned to a unit from the resident's edit form</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Who lives here</th>
                    <th>Note</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($units as $unit)
                <tr style="{{ $unit->is_active ? '' : 'opacity:.6' }}">
                    <td colspan="3" style="padding:0">
                        {{-- Rename / note form wraps the first three columns so Save sits with them --}}
                        <form method="POST" action="{{ route('pabahay-units.update', $unit) }}" style="display:grid;grid-template-columns:150px 1fr 220px auto;gap:12px;align-items:center;padding:10px 16px">
                            @csrf @method('PATCH')
                            <input type="text" name="unit_no" value="{{ $unit->unit_no }}" maxlength="30" required class="form-control" aria-label="Unit number">
                            <div>
                                @forelse($unit->residents as $r)
                                    <a href="{{ route('residents.show', $r) }}" class="badge badge-navy" style="margin:0 4px 4px 0;text-decoration:none">{{ $r->last_name }}, {{ $r->first_name }}</a>
                                @empty
                                    <span class="td-muted">Vacant</span>
                                @endforelse
                                @if($unit->residents->isNotEmpty())
                                    <a href="{{ route('residents.index', ['religion_group' => 'inc', 'fom' => 1, 'pabahay_unit' => $unit->id]) }}" class="td-muted" style="font-size:12px;white-space:nowrap">View in list <i class="fas fa-arrow-right"></i></a>
                                @endif
                            </div>
                            <input type="text" name="notes" value="{{ $unit->notes }}" maxlength="255" class="form-control" placeholder="Note (optional)" aria-label="Note">
                            <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-check"></i> Save</button>
                        </form>
                    </td>
                    <td><span class="badge {{ $unit->is_active ? 'badge-green' : 'badge-gray' }}">{{ $unit->is_active ? 'Active' : 'Off' }}</span></td>
                    <td style="text-align:right">
                        <form method="POST" action="{{ route('pabahay-units.update', $unit) }}" style="display:inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="is_active" value="{{ $unit->is_active ? 0 : 1 }}">
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fas {{ $unit->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i> {{ $unit->is_active ? 'Turn off' : 'Turn on' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state" style="padding:32px">
                            <i class="fas fa-door-open"></i>
                            <p>No units yet. Add them above, for example “A-1, A-2, A-3”.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
