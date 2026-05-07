@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('contact');
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')

{{-- ── Header ──────────────────────────────────────────────────────────── --}}
<section class="relative pt-[72px] min-h-[45vh] flex items-end overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset(config('ferro.page_backgrounds.heroes.contact')) }}" alt=""
             class="ferro-brand-photo w-full h-full object-cover object-center" aria-hidden="true" loading="eager" decoding="sync">
        <div class="absolute inset-0 bg-gradient-to-t from-ferro-black via-ferro-black/70 to-ferro-black/20"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_50%,rgba(232,80,10,0.08)_0%,transparent_60%)]"></div>
    </div>
    <div class="container-ferro relative z-10 pb-16 {{ $isAr ? 'text-right' : '' }}">
        <span class="eyebrow">{{ $isAr ? 'تواصل معنا' : 'Get in Touch' }}</span>
        <h1 class="font-display text-display-xl text-ferro-white">
            {{ $isAr ? 'نحن هنا لك' : "We're Here for You" }}
        </h1>
        <p class="text-ferro-silver text-lg mt-3 max-w-lg">
            {{ $isAr ? 'فريقنا جاهز للإجابة على استفساراتك.' : 'Our team is ready to answer your questions.' }}
        </p>
    </div>
    <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-ferro-black to-transparent pointer-events-none"></div>
</section>

