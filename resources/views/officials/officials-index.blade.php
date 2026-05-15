@extends('layouts.app')

@section('title', 'Officials & Staff')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Officials & Staff</h1>
        <p class="page-subtitle">Barangay New Era elected officials and personnel</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('officials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Official
        </a>
    </div>
</div>

{{-- Summary — uses is_active boolean, no status column --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-user-tie"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($officials->count()) }}</div>
            <div class="stat-label">Total Officials</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($officials->where('is_active', true)->count()) }}</div>
            <div class="stat-label">Active</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#F1F5F9;color:var(--navy)">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($officials->whereIn('position', ['Punong Barangay','Barangay Captain'])->count()) }}</div>
            <div class="stat-label">Punong Barangay</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ date('Y') }}</div>
            <div class="stat-label">Current Term Year</div>
        </div>
    </div>
</div>

{{-- Table — $officials is a plain collection from controller --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Officials List</span>
        <span style="font-size:13px;color:var(--text-muted)">{{ $officials->count() }} officials</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Committee</th>
                    <th>Contact</th>
                    <th>Term</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($officials as $official)
                <tr>
                    <td style="width:44px">
                        <div style="width:36px;height:36px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                            @if($official->photo_path)
                                <img src="{{ asset('storage/'.$official->photo_path) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                            @else
                                {{ strtoupper(substr($official->full_name ?? 'O', 0, 1)) }}
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13.5px">{{ $official->full_name }}</div>
                    </td>
                    <td>
                        <span class="badge badge-navy">{{ $official->position }}</span>
                    </td>
                    <td class="td-muted">{{ $official->committee ?? '—' }}</td>
                    <td class="td-muted">{{ $official->contact_number ?? '—' }}</td>
                    <td class="td-muted">
                        {{ $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y') : '—' }}
                        @if($official->term_end)
                            – {{ \Carbon\Carbon::parse($official->term_end)->format('Y') }}
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $official->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $official->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="{{ route('officials.show', $official) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('officials.edit', $official) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('officials.destroy', $official) }}"
                                  data-confirm="Delete {{ $official->full_name }}? This cannot be undone."
                                  data-confirm-title="Delete Official"
                                  data-confirm-ok="Delete">
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
                            <i class="fas fa-user-tie"></i>
                            <p>No officials found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection