@extends('layouts.portal')
@section('title', 'Track Appointment')

@push('styles')
<style>
/* ─── Page header ─────────────────────────────────────────── */
.track-hd { margin-bottom: 1.5rem; }
.track-hd h2 {
    font-size: 1.2rem; font-weight: 700;
    color: var(--navy); margin-bottom: .25rem;
}
.track-hd p { font-size: .82rem; color: #6b7280; }

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

/* Status badge in header */
.apt-status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: .3rem .8rem; border-radius: 999px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
    border: 1.5px solid;
}
.apt-status-badge.s-pending    { background: rgba(200,134,26,.18); color: #e6a020; border-color: rgba(200,134,26,.35); }
.apt-status-badge.s-confirmed  { background: rgba(255,255,255,.12); color: #fff; border-color: rgba(255,255,255,.3); }
.apt-status-badge.s-processing { background: rgba(37,99,235,.25); color: #93c5fd; border-color: rgba(37,99,235,.4); }
.apt-status-badge.s-ready      { background: rgba(22,163,74,.2); color: #86efac; border-color: rgba(22,163,74,.4); }
.apt-status-badge.s-released   { background: rgba(255,255,255,.08); color: #d1d5db; border-color: rgba(255,255,255,.2); }
.apt-status-badge.s-cancelled  { background: rgba(220,38,38,.2); color: #fca5a5; border-color: rgba(220,38,38,.35); }

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
}
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

.tl-item {
    position: relative;
    margin-bottom: .9rem;
}
.tl-item.tl-last { margin-bottom: 0; }

.tl-dot {
    position: absolute;
    left: -1.6rem;
    top: .3rem;
    width: 16px; height: 16px;
    border-radius: 50%;
    border: 2px solid;
    background: #fff;
    z-index: 1;
}
/* dot colours */
.tl-dot-pending    { border-color: var(--gold);   background: rgba(200,134,26,.15); }
.tl-dot-confirmed  { border-color: var(--navy);   background: rgba(13,33,68,.1); }
.tl-dot-processing { border-color: #2563eb;       background: rgba(37,99,235,.1); }
.tl-dot-ready      { border-color: #16a34a;       background: rgba(22,163,74,.1); }
.tl-dot-released   { border-color: #6b7280;       background: rgba(107,114,128,.1); }
.tl-dot-cancelled  { border-color: #dc2626;       background: rgba(220,38,38,.1); }

.tl-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: var(--radius-sm);
    padding: .65rem .85rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.tl-status {
    font-size: .85rem; font-weight: 700; color: var(--navy); margin-bottom: .2rem;
}
.tl-meta {
    font-size: .72rem; color: #9ca3af;
    display: flex; flex-wrap: wrap; gap: .25rem .5rem;
}
.tl-meta .tl-who {
    color: #6b7280; font-weight: 600;
}
.tl-note {
    margin-top: .4rem;
    font-size: .77rem;
    color: #6b7280;
    background: #f9fafb;
    border-left: 3px solid #e5e7eb;
    padding: .35rem .6rem;
    border-radius: 0 4px 4px 0;
    font-style: italic;
}

/* ─── Cancelled banner ────────────────────────────────────── */
.cancelled-banner {
    display: flex; align-items: flex-start; gap: .75rem;
    padding: .9rem 1.1rem;
    background: #fef2f2;
    border-radius: var(--radius-sm);
    border: 1px solid #fecaca;
    margin-bottom: 1.25rem;
}
.cancelled-banner .cb-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 50%;
    background: #dc2626; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem;
}
.cancelled-banner .cb-title { font-size: .88rem; font-weight: 700; color: #991b1b; margin-bottom: .15rem; }
.cancelled-banner .cb-text  { font-size: .8rem; color: #b91c1c; }

/* ─── Not found ───────────────────────────────────────────── */
.not-found {
    text-align: center; padding: 2.5rem 1rem; margin-top: 1.5rem;
}
.nf-icon {
    width: 56px; height: 56px; border-radius: 50%;
    background: #fef2f2; color: #dc2626;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; margin: 0 auto 1rem;
}
.not-found h3 { font-size: 1rem; color: var(--navy); margin-bottom: .35rem; }
.not-found p  { font-size: .82rem; color: #6b7280; }

/* ─── Divider between info and timeline ──────────────────── */
.section-divider {
    height: 1px; background: #f0f1f3;
    margin: 1.25rem 0;
}

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
        <h2><i class="fas fa-search" style="color:var(--gold)"></i>&nbsp; Track Your Appointment</h2>
        <p>Enter your appointment number (e.g., <code>APT-20260507-AB12</code>) to check the status of your request.</p>
    </div>

    <form id="trackForm" method="POST" action="{{ route('portal.track.post') }}">
        @csrf
        <div class="search-row">
            <input type="text" name="appointment_number" id="trackInput" class="form-control"
                   placeholder="APT-YYYYMMDD-XXXX"
                   value="{{ request('apt') ?? old('appointment_number') ?? (isset($appointment) ? $appointment->appointment_number : '') }}"
                   style="text-transform:uppercase;letter-spacing:.08em"
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
    @if(isset($appointment))
        @if($appointment)
            @php
                $steps     = ['Pending','Confirmed','Processing','Ready','Released'];
                $current   = $appointment->status;
                $cancelled = $current === 'Cancelled';
                $stepIndex = array_search($current, $steps);
                $statusSlug = strtolower(str_replace(' ', '-', $current));
            @endphp

            <div class="result-card" id="serverResult">

                {{-- Header --}}
                <div class="result-header">
                    <div>
                        <div class="apt-label">Appointment Number</div>
                        <div class="apt-num">{{ $appointment->appointment_number }}</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">
                        <span class="apt-status-badge s-{{ $statusSlug }}">
                            <i class="fas fa-circle" style="font-size:.45rem"></i>
                            {{ $appointment->status }}
                        </span>
                        <div style="font-size:.72rem;opacity:.6;text-align:right">
                            Submitted {{ $appointment->created_at->format('M d, Y · g:i A') }}
                        </div>
                    </div>
                </div>

                <div class="result-body">

                    {{-- Cancelled banner --}}
                    @if($cancelled)
                    <div class="cancelled-banner">
                        <div class="cb-icon"><i class="fas fa-ban"></i></div>
                        <div>
                            <div class="cb-title">This appointment has been cancelled.</div>
                            <div class="cb-text">{{ $appointment->notes ?? 'Please visit the barangay hall or submit a new request.' }}</div>
                        </div>
                    </div>
                    @endif

                    {{-- Progress stepper --}}
                    @if(!$cancelled)
                    <div class="progress-section">
                        <div class="ps-title"><i class="fas fa-route"></i>&nbsp; Progress</div>
                        <div class="progress-steps">
                            @foreach($steps as $i => $step)
                                @php
                                    $isDone    = $stepIndex !== false && $i < $stepIndex;
                                    $isCurrent = $current === $step;
                                @endphp
                                <div class="prog-step {{ $isDone ? 'done' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                    <div class="prog-dot">
                                        @if($isDone) <i class="fas fa-check"></i>
                                        @elseif($isCurrent) <i class="fas fa-circle-dot"></i>
                                        @else {{ $i + 1 }} @endif
                                    </div>
                                    <div class="prog-label">{{ $step }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Info grid --}}
                    <div class="info-grid">
                        <div class="info-cell">
                            <div class="ic-label">Name</div>
                            <div class="ic-value">{{ $appointment->resident_name }}</div>
                        </div>
                        <div class="info-cell">
                            <div class="ic-label">Document</div>
                            <div class="ic-value">{{ $appointment->document_type }}</div>
                        </div>
                        <div class="info-cell">
                            <div class="ic-label">Preferred Date</div>
                            <div class="ic-value">{{ $appointment->preferred_date->format('F j, Y') }}</div>
                        </div>
                        @if($appointment->purpose)
                        <div class="info-cell">
                            <div class="ic-label">Purpose</div>
                            <div class="ic-value">{{ $appointment->purpose }}</div>
                        </div>
                        @endif
                        @if($appointment->processed_by)
                        <div class="info-cell">
                            <div class="ic-label">Processed By</div>
                            <div class="ic-value">{{ $appointment->processed_by }}</div>
                        </div>
                        @endif
                        @if($appointment->released_at)
                        <div class="info-cell">
                            <div class="ic-label">Released On</div>
                            <div class="ic-value">{{ $appointment->released_at->format('F j, Y · g:i A') }}</div>
                        </div>
                        @endif
                        @if($appointment->notes)
                        <div class="info-cell full-width">
                            <div class="ic-label">Staff Notes</div>
                            <div class="ic-value">{{ $appointment->notes }}</div>
                        </div>
                        @endif
                        <div class="info-cell">
                            <div class="ic-label">Last Updated</div>
                            <div class="ic-value">{{ $appointment->updated_at->format('M d, Y · g:i A') }}</div>
                        </div>
                    </div>

                    {{-- Status history timeline --}}
                    @if($appointment->statusLogs->count())
                    <div class="section-divider"></div>
                    <div class="timeline-section">
                        <div class="timeline-hd">
                            <i class="fas fa-clock-rotate-left"></i> Status History
                        </div>
                        <div class="timeline">
                            @foreach($appointment->statusLogs as $log)
                            @php $slug = strtolower(str_replace(' ', '-', $log->to_status)); @endphp
                            <div class="tl-item {{ $loop->last ? 'tl-last' : '' }}">
                                <div class="tl-dot tl-dot-{{ $slug }}"></div>
                                <div class="tl-card">
                                    <div class="tl-status">{{ $log->to_status }}</div>
                                    <div class="tl-meta">
                                        <span>{{ $log->created_at->format('M d, Y · g:i A') }}</span>
                                        @if($log->changed_by)
                                            <span class="tl-who">{{ $log->changed_by }}</span>
                                        @endif
                                    </div>
                                    @if($log->note)
                                    <div class="tl-note">{{ $log->note }}</div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>{{-- /.result-body --}}
            </div>

        @else
            <div class="not-found" id="serverResult">
                <div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>
                <h3>Appointment Not Found</h3>
                <p>No record found for that appointment number. Please double-check and try again.</p>
            </div>
        @endif
    @endif

</div>{{-- /.p-card --}}
</div>{{-- /.portal-wrap --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(function () {
    var form      = document.getElementById('trackForm');
    var input     = document.getElementById('trackInput');
    var btn       = document.getElementById('trackBtn');
    var resultDiv = document.getElementById('trackResult');
    var serverRes = document.getElementById('serverResult');

    if (!form) return;
    if (serverRes) serverRes.style.display = 'none';

    if (input.value.trim()) doSearch(input.value.trim());

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        doSearch(input.value.trim());
    });

    function doSearch(number) {
        if (!number) return;
        btn.disabled  = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching…';
        resultDiv.style.display = 'none';

        axios.get('/portal/track/lookup', { params: { number: number.toUpperCase() } })
            .then(function (res) {
                resultDiv.innerHTML = renderResult(res.data);
                resultDiv.style.display = 'block';
            })
            .catch(function () {
                resultDiv.innerHTML =
                    '<div class="not-found">' +
                    '<div class="nf-icon"><i class="fas fa-wifi"></i></div>' +
                    '<h3>Connection Error</h3>' +
                    '<p>Could not reach the server. Please try again.</p>' +
                    '</div>';
                resultDiv.style.display = 'block';
            })
            .finally(function () {
                btn.disabled  = false;
                btn.innerHTML = '<i class="fas fa-search"></i> Track';
            });
    }

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

    function statusSlug(s) { return s ? s.toLowerCase().replace(/\s+/g, '-') : ''; }

    function statusBadgeHtml(s) {
        return '<span class="apt-status-badge s-' + statusSlug(s) + '">' +
            '<i class="fas fa-circle" style="font-size:.45rem"></i> ' + esc(s) +
            '</span>';
    }

    function renderTimeline(logs) {
        if (!logs || !logs.length) return '';
        var items = logs.map(function (l, i) {
            var isLast = i === logs.length - 1;
            var slug   = statusSlug(l.to);
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

    function renderResult(d) {
        if (!d.found) {
            return '<div class="not-found">' +
                '<div class="nf-icon"><i class="fas fa-circle-xmark"></i></div>' +
                '<h3>Appointment Not Found</h3>' +
                '<p>No record found for that appointment number. Please double-check and try again.</p>' +
                '</div>';
        }

        var slug = statusSlug(d.status);

        /* ── Header ── */
        var header = '<div class="result-header">' +
            '<div>' +
                '<div class="apt-label">Appointment Number</div>' +
                '<div class="apt-num">' + esc(d.appointment_number) + '</div>' +
            '</div>' +
            '<div style="display:flex;flex-direction:column;align-items:flex-end;gap:.4rem">' +
                statusBadgeHtml(d.status) +
                '<div style="font-size:.72rem;opacity:.6;text-align:right">Submitted ' + esc(d.created_at) + '</div>' +
            '</div>' +
            '</div>';

        /* ── Cancelled banner ── */
        var cancelBanner = '';
        if (d.cancelled) {
            var cNote = d.notes ? esc(d.notes) : 'Please visit the barangay hall or submit a new request.';
            cancelBanner = '<div class="cancelled-banner">' +
                '<div class="cb-icon"><i class="fas fa-ban"></i></div>' +
                '<div><div class="cb-title">This appointment has been cancelled.</div>' +
                '<div class="cb-text">' + cNote + '</div></div>' +
                '</div>';
        }

        /* ── Progress stepper ── */
        var progress = '';
        if (!d.cancelled) {
            var dots = (d.steps || []).map(function (step, i) {
                var cls  = i < d.step_index ? 'done' : (d.status === step ? 'current' : '');
                var icon = i < d.step_index
                    ? '<i class="fas fa-check"></i>'
                    : (d.status === step ? '<i class="fas fa-circle-dot"></i>' : (i + 1));
                return '<div class="prog-step ' + cls + '">' +
                    '<div class="prog-dot">' + icon + '</div>' +
                    '<div class="prog-label">' + esc(step) + '</div>' +
                    '</div>';
            }).join('');
            progress = '<div class="progress-section">' +
                '<div class="ps-title"><i class="fas fa-route"></i>&nbsp; Progress</div>' +
                '<div class="progress-steps">' + dots + '</div>' +
                '</div>';
        }

        /* ── Info grid ── */
        var grid = '<div class="info-grid">' +
            infoCell('Name',           d.resident_name) +
            infoCell('Document',       d.document_type) +
            infoCell('Preferred Date', d.preferred_date) +
            infoCell('Purpose',        d.purpose) +
            infoCell('Processed By',   d.processed_by) +
            infoCell('Released On',    d.released_at) +
            infoCell('Staff Notes',    d.notes, true) +
            infoCell('Last Updated',   d.updated_at) +
            '</div>';

        var timeline = renderTimeline(d.logs || []);

        return '<div class="result-card">' +
            header +
            '<div class="result-body">' +
                cancelBanner + progress + grid + timeline +
            '</div>' +
            '</div>';
    }
})();
</script>
@endpush
