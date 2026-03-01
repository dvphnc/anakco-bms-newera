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

        <div class="nav-section-label">Records</div>

        <a href="{{ route('residents.index') }}"
           class="nav-item {{ request()->routeIs('residents.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Residents</span>
        </a>

        <a href="{{ route('households.index') }}"
           class="nav-item {{ request()->routeIs('households.*') ? 'active' : '' }}">
            <i class="fas fa-house"></i>
            <span>Households & Purok</span>
        </a>

        <a href="{{ route('officials.index') }}"
           class="nav-item {{ request()->routeIs('officials.*') ? 'active' : '' }}">
            <i class="fas fa-user-tie"></i>
            <span>Officials & Staff</span>
        </a>

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
        </a>

        <a href="{{ route('businesses.index') }}"
           class="nav-item {{ request()->routeIs('businesses.*') ? 'active' : '' }}">
            <i class="fas fa-store"></i>
            <span>Business Permits</span>
        </a>

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

        <div class="nav-section-label">System</div>

        <a href="{{ route('reports.index') }}"
           class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar"></i>
            <span>Reports & Analytics</span>
        </a>

        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-user-shield"></i>
            <span>User Management</span>
        </a>

    </nav>

    {{-- Sidebar Footer --}}
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-info" style="flex:1;min-width:0">
                <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ auth()->user()->name }}
                </div>
                <div class="user-role">{{ auth()->user()->role ?? 'Staff' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" style="flex-shrink:0">
                @csrf
                <button type="submit"
                        style="background:none;border:none;color:rgba(255,255,255,0.35);cursor:pointer;padding:4px;font-size:13px"
                        title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>