@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

{{-- ── Dynamic SEO Meta ──────────────────────────────────────────────────── --}}
@section('seo_title',       $seoTitle)
@section('seo_description', $seoDesc)
@section('canonical',       route('products.show', $product->slug))

@section('og_type',        'product')
@section('og_title',        $product->getSeoTitleForLocale(app()->getLocale()))
@section('og_description',  $seoDesc)
@section('og_image',        $product->featured_image ? asset($product->featured_image) : asset('images/ferro-og-default.jpg'))

{{-- ── Schema.org Product JSON-LD ──────────────────────────────────────── --}}
@section('schema_org')
<script type="application/ld+json">
{!! json_encode($schemaOrg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
{{-- BreadcrumbList schema --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        { "@type": "ListItem", "position": 1, "name": "{{ $isAr ? 'الرئيسية' : 'Home' }}", "item": "{{ url('/') }}" },
        { "@type": "ListItem", "position": 2, "name": "{{ $isAr ? 'المتجر' : 'Shop' }}", "item": "{{ route('products.index') }}" },
        @if($product->category)
        { "@type": "ListItem", "position": 3, "name": "{{ $product->category->name }}", "item": "{{ route('products.index') }}?category={{ $product->category->slug }}" },
        { "@type": "ListItem", "position": 4, "name": "{{ $product->name }}", "item": "{{ route('products.show', $product->slug) }}" }
        @else
        { "@type": "ListItem", "position": 3, "name": "{{ $product->name }}", "item": "{{ route('products.show', $product->slug) }}" }
        @endif
    ]
}
</script>
@endsection

@section('content')

@php
    $isComingSoon = $product->status === \App\Models\Product::STATUS_COMING_SOON;
    $isOutOfStock = $product->status === \App\Models\Product::STATUS_OUT_OF_STOCK;
@endphp

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<nav class="pt-[72px] bg-ferro-black border-b border-ferro-carbon/40" aria-label="Breadcrumb">
    <div class="container-ferro py-4">
        <ol class="flex items-center gap-2 text-[11px] text-ferro-ash tracking-wider {{ $isAr ? 'flex-row-reverse justify-end' : '' }}"
            itemscope itemtype="https://schema.org/BreadcrumbList">
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('home') }}" class="hover:text-ferro-orange transition-colors" itemprop="item">
                    <span itemprop="name">{{ $isAr ? 'الرئيسية' : 'Home' }}</span>
                </a>
                <meta itemprop="position" content="1">
            </li>
            <li class="text-ferro-carbon">{{ $isAr ? '\\' : '/' }}</li>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="{{ route('products.index') }}" class="hover:text-ferro-orange transition-colors" itemprop="item">
                    <span itemprop="name">{{ $isAr ? 'المتجر' : 'Shop' }}</span>
                </a>
                <meta itemprop="position" content="2">
            </li>
            @if($product->category)
                <li class="text-ferro-carbon">{{ $isAr ? '\\' : '/' }}</li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="{{ route('products.index') }}?category={{ $product->category->slug }}"
                       class="hover:text-ferro-orange transition-colors" itemprop="item">
                        <span itemprop="name">{{ $product->category->name }}</span>
                    </a>
                    <meta itemprop="position" content="3">
                </li>
            @endif
            <li class="text-ferro-carbon">{{ $isAr ? '\\' : '/' }}</li>
            <li class="text-ferro-silver" aria-current="page">{{ $product->name }}</li>
        </ol>
    </div>
</nav>

