{{--
    Shop catalog body: product grid or empty state + pagination.
    Used by products.index (SSR) and Api\ShopCatalogController (AJAX).
--}}
@php
    $isAr = app()->getLocale() === 'ar';
@endphp

@if($products->isEmpty())
    <div class="flex flex-col items-center justify-center py-32 text-center gap-6">
        <div class="w-20 h-20 rounded-full bg-ferro-carbon/50 flex items-center justify-center">
            <svg class="w-10 h-10 text-ferro-ash" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
        </div>
        <div>
            <h2 class="font-display text-2xl text-ferro-white mb-2">
                {{ $isAr ? 'لا توجد منتجات' : 'No Products Found' }}
            </h2>
            <p class="text-ferro-ash text-sm max-w-xs mx-auto">
                {{ $isAr ? 'جرّب تصفية مختلفة أو تصفّح كامل المجموعة.' : 'Try a different filter or browse the full collection.' }}
            </p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-secondary shop-filter-nav" data-shop-all="1">
            {{ $isAr ? 'عرض الكل' : 'View All' }}
        </a>
    </div>

@else
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 reveal-stagger shop-product-grid">
        @foreach($products as $product)
            @include('partials.product-card', ['product' => $product, 'showQuickAdd' => true])
        @endforeach
    </div>

    @if($products->hasPages())
        <div class="mt-16 flex items-center justify-center gap-3 flex-wrap shop-pagination-wrap">
            @if($products->onFirstPage())
                <span class="pagination-btn opacity-30 cursor-not-allowed" aria-disabled="true">
                    @if($isAr)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    @endif
                </span>
            @else
                <a href="{{ $products->previousPageUrl() }}" class="pagination-btn shop-ajax-nav" rel="prev" aria-label="{{ $isAr ? 'السابق' : 'Previous' }}">
                    @if($isAr)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    @endif
                </a>
            @endif

            @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                @if($page === $products->currentPage())
                    <span class="pagination-btn active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="pagination-btn shop-ajax-nav">{{ $page }}</a>
                @endif
            @endforeach

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" class="pagination-btn shop-ajax-nav" rel="next" aria-label="{{ $isAr ? 'التالي' : 'Next' }}">
                    @if($isAr)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </a>
            @else
                <span class="pagination-btn opacity-30 cursor-not-allowed" aria-disabled="true">
                    @if($isAr)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    @endif
                </span>
            @endif
        </div>
        <p class="text-center text-ferro-ash text-xs mt-4 shop-page-indicator">
            {{ $isAr
                ? "صفحة {$products->currentPage()} من {$products->lastPage()}"
                : "Page {$products->currentPage()} of {$products->lastPage()}" }}
        </p>
    @endif
@endif
