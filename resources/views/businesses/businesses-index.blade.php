@extends('layouts.app')

@section('title', 'Business Permits')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Business Permits</h1>
        <p class="page-subtitle">Registered businesses in Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('businesses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Issue Permit
        </a>
    </div>
</div>

{{-- Summary --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-store"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active'] + $summaryCounts['Expired'] + $summaryCounts['Suspended'] + $summaryCounts['Cancelled']) }}</div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active']) }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:#9B1C1C">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Expired']) }}</div>
            <div class="stat-label">Expired</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-pause-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Suspended']) }}</div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" action="{{ route('businesses.index') }}">
            <div class="filter-bar">
                <div class="form-group flex-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                        <input type="text" name="search" class="form-control" style="padding-left:32px"
                               placeholder="Business name, owner, permit no..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="business_type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($businessTypes as $t)
                            <option value="{{ $t }}" {{ request('business_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('businesses.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Records</span>
        <span style="font-size:12px;color:var(--text-muted)">{{ number_format($businesses->total()) }} records</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Permit No.</th>
                    <th>Business Name</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Address</th>
                    <th>Permit Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($businesses as $business)
                <tr>
                    <td class="td-mono">{{ $business->permit_number }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px">{{ $business->business_name }}</div>
                    </td>
                    <td>
                        <span class="badge badge-navy">{{ $business->business_type }}</span>
                    </td>
                    <td>
                        <div style="font-size:13px">{{ $business->owner_name }}</div>
                        @if($business->owner_contact)
                            <div class="td-muted">{{ $business->owner_contact }}</div>
                        @endif
                    </td>
                    <td class="td-muted" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $business->business_address }}
                    </td>
                    <td class="td-muted">
                        {{ $business->permit_date ? \Carbon\Carbon::parse($business->permit_date)->format('M d, Y') : '—' }}
                    </td>
                    <td>
                        @php $expiry = $business->expiry_date ? \Carbon\Carbon::parse($business->expiry_date) : null; @endphp
                        @if($expiry)
                            <span style="font-size:12.5px;{{ $expiry->isPast() ? 'color:var(--crimson);font-weight:600' : 'color:var(--text-muted)' }}">
                                {{ $expiry->format('M d, Y') }}
                            </span>
                        @else
                            <span class="td-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $cls = match($business->status) {
                                'Active'    => 'badge-green',
                                'Expired'   => 'badge-red',
                                'Suspended' => 'badge-yellow',
                                'Cancelled' => 'badge-gray',
                                default     => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $business->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="{{ route('businesses.show', $business) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('businesses.edit', $business) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('businesses.destroy', $business) }}"
                                  onsubmit="return confirm('Delete this business permit?')">
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
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="fas fa-store"></i>
                            <p>No businesses found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($businesses->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing {{ $businesses->firstItem() }} to {{ $businesses->lastItem() }} of {{ number_format($businesses->total()) }}
        </span>
        {{ $businesses->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection
