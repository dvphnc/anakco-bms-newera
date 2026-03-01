@extends('layouts.app')

@section('title', 'Documents')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Document Issuance</h1>
        <p class="page-subtitle">Barangay certificates and clearances</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('documents.create') }}" class="btn btn-primary">
            <i class="fas fa-file-circle-plus"></i> Issue Document
        </a>
    </div>
</div>

{{-- Summary — computed inline so no controller variable needed --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-file-lines"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::count()) }}</div>
            <div class="stat-label">Total Documents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-hourglass-half"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Pending')->count()) }}</div>
            <div class="stat-label">Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-spinner"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Processing')->count()) }}</div>
            <div class="stat-label">Processing</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-circle-check"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format(\App\Models\Document::where('status','Released')->count()) }}</div>
            <div class="stat-label">Released</div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" action="{{ route('documents.index') }}">
            <div class="filter-bar">
                <div class="form-group flex-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px"></i>
                        <input type="text" name="search" class="form-control" style="padding-left:32px"
                               placeholder="Document no., resident name..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="document_type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($documentTypes as $t)
                            <option value="{{ $t }}" {{ request('document_type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">All</option>
                        @foreach(['Pending','Processing','Released','Cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="justify-content:flex-end">
                    <label class="form-label">&nbsp;</label>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                        <a href="{{ route('documents.index') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-file-lines"></i> Document Records</span>
        <span style="font-size:12px;color:var(--text-muted)">{{ number_format($documents->total()) }} records</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Doc No.</th>
                    <th>Resident</th>
                    <th>Document Type</th>
                    <th>Purpose</th>
                    <th>Fee</th>
                    <th>Date Requested</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
                    <td class="td-mono">{{ $doc->doc_number }}</td>
                    <td>
                        <div style="font-weight:600;font-size:13px">{{ $doc->resident->full_name ?? '—' }}</div>
                        <div class="td-muted">{{ $doc->resident->purok->name ?? '' }}</div>
                    </td>
                    <td>
                        <span class="badge badge-navy" style="white-space:normal;text-align:left;line-height:1.4">
                            {{ $doc->document_type }}
                        </span>
                    </td>
                    <td class="td-muted" style="max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $doc->purpose ?? '—' }}
                    </td>
                    <td>
                        @php $fee = $doc->fee ?? $doc->fee_paid ?? 0; @endphp
                        @if($fee && $fee > 0)
                            <span style="font-weight:600;color:var(--navy)">₱{{ number_format($fee,2) }}</span>
                        @else
                            <span class="badge badge-green">Free</span>
                        @endif
                    </td>
                    <td class="td-muted">{{ $doc->created_at->format('M d, Y') }}</td>
                    <td>
                        @php
                            $cls = match($doc->status) {
                                'Released'   => 'badge-green',
                                'Processing' => 'badge-blue',
                                'Pending'    => 'badge-yellow',
                                'Cancelled'  => 'badge-gray',
                                default      => 'badge-gray'
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $doc->status }}</span>
                    </td>
                    <td>
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="{{ route('documents.show', $doc) }}" class="btn btn-secondary btn-sm btn-icon" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('documents.edit', $doc) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('documents.destroy', $doc) }}"
                                  onsubmit="return confirm('Delete this document?')">
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
                            <i class="fas fa-file-lines"></i>
                            <p>No documents found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($documents->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px">
        <span style="font-size:12px;color:var(--text-muted)">
            Showing {{ $documents->firstItem() }} to {{ $documents->lastItem() }} of {{ number_format($documents->total()) }}
        </span>
        {{ $documents->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection