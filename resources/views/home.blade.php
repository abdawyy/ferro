@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

{{-- ── SEO Meta ──────────────────────────────────────────────────────────── --}}
@php
    $seoTitle       = $isAr ? 'فيرو — مصنوع من الحديد، مصقول بالرفاهية | عناية الرجل الفاخرة' : 'FERRO — Forged from Iron, Polished by Luxury | Premium Men\'s Grooming';
    $seoDescription = $isAr ? 'منتجات عناية فاخرة للرجال والرياضيين النخبة. طبيعية. قوية. لا تهادن.' : 'Premium nature-powered grooming essentials engineered for the high-performance man and elite athlete. Natural. Powerful. Uncompromising.';
@endphp
@section('seo_title', $seoTitle)
@section('seo_description', $seoDescription)
@section('og_title', 'FERRO — Forged from Iron, Polished by Luxury')
@section('og_description', 'Premium natural grooming engineered for the elite athlete.')
@section('og_type', 'website')

@section('schema_org')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "FERRO",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@@type": "SearchAction",
        "target": "{{ url('/shop') }}?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
@endsection

@section('content')

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 1 — HERO
     Full-viewport cinematic hero. "Iron" texture + radial orange glow.
     Tagline communicates brand duality: raw power × refined luxury.
──────────────────────────────────────────────────────────────────────────── --}}
<section
    class="relative min-h-screen flex items-center overflow-hidden"
    aria-labelledby="hero-headline"
>
    {{-- Background image with parallax --}}
    <div class="absolute inset-0 z-0" id="hero-bg">
        <img
            src="{{ asset(config('ferro.page_backgrounds.heroes.home')) }}"
            alt=""
            class="ferro-brand-photo w-full h-full object-cover object-right max-md:object-center"
            aria-hidden="true"
            loading="eager"
            fetchpriority="high"
            decoding="sync"
        >
        {{-- Multi-layer overlay: dark base + hero gradient (kept lighter so source art stays sharp) --}}
        <div class="absolute inset-0 bg-gradient-to-b from-ferro-black/22 via-ferro-black/48 to-ferro-black"></div>
        {{-- Forge orange ambient glow --}}
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_40%,rgba(232,80,10,0.10)_0%,transparent_58%)]"></div>
    </div>

    {{-- Hero Content --}}
    <div class="container-ferro relative z-10 pt-[72px]">
        <div class="max-w-3xl {{ $isAr ? 'text-right' : 'text-left' }}">

            {{-- Eyebrow --}}
            <div class="flex items-center gap-3 mb-8 {{ $isAr ? 'justify-end flex-row-reverse' : '' }}">
                <div class="w-8 h-px bg-ferro-orange"></div>
                <span class="eyebrow mb-0">
                    {{ $isAr ? 'الجيل القادم من العناية الفاخرة' : 'Next-Generation Luxury Grooming' }}
                </span>
            </div>

            {{-- Main headline --}}
            <h1
                id="hero-headline"
                class="font-display text-display-2xl text-ferro-white mb-6 leading-none animate-fade-up fill-both"
            >
                @if($isAr)
                    <span class="block">مصنوع</span>
                    <span class="block text-gradient-orange">من الحديد</span>
                    <span class="block">مصقول بالرفاهية</span>
                @else
                    <span class="block">Forged</span>
                    <span class="block text-gradient-orange">from Iron.</span>
                    <span class="block">Polished by Luxury.</span>
                @endif
            </h1>

            {{-- Sub-headline --}}
            <p class="text-ferro-silver text-body-lg max-w-xl mb-10 animate-fade-up fill-both delay-200">
                {{ $isAr
                    ? 'في عالم يملؤه الغياب، جاء فيرو ليسد الفراغ. عناية مدعومة بالطبيعة، مصممة للرجل الذي لا يتوقف.'
                    : 'In a world of absence, FERRO was born to fill the void. Nature-powered. Built for the man who never stops.' }}
            </p>

            {{-- CTA Group --}}
            <div class="flex flex-wrap items-center gap-4 animate-fade-up fill-both delay-400 {{ $isAr ? 'justify-end' : '' }}">
                <a href="{{ route('products.index') }}" class="btn-primary clip-luxury-md">
                    {{ $isAr ? 'استكشف المتجر' : 'Explore the Arsenal' }}
                    <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
                <a href="{{ route('quiz') }}" class="btn-secondary clip-luxury-md">
                    {{ $isAr ? 'اكتشف نوع بشرتك' : 'Find Your Formula' }}
                </a>
            </div>

            {{-- Waitlist teaser --}}
            <div class="mt-10 animate-fade-up fill-both delay-500">
                <button
                    onclick="document.getElementById('waitlist-section').scrollIntoView({behavior:'smooth'})"
                    class="flex items-center gap-2 text-ferro-orange text-body-sm font-medium group {{ $isAr ? 'flex-row-reverse' : '' }}"
                >
                    <span class="w-2 h-2 rounded-full bg-ferro-orange animate-pulse"></span>
                    {{ $isAr ? 'القائمة مفتوحة — سجّل الآن للوصول المبكر' : 'Waitlist Open — Register for Early Access' }}
                    <svg class="w-4 h-4 group-hover:translate-y-1 transition-transform {{ $isAr ? 'rotate-90' : '-rotate-90' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-10 {{ $isAr ? 'left-12' : 'right-12' }} hidden lg:flex flex-col items-center gap-3 animate-fade-in fill-both delay-600">
            <span class="text-ferro-ash text-label tracking-[0.2em] uppercase"
                  style="writing-mode: vertical-rl; text-orientation: mixed;">
                {{ $isAr ? 'مرر للأسفل' : 'Scroll' }}
            </span>
            <div class="w-px h-16 bg-gradient-to-b from-ferro-carbon to-transparent"></div>
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 2 — BRAND STATS
     Reinforces authority. Numbers build trust with performance athletes.
