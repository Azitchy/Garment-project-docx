@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-top:18px;">
        <div class="subtle">
            {!! __('Showing') !!}
            @if ($paginator->firstItem())
                <strong>{{ $paginator->firstItem() }}</strong>
                {!! __('to') !!}
                <strong>{{ $paginator->lastItem() }}</strong>
            @else
                <strong>{{ $paginator->count() }}</strong>
            @endif
            {!! __('of') !!}
            <strong>{{ $paginator->total() }}</strong>
            {!! __('results') !!}
        </div>

        <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
            @if ($paginator->onFirstPage())
                <span class="pill" style="opacity:.55;cursor:not-allowed;">{!! __('pagination.previous') !!}</span>
            @else
                <a class="pill" href="{{ $paginator->previousPageUrl() }}" rel="prev">{!! __('pagination.previous') !!}</a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="pill" style="background:#eef2f7;color:#64748b;">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pill" style="background:#2563eb;color:#fff;">{{ $page }}</span>
                        @else
                            <a class="pill" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a class="pill" href="{{ $paginator->nextPageUrl() }}" rel="next">{!! __('pagination.next') !!}</a>
            @else
                <span class="pill" style="opacity:.55;cursor:not-allowed;">{!! __('pagination.next') !!}</span>
            @endif
        </div>
    </nav>
@endif
