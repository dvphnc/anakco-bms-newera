<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: 'DejaVu Sans', sans-serif; font-size: 8pt; color: #111; }

.header {
    text-align: center;
    padding-bottom: 10px;
    border-bottom: 3px solid #0D2144;
    margin-bottom: 12px;
    position: relative;
}
.header .logo-left  { position: absolute; left: 0; top: 0; width: 55px; height: 55px; }
.header .logo-right { position: absolute; right: 0; top: 0; width: 55px; height: 55px; }
.header .titles { padding: 0 70px; }
.header .republic { font-size: 8pt; font-style: italic; color: #444; }
.header .brgy { font-size: 14pt; font-weight: bold; text-transform: uppercase; color: #0D2144; letter-spacing: 0.05em; }
.header .office { font-size: 8pt; color: #555; font-style: italic; }
.header .address { font-size: 7.5pt; color: #777; }

.report-title {
    text-align: center;
    font-size: 12pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #0D2144;
    margin: 10px 0 2px;
    text-decoration: underline;
}
.report-sub {
    text-align: center;
    font-size: 7.5pt;
    color: #666;
    margin-bottom: 10px;
}

.meta-bar {
    background: #f0f4f8;
    border: 1px solid #dde2ea;
    border-radius: 4px;
    padding: 5px 10px;
    margin-bottom: 12px;
    font-size: 7.5pt;
    color: #444;
    display: flex;
    justify-content: space-between;
}

/* Summary row */
.summary-row {
    display: flex;
    gap: 8px;
    margin-bottom: 14px;
}
.summary-box {
    flex: 1;
    border: 1px solid #dde2ea;
    border-radius: 4px;
    padding: 8px 10px;
    text-align: center;
    background: #f8fafc;
}
.summary-box .num { font-size: 16pt; font-weight: bold; color: #0D2144; line-height: 1; }
.summary-box .lbl { font-size: 6.5pt; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; margin-top: 3px; }

/* Section */
.section-title {
    font-size: 8pt;
    font-weight: bold;
    color: #fff;
    background: #0D2144;
    padding: 5px 10px;
    margin-top: 14px;
    margin-bottom: 0;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.section-title:first-of-type { margin-top: 0; }

table { width: 100%; border-collapse: collapse; font-size: 7pt; margin-bottom: 6px; }
thead tr th {
    background: #1a3a6e;
    color: #fff;
    padding: 5px 7px;
    text-align: left;
    font-size: 6.5pt;
    text-transform: uppercase;
    letter-spacing: .04em;
    border: 1px solid #0a1a36;
}
tbody tr:nth-child(odd)  { background: #fff; }
tbody tr:nth-child(even) { background: #f5f7fa; }
tbody td {
    padding: 4px 7px;
    border-bottom: 1px solid #e5e7eb;
    border-right: 1px solid #f0f0f0;
    vertical-align: middle;
}
tbody td:last-child { border-right: none; }

.badge {
    display: inline-block;
    padding: 1px 5px;
    border-radius: 99px;
    font-size: 6.5pt;
    font-weight: bold;
}
.badge-green  { background: #dcfce7; color: #166534; }
.badge-gold   { background: #fef3dc; color: #92600a; }
.badge-red    { background: #fee2e2; color: #991b1b; }
.badge-gray   { background: #f3f4f6; color: #6b7280; }
.badge-blue   { background: #dbeafe; color: #1e40af; }
.badge-yellow { background: #fef9c3; color: #854d0e; }

.empty { text-align: center; color: #9ca3af; padding: 8px; font-size: 7pt; background: #fafafa; border: 1px solid #f0f0f0; }

.footer {
    margin-top: 20px;
    padding-top: 8px;
    border-top: 2px solid #0D2144;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-size: 7pt;
    color: #555;
}
.sig-line { text-align: center; min-width: 160px; }
.sig-line .line { border-top: 1px solid #333; margin-bottom: 3px; width: 100%; }
.sig-line .name { font-weight: bold; font-size: 7.5pt; text-transform: uppercase; }
.sig-line .title { font-size: 7pt; color: #666; }
</style>
</head>
<body>

{{-- Header --}}
<div class="header">
    <img class="logo-left"  src="{{ public_path('images/qc-seal.png') }}" alt="">
    <img class="logo-right" src="{{ public_path('images/bne-logo.png') }}" alt="">
    <div class="titles">
        <div class="republic"><em>Republic of the Philippines</em></div>
        <div style="font-size:8pt;color:#555">City of Quezon, National Capital Region</div>
        <div class="brgy">Barangay New Era</div>
        <div class="office">Office of the Punong Barangay</div>
        <div class="address">New Era, Quezon City, Metro Manila</div>
    </div>
</div>

<div class="report-title">{{ strtoupper($committeeName) }} Committee Report</div>
<div class="report-sub">As of {{ now()->format('F d, Y') }}</div>

<div class="meta-bar">
    <span>Generated: <strong>{{ $generatedAt }}</strong></span>
    <span>Prepared by: <strong>{{ $generatedBy }}</strong></span>
</div>

{{-- Summary boxes --}}
<div class="summary-row">
    <div class="summary-box">
        <div class="num">{{ $records->count() }}</div>
        <div class="lbl">Records</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $activities->where('activity_type','Activity')->count() }}</div>
        <div class="lbl">Activities</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $activities->where('activity_type','Accomplishment')->count() }}</div>
        <div class="lbl">Accomplishments</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ number_format($attendances->sum('total_attendees')) }}</div>
        <div class="lbl">Total Attendees</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $inventory->count() }}</div>
        <div class="lbl">Inventory Items</div>
    </div>
    <div class="summary-box">
        <div class="num">{{ $partnerships->count() }}</div>
        <div class="lbl">Partnerships</div>
    </div>
</div>

{{-- Activities --}}
@if($activities->count())
<div class="section-title">Activities & Accomplishments</div>
<table>
    <thead><tr><th>Title</th><th>Type</th><th>Date</th><th>Location</th><th>Participants</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($activities->sortByDesc('activity_date') as $a)
        <tr>
            <td style="font-weight:bold">{{ $a->title }}</td>
            <td><span class="badge badge-blue">{{ $a->activity_type }}</span></td>
            <td>{{ $a->activity_date->format('M d, Y') }}</td>
            <td>{{ $a->location ?? '—' }}</td>
            <td style="text-align:right">{{ $a->participants_count ?? '—' }}</td>
            <td><span class="badge {{ match($a->status) { 'Completed'=>'badge-green','Ongoing'=>'badge-yellow',default=>'badge-gray' } }}">{{ $a->status }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Attendance --}}
@if($attendances->count())
<div class="section-title">Attendance Records</div>
<table>
    <thead><tr><th>Event</th><th>Date</th><th>Venue</th><th style="text-align:right">Attendees</th></tr></thead>
    <tbody>
        @foreach($attendances->sortByDesc('event_date') as $a)
        <tr>
            <td style="font-weight:bold">{{ $a->event_name }}</td>
            <td>{{ $a->event_date->format('M d, Y') }}</td>
            <td>{{ $a->venue ?? '—' }}</td>
            <td style="text-align:right;font-weight:bold">{{ number_format($a->total_attendees) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Inventory --}}
@if($inventory->count())
<div class="section-title">Inventory</div>
<table>
    <thead><tr><th>Item</th><th>Category</th><th style="text-align:right">Qty</th><th>Unit</th><th>Condition</th></tr></thead>
    <tbody>
        @foreach($inventory as $item)
        <tr>
            <td style="font-weight:bold">{{ $item->item_name }}</td>
            <td>{{ $item->category ?? '—' }}</td>
            <td style="text-align:right;font-weight:bold">{{ number_format($item->quantity) }}</td>
            <td>{{ $item->unit ?? '—' }}</td>
            <td><span class="badge {{ match($item->condition) { 'Good'=>'badge-green','Fair'=>'badge-yellow','Poor'=>'badge-red',default=>'badge-gray' } }}">{{ $item->condition }}</span></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Partnerships --}}
@if($partnerships->count())
<div class="section-title">Partnership Records</div>
<table>
    <thead><tr><th>Organization</th><th>Type</th><th>MOU Date</th><th>Valid Until</th><th>Contact Person</th></tr></thead>
    <tbody>
        @foreach($partnerships as $p)
        <tr>
            <td style="font-weight:bold">{{ $p->partner_name }}</td>
            <td><span class="badge badge-blue">{{ $p->partner_type }}</span></td>
            <td>{{ $p->mou_date?->format('M d, Y') ?? '—' }}</td>
            <td style="{{ $p->validity_date && $p->validity_date->isPast() ? 'color:#991b1b;font-weight:bold' : '' }}">{{ $p->validity_date?->format('M d, Y') ?? '—' }}</td>
            <td>{{ $p->contact_person ?? '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- Records --}}
@if($records->count())
<div class="section-title">Uploaded Records</div>
<table>
    <thead><tr><th>Title</th><th>Type</th><th>Description</th><th>Date</th></tr></thead>
    <tbody>
        @foreach($records->take(30) as $r)
        <tr>
            <td style="font-weight:bold">{{ $r->title }}</td>
            <td><span class="badge badge-gray">{{ $r->record_type }}</span></td>
            <td>{{ Str::limit($r->description ?? '—', 50) }}</td>
            <td>{{ $r->created_at->format('M d, Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@if($records->count() > 30)
<div class="empty">Showing first 30 of {{ $records->count() }} records.</div>
@endif
@endif

{{-- Footer --}}
<div class="footer">
    <div>
        <div>Barangay New Era, District VI, Quezon City</div>
        <div>{{ $generatedAt }}</div>
    </div>
    <div class="sig-line">
        <div class="line"></div>
        <div class="name">{{ $officialName }}</div>
        <div class="title">Punong Barangay</div>
    </div>
</div>

</body>
</html>