{{-- ────────────────────────────────────────────────────────────────────────
     PDP MAIN — IMAGE GALLERY + PRODUCT INFO
──────────────────────────────────────────────────────────────────────────── --}}
<section class="section-pad" itemscope itemtype="https://schema.org/Product">
    <meta itemprop="name"  content="{{ $product->name }}">
    <meta itemprop="sku"   content="{{ $product->sku }}">
    <meta itemprop="brand" content="FERRO">

    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 xl:gap-20">

            {{-- ── LEFT: Image Gallery ──────────────────────────────── --}}
            <div class="relative" x-data="productGallery()">
                {{-- Main image --}}
                <div class="relative aspect-[3/4] overflow-hidden bg-ferro-obsidian" style="border-radius: 2px;">
                    @if($product->featured_image)
                        <img
                            :src="activeImage"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-opacity duration-300"
                            itemprop="image"
                            width="600" height="800"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-24 h-24 text-ferro-carbon" viewBox="0 0 32 32" fill="currentColor">
                                <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Coming-soon overlay on main image --}}
                    @if($isComingSoon)
                        <div class="coming-soon-overlay">
                            <span class="badge-coming-soon text-base">
                                {{ $isAr ? 'قريباً' : 'Coming Soon' }}
                            </span>
                            @if($product->available_at)
                                <span class="text-ferro-silver text-sm">
                                    {{ $isAr ? 'متاح في' : 'Launch' }}: {{ $product->available_at->format('M Y') }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Status badge --}}
                    @if(!$isComingSoon)
                        <div class="absolute top-4 {{ $isAr ? 'end-4' : 'start-4' }} flex flex-col gap-2 z-20">
                            @if($product->is_on_sale)
                                <span class="badge-coming-soon">-{{ $product->discount_percent }}%</span>
                            @endif
                            @if($product->is_new_arrival)
                                <span class="badge bg-ferro-white/10 text-ferro-white border border-ferro-white/20">
                                    {{ $isAr ? 'جديد' : 'New' }}
                                </span>
                            @endif
                            @if($product->is_low_stock)
                                <span class="badge bg-yellow-500/15 text-yellow-400 border border-yellow-500/30">
                                    {{ $isAr ? 'كميات محدودة' : 'Low Stock' }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Thumbnail strip --}}
                @if($product->gallery_images && count($product->gallery_images) > 1)
                    <div class="flex gap-3 mt-4 overflow-x-auto pb-2">
                        <button
                            @click="setImage('{{ asset($product->featured_image) }}')"
                            class="flex-shrink-0 w-20 aspect-square overflow-hidden border-2 transition-all duration-200"
                            :class="activeImage === '{{ asset($product->featured_image) }}' ? 'border-ferro-orange' : 'border-ferro-carbon hover:border-ferro-ash'"
                            style="border-radius: 2px;"
                        >
                            <img src="{{ asset($product->featured_image) }}" alt="" class="w-full h-full object-cover">
                        </button>
                        @foreach($product->gallery_images as $img)
                            <button
                                @click="setImage('{{ asset($img) }}')"
                                class="flex-shrink-0 w-20 aspect-square overflow-hidden border-2 transition-all duration-200"
                                :class="activeImage === '{{ asset($img) }}' ? 'border-ferro-orange' : 'border-ferro-carbon hover:border-ferro-ash'"
                                style="border-radius: 2px;"
                            >
                                <img src="{{ asset($img) }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── RIGHT: Product Details ───────────────────────────── --}}
            <div class="{{ $isAr ? 'text-right' : '' }}">

                {{-- Category --}}
                @if($product->category)
                    <a href="{{ route('products.index') }}?category={{ $product->category->slug }}"
                       class="eyebrow hover:text-ferro-orange/80 transition-colors">
                        {{ $product->category->name }}
                    </a>
                @endif

                {{-- Product Name --}}
                <h1 class="font-display text-display-lg text-ferro-white mb-2">{{ $product->name }}</h1>

                {{-- Tagline --}}
                @if($product->tagline)
                    <p class="text-ferro-silver text-body-lg mb-6 italic">{{ $product->tagline }}</p>
                @endif

                {{-- Price --}}
                <div class="flex items-center gap-4 mb-8 {{ $isAr ? 'flex-row-reverse justify-end' : '' }}"
                     itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <meta itemprop="priceCurrency" content="{{ $product->currency }}">
                    @if($isComingSoon)
                        <span class="text-ferro-orange text-label tracking-widest uppercase font-semibold">
                            {{ $isAr ? 'السعر يُعلن قريباً' : 'Price Coming Soon' }}
                        </span>
                    @else
                        <span class="font-display text-3xl text-ferro-white" itemprop="price" content="{{ $product->price }}">
                            {{ $product->currency === 'USD' ? '$' : $product->currency }}{{ number_format($product->price, 2) }}
                        </span>
                        @if($product->is_on_sale)
                            <span class="text-ferro-ash text-xl line-through">
                                {{ $product->currency === 'USD' ? '$' : $product->currency }}{{ number_format($product->compare_price, 2) }}
                            </span>
                            <span class="badge-coming-soon">
                                {{ $isAr ? 'وفّر' : 'Save' }} {{ $product->discount_percent }}%
                            </span>
                        @endif
                        <link itemprop="availability"
                              href="{{ $product->status === 'active' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}">
                    @endif
                </div>

                {{-- Short description --}}
                @if($product->short_description)
                    <div class="ferro-prose mb-8">
                        {!! $product->short_description !!}
                    </div>
                @endif

                {{-- Volume / size --}}
                @if($product->volume_ml)
                    <div class="flex items-center gap-2 mb-6 text-ferro-silver text-body-sm {{ $isAr ? 'flex-row-reverse justify-end' : '' }}">
                        <svg class="w-4 h-4 text-ferro-ash" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v11.25a3.75 3.75 0 007.5 0V3m-7.5 0h7.5M9 3H6.75A2.25 2.25 0 004.5 5.25v13.5A2.25 2.25 0 006.75 21h10.5a2.25 2.25 0 002.25-2.25V5.25A2.25 2.25 0 0017.25 3H15"/>
                        </svg>
                        <span>{{ $product->volume_ml }}</span>
                    </div>
                @endif

                {{-- ── ADD TO CART / WAITLIST ───────────────────────── --}}
                @if($isComingSoon)
                    {{-- Coming-soon: waitlist capture inline --}}
                    <div class="waitlist-card mb-8">
                        <p class="text-ferro-silver text-body-sm mb-4">
                            {{ $isAr
                                ? 'سجّل بريدك وكن أول من يعرف حين يتوفر هذا المنتج.'
                                : 'Register your email and be the first to know when this drops.' }}
                        </p>
                        @include('partials.waitlist-mini-form', [
                            'formId'    => 'pdp-waitlist',
                            'productId' => $product->id,
                        ])
                    </div>
                @elseif($isOutOfStock)
                    <div class="mb-8">
                        <div class="badge-out-of-stock mb-4 inline-flex">
                            {{ $isAr ? 'نفد المخزون حالياً' : 'Currently Out of Stock' }}
                        </div>
                        <div class="waitlist-card">
                            <p class="text-ferro-silver text-body-sm mb-4">
                                {{ $isAr ? 'أشعرني حين يعود للمخزون' : 'Notify me when back in stock' }}
                            </p>
                            @include('partials.waitlist-mini-form', [
                                'formId'    => 'pdp-restock',
                                'productId' => $product->id,
                            ])
                        </div>
                    </div>
                @else
                    {{-- Active product: add to cart --}}
                    <div class="flex flex-col sm:flex-row gap-4 mb-8" x-data="{ qty: 1 }">
                        <div class="qty-stepper">
                            <button @click="qty = Math.max(1, qty - 1)" type="button" aria-label="{{ $isAr ? 'تقليل' : 'Decrease' }}">−</button>
                            <input type="number" x-model="qty" min="1" max="10" aria-label="Quantity">
                            <button @click="qty = Math.min(10, qty + 1)" type="button" aria-label="{{ $isAr ? 'زيادة' : 'Increase' }}">+</button>
                        </div>
                        <button
                            class="btn-primary flex-1 clip-luxury-md"
                            @click="addToCart({{ $product->id }}, qty)"
                        >
                            {{ $isAr ? 'أضف إلى السلة' : 'Add to Arsenal' }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.853-7.16"/>
                            </svg>
                        </button>

                        {{-- Subscribe option --}}
                        @if($product->is_subscribable)
                            <a href="#subscribe" class="btn-secondary clip-luxury-sm px-5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                                {{ $isAr ? 'اشترِ واوفّر' : 'Subscribe & Save' }}
                            </a>
                        @endif
                    </div>
                @endif

                {{-- ── Benefits quick list ──────────────────────────── --}}
                @if($product->benefits && count($product->benefits))
                    <div class="mb-8">
                        <h3 class="text-label tracking-widest uppercase text-ferro-silver mb-3">
                            {{ $isAr ? 'الفوائد الرئيسية' : 'Key Benefits' }}
                        </h3>
                        <ul class="space-y-2">
                            @foreach(array_slice($product->benefits, 0, 4) as $benefit)
                                <li class="flex items-start gap-3 text-ferro-off-white text-body-sm {{ $isAr ? 'flex-row-reverse' : '' }}">
                                    <svg class="w-4 h-4 text-ferro-orange flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>{{ $benefit }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Trust badges --}}
                <div class="flex flex-wrap gap-4 pt-6 border-t border-ferro-carbon {{ $isAr ? 'justify-end' : '' }}">
                    @foreach([
                        ['icon' => '🌿', 'label' => $isAr ? 'طبيعي ١٠٠٪' : '100% Natural'],
                        ['icon' => '⚗️', 'label' => $isAr ? 'خالٍ من الضار' : 'Clean Formula'],
                        ['icon' => '🚚', 'label' => $isAr ? 'شحن سريع' : 'Fast Shipping'],
                        ['icon' => '↩️', 'label' => $isAr ? 'إرجاع مجاني' : 'Free Returns'],
                    ] as $trust)
                        <div class="flex items-center gap-1.5 text-ferro-ash text-[11px] tracking-wider">
                            <span>{{ $trust['icon'] }}</span>
                            <span>{{ $trust['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     PDP TABS — Description | Ingredients | How To Use
──────────────────────────────────────────────────────────────────────────── --}}
<section class="section-pad bg-ferro-obsidian" x-data="{ tab: 'description' }">
    <div class="container-ferro">

        {{-- Tab navigation --}}
        <div class="flex {{ $isAr ? 'flex-row-reverse' : '' }} border-b border-ferro-carbon mb-10 overflow-x-auto">
            @foreach([
                ['key' => 'description',  'label' => $isAr ? 'الوصف' : 'Description'],
                ['key' => 'ingredients',  'label' => $isAr ? 'المكونات' : 'Ingredients'],
                ['key' => 'how_to_use',   'label' => $isAr ? 'طريقة الاستخدام' : 'How to Use'],
            ] as $tabItem)
                <button
                    @click="tab = '{{ $tabItem['key'] }}'"
                    class="px-6 py-4 text-body-sm font-medium tracking-wider uppercase whitespace-nowrap transition-all duration-200 border-b-2 -mb-px"
                    :class="tab === '{{ $tabItem['key'] }}'
                        ? 'border-ferro-orange text-ferro-white'
                        : 'border-transparent text-ferro-ash hover:text-ferro-silver'"
                    :aria-selected="tab === '{{ $tabItem['key'] }}'"
                    role="tab"
                >
                    {{ $tabItem['label'] }}
                </button>
            @endforeach
        </div>

        {{-- Tab content --}}
        <div class="max-w-3xl {{ $isAr ? 'text-right ml-auto' : '' }}">

            {{-- Description --}}
            <div x-show="tab === 'description'" x-transition>
                @if($product->description)
                    <div class="ferro-prose">
                        {!! $product->description !!}
                    </div>
                @endif
            </div>

            {{-- Ingredients --}}
            <div x-show="tab === 'ingredients'" x-transition>
                @if($product->ingredients)
                    <p class="text-ferro-ash text-body-sm mb-6">
                        {{ $isAr ? 'مكونات مختارة بعناية، طبيعية ومثبتة علمياً:' : 'Carefully selected, scientifically proven natural ingredients:' }}
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', strip_tags($product->ingredients)) as $ingredient)
                            @if(trim($ingredient))
                                <span class="ingredient-tag">
                                    <svg class="w-3 h-3 text-ferro-orange" viewBox="0 0 12 12" fill="currentColor">
                                        <circle cx="6" cy="6" r="3"/>
                                    </svg>
                                    {{ trim($ingredient) }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- How to use --}}
            <div x-show="tab === 'how_to_use'" x-transition>
                @if($product->how_to_use)
                    <div class="ferro-prose">
                        {!! $product->how_to_use !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     RELATED PRODUCTS
──────────────────────────────────────────────────────────────────────────── --}}
@if($related->count())
<section class="section-pad" aria-labelledby="related-heading">
    <div class="container-ferro">
        <h2 id="related-heading" class="font-display text-display-lg text-ferro-white mb-10 reveal {{ $isAr ? 'text-right' : '' }}">
            {{ $isAr ? 'منتجات مكمّلة' : 'Complete the Arsenal' }}
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal-stagger">
            @foreach($related as $relatedProduct)
                @include('partials.product-card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function productGallery() {
    return {
        activeImage: '{{ $product->featured_image ? asset($product->featured_image) : '' }}',
        setImage(src) { this.activeImage = src; }
    };
}

function addToCart(productId, qty) {
    // Cart implementation — sends to cart API or Livewire component
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: qty }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update cart badge
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.textContent = data.cart_count;
                badge.classList.remove('hidden');
            }
            // Show toast
            showToast('{{ $isAr ? 'أُضيف إلى سلتك!' : 'Added to your arsenal!' }}', 'success');
        }
    });
}

// Reveal animations
(function() {
    const io = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
        { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
    );
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));
})();
</script>
@endpush
