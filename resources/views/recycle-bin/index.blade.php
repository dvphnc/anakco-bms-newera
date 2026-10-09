@extends('layouts.app')
@section('title', 'Recycle Bin')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Recycle Bin</h1>
        <p class="page-subtitle">Archived records stay here and can be restored. Nothing is ever permanently deleted.</p>
    </div>
</div>

<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" action="{{ route('recycle-bin.index') }}">
            <div class="filter-bar">
                <div class="form-group">
                    <label class="form-label" for="rbType">Type</label>
                    <select name="type" id="rbType" class="form-control" onchange="this.form.submit()">
                        <option value="">All types ({{ array_sum($counts) }})</option>
                        @foreach($types as $key => [$label])
                            @if(isset($counts[$key]) || $type === $key)
                            <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>{{ $label }} ({{ $counts[$key] ?? 0 }})</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group flex-1">
                    <label class="form-label" for="rbSearch">Search</label>
                    <input type="search" name="search" id="rbSearch" class="form-control"
                           value="{{ request('search') }}" placeholder="Name, number, address...">
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-magnifying-glass"></i> Search</button>
                        @if($type || request('search'))
                        <a href="{{ route('recycle-bin.index') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i> Reset</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-box-archive"></i> Archived Records</span>
        <span style="font-size:13px;color:var(--text-muted)">{{ number_format($rows->total()) }} {{ Str::plural('record', $rows->total()) }}</span>
    </div>

    @if($rows->count())
    <div style="overflow-x:auto">
        <table>
            <thead>
                <tr>
                    <th>Record</th>
                    <th>Type</th>
                    <th>Archived</th>
                    <th>Archived By</th>
                    <th style="text-align:right">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;border-radius:var(--radius-sm);background:var(--surface2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fas {{ $row->icon }}" style="color:var(--text-muted);font-size:13px"></i>
                            </div>
                            <div style="min-width:0">
                                <div style="font-size:14px;font-weight:600;color:var(--text)">{{ $row->name }}</div>
                                @if($row->detail)
                                <div style="font-size:12px;color:var(--text-muted)">{{ $row->detail }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-gray">{{ $row->label }}</span></td>
                    <td style="color:var(--text-muted);white-space:nowrap">
                        {{ $row->archived_at?->format('M d, Y g:i A') }}
                        <div style="font-size:12px">{{ $row->archived_at?->diffForHumans() }}</div>
                    </td>
                    <td style="color:var(--text-muted)">{{ $row->archived_by ?? 'Unknown' }}</td>
                    <td style="text-align:right">
                        <form method="POST" action="{{ route('recycle-bin.restore', [$row->type, $row->id]) }}"
                              data-confirm="Restore {{ strtolower($row->label) }} &quot;{{ $row->name }}&quot;? It will show up in its module again."
                              data-confirm-title="Restore Record"
                              data-confirm-ok="Restore"
                              data-confirm-type="safe"
                              data-confirm-icon="fa-rotate-left">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary btn-sm">
                                <i class="fas fa-rotate-left"></i> Restore
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="padding:12px 20px">
        {{ $rows->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-box-archive"></i>
        <p>
            @if($type || request('search'))
                No archived records match. <a href="{{ route('recycle-bin.index') }}">Show all</a>
            @else
                The Recycle Bin is empty.
            @endif
        </p>
    </div>
    @endif
</div>

@endsection
