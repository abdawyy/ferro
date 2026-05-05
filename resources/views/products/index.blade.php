{{--
    FERRO — Shop / Product Listing Page
    Variables: $products, $categories, $shopQuickFilters (collections / paginator)
--}}
@extends('layouts.app')

@php
    $isAr   = app()->getLocale() === 'ar';
    $active = request('category', '');
    $status = request('status', '');
    $q      = request('q', '');
@endphp

@section('seo_title',       $isAr ? 'المتجر — FERRO' : 'Shop — FERRO')
@section('seo_description', $isAr ? 'تسوّق منتجات العناية الفاخرة المصممة للرجل عالي الأداء.' : 'Shop premium luxury grooming essentials engineered for the high-performance man.')

@push('head')
<style>
    #shop-catalog-progress-track {
        opacity: 0;
        transition: opacity 0.15s ease;
    }
    #ferro-shop.shop-catalog-loading #shop-catalog-progress-track {
        opacity: 1;
    }
    #shop-catalog-progress-bar {
        width: 38%;
        animation: ferro-shop-progress-slide 0.95s ease-in-out infinite;
    }
    @keyframes ferro-shop-progress-slide {
        from { transform: translateX(-105%); }
        to { transform: translateX(320%); }
    }
</style>
@endpush

@section('content')

{{-- ── Hero Banner ──────────────────────────────────────────────────────── --}}
<section class="relative pt-[72px] overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset(config('ferro.page_backgrounds.heroes.shop')) }}" alt=""
             class="ferro-brand-photo w-full h-full object-cover object-right max-md:object-center opacity-55" aria-hidden="true" loading="eager" decoding="sync">
        <div class="absolute inset-0 bg-gradient-to-b from-ferro-black/72 via-ferro-obsidian/88 to-ferro-black"></div>
    </div>

    <div class="container-ferro relative z-10 py-20 text-center">
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
        <p class="mt-4 text-ferro-ash text-sm" id="shop-hero-count" data-shop-hero-count>
            <span data-shop-total>{{ $products->total() }}</span>
            {{ $isAr ? 'منتج' : ($products->total() === 1 ? 'product' : 'products') }}
            @if($q !== '')
                — {{ $isAr ? 'نتائج البحث عن' : 'for' }} “{{ \Illuminate\Support\Str::limit($q, 48) }}”
            @endif
        </p>
    </div>

    {{-- bottom fade --}}
    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-ferro-black to-transparent pointer-events-none"></div>
</section>

