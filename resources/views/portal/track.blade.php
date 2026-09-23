@extends('layouts.portal')
@section('title', 'Track Your Request')

@push('styles')
<style>
/* ─── Page header ─────────────────────────────────────────── */
.track-hd { margin-bottom: 1.5rem; }
.track-hd h2 {
    font-size: 1.2rem; font-weight: 700;
    color: var(--navy); margin-bottom: .25rem;
}
.track-hd p { font-size: .82rem; color: #6b7280; }

/* ─── Type pills hint ─────────────────────────────────────── */
.ref-hints {
    display: flex; flex-wrap: wrap; gap: .5rem;
    margin-top: .75rem;
}
.ref-hint {
    display: inline-flex; align-items: center; gap: .35rem;
    font-size: .72rem; font-weight: 600;
    padding: .25rem .65rem; border-radius: 99px;
    border: 1px solid;
}
.rh-doc     { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
.rh-biz     { background: #fffbeb; color: #92400e; border-color: #fde68a; }
.rh-blotter { background: #fef2f2; color: #991b1b; border-color: #fecaca; }

/* ─── Search bar ──────────────────────────────────────────── */
.search-row { display: flex; gap: .75rem; align-items: flex-start; }
.search-row .form-control { flex: 1; font-size: .95rem; }
.search-row .btn { flex-shrink: 0; }

/* ─── Result card shell ───────────────────────────────────── */
.result-card {
    margin-top: 2rem;
    border: 1.5px solid #e5e7eb;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(13,33,68,.06);
}
.result-header {
    background: var(--navy);
    color: #fff;
    padding: 1.1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.result-header .apt-num {
    font-size: 1.15rem; font-weight: 800;
    color: var(--gold); letter-spacing: .06em;
}
.result-header .apt-label {
    font-size: .65rem; opacity: .65;
    text-transform: uppercase; letter-spacing: .07em; margin-bottom: .2rem;
}
.result-header .type-chip {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .65rem; font-weight: 700; letter-spacing: .05em;
    text-transform: uppercase;
    background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25);
    border-radius: 99px; padding: .2rem .6rem;
    color: rgba(255,255,255,.8);
    margin-top: .3rem;
}

/* Status badge in header */
.apt-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: .3rem .8rem; border-radius: 999px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
    border: 1.5px solid;
}
.apt-status-badge.s-pending             { background: rgba(200,134,26,.18); color: #e6a020; border-color: rgba(200,134,26,.35); }
.apt-status-badge.s-confirmed           { background: rgba(255,255,255,.12); color: #fff; border-color: rgba(255,255,255,.3); }
.apt-status-badge.s-processing          { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-ready               { background: rgba(22,163,74,.2); color: #86efac; border-color: rgba(22,163,74,.4); }
.apt-status-badge.s-released            { background: rgba(255,255,255,.08); color: #d1d5db; border-color: rgba(255,255,255,.2); }
.apt-status-badge.s-cancelled           { background: rgba(220,38,38,.2); color: #fca5a5; border-color: rgba(220,38,38,.35); }
.apt-status-badge.s-active              { background: rgba(220,38,38,.2); color: #fca5a5; border-color: rgba(220,38,38,.35); }
.apt-status-badge.s-active-biz          { background: rgba(22,163,74,.2);  color: #86efac; border-color: rgba(22,163,74,.4); }
.apt-status-badge.s-for-review          { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-under-investigation { background: rgba(200,134,26,.18); color: #e6a020; border-color: rgba(200,134,26,.35); }
.apt-status-badge.s-mediated            { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-settled             { background: rgba(22,163,74,.2); color: #86efac; border-color: rgba(22,163,74,.4); }
.apt-status-badge.s-closed              { background: rgba(255,255,255,.08); color: #d1d5db; border-color: rgba(255,255,255,.2); }
.apt-status-badge.s-referred-to-higher-authority { background: rgba(124,58,237,.2); color: #c4b5fd; border-color: rgba(124,58,237,.35); }

/* ─── Info grid ───────────────────────────────────────────── */
.result-body { padding: 1.4rem 1.5rem; }
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .5rem .75rem;
    margin-bottom: 1.5rem;
}
.info-cell {
    padding: .65rem .9rem;
    background: #f9fafb;
    border-radius: var(--radius-sm);
    border: 1px solid #f0f1f3;
}
.info-cell .ic-label {
    font-size: .65rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .06em;
    color: #9ca3af; margin-bottom: .18rem;
}
.info-cell .ic-value {
    font-size: .875rem; font-weight: 600; color: var(--navy); line-height: 1.35;
}
.info-cell.full-width { grid-column: 1 / -1; }

/* ─── Progress stepper ────────────────────────────────────── */
.progress-section {
    background: #f9fafb;
    border: 1px solid #f0f1f3;
    border-radius: var(--radius);
    padding: 1.1rem 1.25rem 1rem;
    margin-bottom: 1.5rem;
}
.progress-section .ps-title {
    font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .07em;
    color: #9ca3af; margin-bottom: 1rem;
}
.progress-steps { display: flex; align-items: flex-start; gap: 0; }
.prog-step {
    flex: 1; text-align: center; position: relative;
}
.prog-step::before {
    content: '';
    position: absolute;
    top: 16px;
    left: calc(50% + 18px);
    right: calc(-50% + 18px);
    height: 2px;
    background: #e5e7eb;
    z-index: 0;
}
.prog-step:last-child::before { display: none; }
.prog-step.done::before { background: var(--navy); }
.prog-step.current::before { background: linear-gradient(90deg, var(--navy) 0%, #e5e7eb 100%); }

.prog-dot {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: #e5e7eb;
    color: #9ca3af;
    display: flex; align-items: center; justify-content: center;
    font-size: .72rem; font-weight: 700;
    margin: 0 auto .45rem;
    position: relative; z-index: 1;
    border: 2px solid #d1d5db;
    transition: all .2s;
}
.prog-step.done .prog-dot {
    background: var(--navy); color: #fff;
    border-color: var(--navy);
    font-size: .75rem;
}
.prog-step.current .prog-dot {
    background: var(--gold); color: #fff;
    border-color: var(--gold);
    box-shadow: 0 0 0 4px rgba(200,134,26,.2);
    font-size: .75rem;
}
.prog-step.cancelled .prog-dot {
    background: #dc2626; color: #fff; border-color: #dc2626;
    box-shadow: 0 0 0 4px rgba(220,38,38,.2);
}
.prog-step.cancelled .prog-label { color: #dc2626; font-weight: 700; }
.prog-label {
    font-size: .63rem; color: #9ca3af; font-weight: 500; line-height: 1.2;
}
.prog-step.done .prog-label    { color: var(--navy); font-weight: 700; }
.prog-step.current .prog-label { color: var(--gold);  font-weight: 700; }

/* ─── Timeline ────────────────────────────────────────────── */
.timeline-section { margin-top: 0; }
.timeline-hd {
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: #9ca3af; margin-bottom: 1rem;
    display: flex; align-items: center; gap: .4rem;
}
.timeline { position: relative; padding-left: 1.6rem; }
.timeline::before {
    content: '';
    position: absolute;
    left: 7px; top: 8px;
    bottom: 8px; width: 2px;
    background: #e5e7eb;
    border-radius: 2px;
}
.tl-item { position: relative; margin-bottom: .9rem; }
.tl-item.tl-last { margin-bottom: 0; }
.tl-dot {
    position: absolute;
    left: -1.6rem; top: .3rem;
    width: 16px; height: 16px;
    border-radius: 50%;
    border: 2px solid;
    background: #fff;
    z-index: 1;
}
.tl-dot-pending    { border-color: var(--gold);  background: rgba(200,134,26,.15); }
.tl-dot-confirmed  { border-color: var(--navy);  background: rgba(13,33,68,.1); }
.tl-dot-processing { border-color: #2563eb;      background: rgba(37,99,235,.1); }
.tl-dot-ready      { border-color: #16a34a;      background: rgba(22,163,74,.1); }
.tl-dot-released   { border-color: #6b7280;      background: rgba(107,114,128,.1); }
.tl-dot-cancelled  { border-color: #dc2626;      background: rgba(220,38,38,.1); }
.tl-dot-active                      { border-color: #dc2626;  background: rgba(220,38,38,.1); }
.tl-dot-active-biz                  { border-color: #16a34a;  background: rgba(22,163,74,.1); }
.tl-dot-settled                     { border-color: #16a34a;  background: rgba(22,163,74,.1); }
.tl-dot-mediated                    { border-color: #2563eb;  background: rgba(37,99,235,.1); }
.tl-dot-closed                      { border-color: #6b7280;  background: rgba(107,114,128,.1); }
.tl-dot-under-investigation         { border-color: #d97706;  background: rgba(217,119,6,.1); }
.tl-dot-referred-to-higher-authority{ border-color: #7c3aed;  background: rgba(124,58,237,.1); }

.tl-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: var(--radius-sm);
    padding: .65rem .85rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.tl-status { font-size: .85rem; font-weight: 700; color: var(--navy); margin-bottom: .2rem; }
.tl-meta {
    font-size: .72rem; color: #9ca3af;
    display: flex; flex-wrap: wrap; gap: .25rem .5rem;
}
.tl-meta .tl-who { color: #6b7280; font-weight: 600; }
.tl-note {
    margin-top: .4rem; font-size: .77rem; color: #6b7280;
    background: #f9fafb; border-left: 3px solid #e5e7eb;
    padding: .35rem .6rem; border-radius: 0 4px 4px 0; font-style: italic;
}

/* ─── Referred to Higher Authority banner ─────────────────── */
.referred-banner {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .9rem 1.1rem;
    background: #f5f3ff;
    border-radius: var(--radius-sm);
    border: 1px solid #ddd6fe;
    margin-bottom: 1.25rem;
}
.referred-banner .rb-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 50%; background: #7c3aed; color: #fff;
    display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.referred-banner .rb-title { font-size: .88rem; font-weight: 700; color: #4c1d95; margin-bottom: .15rem; }
.referred-banner .rb-text  { font-size: .8rem; color: #6d28d9; }

/* ─── Closed/cancelled banner ─────────────────────────────── */
.closed-banner {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .9rem 1.1rem;
    background: #fef2f2;
    border-radius: var(--radius-sm);
    border: 1px solid #fecaca;
    margin-bottom: 1.25rem;
}
.closed-banner .cb-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 50%; background: #dc2626; color: #fff;
    display: flex; align-items: center; justify-content: center; font-size: .9rem;
}
.closed-banner .cb-title { font-size: .88rem; font-weight: 700; color: #991b1b; margin-bottom: .15rem; }
.closed-banner .cb-text  { font-size: .8rem; color: #b91c1c; }

/* ─── Not found ───────────────────────────────────────────── */
.not-found { text-align: center; padding: 2.5rem 1rem; margin-top: 1.5rem; }
.nf-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: #fef2f2; color: #dc2626;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin: 0 auto 1rem;
}
.not-found h3 { font-size: 1rem; color: var(--navy); margin-bottom: .35rem; }
.not-found p  { font-size: .82rem; color: #6b7280; }

.section-divider { height: 1px; background: #f0f1f3; margin: 1.25rem 0; }

/* ─── Mobile ──────────────────────────────────────────────── */
@media (max-width: 600px) {
    .search-row { flex-direction: column; }
    .search-row .btn { width: 100%; justify-content: center; }
    .info-grid { grid-template-columns: 1fr; }
    .info-cell.full-width { grid-column: 1; }
    .result-header { flex-direction: column; align-items: flex-start; gap: .5rem; }
    .progress-steps {
        flex-direction: column; align-items: flex-start;
        gap: 0; padding-left: .5rem;
    }
    .prog-step {
        display: flex; align-items: center;
        gap: .75rem; flex: none; width: 100%;
        padding: .3rem 0; text-align: left;
    }
    .prog-step::before {
        top: 32px; left: 15px; right: auto;
        width: 2px; height: calc(100% + 2px);
        background: #e5e7eb;
    }
    .prog-step.done::before { background: var(--navy); }
    .prog-dot { flex-shrink: 0; margin: 0; }
    .prog-label { font-size: .82rem; margin-top: 0; }
}
</style>
@endpush

@section('content')
<div class="portal-wrap">
<div class="p-card">

    <div class="track-hd">
        <h2><i class="fas fa-magnifying-glass" style="color:var(--gold)"></i>&nbsp; Track Your Request</h2>
        <p>Enter your reference number to check the status of your document appointment, business permit, or blotter report.</p>
        <div class="ref-hints">
            <span class="ref-hint rh-doc"><i class="fas fa-file-lines"></i> Document — <code>APT-...</code></span>
            <span class="ref-hint rh-biz"><i class="fas fa-store"></i> Business Permit — <code>BP-...</code></span>
            <span class="ref-hint rh-blotter"><i class="fas fa-shield-halved"></i> Blotter Report — <code>CASE-...</code></span>
        </div>
    </div>

    <form id="trackForm" method="POST" action="{{ route('portal.track.post') }}">
        @csrf
        <div class="search-row">
            <input type="text" name="appointment_number" id="trackInput" class="form-control"
                   placeholder="APT-..., BP-..., or CASE-..."
                   value="{{ request('ref') ?? old('appointment_number') ?? (isset($number) ? $number : '') }}"
                   style="text-transform:uppercase;letter-spacing:.06em"
                   autocomplete="off"
                   required>
            <button type="submit" id="trackBtn" class="btn btn-primary">
                <i class="fas fa-search"></i> Track
            </button>
        </div>
        @error('appointment_number') <div class="form-error" style="margin-top:.4rem">{{ $message }}</div> @enderror
    </form>

    {{-- Axios result container --}}
    <div id="trackResult" style="display:none"></div>

    {{-- Server-side fallback --}}
    @if(isset($type))
        @if($type && $record)
            <div class="result-card" id="serverResult">
                <div class="result-header">
                    <div>
                        <div class="apt-label">Reference Number</div>
                        <div class="apt-num">{{ $number }}</div>
                        <div class="type-chip">
                            @if($type === 'document') <i class="fas fa-file-lines"></i> Document Appointment
                            @elseif($type === 'business') <i class="fas fa-store"></i> Business Permit
                            @else <i class="fas fa-shield-halved"></i> Blotter Report
                            @endif
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">
                        @php $slug = strtolower(str_replace([' ', '/'], ['-', ''], $record->status)); @endphp
                        <span class="apt-status-badge s-{{ $slug }}">
                            <i class="fas fa-circle" style="font-size:.45rem"></i>
                            {{ $record->status }}
                        </span>
                        <div style="font-size:.72rem;opacity:.6;text-align:right">
                            Submitted {{ $record->created_at->format('M d, Y · g:i A') }}
                        </div>
                    </div>
                </div>
                <div class="result-body">
                    <p style="font-size:.85rem;color:#6b7280;text-align:center;padding:1rem 0">
                        <i class="fas fa-circle-info" style="color:var(--navy)"></i>
                        Your request was found. The full status details are displayed above.
                    </p>
                </div>
            </div>
        @else
            <div class="not-found" id="serverResult">
                <div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>
                <h3>Reference Not Found</h3>
                <p>No record found for <strong>{{ $number }}</strong>. Please double-check your reference number and try again.</p>
            </div>
        @endif
    @endif

</div>
</div>
@endsection

@push('scripts')
<style>
/* ── Live-refresh footer ────────────────────────────────── */
.live-bar {
    display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    padding: .55rem 1rem;
    background: #f9fafb;
    border-top: 1px solid #f0f1f3;
    font-size: .7rem; color: #9ca3af;
    border-radius: 0 0 var(--radius-lg) var(--radius-lg);
}
.live-dot {
    display: inline-block; width: 7px; height: 7px;
    border-radius: 50%; background: #22c55e;
    margin-right: 4px;
    animation: livepulse 1.6s ease-in-out infinite;
}
@keyframes livepulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.4; transform:scale(1.4); }
}
.live-bar.stopped .live-dot { background:#9ca3af; animation:none; }
.live-bar button {
    background: none; border: none; cursor: pointer;
    font-size: .7rem; color: #6b7280; font-weight: 600; padding: 0;
}
.live-bar button:hover { color: var(--navy); }
/* flash when status changes */
@keyframes statusFlash {
    0%   { background: rgba(200,134,26,.18); }
    100% { background: var(--navy); }
}
.result-header.flashing { animation: statusFlash .6s ease-out; }
</style>
<script src="{{ asset('assets/vendor/axios/axios.min.js') }}"></script>
<script>
(function () {
    var form         = document.getElementById('trackForm');
    var input        = document.getElementById('trackInput');
    var btn          = document.getElementById('trackBtn');
    var resultDiv    = document.getElementById('trackResult');
    var serverRes    = document.getElementById('serverResult');

    if (!form) return;
    if (serverRes) serverRes.style.display = 'none';

    var POLL_MS     = 15000;   // refresh every 15 s
    var pollTimer   = null;
    var lastStatus  = null;
    var lastChecked = null;
    var currentNum  = null;
    var TERMINAL    = ['Released','Cancelled','Settled','Referred to Higher Authority'];

    if (input.value.trim()) doSearch(input.value.trim());

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        stopPolling();
        lastStatus = null;
        doSearch(input.value.trim());
    });

    /* ── One-shot search (shows spinner on button) ───────────── */
    function doSearch(number) {
        if (!number) return;
        currentNum    = number.toUpperCase();
        btn.disabled  = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching…';
        resultDiv.style.display = 'none';

        fetchStatus(currentNum, true)
            .finally(function () {
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-search"></i> Track';
            });
    }

    /* ── Background silent refresh ───────────────────────────── */
    function silentRefresh() {
        if (!currentNum) return;
        fetchStatus(currentNum, false);
    }

    /* ── Core fetch ──────────────────────────────────────────── */
    function fetchStatus(number, isUserTriggered) {
        return axios.get('{{ route('portal.track.lookup') }}', { params: { number: number } })
            .then(function (res) {
                var d = res.data;
                lastChecked = new Date();

                var statusChanged = d.found && lastStatus !== null && d.status !== lastStatus;
                lastStatus = d.found ? d.status : null;

                resultDiv.innerHTML = renderResult(d);
                resultDiv.style.display = 'block';

                if (statusChanged) {
                    var hdr = resultDiv.querySelector('.result-header');
                    if (hdr) { hdr.classList.add('flashing'); setTimeout(function(){ hdr.classList.remove('flashing'); }, 700); }
                }

                // Start / stop polling based on terminal status
                var terminal = d.found && TERMINAL.indexOf(d.status) !== -1;
                if (!terminal && d.found) {
                    startPolling();
                } else {
                    stopPolling();
                    updateLiveBar(true);
                }
            })
            .catch(function () {
                if (isUserTriggered) {
                    resultDiv.innerHTML =
                        '<div class="not-found">' +
                        '<div class="nf-icon"><i class="fas fa-wifi"></i></div>' +
                        '<h3>Connection Error</h3>' +
                        '<p>Could not reach the server. Please try again.</p>' +
                        '</div>';
                    resultDiv.style.display = 'block';
                } else {
                    // silent fail — just update the timestamp
                    updateLiveBar(false);
                }
            });
    }

    /* ── Poll management ─────────────────────────────────────── */
    function startPolling() {
        stopPolling();
        pollTimer = setInterval(function () {
            silentRefresh();
        }, POLL_MS);
    }
    function stopPolling() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    /* ── Update live bar without re-rendering the whole card ─── */
    function updateLiveBar(stopped) {
        var bar = resultDiv.querySelector('.live-bar');
        if (!bar) return;
        var dot = bar.querySelector('.live-dot');
        var ts  = bar.querySelector('.live-ts');
        if (stopped) {
            bar.classList.add('stopped');
            if (dot) dot.style.display = 'none';
        }
        if (ts && lastChecked) ts.textContent = 'Updated ' + formatAgo(lastChecked);
    }

    function formatAgo(date) {
        var s = Math.round((Date.now() - date.getTime()) / 1000);
        if (s < 5)  return 'just now';
        if (s < 60) return s + 's ago';
        return Math.floor(s/60) + 'm ago';
    }

    /* ── Helpers ── */
    function esc(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
    function infoCell(label, value, full) {
        if (!value) return '';
        return '<div class="info-cell' + (full ? ' full-width' : '') + '">' +
            '<div class="ic-label">' + label + '</div>' +
            '<div class="ic-value">' + esc(value) + '</div>' +
            '</div>';
    }
    function statusSlug(s) {
        return s ? s.toLowerCase().replace(/\s+/g, '-').replace(/\//g, '') : '';
    }
    function statusBadgeHtml(s, type) {
        var slug = statusSlug(s);
        // Business permit Active = green (not blotter red)
        if (type === 'business' && s === 'Active') slug = 'active-biz';
        return '<span class="apt-status-badge s-' + slug + '">' +
            '<i class="fas fa-circle" style="font-size:.45rem"></i> ' + esc(s) +
            '</span>';
    }
    function typeChip(type) {
        var map = {
            document: { icon: 'fa-file-lines',    label: 'Document Appointment' },
            business: { icon: 'fa-store',          label: 'Business Permit' },
            blotter:  { icon: 'fa-shield-halved',  label: 'Blotter Report' },
        };
        var t = map[type] || { icon: 'fa-circle', label: type };
        return '<div class="type-chip"><i class="fas ' + t.icon + '"></i> ' + t.label + '</div>';
    }

    function renderStepper(d) {
        if (!d.steps || !d.steps.length) return '';
        // "Referred to Higher Authority" gets its own banner — no confusing stepper
        if (d.referred) return '';

        var isCancelled = d.cancelled;

        // Per-step custom colors for "current" dot
        var stepColors = {
            // Document steps
            'Pending':              { bg: '#C8861A', border: '#C8861A', shadow: 'rgba(200,134,26,.25)' },
            'Processing':           { bg: '#2563eb', border: '#2563eb', shadow: 'rgba(37,99,235,.25)' },
            'Ready':                { bg: '#16a34a', border: '#16a34a', shadow: 'rgba(22,163,74,.25)' },
            'Released':             { bg: '#0D2144', border: '#0D2144', shadow: 'rgba(13,33,68,.25)' },
            // Blotter steps (Active = red — open case)
            'Active':               { bg: '#dc2626', border: '#dc2626', shadow: 'rgba(220,38,38,.25)' },
            'Under Investigation':  { bg: '#d97706', border: '#d97706', shadow: 'rgba(217,119,6,.25)' },
            'Mediated':             { bg: '#2563eb', border: '#2563eb', shadow: 'rgba(37,99,235,.25)' },
            'Settled':              { bg: '#16a34a', border: '#16a34a', shadow: 'rgba(22,163,74,.25)' },
            // Business steps
            'For Review':           { bg: '#2563eb', border: '#2563eb', shadow: 'rgba(37,99,235,.25)' },
        };
        // Business permit: Active = green (issued/valid), not blotter red
        if (d.type === 'business') {
            stepColors['Active'] = { bg: '#16a34a', border: '#16a34a', shadow: 'rgba(22,163,74,.25)' };
        }

        var dots = d.steps.map(function (step, i) {
            var isDone         = d.step_index !== -1 && i < d.step_index;
            // For cancelled: mark the step it was at when cancelled with the 'cancelled' class
            var isCancelledAt  = isCancelled && d.step_index !== -1 && i === d.step_index;
            var isCurrent      = !isCancelled && (
                d.status === step ||
                (d.step_index !== -1 && i === d.step_index && !d.steps.includes(d.status))
            );

            var cls = isDone ? 'done'
                             : (isCancelledAt ? 'cancelled'
                                              : (isCurrent ? 'current' : ''));
            var icon = isDone         ? '<i class="fas fa-check"></i>'
                     : isCancelledAt  ? '<i class="fas fa-xmark"></i>'
                     : isCurrent      ? '<i class="fas fa-circle-dot"></i>'
                     : (i + 1);

            var dotStyle = '';
            if (isCurrent && stepColors[step]) {
                var c = stepColors[step];
                dotStyle = ' style="background:' + c.bg + ';border-color:' + c.border +
                           ';box-shadow:0 0 0 4px ' + c.shadow + ';color:#fff"';
            }

            return '<div class="prog-step ' + cls + '">' +
                '<div class="prog-dot"' + dotStyle + '>' + icon + '</div>' +
                '<div class="prog-label">' + esc(step) + '</div>' +
                '</div>';
        }).join('');

        return '<div class="progress-section">' +
            '<div class="ps-title"><i class="fas fa-route"></i>&nbsp; Progress</div>' +
            '<div class="progress-steps">' + dots + '</div>' +
            '</div>';
    }

    function renderTimeline(logs, type) {
        if (!logs || !logs.length) return '';
        var items = logs.map(function (l, i) {
            var isLast = i === logs.length - 1;
            var slug   = statusSlug(l.to);
            // Business permit Active = green dot
            if (type === 'business' && l.to === 'Active') slug = 'active-biz';
            var who    = l.by ? '<span class="tl-who">' + esc(l.by) + '</span>' : '';
            var note   = l.note ? '<div class="tl-note">' + esc(l.note) + '</div>' : '';
            return '<div class="tl-item' + (isLast ? ' tl-last' : '') + '">' +
                '<div class="tl-dot tl-dot-' + slug + '"></div>' +
                '<div class="tl-card">' +
                    '<div class="tl-status">' + esc(l.to) + '</div>' +
                    '<div class="tl-meta"><span>' + esc(l.date) + ' · ' + esc(l.time) + '</span>' + who + '</div>' +
                    note +
                '</div>' +
                '</div>';
        }).join('');
        return '<div class="section-divider"></div>' +
            '<div class="timeline-section">' +
            '<div class="timeline-hd"><i class="fas fa-clock-rotate-left"></i> Status History</div>' +
            '<div class="timeline">' + items + '</div>' +
            '</div>';
    }

    function closedBanner(title, text, icon) {
        return '<div class="closed-banner">' +
            '<div class="cb-icon"><i class="fas ' + (icon||'fa-ban') + '"></i></div>' +
            '<div><div class="cb-title">' + title + '</div>' +
            '<div class="cb-text">' + text + '</div></div>' +
            '</div>';
    }
    function referredBanner(resolutionNotes) {
        var noteText = resolutionNotes
            ? esc(resolutionNotes)
            : 'The Barangay has completed its process and escalated this case to the appropriate authority.';
        return '<div class="referred-banner">' +
            '<div class="rb-icon"><i class="fas fa-arrow-up-right-from-square"></i></div>' +
            '<div>' +
                '<div class="rb-title">Case Referred to Higher Authority</div>' +
                '<div class="rb-text">' + noteText + '</div>' +
                '<div style="margin-top:.45rem;font-size:.75rem;color:#7c3aed;font-weight:600">' +
                    '<i class="fas fa-circle-info" style="margin-right:3px"></i>' +
                    'This case has been escalated to police, courts, or other agencies. Visit the Barangay Hall for further assistance.' +
                '</div>' +
            '</div>' +
            '</div>';
    }

    /* ── Info grids per type ── */
    function infoGridDocument(d) {
        // Build pickup date cell with a highlight if set
        var pickupCell = '';
        if (d.pickup_date) {
            pickupCell = '<div class="info-cell" style="border:1.5px solid #bbf7d0;background:#f0fdf4">' +
                '<div class="ic-label" style="color:#16a34a"><i class="fas fa-calendar-check"></i> Ready for Pick-up</div>' +
                '<div class="ic-value" style="color:#15803d;font-size:.95rem">' + esc(d.pickup_date) + '</div>' +
                '</div>';
        } else if (d.preferred_date) {
            pickupCell = infoCell('Preferred Pick-up Date', d.preferred_date);
        }
        // Representative cell
        var repCell = '';
        if (d.requestor_name) {
            var repVal = esc(d.requestor_name);
            if (d.requestor_relationship) repVal += '<span style="font-size:.75rem;color:#6b7280;font-weight:400"> (' + esc(d.requestor_relationship) + ')</span>';
            if (d.requestor_contact)      repVal += '<div style="font-size:.75rem;color:#6b7280;margin-top:2px">' + esc(d.requestor_contact) + '</div>';
            repCell = '<div class="info-cell" style="border:1.5px solid #fde68a;background:#fffbeb">' +
                '<div class="ic-label" style="color:#92400e"><i class="fas fa-user-group"></i> Representative</div>' +
                '<div class="ic-value">' + repVal + '</div>' +
                '</div>';
        }

        return '<div class="info-grid">' +
            infoCell('Name',         d.resident_name) +
            infoCell('Document',     d.document_type) +
            pickupCell +
            infoCell('Purpose',      d.purpose) +
            (repCell || '') +
            infoCell('Processed By', d.processed_by) +
            infoCell('Released On',  d.released_at) +
            infoCell('Last Updated', d.updated_at) +
            infoCell('Staff Notes',  d.notes, true) +
            '</div>';
    }
    function infoGridBusiness(d) {
        return '<div class="info-grid">' +
            infoCell('Owner',            d.owner_name) +
            infoCell('Business Name',    d.business_name) +
            infoCell('Business Type',    d.business_type) +
            infoCell('Appointment Date', d.appointment_date) +
            infoCell('Business Address', d.business_address, true) +
            (d.permit_date ? infoCell('Permit Date',  d.permit_date)  : '') +
            (d.expiry_date ? infoCell('Expiry Date',  d.expiry_date)  : '') +
            infoCell('Last Updated',     d.updated_at) +
            '</div>';
    }
    function infoGridBlotter(d) {
        return '<div class="info-grid">' +
            infoCell('Complainant',    d.complainant_name) +
            infoCell('Incident Type',  d.incident_type) +
            infoCell('Incident Date',  d.incident_date) +
            infoCell('Last Updated',   d.updated_at) +
            infoCell('Location',       d.incident_location, true) +
            (d.respondent_name ? infoCell('Respondent', d.respondent_name) : '') +
            (d.resolution_notes ? infoCell('Staff Notes', d.resolution_notes, true) : '') +
            '</div>';
    }

    /* ── Main render ── */
    function renderResult(d) {
        if (!d.found) {
            return '<div class="not-found">' +
                '<div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>' +
                '<h3>Reference Not Found</h3>' +
                '<p>No record found for that reference number. Please double-check and try again.</p>' +
                '</div>';
        }

        var header = '<div class="result-header">' +
            '<div>' +
                '<div class="apt-label">Reference Number</div>' +
                '<div class="apt-num">' + esc(d.reference_number) + '</div>' +
                typeChip(d.type) +
            '</div>' +
            '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">' +
                statusBadgeHtml(d.status, d.type) +
                '<div style="font-size:.72rem;opacity:.6;text-align:right">Submitted ' + esc(d.created_at) + '</div>' +
            '</div>' +
            '</div>';

        var banner = '';
        if (d.referred) {
            banner = referredBanner(d.resolution_notes);
        } else if (d.cancelled) {
            if (d.type === 'blotter') {
                banner = closedBanner('This blotter case has been closed.', d.resolution_notes || 'The case has been resolved.', 'fa-shield-halved');
            } else if (d.type === 'business') {
                banner = closedBanner('This permit application has been cancelled.', 'Please visit the Barangay Hall for assistance.', 'fa-ban');
            } else {
                var cNote = d.notes || 'Please visit the barangay hall or submit a new request.';
                banner = closedBanner('This appointment has been cancelled.', cNote, 'fa-ban');
            }
        }

        var progress = renderStepper(d);

        var grid = '';
        if (d.type === 'document') grid = infoGridDocument(d);
        else if (d.type === 'business') grid = infoGridBusiness(d);
        else if (d.type === 'blotter') grid = infoGridBlotter(d);

        var timeline = renderTimeline(d.logs || [], d.type);

        var terminal = TERMINAL.indexOf(d.status) !== -1;
        var liveBar  = terminal
            ? '<div class="live-bar stopped">' +
                '<span><span class="live-dot" style="display:none"></span>' +
                '<span class="live-ts">Status finalised</span></span>' +
              '</div>'
            : '<div class="live-bar">' +
                '<span><span class="live-dot"></span>' +
                '<span class="live-ts">Live · checking every 15s</span></span>' +
                '<button type="button" onclick="window._manualRefresh()" title="Refresh now">' +
                '<i class="fas fa-rotate-right" style="margin-right:3px"></i>Refresh</button>' +
              '</div>';

        return '<div class="result-card">' +
            header +
            '<div class="result-body">' +
                banner + progress + grid + timeline +
            '</div>' +
            liveBar +
            '</div>';
    }

    // Expose manual refresh for the "Refresh" button
    window._manualRefresh = function () {
        if (!currentNum) return;
        stopPolling();
        lastStatus = null;
        fetchStatus(currentNum, false).then(function () {
            if (lastStatus && TERMINAL.indexOf(lastStatus) === -1) startPolling();
        });
    };

    // Tick the "Updated Xs ago" label every 5 seconds without re-rendering
    setInterval(function () {
        if (!lastChecked) return;
        var bar = resultDiv.querySelector('.live-bar:not(.stopped) .live-ts');
        if (bar) bar.textContent = 'Live · updated ' + formatAgo(lastChecked);
    }, 5000);
})();
</script>
@endpush
