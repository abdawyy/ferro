@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('quiz');
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')

<div class="relative min-h-screen flex flex-col pt-[72px] pb-16 sm:pb-20" x-data="ferroQuiz()">

    <div class="absolute inset-0 z-0 bg-ferro-black">
        <img src="{{ asset(config('ferro.page_backgrounds.heroes.quiz')) }}" alt="" class="ferro-brand-photo w-full h-full object-cover object-center opacity-[0.22]" aria-hidden="true" loading="eager" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-b from-ferro-black/60 via-ferro-black/80 to-ferro-black"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_30%,rgba(232,80,10,0.10)_0%,transparent_60%)]" aria-hidden="true"></div>
    </div>

    {{-- Tighter vertical rhythm than section-pad so intro + CTA fit laptop viewports --}}
    <div class="container-ferro relative z-10 w-full flex-1 py-6 sm:py-8 md:py-10 lg:py-12">

        <div x-show="step === 0" x-transition class="quiz-intro-step max-w-5xl mx-auto text-center px-1 sm:px-0">
            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-ferro-orange mx-auto mb-4 sm:mb-5" viewBox="0 0 32 32" fill="currentColor" aria-hidden="true">
                <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
            </svg>
            <span class="eyebrow">{{ $isAr ? 'خصّص روتينك' : 'Personalize Your Routine' }}</span>
            <h1 class="font-display text-ferro-white mb-3 sm:mb-4 text-[clamp(1.75rem,4vw+1rem,3.5rem)] leading-tight tracking-tight">
                {{ $isAr ? 'ما هو ملف بشرتك؟' : "What's Your Skin Profile?" }}
            </h1>
            <p class="text-ferro-silver text-body-lg mb-4 sm:mb-5 max-w-2xl mx-auto leading-relaxed">
                {{ $isAr
                    ? '٥ أسئلة سريعة للحصول على روتين فيرو المثالي لنمط حياتك وأهدافك.'
                    : '5 quick questions to find your perfect FERRO regimen tailored to your lifestyle and goals.' }}
            </p>
            <p class="text-ferro-ash text-xs uppercase tracking-widest mb-3">{{ $isAr ? 'ما سنغطيه' : 'What we’ll cover' }}</p>
            <div class="quiz-intro-strip w-full max-w-5xl mx-auto text-start">
                <template x-for="(q, qi) in questions" :key="'intro-' + qi">
                    <div class="quiz-intro-card">
                        <img :src="q.step_img" :alt="isAr ? q.step_label_ar : q.step_label_en" width="320" height="160" loading="lazy" class="quiz-intro-card-img">
                        <span class="quiz-intro-card-label" x-text="(qi + 1) + ' — ' + (isAr ? q.step_label_ar : q.step_label_en)"></span>
                    </div>
                </template>
            </div>
            <div class="flex flex-wrap justify-center gap-3 sm:gap-4 mt-6 sm:mt-8">
                <button type="button" @click="step = 1" class="btn-primary clip-luxury-md">
                    {{ $isAr ? 'ابدأ الاختبار المجاني' : 'Start the Free Quiz' }}
                    <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </div>
            <p class="text-ferro-ash text-xs mt-6">{{ $isAr ? 'يستغرق أقل من دقيقة' : 'Takes less than 1 minute' }}</p>
        </div>

        <div x-show="step !== 0" x-cloak class="max-w-6xl mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-start gap-8 lg:gap-12 xl:gap-14">

                <aside class="w-full lg:w-64 shrink-0 lg:sticky lg:top-28" aria-label="{{ $isAr ? 'خطوات الاختبار' : 'Quiz steps' }}">
                    <div class="quiz-track">
                        <p class="quiz-track-title">{{ $isAr ? 'الاختبار' : 'The quiz' }}</p>
                        <ol class="quiz-track-list scrollbar-hide">
                            <template x-for="(q, qi) in questions" :key="'track-' + qi">
                                <li
                                    class="quiz-track-item"
                                    :class="{
                                        'is-done': answers[qi] || step === 'results',
                                        'is-current': step === qi + 1,
                                        'opacity-50': typeof step === 'number' && step < qi + 1 && !answers[qi]
                                    }"
                                >
                                    <img class="quiz-track-thumb" :src="q.step_img" alt="" width="44" height="44" loading="lazy">
                                    <span class="quiz-track-num" x-text="answers[qi] || step === 'results' ? '✓' : (qi + 1)"></span>
                                    <span class="quiz-track-label" x-text="isAr ? q.step_label_ar : q.step_label_en"></span>
                                </li>
                            </template>
                        </ol>
                    </div>
                </aside>

                <div class="flex-1 min-w-0">

        <template x-for="(question, index) in questions" :key="index">
            <div
                x-show="step === index + 1"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
            >
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-ferro-ash text-xs uppercase tracking-widest">
                            {{ $isAr ? 'السؤال' : 'Question' }} <span x-text="index + 1"></span> / <span x-text="questions.length"></span>
                        </span>
                        <span class="text-ferro-orange text-xs font-semibold" x-text="Math.round(((index + 1) / questions.length) * 100) + '%'"></span>
                    </div>
                    <div class="h-1 bg-ferro-carbon overflow-hidden" style="border-radius:2px;">
                        <div class="h-full bg-ferro-orange transition-all duration-500"
                             :style="{ width: ((index + 1) / questions.length * 100) + '%' }"></div>
                    </div>
                </div>

                <div class="mb-6 overflow-hidden rounded-sm border border-white/10 max-w-xl">
                    <img :src="question.step_img" alt="" class="w-full h-36 sm:h-44 object-cover opacity-90" width="720" height="280" loading="lazy">
                </div>

                <h2 class="font-display text-display-lg text-ferro-white mb-8 {{ $isAr ? 'text-right' : '' }}"
                    x-text="isAr ? question.question_ar : question.question_en"
                    aria-live="polite">&#8203;</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="option in question.options" :key="option.value">
                        <button
                            type="button"
                            @click="answer(index, option.value)"
                            class="quiz-option-card group"
                            :class="answers[index] === option.value ? 'is-selected' : ''"
                        >
                            <div class="quiz-option-visual">
                                <img :src="option.img" alt="" width="640" height="360" loading="lazy">
                                <span class="quiz-option-icon" x-text="option.icon"></span>
                            </div>
                            <div class="quiz-option-body {{ $isAr ? 'text-right' : '' }}">
                                <div class="quiz-option-title" x-text="isAr ? option.label_ar : option.label_en"></div>
                                <div class="quiz-option-desc" x-text="isAr ? option.desc_ar : option.desc_en"></div>
                            </div>
                        </button>
                    </template>
                </div>

                <div class="flex items-center justify-between mt-10">
                    <button type="button" @click="step = Math.max(0, step - 1)" x-show="step > 1" class="btn-ghost">
                        <svg class="w-4 h-4 {{ $isAr ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                        {{ $isAr ? 'السابق' : 'Back' }}
                    </button>
                    <button type="button" @click="nextStep(index)" :disabled="!answers[index]"
                        :class="answers[index] ? 'btn-primary' : 'btn-secondary opacity-50 cursor-not-allowed'" class="clip-luxury-sm">
                        <span x-text="index + 1 < questions.length ? '{{ $isAr ? 'التالي' : 'Next' }}' : '{{ $isAr ? 'عرض النتائج' : 'See Results' }}'"></span>
                    </button>
                </div>
            </div>
        </template>

        <div
            x-show="step === 'results'"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="max-w-3xl mx-auto lg:mx-0 text-center lg:text-start"
        >
            <div class="mb-8">
                <div class="w-16 h-16 bg-ferro-orange/10 border border-ferro-orange/30 flex items-center justify-center mx-auto lg:mx-0 mb-6" style="border-radius: 2px;">
                    <svg class="w-7 h-7 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="eyebrow">{{ $isAr ? 'روتينك المخصص' : 'Your Personalized Routine' }}</span>
                <h2 class="font-display text-display-lg text-ferro-white mb-4">
                    {{ $isAr ? 'ملف بشرتك:' : 'Your Skin Profile:' }}
                    @if($isAr)
                    <span class="text-gradient-orange" x-text="skinProfile.label_ar"></span>
                    @else
                    <span class="text-gradient-orange" x-text="skinProfile.label_en"></span>
                    @endif
                </h2>
                @if($isAr)
                <p class="text-ferro-silver text-body-lg" x-text="skinProfile.desc_ar"></p>
                @else
                <p class="text-ferro-silver text-body-lg" x-text="skinProfile.desc_en"></p>
                @endif
            </div>

            <div id="quiz-recommendations" class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10"></div>

            <div class="waitlist-card max-w-lg mx-auto lg:mx-0 p-8">
                <h3 class="font-display text-xl text-ferro-white mb-2">
                    {{ $isAr ? 'احفظ نتائجك' : 'Save Your Results' }}
                </h3>
                <p class="text-ferro-silver text-body-sm mb-6">
                    {{ $isAr
                        ? 'أدخل بريدك لتلقي توصياتك المخصصة وتنبيهات الإطلاق.'
                        : 'Enter your email to receive your personalized routine and launch alerts.' }}
                </p>
                <form @submit.prevent="captureQuizLead" class="flex flex-col gap-3">
                    @csrf
                    <input type="email" x-model="leadEmail" class="input-ferro"
                        placeholder="{{ $isAr ? 'بريدك الإلكتروني' : 'your@email.com' }}" required>
                    <button type="submit" class="btn-primary w-full" :disabled="leadSubmitted">
                        <span x-show="!leadSubmitted">{{ $isAr ? 'أرسل روتيني' : 'Send My Routine' }}</span>
                        <span x-show="leadSubmitted" class="text-green-400">✓ {{ $isAr ? 'تم الإرسال!' : 'Sent!' }}</span>
                    </button>
                </form>
            </div>

            <button type="button" @click="reset()" class="mt-8 text-ferro-ash text-body-sm hover:text-ferro-silver transition-colors underline underline-offset-4">
                {{ $isAr ? 'إعادة الاختبار' : 'Retake Quiz' }}
            </button>
        </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const FERRO_QUIZ_URL = '{{ route('quiz.capture') }}';
