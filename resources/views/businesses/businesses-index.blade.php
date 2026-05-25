@extends('layouts.app')
@section('title', 'Business Permits')
@section('page-title', 'Business Permits')
@section('page-subtitle', 'Registered businesses in Barangay New Era')
@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Business Permits</h1>
            <p class="page-subtitle">Registered businesses in Barangay New Era</p>
        </div>
        <span id="headerFilterChip"
              style="display:none;font-size:11px;font-weight:700;padding:3px 10px;
                     border-radius:99px;background:var(--gold-pale);color:var(--gold);
                     border:1px solid var(--gold-border);cursor:pointer"
              onclick="toggleFilters('businesses')"
              title="Filters active — click to open">
            <i class="fas fa-sliders"></i> <span id="headerFilterCount"></span> active
        </span>
    </div>
    <div class="page-actions">
        <a id="btnExportPdf" href="{{ route('export.pdf', 'businesses') }}" class="btn btn-secondary" title="Export PDF">
            <i class="fas fa-file-pdf" style="color:#dc2626"></i> PDF
        </a>
        <a id="btnExportExcel" href="{{ route('export.excel', 'businesses') }}" class="btn btn-secondary" title="Export Excel">
            <i class="fas fa-file-excel" style="color:#16a34a"></i> Excel
        </a>
        <a href="{{ route('businesses.create') }}" class="btn btn-primary">
            <i class="fas fa-file-plus"></i> Issue Permit
        </a>
    </div>
</div>

{{-- Expiry Notice Tray --}}
@php
    $bizAlertCount = ($summaryCounts['Overdue'] > 0 ? 1 : 0) + ($summaryCounts['ExpiringSoon'] > 0 ? 1 : 0);
@endphp
@if($bizAlertCount)
<div class="alert-tray open no-print mb-6">
    <div class="alert-tray-hdr" onclick="this.closest('.alert-tray').classList.toggle('open')">
        <i class="fas fa-bell tray-icon"></i>
        <span>{{ $bizAlertCount }} Notice{{ $bizAlertCount > 1 ? 's' : '' }} — Business Permit Alert{{ $bizAlertCount > 1 ? 's' : '' }}</span>
        <i class="fas fa-chevron-down tray-caret"></i>
    </div>
    <div class="alert-tray-body">

        @if($summaryCounts['Overdue'] > 0)
        <div class="alert-item alert-permit">
            <i class="fas fa-triangle-exclamation"></i>
            <span>
                <strong>{{ $summaryCounts['Overdue'] }} Active Permit{{ $summaryCounts['Overdue'] > 1 ? 's' : '' }} Overdue —</strong>
                {{ $overdueBusinesses->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ') }}{{ $summaryCounts['Overdue'] > 3 ? ' +' . ($summaryCounts['Overdue'] - 3) . ' more' : '' }}
            </span>
            <button class="alert-link" style="background:none;cursor:pointer" onclick="toggleFilters('businesses');quickFilter('expiryFilter','expired')">
                <i class="fas fa-filter" style="font-size:11px;margin-right:4px"></i> Show Overdue
            </button>
        </div>
        @endif

        @if($summaryCounts['ExpiringSoon'] > 0)
        <div class="alert-item alert-senior">
            <i class="fas fa-clock"></i>
            <span>
                <strong>{{ $summaryCounts['ExpiringSoon'] }} Permit{{ $summaryCounts['ExpiringSoon'] > 1 ? 's' : '' }} Expiring Within 30 Days —</strong>
                {{ $expiringBusinesses->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ') }}{{ $summaryCounts['ExpiringSoon'] > 3 ? ' +' . ($summaryCounts['ExpiringSoon'] - 3) . ' more' : '' }}
            </span>
            <button class="alert-link" style="background:none;cursor:pointer" onclick="toggleFilters('businesses');quickFilter('expiryFilter','expiring_soon')">
                <i class="fas fa-filter" style="font-size:11px;margin-right:4px"></i> Show Expiring
            </button>
        </div>
        @endif

    </div>
</div>
@endif

{{-- Stat Cards --}}
<div class="grid-4 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', null)">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statBizTotal">{{ number_format(array_sum([$summaryCounts['Active'],$summaryCounts['Expired'],$summaryCounts['Suspended'],$summaryCounts['Cancelled']])) }}</div>
            <div class="stat-label">Total Businesses</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Active')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Active']) }}</div>
            <div class="stat-label">Active Permits</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('expiryFilter', 'expiring_soon')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['ExpiringSoon']) }}</div>
            <div class="stat-label">Expiring in 30 Days</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('expiryFilter', 'expired')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-triangle-exclamation"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Overdue']) }}</div>
            <div class="stat-label">Overdue (Active)</div>
        </div>
    </div>
