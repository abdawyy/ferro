{{--
    FERRO Product Card Partial
    Supports: active products, coming-soon overlay, sale badge, subscription badge
    Props:
      $product         — Product model
      $showComingSoon  — Force coming-soon overlay even if active (optional)
--}}
@php
    $isAr         = app()->getLocale() === 'ar';
    $isComingSoon = $showComingSoon ?? ($product->status === \App\Models\Product::STATUS_COMING_SOON);
    $isOutOfStock = $product->status === \App\Models\Product::STATUS_OUT_OF_STOCK;
    $pdpUrl       = route('products.show', $product->slug);
    $featuredUrl  = null;
    if ($product->featured_image) {
        $fi = $product->featured_image;
        if (\Illuminate\Support\Str::startsWith($fi, ['http://', 'https://', '//'])) {
            $featuredUrl = $fi;
        } elseif (\Illuminate\Support\Str::startsWith($fi, 'images/')) {
            $featuredUrl = asset($fi);
        } else {
            $featuredUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($fi);
        }
    }
@endphp

<article
    class="card-product group min-w-0"
    itemscope
    itemtype="https://schema.org/Product"
>
    {{-- ── Product Image ─────────────────────────────────────────────── --}}
    <a href="{{ $pdpUrl }}" class="block product-image-wrap" aria-label="{{ $product->name }}">
        @if($featuredUrl)
            <img
                src="{{ $featuredUrl }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover"
                loading="lazy"
                width="400" height="533"
                itemprop="image"
            >
        @else
            {{-- Placeholder --}}
            <div class="w-full h-full flex items-center justify-center bg-ferro-carbon">
                <svg class="w-16 h-16 text-ferro-ash" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                </svg>
            </div>
        @endif

        {{-- Badges (absolute positioned top) --}}
        <div class="absolute top-3 {{ $isAr ? 'end-3' : 'start-3' }} flex flex-col gap-2 z-20">
            @if($isComingSoon)
                <span class="badge-coming-soon">{{ $isAr ? 'قريباً' : 'Coming Soon' }}</span>
            @elseif($product->is_new_arrival)
                <span class="badge bg-ferro-white/10 text-ferro-white border border-ferro-white/20">{{ $isAr ? 'جديد' : 'New' }}</span>
            @elseif($product->is_best_seller)
                <span class="badge bg-yellow-500/15 text-yellow-400 border border-yellow-500/30">{{ $isAr ? 'الأكثر مبيعاً' : 'Best Seller' }}</span>
            @endif
            @if($product->is_on_sale && !$isComingSoon)
                <span class="badge bg-ferro-orange/15 text-ferro-orange border border-ferro-orange/30">
                    -{{ $product->discount_percent }}%
                </span>
            @endif
        </div>

        {{-- Subscription badge --}}
        @if($product->is_subscribable && !$isComingSoon)
            <div class="absolute top-3 {{ $isAr ? 'start-3' : 'end-3' }} z-20">
                <span class="badge bg-purple-500/15 text-purple-400 border border-purple-500/30">
                    {{ $isAr ? 'اشتراك' : 'Subscribe' }}
                </span>
            </div>
        @endif

        {{-- Coming-soon overlay --}}
        @if($isComingSoon)
            <div class="coming-soon-overlay">
                <svg class="w-8 h-8 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                </svg>
                <span class="font-body font-semibold text-label tracking-widest uppercase text-ferro-orange">
                    {{ $isAr ? 'قريباً' : 'Coming Soon' }}
                </span>
                @if($product->available_at)
                    <span class="text-ferro-silver text-xs">
                        {{ $isAr ? 'متاح في' : 'Available' }} {{ $product->available_at->format('M Y') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Out of stock overlay --}}
        @if($isOutOfStock && !$isComingSoon)
            <div class="absolute inset-0 bg-ferro-black/50 flex items-end p-4 z-10">
                <span class="badge-out-of-stock w-full justify-center">{{ $isAr ? 'نفد المخزون' : 'Out of Stock' }}</span>
            </div>
        @endif
    </a>

    {{-- ── Product Info ──────────────────────────────────────────────── --}}
    <div class="p-5 min-w-0 {{ $isAr ? 'text-right' : '' }}">
        {{-- Category --}}
        @if($product->category)
            <span class="text-ferro-ash text-[11px] tracking-widest uppercase font-medium mb-1 block truncate">
                {{ $product->category->getTranslation('name', app()->getLocale(), false) ?? $product->category->name }}
            </span>
        @endif

        {{-- Name --}}
        <h3 class="font-display text-lg text-ferro-white mb-1 leading-tight min-w-0" itemprop="name">
            <a href="{{ $pdpUrl }}" class="hover:text-ferro-orange transition-colors duration-200 line-clamp-2">
                {{ $product->name }}
            </a>
        </h3>

        {{-- Short description --}}
        @if($product->short_description)
            <p class="text-ferro-ash text-[13px] leading-relaxed mb-4 line-clamp-2">
                {{ strip_tags($product->short_description) }}
            </p>
        @endif

        {{-- Price + CTA row (quick-add: always stacked so buttons never overlap price) --}}
        <div class="flex flex-col gap-3 w-full min-w-0 {{ ($showQuickAdd ?? false) ? '' : 'sm:flex-row sm:items-end sm:justify-between sm:gap-3' }}">
            <div class="min-w-0 shrink w-full {{ ($showQuickAdd ?? false) ? '' : 'sm:w-auto' }}" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                <meta itemprop="priceCurrency" content="{{ $product->currency }}">
                @if($isComingSoon)
                    <span class="text-ferro-orange text-label tracking-widest uppercase font-semibold">
                        {{ $isAr ? 'سعر قريباً' : 'Price TBA' }}
                    </span>
                @else
                    <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                        <span class="text-ferro-white font-semibold text-base tabular-nums" itemprop="price" content="{{ number_format((float) $product->price, 2, '.', '') }}">
                            {{ $product->currency === 'USD' ? '$' : $product->currency }}{{ number_format((float) $product->price, 2) }}
                        </span>
                        @if($product->is_on_sale)
                            <span class="text-ferro-ash text-sm line-through tabular-nums">
                                {{ $product->currency === 'USD' ? '$' : $product->currency }}{{ number_format((float) $product->compare_price, 2) }}
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Action button --}}
            @if($isComingSoon)
                <a href="{{ $pdpUrl }}#pdp-waitlist" class="btn-secondary px-4 py-2 text-xs shrink-0 text-center">
                    {{ $isAr ? 'أشعرني' : 'Notify Me' }}
                </a>
            @elseif($isOutOfStock)
                <a href="{{ $pdpUrl }}#pdp-restock" class="btn-secondary px-4 py-2 text-xs shrink-0 text-center">
                    {{ $isAr ? 'أشعرني' : 'Notify Me' }}
                </a>
            @elseif($showQuickAdd ?? false)
                <div class="flex flex-wrap items-stretch sm:items-center gap-2 w-full {{ $isAr ? 'justify-end' : 'justify-start' }}">
                    <button
                        type="button"
                        class="btn-primary px-3 py-2 text-[11px] uppercase tracking-wider clip-luxury-sm"
                        data-ferro-add-to-cart="{{ $product->id }}"
                        data-ferro-add-qty="1"
                    >
                        {{ $isAr ? 'أضف للسلة' : 'Add to cart' }}
                    </button>
                    <a href="{{ $pdpUrl }}" class="btn-secondary px-3 py-2 text-[11px] uppercase tracking-wider text-center">
                        {{ $isAr ? 'التفاصيل' : 'Details' }}
                    </a>
                </div>
            @else
                <a href="{{ $pdpUrl }}" class="btn-primary px-4 py-2 text-xs clip-luxury-sm shrink-0 text-center">
                    {{ $isAr ? 'اشترِ الآن' : 'Shop Now' }}
                </a>
            @endif
        </div>
    </div>
</article>
