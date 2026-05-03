{{--
    FERRO — Shop / Product Listing Page
    Variables: $products (LengthAwarePaginator), $categories (Collection)
--}}
@extends('layouts.app')

@php
    $isAr   = app()->getLocale() === 'ar';
    $active = request('category', '');
    $status = request('status', '');
@endphp

@section('seo_title',       $isAr ? 'المتجر — FERRO' : 'Shop — FERRO')
@section('seo_description', $isAr ? 'تسوّق منتجات العناية الفاخرة المصممة للرجل عالي الأداء.' : 'Shop premium luxury grooming essentials engineered for the high-performance man.')

@section('content')

{{-- ── Hero Banner ──────────────────────────────────────────────────────── --}}
<section class="relative pt-[72px] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-ferro-obsidian to-ferro-black"></div>
    {{-- subtle grid lines --}}
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: linear-gradient(var(--color-ferro-silver,#B0B0B0) 1px, transparent 1px),
                                   linear-gradient(90deg, var(--color-ferro-silver,#B0B0B0) 1px, transparent 1px);
                background-size: 60px 60px;"></div>

    <div class="container-ferro relative py-20 text-center">
        <p class="eyebrow mb-4">{{ $isAr ? 'مجموعتنا' : 'The Collection' }}</p>
        <h1 class="font-display text-5xl md:text-7xl font-semibold tracking-wide text-ferro-white leading-none mb-6">
            {{ $isAr ? 'العناية الفاخرة' : 'Luxury' }}
            <span class="text-gradient-orange block">{{ $isAr ? 'المُصنَّعة بدقّة' : 'Engineered' }}</span>
        </h1>
        <p class="text-ferro-silver text-lg max-w-xl mx-auto">
            {{ $isAr
                ? 'منتجات طبيعية متطورة مصممة خصيصاً للرجل الذي يرفض المساومة على الجودة.'
                : 'Nature-powered formulas refined for the man who demands performance and precision.' }}
        </p>
        {{-- result count --}}
        <p class="mt-4 text-ferro-ash text-sm">
            {{ $products->total() }} {{ $isAr ? 'منتج' : ($products->total() === 1 ? 'product' : 'products') }}
        </p>
    </div>

    {{-- bottom fade --}}
    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-ferro-black to-transparent pointer-events-none"></div>
</section>

{{-- ── Filter Bar ───────────────────────────────────────────────────────── --}}
<section class="sticky top-[72px] z-40 bg-ferro-black/95 backdrop-blur-xl border-b border-ferro-carbon/60">
    <div class="container-ferro">
        <div class="flex items-center gap-2 py-4 overflow-x-auto scrollbar-hide">

            {{-- All --}}
            <a href="{{ route('products.index') }}"
               class="filter-pill {{ $active === '' && $status === '' ? 'active' : '' }}">
                {{ $isAr ? 'الكل' : 'All' }}
            </a>

            {{-- Category pills --}}
            @foreach($categories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                   class="filter-pill {{ $active === $cat->slug ? 'active' : '' }}">
                    {{ $cat->getTranslation('name', app()->getLocale(), false) ?? $cat->name }}
                </a>
            @endforeach

            {{-- Status separator + pills --}}
            <span class="w-px h-5 bg-ferro-carbon/80 mx-1 flex-shrink-0"></span>

            <a href="{{ route('products.index', array_merge(request()->except('status', 'page'), ['status' => 'active'])) }}"
               class="filter-pill {{ $status === 'active' ? 'active' : '' }}">
                {{ $isAr ? 'متاح' : 'In Stock' }}
            </a>

            <a href="{{ route('products.index', array_merge(request()->except('status', 'page'), ['status' => 'coming_soon'])) }}"
               class="filter-pill {{ $status === 'coming_soon' ? 'active' : '' }}">
                {{ $isAr ? 'قريباً' : 'Coming Soon' }}
            </a>
        </div>
    </div>
</section>

{{-- ── Products Grid ────────────────────────────────────────────────────── --}}
<section class="section-pad">
    <div class="container-ferro">

        @if($products->isEmpty())
            {{-- Empty state --}}
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
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    {{ $isAr ? 'عرض الكل' : 'View All' }}
                </a>
            </div>

        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 reveal-stagger">
                @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>

            {{-- ── Pagination ──────────────────────────────────────────── --}}
            @if($products->hasPages())
                <div class="mt-16 flex items-center justify-center gap-3">
                    {{-- Prev --}}
                    @if($products->onFirstPage())
                        <span class="pagination-btn opacity-30 cursor-not-allowed" aria-disabled="true">
                            @if($isAr)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            @endif
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="pagination-btn" aria-label="{{ $isAr ? 'السابق' : 'Previous' }}">
                            @if($isAr)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                            @endif
                        </a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                        @if($page === $products->currentPage())
                            <span class="pagination-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-btn">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="pagination-btn" aria-label="{{ $isAr ? 'التالي' : 'Next' }}">
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
                <p class="text-center text-ferro-ash text-xs mt-4">
                    {{ $isAr
                        ? "صفحة {$products->currentPage()} من {$products->lastPage()}"
                        : "Page {$products->currentPage()} of {$products->lastPage()}" }}
                </p>
            @endif
        @endif

    </div>
</section>

{{-- ── Brand Promise Strip ──────────────────────────────────────────────── --}}
<section class="border-t border-ferro-carbon/40 bg-ferro-obsidian/40">
    <div class="container-ferro py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @php
            $promises = $isAr ? [
                ['icon' => 'leaf',    'title' => 'طبيعي 100%',         'desc'  => 'مكوّنات نقية من الطبيعة'],
                ['icon' => 'shield',  'title' => 'خالٍ من المواد الضارة','desc' => 'بدون بارابين أو كبريتات'],
                ['icon' => 'truck',   'title' => 'شحن سريع',            'desc'  => 'توصيل مجاني فوق 150$'],
                ['icon' => 'refresh', 'title' => 'إرجاع مضمون',         'desc'  => '30 يوم ضمان استرداد'],
            ] : [
                ['icon' => 'leaf',    'title' => '100% Natural',        'desc'  => 'Pure ingredients, no compromise'],
                ['icon' => 'shield',  'title' => 'Clean Formula',       'desc'  => 'Free from parabens & sulfates'],
                ['icon' => 'truck',   'title' => 'Fast Shipping',       'desc'  => 'Free delivery over $150'],
                ['icon' => 'refresh', 'title' => '30-Day Returns',      'desc'  => 'Shop with confidence'],
            ];
            @endphp

            @foreach($promises as $p)
                <div class="flex flex-col items-center text-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-ferro-orange/10 border border-ferro-orange/20 flex items-center justify-center">
                        @if($p['icon'] === 'leaf')
                            <svg class="w-5 h-5 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"/></svg>
                        @elseif($p['icon'] === 'shield')
                            <svg class="w-5 h-5 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        @elseif($p['icon'] === 'truck')
                            <svg class="w-5 h-5 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                        @else
                            <svg class="w-5 h-5 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-ferro-white font-semibold text-sm">{{ $p['title'] }}</p>
                        <p class="text-ferro-ash text-xs mt-0.5">{{ $p['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
@verbatim
<script>
(function () {
    // Reveal stagger on product grid
    const grids = document.querySelectorAll('.reveal-stagger');
    if (!grids.length) return;
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target); } });
    }, { threshold: 0.05 });
    grids.forEach(g => obs.observe(g));
})();
</script>
@endverbatim
@endpush
