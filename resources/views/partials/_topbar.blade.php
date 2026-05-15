@php
    $tpDbOk = true;
    try { \DB::connection()->getPdo(); } catch (\Exception $e) { $tpDbOk = false; }
    $tpDir   = storage_path('app/backups');
    $tpFiles = array_merge(glob($tpDir.'/*.sql') ?: [], glob($tpDir.'/*.gz') ?: []);
    $tpTs    = $tpFiles ? max(array_map('filemtime', $tpFiles)) : null;
    $tpBkOk  = $tpTs && (time() - $tpTs < 86400 * 7);
    $tpBkAge = $tpTs ? \Carbon\Carbon::createFromTimestamp($tpTs)->diffForHumans() : 'No backup';
    $tpDiskTotal = @disk_total_space(storage_path()) ?: 1;
    $tpDiskFree  = @disk_free_space(storage_path()) ?: $tpDiskTotal;
    $tpDiskPct   = round((($tpDiskTotal - $tpDiskFree) / $tpDiskTotal) * 100);
    $tpAllOk = $tpDbOk && $tpBkOk && $tpDiskPct < 85;
@endphp
<header class="topbar no-print">
    <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <div class="topbar-title">Barangay New Era</div>
            <div class="topbar-subtitle">Management System</div>
        </div>
    </div>

    {{-- Global Search --}}
    <div style="flex:1;max-width:480px;margin:0 24px;position:relative" id="search-container">
        <div style="position:relative">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-subtle);font-size:13px;pointer-events:none"></i>
            <input type="text" id="global-search-input" placeholder="Search residents, documents, blotter, businesses..."
                   autocomplete="off"
                   style="width:100%;padding:8px 12px 8px 36px;background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:13px;font-family:'Poppins',sans-serif;color:var(--text);outline:none;transition:all 0.15s"
                   onfocus="this.style.borderColor='var(--navy)';this.style.background='#fff';this.style.boxShadow='0 0 0 3px var(--navy-pale)'"
                   onblur="setTimeout(()=>{this.style.borderColor='var(--border)';this.style.background='var(--surface2)';this.style.boxShadow='none';hideSearch()},200)">
            <button onclick="openCmdPalette()" title="Command palette (Ctrl+K)"
                    style="position:absolute;right:8px;top:50%;transform:translateY(-50%);
                           background:var(--surface3);border:1px solid var(--border);
                           border-radius:6px;padding:2px 7px;font-size:10px;
                           color:var(--text-subtle);font-family:monospace;cursor:pointer;
                           display:flex;align-items:center;gap:3px;line-height:1.5;
                           white-space:nowrap;transition:all .15s"
                    onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)'"
                    onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-subtle)'">
                <span style="font-size:9px;opacity:.7">⌃</span>K
            </button>
        </div>

        {{-- Search Results Dropdown --}}
        <div id="search-results" style="display:none;position:absolute;top:calc(100% + 6px);left:0;right:0;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);z-index:500;max-height:420px;overflow-y:auto">
            <div id="search-results-inner"></div>
        </div>
    </div>

    <div class="topbar-right">
        <div class="topbar-date">
            <i class="fas fa-calendar-day"></i>
            <span>{{ now()->format('M d, Y') }}</span>
        </div>
        <div class="topbar-divider"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="topbar-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</header>

<style>
.search-result-item {
    display:flex;align-items:center;gap:12px;
    padding:10px 14px;cursor:pointer;
    transition:background 0.1s;text-decoration:none;color:inherit;
    border-bottom:1px solid var(--border);
}
.search-result-item:last-child { border-bottom:none; }
.search-result-item:hover { background:var(--navy-pale); }
.search-section-header {
    padding:6px 14px;font-size:9.5px;font-weight:700;
    text-transform:uppercase;letter-spacing:0.1em;
    color:var(--text-subtle);background:var(--surface2);
    border-bottom:1px solid var(--border);
}
.search-empty { padding:24px;text-align:center;font-size:13px;color:var(--text-muted); }
.search-loading { padding:16px;text-align:center;font-size:13px;color:var(--text-muted); }
</style>

<script>
const searchInput   = document.getElementById('global-search-input');
const searchResults = document.getElementById('search-results');
const searchInner   = document.getElementById('search-results-inner');
let searchTimeout   = null;

document.addEventListener('keydown', (e) => {
    if (e.key === '/' && !['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)) {
        e.preventDefault(); searchInput.focus();
    }
    // Ctrl+K is handled globally in app.blade.php — no duplicate here
    if (e.key === 'Escape') { searchInput.blur(); hideSearch(); }
});

searchInput.addEventListener('input', () => {
    clearTimeout(searchTimeout);
    const q = searchInput.value.trim();
    if (q.length < 2) { hideSearch(); return; }
    showLoading();
    searchTimeout = setTimeout(() => doSearch(q), 300);
});

function showLoading() {
    searchResults.style.display = 'block';
    searchInner.innerHTML = '<div class="search-loading"><i class="fas fa-spinner fa-spin" style="margin-right:6px"></i>Searching...</div>';
}

function hideSearch() { searchResults.style.display = 'none'; }

function doSearch(q) {
    fetch(`/search?q=${encodeURIComponent(q)}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => renderResults(data))
    .catch(() => { searchInner.innerHTML = '<div class="search-empty">Search unavailable.</div>'; });
}

function renderResults(data) {
    searchResults.style.display = 'block';
    if (!data.results || data.results.length === 0) {
        searchInner.innerHTML = `<div class="search-empty"><i class="fas fa-search" style="font-size:24px;opacity:0.15;display:block;margin-bottom:8px"></i>No results for "<strong>${data.query}</strong>"</div>`;
        return;
    }
    const groups = {};
    data.results.forEach(r => { if (!groups[r.type]) groups[r.type] = []; groups[r.type].push(r); });
    let html = '';
    for (const [type, items] of Object.entries(groups)) {
        html += `<div class="search-section-header">${type}s</div>`;
        items.forEach(item => {
            html += `<a href="${item.url}" class="search-result-item">
                <div style="width:32px;height:32px;border-radius:var(--radius-sm);background:${item.color}15;color:${item.color};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:13px"><i class="fas ${item.icon}"></i></div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${item.title}</div>
                    <div style="font-size:11px;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${item.subtitle}</div>
                </div>
                <span class="badge ${item.badge_class}" style="flex-shrink:0">${item.badge}</span>
            </a>`;
        });
    }
    html += `<div style="padding:8px 14px;font-size:12px;color:var(--text-subtle);background:var(--surface2);border-top:1px solid var(--border);text-align:center">${data.total} result${data.total !== 1 ? 's' : ''} for "<strong style="color:var(--text)">${data.query}</strong>" &nbsp;·&nbsp; Press <kbd style="background:var(--surface3);border:1px solid var(--border);border-radius:3px;padding:0 4px">Esc</kbd> to close</div>`;
    searchInner.innerHTML = html;
}
</script>