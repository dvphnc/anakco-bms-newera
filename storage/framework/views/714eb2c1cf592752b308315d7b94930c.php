<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>
<style>
<<<<<<< Updated upstream
/* ── Plain card-header (no gold bar) ────────────────────── */
.card-header-plain { border-left:none; background:var(--surface); padding-bottom:12px; }
.card-header-plain .card-title i { color:var(--text-subtle); }
=======
/* ── Alert Strip ─────────────────────────────────────────── */
.alert-strip { display:flex; flex-direction:column; gap:8px; margin-bottom:24px; }
.alert-item  { display:flex; align-items:center; gap:10px; padding:10px 16px; border-radius:var(--radius); font-size:14px; }
.alert-item i { flex-shrink:0; font-size:14px; }
.alert-item > span { flex:1; line-height:1.5; }
.alert-senior  { background:var(--gold-pale);    border:1px solid var(--gold-border);    border-left:4px solid var(--gold);    color:#78450a; }
.alert-birthday{ background:var(--navy-pale);    border:1px solid var(--navy-border);    border-left:4px solid var(--navy);    color:var(--navy); }
.alert-permit  { background:var(--crimson-pale); border:1px solid var(--crimson-border); border-left:4px solid var(--crimson); color:var(--crimson); }
.alert-link {
    font-size:13px; font-weight:600; color:inherit; opacity:.85;
    text-decoration:none; padding:5px 14px; border:1px solid currentColor;
    border-radius:99px; white-space:nowrap; flex-shrink:0;
}
.alert-link:hover { opacity:1; }
>>>>>>> Stashed changes

/* ── Dashboard Tab Strip ─────────────────────────────────── */
.dash-tabs {
    display: flex;
    gap: 2px;
    margin-bottom: 20px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 6px;
    width: fit-content;
}
.dash-tab-btn {
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    padding: 8px 18px;
    min-height: 38px;
    font-size: 13px;
=======
    padding: 10px 22px;
    min-height: 44px;
    font-size: 14px;
>>>>>>> Stashed changes
=======
    padding: 10px 22px;
    min-height: 44px;
    font-size: 14px;
>>>>>>> Stashed changes
=======
    padding: 10px 22px;
    min-height: 44px;
    font-size: 14px;
>>>>>>> Stashed changes
    font-weight: 600;
    color: var(--text-muted);
    background: none;
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    white-space: nowrap;
    font-family: 'Poppins', sans-serif;
    transition: all .15s;
}
.dash-tab-btn:hover { color: var(--navy); background: var(--surface2); }
.dash-tab-btn.active { background: var(--navy); color: #fff; }
.dash-tab-btn .tab-badge {
    font-size: 11px; font-weight: 700;
    padding: 1px 6px; border-radius: 99px;
    background: rgba(255,255,255,.25);
    color: inherit;
}
.dash-tab-btn:not(.active) .tab-badge { background: var(--gold-pale); color: var(--gold); }

.dash-panel { display: none; }
.dash-panel.active { display: block; }

/* ── Stat Cards ──────────────────────────────────────────── */
.dash-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:20px; }
.dash-stat-card {
    display:flex; align-items:center; gap:12px;
    background:#fff; border:none;
    border-radius:var(--radius-lg); padding:14px 16px;
    text-decoration:none;
    box-shadow:0 2px 12px rgba(13,33,68,0.08), 0 1px 3px rgba(0,0,0,0.04);
    transition:box-shadow .2s, transform .2s;
    position:relative; overflow:hidden;
}
.dash-stat-card::before {
    content:''; position:absolute; left:0; top:0; bottom:0;
    width:3px; border-radius:4px 0 0 4px;
    background:var(--gold); opacity:.55;
}
.dash-stat-card:hover {
    box-shadow:0 8px 28px rgba(13,33,68,0.13), 0 2px 8px rgba(0,0,0,0.06);
    transform:translateY(-3px);
}
.dash-stat-card:hover .dash-stat-icon {
    background:var(--navy) !important; color:#fff !important;
}
.dash-stat-icon {
    width:38px; height:38px; border-radius:var(--radius);
    display:flex; align-items:center; justify-content:center;
    font-size:15px; flex-shrink:0;
    background:#F1F5F9; color:var(--navy);
    transition:background .2s, color .2s;
}
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
.dash-stat-number { font-size:22px; font-weight:800; color:var(--navy); line-height:1.1; letter-spacing:-0.01em; }
.dash-stat-label  { font-size:12px; color:var(--text-muted); margin-top:3px; font-weight:400; line-height:1.4; }

/* ── Command Bar ─────────────────────────────────────────── */
.cmd-bar {
    display:flex; align-items:center; gap:8px; flex-wrap:nowrap; overflow-x:auto;
    background:#fff; border-radius:var(--radius-lg);
    padding:10px 16px; margin-bottom:20px;
    box-shadow:0 1px 6px rgba(13,33,68,0.07);
    scrollbar-width:none;
}
.cmd-bar::-webkit-scrollbar { display:none; }
.cmd-bar-label {
    font-size:11px; font-weight:700; text-transform:uppercase;
    letter-spacing:.1em; color:var(--text-subtle); margin-right:4px; flex-shrink:0;
}
.cmd-bar-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:7px 13px; border-radius:var(--radius-sm); min-height:36px;
    font-size:13px; font-weight:600; font-family:'Poppins',sans-serif;
    cursor:pointer; transition:all .15s; border:1px solid transparent;
    text-decoration:none; white-space:nowrap; flex-shrink:0;
}
.cmd-bar-btn-primary {
    background:var(--navy); color:#fff; border-color:var(--navy);
}
.cmd-bar-btn-primary:hover { background:var(--navy-mid); box-shadow:0 4px 14px rgba(13,33,68,0.22); }
.cmd-bar-btn-gold {
    background:var(--gold); color:#fff; border-color:var(--gold);
}
.cmd-bar-btn-gold:hover { background:var(--gold-light); box-shadow:var(--shadow-gold); }
.cmd-bar-btn-ghost {
    background:var(--surface2); color:var(--text-muted); border-color:var(--border);
}
.cmd-bar-btn-ghost:hover { background:var(--navy-pale); color:var(--navy); border-color:var(--navy-border); }
.cmd-bar-divider { width:1px; height:26px; background:var(--border); flex-shrink:0; margin:0 4px; }
=======
.dash-stat-number { font-size:28px; font-weight:700; color:var(--text); line-height:1.1; }
.dash-stat-label  { font-size:14px; color:var(--text-muted); margin-top:4px; }
>>>>>>> Stashed changes
=======
.dash-stat-number { font-size:28px; font-weight:700; color:var(--text); line-height:1.1; }
.dash-stat-label  { font-size:14px; color:var(--text-muted); margin-top:4px; }
>>>>>>> Stashed changes
=======
.dash-stat-number { font-size:28px; font-weight:700; color:var(--text); line-height:1.1; }
.dash-stat-label  { font-size:14px; color:var(--text-muted); margin-top:4px; }
>>>>>>> Stashed changes

/* ── Mid Row: Chart + Quick Access ───────────────────────── */
.dash-mid { display:grid; grid-template-columns:2fr 1fr; gap:16px; margin-bottom:24px; }