</div>
<div class="grid-2 mb-6">
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Expired')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-times-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Expired']) }}</div>
            <div class="stat-label">Marked Expired</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="quickFilter('statusFilter', 'Suspended')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-pause-circle"></i></div>
        <div class="stat-info">
            <div class="stat-number">{{ number_format($summaryCounts['Suspended']) }}</div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>

{{-- Source Quick-Filter Chip Strip --}}
<div class="no-print" style="display:flex;align-items:center;gap:8px;margin-bottom:16px;flex-wrap:wrap">
    <span style="font-size:12px;font-weight:600;color:var(--text-subtle);text-transform:uppercase;letter-spacing:.06em">Source:</span>
    <button type="button" class="src-chip src-chip-active" data-src=""
            style="height:30px;padding:0 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid var(--navy);background:var(--navy);color:#fff;transition:all .15s">
        All
    </button>
    <button type="button" class="src-chip" data-src="portal"
            style="height:30px;padding:0 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid #bfdbfe;background:#eff6ff;color:#1d4ed8;transition:all .15s">
        <i class="fas fa-globe" style="font-size:10px;margin-right:4px"></i>Portal
        <span id="srcChipPortalCount" style="margin-left:5px;background:#1d4ed8;color:#fff;border-radius:4px;
              padding:1px 7px;font-size:10px;font-weight:700">
            {{ \App\Models\Business::where('source','portal')->whereNotNull('permit_date')->count() }}
        </span>
    </button>
    <button type="button" class="src-chip" data-src="walk-in"
            style="height:30px;padding:0 14px;border-radius:var(--radius-sm);font-size:12px;font-weight:600;cursor:pointer;
                   border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);transition:all .15s">
        <i class="fas fa-walking" style="font-size:10px;margin-right:4px"></i>Walk-in
    </button>
</div>

{{-- Hidden source select — backing value for chip logic + DataTable AJAX --}}
<select id="sourceFilter" style="display:none">
    <option value=""></option>
    <option value="portal">Portal</option>
    <option value="walk-in">Walk-in</option>
</select>