<section class="section-pad">
    <div class="container-ferro">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-16">

            {{-- ── Contact Info ─────────────────────────────────────────── --}}
            <div class="lg:col-span-2 {{ $isAr ? 'text-right' : '' }} reveal">
                <h2 class="font-display text-display-lg text-ferro-white mb-6">
                    {{ $isAr ? 'تحدث معنا' : "Let's Talk" }}
                </h2>
                <p class="text-ferro-silver text-body-sm mb-10 leading-relaxed">
                    {{ $isAr
                        ? 'سواء كان لديك سؤال حول منتج، طلب دعم، أو فرصة شراكة — فريقنا جاهز.'
                        : "Whether you have a product question, order support need, or partnership opportunity — our team is ready." }}
                </p>

                <div class="space-y-8">
                    @foreach($contactSetting->infoRows($isAr ? 'ar' : 'en') as $info)
                        <div class="flex items-start gap-4 {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <div class="w-10 h-10 bg-ferro-orange/10 border border-ferro-orange/20 flex items-center justify-center flex-shrink-0" style="border-radius: 2px;">
                                <svg class="w-4 h-4 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-ferro-silver text-xs tracking-widest uppercase mb-1">
                                    {{ $info['title'] }}
                                </div>
                                <div class="text-ferro-white text-body-sm">{{ $info['value'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Social --}}
                <div class="mt-10 pt-8 border-t border-ferro-carbon">
                    <p class="text-ferro-ash text-xs tracking-widest uppercase mb-4">
                        {{ $contactSetting->followHeading($isAr ? 'ar' : 'en') }}
                    </p>
                    <div class="flex flex-wrap items-center gap-3 {{ $isAr ? 'flex-row-reverse justify-end' : '' }}">
                        @include('partials.social-follow-links', ['contactSetting' => $contactSetting, 'variant' => 'contact'])
                    </div>
                </div>
            </div>

            {{-- ── Contact Form ─────────────────────────────────────────── --}}
            <div class="lg:col-span-3 reveal" x-data="contactForm()">
                <div class="card-glass p-8 md:p-10">
                    <form @submit.prevent="submit" novalidate>
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="form-label" for="contact-name">
                                    {{ $isAr ? 'الاسم الكامل' : 'Full Name' }}
                                    <span class="text-ferro-orange">*</span>
                                </label>
                                <input
                                    type="text" id="contact-name" x-model="form.name"
                                    class="input-ferro" :class="{ 'border-red-500/50': errors.name }"
                                    placeholder="{{ $isAr ? 'اسمك الكامل' : 'John Smith' }}"
                                    autocomplete="name" required
                                >
                                <p x-show="errors.name" x-text="errors.name" class="text-red-400 text-xs mt-1.5" x-cloak></p>
                            </div>
                            <div>
                                <label class="form-label" for="contact-email">
                                    {{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}
                                    <span class="text-ferro-orange">*</span>
                                </label>
                                <input
                                    type="email" id="contact-email" x-model="form.email"
                                    class="input-ferro" :class="{ 'border-red-500/50': errors.email }"
                                    placeholder="{{ $isAr ? 'بريدك@email.com' : 'you@email.com' }}"
                                    autocomplete="email" required
                                >
                                <p x-show="errors.email" x-text="errors.email" class="text-red-400 text-xs mt-1.5" x-cloak></p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label" for="contact-subject">
                                {{ $isAr ? 'الموضوع' : 'Subject' }}
                            </label>
                            <select id="contact-subject" x-model="form.subject" class="input-ferro">
                                <option value="">{{ $isAr ? 'اختر الموضوع' : 'Select a subject' }}</option>
                                <option value="product">{{ $isAr ? 'استفسار منتج' : 'Product Inquiry' }}</option>
                                <option value="order">{{ $isAr ? 'دعم الطلب' : 'Order Support' }}</option>
                                <option value="partnership">{{ $isAr ? 'شراكة' : 'Partnership' }}</option>
                                <option value="media">{{ $isAr ? 'إعلام وصحافة' : 'Media & Press' }}</option>
                                <option value="other">{{ $isAr ? 'أخرى' : 'Other' }}</option>
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="form-label" for="contact-message">
                                {{ $isAr ? 'الرسالة' : 'Message' }}
                                <span class="text-ferro-orange">*</span>
                            </label>
                            <textarea
                                id="contact-message" x-model="form.message" rows="6"
                                class="input-ferro resize-none" :class="{ 'border-red-500/50': errors.message }"
                                placeholder="{{ $isAr ? 'رسالتك هنا...' : 'Your message here...' }}"
                                required
                            ></textarea>
                            <p x-show="errors.message" x-text="errors.message" class="text-red-400 text-xs mt-1.5" x-cloak></p>
                        </div>

                        <div x-show="!success">
                            <button
                                type="submit"
                                class="btn-primary w-full clip-luxury-md"
                                :disabled="loading"
                                :class="{ 'opacity-70 cursor-not-allowed': loading }"
                            >
                                <span x-show="!loading">
                                    {{ $isAr ? 'إرسال الرسالة' : 'Send Message' }}
                                </span>
                                <span x-show="loading" class="flex items-center justify-center gap-2" x-cloak>
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    {{ $isAr ? 'جاري الإرسال...' : 'Sending...' }}
                                </span>
                            </button>
                        </div>

                        <div x-show="success" x-transition class="text-center py-8" x-cloak>
                            <svg class="w-12 h-12 text-green-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="font-display text-xl text-ferro-white mb-2">
                                {{ $isAr ? 'شكراً لك!' : 'Message Sent!' }}
                            </h3>
                            <p class="text-ferro-silver text-body-sm">
                                {{ $isAr ? 'سيتواصل معك فريقنا خلال ٢٤ ساعة.' : "Our team will get back to you within 24 hours." }}
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
@verbatim
<script>
function contactForm() {
    return {
        form: { name: '', email: '', subject: '', message: '' },
        errors: {},
        loading: false,
        success: false,

        validate() {
            this.errors = {};
            const isAr = document.documentElement.lang === 'ar';
            if (!this.form.name.trim())    this.errors.name    = isAr ? 'الاسم مطلوب'          : 'Name is required';
            if (!this.form.email.trim())   this.errors.email   = isAr ? 'البريد مطلوب'         : 'Email is required';
            if (!this.form.message.trim()) this.errors.message = isAr ? 'الرسالة مطلوبة'       : 'Message is required';
            return Object.keys(this.errors).length === 0;
        },

        submit() {
            if (!this.validate()) return;
            this.loading = true;
            // POST to contact route (to be wired up)
            setTimeout(() => {
                this.loading = false;
                this.success = true;
            }, 1200);
        }
    };
}

(function() {
    const io = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
        { threshold: 0.1 }
    );
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
})();
</script>
@endverbatim
@endpush
