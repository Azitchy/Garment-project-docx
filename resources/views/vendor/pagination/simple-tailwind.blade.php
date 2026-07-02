@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:flex-end;gap:8px;margin-top:18px;">
        @if ($paginator->onFirstPage())
            <span class="pill" style="opacity:.55;cursor:not-allowed;">{!! __('pagination.previous') !!}</span>
        @else
            <a class="pill" href="{{ $paginator->previousPageUrl() }}" rel="prev">{!! __('pagination.previous') !!}</a>
        @endif

        @if ($paginator->hasMorePages())
            <a class="pill" href="{{ $paginator->nextPageUrl() }}" rel="next">{!! __('pagination.next') !!}</a>
        @else
            <span class="pill" style="opacity:.55;cursor:not-allowed;">{!! __('pagination.next') !!}</span>
        @endif
    </nav>
@endif
