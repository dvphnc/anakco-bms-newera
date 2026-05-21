@extends('layouts.app')
@section('title', 'Portal Appointments')
@section('page-title', 'Portal Appointments')
@section('page-subtitle', 'Document requests & business permit appointments from the Resident Portal')
@section('content')

<div class="page-header">
    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
        <div>
            <h1 class="page-title">Portal Appointments</h1>
            <p class="page-subtitle">Document requests &amp; business permit appointments from the Resident Portal</p>
        </div>
    </div>
    <div class="page-actions">
        <a href="{{ route('portal.index') }}" class="btn btn-secondary" target="_blank">
            <i class="fas fa-external-link-alt"></i> View Portal
        </a>
    </div>
</div>

{{-- Stat cards --}}
@php
    $aptCounts = [];
    foreach($statuses as $s) {
        $aptCounts[$s] = \App\Models\DocumentAppointment::where('status', $s)->count();
    }
    $pendingCount  = $aptCounts['Pending']  ?? 0;
    $totalCount    = \App\Models\DocumentAppointment::count();
    $releasedCount = $aptCounts['Released'] ?? 0;
    $readyCount    = $aptCounts['Ready']    ?? 0;
    $bizPending     = \App\Models\Business::where('source','portal')->whereIn('status',['Pending','For Review'])->count();
@endphp
<div class="grid-4 mb-6" style="grid-template-columns:repeat(6,1fr)">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-file-lines"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statApptTotal">{{ number_format($totalCount) }}</div>
            <div class="stat-label">Document Requests</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Pending')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-hourglass-half"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptPending">{{ number_format($pendingCount) }}</div>
            <div class="stat-label">Doc. Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Ready')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-box-open"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptReady">{{ number_format($readyCount) }}</div>
            <div class="stat-label">Ready for Pick-up</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="aptQuickFilter('statusFilter','Released')">
        <div class="stat-icon" style="background:rgba(13,33,68,0.08);color:var(--navy)"><i class="fas fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statAptReleased">{{ number_format($releasedCount) }}</div>
            <div class="stat-label">Released</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="bizQuickFilter('bizStatusFilter','Pending')">
        <div class="stat-icon" style="background:rgba(200,134,26,0.10);color:var(--gold)"><i class="fas fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statBizPending">{{ number_format($bizPending) }}</div>
            <div class="stat-label">Biz. Permit Pending</div>
        </div>
    </div>
    <div class="stat-card" style="cursor:pointer" onclick="blotterQuickFilter('blotterStatusFilter','Pending')">
        <div class="stat-icon" style="background:rgba(220,38,38,0.08);color:#dc2626"><i class="fas fa-shield-halved"></i></div>
        <div class="stat-info">
            <div class="stat-number" id="statBlotterPending">{{ number_format($blotterPending) }}</div>
            <div class="stat-label">Blotter Pending</div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     SECTION 1 — DOCUMENT REQUEST APPOINTMENTS
════════════════════════════════════════════════════════════ --}}

{{-- Filter Bar (Document) --}}
<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('appointments')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-file-lines"></i> Document Request Appointments</span>
            <span id="filterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('appointments')">
            <i class="fas fa-sliders" id="filterToggleIcon"></i>
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
                               placeholder="Resident name, appointment number…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Status</label>
                    <select id="statusFilter">
                        <option value=""></option>
                        @foreach($statuses as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Document Type</label>
                    <select id="docTypeFilter">
                        <option value=""></option>
                        @foreach($documentTypes as $dt)
                            <option value="{{ $dt }}">{{ $dt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <button type="button" id="resetBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-xmark"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Document Appointments Table --}}
    <div class="table-responsive">
        <table id="appointmentsTable" style="width:100%">
            <thead>
                <tr>
                    <th>Appt. No.</th>
                    <th>Resident</th>
                    <th>Document Type</th>
                    <th>Preferred Date</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     SECTION 2 — BUSINESS PERMIT APPOINTMENTS
════════════════════════════════════════════════════════════ --}}

<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('biz')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-store"></i> Business Permit Appointments</span>
            <span id="bizFilterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('biz')">
            <i class="fas fa-sliders"></i>
            <span id="bizFilterToggleText">Show Filters</span>
        </button>
    </div>
    <div id="bizFilterPanel" style="display:none">
        <div class="card-body" style="padding:20px 22px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="bizSearchInput" class="form-control" style="padding-left:32px"
                               placeholder="Business name, owner, permit number…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Status</label>
                    <select id="bizStatusFilter">
                        <option value=""></option>
                        <option value="Pending">Pending</option>
                        <option value="For Review">For Review</option>
                        <option value="Active">Active</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Business Type</label>
                    <select id="bizTypeFilter">
                        <option value=""></option>
                        @foreach($businessTypes as $bt)
                            <option value="{{ $bt }}">{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <button type="button" id="bizResetBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-xmark"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>

    {{-- Business Appointments Table --}}
    <div class="table-responsive">
        <table id="bizTable" style="width:100%">
            <thead>
                <tr>
                    <th>Permit No.</th>
                    <th>Owner</th>
                    <th>Business</th>
                    <th>Type</th>
                    <th>Appt. Date</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     SECTION 3 — BLOTTER REPORTS FROM PORTAL
════════════════════════════════════════════════════════════ --}}

