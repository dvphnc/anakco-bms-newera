@extends('layouts.app')
@section('title', 'Religions')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Religions</h1>
        <p class="page-subtitle">The list staff choose from on the resident form. Only the Admin can see this.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('pabahays.index') }}" class="btn btn-secondary"><i class="fas fa-house-chimney"></i> Pabahay</a>
    </div>
</div>

{{-- Living residents by group. Each card opens the Residents list with that filter. --}}
<div class="grid-3 mb-6">
    @foreach([
        ['inc',        'INC',          'Iglesia ni Cristo members',        'var(--navy)',   'fa-church'],
        ['non_inc',    'Non-INC',      'Other religions',                  'var(--gold)',   'fa-users'],
        ['unrecorded', 'Not recorded', 'No religion filled in yet',        '#78716C',       'fa-circle-question'],
    ] as [$group, $label, $caption, $color, $icon])
        <a href="{{ route('residents.index', ['religion_group' => $group]) }}" class="card" style="padding:18px 20px;display:flex;align-items:center;gap:16px;text-decoration:none;border-left:4px solid {{ $color }}">
            <i class="fas {{ $icon }}" style="font-size:22px;color:{{ $color }};width:28px;text-align:center"></i>
            <div>
                <div style="font-size:26px;font-weight:700;color:var(--navy);line-height:1.1">{{ number_format($summary[$group]) }}</div>
                <div style="font-weight:600;font-size:13px;color:var(--text)">{{ $label }} <span class="td-muted" style="font-weight:400">· living residents</span></div>
                <div class="td-muted" style="font-size:12px">{{ $caption }}</div>
            </div>
        </a>
    @endforeach
</div>

<div class="card mb-6">
    <div class="card-header"><span class="card-title"><i class="fas fa-plus"></i> Add a religion</span></div>
    <div class="card-body">
        <form method="POST" action="{{ route('religions.store') }}" style="display:flex;gap:10px;align-items:flex-start;flex-wrap:wrap">
            @csrf
            <div style="flex:1;min-width:240px">
                <input type="text" name="name" maxlength="100" required class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="e.g. Members Church of God International" aria-label="Religion name">
                @error('name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Add</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-list"></i> Religion list</span>
        <span class="td-muted">Turning one off only hides it from the form. Residents who already have it keep it.</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Religion</th>
                    <th>Group</th>
                    <th style="text-align:right">Living residents</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($religions as $religion)
                <tr style="{{ $religion->is_active ? '' : 'opacity:.6' }}">
                    <td style="min-width:260px">
                        <form method="POST" action="{{ route('religions.update', $religion) }}" style="display:flex;gap:8px;align-items:center">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $religion->name }}" maxlength="100" required class="form-control" style="min-width:200px" aria-label="Rename {{ $religion->name }}">
                            <button type="submit" class="btn btn-secondary btn-sm" title="Save the new name"><i class="fas fa-check"></i> Save</button>
                        </form>
                    </td>
                    <td>
                        @if($religion->is_inc)
                            <span class="badge badge-navy">INC</span>
                        @else
                            <span class="badge badge-gray">Non-INC</span>
                        @endif
                    </td>
                    <td style="text-align:right;font-weight:600">{{ number_format($religion->living_count) }}</td>
                    <td><span class="badge {{ $religion->is_active ? 'badge-green' : 'badge-gray' }}">{{ $religion->is_active ? 'On the form' : 'Hidden' }}</span></td>
                    <td style="text-align:right">
                        @if($religion->is_inc)
                            <span class="td-muted" title="The INC / Non-INC filter depends on this entry"><i class="fas fa-lock"></i> Always on</span>
                        @else
                            <form method="POST" action="{{ route('religions.update', $religion) }}" style="display:inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="is_active" value="{{ $religion->is_active ? 0 : 1 }}">
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    <i class="fas {{ $religion->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i> {{ $religion->is_active ? 'Turn off' : 'Turn on' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
