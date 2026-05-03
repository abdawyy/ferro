@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr
    ? 'المتجر — ترسانة فيرو | منتجات العناية الفاخرة للرجال'
    : 'Shop — The FERRO Arsenal | Premium Men\'s Grooming')
@section('seo_description', $isAr
    ? 'تسوّق جميع منتجات فيرو للعناية الفاخرة. مكونات طبيعية مصممة للرياضي عالي الأداء.'
    : 'Shop all FERRO premium grooming products. Natural ingredients engineered for the elite athlete.')

@section('content')

{{-- ── Page header ────────────────────────────────────────────────────── --}}
<div class="pt-[72px] bg-ferro-obsidian border-b border-ferro-carbon">
    <div class="container-ferro py-14 md:py-20">
        <div class="{{ $isAr ? 'text-right' : '' }}">
            <span class="eyebrow">{{ $isAr ? 'ترسانة فيرو' : 'The FERRO Arsenal' }}</span>
            <h1 class="font-display text-display-xl text-ferro-white">
                {{ $isAr ? 'أدوات لا تهادن' : 'Tools That Don\'t Compromise' }}
            </h1>
            @if($totalCount > 0)
                <p class="text-ferro-ash text-body-sm mt-2">
                    {{ $totalCount }} {{ $isAr ? 'منتج' : ($totalCount === 1 ? 'product' : 'products') }}
                </p>
            @endif
        </div>
    </div>
</div>