<div class="card mb-6">
    <div class="card-header" style="cursor:pointer" onclick="toggleFilters('blotter')">
        <div style="display:flex;align-items:center;gap:10px">
            <span class="card-title"><i class="fas fa-shield-halved"></i> Blotter Reports from Portal</span>
            <span id="blotterFilterBadge" class="badge badge-gold" style="display:none"></span>
        </div>
        <button type="button" class="btn btn-gold btn-sm" onclick="event.stopPropagation();toggleFilters('blotter')">
            <i class="fas fa-sliders"></i>
            <span id="blotterFilterToggleText">Show Filters</span>
        </button>
    </div>
    <div id="blotterFilterPanel" style="display:none">
        <div class="card-body" style="padding:20px 22px">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Search</label>
                    <div style="position:relative">
                        <i class="fas fa-search" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:12px;pointer-events:none;z-index:1"></i>
                        <input type="text" id="blotterSearchInput" class="form-control" style="padding-left:32px"
                               placeholder="Complainant, case number, location…">
                    </div>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Status</label>
                    <select id="blotterStatusFilter">
                        <option value=""></option>
                        <option value="Pending">Pending</option>
                        <option value="Active">Active</option>
                        <option value="Under Investigation">Under Investigation</option>
                        <option value="Mediated">Mediated</option>
                        <option value="Settled">Settled</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:span 2">
                    <label class="form-label">Incident Type</label>
                    <select id="blotterTypeFilter">
                        <option value=""></option>
                        @foreach($incidentTypes as $it)
                            <option value="{{ $it }}">{{ $it }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="display:flex;justify-content:flex-end;margin-top:16px;padding-top:16px;border-top:1px solid var(--border)">
                <button type="button" id="blotterResetBtn" class="btn btn-secondary btn-sm">
                    <i class="fas fa-xmark"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="blotterTable" style="width:100%">
            <thead>
                <tr>
                    <th>Case No.</th>
                    <th>Complainant</th>
                    <th>Incident Type</th>
                    <th>Location / Date</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th style="text-align:right">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODALS
════════════════════════════════════════════════════════════ --}}

