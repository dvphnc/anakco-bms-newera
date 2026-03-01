@extends('layouts.app')

@section('title', 'Residents')
@section('page-title', 'Residents')
@section('page-subtitle', 'Manage all registered residents of Barangay New Era')

@section('content')

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-title">Residents</h1>
        <p class="page-subtitle">All registered residents of Barangay New Era</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('residents.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Register Resident
        </a>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($residents->total()) }}</div>
            <div class="stat-label">Total Results</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::where('residency_status','Active')->count()) }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-check-to-slot"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::active()->where('is_voter',true)->count()) }}</div>
            <div class="stat-label">Registered Voters</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(122,21,21,0.08);color:var(--crimson-mid)">
            <i class="fas fa-person-cane"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Resident::active()->seniors()->count()) }}</div>
            <div class="stat-label">Senior Citizens</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" action="{{ route('residents.index') }}">
            <div class="filter-bar">
                <div class="form-group flex-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                        <input type="text" name="search" class="form-control" style="padding-left:32px"
                               placeholder="Name, address..." value="{{ request('search') }}">
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
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">All</option>
                        <option value="Male"   {{ request('gender') === 'Male'   ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        <option value="Active"      {{ request('status') === 'Active'      ? 'selected' : '' }}>Active</option>
                        <option value="Deceased"    {{ request('status') === 'Deceased'    ? 'selected' : '' }}>Deceased</option>
                        <option value="Transferred" {{ request('status') === 'Transferred' ? 'selected' : '' }}>Transferred</option>
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('residents.index') }}" class="btn btn-secondary">
                            <i class="fas fa-xmark"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">
            <i class="fas fa-users"></i>
            Resident List
        </span>
        <span style="font-size:12px;color:var(--text-muted)">
            Showing {{ $residents->firstItem() }}–{{ $residents->lastItem() }} of {{ number_format($residents->total()) }}
        </span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Resident</th>
                    <th>Purok</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Civil Status</th>
                    <th>Classifications</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($residents as $resident)
                <tr>
                    {{-- Name + Contact --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            {{-- Avatar --}}
                            <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px">
                                @if($resident->photo_path)
                                    <img src="{{ asset('storage/'.$resident->photo_path) }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    {{ strtoupper(substr($resident->first_name,0,1)) }}
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px">{{ $resident->full_name }}</div>
                                @if($resident->contact_number)
                                    <div class="td-muted">{{ $resident->contact_number }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="td-muted">{{ $resident->purok->name ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $resident->gender === 'Male' ? 'badge-blue' : 'badge-orange' }}">
                            {{ $resident->gender }}
                        </span>
                    </td>
                    <td>{{ $resident->age ?? '—' }}</td>
                    <td class="td-muted">{{ $resident->civil_status }}</td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:4px">
                            @if($resident->is_voter)
                                <span class="badge badge-green" style="font-size:10px">Voter</span>
                            @endif
                            @if($resident->is_senior)
                                <span class="badge badge-yellow" style="font-size:10px">Senior</span>
                            @endif
                            @if($resident->is_pwd)
                                <span class="badge badge-blue" style="font-size:10px">PWD</span>
                            @endif
                            @if($resident->is_solo_parent)
                                <span class="badge badge-orange" style="font-size:10px">Solo Parent</span>
                            @endif
                            @if($resident->is_4ps)
                                <span class="badge badge-gold" style="font-size:10px">4Ps</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @php
                            $cls = match($resident->residency_status) {
                                'Active'      => 'badge-green',
                                'Deceased'    => 'badge-gray',
                                'Transferred' => 'badge-yellow',
                                default       => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $resident->residency_status }}</span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="{{ route('residents.show', $resident) }}"
                               class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('residents.edit', $resident) }}"
                               class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('residents.destroy', $resident) }}"
                                  onsubmit="return confirm('Delete this resident?')">
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
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>No residents found. Try adjusting your filters.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($residents->hasPages())
    <nav role="navigation">
        <div style="font-size:12px;color:var(--text-muted)">
            Showing {{ $residents->firstItem() }} to {{ $residents->lastItem() }}
            of {{ number_format($residents->total()) }} residents
        </div>
        {{ $residents->withQueryString()->links() }}
    </nav>
    @endif

</div>

@endsection
