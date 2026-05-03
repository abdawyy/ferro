@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr
    ? 'اختبار البشرة — اكتشف روتين فيرو المثالي لك'
    : 'Skin Quiz — Discover Your Perfect FERRO Routine')
@section('seo_description', $isAr
    ? 'أجب على ٥ أسئلة واحصل على توصيات فيرو المخصصة لنوع بشرتك ونمط حياتك.'
    : 'Answer 5 questions and receive personalized FERRO product recommendations tailored to your skin type and lifestyle.')

@section('content')

<div class="pt-[72px] min-h-screen flex items-center" x-data="ferroQuiz()">

    {{-- Background --}}
    <div class="absolute inset-0 z-0 bg-ferro-black">
        <img src="{{ asset('images/quiz-bg.jpg') }}" alt="" class="w-full h-full object-cover object-center opacity-10" aria-hidden="true" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-b from-ferro-black/60 via-ferro-black/80 to-ferro-black"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_50%_30%,rgba(232,80,10,0.10)_0%,transparent_60%)]" aria-hidden="true"></div>
    </div>

    <div class="container-ferro relative z-10 section-pad w-full">

        {{-- ── Intro Screen ────────────────────────────────────────────── --}}
        <div x-show="step === 0" x-transition class="max-w-2xl mx-auto text-center">
            <svg class="w-10 h-10 text-ferro-orange mx-auto mb-8" viewBox="0 0 32 32" fill="currentColor">
                <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
            </svg>
            <span class="eyebrow">{{ $isAr ? 'خصّص روتينك' : 'Personalize Your Routine' }}</span>
            <h1 class="font-display text-display-xl text-ferro-white mb-6">
                {{ $isAr ? 'ما هو ملف بشرتك؟' : "What's Your Skin Profile?" }}
            </h1>
            <p class="text-ferro-silver text-body-lg mb-10">
                {{ $isAr
                    ? '٥ أسئلة سريعة للحصول على روتين فيرو المثالي لنمط حياتك وأهدافك.'
                    : '5 quick questions to find your perfect FERRO regimen tailored to your lifestyle and goals.' }}
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <button @click="step = 1" class="btn-primary clip-luxury-md">
                    {{ $isAr ? 'ابدأ الاختبار المجاني' : 'Start the Free Quiz' }}
                    <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </button>
            </div>
            <p class="text-ferro-ash text-xs mt-6">{{ $isAr ? 'يستغرق أقل من دقيقة' : 'Takes less than 1 minute' }}</p>
        </div>

        {{-- ── Question Screen ─────────────────────────────────────────── --}}
        <template x-for="(question, index) in questions" :key="index">
            <div
                x-show="step === index + 1"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-8"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="max-w-2xl mx-auto"
            >
                {{-- Progress --}}
                <div class="mb-10">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-ferro-ash text-xs uppercase tracking-widest">
                            {{ $isAr ? 'السؤال' : 'Question' }} <span x-text="index + 1"></span> / {{ count($questions ?? []) ?: 5 }}
                        </span>
                        <span class="text-ferro-orange text-xs font-semibold" x-text="Math.round(((index + 1) / questions.length) * 100) + '%'"></span>
                    </div>
                    <div class="h-1 bg-ferro-carbon overflow-hidden" style="border-radius:2px;">
                        <div class="h-full bg-ferro-orange transition-all duration-500"
                             :style="{ width: ((index + 1) / questions.length * 100) + '%' }"></div>
                    </div>
                </div>

                <h2 class="font-display text-display-lg text-ferro-white mb-8 {{ $isAr ? 'text-right' : '' }}"
                    x-text="{{ $isAr ? 'question.question_ar' : 'question.question_en' }}"
                    aria-live="polite">&#8203;</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="option in question.options" :key="option.value">
                        <button
                            @click="answer(index, option.value)"
                            class="card-glass p-6 text-start hover:border-ferro-orange/50 transition-all duration-200 group"
                            :class="answers[index] === option.value ? 'border-ferro-orange bg-ferro-orange/5' : ''"
                        >
                            <div class="text-2xl mb-2" x-text="option.icon"></div>
                            <div class="text-ferro-white text-body-sm font-medium group-hover:text-ferro-white {{ $isAr ? 'text-right' : '' }}"
                                 x-text="{{ $isAr ? 'option.label_ar' : 'option.label_en' }}"></div>
                            <div class="text-ferro-ash text-xs mt-1 {{ $isAr ? 'text-right' : '' }}"
                                 x-text="{{ $isAr ? 'option.desc_ar' : 'option.desc_en' }}"></div>
                        </button>
                    </template>
                </div>

                <div class="flex items-center justify-between mt-10">
                    <button
                        @click="step = Math.max(0, step - 1)"
                        x-show="step > 1"
                        class="btn-ghost"
                    >
                        <svg class="w-4 h-4 {{ $isAr ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                        {{ $isAr ? 'السابق' : 'Back' }}
                    </button>
                    <button
                        @click="nextStep(index)"
                        :disabled="!answers[index]"
                        :class="answers[index] ? 'btn-primary' : 'btn-secondary opacity-50 cursor-not-allowed'"
                        class="clip-luxury-sm"
                    >
                        <span x-text="index + 1 < questions.length ? '{{ $isAr ? 'التالي' : 'Next' }}' : '{{ $isAr ? 'عرض النتائج' : 'See Results' }}'"></span>
                    </button>
                </div>
            </div>
        </template>

        {{-- ── Results Screen ──────────────────────────────────────────── --}}
        <div
            x-show="step === 'results'"
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            class="max-w-3xl mx-auto text-center"
        >
            <div class="mb-8">
                <div class="w-16 h-16 bg-ferro-orange/10 border border-ferro-orange/30 flex items-center justify-center mx-auto mb-6" style="border-radius: 2px;">
                    <svg class="w-7 h-7 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="eyebrow">{{ $isAr ? 'روتينك المخصص' : 'Your Personalized Routine' }}</span>
                <h2 class="font-display text-display-lg text-ferro-white mb-4">
                    {{ $isAr ? 'ملف بشرتك:' : 'Your Skin Profile:' }}
                    <span class="text-gradient-orange" x-text="{{ $isAr ? 'skinProfile.label_ar' : 'skinProfile.label_en' }}"></span>
                </h2>
                <p class="text-ferro-silver text-body-lg" x-text="{{ $isAr ? 'skinProfile.desc_ar' : 'skinProfile.desc_en' }}"></p>
            </div>

            {{-- Recommended products (fetched from quiz engine) --}}
            <div id="quiz-recommendations" class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
                {{-- Populated via AJAX after quiz submission --}}
            </div>

            {{-- Lead capture --}}
            <div class="waitlist-card max-w-lg mx-auto p-8">
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
                    <input
                        type="email"
                        x-model="leadEmail"
                        class="input-ferro"
                        placeholder="{{ $isAr ? 'بريدك الإلكتروني' : 'your@email.com' }}"
                        required
                    >
                    <button type="submit" class="btn-primary w-full" :disabled="leadSubmitted">
                        <span x-show="!leadSubmitted">{{ $isAr ? 'أرسل روتيني' : 'Send My Routine' }}</span>
                        <span x-show="leadSubmitted" class="text-green-400">
                            ✓ {{ $isAr ? 'تم الإرسال!' : 'Sent!' }}
                        </span>
                    </button>
                </form>
            </div>

            <button @click="reset()" class="mt-8 text-ferro-ash text-body-sm hover:text-ferro-silver transition-colors underline underline-offset-4">
                {{ $isAr ? 'إعادة الاختبار' : 'Retake Quiz' }}
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>const FERRO_QUIZ_URL = '{{ route('quiz.capture') }}';</script>
@verbatim
<script>
function ferroQuiz() {
    return {
        step: 0,
        answers: {},
        leadEmail: '',
        leadSubmitted: false,
        skinProfile: { label_en: '', label_ar: '', desc_en: '', desc_ar: '' },
        questions: [
            {
                question_en: 'How would you describe your lifestyle?',
                question_ar: 'كيف تصف نمط حياتك؟',
                options: [
                    { value: 'athlete',   icon: '⚡', label_en: 'Elite Athlete',    label_ar: 'رياضي نخبة',    desc_en: 'Daily training, high sweat output',   desc_ar: 'تدريب يومي مكثف' },
                    { value: 'executive', icon: '🏙️', label_en: 'Urban Executive',  label_ar: 'رجل الأعمال',   desc_en: 'High-stress, city environment',       desc_ar: 'بيئة المدينة والضغط العالي' },
                    { value: 'outdoor',   icon: '🌿', label_en: 'Outdoor Enthusiast',label_ar: 'محب الطبيعة',   desc_en: 'Exposed to elements daily',           desc_ar: 'التعرض للعناصر يومياً' },
                    { value: 'refined',   icon: '💎', label_en: 'The Refined Man',   label_ar: 'الرجل الراقي',  desc_en: 'Premium lifestyle, values aesthetics', desc_ar: 'نمط حياة فاخر ومتطور' },
                ]
            },
            {
                question_en: "What's your primary skin concern?",
                question_ar: 'ما هو اهتمامك الجلدي الأساسي؟',
                options: [
                    { value: 'recovery',  icon: '🔄', label_en: 'Post-workout Recovery', label_ar: 'التعافي بعد التمرين', desc_en: 'Redness, inflammation, irritation',    desc_ar: 'احمرار، التهاب، تهيج' },
                    { value: 'hydration', icon: '💧', label_en: 'Deep Hydration',         label_ar: 'ترطيب عميق',          desc_en: 'Dryness and tightness',               desc_ar: 'جفاف وشد' },
                    { value: 'oil',       icon: '✨', label_en: 'Oil Control',             label_ar: 'التحكم في الدهون',    desc_en: 'Shine and breakouts',                 desc_ar: 'لمعان وحبوب' },
                    { value: 'aging',     icon: '⏱️', label_en: 'Anti-Aging Defense',      label_ar: 'مكافحة الشيخوخة',    desc_en: 'Fine lines and firmness',             desc_ar: 'خطوط دقيقة وترهل' },
                ]
            },
            {
                question_en: 'How often do you currently use skincare?',
                question_ar: 'كم مرة تستخدم العناية بالبشرة حالياً؟',
                options: [
                    { value: 'none',      icon: '0️⃣', label_en: 'Never',        label_ar: 'أبداً',           desc_en: 'Starting from zero',       desc_ar: 'أبدأ من الصفر' },
                    { value: 'basic',     icon: '1️⃣', label_en: 'Just basics',  label_ar: 'الأساسيات فقط',  desc_en: 'Cleanser or moisturizer',  desc_ar: 'منظف أو مرطب' },
                    { value: 'routine',   icon: '2️⃣', label_en: 'Daily routine',label_ar: 'روتين يومي',      desc_en: '3-4 steps daily',          desc_ar: '٣-٤ خطوات يومياً' },
                    { value: 'advanced',  icon: '🔬', label_en: 'Advanced',      label_ar: 'متقدم',           desc_en: 'Full regimen with serums',  desc_ar: 'روتين كامل مع السيرومات' },
                ]
            },
            {
                question_en: 'How does your skin typically feel?',
                question_ar: 'كيف تشعر بشرتك عادةً؟',
                options: [
                    { value: 'dry',       icon: '🏜️', label_en: 'Dry & Tight',      label_ar: 'جافة وشادة',       desc_en: 'Often flaky or tight',         desc_ar: 'تقشر أو شد متكرر' },
                    { value: 'oily',      icon: '💦', label_en: 'Oily & Shiny',      label_ar: 'دهنية ولامعة',    desc_en: 'Shiny by midday',              desc_ar: 'لمعان في منتصف اليوم' },
                    { value: 'combo',     icon: '⚖️', label_en: 'Combination',       label_ar: 'مختلطة',           desc_en: 'Oily T-zone, dry cheeks',      desc_ar: 'منطقة T دهنية، خدود جافة' },
                    { value: 'sensitive', icon: '🌡️', label_en: 'Sensitive',         label_ar: 'حساسة',            desc_en: 'Reacts easily to products',    desc_ar: 'تتفاعل بسهولة مع المنتجات' },
                ]
            },
            {
                question_en: "What's your primary performance goal?",
                question_ar: 'ما هو هدفك الأدائي الأساسي؟',
                options: [
                    { value: 'protect',   icon: '🛡️', label_en: 'Protection',        label_ar: 'الحماية',          desc_en: 'Shield against environment',   desc_ar: 'حماية من البيئة' },
                    { value: 'recover',   icon: '⚕️', label_en: 'Recovery',          label_ar: 'الاسترداد',        desc_en: 'Heal and repair faster',       desc_ar: 'شفاء وإصلاح أسرع' },
                    { value: 'perform',   icon: '🏆', label_en: 'Performance',       label_ar: 'الأداء',           desc_en: 'Look sharp all day',           desc_ar: 'مظهر حاد طوال اليوم' },
                    { value: 'elevate',   icon: '📈', label_en: 'Elevate',           label_ar: 'الارتقاء',         desc_en: 'Full luxury upgrade',          desc_ar: 'ترقية فاخرة كاملة' },
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
                body: JSON.stringify({ answers: this.answers }),
            })
            .then(r => r.json())
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
                this.step = 'results';
            })
            .catch(() => {
                this.skinProfile = {
                    label_en: 'The Iron Athlete',
                    label_ar: 'الرياضي الحديدي',
                    desc_en: 'Built for performance.',
                    desc_ar: 'مصنوع للأداء.',
                };
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
                    source: 'quiz',
                }),
            })
            .then(() => { this.leadSubmitted = true; })
            .catch(() => { this.leadSubmitted = true; });
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