{{-- ── Filters + Grid ─────────────────────────────────────────────────── --}}
<div class="container-ferro section-pad" x-data="shopFilters()">

    {{-- ── Filter Bar ─────────────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-10">

        {{-- Category tabs --}}
        <div class="flex flex-wrap items-center gap-2" aria-label="{{ $isAr ? 'تصفية حسب الفئة' : 'Filter by category' }}">
            <a
                href="{{ route('products.index') }}"
                class="filter-chip {{ !request('category') ? 'active' : '' }}"
            >{{ $isAr ? 'الكل' : 'All' }}</a>
            @foreach($categories as $cat)
                <a
                    href="{{ route('products.index', array_merge(request()->query(), ['category' => $cat->slug])) }}"
                    class="filter-chip {{ request('category') === $cat->slug ? 'active' : '' }}"
                >{{ $cat->name }}</a>
            @endforeach
        </div>

        {{-- Sort + View controls --}}
        <div class="flex items-center gap-3">
            {{-- Status filter --}}
            <select
                onchange="window.location.href=this.value"
                class="input-ferro py-2 px-4 text-body-sm min-w-[150px]"
                aria-label="{{ $isAr ? 'تصفية الحالة' : 'Filter by status' }}"
            >
                <option value="{{ route('products.index', array_merge(request()->except('status'), [])) }}"
                        {{ !request('status') ? 'selected' : '' }}>
                    {{ $isAr ? 'جميع الحالات' : 'All Status' }}
                </option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['status' => 'active'])) }}"
                        {{ request('status') === 'active' ? 'selected' : '' }}>
                    {{ $isAr ? 'متاح الآن' : 'In Stock' }}
                </option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['status' => 'coming_soon'])) }}"
                        {{ request('status') === 'coming_soon' ? 'selected' : '' }}>
                    {{ $isAr ? 'قريباً' : 'Coming Soon' }}
                </option>
            </select>

            {{-- Sort --}}
            <select
                onchange="window.location.href=this.value"
                class="input-ferro py-2 px-4 text-body-sm min-w-[160px]"
                aria-label="{{ $isAr ? 'الترتيب' : 'Sort by' }}"
            >
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'featured'])) }}"
                        {{ (!request('sort') || request('sort') === 'featured') ? 'selected' : '' }}>
                    {{ $isAr ? 'مميز' : 'Featured' }}
                </option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}"
                        {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                    {{ $isAr ? 'السعر: الأقل' : 'Price: Low to High' }}
                </option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}"
                        {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                    {{ $isAr ? 'السعر: الأعلى' : 'Price: High to Low' }}
                </option>
                <option value="{{ route('products.index', array_merge(request()->query(), ['sort' => 'newest'])) }}"
                        {{ request('sort') === 'newest' ? 'selected' : '' }}>
                    {{ $isAr ? 'الأحدث' : 'Newest' }}
                </option>
            </select>

            {{-- Grid / List toggle --}}
            <div class="hidden sm:flex items-center gap-1 bg-ferro-carbon rounded-sm p-1">
                <button
                    @click="gridCols = 4"
                    :class="gridCols === 4 ? 'bg-ferro-obsidian text-ferro-white' : 'text-ferro-ash hover:text-ferro-silver'"
                    class="p-1.5 rounded-sm transition-colors"
                    aria-label="{{ $isAr ? 'عرض شبكة صغيرة' : 'Small grid' }}"
                >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 012.5 1h3A1.5 1.5 0 017 2.5v3A1.5 1.5 0 015.5 7h-3A1.5 1.5 0 011 5.5v-3zm8 0A1.5 1.5 0 0110.5 1h3A1.5 1.5 0 0115 2.5v3A1.5 1.5 0 0113.5 7h-3A1.5 1.5 0 019 5.5v-3zm-8 8A1.5 1.5 0 012.5 9h3A1.5 1.5 0 017 10.5v3A1.5 1.5 0 015.5 15h-3A1.5 1.5 0 011 13.5v-3zm8 0A1.5 1.5 0 0110.5 9h3a1.5 1.5 0 011.5 1.5v3A1.5 1.5 0 0113.5 15h-3A1.5 1.5 0 019 13.5v-3z"/>
                    </svg>
                </button>
                <button
                    @click="gridCols = 3"
                    :class="gridCols === 3 ? 'bg-ferro-obsidian text-ferro-white' : 'text-ferro-ash hover:text-ferro-silver'"
                    class="p-1.5 rounded-sm transition-colors"
                    aria-label="{{ $isAr ? 'عرض شبكة كبيرة' : 'Large grid' }}"
                >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1 2.5A1.5 1.5 0 012.5 1h11A1.5 1.5 0 0115 2.5v11a1.5 1.5 0 01-1.5 1.5h-11A1.5 1.5 0 011 13.5v-11zM2.5 2a.5.5 0 00-.5.5v4.793l3.146-3.147a.5.5 0 01.708 0L9 7.293l2.146-2.147a.5.5 0 01.708 0L14 7.5V2.5a.5.5 0 00-.5-.5h-11z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Active filter tags ─────────────────────────────────────────── --}}
    @if(request('category') || request('status') || request('sort'))
        <div class="flex flex-wrap items-center gap-2 mb-8 {{ $isAr ? 'flex-row-reverse' : '' }}">
            <span class="text-ferro-ash text-xs">{{ $isAr ? 'عوامل التصفية:' : 'Active filters:' }}</span>
            @if(request('category'))
                <a href="{{ route('products.index', request()->except('category')) }}"
                   class="flex items-center gap-1.5 bg-ferro-carbon/60 text-ferro-silver text-xs px-3 py-1.5 border border-ferro-carbon hover:border-ferro-orange/40 transition-colors"
                   style="border-radius:2px;">
                    {{ request('category') }}
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
            @if(request('status'))
                <a href="{{ route('products.index', request()->except('status')) }}"
                   class="flex items-center gap-1.5 bg-ferro-carbon/60 text-ferro-silver text-xs px-3 py-1.5 border border-ferro-carbon hover:border-ferro-orange/40 transition-colors"
                   style="border-radius:2px;">
                    {{ request('status') }}
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="text-ferro-orange text-xs underline-offset-2 hover:underline">
                {{ $isAr ? 'مسح الكل' : 'Clear all' }}
            </a>
        </div>
    @endif

    {{-- ── Product Grid ────────────────────────────────────────────────── --}}
    @if($products->isEmpty())
        {{-- Empty state --}}
        <div class="text-center py-24 reveal">
            <svg class="w-16 h-16 text-ferro-carbon mx-auto mb-6" viewBox="0 0 32 32" fill="currentColor">
                <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
            </svg>
            <h2 class="font-display text-display-lg text-ferro-white mb-3">
                {{ $isAr ? 'لا توجد منتجات' : 'No Products Found' }}
            </h2>
            <p class="text-ferro-silver text-body-sm mb-8">
                {{ $isAr ? 'جرّب تعديل عوامل التصفية.' : 'Try adjusting your filters.' }}
            </p>
            <a href="{{ route('products.index') }}" class="btn-secondary">
                {{ $isAr ? 'عرض الكل' : 'View All Products' }}
            </a>
        </div>
    @else
        <div
            class="grid gap-6 reveal-stagger"
            :class="gridCols === 4
                ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4'
                : 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'"
        >
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="mt-16 flex justify-center {{ $isAr ? 'flex-row-reverse' : '' }}">
                {{ $products->links('partials.pagination') }}
            </div>
        @endif
    @endif

</div>

{{-- ── Coming Soon Spotlight (if any) ─────────────────────────────────── --}}
@if($comingSoonProducts->count() && !request('status'))
<section class="section-pad bg-ferro-obsidian" aria-labelledby="coming-soon-shop-heading">
    <div class="container-ferro">
        <div class="text-center mb-12 reveal {{ $isAr ? 'text-right' : '' }}">
            <span class="eyebrow">{{ $isAr ? 'قادم قريباً' : 'Coming Soon' }}</span>
            <h2 id="coming-soon-shop-heading" class="font-display text-display-lg text-ferro-white">
                {{ $isAr ? 'الترسانة تتوسع' : 'The Arsenal Expands' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal-stagger">
            @foreach($comingSoonProducts as $product)
                @include('partials.product-card', ['product' => $product, 'showComingSoon' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function shopFilters() {
    return {
        gridCols: 4,
    };
}

// Reveal animations
(function() {
    const io = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
        { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
    );
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));
})();
</script>
@endpush