.quick-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.quick-item {
    display:flex; flex-direction:column; align-items:center; gap:7px;
    padding:14px 6px; background:#fff;
    border:1px solid var(--border); border-radius:var(--radius);
    text-decoration:none; transition:all .2s;
}
<<<<<<< Updated upstream
.quick-item:hover { background:var(--navy-pale); border-color:var(--navy-border); }
.quick-item:hover .quick-icon { background:var(--navy) !important; color:#fff !important; }
.quick-icon {
    width:40px; height:40px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; font-size:15px;
    background:#F1F5F9; color:var(--navy);
    transition:background .2s, color .2s;
}
.quick-label { font-size:12.5px; font-weight:600; color:var(--text); text-align:center; line-height:1.3; }
=======
.quick-item:hover { background:var(--navy-pale); border-color:var(--qa-color, var(--navy)); }
.quick-icon { width:38px; height:38px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:16px; }
.quick-label { font-size:13px; font-weight:600; color:var(--text); text-align:center; }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes

/* ── Bottom 2-col ────────────────────────────────────────── */
.dash-bottom { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

/* ── Feed Rows (Audit 1: compact density) ───────────────── */
.feed-row {
    display:flex; align-items:center; gap:10px;
    padding:8px 14px; border-bottom:1px solid var(--border);
    text-decoration:none; transition:background .1s;
}
.feed-row:last-child { border-bottom:none; }
.feed-row:hover { background:var(--navy-pale); }
.feed-icon { width:30px; height:30px; border-radius:50%; background:#F1F5F9; color:var(--navy); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:12px; }
.feed-body { flex:1; min-width:0; }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
.feed-title { font-size:13px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:12px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
=======
.feed-title { font-size:14px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:13px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px; }
>>>>>>> Stashed changes
=======
.feed-title { font-size:14px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:13px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px; }
>>>>>>> Stashed changes
=======
.feed-title { font-size:14px; font-weight:600; color:var(--text); font-family:monospace; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.feed-sub   { font-size:13px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px; }
>>>>>>> Stashed changes

/* ── Analytics Tab ───────────────────────────────────────── */
.analytics-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:24px; }
.demog-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; }
.demog-item {
    background:var(--surface2); border:1px solid var(--border);
    border-radius:var(--radius); padding:14px 16px; text-align:center;
}
.demog-num { font-size:22px; font-weight:700; color:var(--navy); line-height:1.1; }
.demog-lbl { font-size:13px; color:var(--text-muted); margin-top:4px; }

/* ── Appointment Status Badges (dashboard) ───────────────── */
.apt-badge {
    display:inline-block; padding:2px 8px; border-radius:999px;
    font-size:11px; font-weight:700; border:1.5px solid;
}
.apt-pending    { background:var(--gold-pale);    color:#78450a; border-color:var(--gold-border); }
.apt-confirmed  { background:var(--navy-pale);    color:var(--navy); border-color:var(--navy-border); }
.apt-processing { background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
.apt-ready      { background:#f0fdf4; color:#14532d; border-color:#bbf7d0; }
.apt-released   { background:#f9fafb; color:#6b7280; border-color:#d1d5db; }
.apt-cancelled  { background:var(--crimson-pale); color:var(--crimson); border-color:var(--crimson-border); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="cc-banner no-print" style="
    background:linear-gradient(135deg, var(--navy) 0%, var(--navy-mid) 60%, #1a3a6e 100%);
    border-radius:var(--radius-lg); padding:28px 32px; margin-bottom:20px;
    position:relative; overflow:hidden; box-shadow:0 8px 32px rgba(13,33,68,0.22)">

    
    <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;
                background:radial-gradient(circle, rgba(200,134,26,0.12) 0%, transparent 65%);
                pointer-events:none"></div>
    <div style="position:absolute;bottom:-60px;left:-40px;width:260px;height:260px;
                background:radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%);
                pointer-events:none"></div>

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <div style="position:relative;display:flex;align-items:center;justify-content:space-between;
=======
    <div style="position:relative;display:flex;align-items:flex-start;justify-content:space-between;
>>>>>>> Stashed changes
=======
    <div style="position:relative;display:flex;align-items:flex-start;justify-content:space-between;
>>>>>>> Stashed changes
=======
    <div style="position:relative;display:flex;align-items:flex-start;justify-content:space-between;
>>>>>>> Stashed changes
                gap:24px;flex-wrap:wrap">

        
        <div style="display:flex;align-items:center;gap:16px">
            <div style="width:56px;height:56px;border-radius:50%;border:2px solid rgba(200,134,26,0.5);
                        background:#fff;overflow:hidden;flex-shrink:0;
                        box-shadow:0 0 0 4px rgba(200,134,26,0.1)">
                <img src="<?php echo e(asset('images/bne-logo.png')); ?>" alt="BNE Logo"
                     style="width:100%;height:100%;object-fit:cover"
                     onerror="this.style.display='none';this.parentNode.style.background='linear-gradient(135deg,var(--gold),var(--gold-light))'">
            </div>
            <div>
                <div style="font-size:11px;font-weight:700;letter-spacing:0.14em;text-transform:uppercase;
                            color:rgba(229,160,32,0.9);margin-bottom:2px">
                    Command Center
                </div>
                <div style="font-size:20px;font-weight:800;color:#fff;line-height:1.15;letter-spacing:-0.01em">
                    Barangay New Era
                </div>
                <div style="font-size:13px;color:rgba(255,255,255,0.55);font-weight:300;margin-top:1px">
                    District VI, Quezon City
                </div>
            </div>
        </div>

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream

        <div style="text-align:right">
            <div style="display:inline-flex;align-items:center;gap:7px;
                        background:rgba(255,255,255,0.09);border:1px solid rgba(255,255,255,0.13);
                        border-radius:99px;padding:4px 12px 4px 7px;margin-bottom:8px">
                <span style="width:22px;height:22px;border-radius:50%;
                              background:var(--gold);color:var(--navy);
                              display:flex;align-items:center;justify-content:center;
                              font-size:10px;font-weight:800;flex-shrink:0">
                    <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                </span>
                <span style="font-size:11px;font-weight:600;color:rgba(255,255,255,0.85);
                             letter-spacing:0.04em"><?php echo e(auth()->user()->role ?? 'Staff'); ?></span>
            </div>
            
            <div style="font-size:17px;font-weight:700;color:#fff;letter-spacing:0.01em;line-height:1.3">
                Welcome back, <span style="color:var(--gold-light)"><?php echo e(auth()->user()->name); ?></span>
            </div>
            
            <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;margin-top:4px">
                <span id="cc-clock-date"
                      style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.6);
                             font-family:monospace;letter-spacing:0.05em">
                    <?php echo e(now()->format('l, F d, Y')); ?>

                </span>
                <span style="color:rgba(255,255,255,0.2);font-size:11px">·</span>
                <span id="cc-clock-time"
                      style="font-size:13px;font-weight:600;color:rgba(255,255,255,0.6);
                             font-family:monospace;letter-spacing:0.05em">
                </span>
            </div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        
        <div style="display:flex;align-items:center;gap:12px;
                    background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.10);
                    border-radius:var(--radius);padding:12px 18px;backdrop-filter:blur(8px)">
            <div style="width:40px;height:40px;border-radius:50%;
                        background:linear-gradient(135deg,var(--gold),var(--gold-light));
                        display:flex;align-items:center;justify-content:center;
                        font-size:15px;font-weight:800;color:var(--navy);flex-shrink:0">
                R
            </div>
            <div>
                <div style="font-size:10px;font-weight:600;letter-spacing:0.1em;
                            text-transform:uppercase;color:rgba(229,160,32,0.8);margin-bottom:2px">
                    Punong Barangay
                </div>
                <div style="font-size:15px;font-weight:700;color:#fff;line-height:1.2">
                    Robert S. Romano
                </div>
            </div>
        </div>

        
        <div style="text-align:right">
            <div style="font-size:13px;color:rgba(255,255,255,0.55);font-weight:300">
                <?php echo e(now()->format('l, F d, Y')); ?>

            </div>
            <div style="font-size:16px;font-weight:600;color:#fff;margin-top:2px">
                Welcome back, <span style="color:var(--gold-light)"><?php echo e(auth()->user()->name); ?></span>
            </div>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
            <div style="margin-top:10px;display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap">
                <button id="startTourBtn"
                        class="btn btn-sm"
                        data-tippy-content="Take a guided tour of the Dashboard"
                        style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);
                               min-height:34px;font-size:12px;padding:6px 14px">
                    <i class="fas fa-map" style="color:var(--gold-light)"></i> Take Tour
                </button>
                <a href="<?php echo e(route('residents.create')); ?>"
                   style="background:rgba(255,255,255,0.10);color:#fff;border:1px solid rgba(255,255,255,0.20);
                          min-height:34px;font-size:12px;padding:6px 14px"
                   class="btn btn-sm">
                    <i class="fas fa-user-plus"></i> New Resident
                </a>
                <a href="<?php echo e(route('documents.create')); ?>"
                   style="background:var(--gold);color:#fff;border:none;
                          min-height:34px;font-size:12px;padding:6px 14px"
                   class="btn btn-sm">
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
                    <i class="fas fa-file-circle-plus"></i> New Document
                </a>
            </div>
        </div>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
                    <i class="fas fa-file-plus"></i> New Document
                </a>
            </div>
        </div>
    </div>

    
    <div style="position:relative;margin-top:20px;padding-top:16px;
                border-top:1px solid rgba(255,255,255,0.08);
                display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <span style="font-size:11px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;
                     color:rgba(255,255,255,0.35);margin-right:4px">Quick Add</span>
        <a href="<?php echo e(route('blotter.create')); ?>"
           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.75);
                  border:1px solid rgba(255,255,255,0.12);min-height:32px;
                  font-size:12px;padding:5px 14px"
           class="btn btn-sm">
            <i class="fas fa-gavel"></i> Blotter Case
        </a>
        <a href="<?php echo e(route('businesses.create')); ?>"
           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.75);
                  border:1px solid rgba(255,255,255,0.12);min-height:32px;
                  font-size:12px;padding:5px 14px"
           class="btn btn-sm">
            <i class="fas fa-store"></i> Business Permit
        </a>
        <a href="<?php echo e(route('households.create')); ?>"
           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.75);
                  border:1px solid rgba(255,255,255,0.12);min-height:32px;
                  font-size:12px;padding:5px 14px"
           class="btn btn-sm">
            <i class="fas fa-house-circle-plus"></i> Household
        </a>
        <?php if(auth()->user()->role === 'Admin'): ?>
        <a href="<?php echo e(route('officials.create')); ?>"
           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.75);
                  border:1px solid rgba(255,255,255,0.12);min-height:32px;
                  font-size:12px;padding:5px 14px"
           class="btn btn-sm">
            <i class="fas fa-user-tie"></i> Official
        </a>
        <?php endif; ?>
        <div style="flex:1"></div>
        <span style="font-size:12px;color:rgba(255,255,255,0.30);display:flex;align-items:center;gap:6px">
            <kbd style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.15);
                        border-radius:4px;padding:2px 7px;font-family:monospace;font-size:10px;
                        color:rgba(255,255,255,0.55)">Ctrl+K</kbd>
            Global Search
        </span>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    </div>

