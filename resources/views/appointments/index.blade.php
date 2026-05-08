@extends('layouts.app')
@section('title', 'Document Appointments')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .page-header h1 {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--navy);
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .page-header h1 i { color: var(--gold); }

    /* Filters */
    .filter-bar {
        background: #fff;
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
        align-items: flex-end;
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border: 1px solid #e5e7eb;
    }
    .filter-group { display: flex; flex-direction: column; gap: .3rem; }
    .filter-group label { font-size: .74rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .04em; }
    .filter-control {
        padding: .45rem .75rem;
        border: 1.5px solid #d1d5db;
        border-radius: var(--radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: .82rem;
        min-width: 160px;
    }
    .filter-control:focus { outline: none; border-color: var(--navy); }
    .filter-actions { display: flex; gap: .5rem; align-self: flex-end; }

    /* Table */
    .table-wrap {
        background: #fff;
        border-radius: var(--radius);
        box-shadow: 0 1px 4px rgba(0,0,0,.06);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    .apt-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
    .apt-table th {
        background: var(--navy);
        color: rgba(255,255,255,.85);
        padding: .65rem 1rem;
        text-align: left;
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
    }
    .apt-table td {
        padding: .75rem 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        color: #374151;
        font-size: 14px;
    }
    .apt-table tr:last-child td { border-bottom: none; }
    .apt-table tr:hover td { background: #fafafa; }

    .apt-num {
        font-weight: 700;
        color: var(--navy);
        font-size: .8rem;
        letter-spacing: .06em;
    }
    .apt-name { font-weight: 600; color: var(--navy); }
    .apt-doc { font-size: .8rem; color: #4b5563; }

    /* Status badges */
    .badge {
        display: inline-block;
        padding: .25rem .65rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 700;
        border: 1.5px solid;
    }
    .badge-pending    { background: var(--gold-pale);    color: #78450a; border-color: var(--gold-border); }
    .badge-confirmed  { background: var(--navy-pale);    color: var(--navy); border-color: var(--navy-border); }
    .badge-processing { background: #eff6ff;              color: #1d4ed8; border-color: #bfdbfe; }
    .badge-ready      { background: #f0fdf4;              color: #14532d; border-color: #bbf7d0; }
    .badge-released   { background: #f9fafb;              color: #6b7280; border-color: #d1d5db; }
    .badge-cancelled  { background: var(--crimson-pale); color: var(--crimson); border-color: var(--crimson-border); }

    /* Update modal */
    .modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-backdrop.open { display: flex; }
    .modal {
        background: #fff;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: 440px;
        padding: 1.75rem;
        box-shadow: 0 8px 40px rgba(0,0,0,.2);
        position: relative;
    }
    .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--navy);
        margin-bottom: 1.25rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        font-size: 1rem;
        color: #9ca3af;
        cursor: pointer;
    }
    .modal-close:hover { color: var(--crimson); }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .78rem; font-weight: 600; color: var(--navy); margin-bottom: .35rem; }
    .form-control {
        width: 100%;
        padding: .5rem .8rem;
        border: 1.5px solid #d1d5db;
        border-radius: var(--radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: .83rem;
    }
    .form-control:focus { outline: none; border-color: var(--navy); }
    .modal-actions { display: flex; justify-content: flex-end; gap: .6rem; margin-top: 1.25rem; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .5rem 1.1rem;
        border-radius: var(--radius-sm);
        font-family: 'Poppins', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: opacity .2s;
    }
    .btn:hover { opacity: .88; }
    .btn-navy { background: var(--navy); color: #fff; }
    .btn-sm { padding: .35rem .75rem; font-size: .75rem; }
    .btn-outline { background: transparent; border: 1.5px solid var(--navy); color: var(--navy); }
    .btn-gold { background: var(--gold); color: #fff; }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #9ca3af;
    }
    .empty-state i { font-size: 2.5rem; margin-bottom: .75rem; }
    .empty-state p { font-size: .85rem; }

    .pagination-wrap { padding: 1rem 1.25rem; border-top: 1px solid #f0f0f0; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="fas fa-calendar-check"></i> Document Appointments</h1>
    <a href="{{ route('portal.index') }}" class="btn btn-outline btn-sm" target="_blank">
        <i class="fas fa-external-link-alt"></i> View Portal
    </a>
</div>

@include('partials._alerts')

{{-- Filters --}}
<form method="GET" action="{{ route('appointments.index') }}" class="filter-bar">
    <div class="filter-group">
        <label>Search</label>
        <input type="text" name="search" class="filter-control" placeholder="Name or number…" value="{{ request('search') }}">
    </div>
    <div class="filter-group">
        <label>Status</label>
        <select name="status" class="filter-control">
            <option value="">All Statuses</option>
            @foreach($statuses as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-group">
        <label>Document Type</label>
        <select name="document_type" class="filter-control">
            <option value="">All Types</option>
            @foreach($documentTypes as $dt)
                <option value="{{ $dt }}" {{ request('document_type') === $dt ? 'selected' : '' }}>{{ $dt }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn btn-navy btn-sm"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('appointments.index') }}" class="btn btn-outline btn-sm">Reset</a>
    </div>
</form>

{{-- Table --}}
<div class="table-wrap">
    @if($appointments->isEmpty())
        <div class="empty-state">
            <i class="fas fa-calendar-xmark"></i>
            <p>No appointments found.</p>
        </div>
    @else
        <table class="apt-table">
            <thead>
                <tr>
                    <th>Appointment #</th>
                    <th>Resident</th>
                    <th>Document</th>
                    <th>Preferred Date</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $apt)
                <tr data-id="{{ $apt->id }}">
                    <td><span class="apt-num">{{ $apt->appointment_number }}</span></td>
                    <td>
                        <div class="apt-name">{{ $apt->resident_name }}</div>
                        <div style="font-size:13px;color:#9ca3af">{{ $apt->contact_number }}</div>
                    </td>
                    <td><div class="apt-doc">{{ $apt->document_type }}</div></td>
                    <td>{{ $apt->preferred_date->format('M d, Y') }}</td>
                    <td style="color:#9ca3af">{{ $apt->created_at->format('M d, Y') }}</td>
                    <td>
                        @php $sc = strtolower($apt->status); @endphp
                        <span class="badge badge-{{ $sc }}">{{ $apt->status }}</span>
                    </td>
                    <td style="white-space:nowrap;display:flex;gap:.4rem">
                        <button class="btn btn-navy btn-sm"
                                onclick="openModal({{ $apt->id }}, '{{ $apt->appointment_number }}', '{{ $apt->status }}', '{{ addslashes($apt->notes ?? '') }}')">
                            <i class="fas fa-pencil"></i> Update
                        </button>
                        <form method="POST" action="{{ route('appointments.destroy', $apt) }}"
                              data-confirm="Delete appointment {{ $apt->appointment_number }}? This cannot be undone."
                              data-confirm-title="Delete Appointment"
                              data-confirm-ok="Delete">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:var(--crimson-pale);color:var(--crimson);border:1.5px solid var(--crimson-border)"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($appointments->hasPages())
            <div class="pagination-wrap">
                {{ $appointments->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Update Status Modal --}}
<div class="modal-backdrop" id="statusModal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
        <div class="modal-title"><i class="fas fa-rotate" style="color:var(--gold)"></i>&nbsp; Update Appointment Status</div>

        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')

            <div style="font-size:13px;color:#6b7280;margin-bottom:1rem">
                Appointment: <strong id="modalAptNum" style="color:var(--navy)"></strong>
            </div>

            <div class="form-group">
                <label>New Status</label>
                <select name="status" class="form-control" id="modalStatus">
                    @foreach($statuses as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Notes <span style="color:#9ca3af;font-weight:400">(optional — visible to resident)</span></label>
                <textarea name="notes" class="form-control" id="modalNotes" rows="3"
                          placeholder="e.g., Please confirm your preferred date, document is ready for pick-up, etc."></textarea>
            </div>

            <div id="statusError" style="display:none;color:var(--crimson);font-size:13px;margin-bottom:.75rem;padding:.5rem .75rem;background:#fef2f2;border-radius:6px;border:1px solid #fca5a5"></div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                <button type="submit" id="statusSubmitBtn" class="btn btn-gold"><i class="fas fa-save"></i> Save Status</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let _updateAptId = null;

    function openModal(id, aptNum, currentStatus, currentNotes) {
        _updateAptId = id;
        document.getElementById('modalAptNum').textContent = aptNum;
        document.getElementById('modalStatus').value  = currentStatus;
        document.getElementById('modalNotes').value   = currentNotes;
        document.getElementById('statusError').style.display = 'none';
        document.getElementById('statusModal').classList.add('open');
    }
    function closeModal() {
        document.getElementById('statusModal').classList.remove('open');
    }
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    const badgeClasses = ['badge-pending','badge-confirmed','badge-processing','badge-ready','badge-released','badge-cancelled'];

    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (!_updateAptId) return;

        const btn    = document.getElementById('statusSubmitBtn');
        const errDiv = document.getElementById('statusError');
        const status = document.getElementById('modalStatus').value;
        const notes  = document.getElementById('modalNotes').value;
        const url    = '/appointments/' + _updateAptId + '/status';

        errDiv.style.display = 'none';
        btn.disabled    = true;
        btn.innerHTML   = '<i class="fas fa-spinner fa-spin"></i> Saving…';

        axios.patch(url, { status: status, notes: notes })
            .then(function(res) {
                const row = document.querySelector('tr[data-id="' + _updateAptId + '"]');
                if (row) {
                    const badge = row.querySelector('.badge');
                    if (badge) {
                        badge.classList.remove(...badgeClasses);
                        badge.classList.add('badge-' + status.toLowerCase());
                        badge.textContent = status;
                    }
                }
                closeModal();
                bmsToast(res.data.message, 'success');
            })
            .catch(function(err) {
                const data = err.response?.data;
                const msg  = data?.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data?.message || 'Failed to update status.');
                errDiv.textContent   = msg;
                errDiv.style.display = 'block';
            })
            .finally(function() {
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Save Status';
            });
    });
</script>
@endpush
