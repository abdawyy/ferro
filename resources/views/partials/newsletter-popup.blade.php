@if($newsletterSettings->is_enabled)
@php
    $isAr = app()->getLocale() === 'ar';
    $popup = [
        'enabled' => true,
        'delay' => (int) $newsletterSettings->delay_seconds,
        'title' => $newsletterSettings->title(),
        'message' => $newsletterSettings->message(),
        'button' => $newsletterSettings->buttonText(),
        'success' => $newsletterSettings->successMessage(),
        'discount' => (int) $newsletterSettings->discount_percent,
        'locale' => app()->getLocale(),
        'subscribeUrl' => route('newsletter.subscribe'),
        'closeLabel' => $isAr ? 'إغلاق' : 'Close',
        'emailPlaceholder' => $isAr ? 'بريدك الإلكتروني' : 'Your email address',
        'discountLabel' => $isAr ? 'خصم' : 'OFF',
    ];
@endphp
<div
    x-data="ferroNewsletterPopup(@js($popup))"
    x-cloak
    @keydown.escape.window="dismiss()"
>
    <template x-teleport="body">
        <div
            x-show="visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            id="ferro-newsletter-popup"
            class="fixed inset-0 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            style="z-index: 99999"
            @click.self="dismiss()"
        >
            <div
                x-show="visible"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                class="relative w-full max-w-md border border-ferro-charcoal/80 bg-ferro-black shadow-2xl"
                role="dialog"
                aria-modal="true"
                aria-labelledby="ferro-newsletter-title"
            >
            <button
                type="button"
                @click="dismiss()"
                class="absolute top-3 end-3 text-ferro-muted hover:text-ferro-off-white transition-colors"
                :aria-label="config.closeLabel"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full border border-ferro-orange/40 bg-ferro-orange/10 text-ferro-orange text-xl mb-5">
                    %
                </div>

                <div class="text-[11px] uppercase tracking-[0.18em] text-ferro-orange mb-2">
                    <span x-text="config.discount + '%'"></span>
                    <span x-text="config.discountLabel"></span>
                </div>

                <h2 id="ferro-newsletter-title" class="font-serif text-2xl text-ferro-off-white mb-3" x-text="config.title"></h2>
                <p class="text-sm text-ferro-muted leading-relaxed mb-6" x-text="config.message"></p>

                <template x-if="!submitted">
                    <form @submit.prevent="submit()" class="space-y-3">
                        <input
                            type="email"
                            x-model="email"
                            required
                            :placeholder="config.emailPlaceholder"
                            class="newsletter-popup-email w-full px-4 py-3 text-sm focus:outline-none"
                        >
                        <p x-show="error" x-text="error" class="text-red-400 text-xs"></p>
                        <button type="submit" class="btn-primary w-full" :disabled="loading">
                            <span x-show="!loading" x-text="config.button"></span>
                            <span x-show="loading">...</span>
                        </button>
                    </form>
                </template>

                <template x-if="submitted">
                    <div class="py-4">
                        <div class="text-green-400 text-sm" x-text="successMessage"></div>
                    </div>
                </template>
            </div>
            </div>
        </div>
    </template>
</div>

@push('head')
<style>
    #ferro-newsletter-popup .newsletter-popup-email {
        background-color: #2A2A2A !important;
        border: 1px solid #3A3A3A;
        color: #F5F2EE !important;
        -webkit-text-fill-color: #F5F2EE !important;
        caret-color: #F5F2EE;
    }
    #ferro-newsletter-popup .newsletter-popup-email::placeholder {
        color: #6B6B6B;
        opacity: 1;
    }
    #ferro-newsletter-popup .newsletter-popup-email:focus {
        border-color: #E8500A;
        box-shadow: 0 0 0 3px rgba(232, 80, 10, 0.15);
    }
    #ferro-newsletter-popup .newsletter-popup-email:-webkit-autofill,
    #ferro-newsletter-popup .newsletter-popup-email:-webkit-autofill:hover,
    #ferro-newsletter-popup .newsletter-popup-email:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #2A2A2A inset !important;
        box-shadow: 0 0 0 1000px #2A2A2A inset !important;
        -webkit-text-fill-color: #F5F2EE !important;
        caret-color: #F5F2EE;
        border: 1px solid #3A3A3A;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('ferroNewsletterPopup', (config) => ({
        config,
        visible: false,
        email: '',
        loading: false,
        submitted: false,
        successMessage: '',
        error: '',

        init() {
            if (localStorage.getItem('ferro_newsletter_subscribed') === '1') return;
            if (localStorage.getItem('ferro_newsletter_dismissed') === '1') return;

            setTimeout(() => {
                if (localStorage.getItem('ferro_newsletter_subscribed') !== '1'
                    && localStorage.getItem('ferro_newsletter_dismissed') !== '1') {
                    this.visible = true;
                }
            }, (config.delay || 5) * 1000);
        },

        dismiss() {
            this.visible = false;
            localStorage.setItem('ferro_newsletter_dismissed', '1');
        },

        async submit() {
            this.loading = true;
            this.error = '';

            try {
                const response = await fetch(config.subscribeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        email: this.email,
                        preferred_language: config.locale,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    this.error = data.errors?.email?.[0] || data.message || 'Something went wrong.';
                    return;
                }

                this.submitted = true;
                this.successMessage = data.message || config.success;
                localStorage.setItem('ferro_newsletter_subscribed', '1');

                if (window.showToast) {
                    window.showToast(this.successMessage, 'success');
                }
            } catch (e) {
                this.error = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        },
    }));
});
</script>
@endpush
@endif