</div>



<?php
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    $alertCount = $seniorBdays->count() + $regularBdays->count()
                + ($expiringPermits->count() ? 1 : 0)
                + ($pendingAppointments ? 1 : 0)
                + ($lowStockMeds->count() ? 1 : 0);
?>
<?php if($alertCount): ?>
<div class="alert-tray no-print" id="tour-alerts" data-alerts="<?php echo e($alertCount); ?>">
    <div class="alert-tray-hdr" onclick="this.closest('.alert-tray').classList.toggle('open')">
        <i class="fas fa-bell tray-icon"></i>
        <span><?php echo e($alertCount); ?> Notice<?php echo e($alertCount > 1 ? 's' : ''); ?> — Birthdays, Permits & More</span>
        <i class="fas fa-chevron-down tray-caret"></i>
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    $diskTotal = @disk_total_space(storage_path()) ?: 1;
    $diskFree  = @disk_free_space(storage_path()) ?: $diskTotal;
    $diskUsed  = $diskTotal - $diskFree;
    $diskPct   = round(($diskUsed / $diskTotal) * 100);
    $diskUsedGB = round($diskUsed / 1073741824, 1);
    $diskTotalGB= round($diskTotal / 1073741824, 1);

    // Last backup file
    $backupDir = storage_path('app/backups');
    $lastBackupTs = null;
    if (is_dir($backupDir)) {
        $files = glob($backupDir . '/*.sql') ?: [];
        $files = array_merge($files, glob($backupDir . '/*.gz') ?: []);
        if ($files) {
            usort($files, fn($a,$b) => filemtime($b) - filemtime($a));
            $lastBackupTs = filemtime($files[0]);
        }
    }
    $lastBackupLabel = $lastBackupTs
        ? \Carbon\Carbon::createFromTimestamp($lastBackupTs)->diffForHumans()
        : 'No backups yet';
    $backupOk = $lastBackupTs && (time() - $lastBackupTs < 86400 * 7); // within 7 days
