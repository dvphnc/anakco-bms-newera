@php
    $logs = \App\Models\ActivityLog::where('loggable_type', get_class($record))
        ->where('loggable_id', $record->id)
        ->with('user')
        ->latest()
        ->take(10)
        ->get();
@endphp

@if($logs->count() > 0)
<div class="card" style="margin-top:16px">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Edit History</span>
        <span style="font-size:12px;color:var(--text-muted)">Last {{ $logs->count() }} changes</span>
    </div>
    <div>
        @foreach($logs as $log)
        <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border)">
            {{-- Avatar --}}
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:12px;flex-shrink:0;margin-top:2px">
                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span style="font-size:13px;font-weight:600;color:var(--text)">
                        {{ $log->user->name ?? 'Unknown User' }}
                    </span>
                    <span class="badge {{ $log->user->role === 'Admin' ? 'badge-navy' : ($log->user->role === 'Secretary' ? 'badge-blue' : 'badge-gold') }}" style="font-size:10px">
                        {{ $log->user->role ?? 'Staff' }}
                    </span>
                    <span class="badge {{ $log->action === 'created' ? 'badge-green' : ($log->action === 'deleted' ? 'badge-red' : 'badge-yellow') }}" style="font-size:10px">
                        {{ ucfirst($log->action) }}
                    </span>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:2px">
                    {{ $log->created_at->format('F d, Y \a\t h:i:s A') }}
                    <span style="color:var(--text-subtle)"> · {{ $log->created_at->diffForHumans() }}</span>
                </div>
                {{-- Changes detail --}}
                @if($log->changes && count($log->changes) > 0)
                <div style="margin-top:6px;font-size:11.5px;color:var(--text-muted)">
                    @foreach(array_slice($log->changes, 0, 4) as $field => $change)
                    <span style="display:inline-flex;align-items:center;gap:4px;margin-right:8px;margin-bottom:2px">
                        <span style="font-weight:600;color:var(--text)">{{ ucwords(str_replace('_',' ',$field)) }}:</span>
                        <span style="text-decoration:line-through;color:var(--text-subtle)">{{ is_array($change['old'] ?? null) ? '...' : ($change['old'] ?? '—') }}</span>
                        <i class="fas fa-arrow-right" style="font-size:9px;color:var(--text-subtle)"></i>
                        <span style="color:var(--navy);font-weight:500">{{ is_array($change['new'] ?? null) ? '...' : ($change['new'] ?? '—') }}</span>
                    </span>
                    @endforeach
                    @if(count($log->changes) > 4)
                    <span style="color:var(--text-subtle)">+{{ count($log->changes) - 4 }} more fields</span>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
