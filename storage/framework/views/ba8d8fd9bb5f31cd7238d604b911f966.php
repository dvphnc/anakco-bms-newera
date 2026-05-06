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
        </a>

        <a href="<?php echo e(route('businesses.index')); ?>"
           class="nav-item <?php echo e(request()->routeIs('businesses.*') ? 'active' : ''); ?>">
            <i class="fas fa-store"></i>
            <span>Business Permits</span>
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
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="flex-shrink:0">
                <?php echo csrf_field(); ?>
                <button type="submit"
                        style="background:none;border:none;color:rgba(255,255,255,0.35);cursor:pointer;padding:4px;font-size:13px"
                        title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>

</aside>


<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div><?php /**PATH D:\laragon\www\anakco_bms\resources\views/partials/_sidebar.blade.php ENDPATH**/ ?>