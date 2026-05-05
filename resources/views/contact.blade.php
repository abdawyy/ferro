@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr
    ? 'تواصل معنا — فيرو'
    : 'Contact Us — FERRO')
@section('seo_description', $isAr
    ? 'تواصل مع فريق فيرو لأي استفسارات حول منتجاتنا أو طلباتك.'
    : 'Get in touch with the FERRO team for product inquiries, order support, or partnership opportunities.')

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
                    @foreach([
                        [
                            'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
                            'title_en' => 'Email',
                            'title_ar' => 'البريد الإلكتروني',
                            'value'    => 'support@ferro.com',
                        ],
                        [
                            'icon' => 'M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z',
                            'title_en' => 'Live Chat',
                            'title_ar' => 'الدردشة المباشرة',
                            'value'    => $isAr ? 'متاح ٧ أيام ٩ص–٩م' : 'Available 7 days, 9am–9pm',
                        ],
                        [
                            'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z',
                            'title_en' => 'Headquarters',
                            'title_ar' => 'المقر الرئيسي',
                            'value'    => $isAr ? 'دبي، الإمارات العربية المتحدة' : 'Dubai, United Arab Emirates',
                        ],
                    ] as $info)
                        <div class="flex items-start gap-4 {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <div class="w-10 h-10 bg-ferro-orange/10 border border-ferro-orange/20 flex items-center justify-center flex-shrink-0" style="border-radius: 2px;">
                                <svg class="w-4 h-4 text-ferro-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['icon'] }}"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-ferro-silver text-xs tracking-widest uppercase mb-1">
                                    {{ $isAr ? $info['title_ar'] : $info['title_en'] }}
                                </div>
                                <div class="text-ferro-white text-body-sm">{{ $info['value'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Social --}}
                <div class="mt-10 pt-8 border-t border-ferro-carbon">
                    <p class="text-ferro-ash text-xs tracking-widest uppercase mb-4">
                        {{ $isAr ? 'تابعنا' : 'Follow Us' }}
                    </p>
                    <div class="flex items-center gap-3 {{ $isAr ? 'flex-row-reverse justify-end' : '' }}">
                        <a href="https://instagram.com/ferrogrooming" target="_blank" rel="noopener"
                           class="btn-icon w-10 h-10 text-ferro-ash hover:text-ferro-orange" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0 2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="https://tiktok.com/@ferrogrooming" target="_blank" rel="noopener"
                           class="btn-icon w-10 h-10 text-ferro-ash hover:text-ferro-orange" aria-label="TikTok">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.54V6.77a4.85 4.85 0 01-1.02-.08z"/>
                            </svg>
                        </a>
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
