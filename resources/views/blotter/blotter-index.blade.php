@extends('layouts.app')

@section('title', 'Blotter Cases')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Blotter Cases</h1>
        <p class="page-subtitle">Incident and complaint records</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('blotter.create') }}" class="btn btn-primary">
            <i class="fas fa-gavel"></i> File Case
        </a>
    </div>
</div>

{{-- Summary — uses $summaryCounts from BlotterController --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-gavel"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($cases->total()) }}</div>
            <div class="stat-label">Total Cases</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C">
            <i class="fas fa-circle-exclamation"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active'] ?? 0) }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-magnifying-glass"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Under Investigation'] ?? 0) }}</div>
            <div class="stat-label">Under Investigation</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Settled'] ?? 0) }}</div>
            <div class="stat-label">Settled</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" action="{{ route('blotter.index') }}">
            <div class="filter-bar">
                <div class="form-group flex-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                        <input type="text" name="search" class="form-control" style="padding-left:32px"
                               placeholder="Case no., complainant, respondent..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Incident Type</label>
                    <select name="incident_type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($incidentTypes as $t)
                            <option value="{{ $t }}" {{ request('incident_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('blotter.index') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table — controller passes $cases not $blotters --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-gavel"></i> Case Records</span>
        <span style="font-size:12px;color:var(--text-muted)">{{ number_format($cases->total()) }} cases</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Case No.</th>
                    <th>Incident Type</th>
                    <th>Complainant</th>
                    <th>Respondent</th>
                    <th>Incident Date</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cases as $case)
                <tr>
                    <td class="td-mono">{{ $case->case_number }}</td>
                    <td>
                        <span class="badge badge-navy">{{ $case->incident_type }}</span>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px">
                            {{ $case->complainantResident->full_name ?? $case->complainant_name ?? '—' }}
                        </div>
                    </td>
                    <td class="td-muted">
                        {{ $case->respondent_name ?? '—' }}
                    </td>
                    <td class="td-muted">
                        {{ $case->incident_date ? \Carbon\Carbon::parse($case->incident_date)->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        @php
                            $cls = match($case->status) {
                                'Active'                       => 'badge-red',
                                'Under Investigation'          => 'badge-yellow',
                                'Mediated'                     => 'badge-blue',
                                'Settled'                      => 'badge-green',
                                'Closed'                       => 'badge-gray',
                                'Referred to Higher Authority' => 'badge-orange',
                                default                        => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $case->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="{{ route('blotter.show', $case) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('blotter.edit', $case) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('blotter.destroy', $case) }}"
                                  onsubmit="return confirm('Delete this case?')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-gavel"></i>
                            <p>No blotter cases found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cases->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing {{ $cases->firstItem() }} to {{ $cases->lastItem() }} of {{ number_format($cases->total()) }}
        </span>
        {{ $cases->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection