@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('about');
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')

{{-- ── Page Hero ──────────────────────────────────────────────────────── --}}
<section class="relative min-h-[60vh] flex items-end overflow-hidden pt-[72px]">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset(config('ferro.page_backgrounds.heroes.about')) }}" alt=""
             class="ferro-brand-photo w-full h-full object-cover object-center" aria-hidden="true" loading="eager" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-ferro-black via-ferro-black/50 to-ferro-black/10"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_30%,rgba(232,80,10,0.1)_0%,transparent_60%)]"></div>
    </div>
    <div class="container-ferro relative z-10 pb-20">
        <div class="max-w-2xl {{ $isAr ? 'text-right ml-auto' : '' }}">
            <span class="eyebrow">{{ $isAr ? 'قصتنا' : 'Our Story' }}</span>
            <h1 class="font-display text-display-xl text-ferro-white">
                {{ $isAr ? 'وُلدنا لنسد الفراغ' : 'Born to Fill the Void' }}
            </h1>
        </div>
    </div>
</section>

{{-- ── Mission Statement ───────────────────────────────────────────────── --}}
<section class="section-pad bg-ferro-obsidian">
    <div class="container-ferro">
        <div class="max-w-3xl mx-auto text-center reveal">
            <svg class="w-8 h-8 text-ferro-orange mx-auto mb-8" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
            </svg>
            <h2 class="font-display text-display-lg text-ferro-white mb-6">
                {{ $isAr ? 'مهمتنا' : 'Our Mission' }}
            </h2>
            <p class="text-ferro-silver text-body-lg leading-relaxed">
                {{ $isAr
                    ? 'تزويد الرجال والرياضيين النخبة بمستلزمات عناية طبيعية متميزة تجمع بين الأداء العالي والاسترداد الفاخر.'
                    : 'To provide men and elite athletes with premium, nature-powered grooming essentials that bridge the gap between high-performance recovery and luxury self-care.' }}
            </p>
        </div>
    </div>
</section>

{{-- ── Brand Origin Story ──────────────────────────────────────────────── --}}
<section class="section-pad">
    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 xl:gap-24 items-center">
            <div class="{{ $isAr ? 'order-2 text-right' : 'order-1' }} reveal">
                <span class="eyebrow">{{ $isAr ? 'الأصل' : 'The Origin' }}</span>
                <h2 class="font-display text-display-lg text-ferro-white mb-6">
                    {{ $isAr ? 'من اللاتينية إلى الملعب' : 'From Latin to the Arena' }}
                </h2>
                <div class="space-y-5 text-ferro-silver text-body-sm leading-relaxed">
                    @if($isAr)
                        <p>في عالم تملؤه منتجات الجمال للمرأة، أدركنا أن الرجل عالي الأداء تُرك خلف الركب. لم يكن هناك حل يجمع بين فاعلية المكونات الطبيعية ورقي الرفاهية الحقيقية.</p>
                        <p>فيرو — مستوحى من الكلمة اللاتينية للحديد — وُلد ليغيّر هذا. اسمنا يعكس قوة ومرونة الرياضي العصري: صلب كالحديد، لكن مصقول كالفخامة.</p>
                        <p>ندمج القوة الخام للمكونات الطبيعية مع الرقي المصقول لدار أزياء فاخرة. لا نصنع مستحضرات تجميل؛ نحن نصنع أدوات أساسية للرجال الذين يتخطون حدودهم.</p>
                    @else
                        <p>In a world of mass-produced grooming products catered to women, we realized the high-performance man was consistently left behind. There was no solution that combined the efficacy of natural ingredients with true luxury refinement.</p>
                        <p>FERRO — derived from the Latin word for Iron — was born to change that. Our name reflects the strength and resilience of the modern athlete: tough as iron, yet polished as luxury.</p>
                        <p>We merge the raw power of natural ingredients with the refined sophistication of a luxury house. We don't just create skincare; we forge essential tools for men who push their limits.</p>
                    @endif
                </div>
            </div>
            <div class="{{ $isAr ? 'order-1' : 'order-2' }} reveal">
                <div class="relative aspect-[4/5] overflow-hidden" style="border-radius: 2px;">
                    <img src="{{ asset(config('ferro.page_backgrounds.heroes.about_story')) }}"
                         alt="{{ $isAr ? 'قصة فيرو' : 'The FERRO story' }}"
                         class="ferro-brand-photo w-full h-full object-cover" loading="lazy" width="900" height="1125" decoding="async">
                    <div class="absolute inset-0 bg-gradient-to-t from-ferro-black/40 to-transparent"></div>
                </div>
                {{-- Orange accent --}}
                <div class="absolute -bottom-4 {{ $isAr ? '-start-4' : '-end-4' }} w-24 h-24 bg-ferro-orange/10 border border-ferro-orange/20" style="border-radius: 2px;" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>