{{-- ── Filter Bar (categories + quick filters from admin; AJAX on this page) ─ --}}
<section class="sticky top-[72px] z-40 bg-ferro-black/95 backdrop-blur-xl border-b border-ferro-carbon/60 relative"
         id="ferro-shop"
         data-shop-catalog-url="{{ route('api.shop.catalog') }}"
         data-shop-path="{{ parse_url(route('products.index'), PHP_URL_PATH) }}">
    <div id="shop-catalog-progress-track" class="pointer-events-none absolute bottom-0 left-0 right-0 z-[60] h-[3px] overflow-hidden bg-ferro-carbon/40" aria-hidden="true">
        <div id="shop-catalog-progress-bar" class="h-full rounded-full bg-gradient-to-r from-ferro-orange/30 via-ferro-orange to-ferro-orange/30 shadow-[0_0_14px_rgba(232,80,10,0.45)]"></div>
    </div>
    <div class="container-ferro">
        <div class="flex items-center gap-2 py-4 overflow-x-auto scrollbar-hide md:flex-wrap md:overflow-x-visible md:gap-y-2 touch-pan-x">

            <a href="{{ route('products.index', array_filter(request()->only('q'))) }}"
               class="filter-pill shop-filter-nav flex-shrink-0 {{ $active === '' && $status === '' ? 'active' : '' }}"
               data-shop-all="1">
                {{ $isAr ? 'الكل' : 'All' }}
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('products.index', array_filter(array_merge(request()->only('q'), ['category' => $cat->slug]))) }}"
                   class="filter-pill shop-filter-nav flex-shrink-0 {{ $active === $cat->slug ? 'active' : '' }}"
                   data-shop-category="{{ $cat->slug }}">
                    {{ $cat->getTranslation('name', app()->getLocale(), false) ?? $cat->name }}
                </a>
            @endforeach

            @if($shopQuickFilters->isNotEmpty())
                <span class="w-px h-5 bg-ferro-carbon/80 mx-1 flex-shrink-0 max-md:hidden md:inline-block" aria-hidden="true"></span>
                <span class="w-full h-px bg-ferro-carbon/80 my-1 md:hidden flex-shrink-0 basis-full" aria-hidden="true"></span>

                @foreach($shopQuickFilters as $qf)
                    <a href="{{ route('products.index', array_filter(array_merge(request()->except('status', 'page'), ['status' => $qf->product_status]))) }}"
                       class="filter-pill shop-filter-nav flex-shrink-0 {{ $status === $qf->product_status ? 'active' : '' }}"
                       data-shop-status="{{ $qf->product_status }}">
                        {{ $qf->getTranslation('name', app()->getLocale(), false) ?? $qf->name }}
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- ── Products Grid (fragment replaced via AJAX) ───────────────────────── --}}
<section class="section-pad">
    <div class="container-ferro" id="shop-catalog-mount">
        @include('products.partials.shop-results', ['products' => $products])
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
    function bindRevealStagger(root) {
        const grids = root.querySelectorAll('.reveal-stagger');
        if (!grids.length) return;
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.05 });
        grids.forEach(g => obs.observe(g));
    }

    bindRevealStagger(document);

    const shop = document.getElementById('ferro-shop');
    const mount = document.getElementById('shop-catalog-mount');
    const totalEl = document.querySelector('[data-shop-total]');
    const catalogUrl = shop && shop.dataset.shopCatalogUrl;
    const shopPath = shop && shop.dataset.shopPath;
    if (!shop || !mount || !catalogUrl || !shopPath) return;

    let inflight = null;
    let loadGeneration = 0;

    function setCatalogLoading(on) {
        shop.classList.toggle('shop-catalog-loading', on);
    }

    function shopListPath(pathname) {
        const p = (pathname || '').replace(/\/$/, '') || '/';
        const target = (shopPath || '').replace(/\/$/, '') || '/';
        return p === target;
    }

    function syncPills(search) {
        const params = new URLSearchParams(search.startsWith('?') ? search.slice(1) : search);
        const cat = params.get('category') || '';
        const st = params.get('status') || '';
        shop.querySelectorAll('a.shop-filter-nav').forEach((a) => {
            let on = false;
            if (a.getAttribute('data-shop-all') === '1') {
                on = cat === '' && st === '';
            } else if (a.hasAttribute('data-shop-status')) {
                on = st === (a.getAttribute('data-shop-status') || '');
            } else if (a.hasAttribute('data-shop-category')) {
                on = cat === (a.getAttribute('data-shop-category') || '');
            }
            a.classList.toggle('active', on);
        });
    }

    function updateTotal(n) {
        if (totalEl) totalEl.textContent = String(n);
    }

    function loadCatalog(search, push) {
        const qs = search.startsWith('?') ? search.slice(1) : search;
        const url = qs ? catalogUrl + '?' + qs : catalogUrl;
        if (inflight) inflight.abort();
        inflight = new AbortController();
        const gen = ++loadGeneration;
        setCatalogLoading(true);
        mount.setAttribute('aria-busy', 'true');
        fetch(url, {
            signal: inflight.signal,
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((r) => {
                if (!r.ok) throw new Error('Network');
                return r.json();
            })
            .then((data) => {
                mount.innerHTML = data.html;
                updateTotal(data.total);
                bindRevealStagger(mount);
                syncPills(qs ? '?' + qs : '');
                if (push) {
                    const path = shopPath.startsWith('/') ? shopPath : '/' + shopPath;
                    history.pushState({ shop: true }, '', qs ? path + '?' + qs : path);
                }
            })
            .catch(() => {})
            .finally(() => {
                if (gen !== loadGeneration) return;
                mount.removeAttribute('aria-busy');
                setCatalogLoading(false);
            });
    }

    shop.addEventListener('click', (e) => {
        const a = e.target.closest('a.shop-filter-nav');
        if (!a || !shop.contains(a)) return;
        e.preventDefault();
        const u = new URL(a.href, window.location.origin);
        if (!shopListPath(u.pathname)) return;
        loadCatalog(u.search, true);
    });

    mount.addEventListener('click', (e) => {
        const filter = e.target.closest('a.shop-filter-nav');
        if (filter && mount.contains(filter)) {
            e.preventDefault();
            const u = new URL(filter.href, window.location.origin);
            if (!shopListPath(u.pathname)) return;
            loadCatalog(u.search, true);
            return;
        }
        const a = e.target.closest('a.shop-ajax-nav');
        if (!a || !mount.contains(a)) return;
        e.preventDefault();
        const u = new URL(a.href, window.location.origin);
        if (!shopListPath(u.pathname)) return;
        loadCatalog(u.search, true);
        const grid = mount.querySelector('.shop-product-grid');
        if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    window.addEventListener('popstate', () => {
        loadCatalog(window.location.search, false);
    });
})();
</script>
@endverbatim
@endpush
