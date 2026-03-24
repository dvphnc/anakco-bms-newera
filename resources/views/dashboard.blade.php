@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ auth()->user()->name }} — {{ now()->format('l, F d, Y') }}</p>
    </div>
    <div class="page-actions no-print">
        <a href="{{ route('residents.create') }}"  class="btn btn-secondary btn-sm"><i class="fas fa-user-plus"></i> New Resident</a>
        <a href="{{ route('documents.create') }}"  class="btn btn-secondary btn-sm"><i class="fas fa-file-plus"></i> New Document</a>
        <a href="{{ route('blotter.create') }}"    class="btn btn-secondary btn-sm"><i class="fas fa-gavel"></i> New Blotter</a>
        <a href="{{ route('businesses.create') }}" class="btn btn-secondary btn-sm"><i class="fas fa-store"></i> New Permit</a>
    </div>
</div>

{{-- ALERTS: Birthday + Senior + Expiring --}}
@php
    $today       = now()->format('m-d');
    $birthdays   = \App\Models\Resident::where('residency_status','Active')
        ->whereRaw("DATE_FORMAT(birthdate,'%m-%d') = ?", [$today])
        ->orderBy('last_name')->get();
    $seniorBdays = $birthdays->filter(fn($r) => $r->age >= 60);
    $regularBdays= $birthdays->filter(fn($r) => $r->age < 60);

    $expiringPermits = \App\Models\Business::where('status','Active')
        ->whereBetween('expiry_date', [now(), now()->addDays(30)])
        ->orderBy('expiry_date')->get();

    $expiringDocs = \App\Models\Document::where('status','Pending')
        ->where('created_at', '<=', now()->subDays(7))
        ->orderBy('created_at')->limit(5)->get();
@endphp

@if($birthdays->count() || $expiringPermits->count())
<div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px">

    {{-- Senior Citizen Birthdays --}}
    @if($seniorBdays->count())
    <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#faf5ff;border:1px solid #e9d5ff;border-left:4px solid #7c3aed;border-radius:var(--radius)">
        <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#f3e8ff;color:#7c3aed;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px">
            🎂
        </div>
        <div style="flex:1">
            <div style="font-size:12.5px;font-weight:700;color:#6b21a8;margin-bottom:2px">
                Senior Citizen Birthdays Today ({{ $seniorBdays->count() }})
            </div>
            <div style="font-size:12px;color:#7c3aed">
                {{ $seniorBdays->map(fn($r) => $r->first_name.' '.$r->last_name.' ('.$r->age.')')->implode(', ') }}
            </div>
        </div>
        <a href="{{ route('residents.index') }}?filter=senior" class="btn btn-secondary btn-sm">View All</a>
    </div>
    @endif

    {{-- Regular Birthdays --}}
    @if($regularBdays->count())
    <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fff7ed;border:1px solid #fed7aa;border-left:4px solid #f97316;border-radius:var(--radius)">
        <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#ffedd5;color:#f97316;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px">
            🎉
        </div>
        <div style="flex:1">
            <div style="font-size:12.5px;font-weight:700;color:#c2410c;margin-bottom:2px">
                Resident Birthdays Today ({{ $regularBdays->count() }})
            </div>
            <div style="font-size:12px;color:#f97316">
                {{ $regularBdays->map(fn($r) => $r->first_name.' '.$r->last_name.' ('.$r->age.')')->implode(', ') }}
            </div>
        </div>
    </div>
    @endif

    {{-- Expiring Permits --}}
    @if($expiringPermits->count())
    <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #ef4444;border-radius:var(--radius)">
        <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#fee2e2;color:#ef4444;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:16px">
            ⚠️
        </div>
        <div style="flex:1">
            <div style="font-size:12.5px;font-weight:700;color:#991b1b;margin-bottom:2px">
                {{ $expiringPermits->count() }} Business Permit{{ $expiringPermits->count() > 1 ? 's' : '' }} Expiring Within 30 Days
            </div>
            <div style="font-size:12px;color:#ef4444">
                {{ $expiringPermits->map(fn($b) => $b->business_name.' (expires '.\Carbon\Carbon::parse($b->expiry_date)->format('M d').')')->take(3)->implode(', ') }}
                @if($expiringPermits->count() > 3) and {{ $expiringPermits->count() - 3 }} more... @endif
            </div>
        </div>
        <a href="{{ route('businesses.index') }}" class="btn btn-secondary btn-sm">View All</a>
    </div>
    @endif

