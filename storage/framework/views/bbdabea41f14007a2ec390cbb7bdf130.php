<aside class="sidebar" id="sidebar">

    
    <div class="sidebar-brand">
        <div class="brand-logo">
            <img src="<?php echo e(asset('images/bne-logo.png')); ?>"
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

    
    <nav class="sidebar-nav">

        <?php $role = auth()->user()?->role; ?>

        <div class="nav-section-label">Main</div>

        <a href="<?php echo e(route('dashboard')); ?>"
           class="nav-item <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        
        <?php if(in_array($role, ['Admin', 'Secretary'])): ?>
        <div class="nav-section-label">Records</div>

        <a href="<?php echo e(route('residents.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('residents.*') ? 'active' : ''); ?>">
            <i class="fas fa-users"></i>
            <span>Residents</span>
        </a>

        <a href="<?php echo e(route('households.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('households.*') ? 'active' : ''); ?>">
            <i class="fas fa-house"></i>
            <span>Households</span>
        </a>

        <a href="<?php echo e(route('puroks.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('puroks.*') ? 'active' : ''); ?>">
            <i class="fas fa-location-dot"></i>
            <span>Puroks</span>
        </a>

        <a href="<?php echo e(route('officials.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('officials.*') ? 'active' : ''); ?>">
            <i class="fas fa-user-tie"></i>
            <span>Officials & Staff</span>
        </a>
        <?php endif; ?>

        
        <?php if(in_array($role, ['Admin', 'Secretary'])): ?>
        <div class="nav-section-label">Services</div>

        <a href="<?php echo e(route('documents.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('documents.*') ? 'active' : ''); ?>">
            <i class="fas fa-file-alt"></i>
            <span>Document Issuance</span>
        </a>

        <a href="<?php echo e(route('blotter.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('blotter.*') ? 'active' : ''); ?>">
            <i class="fas fa-gavel"></i>
            <span>Blotter Records</span>
            <span id="pblBadge"
                  style="display:none;background:var(--gold);color:var(--navy);font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Active portal blotter cases"></span>
        </a>

        <a href="<?php echo e(route('businesses.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('businesses.*') ? 'active' : ''); ?>">
            <i class="fas fa-store"></i>
            <span>Business Permits</span>
            <span id="pbizBadge"
                  style="display:none;background:var(--gold);color:var(--navy);font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Business applications under review"></span>
        </a>

        <a href="<?php echo e(route('appointments.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('appointments.*') ? 'active' : ''); ?>">
            <i class="fas fa-calendar-check"></i>
            <span>Appointments</span>
            <span id="portalPendingBadge"
                  style="display:none;background:var(--crimson);color:#fff;font-size:9px;
                         font-weight:700;padding:1px 6px;border-radius:99px;min-width:18px;
                         text-align:center;line-height:16px;margin-left:auto"
                  title="Total portal submissions pending (documents + blotter + business)"></span>
        </a>

        <a href="<?php echo e(route('portal.index')); ?>" target="_blank"
           class="nav-item">
            <i class="fas fa-globe"></i>
            <span>Resident Portal</span>
            <i class="fas fa-arrow-up-right-from-square" style="font-size:9px;margin-left:auto;opacity:.5"></i>
        </a>
        <?php endif; ?>

        
        <div class="nav-section-label">Committees</div>

        <a href="<?php echo e(route('committees.show', 'peace-order')); ?>"
           class="nav-item <?php echo e(request()->is('committees/peace-order*') ? 'active' : ''); ?>">
            <i class="fas fa-shield-halved"></i>
            <span>Peace & Order</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'health')); ?>"
           class="nav-item <?php echo e(request()->is('committees/health*') ? 'active' : ''); ?>">
            <i class="fas fa-heartbeat"></i>
            <span>Health</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'education')); ?>"
           class="nav-item <?php echo e(request()->is('committees/education*') ? 'active' : ''); ?>">
            <i class="fas fa-graduation-cap"></i>
            <span>Education</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'infrastructure')); ?>"
           class="nav-item <?php echo e(request()->is('committees/infrastructure*') ? 'active' : ''); ?>">
            <i class="fas fa-road"></i>
            <span>Infrastructure</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'environment')); ?>"
           class="nav-item <?php echo e(request()->is('committees/environment*') ? 'active' : ''); ?>">
            <i class="fas fa-leaf"></i>
            <span>Environment</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'livelihood')); ?>"
           class="nav-item <?php echo e(request()->is('committees/livelihood*') ? 'active' : ''); ?>">
            <i class="fas fa-briefcase"></i>
            <span>Livelihood</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'transport')); ?>"
           class="nav-item <?php echo e(request()->is('committees/transport*') ? 'active' : ''); ?>">
            <i class="fas fa-bus"></i>
            <span>Transport & Comm.</span>
        </a>

        <a href="<?php echo e(route('committees.show', 'bdrrm')); ?>"
           class="nav-item <?php echo e(request()->is('committees/bdrrm*') ? 'active' : ''); ?>">
            <i class="fas fa-exclamation-triangle"></i>
            <span>BDRRM</span>
        </a>

        
        <?php if(in_array($role, ['Admin', 'Secretary'])): ?>
        <div class="nav-section-label">System</div>

        <a href="<?php echo e(route('reports.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
            <i class="fas fa-chart-bar"></i>
            <span>Reports & Analytics</span>
        </a>

        <a href="<?php echo e(route('activity-log.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('activity-log.*') ? 'active' : ''); ?>">
            <i class="fas fa-clock-rotate-left"></i>
            <span>Activity Log</span>
        </a>

        <?php if($role === 'Admin'): ?>
        <a href="<?php echo e(route('users.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
            <i class="fas fa-user-shield"></i>
            <span>User Management</span>
        </a>
        <?php endif; ?>
        <?php endif; ?>

    </nav>

    
    <div class="sidebar-footer">
        <?php
            $sbDbOk  = true;
            try { \DB::connection()->getPdo(); } catch (\Exception $e) { $sbDbOk = false; }
            $sbDir   = storage_path('app/backups');
            $sbFiles = array_merge(glob($sbDir.'/*.sql') ?: [], glob($sbDir.'/*.gz') ?: []);
            $sbTs    = $sbFiles ? max(array_map('filemtime', $sbFiles)) : null;
            $sbAgo   = $sbTs ? \Carbon\Carbon::createFromTimestamp($sbTs)->diffForHumans() : 'No backup';
            $sbBkOk  = $sbTs && (time() - $sbTs < 86400 * 7);
        ?>
        <div class="sidebar-health">
            <div class="sh-label">System Status</div>
            <div class="sh-row">
                <span class="sh-dot <?php echo e($sbDbOk ? 'ok' : 'offline'); ?>"></span>
                <span><?php echo e($sbDbOk ? 'Database Online' : 'Database Error'); ?></span>
            </div>
            <div class="sh-row">
                <span class="sh-dot <?php echo e($sbBkOk ? 'ok' : 'warn'); ?>"></span>
                <span>Backup: <?php echo e($sbAgo); ?></span>
            </div>
        </div>
        <div class="sidebar-user">
            <div class="user-avatar">
                <?php echo e(strtoupper(substr(auth()->user()?->name ?? 'User', 0, 1))); ?>

            </div>
            <div class="user-info" style="flex:1;min-width:0">
                <div class="user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    <?php echo e(auth()->user()?->name ?? 'User'); ?>

                </div>
                <div class="user-role"><?php echo e(auth()->user()?->role ?? 'Staff'); ?></div>
            </div>
            <button type="button" onclick="bmsLogout()"
                    style="background:none;border:none;color:rgba(255,255,255,0.35);cursor:pointer;padding:4px;font-size:13px;flex-shrink:0"
                    title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
    </div>

</aside>


<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<?php if(in_array(auth()->user()?->role, ['Admin', 'Secretary'])): ?>
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
        axios.get('<?php echo e(route('portal.pending-count')); ?>')
            .then(function (res) { updatePortalBadges(res.data); })
            .catch(function () { /* silent */ });
    }

    // Expose globally so any action handler can force an immediate refresh
    window.refreshPortalBadges = fetchPendingCount;

    /* ── SSE connection ───────────────────────────────────── */
    var _sseActive   = false;
    var _pollTimer   = null;
    var _sseUrl      = '<?php echo e(route('portal.badge-stream')); ?>';

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
<?php endif; ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views\partials\_sidebar.blade.php ENDPATH**/ ?>