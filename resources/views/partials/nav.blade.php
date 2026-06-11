{{--
    FERRO — Navigation Partial
    Features:
    - Sticky on scroll (JS adds .is-scrolled)
    - Language toggle (EN / AR)
    - Cart indicator
    - Mobile drawer
    RTL-aware with logical CSS properties (start/end instead of left/right)
--}}
<header
    id="site-nav"
    class="fixed top-0 inset-x-0 z-[999] transition-all duration-300"
    x-data="{ mobileOpen: false, searchOpen: false }"
    @keydown.escape.window="searchOpen = false; mobileOpen = false"
>
    {{-- Always-on dark scrim so links stay readable over bright / white hero photography; strengthens on scroll --}}
    <div id="nav-backdrop"
         class="nav-backdrop-layer absolute inset-0 z-0 pointer-events-none border-b border-ferro-carbon/45"
         aria-hidden="true"></div>

    <div class="container-ferro relative z-10">
        <div class="flex items-center justify-between h-[72px] w-full min-w-0 gap-2 sm:gap-4">

            {{-- ── Logotype (shrink on narrow screens so actions + hamburger stay visible) ── --}}
            <a href="{{ route('home') }}" class="flex min-w-0 shrink items-center gap-2 sm:gap-3 group" aria-label="FERRO Home">
                @if(ferro_storefront_logo_url())
                <img
                    src="{{ ferro_storefront_logo_url() }}"
                    alt="FERRO"
                    class="h-7 sm:h-9 w-auto max-w-[140px] shrink-0 object-contain group-hover:opacity-90 transition-opacity duration-300"
                    width="140"
                    height="36"
                >
                @else
                @include('partials.brand-mark-f', ['class' => 'w-7 h-7 sm:w-8 sm:h-8 shrink-0 text-ferro-orange group-hover:scale-110 transition-transform duration-300'])
                @endif
                <span class="font-display text-lg sm:text-2xl font-semibold tracking-[0.12em] sm:tracking-[0.2em] text-ferro-white uppercase truncate">
                    FERRO
                </span>
            </a>

            {{-- ── Desktop Nav Links ─────────────────────────────────── --}}
            <nav class="hidden lg:flex items-center gap-8" aria-label="Main navigation">
                <a href="{{ route('products.index') }}"
                   class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    {{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}
                </a>
                <a href="{{ route('about') }}"
                   class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    {{ app()->getLocale() === 'ar' ? 'قصتنا' : 'Our Story' }}
                </a>
                <a href="{{ route('quiz') }}"
                   class="nav-link {{ request()->routeIs('quiz') ? 'active' : '' }}">
                    {{ app()->getLocale() === 'ar' ? 'اختبار البشرة' : 'Skin Quiz' }}
                </a>
                <a href="{{ route('contact') }}"
                   class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                    {{ app()->getLocale() === 'ar' ? 'تواصل' : 'Contact' }}
                </a>
            </nav>

            {{-- ── Right Side Actions (tight gaps on xs so hamburger stays on-screen) ── --}}
            <div class="flex shrink-0 items-center gap-1 sm:gap-3 md:gap-4">

                {{-- Language Toggle --}}
                <div class="lang-toggle shrink-0" role="group" aria-label="Language selector">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="lang-toggle-btn !px-2.5 !py-1 text-[10px] sm:!px-4 sm:!py-1.5 sm:text-xs {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                       aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}">
                        EN
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}"
                       class="lang-toggle-btn !px-2.5 !py-1 text-[10px] sm:!px-4 sm:!py-1.5 sm:text-xs {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                       aria-pressed="{{ app()->getLocale() === 'ar' ? 'true' : 'false' }}">
                        AR
                    </a>
                </div>

                {{-- Search (icon + panel) --}}
                <div class="relative shrink-0">
                    <button
                        type="button"
                        class="btn-icon shrink-0"
                        @click="searchOpen = !searchOpen; if (searchOpen) { mobileOpen = false; $nextTick(() => $refs.navSearchInput?.focus()) }"
                        :aria-expanded="searchOpen.toString()"
                        aria-controls="nav-search-panel"
                        aria-label="{{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </button>
                    <div
                        id="nav-search-panel"
                        x-show="searchOpen"
                        x-transition
                        x-cloak
                        @click.outside="searchOpen = false"
                        class="fixed z-[1000] p-4 bg-ferro-obsidian border border-ferro-carbon shadow-2xl
                               start-4 end-4 top-20 w-auto
                               lg:absolute lg:top-full lg:inset-auto lg:end-0 lg:start-auto lg:w-80 lg:mt-2"
                    >
                        <form method="GET" action="{{ route('products.index') }}" class="flex gap-2" role="search" @submit="searchOpen = false">
                            <label for="nav-search-q" class="sr-only">{{ app()->getLocale() === 'ar' ? 'بحث في المنتجات' : 'Search products' }}</label>
                            <input
                                id="nav-search-q"
                                x-ref="navSearchInput"
                                type="search"
                                name="q"
                                value="{{ request()->routeIs('products.*') ? request('q') : '' }}"
                                maxlength="120"
                                placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث بالاسم أو رمز المنتج…' : 'Search by name or SKU…' }}"
                                class="input-ferro flex-1 min-w-0 !py-2.5 text-sm"
                                autocomplete="off"
                            >
                            <button type="submit" class="btn-primary clip-luxury-sm shrink-0 px-4 text-sm">
                                {{ app()->getLocale() === 'ar' ? 'بحث' : 'Go' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Cart (mobile: icon + counter; desktop: same) --}}
                <a href="{{ route('cart') }}"
                   class="btn-icon relative flex shrink-0"
                   aria-label="{{ app()->getLocale() === 'ar' ? 'عربة التسوق' : 'Cart' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.853-7.16a4.5 4.5 0 00-4.244-5.756H5.25a4.5 4.5 0 00-4.244 5.756l1.107 4.15A3 3 0 007.5 14.25z"/>
                    </svg>
                    <span data-ferro-cart-badge
                          class="absolute -top-1 -end-1 min-w-4 h-4 px-0.5 rounded-full bg-ferro-orange text-white text-[10px] font-bold flex items-center justify-center hidden origin-center"
                          aria-live="polite">0</span>
                </a>

                {{-- Auth links (desktop) --}}
                <div class="hidden lg:block">
                    @auth
                        <a href="{{ route('account') }}" class="btn-ghost text-xs">
                            {{ app()->getLocale() === 'ar' ? 'حسابي' : 'Account' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-secondary px-5 py-2.5 text-xs">
                            {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
                        </a>
                    @endauth
                </div>

                {{-- Mobile hamburger (z above backdrop; shrink-0 so row overflow cannot clip it) --}}
                <button
                    type="button"
                    class="lg:hidden btn-icon relative z-[20] shrink-0"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-controls="mobile-menu"
                    :aria-label="mobileOpen ? 'Close menu' : 'Open menu'"
                >
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Mobile Drawer ─────────────────────────────────────────────── --}}
    <div
        id="mobile-menu"
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden relative z-[20] bg-ferro-black/95 backdrop-blur-xl border-t border-ferro-carbon"
        :aria-hidden="(!mobileOpen).toString()"
    >
        <nav class="container-ferro py-8 flex flex-col gap-6">
            <a href="{{ route('products.index') }}" @click="mobileOpen=false" class="nav-link text-lg">
                {{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}
            </a>
            <a href="{{ route('about') }}"          @click="mobileOpen=false" class="nav-link text-lg">
                {{ app()->getLocale() === 'ar' ? 'قصتنا' : 'Our Story' }}
            </a>
            <a href="{{ route('quiz') }}"            @click="mobileOpen=false" class="nav-link text-lg">
                {{ app()->getLocale() === 'ar' ? 'اختبار البشرة' : 'Skin Quiz' }}
            </a>
            <a href="{{ route('contact') }}"         @click="mobileOpen=false" class="nav-link text-lg">
                {{ app()->getLocale() === 'ar' ? 'تواصل' : 'Contact' }}
            </a>
            <div class="divider"></div>
            @auth
                <a href="{{ route('account') }}" @click="mobileOpen=false" class="nav-link">
                    {{ app()->getLocale() === 'ar' ? 'حسابي' : 'My Account' }}
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary w-full text-center">
                    {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
                </a>
            @endauth
        </nav>
    </div>
</header>

{{-- Nav scroll: slightly stronger bar after scroll (optional polish) --}}
<script>
(function () {
    const nav = document.getElementById('site-nav');
    if (!nav) return;
    const onScroll = () => {
        nav.classList.toggle('is-scrolled', window.scrollY > 20);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
})();
</script>
