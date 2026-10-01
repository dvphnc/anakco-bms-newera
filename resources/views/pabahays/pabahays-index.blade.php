@extends('layouts.app')
@section('title', 'Pabahay')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Pabahay</h1>
        <p class="page-subtitle">Housing blocks for ministers' families, and who lives in each unit. Only the Admin can see this.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('religions.index') }}" class="btn btn-secondary"><i class="fas fa-church"></i> Religions</a>
        <a href="{{ route('pabahays.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Pabahay</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house-chimney"></i> Pabahay blocks</span>
        <span class="td-muted">Assign a family to a unit from the resident's edit form</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Pabahay</th>
                    <th>Location</th>
                    <th style="text-align:right">Units</th>
                    <th style="text-align:right">Occupied</th>
                    <th style="text-align:right">Living residents</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($pabahays as $pabahay)
                @php $o = $occupancy[$pabahay->id] ?? null; @endphp
                <tr style="{{ $pabahay->is_active ? '' : 'opacity:.6' }}">
                    <td>
                        <a href="{{ route('pabahays.show', $pabahay) }}" style="font-weight:600;color:var(--navy)">{{ $pabahay->name }}</a>
                    </td>
                    <td class="td-muted">
                        {{ $pabahay->location ?: '—' }}
                        @if($pabahay->purok)<div style="font-size:12px">{{ $pabahay->purok->name }}</div>@endif
                    </td>
                    <td style="text-align:right">{{ $pabahay->units_count }}</td>
                    <td style="text-align:right">{{ $o->occupied ?? 0 }} <span class="td-muted">of {{ $pabahay->units_count }}</span></td>
                    <td style="text-align:right;font-weight:600">{{ number_format($o->people ?? 0) }}</td>
                    <td><span class="badge {{ $pabahay->is_active ? 'badge-green' : 'badge-gray' }}">{{ $pabahay->is_active ? 'Active' : 'Off' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('pabahays.show', $pabahay) }}" class="btn btn-secondary btn-sm"><i class="fas fa-door-open"></i> Units</a>
                            <a href="{{ route('pabahays.edit', $pabahay) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state" style="padding:32px">
                            <i class="fas fa-house-chimney"></i>
                            <p>No Pabahay yet. Add one, for example “Pabahay A”, then add its units.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