{{-- Filter Bar --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('businesses')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-sliders"></i> Filters</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('businesses')">
            <i class="fas fa-sliders"></i>
            <span id="filterToggleText">Show Filters</span>
        </button>
    </div>
    <div id="filterPanel" style="display:none">
        <div class="card-body" style="padding:20px 22px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="searchInput" class="form-control" style="padding-left:32px"
                               placeholder="Business name, owner, permit number…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Business Type</label>
                    <select id="typeFilter">
                        <option value=""></option>
                        @foreach($businessTypes as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        @foreach(['Active','Expired','Suspended','Cancelled'] as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Expiry Status</label>
                    <select id="expiryFilter">
                        <option value=""></option>
                        <option value="expiring_soon">⚠ Expiring Soon (30 days)</option>
                        <option value="expired">🔴 Overdue</option>
                        <option value="valid">✅ Valid</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <button type="button" id="resetBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-xmark"></i> Reset All Filters
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-store"></i> Business Records</span>
        <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-muted)">
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block"></span>Overdue</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block"></span>Expiring Soon</span>
            <span style="display:flex;align-items:center;gap:4px"><span style="width:10px;height:10px;border-radius:50%;background:#16a34a;display:inline-block"></span>Valid</span>
        </div>
    </div>
    <div class="table-responsive">
        <table id="businessesTable" style="width:100%">
            <thead>
                <tr>
                    <th>Permit No.</th>
                    <th>Business Name</th>
                    <th>Type</th>
                    <th>Owner</th>
                    <th>Permit Date</th>
                    <th>Expiry Date</th>
                    <th>Status</th>
                    <th style="display:none"></th>
                    <th style="text-align:right;width:110px">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- Issue Business Permit Modal (portal submissions) --}}
<div id="bizIssueModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9700;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBizIssueModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:480px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-stamp" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Issue Business Permit</span>
            </div>
            <button onclick="closeBizIssueModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:10px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Permit No.</div>
                        <div id="bizIssueNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Appointment Date</div>
                        <div id="bizIssueAppt" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Business</div>
                        <div id="bizIssueBiz" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Owner</div>
                        <div id="bizIssueOwner" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Permit Date <span style="color:var(--crimson)">*</span></label>
                    <input type="date" id="bizIssuePermitDate" class="form-control">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Expiry Date <span style="color:var(--crimson)">*</span></label>
                    <input type="date" id="bizIssueExpiryDate" class="form-control">
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Fee Paid (₱) <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="number" id="bizIssueFee" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">O.R. Number <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="text" id="bizIssueOR" class="form-control" placeholder="e.g. 2026-00123">
                </div>
            </div>
            <div id="bizIssueError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeBizIssueModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="bizIssueSaveBtn" onclick="saveBizIssue()" class="btn btn-success">
                    <i class="fas fa-stamp"></i> Issue Permit
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Business Status Update Modal --}}
<div id="bizStatusModal"
     style="display:none;position:fixed;inset:0;z-index:9600;align-items:center;justify-content:center;
            background:rgba(9,20,40,0.5);backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBizModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:420px;max-width:94vw;
                box-shadow:0 24px 60px rgba(0,0,0,0.28);animation:qvSlideIn .18s ease">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid var(--border);background:var(--navy);
                    border-radius:var(--radius-lg) var(--radius-lg) 0 0">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Business Status</span>
            </div>
            <button onclick="closeBizModal()"
                    style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;
                           display:flex;align-items:center;justify-content:center;
                           color:rgba(255,255,255,0.7);cursor:pointer;font-size:13px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <div style="font-size:12px;color:var(--text-subtle);font-weight:600;text-transform:uppercase;
                        letter-spacing:.05em;margin-bottom:4px">Permit</div>
            <div id="bizModalNum" style="font-family:'Courier New',monospace;font-size:13px;
                                         color:var(--navy);font-weight:700;margin-bottom:14px"></div>

            <div class="form-group" style="margin-bottom:14px">
                <label class="form-label">New Status</label>
                <select id="bizModalStatus" class="form-control" style="height:42px">
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Expired">Expired</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:20px">
                <label class="form-label">Notes <span style="color:var(--text-subtle);font-weight:400">(optional)</span></label>
                <textarea id="bizModalNotes" class="form-control" rows="3"
                          placeholder="Add notes for the applicant…"
                          style="resize:vertical;min-height:80px"></textarea>
                <div id="bizModalEmailNote"
                     style="display:none;font-size:11.5px;color:#2563eb;margin-top:5px">
                    <i class="fas fa-envelope" style="margin-right:4px"></i>
                    An email notification will be sent to the applicant.
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="closeBizModal()" class="btn btn-secondary btn-sm">Cancel</button>
                <button type="button" id="bizModalSave" class="btn btn-primary btn-sm" style="min-width:110px">
                    <span id="bizModalSpinner" style="display:none">
                        <span style="display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);
                                     border-top-color:#fff;border-radius:50%;animation:req-spin .7s linear infinite;
                                     vertical-align:middle;margin-right:5px"></span>
                    </span>
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Business Quick View Panel --}}
<div id="bizQvPanel"
     style="display:none;opacity:0;position:fixed;inset:0;z-index:9500;
            align-items:flex-start;justify-content:flex-end;
            background:rgba(9,20,40,0.45);backdrop-filter:blur(3px);
            transition:opacity .2s"
     onclick="if(event.target===this)closeBizPanel()">
    <div style="width:420px;max-width:95vw;height:100vh;background:var(--surface);
                overflow-y:auto;box-shadow:-8px 0 40px rgba(0,0,0,0.22);
                display:flex;flex-direction:column;animation:qvSlideIn .2s ease">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:16px 20px;border-bottom:1px solid var(--border);
                    background:var(--navy);flex-shrink:0">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-store" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Business Quick View</span>
            </div>
            <button onclick="closeBizPanel()"
                    style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;
                           display:flex;align-items:center;justify-content:center;
                           color:rgba(255,255,255,0.7);cursor:pointer;font-size:13px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div id="bizQvBody" style="flex:1"></div>
    </div>
