@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('cart');
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')

<div class="pt-[72px] min-h-screen" x-data="ferroCart()">

    {{-- Page header --}}
    <div class="bg-ferro-obsidian border-b border-ferro-carbon">
        <div class="container-ferro py-10 {{ $isAr ? 'text-right' : '' }}">
            <span class="eyebrow">{{ $isAr ? 'سلتك' : 'Your Arsenal' }}</span>
            <h1 class="font-display text-display-lg text-ferro-white">
                {{ $isAr ? 'مراجعة الطلب' : 'Review Your Order' }}
            </h1>
        </div>
    </div>

    <div class="container-ferro section-pad">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 xl:gap-16" x-show="items.length > 0" x-cloak>

            {{-- ── Cart Items ────────────────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-4">
                <template x-for="item in items" :key="item.id">
                    <div class="flex gap-5 bg-ferro-obsidian border border-ferro-carbon p-5 transition-all duration-300 hover:border-ferro-carbon/80"
                         style="border-radius: 2px;">

                        {{-- Thumbnail --}}
                        <a :href="item.url" class="flex-shrink-0 w-24 h-28 bg-ferro-carbon overflow-hidden" style="border-radius: 2px;">
                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                        </a>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0 {{ $isAr ? 'text-right' : '' }}">
                            <a :href="item.url" class="font-display text-lg text-ferro-white hover:text-ferro-orange transition-colors" x-text="item.name" :aria-label="item.name">&#8203;</a>
                            <p class="text-ferro-ash text-xs mt-0.5" x-text="item.category"></p>

                            {{-- Qty + Price row --}}
                            <div class="flex items-center justify-between mt-4 gap-4 {{ $isAr ? 'flex-row-reverse' : '' }}">
                                <div class="qty-stepper">
                                    <button @click="updateQty(item.id, item.qty - 1)" type="button" aria-label="{{ $isAr ? 'تقليل' : 'Decrease' }}">−</button>
                                    <input type="number" x-model.number="item.qty" @change="updateQty(item.id, item.qty)" min="1" max="10">
                                    <button @click="updateQty(item.id, item.qty + 1)" type="button" aria-label="{{ $isAr ? 'زيادة' : 'Increase' }}">+</button>
                                </div>
                                <div class="text-right {{ $isAr ? 'text-left' : '' }}">
                                    <span class="text-ferro-white font-semibold" x-text="formatPrice(item.price * item.qty)"></span>
                                    <span class="text-ferro-ash text-xs block" x-text="formatPrice(item.price) + ' {{ $isAr ? 'للقطعة' : 'each' }}'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Remove --}}
                        <button
                            @click="removeItem(item.id)"
                            class="flex-shrink-0 text-ferro-ash hover:text-red-400 transition-colors self-start mt-1"
                            :aria-label="'{{ $isAr ? 'حذف' : 'Remove' }} ' + item.name"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                            </svg>
                        </button>
                    </div>
                </template>

                {{-- Continue shopping --}}
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-ferro-ash text-body-sm hover:text-ferro-orange transition-colors mt-4">
                    <svg class="w-4 h-4 {{ $isAr ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                    {{ $isAr ? 'متابعة التسوق' : 'Continue Shopping' }}
                </a>
            </div>

            {{-- ── Order Summary ──────────────────────────────────────── --}}
            <div class="lg:col-span-1">
                <div class="bg-ferro-obsidian border border-ferro-carbon p-6 sticky top-[90px]" style="border-radius: 2px;">
                    <h2 class="font-display text-xl text-ferro-white mb-6 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'ملخص الطلب' : 'Order Summary' }}
                    </h2>

                    <div class="space-y-3 mb-6 {{ $isAr ? 'text-right' : '' }}">
                        <div class="flex justify-between text-body-sm {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                            <span class="text-ferro-white" x-text="formatPrice(subtotal)"></span>
                        </div>
                        <div class="flex justify-between text-body-sm {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الشحن' : 'Shipping' }}</span>
                            <span class="text-ferro-ash text-xs font-medium">{{ $isAr ? 'يُحسب عند الدفع' : 'Calculated at checkout' }}</span>
                        </div>
                        <div class="flex justify-between text-body-sm {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الضريبة (5%)' : 'Tax (5%)' }}</span>
                            <span class="text-ferro-white" x-text="formatPrice(tax)"></span>
                        </div>
                    </div>

                    <div class="divider py-4 flex justify-between items-center font-semibold {{ $isAr ? 'flex-row-reverse' : '' }}">
                        <span class="text-ferro-white">{{ $isAr ? 'الإجمالي' : 'Total' }}</span>
                        <span class="text-ferro-orange text-xl" x-text="formatPrice(total)"></span>
                    </div>

                    {{-- Promo code --}}
                    <div class="mt-5 mb-6" x-data="{ promoOpen: false }">
                        <button @click="promoOpen = !promoOpen"
                                class="text-ferro-ash text-body-sm hover:text-ferro-orange transition-colors w-full {{ $isAr ? 'text-right' : '' }}">
                            {{ $isAr ? 'هل لديك كود خصم؟' : 'Have a promo code?' }}
                        </button>
                        <div x-show="promoOpen" x-transition class="mt-3 flex gap-2">
                            <input type="text" class="input-ferro flex-1 py-2.5 text-sm" placeholder="{{ $isAr ? 'الكود' : 'Code' }}">
                            <button class="btn-secondary px-4 py-2.5 text-xs">{{ $isAr ? 'تطبيق' : 'Apply' }}</button>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="btn-primary w-full clip-luxury-md mb-4">
                        {{ $isAr ? 'إتمام الطلب' : 'Proceed to Checkout' }}
                        <svg class="w-4 h-4 {{ $isAr ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>

                    {{-- Trust signals --}}
                    <div class="flex flex-wrap justify-center gap-x-4 gap-y-2 text-ferro-ash text-[11px] pt-4 border-t border-ferro-carbon">
                        <span class="flex items-center gap-1">🔒 {{ $isAr ? 'دفع آمن' : 'Secure Checkout' }}</span>
                        <span class="flex items-center gap-1">↩️ {{ $isAr ? 'إرجاع مجاني' : 'Free Returns' }}</span>
                        <span class="flex items-center gap-1">🚚 {{ $isAr ? 'شحن مجاني' : 'Free Shipping' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Empty cart state --}}
        <div x-show="items.length === 0" class="text-center py-24" x-cloak>
            <svg class="w-20 h-20 text-ferro-carbon mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.853-7.16a4.5 4.5 0 00-4.244-5.756H5.25a4.5 4.5 0 00-4.244 5.756l1.107 4.15A3 3 0 007.5 14.25z"/>
            </svg>
            <h2 class="font-display text-display-lg text-ferro-white mb-3">
                {{ $isAr ? 'ترسانتك فارغة' : 'Your Arsenal is Empty' }}
            </h2>
            <p class="text-ferro-silver text-body-sm mb-8">
                {{ $isAr ? 'ابدأ باستكشاف منتجات فيرو.' : 'Start exploring the FERRO product lineup.' }}
            </p>
            <a href="{{ route('products.index') }}" class="btn-primary clip-luxury-md">
                {{ $isAr ? 'استكشف المتجر' : 'Explore the Arsenal' }}
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function ferroCart() {
    // Hydrate from localStorage / server session
    const saved = JSON.parse(localStorage.getItem('ferro_cart') || '[]');
    return {
        items: saved,

        get subtotal() {
            return this.items.reduce((sum, i) => sum + (i.price * i.qty), 0);
        },
        get tax() {
            return this.subtotal * 0.05;
        },
        get total() {
            return this.subtotal + this.tax;
        },

        formatPrice(v) {
            const cur = (this.items[0] && this.items[0].currency) || 'EGP';
            const n = Number(v) || 0;
            const u = String(cur).toUpperCase();
            if (u === 'EGP' || u === 'USD' || u === 'LE') return n.toFixed(2) + ' EGP';
            if (u === 'AED') return n.toFixed(2) + ' AED';
            return n.toFixed(2) + ' ' + u;
        },

        updateQty(id, qty) {
            const item = this.items.find(i => i.id === id);
            if (item) {
                item.qty = Math.max(1, Math.min(10, qty));
                this.persist();
            }
        },

        removeItem(id) {
            this.items = this.items.filter(i => i.id !== id);
            this.persist();
        },

        persist() {
            localStorage.setItem('ferro_cart', JSON.stringify(this.items));
            const total = this.items.reduce((s, i) => s + Number(i.qty || 0), 0);
            if (typeof window.ferroSyncCartBadges === 'function') {
                window.ferroSyncCartBadges(total);
            }
        }
    };
}
</script>
@endpush
