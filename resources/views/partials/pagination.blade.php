@php
    $isRtl  = app()->getLocale() === 'ar';
    $align  = $isRtl ? 'right' : 'left';
    $start  = $isRtl ? 'right' : 'left';
    $end    = $isRtl ? 'left'  : 'right';
@endphp

@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="ferro-pagination">
    <div class="ferro-pagination__inner">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <span class="ferro-pagination__btn ferro-pagination__btn--disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                @if($isRtl)
                <polyline points="9 18 15 12 9 6"></polyline>
                @else
                <polyline points="15 18 9 12 15 6"></polyline>
                @endif
            </svg>
        </span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
           class="ferro-pagination__btn ferro-pagination__btn--nav"
           aria-label="{{ __('pagination.previous') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                @if($isRtl)
                <polyline points="9 18 15 12 9 6"></polyline>
                @else
                <polyline points="15 18 9 12 15 6"></polyline>
                @endif
            </svg>
        </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
            <span class="ferro-pagination__ellipsis" aria-hidden="true">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <span class="ferro-pagination__btn ferro-pagination__btn--active"
                          aria-current="page" aria-label="{{ __('Page :page', ['page' => $page]) }}">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $url }}"
                       class="ferro-pagination__btn ferro-pagination__btn--page">
                        {{ $page }}
                        <span class="sr-only">{{ __(' (page :page)', ['page' => $page]) }}</span>
                    </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
           class="ferro-pagination__btn ferro-pagination__btn--nav"
           aria-label="{{ __('pagination.next') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                @if($isRtl)
                <polyline points="15 18 9 12 15 6"></polyline>
                @else
                <polyline points="9 18 15 12 9 6"></polyline>
                @endif
            </svg>
        </a>
        @else
        <span class="ferro-pagination__btn ferro-pagination__btn--disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                @if($isRtl)
                <polyline points="15 18 9 12 15 6"></polyline>
                @else
                <polyline points="9 18 15 12 9 6"></polyline>
                @endif
            </svg>
        </span>
        @endif

    </div>

    {{-- Results Summary --}}
    <p class="ferro-pagination__summary">
        @if($isRtl)
            عرض {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} من {{ $paginator->total() }} نتيجة
        @else
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        @endif
    </p>
</nav>

@once
@push('head')
<style>
.ferro-pagination {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
    padding: 40px 0;
}
.ferro-pagination__inner {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
    justify-content: center;
}
.ferro-pagination__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.05em;
    border-radius: 1px;
    transition: background-color 0.15s, color 0.15s, border-color 0.15s;
    text-decoration: none;
    font-family: inherit;
    border: 1px solid transparent;
}
.ferro-pagination__btn--page {
    color: #C5C1BB;
    border-color: #2A2A2A;
    background-color: transparent;
}
.ferro-pagination__btn--page:hover {
    background-color: #1A1A1A;
    border-color: #E8500A;
    color: #FFFFFF;
}
.ferro-pagination__btn--active {
    background-color: #E8500A;
    color: #FFFFFF;
    border-color: #E8500A;
    cursor: default;
}
.ferro-pagination__btn--nav {
    color: #C5C1BB;
    border-color: #2A2A2A;
    background-color: transparent;
}
.ferro-pagination__btn--nav:hover {
    background-color: #1A1A1A;
    border-color: #3A3A3A;
    color: #FFFFFF;
}
.ferro-pagination__btn--disabled {
    color: #3A3A3A;
    border-color: #1A1A1A;
    cursor: not-allowed;
    background-color: transparent;
}
.ferro-pagination__ellipsis {
    color: #6B6B6B;
    padding: 0 6px;
    font-size: 14px;
    line-height: 36px;
}
.ferro-pagination__summary {
    font-size: 12px;
    color: #6B6B6B;
    letter-spacing: 0.05em;
    margin: 0;
}
</style>
@endpush
@endonce
@endif