</div>
@endif

{{-- STAT CARDS --}}
<div class="grid-4 mb-6">
    @php
        $stats = [
            ['label'=>'Total Residents',  'value'=>number_format($totalResidents),  'icon'=>'fa-users',    'color'=>'#22c55e','bg'=>'#f0fdf4', 'route'=>'residents.index'],
            ['label'=>'Households',       'value'=>number_format($totalHouseholds), 'icon'=>'fa-house',    'color'=>'#3b82f6','bg'=>'#eff6ff', 'route'=>'households.index'],
            ['label'=>'Pending Documents','value'=>number_format($pendingDocuments),'icon'=>'fa-file-alt', 'color'=>'#f59e0b','bg'=>'#fffbeb', 'route'=>'documents.index'],
            ['label'=>'Active Blotter',   'value'=>number_format($activeBlotter),   'icon'=>'fa-gavel',    'color'=>'#ef4444','bg'=>'#fef2f2', 'route'=>'blotter.index'],
            ['label'=>'Male Residents',   'value'=>number_format($totalMale),       'icon'=>'fa-person',   'color'=>'#3b82f6','bg'=>'#eff6ff', 'route'=>'residents.index'],
            ['label'=>'Female Residents', 'value'=>number_format($totalFemale),     'icon'=>'fa-person-dress','color'=>'#f97316','bg'=>'#fff7ed','route'=>'residents.index'],
            ['label'=>'Registered Voters','value'=>number_format($totalVoters),     'icon'=>'fa-check-to-slot','color'=>'#22c55e','bg'=>'#f0fdf4','route'=>'residents.index'],
            ['label'=>'Active Businesses','value'=>number_format($activeBusinesses),'icon'=>'fa-store',    'color'=>'#f59e0b','bg'=>'#fffbeb', 'route'=>'businesses.index'],
        ];
    @endphp
    @foreach($stats as $s)
    <a href="{{ route($s['route']) }}" style="text-decoration:none">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $s['bg'] }};color:{{ $s['color'] }}">
                <i class="fas {{ $s['icon'] }}"></i>
            </div>
            <div class="stat-info">
                <div class="stat-number">{{ $s['value'] }}</div>
                <div class="stat-label">{{ $s['label'] }}</div>
            </div>
        </div>
    </a>
    @endforeach
</div>

{{-- CHARTS + DEMOGRAPHICS --}}
<div class="grid-2 mb-6" style="grid-template-columns:2fr 1fr">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Documents Issued — {{ date('Y') }}</span>
        </div>
        <div class="card-body">
            <canvas id="monthlyDocChart" height="110"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-pie"></i> Age Groups</span>
        </div>
        <div class="card-body">
            <canvas id="ageChart" height="160"></canvas>
        </div>
    </div>
</div>

