@extends('layouts.app')
@section('title', 'Assistance Programs')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Assistance Programs</h1>
        <p class="page-subtitle">Relief, medicine and aid distributions — and how often each household or resident may claim</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('programs.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> New Program</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-hand-holding-heart"></i> Programs</span>
        <span class="td-muted">Claims are recorded from each resident's profile → Transactions</span>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Program</th>
                    <th>Claiming rule</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th style="text-align:right">Claims</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($programs as $program)
                @php
                    [$statusLabel, $statusBadge] = match (true) {
                        ! $program->is_active => ['Inactive', 'badge-gray'],
                        $program->isOpen()    => ['Open', 'badge-green'],
                        default               => ['Outside period', 'badge-yellow'],
                    };
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:600">{{ $program->name }}</div>
                        <div class="td-muted">{{ $program->type_label }}</div>
                    </td>
                    <td>{{ $program->scope_label }}</td>
                    <td class="td-muted" style="white-space:nowrap">
                        {{ $program->starts_on?->format('m/d/Y') ?? 'Any time' }} – {{ $program->ends_on?->format('m/d/Y') ?? 'no end date' }}
                    </td>
                    <td><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span></td>
                    <td style="text-align:right;font-weight:600">{{ number_format($program->claims_count) }}</td>
                    <td>
                        <div style="display:flex;gap:6px;justify-content:flex-end">
                            <a href="{{ route('programs.edit', $program) }}" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('programs.destroy', $program) }}"
                                  data-confirm="Archive “{{ $program->name }}”? It will no longer accept claims. Claims already made stay in residents' histories."
                                  data-confirm-title="Archive Program"
                                  data-confirm-ok="Archive">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon" title="Archive"><i class="fas fa-box-archive"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state" style="padding:32px">
                            <i class="fas fa-hand-holding-heart"></i>
                            <p>No programs yet. Create one — for example “Relief Pack — Typhoon Kristine”, once per household.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
