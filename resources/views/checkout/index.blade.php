@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $checkoutPrefill = [];
    if (auth()->check()) {
        $u = auth()->user();
        $parts = preg_split('/\s+/', trim($u->name), 2, PREG_SPLIT_NO_EMPTY);
        $checkoutPrefill = [
            'firstName' => $parts[0] ?? '',
            'lastName'  => isset($parts[1]) ? $parts[1] : '',
            'email'     => $u->email,
            'phone'     => '',
        ];
    }
    $shippingCityRates = $shippingCities->map(function ($c) use ($isAr) {
        $loc = $isAr ? 'ar' : 'en';

        return [
            'slug' => $c->slug,
            'fee' => (float) $c->shipping_price,
            'currency' => $c->currency,
            'label' => $c->getTranslation('name', $loc, false)
                ?? $c->getTranslation('name', 'en', false)
                ?? (string) $c->name,
        ];
    })->values();
@endphp

@section('seo_title', $isAr ? 'إتمام الطلب — فيرو' : 'Checkout — FERRO')

@section('content')

<div id="checkout-app" class="pt-[72px] min-h-screen" x-data="ferroCheckout(@js($checkoutPrefill), @js($shippingCityRates), @js($isAr))" x-bind:data-cart-email="info.email || ''">

    <div class="bg-ferro-obsidian border-b border-ferro-carbon">
        <div class="container-ferro py-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <svg class="w-7 h-7 text-ferro-orange" viewBox="0 0 32 32" fill="currentColor">
                        <path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/>
                    </svg>
                    <span class="font-display text-xl tracking-[0.2em] text-ferro-white uppercase">FERRO</span>
                </a>
                <div class="hidden sm:flex items-center gap-2 text-xs">
                    @foreach([
                        ['key' => 1, 'label_en' => 'Information', 'label_ar' => 'المعلومات'],
                        ['key' => 2, 'label_en' => 'Shipping & COD', 'label_ar' => 'الشحن والدفع'],
                    ] as $s)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold transition-all duration-300"
                                 :class="step >= {{ $s['key'] }}
                                     ? 'bg-ferro-orange text-white'
                                     : 'bg-ferro-carbon text-ferro-ash'">
                                {{ $s['key'] }}
                            </div>
                            <span :class="step >= {{ $s['key'] }} ? 'text-ferro-white' : 'text-ferro-ash'">
                                {{ $isAr ? $s['label_ar'] : $s['label_en'] }}
                            </span>
                            @if(!$loop->last)
                                <svg class="w-4 h-4 text-ferro-carbon {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                </svg>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="container-ferro section-pad">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">

            <div class="lg:col-span-3">

                <div x-show="step === 1" x-transition>
                    <h2 class="font-display text-2xl text-ferro-white mb-8 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'معلومات الاتصال' : 'Contact Information' }}
                    </h2>
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label" for="first-name">{{ $isAr ? 'الاسم الأول' : 'First Name' }}</label>
                                <input type="text" id="first-name" x-model="info.firstName" class="input-ferro" autocomplete="given-name" required>
                            </div>
                            <div>
                                <label class="form-label" for="last-name">{{ $isAr ? 'اسم العائلة' : 'Last Name' }}</label>
                                <input type="text" id="last-name" x-model="info.lastName" class="input-ferro" autocomplete="family-name" required>
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="email">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                            <input type="email" id="email" x-model="info.email" class="input-ferro" autocomplete="email" required>
                        </div>
                        <div>
                            <label class="form-label" for="phone">{{ $isAr ? 'رقم الهاتف' : 'Phone' }}</label>
                            <input type="tel" id="phone" x-model="info.phone" class="input-ferro" autocomplete="tel">
                        </div>
                        <div>
                            <label class="form-label" for="hear-about">{{ $isAr ? 'كيف سمعت عنا؟ (اختياري)' : 'How did you hear about us? (optional)' }}</label>
                            <input type="text" id="hear-about" x-model="hearAboutUs" class="input-ferro" maxlength="120" placeholder="{{ $isAr ? 'مثال: إنستغرام، صديق…' : 'e.g. Instagram, friend…' }}">
                        </div>
                        <label class="flex items-start gap-3 cursor-pointer {{ $isAr ? 'flex-row-reverse text-right' : '' }}">
                            <input type="checkbox" x-model="marketingConsent" class="accent-ferro-orange mt-1 w-4 h-4 shrink-0">
                            <span class="text-ferro-silver text-sm leading-relaxed">
                                {{ $isAr
                                    ? 'أرغب في تلقي أخبار العروض والمنتجات عبر البريد. يمكنني إلغاء الاشتراك في أي وقت.'
                                    : 'Email me about new products and exclusive offers. Unsubscribe anytime.' }}
                            </span>
                        </label>
                        <button type="button" @click="step = 2" class="btn-primary w-full clip-luxury-md">
                            {{ $isAr ? 'متابعة إلى الشحن' : 'Continue to Shipping' }}
                            <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div x-show="step === 2" x-transition>
                    <h2 class="font-display text-2xl text-ferro-white mb-8 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'الشحن والدفع عند الاستلام' : 'Shipping & cash on delivery' }}
                    </h2>
                    <div class="space-y-5">
                        <input type="hidden" x-model="shipping.country">

                        <div>
                            <label class="form-label" for="governorate">{{ $isAr ? 'المحافظة' : 'Governorate' }}</label>
                            <select id="governorate" x-model="shipping.city_slug" class="input-ferro" required>
                                <template x-for="c in cityRates" :key="c.slug">
                                    <option :value="c.slug" x-text="c.label"></option>
                                </template>
                            </select>
                            <p class="text-ferro-ash text-xs mt-2">
                                {{ $isAr ? 'يتم احتساب رسوم الشحن حسب المحافظة (قابلة للتعديل من لوحة التحكم).' : 'Delivery fee is set per governorate (editable in admin).' }}
                            </p>
                        </div>

                        <div>
                            <label class="form-label" for="address">{{ $isAr ? 'العنوان بالتفصيل' : 'Street address' }}</label>
                            <input type="text" id="address" x-model="shipping.address" class="input-ferro" autocomplete="address-line1" required>
                        </div>

                        <div class="bg-ferro-carbon/30 border border-ferro-carbon p-5" style="border-radius: 2px;">
                            <p class="text-ferro-white text-sm font-semibold mb-2">
                                {{ $isAr ? 'الدفع عند الاستلام فقط' : 'Cash on delivery only' }}
                            </p>
                            <p class="text-ferro-silver text-body-sm leading-relaxed">
                                {{ $isAr
                                    ? 'لا نقبل البطاقات عبر الموقع حالياً. سيدفع عند التسليم نقداً للمندوب.'
                                    : 'Card payments are disabled for now. You will pay the courier in cash when your order arrives.' }}
                            </p>
                        </div>

                        <div>
                            <label class="form-label" for="order-notes">{{ $isAr ? 'ملاحظات الطلب (اختياري)' : 'Order notes (optional)' }}</label>
                            <textarea id="order-notes" x-model="customerNotes" class="input-ferro min-h-[88px]" maxlength="2000" placeholder="{{ $isAr ? 'تعليمات التوصيل…' : 'Delivery instructions…' }}"></textarea>
                        </div>

                        <div class="flex gap-3 mt-6 {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <button type="button" @click="step = 1" class="btn-secondary clip-luxury-sm">
                                {{ $isAr ? 'رجوع' : 'Back' }}
                            </button>
                            <button type="button" @click="placeOrder()" class="btn-primary flex-1 clip-luxury-md" :disabled="loading">
                                <span x-show="!loading">{{ $isAr ? 'تأكيد الطلب' : 'Place order' }}</span>
                                <span x-show="loading" class="flex items-center gap-2" x-cloak>
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                    </svg>
                                    {{ $isAr ? 'جاري المعالجة...' : 'Processing...' }}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-ferro-obsidian border border-ferro-carbon p-6 sticky top-[90px]" style="border-radius: 2px;">
                    <h3 class="font-display text-lg text-ferro-white mb-5 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'ملخص طلبك' : 'Your order' }}
                    </h3>

                    <div class="space-y-3 mb-6">
                        <template x-for="item in cartItems" :key="item.id">
                            <div class="flex items-center gap-3 {{ $isAr ? 'flex-row-reverse' : '' }}">
                                <div class="relative flex-shrink-0">
                                    <img :src="item.image" :alt="item.name" class="w-12 h-14 object-cover bg-ferro-carbon" style="border-radius: 2px;">
                                    <span class="absolute -top-1.5 {{ $isAr ? '-start-1.5' : '-end-1.5' }} w-5 h-5 bg-ferro-orange text-white text-[10px] font-bold rounded-full flex items-center justify-center" x-text="item.qty"></span>
                                </div>
                                <div class="flex-1 min-w-0 {{ $isAr ? 'text-right' : '' }}">
                                    <p class="text-ferro-white text-xs font-medium leading-snug" x-text="item.name"></p>
                                </div>
                                <span class="text-ferro-white text-xs font-semibold flex-shrink-0" x-text="formatMoney(item.price * item.qty)"></span>
                            </div>
                        </template>
                    </div>

                    <div class="divider pt-4 space-y-2 text-body-sm {{ $isAr ? 'text-right' : '' }}">
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'المجموع' : 'Subtotal' }}</span>
                            <span class="text-ferro-white" x-text="formatMoney(subtotal)"></span>
                        </div>
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الشحن' : 'Shipping' }}</span>
                            <span class="text-ferro-white" x-text="formatMoney(shippingCost)"></span>
                        </div>
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الضريبة ٥٪' : 'Tax (5%)' }}</span>
                            <span class="text-ferro-white" x-text="formatMoney(taxAmount)"></span>
                        </div>
                        <div class="flex justify-between font-semibold pt-2 border-t border-ferro-carbon {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-white">{{ $isAr ? 'الإجمالي' : 'Total' }}</span>
                            <span class="text-ferro-orange text-lg" x-text="formatMoney(orderTotal)"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ferroCheckout(prefill, cityRates, isAr) {
    const cart = JSON.parse(localStorage.getItem('ferro_cart') || '[]');
    const pf = (prefill && typeof prefill === 'object' && !Array.isArray(prefill)) ? prefill : {};
    const rates = Array.isArray(cityRates) ? cityRates : [];
    const defaultSlug = rates[0]?.slug || '';

    return {
        step: 1,
        loading: false,
        cartItems: cart,
        cityRates: rates,
        info: { firstName: '', lastName: '', email: '', phone: '', ...pf },
        shipping: { address: '', city_slug: defaultSlug, country: 'EG' },
        marketingConsent: false,
        hearAboutUs: '',
        customerNotes: '',

        init() {
            if (!this.cartItems.length) {
                window.location.href = @json(route('cart'));
                return;
            }
            if (!this.shipping.city_slug && defaultSlug) {
                this.shipping.city_slug = defaultSlug;
            }
            this.$watch('cartItems', () => this.syncBeaconCart(), { deep: true });
            this.$watch('info.email', () => this.syncBeaconCart());
            this.$watch('shipping.city_slug', () => this.syncBeaconCart());
            this.syncBeaconCart();
        },

        syncBeaconCart() {
            window.__FERRO_CART__ = {
                items: this.cartItems.map(i => ({ id: i.id, name: i.name, qty: i.qty, price: i.price })),
                total: this.orderTotal,
            };
        },

        moneyCurrency() {
            return this.cartItems[0]?.currency || 'EGP';
        },

        formatMoney(amount) {
            const n = Number(amount) || 0;
            const cur = this.moneyCurrency();
            const u = String(cur).toUpperCase();
            if (u === 'EGP' || u === 'USD' || u === 'LE') return n.toFixed(2) + ' EGP';
            if (u === 'AED') return n.toFixed(2) + ' AED';
            return n.toFixed(2) + ' ' + u;
        },

        selectedCity() {
            return this.cityRates.find((c) => c.slug === this.shipping.city_slug) || this.cityRates[0] || null;
        },

        get subtotal() {
            return this.cartItems.reduce((s, i) => s + (i.price * i.qty), 0);
        },

        get shippingCost() {
            const c = this.selectedCity();
            return c ? Number(c.fee) || 0 : 0;
        },

        get taxAmount() {
            return +(this.subtotal * 0.05).toFixed(2);
        },

        get orderTotal() {
            return +(this.subtotal + this.shippingCost + this.taxAmount).toFixed(2);
        },

        async placeOrder() {
            if (!this.cartItems.length) {
                showToast(isAr ? 'سلتك فارغة' : 'Your cart is empty.', 'error');
                return;
            }
            if (!this.shipping.address?.trim()) {
                showToast(isAr ? 'يرجى إدخال العنوان.' : 'Please enter your address.', 'error');
                return;
            }
            if (!this.shipping.city_slug) {
                showToast(isAr ? 'يرجى اختيار المحافظة.' : 'Please choose a governorate.', 'error');
                return;
            }
            this.loading = true;
            try {
                const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content');
                const body = {
                    items: this.cartItems.map(i => ({ id: i.id, quantity: i.qty })),
                    contact: {
                        first_name: this.info.firstName,
                        last_name: this.info.lastName,
                        email: this.info.email,
                        phone: this.info.phone || null,
                    },
                    shipping: {
                        address: this.shipping.address,
                        city_slug: this.shipping.city_slug,
                        country: 'EG',
                    },
                    marketing_consent: this.marketingConsent,
                    hear_about_us: this.hearAboutUs || null,
                    customer_notes: this.customerNotes || null,
                };
                const res = await fetch(@json(route('checkout.order')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify(body),
                });
                const data = await res.json().catch(() => ({}));
                if (!res.ok || !data.success) {
                    const msg = data.message
                        || (data.errors && Object.values(data.errors).flat().join(' '))
                        || (isAr ? 'تعذر إتمام الطلب.' : 'Could not place order.');
                    showToast(msg, 'error');
                    this.loading = false;
                    return;
                }
                localStorage.setItem('ferro_cart', '[]');
                if (typeof window.ferroSyncCartBadges === 'function') {
                    window.ferroSyncCartBadges(0);
                }
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                showToast(isAr ? 'تم تأكيد طلبك!' : 'Order confirmed!', 'success');
            } catch (e) {
                showToast(isAr ? 'خطأ في الشبكة.' : 'Network error.', 'error');
            }
            this.loading = false;
        },
    };
}
</script>
@endpush
