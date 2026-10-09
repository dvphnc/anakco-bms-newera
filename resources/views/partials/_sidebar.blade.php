<aside class="sidebar" id="sidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="{{ asset('images/bne-logo.png') }}"
                 alt="BNE Logo"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div style="display:none;width:100%;height:100%;align-items:center;justify-content:center;background:var(--navy-mid);color:var(--gold);font-size:18px">
                <i class="fas fa-shield-halved"></i>
            </div>
        </div>
        <div class="brand-text">
            <h1>Barangay New Era</h1>
            <span>District VI, Quezon City</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">


        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        {{-- Records: each link needs its permission (Roles & Permissions, Part 3.2) --}}
        @canany(['residents.view', 'households.view', 'puroks.view', 'programs.view', 'religion.manage', 'officials.view'])
        <div class="nav-section-label">Records</div>

        @can('residents.view')
        <a href="{{ route('residents.index') }}"
           class="nav-item {{ request()->routeIs('residents.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Residents</span>
        </a>

        {{-- Part 2: nested religion filter, Admin only.
             INC / Non-INC → Family of Ministers → Pabahay unit. On the Residents page a click
             filters the table in place; anywhere else it opens the Residents page with the filter. --}}
        @can('view-religion-data')
        @php
            $onList   = request()->routeIs('residents.index');
            $rGroup   = $onList && in_array(request('religion_group'), ['inc', 'non_inc', 'unrecorded'], true) ? request('religion_group') : '';
            $rFom     = $rGroup === 'inc' && (request()->boolean('fom') || request()->filled('pabahay_unit'));
            $rUnit    = $rFom ? (int) request('pabahay_unit') : 0;
        @endphp
        <div class="nav-tree" id="religionTree" data-url="{{ route('residents.index') }}"
             data-group="{{ $rGroup }}" data-fom="{{ $rFom ? 1 : 0 }}" data-unit="{{ $rUnit ?: '' }}" data-open="{{ $rGroup ? 1 : 0 }}">
            <button type="button" class="nav-tree-toggle" id="religionTreeToggle" aria-expanded="{{ $rGroup ? 'true' : 'false' }}" aria-controls="religionTreeBody">
                <i class="fas fa-sitemap"></i><span>Filter by religion</span><i class="fas fa-chevron-down caret"></i>
            </button>
            <div class="nav-tree-body" id="religionTreeBody" @if(! $rGroup) hidden @endif>
                <a href="{{ route('residents.index', ['religion_group' => 'inc']) }}"
                   class="nav-sub {{ $rGroup === 'inc' ? ($rFom ? 'on-path' : 'active') : '' }}" data-level="1" data-group="inc">
                    <i class="fas fa-church"></i><span>INC</span>
                </a>
                <div class="nav-children {{ $rGroup === 'inc' ? 'open' : '' }}" data-children="inc">
                    <a href="{{ route('residents.index', ['religion_group' => 'inc', 'fom' => 1]) }}"
                       class="nav-sub lvl2 {{ $rFom ? ($rUnit ? 'on-path' : 'active') : '' }}" data-level="2" data-group="inc" data-fom="1">
                        <i class="fas fa-people-roof"></i><span>Family of Ministers</span>
                    </a>
                    <div class="nav-unit-wrap" style="{{ $rFom ? '' : 'display:none' }}">
                        <select id="navPabahayUnit" class="nav-select" aria-label="Pabahay unit">
                            <option value="">All Pabahay units</option>
                            @foreach($sidebarPabahayUnits ?? [] as $pabahayName => $units)
                                <optgroup label="{{ $pabahayName }}">
                                    @foreach($units as $u)
                                        <option value="{{ $u->id }}" @selected($rUnit === $u->id)>{{ $u->unit_no }} ({{ $u->living_count }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <a href="{{ route('residents.index', ['religion_group' => 'non_inc']) }}"
                   class="nav-sub {{ $rGroup === 'non_inc' ? 'active' : '' }}" data-level="1" data-group="non_inc">
                    <i class="fas fa-users"></i><span>Non-INC</span>
                </a>
                <a href="{{ route('residents.index', ['religion_group' => 'unrecorded']) }}"
                   class="nav-sub {{ $rGroup === 'unrecorded' ? 'active' : '' }}" data-level="1" data-group="unrecorded">
                    <i class="fas fa-circle-question"></i><span>Not recorded</span>
                </a>
            </div>
        </div>
        @endcan
        @endcan

        @can('households.view')
        <a href="{{ route('households.index') }}"
           class="nav-item {{ request()->routeIs('households.*') ? 'active' : '' }}">
            <i class="fas fa-house"></i>
            <span>Households</span>
        </a>
        @endcan

        @can('puroks.view')
        <a href="{{ route('puroks.index') }}"
           class="nav-item {{ request()->routeIs('puroks.*') ? 'active' : '' }}">
            <i class="fas fa-location-dot"></i>
            <span>Puroks</span>
        </a>
        @endcan

        @can('programs.view')
        <a href="{{ route('programs.index') }}"
           class="nav-item {{ request()->routeIs('programs.*') ? 'active' : '' }}">
            <i class="fas fa-hand-holding-heart"></i>
            <span>Assistance Programs</span>
        </a>
        @endcan

        @can('manage-religion-data')
        <a href="{{ route('religions.index') }}"
           class="nav-item {{ request()->routeIs('religions.*') ? 'active' : '' }}">
            <i class="fas fa-church"></i>
            <span>Religions</span>
        </a>

        <a href="{{ route('pabahays.index') }}"
           class="nav-item {{ request()->routeIs('pabahays.*') ? 'active' : '' }}">
            <i class="fas fa-house-chimney"></i>
            <span>Pabahay</span>
        </a>
        @endcan

        @can('officials.view')
        <a href="{{ route('officials.index') }}"
           class="nav-item {{ request()->routeIs('officials.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            <span>Officials & Staff</span>
        </a>
        @endcan
        @endcanany

        {{-- Services --}}
        @canany(['documents.view', 'blotter.view', 'businesses.view', 'appointments.view'])
        <div class="nav-section-label">Services</div>

        @can('documents.view')
        <a href="{{ route('documents.index') }}"
           class="nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Document Issuance</span>
        </a>
        @endcan

        @can('blotter.view')
        <a href="{{ route('blotter.index') }}"
           class="nav-item {{ request()->routeIs('blotter.*') ? 'active' : '' }}">
            <i class="fas fa-gavel"></i>
            <span>Blotter Records</span>
            <span id="pblBadge"
                  style="display:none;background:var(--gold);color:var(--navy);font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Active portal blotter cases"></span>
        </a>
        @endcan

        @can('businesses.view')
        <a href="{{ route('businesses.index') }}"
           class="nav-item {{ request()->routeIs('businesses.*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Business Permits</span>
            <span id="pbizBadge"
                  style="display:none;background:var(--gold);color:var(--navy);font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Business applications under review"></span>
        </a>
        @endcan

        @can('appointments.view')
        <a href="{{ route('appointments.index') }}"
           class="nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Appointments</span>
            <span id="portalPendingBadge"
                  style="display:none;background:var(--crimson);color:#fff;font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Total portal submissions pending (documents + blotter + business)"></span>
        </a>
        @endcan

        <a href="{{ route('portal.index') }}" target="_blank"
           class="nav-item">
            <i class="fas fa-globe"></i>
            <span>Resident Portal</span>
            <i class="fas fa-arrow-up-right-from-square" style="font-size:9px;margin-left:auto;opacity:.5"></i>
        </a>
        @endcanany

        {{-- Committees --}}
        @can('committees.view')
        <div class="nav-section-label">Committees</div>

        <a href="{{ route('committees.show', 'peace-order') }}"
           class="nav-item {{ request()->is('committees/peace-order*') ? 'active' : '' }}">
            <i class="fas fa-shield-halved"></i>
            <span>Peace & Order</span>
        </a>

        <a href="{{ route('committees.show', 'health') }}"
           class="nav-item {{ request()->is('committees/health*') ? 'active' : '' }}">
            <i class="fas fa-heartbeat"></i>
            <span>Health</span>
        </a>

        <a href="{{ route('committees.show', 'education') }}"
           class="nav-item {{ request()->is('committees/education*') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i>
            <span>Education</span>
        </a>

        <a href="{{ route('committees.show', 'infrastructure') }}"
           class="nav-item {{ request()->is('committees/infrastructure*') ? 'active' : '' }}">
            <i class="fas fa-road"></i>
            <span>Infrastructure</span>
        </a>

        <a href="{{ route('committees.show', 'environment') }}"
           class="nav-item {{ request()->is('committees/environment*') ? 'active' : '' }}">
            <i class="fas fa-leaf"></i>
            <span>Environment</span>
        </a>

        <a href="{{ route('committees.show', 'livelihood') }}"
           class="nav-item {{ request()->is('committees/livelihood*') ? 'active' : '' }}">
            <i class="fas fa-briefcase"></i>
            <span>Livelihood</span>
        </a>

        <a href="{{ route('committees.show', 'transport') }}"
           class="nav-item {{ request()->is('committees/transport*') ? 'active' : '' }}">
            <i class="fas fa-bus"></i>
            <span>Transport & Comm.</span>
        </a>

        <a href="{{ route('committees.show', 'bdrrm') }}"
           class="nav-item {{ request()->is('committees/bdrrm*') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i>
            <span>BDRRM</span>
        </a>
        @endcan

        {{-- System --}}
        @canany(['reports.view', 'activity-log.view', 'users.manage', 'roles.manage', 'recycle-bin.manage'])
        <div class="nav-section-label">System</div>

        @can('reports.view')
        <a href="{{ route('reports.index') }}"
           class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Reports & Analytics</span>
        </a>
        @endcan

        @can('activity-log.view')
        <a href="{{ route('activity-log.index') }}"
           class="nav-item {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
            <i class="fas fa-clock-rotate-left"></i>
            <span>Activity Log</span>
        </a>
        @endcan

        @can('users.manage')
        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>User Management</span>
        </a>
        @endcan

        @can('roles.manage')
        <a href="{{ route('roles.index') }}"
           class="nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="fas fa-key"></i>
            <span>Roles & Permissions</span>
        </a>
        @endcan
        @can('recycle-bin.manage')
        <a href="{{ route('recycle-bin.index') }}"
           class="nav-item {{ request()->routeIs('recycle-bin.*') ? 'active' : '' }}">
            <i class="fas fa-box-archive"></i>
            <span>Recycle Bin</span>
        </a>
        @endcan
        @endcanany

    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        @php
            $sbDbOk  = true;
            try { \DB::connection()->getPdo(); } catch (\Exception $e) { $sbDbOk = false; }
            $sbDir   = storage_path('app/backups');
            $sbFiles = array_merge(glob($sbDir.'/*.sql') ?: [], glob($sbDir.'/*.gz') ?: []);
            $sbTs    = $sbFiles ? max(array_map('filemtime', $sbFiles)) : null;
            $sbAgo   = $sbTs ? \Carbon\Carbon::createFromTimestamp($sbTs)->diffForHumans() : 'No backup';
            $sbBkOk  = $sbTs && (time() - $sbTs < 86400 * 7);
        @endphp
        <div class="sidebar-health">
            <div class="sh-label">System Status</div>
            <div class="sh-row">
                <span class="sh-dot {{ $sbDbOk ? 'ok' : 'offline' }}"></span>
                <span>{{ $sbDbOk ? 'Database Online' : 'Database Error' }}</span>
            </div>
            <div class="sh-row">
                <span class="sh-dot {{ $sbBkOk ? 'ok' : 'warn' }}"></span>
                <span>Backup: {{ $sbAgo }}</span>
            </div>
        </div>
        <div class="sidebar-user">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()?->name ?? 'User', 0, 1)) }}
            </div>
            <div class="user-info" style="flex:1;min-width:0">
                <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ auth()->user()?->name ?? 'User' }}
                </div>
                <div class="user-role">{{ auth()->user()?->role ?? 'Staff' }}</div>
            </div>
            <button type="button" onclick="bmsLogout()"
                    style="background:none;border:none;color:rgba(255,255,255,0.35);cursor:pointer;padding:4px;font-size:13px;flex-shrink:0"
                    title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

</aside>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

@can('appointments.view')
<script>
/* ── Portal pending badges — SSE with polling fallback ─── */
(function () {

    /* ── DOM helpers ──────────────────────────────────────── */
    function setBadge(id, n) {
        var el = document.getElementById(id);
        if (!el) return;
        var prev = parseInt(el.textContent, 10) || 0;
        el.textContent   = n > 99 ? '99+' : String(n);
        el.style.display = n > 0 ? 'inline-block' : 'none';
        // Pulse animation when count changes
        if (n !== prev) {
            el.style.transition = 'opacity .2s';
            el.style.opacity    = '.35';
            setTimeout(function () { el.style.opacity = '1'; }, 220);
        }
    }

    function updatePortalBadges(data) {
        // Module sidebar badges show in-progress counts (Active blotter, For Review business)
        setBadge('pblBadge',  data.blotter_active || 0);
        setBadge('pbizBadge', data.biz_for_review || 0);
        // Appointments badge = total Pending (new, not yet triaged)
        var total = (data.documents || 0) + (data.blotter || 0) + (data.business || 0);
        setBadge('portalPendingBadge', total);

        // Keep the Appointments page tab badges in sync (show Pending counts per tab)
        var tabBlotter = document.getElementById('tabBadgeBlotter');
        var tabBiz     = document.getElementById('tabBadgeBiz');
        if (tabBlotter) tabBlotter.textContent = data.blotter  || 0;
        if (tabBiz)     tabBiz.textContent     = data.business || 0;
    }

    /* ── Fallback: one-shot AJAX fetch ───────────────────── */
    function fetchPendingCount() {
        axios.get('{{ route('portal.pending-count') }}')
            .then(function (res) { updatePortalBadges(res.data); })
            .catch(function () { /* silent */ });
    }

    // Expose globally so any action handler can force an immediate refresh
    window.refreshPortalBadges = fetchPendingCount;

    /* ── SSE connection ───────────────────────────────────── */
    var _sseActive   = false;
    var _pollTimer   = null;
    var _sseUrl      = '{{ route('portal.badge-stream') }}';

    function startFallbackPoll() {
        if (_pollTimer) return;                  // already running
        fetchPendingCount();
        _pollTimer = setInterval(fetchPendingCount, 15000);
    }

    function stopFallbackPoll() {
        if (_pollTimer) { clearInterval(_pollTimer); _pollTimer = null; }
    }

    function connectSSE() {
        if (!window.EventSource) { startFallbackPoll(); return; }

        var sse = new EventSource(_sseUrl);

        sse.onopen = function () {
            _sseActive = true;
            stopFallbackPoll();   // SSE running — no need to poll
        };

        sse.onmessage = function (e) {
            try { updatePortalBadges(JSON.parse(e.data)); } catch (_) {}
        };

        sse.onerror = function () {
            // EventSource auto-reconnects; while disconnected, poll as backup
            _sseActive = false;
            startFallbackPoll();
        };

        // When SSE reconnects successfully, stop the backup poll again
        sse.addEventListener('open', function () {
            if (_sseActive) return;
            _sseActive = true;
            stopFallbackPoll();
        });

        return sse;
    }

    /* ── Boot ─────────────────────────────────────────────── */
    document.addEventListener('DOMContentLoaded', function () {
        fetchPendingCount();   // Instant first load
        connectSSE();          // Live stream thereafter
    });

    // Re-fetch immediately when the user brings the tab back into focus
    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'visible') fetchPendingCount();
    });

}());
</script>
@endcan
{{-- Part 2: behaviour of the nested religion filter (Admin only) --}}
@can('view-religion-data')
<script>
(function () {
    const tree = document.getElementById('religionTree');
    if (!tree) return;
    const body   = document.getElementById('religionTreeBody');
    const toggle = document.getElementById('religionTreeToggle');
    const select = document.getElementById('navPabahayUnit');

    // What is selected right now (kept in step with the Residents page)
    let current = { group: tree.dataset.group || '', fom: tree.dataset.fom === '1', unit: tree.dataset.unit || '' };

    function setOpen(open) {
        body.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        try { localStorage.setItem('bms_religion_tree', open ? '1' : '0'); } catch (e) {}
    }
    toggle.addEventListener('click', () => setOpen(body.hidden));
    try { if (tree.dataset.open !== '1' && localStorage.getItem('bms_religion_tree') === '1') setOpen(true); } catch (e) {}

    // Reflect a selection in the tree: deepest choice = active, the levels above it = on-path
    window.syncReligionTree = function (s) {
        current = { group: s.group || '', fom: !!s.fom, unit: s.unit || '' };
        tree.querySelectorAll('[data-level="1"]').forEach(a => {
            const here = a.dataset.group === current.group;
            a.classList.toggle('active', here && !(a.dataset.group === 'inc' && current.fom));
            a.classList.toggle('on-path', here && a.dataset.group === 'inc' && current.fom);
        });
        tree.querySelector('[data-children="inc"]').classList.toggle('open', current.group === 'inc');
        const fom = tree.querySelector('[data-level="2"]');
        fom.classList.toggle('active', current.fom && !current.unit);
        fom.classList.toggle('on-path', !!current.unit);
        tree.querySelector('.nav-unit-wrap').style.display = current.fom ? '' : 'none';
        select.value = current.unit;
        if (current.group) setOpen(true);
    };

    // On the Residents page the table filters in place; anywhere else the link just opens it
    function go(state, fallbackUrl) {
        if (window.applyReligionFilter) { window.applyReligionFilter(state); return true; }
        if (fallbackUrl) window.location.href = fallbackUrl;
        return false;
    }

    tree.addEventListener('click', function (e) {
        const a = e.target.closest('a.nav-sub');
        if (!a || !window.applyReligionFilter) return;   // not on the Residents page: follow the link
        e.preventDefault();

        if (a.dataset.level === '2') {
            // Family of Ministers: click again to go back up to INC
            go(current.fom && !current.unit ? { group: 'inc' } : { group: 'inc', fom: true });
        } else {
            // A group: click again to clear the filter
            const same = current.group === a.dataset.group && !current.fom && !current.unit;
            go(same ? {} : { group: a.dataset.group });
        }
    });

    select.addEventListener('change', function () {
        const query = new URLSearchParams({ religion_group: 'inc', fom: '1' });
        if (select.value) query.set('pabahay_unit', select.value);
        go({ group: 'inc', fom: true, unit: select.value }, tree.dataset.url + '?' + query);
    });
})();
</script>
@endcan