</div>
<style>
@keyframes qvSlideIn { from { transform:translateX(32px);opacity:0; } to { transform:translateX(0);opacity:1; } }
</style>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
/* ── SaaS surface & stat card polish ──────────────────────────────── */
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }
#businessesTable_wrapper .dataTables_length,
#businessesTable_wrapper .dataTables_filter { display:none; }
#businessesTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate { padding:12px 20px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#businessesTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#businessesTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

/* ── Spinner keyframe (used in status modal) ──────────────────────── */
@keyframes req-spin { to { transform: rotate(360deg); } }

/* ── Filter Select2 height override — filter panels use 38px, not 48px ── */
#filterPanel .select2-container--default .select2-selection--single {
    height: 38px !important;
    padding: 0 32px 0 10px !important;
    min-height: unset !important;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
    top: 0 !important;
    right: 8px !important;
}
.btn-export-filtered { border-color: var(--gold) !important; box-shadow: 0 0 0 2px rgba(200,134,26,0.18) !important; }
</style>
<script>
$(document).ready(function () {

    /* Temporarily expose the hidden filter panel so Select2 measures real dimensions.
       The browser won't paint until after this synchronous block, so no visual flash. */
    var $fp = $('#filterPanel');
    var _fpW = $fp.parent().width();
    $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });

    const s2Single = { dropdownParent: $('body'), allowClear: true,  width: '100%',
                       minimumResultsForSearch: 0,
                       language: { noResults: () => 'No matches' } };

    $('#typeFilter').select2($.extend({}, s2Single, { placeholder: 'All business types…' }));
    $('#statusFilter').select2($.extend({}, s2Single, { placeholder: 'All statuses…' }));
    $('#expiryFilter').select2($.extend({}, s2Single, { placeholder: 'All' }));

    $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });

    window._bizTable = null;
    var table = $('#businessesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('businesses.index') }}',
            data: function (d) {
                d.business_type = $('#typeFilter').val();
                d.status        = $('#statusFilter').val();
                d.expiry_filter = $('#expiryFilter').val();
                d.source        = $('#sourceFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',      name: 'permit_number',  width: '130px' },
            { data: 'name_col',        name: 'business_name' },
            { data: 'type_col',        name: 'business_type',  width: '130px' },
            { data: 'owner_col',       name: 'owner_name',     orderable: false },
            { data: 'permit_date_col', name: 'permit_date',    width: '110px' },
            { data: 'expiry_col',      name: 'expiry_date',    width: '140px' },
            { data: 'status_col',      name: 'status',         width: '100px' },
            { data: 'updated_at',      name: 'updated_at',     visible: false, searchable: false },
            { data: 'actions',         name: 'actions',        orderable: false, searchable: false, width: '110px' },
        ],
        order: [[7, 'desc']],
        pageLength: 15,
        initComplete: function () { window._bizTable = this.api(); },
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#businessesTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-store"></i><p>No businesses found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No businesses match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        },
        createdRow: function (row, data) {
            if (data.expiry_col && data.expiry_col.includes('overdue')) {
                $(row).css('background', '#fff5f5');
            } else if (data.expiry_col && data.expiry_col.includes('days left') && data.expiry_col.includes('#b45309')) {
                $(row).css('background', '#fffdf0');
            }
        }
    });

    /* ── Axios DELETE ─────────────────────────────────────────────────── */
    $('#businessesTable').on('click', '.biz-delete-btn', function () {
        const $btn = $(this);
        const url  = $btn.data('url');
        const num  = $btn.data('num');
        const name = $btn.data('name');

        bmsConfirm({
            title:   'Delete Business Permit',
            message: 'Delete permit ' + num + ' for ' + name + '? This cannot be undone.',
            ok:      'Delete',
        }, function () {
            const icon = $btn.find('i');
            const orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin');
            $btn.prop('disabled', true);

            axios.delete(url, { data: { _token: '{{ csrf_token() }}' } })
                .then(function (res) {
                    table.row($btn.closest('tr')).remove().draw(false);
                    bmsStatDecrement('statBizTotal');
                    bmsToast(res.data.message || 'Permit deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig);
                    $btn.prop('disabled', false);
                    bmsToast('Could not delete permit.', 'error');
                });
        });
    });

        /* ── Issue Permit (portal submissions) ──────────────────────────── */
    $('#businessesTable').on('click', '.biz-issue-btn', function () {
        const $btn = $(this);
        document.getElementById('bizIssueNum').textContent   = $btn.data('num');
        document.getElementById('bizIssueBiz').textContent   = $btn.data('biz');
        document.getElementById('bizIssueOwner').textContent = $btn.data('owner');
        document.getElementById('bizIssueAppt').textContent  = $btn.data('appt');
        const today    = new Date();
        const nextYear = new Date(today);
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        const fmt = d => d.toISOString().slice(0, 10);
        document.getElementById('bizIssuePermitDate').value  = fmt(today);
        document.getElementById('bizIssueExpiryDate').value  = fmt(nextYear);
        document.getElementById('bizIssueFee').value         = '';
        document.getElementById('bizIssueOR').value          = '';
        document.getElementById('bizIssueError').style.display = 'none';
        window._bizIssueUrl = $btn.data('issue-url');
        document.getElementById('bizIssueModal').style.display = 'flex';
    });

    /* ── URL persistence ──────────────────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        ['s','expiry_filter','source'].forEach(k => url.searchParams.delete(k));
        url.searchParams.delete('business_type');
        url.searchParams.delete('status');
        if ($('#searchInput').val())  url.searchParams.set('s', $('#searchInput').val());
        if ($('#expiryFilter').val()) url.searchParams.set('expiry_filter', $('#expiryFilter').val());
        if ($('#typeFilter').val())   url.searchParams.set('business_type', $('#typeFilter').val());
        if ($('#statusFilter').val()) url.searchParams.set('status', $('#statusFilter').val());
        if ($('#sourceFilter').val()) url.searchParams.set('source', $('#sourceFilter').val());
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s'))             { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('expiry_filter')) { $('#expiryFilter').val(p.get('expiry_filter')).trigger('change.select2'); any = true; }
        const type = p.get('business_type'), status = p.get('status');
        if (type)   { $('#typeFilter').val(type).trigger('change.select2'); any = true; }
        if (status) { $('#statusFilter').val(status).trigger('change.select2'); any = true; }
        if (p.get('source')) {
            var src = p.get('source');
            $('#sourceFilter').val(src);
            $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === src); });
            any = true;
        }
        return any;
    }
    var _pdfBase  = '{{ route('export.pdf',   'businesses') }}';
    var _xlsxBase = '{{ route('export.excel', 'businesses') }}';

    function _syncExportUrls() {
        var p = {};
        var s  = $('#searchInput').val();   if (s)  p.s = s;
        var st = $('#statusFilter').val();  if (st) p.status = st;
        var tp = $('#typeFilter').val();    if (tp) p.business_type = tp;
        var ex = $('#expiryFilter').val();  if (ex) p.expiry_filter = ex;
        var sc = $('#sourceFilter').val();  if (sc) p.source = sc;
        var qs = Object.keys(p).length ? '?' + $.param(p) : '';
        $('#btnExportPdf').attr('href', _pdfBase + qs)
            .attr('title', qs ? 'Export filtered results' : 'Export PDF');
        $('#btnExportExcel').attr('href', _xlsxBase + qs)
            .attr('title', qs ? 'Export filtered results' : 'Export Excel');
        $('#btnExportPdf, #btnExportExcel').toggleClass('btn-export-filtered', Object.keys(p).length > 0);
    }

    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())  n++;
        if ($('#typeFilter').val())   n++;
        if ($('#statusFilter').val()) n++;
        if ($('#expiryFilter').val()) n++;
        if ($('#sourceFilter').val()) n++;
        const badge = document.getElementById('filterBadge');
        const chip  = document.getElementById('headerFilterChip');
        const chipN = document.getElementById('headerFilterCount');
        if (n > 0) {
            badge.textContent = n + (n === 1 ? ' filter active' : ' filters active');
            badge.style.display = '';
            chipN.textContent = n;
            chip.style.display = '';
        } else {
            badge.style.display = 'none';
            chip.style.display  = 'none';
        }
        _syncExportUrls();
    }
    window.toggleFilters = function (key) {
        const panel  = document.getElementById('filterPanel');
        const isOpen = panel.style.display !== 'none';
        panel.style.display = isOpen ? 'none' : 'block';
        document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
        localStorage.setItem('fp_' + key, isOpen ? '0' : '1');
    };
    window.quickFilter = function (filterId, value) {
        if (filterId === 'sourceFilter') {
            $('#sourceFilter').val(value);
            $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === value); });
        } else {
            if (value === null)            { $('#' + filterId).val(null).trigger('change'); }
            else if (Array.isArray(value)) { $('#' + filterId).val(value).trigger('change'); }
            else                           { $('#' + filterId).val(value).trigger('change'); }
            if (document.getElementById('filterPanel').style.display === 'none') {
                document.getElementById('filterPanel').style.display = 'block';
                document.getElementById('filterToggleText').textContent = 'Hide Filters';
                localStorage.setItem('fp_businesses', '1');
            }
        }
        saveToUrl(); table.ajax.reload();
    };
    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || localStorage.getItem('fp_businesses') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();

    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#typeFilter, #statusFilter, #expiryFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#typeFilter, #statusFilter').val(null).trigger('change');
        $('#expiryFilter').val(null).trigger('change');
        $('#sourceFilter').val(null);
        $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === ''); });
        saveToUrl(); table.ajax.reload();
    });
});

/* ── Source chip helpers ──────────────────────────────────────────── */
function _syncChip($chip, isActive) {
    var src = $chip.data('src');
    $chip.toggleClass('src-chip-active', isActive);
    if (src === '') {
        $chip.css({ background: isActive ? 'var(--navy)' : '#fff',
                    color:       isActive ? '#fff'        : 'var(--text-muted)',
                    borderColor: isActive ? 'var(--navy)' : 'var(--border)' });
    } else if (src === 'portal') {
        $chip.css({ background: isActive ? '#1d4ed8' : '#eff6ff',
                    color:       isActive ? '#fff'    : '#1d4ed8',
                    borderColor: isActive ? '#1d4ed8' : '#bfdbfe' });
    } else {
        $chip.css({ background: isActive ? 'var(--navy)'  : 'var(--surface)',
                    color:       isActive ? '#fff'         : 'var(--text-muted)',
                    borderColor: isActive ? 'var(--navy)'  : 'var(--border)' });
    }
}

$(document).on('click', '.src-chip', function () {
    var src = $(this).data('src');
    $('.src-chip').each(function () { _syncChip($(this), $(this).data('src') === src); });
    $('#sourceFilter').val(src || null);
    const url = new URL(window.location);
    url.searchParams.delete('source');
    if (src) url.searchParams.set('source', src);
    history.replaceState({}, '', url);
    var n = 0;
    if ($('#searchInput').val())  n++;
    if ($('#typeFilter').val())   n++;
    if ($('#statusFilter').val()) n++;
    if ($('#expiryFilter').val()) n++;
    if (src) n++;
    var badge = document.getElementById('filterBadge');
    var chip  = document.getElementById('headerFilterChip');
    var chipN = document.getElementById('headerFilterCount');
    if (n > 0) { badge.textContent = n + (n === 1 ? ' filter active' : ' filters active'); badge.style.display = ''; chipN.textContent = n; chip.style.display = ''; }
    else { badge.style.display = 'none'; chip.style.display = 'none'; }
    $('#businessesTable').DataTable().ajax.reload();
});

/* ── Issue Permit Modal (portal) ─────────────────────────────────── */
window.closeBizIssueModal = function () {
    document.getElementById('bizIssueModal').style.display = 'none';
    window._bizIssueUrl = null;
};

window.saveBizIssue = function () {
    const permitDate = document.getElementById('bizIssuePermitDate').value;
    const expiryDate = document.getElementById('bizIssueExpiryDate').value;
    const errEl      = document.getElementById('bizIssueError');
    if (!permitDate || !expiryDate) {
        errEl.textContent   = 'Please fill in both permit date and expiry date.';
        errEl.style.display = '';
        return;
    }
    const btn    = document.getElementById('bizIssueSaveBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Issuing…';
    errEl.style.display = 'none';

    axios.post(window._bizIssueUrl, {
        permit_date: permitDate,
        expiry_date: expiryDate,
        fee_paid:    document.getElementById('bizIssueFee').value  || null,
        or_number:   document.getElementById('bizIssueOR').value   || null,
        _token:      '{{ csrf_token() }}',
    })
    .then(function (res) {
        closeBizIssueModal();
        if (window._bizTable) window._bizTable.ajax.reload(null, false);
        bmsToast(res.data.message || 'Permit issued.', 'success');
        if (window.refreshPortalBadges) window.refreshPortalBadges();
    })
    .catch(function (err) {
        errEl.textContent   = err.response?.data?.message || 'Failed to issue permit.';
        errEl.style.display = '';
    })
    .finally(function () {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-stamp"></i> Issue Permit';
    });
};

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && document.getElementById('bizIssueModal').style.display === 'flex') {
        closeBizIssueModal();
    }
});