{{-- POPULATION + PUROK --}}
<div class="grid-2 mb-6">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Population Demographics</span>
        </div>
        <div class="card-body">
            @php
                $demos = [
                    ['label'=>'Registered Voters','value'=>$totalVoters,     'color'=>'var(--navy)'],
                    ['label'=>'Senior Citizens',  'value'=>$totalSeniors,    'color'=>'var(--gold)'],
                    ['label'=>'PWD',              'value'=>$totalPwd,        'color'=>'#2563eb'],
                    ['label'=>'Solo Parents',     'value'=>$totalSoloParent, 'color'=>'#7c3aed'],
                    ['label'=>'4Ps Beneficiaries','value'=>$total4ps,        'color'=>'#dc2626'],
                ];
            @endphp
            @foreach($demos as $d)
            @php $pct = $totalResidents > 0 ? round(($d['value'] / $totalResidents) * 100) : 0; @endphp
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:12.5px;color:var(--text)">{{ $d['label'] }}</span>
                    <span style="font-size:12px;color:var(--text-muted)">{{ number_format($d['value']) }} <span style="color:var(--text-subtle)">({{ $pct }}%)</span></span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $d['color'] }}"></div>
                </div>
            </div>
            @endforeach

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">Residency Status</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                    @php $statuses = [['Active',$totalActive,'#16a34a'],['Deceased',$totalDeceased,'var(--text-muted)'],['Transferred',$totalTransferred,'var(--gold)']]; @endphp
                    @foreach($statuses as [$label,$val,$color])
                    <div style="text-align:center;padding:10px;background:var(--surface2);border-radius:var(--radius-sm);border:1px solid var(--border)">
                        <div style="font-size:18px;font-weight:700;color:{{ $color }}">{{ number_format($val) }}</div>
                        <div style="font-size:10.5px;color:var(--text-muted);margin-top:2px">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">Gender Split</div>
                @php $gTotal = $totalMale + $totalFemale ?: 1; $mPct = round(($totalMale/$gTotal)*100); @endphp
                <div style="display:flex;border-radius:99px;overflow:hidden;height:12px;margin-bottom:8px">
                    <div style="width:{{ $mPct }}%;background:var(--navy)"></div>
                    <div style="width:{{ 100-$mPct }}%;background:var(--gold)"></div>
                </div>
                <div style="display:flex;gap:20px">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--navy)"></div>
                        Male — {{ number_format($totalMale) }} ({{ $mPct }}%)
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--gold)"></div>
                        Female — {{ number_format($totalFemale) }} ({{ 100-$mPct }}%)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-location-dot"></i> Residents by Purok</span>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead><tr><th>Purok</th><th style="text-align:right">Residents</th><th style="text-align:right">%</th></tr></thead>
                <tbody>
                    @forelse($residentsByPurok as $purok)
                    @php $pct = $totalResidents > 0 ? round(($purok->residents_count/$totalResidents)*100) : 0; @endphp
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:26px;height:26px;border-radius:var(--radius-sm);background:var(--navy-pale);display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-location-dot" style="font-size:11px;color:var(--navy)"></i>
                                </div>
                                <span style="font-weight:500;font-size:13px">{{ $purok->name }}</span>
                            </div>
                        </td>
                        <td style="text-align:right;font-weight:600;color:var(--navy)">{{ number_format($purok->residents_count) }}</td>
                        <td style="text-align:right;color:var(--text-muted);font-size:12px">{{ $pct }}%</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--text-muted)">No purok data yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- RECENT RECORDS + ACTIVITY --}}
<div class="grid-3 mb-6">

    {{-- Recent Residents --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-users"></i> Recent Residents</span>
            <a href="{{ route('residents.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($recentResidents as $r)
            <a href="{{ route('residents.show', $r->id) }}" style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border);text-decoration:none;transition:background 0.1s" onmouseover="this.style.background='var(--navy-pale)'" onmouseout="this.style.background=''">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:var(--gold);flex-shrink:0">
                    {{ strtoupper(substr($r->first_name,0,1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $r->last_name }}, {{ $r->first_name }}</div>
                    <div style="font-size:11px;color:var(--text-muted)">{{ $r->purok->name ?? '—' }}</div>
                </div>
                <span class="badge {{ $r->residency_status === 'Active' ? 'badge-green' : 'badge-gray' }}" style="font-size:10px">{{ $r->residency_status }}</span>
            </a>
            @empty
            <div class="empty-state" style="padding:24px"><i class="fas fa-users"></i><p>No residents yet</p></div>
            @endforelse
        </div>
    </div>

    {{-- Recent Documents --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-alt"></i> Recent Documents</span>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($recentDocuments as $d)
            <a href="{{ route('documents.show', $d->id) }}" style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border);text-decoration:none;transition:background 0.1s" onmouseover="this.style.background='var(--navy-pale)'" onmouseout="this.style.background=''">
                <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#fffbeb;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-file-alt" style="color:#f59e0b;font-size:14px"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12px;font-weight:600;color:var(--text);font-family:monospace">{{ $d->doc_number }}</div>
                    <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $d->resident->full_name ?? '—' }}</div>
                </div>
                <span class="badge {{ $d->status === 'Released' ? 'badge-green' : ($d->status === 'Pending' ? 'badge-yellow' : 'badge-blue') }}" style="font-size:10px">{{ $d->status }}</span>
            </a>
            @empty
            <div class="empty-state" style="padding:24px"><i class="fas fa-file-alt"></i><p>No documents yet</p></div>
            @endforelse
        </div>
    </div>

    {{-- Recent Blotter --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-gavel"></i> Recent Blotter</span>
            <a href="{{ route('blotter.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0">
            @forelse($recentBlotter as $b)
            <a href="{{ route('blotter.show', $b->id) }}" style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border);text-decoration:none;transition:background 0.1s" onmouseover="this.style.background='var(--navy-pale)'" onmouseout="this.style.background=''">
                <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-gavel" style="color:#ef4444;font-size:14px"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12px;font-weight:600;color:var(--text);font-family:monospace">{{ $b->case_number }}</div>
                    <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $b->incident_type }}</div>
                </div>
                <span class="badge {{ $b->status === 'Settled' ? 'badge-green' : ($b->status === 'Active' ? 'badge-red' : 'badge-gray') }}" style="font-size:10px">{{ $b->status }}</span>
            </a>
            @empty
            <div class="empty-state" style="padding:24px"><i class="fas fa-gavel"></i><p>No blotter cases yet</p></div>
            @endforelse
        </div>
    </div>

</div>

{{-- SERVICES SUMMARY --}}
<div class="grid-3 mb-6">
    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-file-alt"></i> Documents by Type</span></div>
        <div class="card-body" style="padding:0">
            <table><thead><tr><th>Type</th><th style="text-align:right">Count</th></tr></thead>
            <tbody>
                @forelse($documentsByType as $type => $count)
                <tr><td style="font-size:12.5px">{{ $type }}</td><td style="text-align:right;font-weight:600;color:var(--navy)">{{ number_format($count) }}</td></tr>
                @empty
                <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-muted)">No data</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:var(--gold)">{{ number_format($pendingDocuments) }}</div>
                <div style="font-size:10px;color:var(--text-subtle)">Pending</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:#16a34a">{{ number_format($releasedDocuments) }}</div>
                <div style="font-size:10px;color:var(--text-subtle)">Released</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-gavel"></i> Blotter by Type</span></div>
        <div class="card-body" style="padding:0">
            <table><thead><tr><th>Incident Type</th><th style="text-align:right">Count</th></tr></thead>
            <tbody>
                @forelse($blotterByType as $type => $count)
                <tr><td style="font-size:12.5px">{{ $type }}</td><td style="text-align:right;font-weight:600;color:var(--crimson)">{{ number_format($count) }}</td></tr>
                @empty
                <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-muted)">No data</td></tr>
                @endforelse
            </tbody></table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:var(--crimson)">{{ number_format($activeBlotter) }}</div>
                <div style="font-size:10px;color:var(--text-subtle)">Active</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:#16a34a">{{ number_format($settledBlotter) }}</div>
                <div style="font-size:10px;color:var(--text-subtle)">Settled</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><span class="card-title"><i class="fas fa-store"></i> Business Permits</span></div>
        <div class="card-body">
            @php $bizStats = [['Active',$activeBusinesses,'#16a34a'],['Expired',$expiredBusinesses,'var(--crimson)'],['Other',$totalBusinesses-$activeBusinesses-$expiredBusinesses,'var(--text-subtle)']]; @endphp
            @foreach($bizStats as [$label,$val,$color])
            @php $pct = $totalBusinesses > 0 ? round(($val/$totalBusinesses)*100) : 0; @endphp
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:12.5px">{{ $label }}</span>
                    <span style="font-size:12px;color:var(--text-muted)">{{ number_format($val) }} ({{ $pct }}%)</span>
                </div>
                <div class="progress-bar-wrap"><div class="progress-bar" style="width:{{ $pct }}%;background:{{ $color }}"></div></div>
            </div>
            @endforeach
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border);text-align:center">
                <div style="font-size:22px;font-weight:700;color:var(--navy)">{{ number_format($totalBusinesses) }}</div>
                <div style="font-size:11px;color:var(--text-subtle)">Total Registered Businesses</div>
            </div>
        </div>
    </div>
</div>

