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
            <i class="fas fa-user-plus"></i> Add Official
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

{{-- Inline search + filter --}}
<div class="card mb-6">
    <div class="card-body" style="padding:14px 20px">
        <div class="filter-bar">
            <div class="form-group flex-1">
                <label class="form-label">Search</label>
                <div style="position:relative">
                    <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none"></i>
                    <input type="text" id="officialSearch" class="form-control" style="padding-left:32px"
                           placeholder="Name, position, committee...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select id="officialStatusFilter">
                    <option value="">All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="form-group" style="justify-content:flex-end">
                <label class="form-label">&nbsp;</label>
                <div style="display:flex;gap:6px">
                    <button id="officialResetBtn" class="btn btn-secondary">
                        <i class="fas fa-xmark"></i> Reset
                    </button>
                    <div style="display:flex;border:1px solid var(--border);border-radius:var(--radius-sm);overflow:hidden">
                        <button id="viewList" onclick="setOfficialView('list')"
                                class="btn btn-sm"
                                style="border-radius:0;border:none;background:var(--navy);color:#fff;
                                       padding:6px 12px;font-size:12px"
                                title="List view">
                            <i class="fas fa-list"></i>
                        </button>
                        <button id="viewGrouped" onclick="setOfficialView('grouped')"
                                class="btn btn-sm"
                                style="border-radius:0;border:none;background:var(--surface2);color:var(--text-muted);
                                       padding:6px 12px;font-size:12px"
                                title="By Position">
                            <i class="fas fa-layer-group"></i>
                        </button>
                    </div>
                </div>
            </div>
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
                        <button class="badge {{ $official->is_active ? 'badge-green' : 'badge-gray' }} status-toggle"
                                data-id="{{ $official->id }}"
                                data-active="{{ $official->is_active ? '1' : '0' }}"
                                style="border:none;cursor:pointer;font-family:inherit"
                                title="Click to toggle status">
                            {{ $official->is_active ? 'Active' : 'Inactive' }}
                        </button>
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

@push('scripts')
<script>
$(document).ready(function () {
    /* Select2 for status filter */
    $('#officialStatusFilter').select2({
        dropdownParent: $('body'),
        placeholder: 'All',
        allowClear: true,
        width: '160px',
        minimumResultsForSearch: -1
    });

    /* Client-side search + status filter */
    function filterTable() {
        const q      = $('#officialSearch').val().toLowerCase();
        const status = $('#officialStatusFilter').val();
        $('tbody tr').each(function () {
            const text     = $(this).text().toLowerCase();
            const isActive = $(this).find('.status-toggle').data('active') == 1;
            const matchQ   = !q || text.includes(q);
            const matchS   = !status
                || (status === 'active' && isActive)
                || (status === 'inactive' && !isActive);
            $(this).toggle(matchQ && matchS);
        });
    }

    let debounce;
    $('#officialSearch').on('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(filterTable, 250);
    });
    $('#officialStatusFilter').on('change', filterTable);
    $('#officialResetBtn').on('click', function () {
        $('#officialSearch').val('');
        $('#officialStatusFilter').val(null).trigger('change');
        filterTable();
    });

    /* Axios status toggle */
    $(document).on('click', '.status-toggle', function () {
        const btn      = $(this);
        const id       = btn.data('id');
        const isActive = btn.data('active') == 1;

        btn.html('<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i>').prop('disabled', true);

        axios.patch(`/officials/${id}/toggle-status`, {
            _token: '{{ csrf_token() }}'
        })
        .then(({ data }) => {
            const nowActive = data.is_active;
            btn.removeClass('badge-green badge-gray')
               .addClass(nowActive ? 'badge-green' : 'badge-gray')
               .text(nowActive ? 'Active' : 'Inactive')
               .data('active', nowActive ? '1' : '0')
               .prop('disabled', false);
            filterTable();
        })
        .catch(() => {
            btn.text(isActive ? 'Active' : 'Inactive').prop('disabled', false);
            alert('Could not update status. Please try again.');
        });
    });
});
</script>
@endpush