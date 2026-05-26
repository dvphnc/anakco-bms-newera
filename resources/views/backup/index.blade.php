@extends('layouts.app')
@section('title', 'Database Backup')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Database Backup & Restore</h1>
        <p class="page-subtitle">Manage database backups for Barangay New Era BMS</p>
    </div>
    <div class="page-actions">
        <form method="POST" action="{{ route('backup.create') }}"
              data-confirm="Create a new database backup now? The previous backup will not be deleted."
              data-confirm-title="Create Backup"
              data-confirm-ok="Create Backup"
              data-confirm-type="safe">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-database"></i> Backup Now
            </button>
        </form>
    </div>
</div>

<div class="grid-2 mb-6" style="grid-template-columns:2fr 1fr">

    {{-- Backup List --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-history"></i> Backup History</span>
            <span style="font-size:13px;color:var(--text-muted)">Last 10 backups kept automatically</span>
        </div>
        @if($files->count())
        <table>
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($files as $i => $file)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:32px;height:32px;border-radius:var(--radius-sm);background:{{ $i === 0 ? 'rgba(22,101,52,0.1)' : 'var(--surface2)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fas fa-file-code" style="color:{{ $i === 0 ? '#16a34a' : 'var(--text-muted)' }};font-size:13px"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:600;font-family:monospace;color:var(--text)">{{ $file['name'] }}</div>
                                @if($i === 0)<span class="badge badge-green">Latest</span>@endif
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--text-muted)">{{ $file['size'] }}</td>
                    <td style="color:var(--text-muted)">{{ $file['created'] }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('backup.download', $file['name']) }}"
                               class="btn btn-secondary btn-sm btn-icon" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                            <form method="POST" action="{{ route('backup.restore') }}"
                                  data-confirm="Restore from {{ $file['name'] }}? This will OVERWRITE all current data and cannot be undone."
                                  data-confirm-title="Restore Database"
                                  data-confirm-ok="Yes, Restore">
                                @csrf
                                <input type="hidden" name="filename" value="{{ $file['name'] }}">
                                <button type="submit" class="btn btn-secondary btn-sm btn-icon" title="Restore" style="color:var(--gold)">
                                    <i class="fas fa-rotate-left"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('backup.delete', $file['name']) }}"
                                  data-confirm="Delete backup file {{ $file['name'] }}? This cannot be recovered."
                                  data-confirm-title="Delete Backup"
                                  data-confirm-ok="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-database"></i>
            <p>No backups yet. Click <strong>Backup Now</strong> to create one.</p>
        </div>
        @endif
    </div>

    {{-- Right panel --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Upload & Restore --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-upload"></i> Upload & Restore</span>
            </div>
            <div class="card-body">
                <p style="font-size:14px;color:var(--text-muted);margin-bottom:14px">
                    Upload a <code>.sql</code> backup file from your computer to restore it.
                </p>
                <form method="POST" action="{{ route('backup.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label class="form-label">SQL Backup File</label>
                        <input type="file" name="backup_file" class="form-control" accept=".sql,.txt" required>
                    </div>
                    <button type="submit" class="btn btn-secondary" style="width:100%">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                </form>
            </div>
        </div>

        {{-- Info card --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle"></i> Backup Info</span>
            </div>
            <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px">
                    @php
                        $items = [
                            ['icon'=>'fa-database','label'=>'Database','value'=>config('database.connections.mysql.database'),'color'=>'var(--navy)'],
                            ['icon'=>'fa-server',  'label'=>'Host',    'value'=>config('database.connections.mysql.host').':'.config('database.connections.mysql.port'),'color'=>'var(--gold)'],
                            ['icon'=>'fa-folder',  'label'=>'Storage', 'value'=>'storage/app/backups/','color'=>'#16a34a'],
                            ['icon'=>'fa-shield-halved','label'=>'Retention','value'=>'Last 10 backups','color'=>'#2563eb'],
                        ];
                    @endphp
                    @foreach($items as $item)
                    <div style="display:flex;align-items:center;gap:10px;padding:8px;background:var(--surface2);border-radius:var(--radius-sm);border:1px solid var(--border)">
                        <div style="width:28px;height:28px;border-radius:var(--radius-sm);background:{{ $item['color'] }}15;color:{{ $item['color'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="fas {{ $item['icon'] }}" style="font-size:12px"></i>
                        </div>
                        <div>
                            <div style="font-size:11px;color:var(--text-subtle);text-transform:uppercase;letter-spacing:0.06em">{{ $item['label'] }}</div>
                            <div style="font-size:13px;font-weight:600;color:var(--text);font-family:monospace">{{ $item['value'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="margin-top:16px;padding:10px;background:#fef9c3;border:1px solid #fde047;border-radius:var(--radius-sm)">
                    <div style="font-size:13px;color:#854d0e;font-weight:600;margin-bottom:4px"><i class="fas fa-exclamation-triangle" style="margin-right:4px"></i> Warning</div>
                    <div style="font-size:13px;color:#854d0e">Restoring a backup will <strong>overwrite all current data</strong>. Always create a fresh backup before restoring.</div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection