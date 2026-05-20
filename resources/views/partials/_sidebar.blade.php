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

        @php $role = auth()->user()?->role; @endphp

        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        {{-- Records — Admin + Secretary only --}}
        @if(in_array($role, ['Admin', 'Secretary']))
        <div class="nav-section-label">Records</div>

        <a href="{{ route('residents.index') }}"
           class="nav-item {{ request()->routeIs('residents.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Residents</span>
        </a>

        <a href="{{ route('households.index') }}"
           class="nav-item {{ request()->routeIs('households.*') ? 'active' : '' }}">
            <i class="fas fa-house"></i>
            <span>Households</span>
        </a>

        <a href="{{ route('puroks.index') }}"
           class="nav-item {{ request()->routeIs('puroks.*') ? 'active' : '' }}">
            <i class="fas fa-location-dot"></i>
            <span>Puroks</span>
        </a>

        <a href="{{ route('officials.index') }}"
           class="nav-item {{ request()->routeIs('officials.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            <span>Officials & Staff</span>
        </a>
        @endif

        {{-- Services — Admin + Secretary only --}}
        @if(in_array($role, ['Admin', 'Secretary']))
        <div class="nav-section-label">Services</div>

        <a href="{{ route('documents.index') }}"
           class="nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
            <i class="fas fa-file-alt"></i>
            <span>Document Issuance</span>
        </a>

        <a href="{{ route('blotter.index') }}"
           class="nav-item {{ request()->routeIs('blotter.*') ? 'active' : '' }}">
            <i class="fas fa-gavel"></i>
            <span>Blotter Records</span>
            <span id="pblBadge"
                  style="display:none;background:var(--gold);color:var(--navy);font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Portal submissions pending"></span>
        </a>

        <a href="{{ route('businesses.index') }}"
           class="nav-item {{ request()->routeIs('businesses.*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Business Permits</span>
            <span id="pbizBadge"
                  style="display:none;background:var(--gold);color:var(--navy);font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Portal submissions pending"></span>
        </a>

        <a href="{{ route('appointments.index') }}"
           class="nav-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-check"></i>
            <span>Appointments</span>
            <span id="portalPendingBadge"
                  style="display:none;background:var(--crimson);color:#fff;font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Portal document requests pending"></span>
        </a>

        <a href="{{ route('portal.index') }}" target="_blank"
           class="nav-item">
            <i class="fas fa-globe"></i>
            <span>Resident Portal</span>
            <i class="fas fa-arrow-up-right-from-square" style="font-size:9px;margin-left:auto;opacity:.5"></i>
        </a>
        @endif

        {{-- Committees — all roles --}}
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

        {{-- System — Admin + Secretary for Reports, Admin only for Users --}}
        @if(in_array($role, ['Admin', 'Secretary']))
        <div class="nav-section-label">System</div>

        <a href="{{ route('reports.index') }}"
           class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Reports & Analytics</span>
        </a>

        <a href="{{ route('activity-log.index') }}"
           class="nav-item {{ request()->routeIs('activity-log.*') ? 'active' : '' }}">
            <i class="fas fa-clock-rotate-left"></i>
            <span>Activity Log</span>
        </a>

        @if($role === 'Admin')
        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>User Management</span>
        </a>
        @endif
        @endif

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

@if(in_array(auth()->user()?->role, ['Admin', 'Secretary']))
<script>
/* ── Portal pending badge polling ──────────────────────── */
(function () {
    function setBadge(id, n) {
        var el = document.getElementById(id);
        if (!el) return;
        el.textContent    = n > 99 ? '99+' : n;
        el.style.display  = n > 0  ? 'inline-block' : 'none';
    }

    function updatePortalBadges(data) {
        setBadge('pblBadge',           data.blotter   || 0);
        setBadge('pbizBadge',          data.business  || 0);
        setBadge('portalPendingBadge', data.documents || 0);
    }

    function fetchPendingCount() {
        axios.get('{{ route('portal.pending-count') }}')
            .then(function (res) { updatePortalBadges(res.data); })
            .catch(function () { /* silent */ });
    }

    document.addEventListener('DOMContentLoaded', function () {
        fetchPendingCount();
        setInterval(fetchPendingCount, 60000);
    });
})();
</script>
@endif