const FERRO_LOCALE = @json(app()->getLocale());
const FERRO_QUIZ_IS_AR = @json(app()->getLocale() === 'ar');
function ferroQuizEscapeHtml(text) {
    const d = document.createElement('div');
    d.textContent = text == null ? '' : String(text);
    return d.innerHTML;
}
function ferroQuizRenderProducts(products) {
    const el = document.getElementById('quiz-recommendations');
    if (!el) return;
    if (!products || !products.length) {
        el.innerHTML = '';
        return;
    }
    el.innerHTML = products.map((p) => `
        <a href="${ferroQuizEscapeHtml(p.url)}" class="card-glass p-5 text-start block hover:border-ferro-orange/40 transition-colors no-underline">
            ${p.image ? `<img src="${ferroQuizEscapeHtml(p.image)}" alt="" class="w-full h-36 object-cover mb-4" style="border-radius:2px;">` : ''}
            <div class="text-ferro-white text-body-sm font-medium">${ferroQuizEscapeHtml(p.name)}</div>
            <div class="text-ferro-orange text-xs mt-2">${FERRO_LOCALE === 'ar' ? 'عرض المنتج' : 'View product'} →</div>
        </a>`).join('');
}
</script>
@verbatim
<script>
function ferroQuiz() {
    return {
        step: 0,
        isAr: FERRO_QUIZ_IS_AR,
        answers: {},
        leadEmail: '',
        leadSubmitted: false,
        skinProfile: { label_en: '', label_ar: '', desc_en: '', desc_ar: '' },
        questions: [
            {
                step_label_en: 'Lifestyle',
                step_label_ar: 'نمط الحياة',
                step_img: 'https://picsum.photos/seed/ferrot1life/720/400',
                question_en: 'How would you describe your lifestyle?',
                question_ar: 'كيف تصف نمط حياتك؟',
                options: [
                    { value: 'athlete',   icon: '⚡', img: 'https://picsum.photos/seed/ferro00ath/720/420', label_en: 'Elite Athlete',    label_ar: 'رياضي نخبة',    desc_en: 'Daily training, high sweat output',   desc_ar: 'تدريب يومي مكثف' },
                    { value: 'executive', icon: '🏙️', img: 'https://picsum.photos/seed/ferro00exe/720/420', label_en: 'Urban Executive',  label_ar: 'رجل الأعمال',   desc_en: 'High-stress, city environment',       desc_ar: 'بيئة المدينة والضغط العالي' },
                    { value: 'outdoor',   icon: '🌿', img: 'https://picsum.photos/seed/ferro00out/720/420', label_en: 'Outdoor Enthusiast',label_ar: 'محب الطبيعة',   desc_en: 'Exposed to elements daily',           desc_ar: 'التعرض للعناصر يومياً' },
                    { value: 'refined',   icon: '💎', img: 'https://picsum.photos/seed/ferro00ref/720/420', label_en: 'The Refined Man',   label_ar: 'الرجل الراقي',  desc_en: 'Premium lifestyle, values aesthetics', desc_ar: 'نمط حياة فاخر ومتطور' },
                ]
            },
            {
                step_label_en: 'Skin concern',
                step_label_ar: 'الاهتمام بالبشرة',
                step_img: 'https://picsum.photos/seed/ferrot2care/720/400',
                question_en: "What's your primary skin concern?",
                question_ar: 'ما هو اهتمامك الجلدي الأساسي؟',
                options: [
                    { value: 'recovery',  icon: '🔄', img: 'https://picsum.photos/seed/ferro01rec/720/420', label_en: 'Post-workout Recovery', label_ar: 'التعافي بعد التمرين', desc_en: 'Redness, inflammation, irritation',    desc_ar: 'احمرار، التهاب، تهيج' },
                    { value: 'hydration', icon: '💧', img: 'https://picsum.photos/seed/ferro01hyd/720/420', label_en: 'Deep Hydration',         label_ar: 'ترطيب عميق',          desc_en: 'Dryness and tightness',               desc_ar: 'جفاف وشد' },
                    { value: 'oil',       icon: '✨', img: 'https://picsum.photos/seed/ferro01oil/720/420', label_en: 'Oil Control',             label_ar: 'التحكم في الدهون',    desc_en: 'Shine and breakouts',                 desc_ar: 'لمعان وحبوب' },
                    { value: 'aging',     icon: '⏱️', img: 'https://picsum.photos/seed/ferro01age/720/420', label_en: 'Anti-Aging Defense',      label_ar: 'مكافحة الشيخوخة',    desc_en: 'Fine lines and firmness',             desc_ar: 'خطوط دقيقة وترهل' },
                ]
            },
            {
                step_label_en: 'Routine',
                step_label_ar: 'الروتين',
                step_img: 'https://picsum.photos/seed/ferrot3freq/720/400',
                question_en: 'How often do you currently use skincare?',
                question_ar: 'كم مرة تستخدم العناية بالبشرة حالياً؟',
                options: [
                    { value: 'none',      icon: '0️⃣', img: 'https://picsum.photos/seed/ferro02non/720/420', label_en: 'Never',        label_ar: 'أبداً',           desc_en: 'Starting from zero',       desc_ar: 'أبدأ من الصفر' },
                    { value: 'basic',     icon: '1️⃣', img: 'https://picsum.photos/seed/ferro02bas/720/420', label_en: 'Just basics',  label_ar: 'الأساسيات فقط',  desc_en: 'Cleanser or moisturizer',  desc_ar: 'منظف أو مرطب' },
                    { value: 'routine',   icon: '2️⃣', img: 'https://picsum.photos/seed/ferro02rou/720/420', label_en: 'Daily routine',label_ar: 'روتين يومي',      desc_en: '3-4 steps daily',          desc_ar: '٣-٤ خطوات يومياً' },
                    { value: 'advanced',  icon: '🔬', img: 'https://picsum.photos/seed/ferro02adv/720/420', label_en: 'Advanced',      label_ar: 'متقدم',           desc_en: 'Full regimen with serums',  desc_ar: 'روتين كامل مع السيرومات' },
                ]
            },
            {
                step_label_en: 'Skin feel',
                step_label_ar: 'إحساس البشرة',
                step_img: 'https://picsum.photos/seed/ferrot4feel/720/400',
                question_en: 'How does your skin typically feel?',
                question_ar: 'كيف تشعر بشرتك عادةً؟',
                options: [
                    { value: 'dry',       icon: '🏜️', img: 'https://picsum.photos/seed/ferro03dry/720/420', label_en: 'Dry & Tight',      label_ar: 'جافة وشادة',       desc_en: 'Often flaky or tight',         desc_ar: 'تقشر أو شد متكرر' },
                    { value: 'oily',      icon: '💦', img: 'https://picsum.photos/seed/ferro03oil/720/420', label_en: 'Oily & Shiny',      label_ar: 'دهنية ولامعة',    desc_en: 'Shiny by midday',              desc_ar: 'لمعان في منتصف اليوم' },
                    { value: 'combo',     icon: '⚖️', img: 'https://picsum.photos/seed/ferro03com/720/420', label_en: 'Combination',       label_ar: 'مختلطة',           desc_en: 'Oily T-zone, dry cheeks',      desc_ar: 'منطقة T دهنية، خدود جافة' },
                    { value: 'sensitive', icon: '🌡️', img: 'https://picsum.photos/seed/ferro03sen/720/420', label_en: 'Sensitive',         label_ar: 'حساسة',            desc_en: 'Reacts easily to products',    desc_ar: 'تتفاعل بسهولة مع المنتجات' },
                ]
            },
            {
                step_label_en: 'Goal',
                step_label_ar: 'الهدف',
                step_img: 'https://picsum.photos/seed/ferrot5goal/720/400',
                question_en: "What's your primary performance goal?",
                question_ar: 'ما هو هدفك الأدائي الأساسي؟',
                options: [
                    { value: 'protect',   icon: '🛡️', img: 'https://picsum.photos/seed/ferro04pro/720/420', label_en: 'Protection',        label_ar: 'الحماية',          desc_en: 'Shield against environment',   desc_ar: 'حماية من البيئة' },
                    { value: 'recover',   icon: '⚕️', img: 'https://picsum.photos/seed/ferro04rcv/720/420', label_en: 'Recovery',          label_ar: 'الاسترداد',        desc_en: 'Heal and repair faster',       desc_ar: 'شفاء وإصلاح أسرع' },
                    { value: 'perform',   icon: '🏆', img: 'https://picsum.photos/seed/ferro04prf/720/420', label_en: 'Performance',       label_ar: 'الأداء',           desc_en: 'Look sharp all day',           desc_ar: 'مظهر حاد طوال اليوم' },
                    { value: 'elevate',   icon: '📈', img: 'https://picsum.photos/seed/ferro04elv/720/420', label_en: 'Elevate',           label_ar: 'الارتقاء',         desc_en: 'Full luxury upgrade',          desc_ar: 'ترقية فاخرة كاملة' },
                ]
            },
        ],

        answer(questionIndex, value) {
            this.answers[questionIndex] = value;
        },

        nextStep(currentIndex) {
            if (!this.answers[currentIndex]) return;
            if (currentIndex + 1 < this.questions.length) {
                this.step = currentIndex + 2;
            } else {
                this.computeProfile();
            }
        },

        computeProfile() {
            // Send answers to backend for recommendation engine
            fetch(FERRO_QUIZ_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ answers: this.answers, preferred_language: FERRO_LOCALE }),
            })
            .then((r) => {
                if (!r.ok) throw new Error('quiz capture failed');
                return r.json();
            })
            .then(data => {
                if (data.profile) {
                    this.skinProfile = data.profile;
                } else {
                    this.skinProfile = {
                        label_en: 'The Iron Athlete',
                        label_ar: 'الرياضي الحديدي',
                        desc_en: 'Built for performance. Your skin needs recovery and resilience.',
                        desc_ar: 'مصنوع للأداء. بشرتك تحتاج إلى الاسترداد والمرونة.',
                    };
                }
                ferroQuizRenderProducts(data.products);
                this.step = 'results';
            })
            .catch(() => {
                this.skinProfile = {
                    label_en: 'The Iron Athlete',
                    label_ar: 'الرياضي الحديدي',
                    desc_en: 'Built for performance.',
                    desc_ar: 'مصنوع للأداء.',
                };
                ferroQuizRenderProducts([]);
                this.step = 'results';
            });
        },

        captureQuizLead() {
            if (!this.leadEmail) return;
            fetch(FERRO_QUIZ_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    email: this.leadEmail,
                    answers: this.answers,
                    preferred_language: FERRO_LOCALE,
                }),
            })
            .then((r) => {
                if (!r.ok) throw new Error('lead save failed');
                return r.json();
            })
            .then((data) => {
                ferroQuizRenderProducts(data.products);
                this.leadSubmitted = true;
            })
            .catch(() => { this.leadSubmitted = false; });
        },

        reset() {
            this.step = 0;
            this.answers = {};
            this.leadEmail = '';
            this.leadSubmitted = false;
        }
    };
}
</script>
@endverbatim
@endpush
