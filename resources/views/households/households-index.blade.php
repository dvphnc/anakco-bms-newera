@extends('layouts.app')

@section('title', 'Households')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Households</h1>
        <p class="page-subtitle">All registered households in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('households.create') }}" class="btn btn-primary">
            <i class="fas fa-house-circle-plus"></i> Add Household
        </a>
    </div>
</div>

{{-- Summary Cards — only queries columns that actually exist --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-house"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($households->total()) }}</div>
            <div class="stat-label">Total Households</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-people-roof"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->count()) }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-location-dot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Purok::count()) }}</div>
            <div class="stat-label">Total Puroks</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-chart-pie"></i>
        </div>
        <div class="stat-info">
            @php
                $total = \App\Models\Household::count();
                $residents = \App\Models\Resident::where('residency_status','Active')->count();
                $avg = $total > 0 ? round($residents / $total, 1) : 0;
            @endphp
            <div class="stat-number">{{ $avg }}</div>
            <div class="stat-label">Avg. Members/HH</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" action="{{ route('households.index') }}">
            <div class="filter-bar">
                <div class="form-group flex-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                        <input type="text" name="search" class="form-control" style="padding-left:32px"
                               placeholder="Household no., head name, address..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Purok</label>
                    <select name="purok_id" class="form-control">
                        <option value="">All Puroks</option>
                        @foreach(\App\Models\Purok::orderBy('name')->get() as $purok)
                            <option value="{{ $purok->id }}" {{ request('purok_id') == $purok->id ? 'selected' : '' }}>
                                {{ $purok->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('households.index') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-house"></i> Household List</span>
        <span style="font-size:12px;color:var(--text-muted)">
            {{ number_format($households->total()) }} households
        </span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Household No.</th>
                    <th>Household Head</th>
                    <th>Purok</th>
                    <th>Address</th>
                    <th>Members</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($households as $hh)
                <tr>
                    <td class="td-mono">{{ $hh->household_number }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px">{{ $hh->household_head ?? '—' }}</div>
                    </td>
                    <td class="td-muted">{{ $hh->purok->name ?? '—' }}</td>
                    <td class="td-muted" style="max-width:220px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $hh->address }}
                    </td>
                    <td>
                        <span class="badge badge-navy">
                            <i class="fas fa-user" style="font-size:9px;margin-right:4px"></i>
                            {{ $hh->residents->count() }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="{{ route('households.show', $hh) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('households.edit', $hh) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('households.destroy', $hh) }}"
                                  onsubmit="return confirm('Delete this household?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-house"></i>
                            <p>No households found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($households->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing {{ $households->firstItem() }} to {{ $households->lastItem() }} of {{ number_format($households->total()) }}
        </span>
        {{ $households->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection