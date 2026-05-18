@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;gap:4px;flex-wrap:wrap">

    {{-- Previous --}}
    @if ($paginator->onFirstPage())
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface2);color:var(--text-subtle);font-size:12px;cursor:default">
            <i class="fas fa-chevron-left"></i>
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;color:var(--text);font-size:12px;text-decoration:none;transition:all 0.15s"
           onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
            <i class="fas fa-chevron-left"></i>
        </a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;font-size:13px;color:var(--text-subtle)">…</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1.5px solid var(--navy);background:var(--navy);color:#fff;font-size:13px;font-weight:600">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;color:var(--text-muted);font-size:13px;text-decoration:none;transition:all 0.15s"
                       onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)';this.style.background='var(--navy-pale)'"
                       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-muted)';this.style.background='#fff'">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:#fff;color:var(--text);font-size:12px;text-decoration:none;transition:all 0.15s"
           onmouseover="this.style.borderColor='var(--navy)';this.style.color='var(--navy)'"
           onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)'">
            <i class="fas fa-chevron-right"></i>
        </a>
    @else
        <span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:var(--radius-sm);border:1px solid var(--border);background:var(--surface2);color:var(--text-subtle);font-size:12px;cursor:default">
            <i class="fas fa-chevron-right"></i>
        </span>
    @endif

</nav>
@endif
