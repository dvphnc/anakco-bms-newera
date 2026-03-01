<header class="topbar">

    {{-- Left: Page Title --}}
    <div class="topbar-left">
        {{-- Mobile menu toggle --}}
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h2 class="topbar-title">@yield('page-title', 'Dashboard')</h2>
            <p class="topbar-subtitle">@yield('page-subtitle', 'Barangay New Era, District VI, Quezon City')</p>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="topbar-right">

        {{-- Current Date --}}
        <div class="topbar-date">
            <i class="fas fa-calendar-days"></i>
            <span>{{ now()->format('F d, Y') }}</span>
        </div>

        {{-- Divider --}}
        <div class="topbar-divider"></div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="topbar-btn" title="Logout">
                <i class="fas fa-right-from-bracket"></i>
                <span>Logout</span>
            </button>
        </form>

    </div>

</header>