──────────────────────────────────────────────────────────────────────────── --}}
<section class="py-12 bg-ferro-obsidian border-y border-ferro-carbon" aria-label="Brand statistics">
    <div class="container-ferro">
        <div class="grid grid-cols-3 gap-6 reveal-stagger">
            @foreach([
                ['number' => $stats['natural_ingredients'], 'suffix' => '+', 'label' => $isAr ? 'مكوّن طبيعي' : 'Natural Ingredients'],
                ['number' => $stats['elite_athletes'],      'suffix' => '+', 'label' => $isAr ? 'رياضي نخبة' : 'Elite Athletes'],
                ['number' => $stats['countries'],           'suffix' => '',  'label' => $isAr ? 'دولة حول العالم' : 'Countries Worldwide'],
            ] as $stat)
                <div class="text-center {{ $isAr ? 'text-right' : 'text-center' }}">
                    <div class="font-display text-display-xl text-ferro-white mb-1">
                        <span class="text-gradient-orange">{{ $stat['number'] }}</span>{{ $stat['suffix'] }}
                    </div>
                    <div class="text-ferro-ash text-label tracking-widest uppercase">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 3 — FEATURED PRODUCTS
──────────────────────────────────────────────────────────────────────────── --}}
<section class="section-pad" aria-labelledby="featured-heading">
    <div class="container-ferro">

        {{-- Section header --}}
        <div class="flex items-end justify-between mb-12 reveal">
            <div>
                <span class="eyebrow">{{ $isAr ? 'ترسانة فيرو' : 'The Arsenal' }}</span>
                <h2 id="featured-heading" class="font-display text-display-lg text-ferro-white">
                    {{ $isAr ? 'أدوات لا تهادن' : 'Tools That Don\'t Compromise' }}
                </h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn-ghost hidden sm:flex items-center gap-2">
                {{ $isAr ? 'عرض الكل' : 'View All' }}
                <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>

        {{-- Product grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 reveal-stagger">
            @forelse($featuredProducts as $product)
                @include('partials.product-card', ['product' => $product, 'showQuickAdd' => true])
            @empty
                {{-- Coming soon placeholder cards --}}
                @foreach(range(1, 4) as $i)
                    @include('partials.product-card-skeleton')
                @endforeach
            @endforelse
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 4 — BRAND STORY (Storytelling block)
──────────────────────────────────────────────────────────────────────────── --}}
<section class="section-pad bg-ferro-obsidian" aria-labelledby="story-heading">
    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-center">

            {{-- Image side --}}
            <div class="relative reveal {{ $isAr ? 'order-2' : 'order-1' }}">
                <div class="relative aspect-[4/5] overflow-hidden" style="border-radius: 2px;">
                    <img
                        src="{{ asset(config('ferro.page_backgrounds.heroes.brand_story')) }}"
                        alt="{{ $isAr ? 'صورة تجسّد قوة فيرو' : 'FERRO — Power and refinement' }}"
                        class="ferro-brand-photo w-full h-full object-cover"
                        loading="lazy"
                        width="600" height="750"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-ferro-black/60 to-transparent"></div>
                </div>
                {{-- Floating accent --}}
                <div class="absolute -bottom-6 {{ $isAr ? '-start-6' : '-end-6' }} w-32 h-32 bg-ferro-orange/10 border border-ferro-orange/20"
                     style="border-radius: 2px;" aria-hidden="true"></div>
                <div class="absolute -top-4 {{ $isAr ? '-end-4' : '-start-4' }} w-20 h-20 bg-ferro-carbon border border-ferro-carbon"
                     style="border-radius: 2px;" aria-hidden="true">
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor">
                            <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Text side --}}
            <div class="{{ $isAr ? 'order-1 text-right' : 'order-2' }} reveal">
                <span class="eyebrow">{{ $isAr ? 'قصتنا' : 'Our Story' }}</span>
                <h2 id="story-heading" class="font-display text-display-lg text-ferro-white mb-6">
                    {{ $isAr ? 'وُلدنا لنسد الفراغ' : 'Born to Fill the Void' }}
                </h2>
                <div class="space-y-4 text-ferro-silver text-body-sm leading-relaxed">
                    @if($isAr)
                        <p>في عالم تملؤه منتجات العناية للمرأة، كان الرجل عالي الأداء يُترك خلف الركب. فيرو وُلد ليغيّر هذا.</p>
                        <p>مستوحى من الكلمة اللاتينية للحديد، يعكس اسمنا قوة ومرونة الرياضي العصري. ندمج قوة المكونات الطبيعية مع رقي دار أزياء فاخرة.</p>
                        <p>لا نصنع مستحضرات تجميل فحسب؛ نحن نصنع أدوات أساسية للرجال الذين يتخطون حدودهم.</p>
                    @else
                        <p>Derived from the Latin word for Iron, our name reflects the strength and resilience of the modern athlete. We realized the high-performance man was left behind.</p>
                        <p>FERRO changes that by merging the raw power of natural ingredients with the refined sophistication of a luxury house.</p>
                        <p>We don't just create skincare. We forge essential tools for men who push their limits — from the intensity of the gym to the demands of a high-end lifestyle.</p>
                    @endif
                </div>
                <div class="mt-8 flex flex-wrap gap-4 {{ $isAr ? 'justify-end' : '' }}">
                    <a href="{{ route('about') }}" class="btn-secondary clip-luxury-sm">
                        {{ $isAr ? 'اقرأ القصة كاملة' : 'Read Full Story' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 5 — COMING SOON PRODUCTS
──────────────────────────────────────────────────────────────────────────── --}}
@if($comingSoonProducts->count())
<section class="section-pad" aria-labelledby="coming-soon-heading">
    <div class="container-ferro">
        <div class="text-center mb-12 reveal">
            <span class="eyebrow">{{ $isAr ? 'قريباً' : 'Coming Soon' }}</span>
            <h2 id="coming-soon-heading" class="font-display text-display-lg text-ferro-white">
                {{ $isAr ? 'الترسانة تتوسع' : 'The Arsenal Expands' }}
            </h2>
            <p class="text-ferro-silver text-body-sm mt-3 max-w-md mx-auto">
                {{ $isAr ? 'منتجات جديدة في الطريق. كن أول من يحصل عليها.' : 'New weapons are being forged. Be the first to claim them.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 reveal-stagger">
            @foreach($comingSoonProducts as $product)
                @include('partials.product-card', ['product' => $product, 'showComingSoon' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 6 — PREMIUM WAITLIST CAPTURE
     Pre-launch CTA. Full-width dramatic section.
──────────────────────────────────────────────────────────────────────────── --}}
<section
    id="waitlist-section"
    class="relative section-pad overflow-hidden"
    aria-labelledby="waitlist-heading"
>
    {{-- Background forge glow --}}
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_50%,rgba(232,80,10,0.08)_0%,transparent_70%)]" aria-hidden="true"></div>
    <div class="absolute inset-0 border-y border-ferro-carbon/30" aria-hidden="true"></div>

    <div class="container-ferro relative z-10">
        <div class="max-w-2xl mx-auto text-center reveal">

            {{-- Decorative element --}}
            <div class="flex items-center justify-center gap-4 mb-8">
                <div class="w-16 h-px bg-ferro-carbon"></div>
                <svg class="w-6 h-6 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                    <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                </svg>
                <div class="w-16 h-px bg-ferro-carbon"></div>
            </div>

            <span class="eyebrow">{{ $isAr ? 'وصول حصري' : 'Exclusive Access' }}</span>
            <h2 id="waitlist-heading" class="font-display text-display-xl text-ferro-white mb-4">
                {{ $isAr ? 'كن أول من يحمل فيرو' : 'Be First to Carry FERRO' }}
            </h2>
            <p class="text-ferro-silver text-body-lg mb-8">
                {{ $isAr
                    ? 'انضم إلى قائمة الانتظار للحصول على أسعار تأسيسية حصرية وشحن مجاني على طلبك الأول.'
                    : 'Join the waitlist for exclusive founding pricing and free shipping on your first order.' }}
            </p>

            {{-- Premium waitlist form --}}
            <div class="waitlist-card max-w-lg mx-auto">
                @include('partials.waitlist-mini-form', ['formId' => 'hero-cta'])
            </div>

            {{-- Social proof --}}
            <p class="text-ferro-ash text-xs mt-6">
                {{ $isAr ? 'انضم إلى أكثر من ٢٠٠٠ رجل في القائمة' : 'Join 2,000+ men already on the list' }}
            </p>
        </div>
    </div>
