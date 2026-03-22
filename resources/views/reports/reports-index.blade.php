@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <p class="page-subtitle">Barangay New Era — population & services overview</p>
    </div>
    <div class="page-actions">
        <span style="font-size:12px;color:var(--text-muted);padding:8px 14px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius-sm)">
            <i class="fas fa-clock" style="color:var(--gold);margin-right:6px"></i>
            As of {{ now()->format('F d, Y') }}
        </span>
        <a href="{{ route('export.analytics', 'pdf') }}" class="btn btn-secondary" title="Export Population Summary PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> Population PDF
        </a>
        <a href="{{ route('export.analytics', 'excel') }}" class="btn btn-secondary" title="Export Population Summary Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Population Excel
        </a>
    </div>
</div>

{{-- EXPORT SECTION --}}
<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-download"></i> Export Data</span>
        <span style="font-size:11px;color:var(--text-muted)">Download records as PDF or Excel</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px">
            @php
                $modules = [
                    ['key' => 'residents',  'label' => 'Residents',  'icon' => 'fa-users',    'color' => '#1d76db'],
                    ['key' => 'households', 'label' => 'Households', 'icon' => 'fa-house',    'color' => '#5319e7'],
                    ['key' => 'documents',  'label' => 'Documents',  'icon' => 'fa-file-alt', 'color' => '#006b75'],
                    ['key' => 'blotter',    'label' => 'Blotter',    'icon' => 'fa-gavel',    'color' => '#e11d48'],
                    ['key' => 'businesses', 'label' => 'Businesses', 'icon' => 'fa-store',    'color' => '#f97316'],
                ];
            @endphp

            @foreach($modules as $m)
            <div style="border:1px solid var(--border);border-radius:var(--radius);overflow:hidden">
                {{-- Module header --}}
                <div style="background:{{ $m['color'] }}12;padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;border-radius:var(--radius-sm);background:{{ $m['color'] }}20;color:{{ $m['color'] }};display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0">
                        <i class="fas {{ $m['icon'] }}"></i>
                    </div>
                    <div>
                        <div style="font-size:13px;font-weight:700;color:var(--text)">{{ $m['label'] }}</div>
                        <div style="font-size:10px;color:var(--text-muted)">All records</div>
                    </div>
                </div>
                {{-- Export buttons --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                    <a href="{{ route('export.pdf', $m['key']) }}"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;font-size:12px;font-weight:600;color:#dc2626;background:#fff;border-right:1px solid var(--border);text-decoration:none;transition:background 0.15s"
                       onmouseover="this.style.background='#fee2e2'"
                       onmouseout="this.style.background='#fff'">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('export.excel', $m['key']) }}"
                       style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;font-size:12px;font-weight:600;color:#16a34a;background:#fff;text-decoration:none;transition:background 0.15s"
                       onmouseover="this.style.background='#dcfce7'"
                       onmouseout="this.style.background='#fff'">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- TOP STATS --}}
<div class="grid-4 mb-6">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalActive) }}</div>
            <div class="stat-label">Active Residents</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(200,134,26,0.1);color:var(--gold)">
            <i class="fas fa-house"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalHouseholds) }}</div>
            <div class="stat-label">Households</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(22,101,52,0.1);color:#14532D">
            <i class="fas fa-file-alt"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalDocuments) }}</div>
            <div class="stat-label">Documents Issued</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(155,28,28,0.08);color:var(--crimson)">
            <i class="fas fa-gavel"></i>
        </div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($totalBlotter) }}</div>
            <div class="stat-label">Blotter Cases</div>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
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

{{-- POPULATION BREAKDOWN --}}
<div class="grid-2 mb-6">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-chart-bar"></i> Population Demographics</span>
        </div>
        <div class="card-body">
            @php
                $demos = [
                    ['label' => 'Registered Voters', 'value' => $totalVoters,     'color' => 'var(--navy)'],
                    ['label' => 'Senior Citizens',   'value' => $totalSeniors,    'color' => 'var(--gold)'],
                    ['label' => 'PWD',               'value' => $totalPwd,        'color' => '#2563eb'],
                    ['label' => 'Solo Parents',      'value' => $totalSoloParent, 'color' => '#7c3aed'],
                    ['label' => '4Ps Beneficiaries', 'value' => $total4ps,        'color' => '#dc2626'],
                ];
            @endphp
            @foreach($demos as $d)
            @php $pct = $totalActive > 0 ? round(($d['value'] / $totalActive) * 100) : 0; @endphp
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:12.5px;color:var(--text)">{{ $d['label'] }}</span>
                    <span style="font-size:12px;color:var(--text-muted)">
                        {{ number_format($d['value']) }} <span style="color:var(--text-subtle)">({{ $pct }}%)</span>
                    </span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $d['color'] }}"></div>
                </div>
            </div>
            @endforeach

            <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">Residency Status</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
                    @php
                        $statuses = [
                            ['label'=>'Active',      'value'=>$totalActive,      'color'=>'#16a34a'],
                            ['label'=>'Deceased',    'value'=>$totalDeceased,    'color'=>'var(--text-muted)'],
                            ['label'=>'Transferred', 'value'=>$totalTransferred, 'color'=>'var(--gold)'],
                        ];
                    @endphp
                    @foreach($statuses as $s)
                    <div style="text-align:center;padding:10px;background:var(--surface2);border-radius:var(--radius-sm);border:1px solid var(--border)">
                        <div style="font-size:18px;font-weight:700;color:{{ $s['color'] }}">{{ number_format($s['value']) }}</div>
                        <div style="font-size:10.5px;color:var(--text-muted);margin-top:2px">{{ $s['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle);margin-bottom:10px">Gender Split</div>
                @php
                    $total = $totalMale + $totalFemale;
                    $malePct   = $total > 0 ? round(($totalMale   / $total) * 100) : 50;
                    $femalePct = 100 - $malePct;
                @endphp
                <div style="display:flex;border-radius:99px;overflow:hidden;height:12px;margin-bottom:8px">
                    <div style="width:{{ $malePct }}%;background:var(--navy)"></div>
                    <div style="width:{{ $femalePct }}%;background:var(--gold)"></div>
                </div>
                <div style="display:flex;gap:20px">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--navy)"></div>
                        Male — {{ number_format($totalMale) }} ({{ $malePct }}%)
                    </div>
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--gold)"></div>
                        Female — {{ number_format($totalFemale) }} ({{ $femalePct }}%)
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
                <thead>
                    <tr>
                        <th>Purok</th>
                        <th style="text-align:right">Residents</th>
                        <th style="text-align:right">%</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($residentsByPurok as $purok)
                    @php $pct = $totalActive > 0 ? round(($purok->residents_count / $totalActive) * 100) : 0; @endphp
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
                    <tr>
                        <td colspan="3" style="text-align:center;padding:24px;color:var(--text-muted)">No purok data yet</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SERVICES SUMMARY ROW --}}