{{-- ACTIVITY FEED + QUICK ACCESS --}}
<div class="grid-2">

    {{-- Recent Activity --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-clock-rotate-left"></i> Recent Activity</span>
            <a href="{{ route('activity-log.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;max-height:340px;overflow-y:auto">
            @forelse($recentActivity as $log)
            @php
                $iconMap = ['created'=>'fa-plus','updated'=>'fa-pen','deleted'=>'fa-trash'];
                $colorMap = ['created'=>'#22c55e','updated'=>'#f59e0b','deleted'=>'#ef4444'];
                $icon  = $iconMap[$log->action]  ?? 'fa-circle';
                $color = $colorMap[$log->action] ?? 'var(--text-muted)';
            @endphp
            <div style="display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-bottom:1px solid var(--border)">
                <div style="width:30px;height:30px;border-radius:50%;background:{{ $color }}15;color:{{ $color }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:11px;margin-top:2px">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12.5px;color:var(--text)">
                        <strong>{{ $log->user->name ?? 'System' }}</strong>
                        {{ $log->action }}
                        a {{ strtolower(class_basename($log->loggable_type ?? '')) }}
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:2px">{{ $log->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge badge-gray" style="font-size:9px;flex-shrink:0">{{ class_basename($log->loggable_type ?? 'System') }}</span>
            </div>
            @empty
            <div class="empty-state" style="padding:32px"><i class="fas fa-clock-rotate-left"></i><p>No activity yet</p></div>
            @endforelse
        </div>
    </div>

    {{-- Quick Access --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                @php $links = [
                    ['href'=>route('residents.index'),  'icon'=>'fa-users',    'label'=>'Residents',  'color'=>'var(--navy)'],
                    ['href'=>route('households.index'), 'icon'=>'fa-house',    'label'=>'Households', 'color'=>'var(--gold)'],
                    ['href'=>route('documents.index'),  'icon'=>'fa-file-alt', 'label'=>'Documents',  'color'=>'#16a34a'],
                    ['href'=>route('blotter.index'),    'icon'=>'fa-gavel',    'label'=>'Blotter',    'color'=>'var(--crimson)'],
                    ['href'=>route('businesses.index'), 'icon'=>'fa-store',    'label'=>'Businesses', 'color'=>'#2563eb'],
                    ['href'=>route('officials.index'),  'icon'=>'fa-user-tie', 'label'=>'Officials',  'color'=>'#7c3aed'],
                    ['href'=>route('reports.index'),    'icon'=>'fa-chart-bar','label'=>'Analytics',  'color'=>'#0891b2'],
                    ['href'=>route('reports.generate'), 'icon'=>'fa-file-pdf', 'label'=>'Reports',    'color'=>'#ef4444'],
                    ['href'=>route('backup.index'),     'icon'=>'fa-database', 'label'=>'Backup',     'color'=>'#374151'],
                ]; @endphp
                @foreach($links as $l)
                <a href="{{ $l['href'] }}"
                   style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:16px 8px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);text-align:center;transition:all 0.15s;text-decoration:none"
                   onmouseover="this.style.borderColor='{{ $l['color'] }}';this.style.background='var(--navy-pale)'"
                   onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface2)'">
                    <div style="width:40px;height:40px;border-radius:var(--radius-sm);background:{{ $l['color'] }}18;display:flex;align-items:center;justify-content:center;color:{{ $l['color'] }};font-size:17px">
                        <i class="fas {{ $l['icon'] }}"></i>
                    </div>
                    <span style="font-size:11.5px;font-weight:600;color:var(--text)">{{ $l['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('monthlyDocChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Documents',
            data: [{{ $monthlyData[1] }},{{ $monthlyData[2] }},{{ $monthlyData[3] }},{{ $monthlyData[4] }},{{ $monthlyData[5] }},{{ $monthlyData[6] }},{{ $monthlyData[7] }},{{ $monthlyData[8] }},{{ $monthlyData[9] }},{{ $monthlyData[10] }},{{ $monthlyData[11] }},{{ $monthlyData[12] }}],
            backgroundColor: 'rgba(13,33,68,0.12)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks:{ color:'#9CA3AF', font:{size:10} }, grid:{ color:'#E5E7EB' } },
            y: { ticks:{ color:'#9CA3AF', font:{size:10}, stepSize:1 }, grid:{ color:'#E5E7EB' }, beginAtZero:true }
        }
    }
});

new Chart(document.getElementById('ageChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($ageGroups)) !!},
        datasets: [{
            data: {!! json_encode(array_values($ageGroups)) !!},
            backgroundColor: ['#C8861A','#0D2144','#16a34a','#9CA3AF'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '60%',
        plugins: {
            legend: { position:'bottom', labels:{ font:{size:11}, padding:10, color:'#4B5563' } }
        }
    }
});
</script>
@endpush