</section>

{{-- ────────────────────────────────────────────────────────────────────────
     SECTION 7 — SKIN QUIZ TEASER (Advanced Feature #2)
──────────────────────────────────────────────────────────────────────────── --}}
<section class="section-pad bg-ferro-obsidian" aria-labelledby="quiz-heading">
    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center reveal">
            <div class="{{ $isAr ? 'text-right' : '' }}">
                <span class="eyebrow">{{ $isAr ? 'خصّص روتينك' : 'Personalize Your Routine' }}</span>
                <h2 id="quiz-heading" class="font-display text-display-lg text-ferro-white mb-5">
                    {{ $isAr ? 'ما هو ملف بشرتك؟' : "What's Your Skin Profile?" }}
                </h2>
                <p class="text-ferro-silver text-body-sm mb-8">
                    {{ $isAr
                        ? 'أجب على ٥ أسئلة واحصل على روتين فيرو المثالي لنمط حياتك.'
                        : 'Answer 5 questions and receive your perfect FERRO regimen tailored to your lifestyle.' }}
                </p>
                <a href="{{ route('quiz') }}" class="btn-primary clip-luxury-md inline-flex">
                    {{ $isAr ? 'ابدأ الاختبار المجاني' : 'Take the Free Quiz' }}
                </a>
            </div>
            {{-- Quiz visual --}}
            <div class="relative">
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['icon' => '⚡', 'label' => $isAr ? 'الرياضي النخبة' : 'Elite Athlete'],
                        ['icon' => '🏙️', 'label' => $isAr ? 'رجل المدينة' : 'Urban Executive'],
                        ['icon' => '🌿', 'label' => $isAr ? 'الطبيعي النشط' : 'Active Naturalist'],
                        ['icon' => '💎', 'label' => $isAr ? 'المتميز' : 'The Refined'],
                    ] as $profile)
                        <div class="card-glass p-6 text-center hover:border-ferro-orange/40 transition-all duration-300 cursor-pointer group">
                            <div class="text-3xl mb-2">{{ $profile['icon'] }}</div>
                            <div class="text-ferro-silver text-body-sm font-medium group-hover:text-ferro-white transition-colors">
                                {{ $profile['label'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
@verbatim
<script>
// Parallax on hero background
(function() {
    const heroBg = document.getElementById('hero-bg');
    if (!heroBg || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    window.addEventListener('scroll', () => {
        const y = window.scrollY;
        heroBg.style.transform = `translateY(${y * 0.3}px)`;
    }, { passive: true });
})();

// IntersectionObserver for reveal animations
(function() {
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); } });
    }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));
})();
</script>
@endverbatim
@endpush