<div class="grid-3 mb-6">
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-file-alt"></i> Documents by Type</span>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th style="text-align:right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documentsByType as $type => $count)
                    <tr>
                        <td style="font-size:12.5px">{{ $type }}</td>
                        <td style="text-align:right;font-weight:600;color:var(--navy)">{{ number_format($count) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-muted)">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
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
        <div class="card-header">
            <span class="card-title"><i class="fas fa-gavel"></i> Blotter by Type</span>
        </div>
        <div class="card-body" style="padding:0">
            <table>
                <thead>
                    <tr>
                        <th>Incident Type</th>
                        <th style="text-align:right">Count</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blotterByType as $type => $count)
                    <tr>
                        <td style="font-size:12.5px">{{ $type }}</td>
                        <td style="text-align:right;font-weight:600;color:var(--crimson)">{{ number_format($count) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;padding:20px;color:var(--text-muted)">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 16px;border-top:1px solid var(--border);display:grid;grid-template-columns:1fr 1fr;gap:8px">
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:var(--crimson)">{{ number_format($activeBlotter) }}</div>
                <div style="font-size:10px;color:var(--text-subtle)">Active</div>
            </div>
            <div style="text-align:center;padding:8px;background:var(--surface2);border-radius:var(--radius-sm)">
                <div style="font-size:15px;font-weight:700;color:#16a34a">{{ number_format($settledBlotter) }}</div>
                <div style="font-size:10px;color:var(--text-subtle)">Settled/Closed</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-store"></i> Business Permits</span>
        </div>
        <div class="card-body">
            @php
                $bizStats = [
                    ['label' => 'Active',  'value' => $activeBusinesses,  'color' => '#16a34a'],
                    ['label' => 'Expired', 'value' => $expiredBusinesses, 'color' => 'var(--crimson)'],
                    ['label' => 'Other',   'value' => $totalBusinesses - $activeBusinesses - $expiredBusinesses, 'color' => 'var(--text-subtle)'],
                ];
            @endphp
            @foreach($bizStats as $b)
            @php $pct = $totalBusinesses > 0 ? round(($b['value'] / $totalBusinesses) * 100) : 0; @endphp
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:12.5px;color:var(--text)">{{ $b['label'] }}</span>
                    <span style="font-size:12px;color:var(--text-muted)">{{ number_format($b['value']) }} ({{ $pct }}%)</span>
                </div>
                <div class="progress-bar-wrap">
                    <div class="progress-bar" style="width:{{ $pct }}%;background:{{ $b['color'] }}"></div>
                </div>
            </div>
            @endforeach
            <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--border);text-align:center">
                <div style="font-size:22px;font-weight:700;color:var(--navy)">{{ number_format($totalBusinesses) }}</div>
                <div style="font-size:11px;color:var(--text-subtle)">Total Registered Businesses</div>
            </div>
        </div>
    </div>
</div>

{{-- QUICK LINKS --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:12px">
            @php
                $links = [
                    ['href' => route('residents.index'),  'icon' => 'fas fa-users',    'label' => 'Residents',  'color' => 'var(--navy)'],
                    ['href' => route('households.index'), 'icon' => 'fas fa-house',    'label' => 'Households', 'color' => 'var(--gold)'],
                    ['href' => route('documents.index'),  'icon' => 'fas fa-file-alt', 'label' => 'Documents',  'color' => '#16a34a'],
                    ['href' => route('blotter.index'),    'icon' => 'fas fa-gavel',    'label' => 'Blotter',    'color' => 'var(--crimson)'],
                    ['href' => route('businesses.index'), 'icon' => 'fas fa-store',    'label' => 'Businesses', 'color' => '#2563eb'],
                    ['href' => route('officials.index'),  'icon' => 'fas fa-user-tie', 'label' => 'Officials',  'color' => '#7c3aed'],
                ];
            @endphp
            @foreach($links as $l)
            <a href="{{ $l['href'] }}"
               style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:16px 8px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);text-align:center;transition:all 0.15s;text-decoration:none"
               onmouseover="this.style.borderColor='{{ $l['color'] }}';this.style.background='var(--navy-pale)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--surface2)'">
                <div style="width:40px;height:40px;border-radius:var(--radius-sm);background:{{ $l['color'] }}18;display:flex;align-items:center;justify-content:center;color:{{ $l['color'] }};font-size:17px">
                    <i class="{{ $l['icon'] }}"></i>
                </div>
                <span style="font-size:11.5px;font-weight:600;color:var(--text)">{{ $l['label'] }}</span>
            </a>
            @endforeach
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
                data: [
                    {{ $monthlyData[1]  }}, {{ $monthlyData[2]  }}, {{ $monthlyData[3]  }},
                    {{ $monthlyData[4]  }}, {{ $monthlyData[5]  }}, {{ $monthlyData[6]  }},
                    {{ $monthlyData[7]  }}, {{ $monthlyData[8]  }}, {{ $monthlyData[9]  }},
                    {{ $monthlyData[10] }}, {{ $monthlyData[11] }}, {{ $monthlyData[12] }}
                ],
                backgroundColor: 'rgba(13,33,68,0.15)',
                borderColor: '#0D2144',
                borderWidth: 2,
                borderRadius: 5,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#9CA3AF', font: { size: 10 } }, grid: { color: '#E5E7EB' } },
                y: { ticks: { color: '#9CA3AF', font: { size: 10 }, stepSize: 1 }, grid: { color: '#E5E7EB' }, beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('ageChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($ageGroups)) !!},
            datasets: [{
                data: {!! json_encode(array_values($ageGroups)) !!},
                backgroundColor: ['#C8861A', '#0D2144', '#16a34a', '#9CA3AF'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 10, color: '#4B5563' }
                }
            }
        }
    });
</script>
@endpush