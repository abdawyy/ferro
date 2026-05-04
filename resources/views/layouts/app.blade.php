<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ferro-app-url" content="{{ url('/') }}">
    <meta name="ferro-cart-add-url" content="{{ route('api.cart.add') }}">

    {{-- ── SEO Meta ──────────────────────────────────────────────────── --}}
    <title>@yield('seo_title', 'FERRO — Forged from Iron, Polished by Luxury')</title>
    <meta name="description" content="@yield('seo_description', 'Premium natural grooming essentials engineered for the high-performance man. Nature-powered. Luxury-refined. Built for resilience.')">
    <meta name="keywords"    content="@yield('seo_keywords', 'mens grooming, luxury skincare, athlete skincare, natural grooming, FERRO')">
    <link  rel="canonical"   href="@yield('canonical', url()->current())">

    {{-- hreflang for bilingual SEO --}}
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="ar" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    {{-- ── Open Graph ────────────────────────────────────────────────── --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:title"       content="@yield('og_title', 'FERRO — Forged from Iron, Polished by Luxury')">
    <meta property="og:description" content="@yield('og_description', 'Premium natural grooming essentials engineered for the high-performance man.')">
    <meta property="og:image"       content="@yield('og_image', asset('images/ferro-og-default.jpg'))">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:site_name"   content="FERRO">
    <meta property="og:locale"      content="{{ app()->getLocale() === 'ar' ? 'ar_AE' : 'en_US' }}">
    <meta property="og:locale:alternate" content="{{ app()->getLocale() === 'ar' ? 'en_US' : 'ar_AE' }}">

    {{-- ── Twitter Card ───────────────────────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', 'FERRO')">
    <meta name="twitter:description" content="@yield('og_description', 'Premium mens grooming.')">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/ferro-og-default.jpg'))">
    <meta name="twitter:site"        content="@ferrogrooming">

    {{-- ── Schema.org — Organization (sitewide) ─────────────────────── --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Organization",
        "name": "FERRO",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/ferro-logo.svg') }}",
        "description": "Premium nature-powered grooming essentials engineered for the high-performance man.",
        "sameAs": [
            "https://instagram.com/ferrogrooming",
            "https://tiktok.com/@ferrogrooming"
        ],
        "contactPoint": {
            "@@type": "ContactPoint",
            "contactType": "customer service",
            "email": "support@ferro.com"
        }
    }
    </script>

    {{-- ── Page-specific Schema.org ──────────────────────────────────── --}}
    @yield('schema_org')

    {{-- ── Preconnect / Performance ───────────────────────────────────── --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preload" as="image" href="{{ config('ferro.page_backgrounds.heroes.home', asset('images/hero-bg.jpg')) }}">

    {{-- ── CDN: Google Fonts ──────────────────────────────────────────── --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Inter:wght@300;400;500;600;700&family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- ── CDN: Alpine.js (fallback if bundled version fails) ────────── --}}
    {{-- Already bundled via Vite — CDN version omitted to avoid double-load --}}

    {{-- ── CDN: Swiper (carousel / slider) ───────────────────────────── --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    {{-- ── Favicon ─────────────────────────────────────────────────────── --}}
    <link rel="icon"             type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <meta name="theme-color" content="#0A0A0A">

    {{-- ── Vite (hashed build filenames from manifest) ─────────────────── --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- ── Page-level head additions ─────────────────────────────────── --}}
    @stack('head')
</head>
<body class="relative bg-ferro-black text-ferro-off-white antialiased">

    @include('partials.page-backdrop')

    @include('partials.nav')

    <main id="main-content" class="relative z-10">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