?>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin-bottom:20px" class="no-print">

    
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);
                padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow-sm)">
        <div style="width:40px;height:40px;border-radius:var(--radius);flex-shrink:0;
                    background:rgba(22,101,52,0.1);display:flex;align-items:center;justify-content:center">
            <i class="fas fa-database" style="color:#16a34a;font-size:16px"></i>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;
                        color:var(--text-subtle);margin-bottom:2px">Database</div>
            <div style="font-size:15px;font-weight:700;color:#14532d;display:flex;align-items:center;gap:6px">
                <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;
                             display:inline-block;box-shadow:0 0 0 3px rgba(34,197,94,0.2)"></span>
                Online
            </div>
        </div>
    </div>

    
    <div style="background:var(--surface);border:1px solid <?php echo e($backupOk ? 'var(--border)' : 'var(--gold-border)'); ?>;
                border-radius:var(--radius-lg);padding:16px 20px;display:flex;align-items:center;
                gap:14px;box-shadow:var(--shadow-sm)">
        <div style="width:40px;height:40px;border-radius:var(--radius);flex-shrink:0;
                    background:<?php echo e($backupOk ? 'rgba(13,33,68,0.07)' : 'var(--gold-pale)'); ?>;
                    display:flex;align-items:center;justify-content:center">
            <i class="fas fa-shield-halved"
               style="color:<?php echo e($backupOk ? 'var(--navy)' : 'var(--gold)'); ?>;font-size:16px"></i>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;
                        color:var(--text-subtle);margin-bottom:2px">Last Backup</div>
            <div style="font-size:14px;font-weight:700;color:<?php echo e($backupOk ? 'var(--navy)' : '#92600A'); ?>">
                <?php echo e($lastBackupLabel); ?>

            </div>
            <?php if(!$backupOk): ?>
            <div style="font-size:11px;color:var(--gold);font-weight:600;margin-top:1px">
                <a href="<?php echo e(route('backup.index')); ?>" style="color:var(--gold)">
                    <i class="fas fa-arrow-right" style="font-size:9px"></i> Back up now
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div style="background:var(--surface);border:1px solid <?php echo e($diskPct > 85 ? 'var(--crimson-border)' : 'var(--border)'); ?>;
                border-radius:var(--radius-lg);padding:16px 20px;box-shadow:var(--shadow-sm)">
        <div style="display:flex;align-items:center;gap:14px">
            <div style="width:40px;height:40px;border-radius:var(--radius);flex-shrink:0;
                        background:<?php echo e($diskPct > 85 ? 'var(--crimson-pale)' : 'rgba(13,33,68,0.07)'); ?>;
                        display:flex;align-items:center;justify-content:center">
                <i class="fas fa-hard-drive"
                   style="color:<?php echo e($diskPct > 85 ? 'var(--crimson)' : 'var(--navy)'); ?>;font-size:16px"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;
                            color:var(--text-subtle);margin-bottom:4px">Storage</div>
                <div style="display:flex;justify-content:space-between;margin-bottom:5px">
                    <span style="font-size:14px;font-weight:700;
                                 color:<?php echo e($diskPct > 85 ? 'var(--crimson)' : 'var(--navy)'); ?>">
                        <?php echo e($diskPct); ?>% used
                    </span>
                    <span style="font-size:12px;color:var(--text-muted)">
                        <?php echo e($diskUsedGB); ?> / <?php echo e($diskTotalGB); ?> GB
                    </span>
                </div>
                <div style="background:var(--surface3);border-radius:99px;height:6px;overflow:hidden">
                    <div style="height:100%;border-radius:99px;transition:width .6s;
                                background:<?php echo e($diskPct > 85 ? 'var(--crimson)' : ($diskPct > 65 ? 'var(--gold)' : 'var(--navy)')); ?>;
                                width:<?php echo e($diskPct); ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    
    <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);
                padding:16px 20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow-sm)">
        <div style="width:40px;height:40px;border-radius:var(--radius);flex-shrink:0;
                    background:rgba(13,33,68,0.07);display:flex;align-items:center;justify-content:center">
            <i class="fas fa-circle-check" style="color:var(--navy);font-size:16px"></i>
        </div>
        <div>
            <div style="font-size:11px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;
                        color:var(--text-subtle);margin-bottom:2px">System</div>
            <div style="font-size:15px;font-weight:700;color:var(--navy);display:flex;align-items:center;gap:6px">
                <span style="width:8px;height:8px;border-radius:50%;background:#22c55e;
                             display:inline-block;box-shadow:0 0 0 3px rgba(34,197,94,0.2)"></span>
                All Services Up
            </div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:1px">
                <?php echo e(config('app.name', 'BMS')); ?> v1.0
            </div>
        </div>
    </div>

</div>


<?php if($birthdays->count() || $expiringPermits->count() || $pendingAppointments || $lowStockMeds->count()): ?>
<div class="alert-strip" id="tour-alerts">

    <?php if($seniorBdays->count()): ?>
    <div class="alert-item alert-senior">
        <i class="fas fa-star"></i>
        <span>
            <strong>Senior Citizen <?php echo e($seniorBdays->count() === 1 ? 'Birthday' : 'Birthdays'); ?> Today (<?php echo e($seniorBdays->count()); ?>) —</strong>
            <?php echo e($seniorBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ')); ?><?php echo e($seniorBdays->count() > 4 ? ' +' . ($seniorBdays->count() - 4) . ' more' : ''); ?>

        </span>
        <a href="<?php echo e(route('residents.index')); ?>" class="alert-link">View All</a>
>>>>>>> Stashed changes
    </div>
    <div class="alert-tray-body">

        <?php if($seniorBdays->count()): ?>
        <div class="alert-item alert-senior">
            <i class="fas fa-star"></i>
            <span>
                <strong>Senior Citizen <?php echo e($seniorBdays->count() === 1 ? 'Birthday' : 'Birthdays'); ?> Today (<?php echo e($seniorBdays->count()); ?>) —</strong>
                <?php echo e($seniorBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ')); ?><?php echo e($seniorBdays->count() > 4 ? ' +' . ($seniorBdays->count() - 4) . ' more' : ''); ?>

            </span>
            <a href="<?php echo e(route('residents.index')); ?>" class="alert-link">View All</a>
        </div>
        <?php endif; ?>

        <?php if($regularBdays->count()): ?>
        <div class="alert-item alert-birthday">
            <i class="fas fa-birthday-cake"></i>
            <span>
                <strong><?php echo e($regularBdays->count() === 1 ? 'Birthday' : 'Birthdays'); ?> Today (<?php echo e($regularBdays->count()); ?>) —</strong>
                <?php echo e($regularBdays->map(fn($r) => $r->first_name . ' ' . $r->last_name . ', ' . $r->age . ' yrs')->take(4)->implode(' · ')); ?><?php echo e($regularBdays->count() > 4 ? ' +' . ($regularBdays->count() - 4) . ' more' : ''); ?>

            </span>
        </div>
        <?php endif; ?>

        <?php if($expiringPermits->count()): ?>
        <div class="alert-item alert-permit">
            <i class="fas fa-triangle-exclamation"></i>
            <span>
                <strong><?php echo e($expiringPermits->count()); ?> Business Permit<?php echo e($expiringPermits->count() > 1 ? 's' : ''); ?> Expiring Within 30 Days —</strong>
                <?php echo e($expiringPermits->map(fn($b) => $b->business_name . ' (exp. ' . \Carbon\Carbon::parse($b->expiry_date)->format('M d') . ')')->take(3)->implode(' · ')); ?><?php echo e($expiringPermits->count() > 3 ? ' +' . ($expiringPermits->count() - 3) . ' more' : ''); ?>

            </span>
            <a href="<?php echo e(route('businesses.index')); ?>" class="alert-link">View All</a>
        </div>
        <?php endif; ?>

        <?php if($pendingAppointments): ?>
        <div class="alert-item alert-birthday">
            <i class="fas fa-calendar-clock"></i>
            <span>
                <strong><?php echo e($pendingAppointments); ?> Pending Document Appointment<?php echo e($pendingAppointments > 1 ? 's' : ''); ?></strong> — waiting for staff confirmation.
            </span>
            <?php if(in_array(auth()->user()->role, ['Admin','Secretary'])): ?>
            <a href="<?php echo e(route('appointments.index')); ?>?status=Pending" class="alert-link">Review</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if($lowStockMeds->count()): ?>
        <div class="alert-item alert-permit">
            <i class="fas fa-pills"></i>
            <span>
                <strong><?php echo e($lowStockMeds->count()); ?> Medicine<?php echo e($lowStockMeds->count() > 1 ? 's' : ''); ?> Low on Stock —</strong>
                <?php echo e($lowStockMeds->map(fn($m) => $m->medicine_name . ' (' . $m->current_stock . ' ' . $m->unit . ')')->take(3)->implode(' · ')); ?><?php echo e($lowStockMeds->count() > 3 ? ' +' . ($lowStockMeds->count() - 3) . ' more' : ''); ?>

            </span>
            <a href="<?php echo e(route('committees.show', 'health')); ?>#medicine-inventory" class="alert-link">View</a>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>


<?php if(in_array(auth()->user()->role, ['Admin', 'Secretary'])): ?>
<div class="cmd-bar no-print">
    <span class="cmd-bar-label">Actions</span>

    <a href="<?php echo e(route('documents.create')); ?>" class="cmd-bar-btn cmd-bar-btn-gold">
        <i class="fas fa-file-circle-plus"></i> Issue Document
    </a>
    <a href="<?php echo e(route('residents.create')); ?>" class="cmd-bar-btn cmd-bar-btn-primary">
        <i class="fas fa-user-plus"></i> Add Resident
    </a>
    <a href="<?php echo e(route('blotter.create')); ?>" class="cmd-bar-btn cmd-bar-btn-ghost">
        <i class="fas fa-gavel"></i> Log Blotter
    </a>
    <a href="<?php echo e(route('businesses.create')); ?>" class="cmd-bar-btn cmd-bar-btn-ghost">
        <i class="fas fa-store"></i> New Permit
    </a>

    <div class="cmd-bar-divider"></div>

    <a href="<?php echo e(route('appointments.index')); ?>" class="cmd-bar-btn cmd-bar-btn-ghost">
        <i class="fas fa-calendar-check"></i> Appointments
        <?php if($pendingAppointments): ?>
        <span style="background:var(--gold);color:#fff;font-size:10px;font-weight:700;
                     padding:1px 6px;border-radius:99px;margin-left:2px"><?php echo e($pendingAppointments); ?></span>
        <?php endif; ?>
    </a>
    <a href="<?php echo e(route('portal.index')); ?>" target="_blank" class="cmd-bar-btn cmd-bar-btn-ghost">
        <i class="fas fa-globe"></i> Resident Portal
    </a>
</div>
<?php endif; ?>


<div class="dash-tabs">
    <button class="dash-tab-btn active" onclick="switchDashTab('overview')" id="dtab-overview">
        <i class="fas fa-tachometer-alt"></i> Overview
    </button>
    <button class="dash-tab-btn" onclick="switchDashTab('analytics')" id="dtab-analytics">
        <i class="fas fa-chart-pie"></i> Analytics
    </button>
</div>


<div id="dpanel-overview" class="dash-panel active">

    
    <div class="dash-stats" id="tour-statcards">
        <a href="<?php echo e(route('residents.index')); ?>" class="dash-stat-card">
            <div class="dash-stat-icon"><i class="fas fa-users"></i></div>
            <div>
                <div class="dash-stat-number"><?php echo e(number_format($totalResidents)); ?></div>
                <div class="dash-stat-label">Total Residents</div>
            </div>
        </a>
        <a href="<?php echo e(route('documents.index')); ?>" class="dash-stat-card">
            <div class="dash-stat-icon"><i class="fas fa-file-alt"></i></div>
            <div>
                <div class="dash-stat-number"><?php echo e(number_format($pendingDocuments)); ?></div>
                <div class="dash-stat-label">Pending Documents</div>
            </div>
        </a>
        <a href="<?php echo e(route('blotter.index')); ?>" class="dash-stat-card" style="position:relative">
            <?php if($overdueBlotter > 0): ?>
            <span style="position:absolute;top:10px;right:12px;
                         background:var(--crimson);color:#fff;
                         font-size:10px;font-weight:700;line-height:1;
                         padding:3px 7px;border-radius:99px;
                         pointer-events:none"
                  title="<?php echo e($overdueBlotter); ?> case<?php echo e($overdueBlotter > 1 ? 's' : ''); ?> overdue 30+ days">
                <?php echo e($overdueBlotter); ?> overdue
            </span>
            <?php endif; ?>
            <div class="dash-stat-icon"><i class="fas fa-gavel"></i></div>
            <div>
                <div class="dash-stat-number"><?php echo e(number_format($activeBlotter)); ?></div>
                <div class="dash-stat-label">Active Blotter Cases</div>
                <?php if($overdueBlotter > 0): ?>
                <div style="font-size:13px;color:var(--crimson);margin-top:3px;font-weight:600">
                    <i class="fas fa-fire"></i> <?php echo e($overdueBlotter); ?> overdue 30+ days
                </div>
                <?php endif; ?>
            </div>
        </a>
        <a href="<?php echo e(route('businesses.index')); ?>" class="dash-stat-card">
            <div class="dash-stat-icon"><i class="fas fa-store"></i></div>
            <div>
                <div class="dash-stat-number"><?php echo e(number_format($activeBusinesses)); ?></div>
                <div class="dash-stat-label">Active Businesses</div>
            </div>
        </a>
        <?php if(in_array(auth()->user()->role, ['Admin','Secretary'])): ?>
        <a href="<?php echo e(route('appointments.index')); ?>" class="dash-stat-card">
            <div class="dash-stat-icon"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="dash-stat-number"><?php echo e(number_format($pendingAppointments)); ?></div>
                <div class="dash-stat-label">Pending Appointments</div>
                <?php if($pendingAppointments > 0): ?>
                <div style="font-size:12px;color:var(--gold);margin-top:4px;font-weight:600;
                            display:flex;align-items:center;gap:4px">
                    <span style="width:6px;height:6px;border-radius:50%;background:var(--gold);
                                 display:inline-block"></span>
                    Needs review
                </div>
                <?php endif; ?>
            </div>
        </a>
        <?php endif; ?>
    </div>

    
    <div class="dash-mid">
        <div class="card" id="tour-chart">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-bar"></i> Documents Issued — <?php echo e(date('Y')); ?></span>
            </div>
            <div class="card-body">
                <canvas id="monthlyDocChart" height="105"></canvas>
                <?php
                    $thisMonth = $monthlyData[now()->month] ?? 0;
                    $yearTotal = array_sum($monthlyData);
                    $prevMonth = $monthlyData[now()->subMonth()->month] ?? 0;
                    $trend = $thisMonth > $prevMonth ? 'up' : ($thisMonth < $prevMonth ? 'down' : 'the same as');
                ?>
                <div class="chart-insight">
                    <strong><?php echo e(number_format($yearTotal)); ?></strong> documents issued so far in <?php echo e(date('Y')); ?>.
                    This month: <strong><?php echo e($thisMonth); ?></strong> —
                    <?php if($thisMonth > $prevMonth): ?>
                        <span style="color:#16a34a"><i class="fas fa-arrow-up"></i> up from <?php echo e($prevMonth); ?> last month.</span>
                    <?php elseif($thisMonth < $prevMonth): ?>
                        <span style="color:var(--crimson)"><i class="fas fa-arrow-down"></i> down from <?php echo e($prevMonth); ?> last month.</span>
                    <?php else: ?>
                        same as last month (<?php echo e($prevMonth); ?>).
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card" id="tour-quickaccess">
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
            <div class="card-header card-header-plain">
=======
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
            <div class="card-header">
>>>>>>> Stashed changes
                <span class="card-title"><i class="fas fa-bolt"></i> Quick Access</span>
            </div>
            <div class="card-body">
                <div class="quick-grid">
                    <?php $links = [
                        ['href' => route('residents.index'),    'icon' => 'fa-users',          'label' => 'Residents'   ],
                        ['href' => route('households.index'),   'icon' => 'fa-house',          'label' => 'Households'  ],
                        ['href' => route('documents.index'),    'icon' => 'fa-file-alt',       'label' => 'Documents'   ],
                        ['href' => route('blotter.index'),      'icon' => 'fa-gavel',          'label' => 'Blotter'     ],
                        ['href' => route('businesses.index'),   'icon' => 'fa-store',          'label' => 'Businesses'  ],
                        ['href' => route('appointments.index'), 'icon' => 'fa-calendar-check', 'label' => 'Appointments'],
                        ['href' => route('officials.index'),    'icon' => 'fa-user-tie',       'label' => 'Officials'   ],
                        ['href' => route('reports.index'),      'icon' => 'fa-chart-bar',      'label' => 'Analytics'   ],
                        ['href' => route('backup.index'),       'icon' => 'fa-database',       'label' => 'Backup'      ],
                    ]; ?>
                    <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($l['href']); ?>" class="quick-item">
                        <div class="quick-icon">
                            <i class="fas <?php echo e($l['icon']); ?>"></i>
                        </div>
                        <span class="quick-label"><?php echo e($l['label']); ?></span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="dash-bottom">
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-alt"></i> Recent Documents</span>
                <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0">
                <?php $__empty_1 = true; $__currentLoopData = $recentDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('documents.show', $d->id)); ?>" class="feed-row">
                    <div class="feed-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="feed-body">
                        <div class="feed-title"><?php echo e($d->doc_number); ?></div>
                        <div class="feed-sub"><?php echo e($d->resident->full_name ?? '—'); ?> · <?php echo e($d->document_type); ?></div>
                    </div>
                    <span class="badge <?php echo e($d->status === 'Released' ? 'badge-green' : ($d->status === 'Pending' ? 'badge-yellow' : 'badge-blue')); ?>"><?php echo e($d->status); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state" style="padding:32px">
                    <i class="fas fa-file-alt"></i>
                    <p>No documents yet. <a href="<?php echo e(route('documents.create')); ?>" style="color:var(--navy);font-weight:600">Issue one now</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-gavel"></i> Recent Blotter</span>
                <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0">
                <?php $__empty_1 = true; $__currentLoopData = $recentBlotter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $bIsOpen   = in_array($b->status, ['Active', 'Under Investigation']);
                    $bDaysOpen = $bIsOpen && $b->incident_date
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
                        ? (int)\Carbon\Carbon::parse($b->incident_date)->diffInDays(now()) : 0;
                ?>
                <a href="<?php echo e(route('blotter.show', $b->id)); ?>" class="feed-row">
                    <div class="feed-icon"
                         style="<?php echo e($bIsOpen && $bDaysOpen >= 30 ? 'outline:2px solid var(--crimson);outline-offset:-2px;' : ''); ?>">
                        <i class="fas fa-gavel"></i>
=======
                        ? \Carbon\Carbon::parse($b->incident_date)->diffInDays(now()) : 0;
                ?>
                <a href="<?php echo e(route('blotter.show', $b->id)); ?>" class="feed-row">
                    <div class="feed-icon" style="background:#fef2f2<?php echo e($bIsOpen && $bDaysOpen >= 30 ? ';outline:2px solid #dc2626;outline-offset:-2px' : ''); ?>">
                        <i class="fas fa-gavel" style="color:#dc2626"></i>
>>>>>>> Stashed changes
=======
                        ? \Carbon\Carbon::parse($b->incident_date)->diffInDays(now()) : 0;
                ?>
                <a href="<?php echo e(route('blotter.show', $b->id)); ?>" class="feed-row">
                    <div class="feed-icon" style="background:#fef2f2<?php echo e($bIsOpen && $bDaysOpen >= 30 ? ';outline:2px solid #dc2626;outline-offset:-2px' : ''); ?>">
                        <i class="fas fa-gavel" style="color:#dc2626"></i>
>>>>>>> Stashed changes
=======
                        ? \Carbon\Carbon::parse($b->incident_date)->diffInDays(now()) : 0;
                ?>
                <a href="<?php echo e(route('blotter.show', $b->id)); ?>" class="feed-row">
                    <div class="feed-icon" style="background:#fef2f2<?php echo e($bIsOpen && $bDaysOpen >= 30 ? ';outline:2px solid #dc2626;outline-offset:-2px' : ''); ?>">
                        <i class="fas fa-gavel" style="color:#dc2626"></i>
>>>>>>> Stashed changes
                    </div>
                    <div class="feed-body">
                        <div class="feed-title"><?php echo e($b->case_number); ?></div>
                        <div class="feed-sub"><?php echo e($b->incident_type); ?><?php echo e($b->complainant_name ? ' · ' . $b->complainant_name : ''); ?></div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:3px;flex-shrink:0">
                        <span class="badge <?php echo e($b->status === 'Settled' ? 'badge-green' : ($b->status === 'Active' ? 'badge-red' : 'badge-gray')); ?>"><?php echo e($b->status); ?></span>
                        <?php if($bIsOpen && $bDaysOpen >= 30): ?>
                        <span style="font-size:11px;background:#fee2e2;color:#991b1b;padding:2px 6px;border-radius:99px;white-space:nowrap;font-weight:700">
                            <i class="fas fa-fire"></i> <?php echo e($bDaysOpen); ?>d
                        </span>
                        <?php endif; ?>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state" style="padding:32px">
                    <i class="fas fa-gavel"></i>
                    <p>No blotter cases yet. <a href="<?php echo e(route('blotter.create')); ?>" style="color:var(--navy);font-weight:600">File a case</a></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>


<div id="dpanel-analytics" class="dash-panel">

    
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-users"></i> Resident Demographics</span>
            <a href="<?php echo e(route('residents.index')); ?>" class="btn btn-secondary btn-sm">View Residents</a>
        </div>
        <div class="card-body">
            <div class="demog-grid">
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalActive)); ?></div>
                    <div class="demog-lbl">Active</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num" style="color:var(--gold)"><?php echo e(number_format($totalSeniors)); ?></div>
                    <div class="demog-lbl">Senior Citizens</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalVoters)); ?></div>
                    <div class="demog-lbl">Registered Voters</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalPwd)); ?></div>
                    <div class="demog-lbl">PWD</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalSoloParent)); ?></div>
                    <div class="demog-lbl">Solo Parents</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($total4ps)); ?></div>
                    <div class="demog-lbl">4Ps Beneficiaries</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalMale)); ?></div>
                    <div class="demog-lbl">Male</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalFemale)); ?></div>
                    <div class="demog-lbl">Female</div>
                </div>
                <div class="demog-item">
                    <div class="demog-num"><?php echo e(number_format($totalHouseholds)); ?></div>
                    <div class="demog-lbl">Households</div>
                </div>
            </div>
        </div>
    </div>

    <div class="analytics-grid">
        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-chart-pie"></i> Age Distribution</span>
            </div>
            <div class="card-body" style="display:flex;align-items:center;gap:20px">
                <div style="flex-shrink:0;width:160px;height:160px">
                    <canvas id="ageChart"></canvas>
                </div>
                <div style="flex:1">
                    <?php
                        $ageColors = ['#4f46e5','#C8861A','#16a34a','#dc2626']; $ai=0;
                        $ageCollection   = collect($ageGroups);
                        $largestAgeGroup = $ageCollection->sortDesc()->keys()->first();
                        $largestAgeCount = $ageCollection->max();
                    ?>
                    <?php $__currentLoopData = $ageGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                        <div style="width:10px;height:10px;border-radius:2px;background:<?php echo e($ageColors[$ai]); ?>;flex-shrink:0"></div>
                        <div style="flex:1;font-size:13px;color:var(--text)"><?php echo e($label); ?></div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy)"><?php echo e(number_format($count)); ?></div>
                    </div>
                    <?php $ai++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="chart-insight" style="margin-top:10px">
                        Largest group: <strong><?php echo e($largestAgeGroup); ?></strong> with <strong><?php echo e(number_format($largestAgeCount)); ?></strong> residents.
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-file-alt"></i> Documents by Type</span>
            </div>
            <div class="card-body" style="display:flex;align-items:center;gap:20px">
                <div style="flex-shrink:0;width:160px;height:160px">
                    <canvas id="docTypeChart"></canvas>
                </div>
                <div style="flex:1">
                    <?php
                        $dtColors = ['#0D2144','#C8861A','#4f46e5','#16a34a','#dc2626','#0891b2']; $di=0;
                        $topDocType = $documentsByType->sortDesc()->keys()->first();
                        $topDocCount = $documentsByType->max();
                    ?>
                    <?php $__currentLoopData = $documentsByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:7px">
                        <div style="width:10px;height:10px;border-radius:2px;background:<?php echo e($dtColors[$di % count($dtColors)]); ?>;flex-shrink:0"></div>
                        <div style="flex:1;font-size:13px;color:var(--text)"><?php echo e($type); ?></div>
                        <div style="font-size:13px;font-weight:700;color:var(--navy)"><?php echo e(number_format($count)); ?></div>
                    </div>
                    <?php $di++; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($topDocType): ?>
                    <div class="chart-insight" style="margin-top:10px">
                        Most requested: <strong><?php echo e($topDocType); ?></strong> (<?php echo e(number_format($topDocCount)); ?> issued).
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-location-dot"></i> Residents by Purok</span>
        </div>
        <div class="card-body">
            <canvas id="purokChart" height="60"></canvas>
        </div>
    </div>

    
    <?php if($blotterByType->count()): ?>
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-gavel"></i> Blotter by Incident Type</span>
            <a href="<?php echo e(route('blotter.index')); ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body">
            <?php $maxBlotter = $blotterByType->max() ?: 1; ?>
            <?php $__currentLoopData = $blotterByType->sortDesc(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:9px">
                <div style="width:150px;font-size:13px;color:var(--text);flex-shrink:0;line-height:1.4"><?php echo e($type); ?></div>
                <div style="flex:1;background:#f3f4f6;border-radius:99px;height:9px;overflow:hidden">
                    <div style="height:100%;border-radius:99px;background:var(--crimson);width:<?php echo e(round(($count/$maxBlotter)*100)); ?>%"></div>
                </div>
                <div style="font-size:13px;font-weight:700;color:var(--crimson);width:30px;text-align:right"><?php echo e($count); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>


<<<<<<< Updated upstream
=======
<?php if(in_array(auth()->user()->role, ['Admin','Secretary'])): ?>
<div id="dpanel-appointments" class="dash-panel">

    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-calendar-check"></i> Document Appointments</span>
            <div style="display:flex;gap:.5rem">
                <a href="<?php echo e(route('portal.index')); ?>" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fas fa-globe"></i> View Portal
                </a>
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-list"></i> Manage All
                </a>
            </div>
        </div>

        
        <div style="display:flex;gap:1px;background:var(--border);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
            <?php $__currentLoopData = ['Pending','Confirmed','Processing','Ready','Released','Cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="flex:1;padding:12px 8px;text-align:center;background:var(--surface2)">
                <div style="font-size:18px;font-weight:700;color:var(--navy)"><?php echo e($aptCounts[$st] ?? 0); ?></div>
                <div style="font-size:13px;color:var(--text-muted);margin-top:3px;font-weight:500"><?php echo e($st); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="card-body" style="padding:0">
            <?php $__empty_1 = true; $__currentLoopData = $recentAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $sc = strtolower($apt->status); ?>
            <div class="feed-row" style="text-decoration:none">
                <div class="feed-icon" style="background:var(--gold-pale)"><i class="fas fa-calendar" style="color:var(--gold)"></i></div>
                <div class="feed-body">
                    <div style="display:flex;align-items:center;gap:8px">
                        <span class="feed-title"><?php echo e($apt->appointment_number); ?></span>
                        <span class="apt-badge apt-<?php echo e($sc); ?>"><?php echo e($apt->status); ?></span>
                    </div>
                    <div class="feed-sub"><?php echo e($apt->resident_name); ?> · <?php echo e($apt->document_type); ?> · <?php echo e($apt->preferred_date->format('M d, Y')); ?></div>
                </div>
                <a href="<?php echo e(route('appointments.index')); ?>?search=<?php echo e($apt->appointment_number); ?>"
                   class="btn btn-secondary btn-sm" style="flex-shrink:0;font-size:13px">Update</a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state" style="padding:40px"><i class="fas fa-calendar-check"></i><p>No appointments yet</p></div>
            <?php endif; ?>
        </div>

        <?php if($recentAppointments->count() >= 8): ?>
        <div style="padding:.75rem 1rem;border-top:1px solid var(--border);text-align:center">
            <a href="<?php echo e(route('appointments.index')); ?>" style="font-size:13px;color:var(--navy);font-weight:600">
                View all appointments <i class="fas fa-arrow-right" style="font-size:10px"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>

</div>
<?php endif; ?>

>>>>>>> Stashed changes
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
// Live Clock
(function() {
    const timeEl = document.getElementById('cc-clock-time');
    const dateEl = document.getElementById('cc-clock-date');
    if (!timeEl) return;
    const days  = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    function pad(n) { return String(n).padStart(2,'0'); }
    function tick() {
        const now = new Date();
        const h = now.getHours(), m = now.getMinutes(), s = now.getSeconds();
        const ampm = h >= 12 ? 'PM' : 'AM';
        const h12  = h % 12 || 12;
        timeEl.textContent = `${pad(h12)}:${pad(m)}:${pad(s)} ${ampm}`;
        if (dateEl) dateEl.textContent = `${days[now.getDay()]}, ${months[now.getMonth()]} ${now.getDate()}, ${now.getFullYear()}`;
    }
    tick();
    setInterval(tick, 1000);
})();

// Tab switching
function switchDashTab(id) {
    document.querySelectorAll('.dash-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.dash-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('dpanel-' + id).classList.add('active');
    document.getElementById('dtab-' + id).classList.add('active');
}

// ── Shepherd.js Onboarding Tour ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Shepherd === 'undefined') return;

    const tour = new Shepherd.Tour({
        useModalOverlay: true,
        defaultStepOptions: {
            cancelIcon: { enabled: true },
            scrollTo: { behavior: 'smooth', block: 'center' },
            popperOptions: { modifiers: [{ name: 'offset', options: { offset: [0, 14] } }] }
        }
    });

    const btn = (label, type, action) => ({ text: label, classes: 'shepherd-button-' + type, action });

    tour.addStep({
        id: 'welcome',
        title: 'Welcome to the Dashboard',
        text: 'This is your Command Center — a real-time summary of everything happening in Barangay New Era. This short tour will show you the most important parts.',
        buttons: [btn('Skip Tour', 'secondary', tour.cancel), btn('Start Tour →', 'primary', tour.next)]
    });

    <?php if($birthdays->count() || $expiringPermits->count() || $pendingAppointments || $lowStockMeds->count()): ?>
    tour.addStep({
        id: 'alerts',
        title: 'Alert Strip — Urgent Items',
        text: 'These colored banners appear when something needs your attention right now: birthdays today, expiring business permits, pending appointments, or low medicine stock. Click the links to act immediately.',
        attachTo: { element: '#tour-alerts', on: 'bottom' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });
    <?php endif; ?>

    tour.addStep({
        id: 'statcards',
        title: 'Key Numbers at a Glance',
        text: 'These cards show your most important counts — Total Residents, Pending Documents, Active Blotter Cases, and more. Click any card to go directly to that module.',
        attachTo: { element: '#tour-statcards', on: 'bottom' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });

    tour.addStep({
        id: 'chart',
        title: 'Monthly Documents Chart',
        text: 'This bar chart shows how many barangay documents were issued each month. The insight below the chart summarizes the trend in plain language — no need to analyze the numbers yourself.',
        attachTo: { element: '#tour-chart', on: 'right' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });

    tour.addStep({
        id: 'quickaccess',
        title: 'Quick Access — Go Anywhere Fast',
        text: 'Use these shortcut buttons to jump to any module instantly: Residents, Documents, Blotter, Businesses, Committees, and more. No need to use the sidebar.',
        attachTo: { element: '#tour-quickaccess', on: 'left' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Next →', 'primary', tour.next)]
    });

    tour.addStep({
        id: 'tabs',
        title: 'Dashboard Tabs',
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        text: 'Switch between <strong>Overview</strong> (today\'s summary) and <strong>Analytics</strong> (charts and demographics). For appointments, use the dedicated Appointments page from the sidebar.',
=======
        text: 'Switch between <strong>Overview</strong> (today\'s summary), <strong>Analytics</strong> (charts and demographics), and <strong>Appointments</strong> (document requests from residents). The orange number on Appointments shows pending items.',
>>>>>>> Stashed changes
=======
        text: 'Switch between <strong>Overview</strong> (today\'s summary), <strong>Analytics</strong> (charts and demographics), and <strong>Appointments</strong> (document requests from residents). The orange number on Appointments shows pending items.',
>>>>>>> Stashed changes
=======
        text: 'Switch between <strong>Overview</strong> (today\'s summary), <strong>Analytics</strong> (charts and demographics), and <strong>Appointments</strong> (document requests from residents). The orange number on Appointments shows pending items.',
>>>>>>> Stashed changes
        attachTo: { element: '.dash-tabs', on: 'bottom' },
        buttons: [btn('← Back', 'secondary', tour.back), btn('Finish Tour ✓', 'primary', function () {
            tour.complete();
            sessionStorage.setItem('bms_tour_done', '1');
        })]
    });

    // Start tour button
    const startBtn = document.getElementById('startTourBtn');
    if (startBtn) startBtn.addEventListener('click', function () { tour.start(); });

    // Auto-start on first visit
    if (!sessionStorage.getItem('bms_tour_done')) {
        setTimeout(function () { tour.start(); }, 800);
    }
});

// Monthly documents bar chart (Overview tab)
new Chart(document.getElementById('monthlyDocChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Documents',
            data: [<?php echo e($monthlyData[1]); ?>,<?php echo e($monthlyData[2]); ?>,<?php echo e($monthlyData[3]); ?>,<?php echo e($monthlyData[4]); ?>,<?php echo e($monthlyData[5]); ?>,<?php echo e($monthlyData[6]); ?>,<?php echo e($monthlyData[7]); ?>,<?php echo e($monthlyData[8]); ?>,<?php echo e($monthlyData[9]); ?>,<?php echo e($monthlyData[10]); ?>,<?php echo e($monthlyData[11]); ?>,<?php echo e($monthlyData[12]); ?>],
            backgroundColor: 'rgba(13,33,68,0.10)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 5,
            hoverBackgroundColor: 'rgba(200,134,26,0.18)',
            hoverBorderColor: '#C8861A',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 11, family: 'Poppins' } }, grid: { color: '#F3F4F6' } },
            y: { ticks: { color: '#9CA3AF', font: { size: 11 }, stepSize: 1 }, grid: { color: '#F3F4F6' }, beginAtZero: true }
        }
    }
});

// Age distribution donut (Analytics tab)
new Chart(document.getElementById('ageChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_keys($ageGroups)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($ageGroups)); ?>,
            backgroundColor: ['#4f46e5','#C8861A','#16a34a','#dc2626'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});

// Documents by type donut (Analytics tab)
<?php $dtLabels = $documentsByType->keys()->toArray(); $dtValues = $documentsByType->values()->toArray(); ?>
new Chart(document.getElementById('docTypeChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($dtLabels); ?>,
        datasets: [{
            data: <?php echo json_encode($dtValues); ?>,
            backgroundColor: ['#0D2144','#C8861A','#4f46e5','#16a34a','#dc2626','#0891b2'],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: { legend: { display: false } }
    }
});

// Residents by purok bar chart (Analytics tab)
new Chart(document.getElementById('purokChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($residentsByPurok->pluck('name')->toArray()); ?>,
        datasets: [{
            label: 'Active Residents',
            data: <?php echo json_encode($residentsByPurok->pluck('residents_count')->toArray()); ?>,
            backgroundColor: 'rgba(13,33,68,0.12)',
            borderColor: '#0D2144',
            borderWidth: 1.5,
            borderRadius: 4,
            hoverBackgroundColor: 'rgba(200,134,26,0.20)',
            hoverBorderColor: '#C8861A',
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: { legend: { display: false } },
        scales: {
            x: { ticks: { color: '#9CA3AF', font: { size: 10 } }, grid: { color: '#F3F4F6' }, beginAtZero: true },
            y: { ticks: { color: '#374151', font: { size: 11, family: 'Poppins' } }, grid: { display: false } }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\anakco_bms\resources\views/dashboard.blade.php ENDPATH**/ ?>