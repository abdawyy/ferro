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
    style="background: transparent;"
    x-data="{ mobileOpen: false, searchOpen: false }"
    @keydown.escape.window="searchOpen = false; mobileOpen = false"
>
    {{-- Backdrop blur applied via JS .is-scrolled class --}}
    <div id="nav-backdrop" class="absolute inset-0 transition-all duration-300 opacity-0 pointer-events-none
         bg-ferro-black/90 backdrop-blur-xl border-b border-ferro-carbon/40"
         aria-hidden="true"></div>

    <div class="container-ferro relative">
        <div class="flex items-center justify-between h-[72px]">

            {{-- ── Logotype ──────────────────────────────────────────── --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" aria-label="FERRO Home">
                <svg class="w-8 h-8 text-ferro-orange group-hover:scale-110 transition-transform duration-300"
                     viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z" fill="currentColor"/>
                    {{-- Iron "F" letterform --}}
                </svg>
                <span class="font-display text-2xl font-semibold tracking-[0.2em] text-ferro-white uppercase">
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

            {{-- ── Right Side Actions ────────────────────────────────── --}}
            <div class="flex items-center gap-4">

                {{-- Language Toggle --}}
                <div class="lang-toggle" role="group" aria-label="Language selector">
                    <a href="{{ route('lang.switch', 'en') }}"
                       class="lang-toggle-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                       aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}">
                        EN
                    </a>
                    <a href="{{ route('lang.switch', 'ar') }}"
                       class="lang-toggle-btn {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                       aria-pressed="{{ app()->getLocale() === 'ar' ? 'true' : 'false' }}">
                        AR
                    </a>
                </div>

                {{-- Search (icon + panel) --}}
                <div class="relative">
                    <button
                        type="button"
                        class="btn-icon"
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

                {{-- Cart icon (desktop) --}}
                <a href="{{ route('cart') }}"
                   class="btn-icon hidden lg:flex relative"
                   aria-label="{{ app()->getLocale() === 'ar' ? 'عربة التسوق' : 'Cart' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.853-7.16a4.5 4.5 0 00-4.244-5.756H5.25a4.5 4.5 0 00-4.244 5.756l1.107 4.15A3 3 0 007.5 14.25z"/>
                    </svg>
                    {{-- Cart badge --}}
                    <span id="cart-badge"
                          class="absolute -top-1 -end-1 w-4 h-4 rounded-full bg-ferro-orange text-white text-[10px] font-bold flex items-center justify-center hidden"
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

                {{-- Mobile hamburger --}}
                <button
                    class="lg:hidden btn-icon"
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
        class="lg:hidden bg-ferro-black/95 backdrop-blur-xl border-t border-ferro-carbon"
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

{{-- Nav scroll script --}}
<script>
(function () {
    const nav     = document.getElementById('site-nav');
    const backdrop= document.getElementById('nav-backdrop');
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY > 20;
        nav.style.background = scrolled ? 'transparent' : 'transparent';
        backdrop.style.opacity = scrolled ? '1' : '0';
    }, { passive: true });
})();
</script>