{{-- ── Core Values ─────────────────────────────────────────────────────── --}}
<section class="section-pad bg-ferro-obsidian" aria-labelledby="values-heading">
    <div class="container-ferro">
        <div class="text-center mb-16 reveal">
            <span class="eyebrow">{{ $isAr ? 'مبادئنا' : 'Our Pillars' }}</span>
            <h2 id="values-heading" class="font-display text-display-lg text-ferro-white">
                {{ $isAr ? 'ما يحرّكنا' : 'What Drives Us' }}
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 reveal-stagger">
            @foreach([
                [
                    'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    'title_en' => 'Natural Power',
                    'title_ar' => 'القوة الطبيعية',
                    'desc_en'  => 'Every formula is built on nature\'s most potent, scientifically-validated ingredients. No filler. No compromise.',
                    'desc_ar'  => 'كل تركيبة مبنية على أقوى مكونات الطبيعة المثبتة علمياً. لا حشو. لا تهاون.',
                ],
                [
                    'icon' => 'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
                    'title_en' => 'Luxury Refined',
                    'title_ar' => 'الرقي المصقول',
                    'desc_en'  => 'From packaging to texture to scent — every detail is designed with the precision of a luxury house.',
                    'desc_ar'  => 'من التعبئة إلى الملمس إلى الرائحة — كل تفصيل مصمم بدقة دار الأزياء الفاخرة.',
                ],
                [
                    'icon' => 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z',
                    'title_en' => 'Built for Performance',
                    'title_ar' => 'مصنوع للأداء',
                    'desc_en'  => 'Engineered for the man who trains hard, recovers harder, and never stops performing at the highest level.',
                    'desc_ar'  => 'مصمم للرجل الذي يتدرب بشدة، يتعافى بشدة، ولا يتوقف عن الأداء عند أعلى المستويات.',
                ],
            ] as $value)
                <div class="card-glass p-8 {{ $isAr ? 'text-right' : '' }}">
                    <div class="w-12 h-12 bg-ferro-orange/10 border border-ferro-orange/20 flex items-center justify-center mb-6 {{ $isAr ? 'mr-auto' : '' }}" style="border-radius: 2px;">
                        <svg class="w-5 h-5 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $value['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 class="font-display text-xl text-ferro-white mb-3">
                        {{ $isAr ? $value['title_ar'] : $value['title_en'] }}
                    </h3>
                    <p class="text-ferro-ash text-body-sm leading-relaxed">
                        {{ $isAr ? $value['desc_ar'] : $value['desc_en'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Vision ──────────────────────────────────────────────────────────── --}}
<section class="section-pad">
    <div class="container-ferro">
        <div class="relative overflow-hidden bg-ferro-obsidian border border-ferro-carbon p-12 md:p-20 text-center reveal"
             style="border-radius: 2px;">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_50%,rgba(232,80,10,0.06)_0%,transparent_70%)]" aria-hidden="true"></div>
            <div class="relative z-10">
                <span class="eyebrow">{{ $isAr ? 'رؤيتنا' : 'Our Vision' }}</span>
                <blockquote class="font-display text-display-lg text-ferro-white max-w-3xl mx-auto">
                    "{{ $isAr
                        ? 'أن نكون الوجهة الفاخرة الأولى للرياضي العصري، نضع المعيار العالمي للعناية الطبيعية الراقية المبنية على المرونة.'
                        : 'To be the ultimate luxury destination for the modern athlete, setting the global standard for natural, high-end skincare built for resilience.' }}"
                </blockquote>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ─────────────────────────────────────────────────────────────── --}}
<section class="section-pad bg-ferro-obsidian">
    <div class="container-ferro text-center reveal">
        <h2 class="font-display text-display-lg text-ferro-white mb-6">
            {{ $isAr ? 'انضم إلى الحركة' : 'Join the Movement' }}
        </h2>
        <p class="text-ferro-silver text-body-lg mb-10 max-w-lg mx-auto">
            {{ $isAr
                ? 'اكتشف الترسانة وابدأ رحلتك نحو عناية لا تهادن.'
                : 'Discover the arsenal and begin your journey toward uncompromising grooming.' }}
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('products.index') }}" class="btn-primary clip-luxury-md">
                {{ $isAr ? 'استكشف المتجر' : 'Explore the Arsenal' }}
            </a>
            <a href="{{ route('quiz') }}" class="btn-secondary clip-luxury-md">
                {{ $isAr ? 'اكتشف روتينك' : 'Find Your Routine' }}
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function() {
    const io = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
        { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
    );
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));
})();
</script>
@endpush