/* ── Business Quick View Panel ────────────────────────────────────── */
function openBizPanel(url) {
    const panel = document.getElementById('bizQvPanel');
    const body  = document.getElementById('bizQvBody');
    panel.style.display = 'flex';
    requestAnimationFrame(() => { panel.style.opacity = '1'; });
    body.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                    height:200px;gap:12px">
            <i class="fas fa-spinner fa-spin" style="font-size:24px;color:var(--gold)"></i>
            <span style="font-size:13px;color:var(--text-muted)">Loading permit…</span>
        </div>`;

    axios.get(url)
        .then(function ({ data: b }) {
            const expBadge = b.expiry_status === 'overdue'
                ? `<span class="badge badge-red"><i class="fas fa-triangle-exclamation"></i> Overdue ${b.expiry_days ? '— ' + b.expiry_days + 'd ago' : ''}</span>`
                : b.expiry_status === 'expiring_soon'
                    ? `<span class="badge badge-yellow"><i class="fas fa-clock"></i> Expiring in ${b.expiry_days ?? '?'} days</span>`
                    : '';
            const statusCls = { Active: 'badge-green', Expired: 'badge-red', Suspended: 'badge-yellow', Cancelled: 'badge-gray', Pending: 'badge-yellow', 'For Review': 'badge-blue' };

            body.innerHTML = `
                <div style="padding:18px 20px 14px;border-bottom:1px solid var(--border)">
                    <div style="font-size:16px;font-weight:700;color:var(--navy)">${b.business_name}</div>
                    <div style="font-size:12px;color:var(--text-muted);margin-top:2px">${b.permit_number}</div>
                    <div style="display:flex;gap:8px;margin-top:8px;flex-wrap:wrap">
                        <span class="badge badge-navy"><i class="fas fa-store"></i> ${b.business_type}</span>
                        <span class="badge ${statusCls[b.status] || 'badge-gray'}">${b.status}</span>
                        ${expBadge}
                    </div>
                </div>
                <div style="padding:14px 20px">
                    <div style="display:grid;gap:10px">
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Address</div>
                            <div style="color:var(--text)">${b.business_address}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Owner</div>
                            <div style="color:var(--text);font-weight:600">${b.owner_name}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Contact</div>
                            <div style="color:var(--text)">${b.owner_contact}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px;padding-top:10px;border-top:1px solid var(--border)">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Permit Date</div>
                            <div style="color:var(--text)">${b.permit_date}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Expiry Date</div>
                            <div style="color:${b.expiry_status === 'overdue' ? 'var(--crimson)' : b.expiry_status === 'expiring_soon' ? '#b45309' : 'var(--text)'};font-weight:${b.expiry_status !== 'valid' ? '700' : '400'}">${b.expiry_date}</div>
                        </div>
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Issued By</div>
                            <div style="color:var(--text)">${b.issued_by}</div>
                        </div>
                        ${b.remarks && b.remarks !== '—' ? `
                        <div style="display:flex;gap:10px;font-size:13px">
                            <div style="min-width:110px;color:var(--text-subtle);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.04em">Remarks</div>
                            <div style="color:var(--text-muted);font-style:italic">${b.remarks}</div>
                        </div>` : ''}
                    </div>
                </div>
                <div style="padding:12px 20px;border-top:1px solid var(--border);
                            display:flex;gap:8px;background:var(--surface2);margin-top:auto">
                    <a href="${b.show_url}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                        <i class="fas fa-eye"></i> Full Details
                    </a>
                    <a href="${b.edit_url}" class="btn btn-secondary btn-sm btn-icon" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                </div>`;
        })
        .catch(function () {
            body.innerHTML = `<div style="padding:32px;text-align:center;color:var(--crimson)">
                <i class="fas fa-exclamation-circle" style="font-size:28px;opacity:.5;display:block;margin-bottom:10px"></i>
                Could not load business data.
            </div>`;
        });
}

function closeBizPanel() {
    const panel = document.getElementById('bizQvPanel');
    panel.style.opacity = '0';
    setTimeout(() => { panel.style.display = 'none'; }, 200);
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeBizPanel(); closeBizModal(); }
});

/* ── Business Status Modal ────────────────────────────────────────── */
let _bizModalId = null;
let _bizModalEmail = null;

$(document).on('click', '.biz-status-btn', function () {
    _bizModalId    = $(this).data('id');
    _bizModalEmail = $(this).data('email') || '';
    const num      = $(this).data('num');
    const status   = $(this).data('status');

    document.getElementById('bizModalNum').textContent = num;
    document.getElementById('bizModalStatus').value    = status;
    document.getElementById('bizModalNotes').value     = '';
    document.getElementById('bizModalEmailNote').style.display = _bizModalEmail ? '' : 'none';

    const modal = document.getElementById('bizStatusModal');
    modal.style.display = 'flex';
    requestAnimationFrame(() => { modal.style.opacity = '1'; });
});

function closeBizModal() {
    const modal = document.getElementById('bizStatusModal');
    modal.style.display = 'none';
    _bizModalId = null;
}

document.getElementById('bizModalSave').addEventListener('click', function () {
    if (!_bizModalId) return;

    const btn      = this;
    const spinner  = document.getElementById('bizModalSpinner');
    const newStatus = document.getElementById('bizModalStatus').value;
    const notes     = document.getElementById('bizModalNotes').value;

    btn.disabled       = true;
    spinner.style.display = '';

    axios.patch(`/businesses/${_bizModalId}/status`, { status: newStatus, notes: notes })
        .then(function (res) {
            bmsToast(res.data.message || 'Status updated.', 'success');
            closeBizModal();
            table.ajax.reload(null, false);
        })
        .catch(function (err) {
            const msg = err.response?.data?.message || 'Could not update status.';
            bmsToast(msg, 'error');
        })
        .finally(function () {
            btn.disabled          = false;
            spinner.style.display = 'none';
        });
});

</script>
@endpush