{{-- Issue Document Modal --}}
<div id="aptConvertModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeConvertModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-file-circle-check" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Issue Document Record</span>
            </div>
            <button onclick="closeConvertModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:12px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Appointment No.</div>
                        <div id="cvtNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Document Type</div>
                        <div id="cvtType" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Resident</div>
                        <div id="cvtName" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div id="cvtPurposeRow" style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Purpose</div>
                        <div id="cvtPurpose" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">Fee Paid (₱) <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="number" id="cvtFee" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">O.R. Number <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span></label>
                    <input type="text" id="cvtOR" class="form-control" placeholder="e.g. 2026-00123">
                </div>
            </div>
            <div id="cvtError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Creates a <strong style="color:var(--navy)">Released</strong> document record in Document Issuance and automatically marks this appointment as Released.</span>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeConvertModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="cvtSaveBtn" onclick="saveConvert()" class="btn btn-primary">
                    <i class="fas fa-file-circle-check"></i> Issue Document
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Document Appointment Status Modal --}}
<div id="aptStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeAptModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:440px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Appointment Status</span>
            </div>
            <button onclick="closeAptModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Appointment: <strong id="aptModalNum" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="aptModalStatus" class="form-control">
                    @foreach($statuses as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional)</span></label>
                <textarea id="aptModalNotes" class="form-control" rows="3"
                          placeholder="e.g., Document is ready for pick-up…"></textarea>
            </div>
            <div id="aptStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeAptModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="aptStatusSaveBtn" onclick="saveAptStatus()" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Save Status
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Activate Blotter Case Modal --}}
<div id="blotterActivateModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBlotterActivateModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:460px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-shield-halved" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Activate Blotter Case</span>
            </div>
            <button onclick="closeBlotterActivateModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:18px">
            {{-- Case summary --}}
            <div style="background:var(--navy-pale,#f0f4fb);border:1px solid var(--navy-border,#d0daea);
                        border-radius:var(--radius-sm);padding:14px 16px;margin-bottom:16px">
                <div style="display:grid;grid-template-columns:1fr 1fr;row-gap:10px;column-gap:16px">
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Case No.</div>
                        <div id="blotterActivateNum" style="font-size:13px;font-weight:700;color:var(--navy);font-family:monospace"></div>
                    </div>
                    <div>
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Incident Type</div>
                        <div id="blotterActivateType" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1;border-top:1px solid var(--border);padding-top:10px">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Complainant</div>
                        <div id="blotterActivateComplainant" style="font-size:13px;font-weight:600;color:var(--navy)"></div>
                    </div>
                    <div style="grid-column:1/-1">
                        <div style="font-size:10.5px;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:3px">Incident Date</div>
                        <div id="blotterActivateDate" style="font-size:13px;color:var(--text)"></div>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom:14px">
                <label class="form-label" style="font-size:12.5px">
                    Staff Notes
                    <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span>
                </label>
                <textarea id="blotterActivateNotes" class="form-control" rows="3"
                          placeholder="e.g., Verified with complainant; proceeding with mediation…"></textarea>
            </div>

            <div id="blotterActivateError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>

            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Sets the case to <strong style="color:var(--navy)">Active</strong> and makes it visible as a real case record in the Blotter section.</span>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeBlotterActivateModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="blotterActivateSaveBtn" onclick="saveBlotterActivate()" class="btn btn-success">
                    <i class="fas fa-shield-halved"></i> Activate Case
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Issue Business Permit Modal --}}
<div id="bizIssueModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBizIssueModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:480px;
                box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        {{-- Header --}}
        <div style="background:var(--navy);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:9px">
                <i class="fas fa-file-certificate" style="color:var(--gold);font-size:13px"></i>
                <span style="font-size:13.5px;font-weight:700;color:#fff">Issue Business Permit</span>
            </div>
            <button onclick="closeBizIssueModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:28px;height:28px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer;font-size:12px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:18px">

            {{-- Appointment summary (read-only) --}}
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

            {{-- Permit dates --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">
                        Permit Date <span style="color:var(--crimson)">*</span>
                    </label>
                    <input type="date" id="bizIssuePermitDate" class="form-control">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">
                        Expiry Date <span style="color:var(--crimson)">*</span>
                    </label>
                    <input type="date" id="bizIssueExpiryDate" class="form-control">
                </div>
            </div>

            {{-- Fee & OR --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">
                        Fee Paid (₱)
                        <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span>
                    </label>
                    <input type="number" id="bizIssueFee" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" style="font-size:12.5px">
                        O.R. Number
                        <span style="font-weight:400;color:var(--text-subtle);font-size:11.5px">— optional</span>
                    </label>
                    <input type="text" id="bizIssueOR" class="form-control" placeholder="e.g. 2026-00123">
                </div>
            </div>

            <div id="bizIssueError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>

            {{-- Info note --}}
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:12px;
                        color:var(--text-subtle);background:#f8f9fb;border:1px solid var(--border);
                        border-radius:var(--radius-sm);padding:10px 12px;margin-bottom:16px">
                <i class="fas fa-circle-info" style="color:var(--navy);opacity:.5;margin-top:1px;flex-shrink:0"></i>
                <span>Sets the permit to <strong style="color:var(--navy)">Active</strong> and makes it visible as a real permit record in the Business Permits section.</span>
            </div>

            {{-- Actions --}}
            <div style="display:flex;justify-content:flex-end;gap:10px">
                <button type="button" onclick="closeBizIssueModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="bizIssueSaveBtn" onclick="saveBizIssue()" class="btn btn-success">
                    <i class="fas fa-file-certificate"></i> Issue Permit
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Business Permit Status Modal --}}
<div id="bizStatusModal"
     style="display:none;position:fixed;inset:0;background:rgba(9,20,40,0.45);z-index:9500;
            align-items:center;justify-content:center;backdrop-filter:blur(3px)"
     onclick="if(event.target===this)closeBizModal()">
    <div style="background:var(--surface);border-radius:var(--radius-lg);width:100%;max-width:440px;
                padding:0;box-shadow:0 20px 60px rgba(0,0,0,0.22);overflow:hidden">
        <div style="background:var(--navy);padding:16px 20px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-rotate" style="color:var(--gold);font-size:14px"></i>
                <span style="font-size:14px;font-weight:700;color:#fff">Update Business Permit Status</span>
            </div>
            <button onclick="closeBizModal()"
                    style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                           border-radius:var(--radius-sm);width:30px;height:30px;display:flex;
                           align-items:center;justify-content:center;color:rgba(255,255,255,.7);cursor:pointer">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div style="padding:20px">
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:4px">
                Permit No.: <strong id="bizModalNum" style="color:var(--navy)"></strong>
            </p>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">
                Business: <strong id="bizModalBiz" style="color:var(--navy)"></strong>
            </p>
            <div class="form-group">
                <label class="form-label">New Status <span style="color:var(--crimson)">*</span></label>
                <select id="bizModalStatus" class="form-control">
                    <option value="Pending">Pending</option>
                    <option value="For Review">For Review</option>
                    <option value="Active">Active</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Notes <span style="font-size:12px;color:var(--text-subtle);font-weight:400">(optional)</span></label>
                <textarea id="bizModalNotes" class="form-control" rows="3"
                          placeholder="e.g., Please bring your DTI registration…"></textarea>
            </div>
            <div id="bizStatusError" style="display:none;font-size:13px;color:var(--crimson);
                 padding:8px 12px;background:var(--crimson-pale);border-radius:var(--radius-sm);
                 border:1px solid var(--crimson-border);margin-bottom:12px"></div>
            <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:4px">
                <button type="button" onclick="closeBizModal()" class="btn btn-secondary">Cancel</button>
                <button type="button" id="bizStatusSaveBtn" onclick="saveBizStatus()" class="btn btn-primary">
                    <i class="fas fa-floppy-disk"></i> Save Status
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
.main-content { background: #F8F9FA; }
.stat-card { background: #FFFFFF !important; box-shadow: 0 1px 4px rgba(13,33,68,0.07), 0 4px 16px rgba(13,33,68,0.04); }
.stat-label { font-size: 12px; color: var(--text-subtle); font-weight: 500; letter-spacing: 0.02em; }
.stat-number { font-size: 28px; font-weight: 700; color: var(--navy); line-height: 1.1; }

#appointmentsTable_wrapper .dataTables_length,
#appointmentsTable_wrapper .dataTables_filter { display:none; }
#appointmentsTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate { padding:12px 20px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#appointmentsTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

#bizTable_wrapper .dataTables_length,
#bizTable_wrapper .dataTables_filter { display:none; }
#bizTable_wrapper .dataTables_info { font-size:13px;color:var(--text-muted);padding:12px 20px; }
#bizTable_wrapper .dataTables_paginate { padding:12px 20px; }
#bizTable_wrapper .dataTables_paginate .paginate_button { padding:4px 10px;border-radius:6px;font-size:13px;cursor:pointer;border:1px solid var(--border) !important;background:white !important;color:var(--text) !important;margin:0 2px; }
#bizTable_wrapper .dataTables_paginate .paginate_button.current { background:var(--navy) !important;color:white !important;border-color:var(--navy) !important; }
#bizTable_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background:var(--navy-pale) !important;color:var(--navy) !important; }

.btn-success { background:#16a34a; color:#fff; border-color:#16a34a; }
.btn-success:disabled { opacity:.65; cursor:not-allowed; }

#filterPanel .select2-container,
#bizFilterPanel .select2-container { width: 100% !important; }
#filterPanel .select2-container--default .select2-selection--single,
#filterPanel .select2-container--default .select2-selection--multiple,
#bizFilterPanel .select2-container--default .select2-selection--single,
#bizFilterPanel .select2-container--default .select2-selection--multiple {
    border: 1px solid var(--border); border-radius: var(--radius-sm);
    background: var(--surface); min-height: 38px;
}
#filterPanel .select2-container--default .select2-selection--single,
#bizFilterPanel .select2-container--default .select2-selection--single {
    padding: 0 32px 0 10px; display: flex; align-items: center;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__rendered,
#bizFilterPanel .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: var(--text); font-size: 13.5px; padding: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: normal;
}
#filterPanel .select2-container--default .select2-selection--single .select2-selection__placeholder,
#bizFilterPanel .select2-container--default .select2-selection--single .select2-selection__placeholder { color: var(--text-subtle); }
#filterPanel .select2-container--default .select2-selection--single .select2-selection__arrow,
#bizFilterPanel .select2-container--default .select2-selection--single .select2-selection__arrow { height: 100%; top: 0; right: 8px; }
</style>
<script>
$(document).ready(function () {

    /* ── Init Select2 for both filter panels ──────────────────────────── */
    function initSelect2InPanel($panel) {
        var $fp = $panel;
        var _fpW = $fp.parent().width();
        $fp.css({ display: 'block', visibility: 'hidden', position: 'absolute', 'z-index': '-1', width: _fpW + 'px' });
        const s2 = { dropdownParent: $('body'), allowClear: true, width: '100%',
                     minimumResultsForSearch: 0, language: { noResults: () => 'No matches' } };
        $panel.find('select').each(function () {
            var placeholder = $(this).data('placeholder') || 'Select…';
            $(this).select2($.extend({}, s2, { placeholder: placeholder }));
        });
        $fp.css({ display: 'none', visibility: '', position: '', 'z-index': '', width: '' });
    }

    $('#statusFilter').data('placeholder', 'All statuses…');
    $('#docTypeFilter').data('placeholder', 'All document types…');
    $('#bizStatusFilter').data('placeholder', 'All statuses…');
    $('#bizTypeFilter').data('placeholder', 'All types…');
    $('#blotterStatusFilter').data('placeholder', 'All statuses…');
    $('#blotterTypeFilter').data('placeholder', 'All types…');

    initSelect2InPanel($('#filterPanel'));
    initSelect2InPanel($('#bizFilterPanel'));
    initSelect2InPanel($('#blotterFilterPanel'));

    /* ─────────────────────────────────────────────────────────────────────
     |  TABLE 1 — DOCUMENT APPOINTMENTS
     |────────────────────────────────────────────────────────────────────── */
    var table = $('#appointmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('appointments.index') }}',
            data: function (d) {
                d.status        = $('#statusFilter').val();
                d.document_type = $('#docTypeFilter').val();
                d.search        = { value: $('#searchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',    name: 'appointment_number' },
            { data: 'resident_col',  name: 'resident_name',    orderable: false },
            { data: 'document_col',  name: 'document_type',    orderable: false },
            { data: 'date_col',      name: 'preferred_date' },
            { data: 'submitted_col', name: 'created_at' },
            { data: 'status_col',    name: 'status' },
            { data: 'actions',       name: 'actions', orderable: false, searchable: false },
        ],
        order: [[3, 'asc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#appointmentsTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-file-lines"></i><p>No document appointments found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No appointments match your filters. <a href="#" onclick="document.getElementById(\'resetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ─────────────────────────────────────────────────────────────────────
     |  TABLE 2 — BUSINESS PERMIT APPOINTMENTS
     |────────────────────────────────────────────────────────────────────── */
    var bizTable = $('#bizTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('appointments.bizData') }}',
            data: function (d) {
                d.status        = $('#bizStatusFilter').val();
                d.business_type = $('#bizTypeFilter').val();
                d.search        = { value: $('#bizSearchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',    name: 'permit_number',  width: '130px' },
            { data: 'owner_col',     name: 'owner_name',     orderable: false },
            { data: 'biz_col',       name: 'business_name' },
            { data: 'type_col',      name: 'business_type',  width: '120px', orderable: false },
            { data: 'appt_date_col', name: 'preferred_date', width: '110px' },
            { data: 'submitted_col', name: 'created_at',     width: '100px' },
            { data: 'status_col',    name: 'status',         width: '100px' },
            { data: 'actions',       name: 'actions', orderable: false, searchable: false, width: '140px' },
        ],
        order: [[4, 'asc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#bizTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-store"></i><p>No business permit appointments found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No appointments match your filters. <a href="#" onclick="document.getElementById(\'bizResetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ─────────────────────────────────────────────────────────────────────
     |  TABLE 3 — BLOTTER PORTAL REPORTS
     |────────────────────────────────────────────────────────────────────── */
    var blotterTable = $('#blotterTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('appointments.blotterData') }}',
            data: function (d) {
                d.status        = $('#blotterStatusFilter').val();
                d.incident_type = $('#blotterTypeFilter').val();
                d.search        = { value: $('#blotterSearchInput').val() };
            }
        },
        columns: [
            { data: 'number_col',      name: 'case_number',     width: '140px' },
            { data: 'complainant_col', name: 'complainant_name', orderable: false },
            { data: 'type_col',        name: 'incident_type',   width: '130px', orderable: false },
            { data: 'incident_col',    name: 'incident_location', orderable: false },
            { data: 'submitted_col',   name: 'created_at',      width: '100px' },
            { data: 'status_col',      name: 'status',          width: '110px' },
            { data: 'actions',         name: 'actions', orderable: false, searchable: false, width: '160px' },
        ],
        order: [[4, 'desc']],
        pageLength: 15,
        drawCallback: function () {
            if (typeof tippy !== 'undefined') {
                tippy('#blotterTable [data-tippy-content]', {
                    theme: 'bms', placement: 'top', arrow: true, animation: 'shift-away', duration: [150, 100]
                });
            }
        },
        language: {
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading…',
            emptyTable:  '<div class="empty-state"><i class="fas fa-shield-halved"></i><p>No blotter reports from portal found.</p></div>',
            zeroRecords: '<div class="empty-state"><i class="fas fa-search"></i><p>No reports match your filters. <a href="#" onclick="document.getElementById(\'blotterResetBtn\').click();return false" style="color:var(--navy);font-weight:600">Clear filters</a></p></div>',
        }
    });

    /* ── Axios DELETE — Document ──────────────────────────────────────── */
    $('#appointmentsTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault(); e.stopImmediatePropagation();
        const btn = $(this), form = btn.closest('form'), url = form.attr('action');
        bmsConfirm({ title: form.data('confirm-title') || 'Delete Appointment', message: form.data('confirm'), ok: 'Delete' }, function () {
            const icon = btn.find('i'), orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
            btn.prop('disabled', true);
            axios.delete(url)
                .then(function (res) {
                    table.row(form.closest('tr')).remove().draw(false);
                    bmsStatDecrement('statApptTotal');
                    bmsToast(res.data.message || 'Appointment deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete appointment.', 'error');
                });
        });
    });

    /* ── Axios DELETE — Business ──────────────────────────────────────── */
    $('#bizTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault(); e.stopImmediatePropagation();
        const btn = $(this), form = btn.closest('form'), url = form.attr('action');
        bmsConfirm({ title: form.data('confirm-title') || 'Delete Business Appointment', message: form.data('confirm'), ok: 'Delete' }, function () {
            const icon = btn.find('i'), orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
            btn.prop('disabled', true);
            axios.delete(url)
                .then(function (res) {
                    bizTable.row(form.closest('tr')).remove().draw(false);
                    bmsToast(res.data.message || 'Business appointment deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete.', 'error');
                });
        });
    });

    /* ── Axios DELETE — Blotter ───────────────────────────────────────── */
    $('#blotterTable').on('click', 'form[data-confirm] button[type="submit"]', function (e) {
        e.preventDefault(); e.stopImmediatePropagation();
        const btn = $(this), form = btn.closest('form'), url = form.attr('action');
        bmsConfirm({ title: form.data('confirm-title') || 'Delete Blotter Report', message: form.data('confirm'), ok: 'Delete' }, function () {
            const icon = btn.find('i'), orig = icon.attr('class');
            icon.attr('class', 'fas fa-spinner fa-spin').css('color', 'var(--gold)');
            btn.prop('disabled', true);
            axios.delete(url)
                .then(function (res) {
                    blotterTable.row(form.closest('tr')).remove().draw(false);
                    bmsToast(res.data.message || 'Blotter report deleted.', 'success');
                })
                .catch(function () {
                    icon.attr('class', orig).css('color', '');
                    btn.prop('disabled', false);
                    bmsToast('Could not delete.', 'error');
                });
        });
    });

    /* ── Open blotter activate modal ──────────────────────────────────── */
    $('#blotterTable').on('click', '.blotter-activate-btn', function () {
        var $btn = $(this);
        document.getElementById('blotterActivateNum').textContent        = $btn.data('num');
        document.getElementById('blotterActivateType').textContent       = $btn.data('type');
        document.getElementById('blotterActivateComplainant').textContent = $btn.data('complainant');
        document.getElementById('blotterActivateDate').textContent       = $btn.data('date');
        document.getElementById('blotterActivateNotes').value            = '';
        document.getElementById('blotterActivateError').style.display    = 'none';
        window._blotterActivateUrl = $btn.data('url');
        document.getElementById('blotterActivateModal').style.display    = 'flex';
    });

    /* ── Open convert modal ───────────────────────────────────────────── */
    $('#appointmentsTable').on('click', '.apt-convert-btn', function () {
        var $btn = $(this);
        document.getElementById('cvtNum').textContent  = $btn.data('num');
        document.getElementById('cvtName').textContent = $btn.data('name');
        document.getElementById('cvtType').textContent = $btn.data('type');
        var purpose = $btn.data('purpose') || '';
        var purposeRow = document.getElementById('cvtPurposeRow');
        if (purpose) { document.getElementById('cvtPurpose').textContent = purpose; purposeRow.style.display = ''; }
        else { purposeRow.style.display = 'none'; }
        document.getElementById('cvtFee').value = '';
        document.getElementById('cvtOR').value  = '';
        document.getElementById('cvtError').style.display = 'none';
        window._cvtUrl   = $btn.data('url');
        window._cvtAptId = $btn.data('id');
        document.getElementById('aptConvertModal').style.display = 'flex';
    });

    /* ── Open doc status modal ────────────────────────────────────────── */
    $('#appointmentsTable').on('click', '.apt-status-btn', function () {
        _aptId        = $(this).data('id');
        _aptOldStatus = $(this).data('status');
        document.getElementById('aptModalNum').textContent    = $(this).data('num');
        document.getElementById('aptModalStatus').value       = _aptOldStatus;
        document.getElementById('aptModalNotes').value        = $(this).data('notes') || '';
        document.getElementById('aptStatusError').style.display = 'none';
        document.getElementById('aptStatusModal').style.display = 'flex';
    });

    /* ── Open biz issue modal ─────────────────────────────────────────── */
    $('#bizTable').on('click', '.biz-issue-btn', function () {
        var $btn = $(this);
        document.getElementById('bizIssueNum').textContent   = $btn.data('num');
        document.getElementById('bizIssueBiz').textContent   = $btn.data('biz');
        document.getElementById('bizIssueOwner').textContent = $btn.data('owner');
        document.getElementById('bizIssueAppt').textContent  = $btn.data('appt');

        // Default permit date = today, expiry = +1 year
        var today     = new Date();
        var nextYear  = new Date(today);
        nextYear.setFullYear(nextYear.getFullYear() + 1);
        var fmt = function (d) { return d.toISOString().slice(0,10); };
        document.getElementById('bizIssuePermitDate').value  = fmt(today);
        document.getElementById('bizIssueExpiryDate').value  = fmt(nextYear);
        document.getElementById('bizIssueFee').value         = '';
        document.getElementById('bizIssueOR').value          = '';
        document.getElementById('bizIssueError').style.display = 'none';

        window._bizIssueUrl = $btn.data('url');
        document.getElementById('bizIssueModal').style.display = 'flex';
    });

    /* ── Open biz status modal ────────────────────────────────────────── */
    $('#bizTable').on('click', '.biz-apt-status-btn', function () {
        _bizId        = $(this).data('id');
        _bizOldStatus = $(this).data('status');
        document.getElementById('bizModalNum').textContent     = $(this).data('num');
        document.getElementById('bizModalBiz').textContent     = $(this).data('biz');
        document.getElementById('bizModalStatus').value        = _bizOldStatus;
        document.getElementById('bizModalNotes').value         = '';
        document.getElementById('bizStatusError').style.display = 'none';
        document.getElementById('bizStatusModal').style.display = 'flex';
    });

    /* ── URL persistence (doc table) ──────────────────────────────────── */
    function saveToUrl() {
        const url = new URL(window.location);
        url.searchParams.delete('s');
        url.searchParams.delete('status');
        url.searchParams.delete('document_type');
        if ($('#searchInput').val())  url.searchParams.set('s', $('#searchInput').val());
        if ($('#statusFilter').val()) url.searchParams.set('status', $('#statusFilter').val());
        if ($('#docTypeFilter').val()) url.searchParams.set('document_type', $('#docTypeFilter').val());
        history.replaceState({}, '', url);
        updateBadge();
    }
    function loadFromUrl() {
        const p = new URLSearchParams(window.location.search);
        let any = false;
        if (p.get('s')) { $('#searchInput').val(p.get('s')); any = true; }
        if (p.get('status')) { $('#statusFilter').val(p.get('status')).trigger('change.select2'); any = true; }
        if (p.get('document_type')) { $('#docTypeFilter').val(p.get('document_type')).trigger('change.select2'); any = true; }
        return any;
    }
    function updateBadge() {
        let n = 0;
        if ($('#searchInput').val())  n++;
        if ($('#statusFilter').val()) n++;
        if ($('#docTypeFilter').val()) n++;
        const badge = document.getElementById('filterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter' : ' filters') + ' active'; badge.style.display = ''; }
        else { badge.style.display = 'none'; }
    }
    function updateBizBadge() {
        let n = 0;
        if ($('#bizSearchInput').val())  n++;
        if ($('#bizStatusFilter').val()) n++;
        if ($('#bizTypeFilter').val())   n++;
        const badge = document.getElementById('bizFilterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter' : ' filters') + ' active'; badge.style.display = ''; }
        else { badge.style.display = 'none'; }
    }

    function updateBlotterBadge() {
        let n = 0;
        if ($('#blotterSearchInput').val())  n++;
        if ($('#blotterStatusFilter').val()) n++;
        if ($('#blotterTypeFilter').val())   n++;
        const badge = document.getElementById('blotterFilterBadge');
        if (n > 0) { badge.textContent = n + (n === 1 ? ' filter' : ' filters') + ' active'; badge.style.display = ''; }
        else { badge.style.display = 'none'; }
    }

    window.toggleFilters = function (key) {
        if (key === 'biz') {
            const panel  = document.getElementById('bizFilterPanel');
            const isOpen = panel.style.display !== 'none';
            panel.style.display = isOpen ? 'none' : 'block';
            document.getElementById('bizFilterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
            localStorage.setItem('fp_biz', isOpen ? '0' : '1');
        } else if (key === 'blotter') {
            const panel  = document.getElementById('blotterFilterPanel');
            const isOpen = panel.style.display !== 'none';
            panel.style.display = isOpen ? 'none' : 'block';
            document.getElementById('blotterFilterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
            localStorage.setItem('fp_blotter', isOpen ? '0' : '1');
        } else {
            const panel  = document.getElementById('filterPanel');
            const isOpen = panel.style.display !== 'none';
            panel.style.display = isOpen ? 'none' : 'block';
            document.getElementById('filterToggleText').textContent = isOpen ? 'Show Filters' : 'Hide Filters';
            localStorage.setItem('fp_appointments', isOpen ? '0' : '1');
        }
    };

    const hasUrlFilters = loadFromUrl();
    if (hasUrlFilters || localStorage.getItem('fp_appointments') === '1') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
    }
    if (localStorage.getItem('fp_biz') === '1') {
        document.getElementById('bizFilterPanel').style.display = 'block';
        document.getElementById('bizFilterToggleText').textContent = 'Hide Filters';
    }
    if (localStorage.getItem('fp_blotter') === '1') {
        document.getElementById('blotterFilterPanel').style.display = 'block';
        document.getElementById('blotterFilterToggleText').textContent = 'Hide Filters';
    }
    updateBadge();
    updateBizBadge();
    updateBlotterBadge();

    /* ── Filters — doc ────────────────────────────────────────────────── */
    let debounce;
    $('#searchInput').on('input', function () { clearTimeout(debounce); debounce = setTimeout(() => { saveToUrl(); table.ajax.reload(); }, 380); });
    $('#statusFilter, #docTypeFilter').on('change', function () { saveToUrl(); table.ajax.reload(); });
    $('#resetBtn').on('click', function () {
        $('#searchInput').val('');
        $('#statusFilter, #docTypeFilter').val(null).trigger('change');
        saveToUrl(); table.ajax.reload();
    });

    /* ── Filters — biz ────────────────────────────────────────────────── */
    let bizDebounce;
    $('#bizSearchInput').on('input', function () { clearTimeout(bizDebounce); bizDebounce = setTimeout(() => { updateBizBadge(); bizTable.ajax.reload(); }, 380); });
    $('#bizStatusFilter, #bizTypeFilter').on('change', function () { updateBizBadge(); bizTable.ajax.reload(); });
    $('#bizResetBtn').on('click', function () {
        $('#bizSearchInput').val('');
        $('#bizStatusFilter, #bizTypeFilter').val(null).trigger('change');
        updateBizBadge(); bizTable.ajax.reload();
    });
});

window.aptQuickFilter = function (filterId, values) {
    $('#' + filterId).val(values).trigger('change');
    if (document.getElementById('filterPanel').style.display === 'none') {
        document.getElementById('filterPanel').style.display = 'block';
        document.getElementById('filterToggleText').textContent = 'Hide Filters';
        localStorage.setItem('fp_appointments', '1');
    }
    $('#appointmentsTable').DataTable().ajax.reload();
};

window.bizQuickFilter = function (filterId, values) {
    $('#' + filterId).val(values).trigger('change');
    if (document.getElementById('bizFilterPanel').style.display === 'none') {
        document.getElementById('bizFilterPanel').style.display = 'block';
        document.getElementById('bizFilterToggleText').textContent = 'Hide Filters';
        localStorage.setItem('fp_biz', '1');
    }
    $('#bizTable').DataTable().ajax.reload();
};

/* ── Convert Modal ────────────────────────────────────────────────── */
window._cvtUrl   = null;
window._cvtAptId = null;

function closeConvertModal() {
    document.getElementById('aptConvertModal').style.display = 'none';
    window._cvtUrl = null; window._cvtAptId = null;
}

function saveConvert() {
    if (!window._cvtUrl) return;
    var btn    = document.getElementById('cvtSaveBtn');
    var errDiv = document.getElementById('cvtError');
    var fee    = document.getElementById('cvtFee').value;
    var or_num = document.getElementById('cvtOR').value;
    errDiv.style.display = 'none';
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Issuing…';

    axios.post(window._cvtUrl, { fee_paid: fee || null, or_number: or_num || null })
        .then(function (res) {
            closeConvertModal();
            bmsToast(res.data.message, 'success');
            $('#appointmentsTable').DataTable().ajax.reload(null, false);
            setTimeout(function () {
                bmsConfirm({ title: 'Document Issued', message: res.data.doc_number + ' has been created. Open the document record now?', ok: 'Open Document' }, function () {
                    window.open(res.data.view_url, '_blank');
                });
            }, 400);
        })
        .catch(function (err) {
            var data = err.response?.data;
            var msg  = data?.errors ? Object.values(data.errors).flat().join(' ') : (data?.message || 'Failed to issue document.');
            if (err.response?.status === 422 && data?.view_url) {
                msg += ' <a href="' + data.view_url + '" target="_blank" style="color:var(--navy);font-weight:600">View it here →</a>';
            }
            errDiv.innerHTML = msg; errDiv.style.display = 'block';
        })
        .finally(function () {
            btn.disabled = false; btn.innerHTML = '<i class="fas fa-file-circle-check"></i> Issue Document';
        });
}

/* ── Doc Status Modal ─────────────────────────────────────────────── */
var _aptId = null, _aptOldStatus = null;
var _aptStatMap = { 'Pending': 'statAptPending', 'Ready': 'statAptReady', 'Released': 'statAptReleased' };

function closeAptModal() {
    document.getElementById('aptStatusModal').style.display = 'none';
    _aptId = null; _aptOldStatus = null;
}

function saveAptStatus() {
    if (!_aptId) return;
    const btn = document.getElementById('aptStatusSaveBtn');
    const errDiv = document.getElementById('aptStatusError');
    const newStatus = document.getElementById('aptModalStatus').value;
    const notes = document.getElementById('aptModalNotes').value;
    const oldStatus = _aptOldStatus;
    if (newStatus === oldStatus) { closeAptModal(); return; }
    errDiv.style.display = 'none';
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/appointments/' + _aptId + '/status', { status: newStatus, notes: notes })
        .then(function (res) {
            closeAptModal();
            if (res.data.counts) {
                Object.entries(res.data.counts).forEach(function ([s, n]) {
                    var elId = _aptStatMap[s];
                    if (!elId) return;
                    var el = document.getElementById(elId);
                    if (!el) return;
                    var prev = parseInt(el.textContent.replace(/,/g, ''), 10) || 0;
                    el.textContent = n.toLocaleString();
                    if (n !== prev) { el.style.transition = 'color .15s'; el.style.color = n > prev ? 'var(--gold)' : 'var(--crimson)'; setTimeout(() => el.style.color = '', 800); }
                });
            }
            bmsToast(res.data.message || 'Status updated.', 'success');
            $('#appointmentsTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            const data = err.response?.data;
            errDiv.textContent = data?.errors ? Object.values(data.errors).flat().join(' ') : (data?.message || 'Failed.');
            errDiv.style.display = 'block';
        })
        .finally(function () { btn.disabled = false; btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status'; });
}

/* ── Biz Issue Modal ──────────────────────────────────────────────── */
window._bizIssueUrl = null;

function closeBizIssueModal() {
    document.getElementById('bizIssueModal').style.display = 'none';
    window._bizIssueUrl = null;
}

function saveBizIssue() {
    if (!window._bizIssueUrl) return;
    var btn        = document.getElementById('bizIssueSaveBtn');
    var errDiv     = document.getElementById('bizIssueError');
    var permitDate = document.getElementById('bizIssuePermitDate').value;
    var expiryDate = document.getElementById('bizIssueExpiryDate').value;
    var fee        = document.getElementById('bizIssueFee').value;
    var orNum      = document.getElementById('bizIssueOR').value;

    errDiv.style.display = 'none';
    btn.disabled  = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Issuing…';

    axios.post(window._bizIssueUrl, {
        permit_date: permitDate,
        expiry_date: expiryDate,
        fee_paid:    fee   || null,
        or_number:   orNum || null,
    })
    .then(function (res) {
        closeBizIssueModal();
        bmsToast(res.data.message, 'success');

        // Sync biz pending stat card
        if (typeof res.data.biz_pending !== 'undefined') {
            var el = document.getElementById('statBizPending');
            if (el) {
                var prev = parseInt(el.textContent.replace(/,/g,''), 10) || 0;
                el.textContent = res.data.biz_pending.toLocaleString();
                if (res.data.biz_pending !== prev) {
                    el.style.transition = 'color .15s';
                    el.style.color = 'var(--crimson)';
                    setTimeout(() => el.style.color = '', 800);
                }
            }
        }

        // Reload table so row now shows grey "View Permit"
        $('#bizTable').DataTable().ajax.reload(null, false);

        // Offer to open the permit record
        setTimeout(function () {
            bmsConfirm({
                title:   'Permit Issued',
                message: res.data.permit_num + ' is now active. Open the permit record now?',
                ok:      'Open Permit',
            }, function () {
                window.open(res.data.view_url, '_blank');
            });
        }, 400);
    })
    .catch(function (err) {
        var data = err.response?.data;
        var msg  = data?.errors
            ? Object.values(data.errors).flat().join(' ')
            : (data?.message || 'Failed to issue permit.');
        if (err.response?.status === 422 && data?.view_url) {
            msg += ' <a href="' + data.view_url + '" target="_blank" style="color:var(--navy);font-weight:600">View it here →</a>';
        }
        errDiv.innerHTML     = msg;
        errDiv.style.display = 'block';
    })
    .finally(function () {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-file-certificate"></i> Issue Permit';
    });
}

/* ── Biz Status Modal ─────────────────────────────────────────────── */
var _bizId = null, _bizOldStatus = null;

function closeBizModal() {
    document.getElementById('bizStatusModal').style.display = 'none';
    _bizId = null; _bizOldStatus = null;
}

function saveBizStatus() {
    if (!_bizId) return;
    const btn = document.getElementById('bizStatusSaveBtn');
    const errDiv = document.getElementById('bizStatusError');
    const newStatus = document.getElementById('bizModalStatus').value;
    const notes = document.getElementById('bizModalNotes').value;
    const oldStatus = _bizOldStatus;
    if (newStatus === oldStatus) { closeBizModal(); return; }
    errDiv.style.display = 'none';
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="color:var(--gold)"></i> Saving…';

    axios.patch('/appointments/biz/' + _bizId + '/status', { status: newStatus, notes: notes })
        .then(function (res) {
            closeBizModal();
            // Update stat card
            if (typeof res.data.biz_pending !== 'undefined') {
                var el = document.getElementById('statBizPending');
                if (el) {
                    var prev = parseInt(el.textContent.replace(/,/g,''), 10) || 0;
                    el.textContent = res.data.biz_pending.toLocaleString();
                    if (res.data.biz_pending !== prev) {
                        el.style.transition = 'color .15s';
                        el.style.color = res.data.biz_pending < prev ? 'var(--crimson)' : 'var(--gold)';
                        setTimeout(() => el.style.color = '', 800);
                    }
                }
            }
            bmsToast(res.data.message || 'Status updated.', 'success');
            $('#bizTable').DataTable().ajax.reload(null, false);
        })
        .catch(function (err) {
            const data = err.response?.data;
            errDiv.textContent = data?.errors ? Object.values(data.errors).flat().join(' ') : (data?.message || 'Failed.');
            errDiv.style.display = 'block';
        })
        .finally(function () { btn.disabled = false; btn.innerHTML = '<i class="fas fa-floppy-disk"></i> Save Status'; });
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { closeAptModal(); closeConvertModal(); closeBizModal(); closeBizIssueModal(); }
});
</script>
@endpush
