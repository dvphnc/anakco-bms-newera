@extends('layouts.app')

@section('title', $document->doc_number)

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Document Details</h1>
        <p class="page-subtitle">{{ $document->doc_number }} — {{ $document->document_type }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('documents.edit', $document) }}" class="btn btn-primary">
            <i class="fas fa-pen"></i> Edit
        </a>
        <a href="{{ route('documents.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:280px 1fr;gap:20px;align-items:start">

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card">
            <div style="background:linear-gradient(135deg,var(--navy),var(--navy-mid));padding:24px 20px;text-align:center">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(200,134,26,0.2);border:2px solid rgba(200,134,26,0.4);margin:0 auto 14px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-file-certificate" style="font-size:24px;color:var(--gold-light)"></i>
                </div>
                <div class="td-mono" style="color:rgba(255,255,255,0.5);font-size:11px;margin-bottom:4px">
                    {{ $document->doc_number }}
                </div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.3;margin-bottom:10px">
                    {{ $document->document_type }}
                </div>
                @php
                    $cls = match($document->status) {
                        'Released'   => 'badge-green',
                        'Processing' => 'badge-blue',
                        'Pending'    => 'badge-yellow',
                        'Cancelled'  => 'badge-gray',
                        default      => 'badge-gray'
                    };
                @endphp
                <span class="badge {{ $cls }}">{{ $document->status }}</span>
            </div>
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:8px">Resident</div>
                <a href="{{ route('residents.show', $document->resident) }}"
                   style="display:flex;align-items:center;gap:10px;color:var(--navy)">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;flex-shrink:0;font-weight:700;color:#fff;font-size:13px">
                        {{ strtoupper(substr($document->resident->first_name ?? 'R', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:13px">{{ $document->resident->full_name ?? '—' }}</div>
                        <div class="td-muted">{{ $document->resident->purok->name ?? '' }}</div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Actions --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-bolt"></i> Actions</span>
            </div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('documents.edit', $document) }}" class="btn btn-secondary" style="justify-content:flex-start">
                    <i class="fas fa-pen" style="color:var(--navy)"></i> Edit Document
                </a>

                {{-- Quick status update --}}
                @if($document->status === 'Pending' || $document->status === 'Processing')
                <form method="POST" action="{{ route('documents.update', $document) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="Released">
                    <input type="hidden" name="resident_id" value="{{ $document->resident_id }}">
                    <input type="hidden" name="document_type" value="{{ $document->document_type }}">
                    <input type="hidden" name="purpose" value="{{ $document->purpose }}">
                    <input type="hidden" name="date_released" value="{{ now()->format('Y-m-d') }}">
                    <button type="submit" class="btn btn-gold" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-circle-check"></i> Mark as Released
                    </button>
                </form>
                @endif

                <form method="POST" action="{{ route('documents.destroy', $document) }}"
                      onsubmit="return confirm('Delete this document?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:flex-start">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-circle-info"></i> Document Information</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                @php
                    $details = [
                        ['label'=>'Document No.',  'value'=>$document->doc_number],
                        ['label'=>'Document Type', 'value'=>$document->document_type],
                        ['label'=>'Status',        'value'=>$document->status],
                        ['label'=>'Purpose',       'value'=>$document->purpose ?? '—'],
                        ['label'=>'Fee',           'value'=>$document->fee > 0 ? '₱'.number_format($document->fee,2) : 'Free'],
                        ['label'=>'OR Number',     'value'=>$document->or_number ?? '—'],
                        ['label'=>'Issued By',     'value'=>$document->issued_by ?? '—'],
                        ['label'=>'Date Requested','value'=>$document->created_at->format('F d, Y')],
                        ['label'=>'Date Released', 'value'=>$document->date_released?->format('F d, Y') ?? '—'],
                        ['label'=>'Last Updated',  'value'=>$document->updated_at->format('F d, Y')],
                    ];
                @endphp
                @foreach($details as $d)
                <div style="padding:10px 0;border-bottom:1px solid var(--border);{{ $loop->even ? 'padding-left:24px' : '' }}">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:2px">
                        {{ $d['label'] }}
                    </div>
                    <div style="font-size:13.5px;color:var(--text);font-weight:500">{{ $d['value'] }}</div>
                </div>
                @endforeach
            </div>

            @if($document->remarks)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:6px">Remarks</div>
                <div style="font-size:13px;color:var(--text-muted);background:var(--surface2);padding:12px 16px;border-radius:var(--radius-sm);border:1px solid var(--border)">
                    {{ $document->remarks }}
                </div>
            </div>
            @endif
        </div>
    </div>

</div>

@endsection
