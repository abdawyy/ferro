{{-- FERRO Footer --}}
<footer class="bg-ferro-black border-t border-ferro-carbon">
    <div class="container-ferro py-16 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            {{-- Brand column --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-5">
                    @if(ferro_storefront_logo_url())
                    <img src="{{ ferro_storefront_logo_url() }}" alt="FERRO" class="h-7 w-auto max-w-[120px] object-contain">
                    @else
                    @include('partials.brand-mark-f', ['class' => 'w-7 h-7 text-ferro-orange'])
                    @endif
                    <span class="font-display text-xl tracking-[0.2em] text-ferro-white uppercase">FERRO</span>
                </a>
                <p class="text-ferro-ash text-body-sm leading-relaxed mb-6">
                    {{ app()->getLocale() === 'ar'
                        ? 'مصنوع من الحديد، مصقول بالرفاهية. عناية ببشرة الرجل عالي الأداء.'
                        : 'Forged from Iron. Polished by Luxury. Grooming for the high-performance man.' }}
                </p>
                {{-- Social links --}}
                <div class="flex flex-wrap items-center gap-3">
                    @include('partials.social-follow-links', ['contactSetting' => $contactSetting, 'variant' => 'footer'])
                </div>
            </div>

            {{-- Shop links --}}
            <div>
                <h3 class="eyebrow">{{ app()->getLocale() === 'ar' ? 'المتجر' : 'Shop' }}</h3>
                <ul class="space-y-3">
                    @foreach([
                        [route('products.index'), app()->getLocale() === 'ar' ? 'جميع المنتجات' : 'All Products'],
                        [route('products.index') . '?status=coming_soon', app()->getLocale() === 'ar' ? 'قريباً' : 'Coming Soon'],
                        [route('products.index') . '?featured=1', app()->getLocale() === 'ar' ? 'الأكثر مبيعاً' : 'Best Sellers'],
                        [route('quiz'), app()->getLocale() === 'ar' ? 'اختبار البشرة' : 'Skin Quiz'],
                    ] as [$url, $label])
                        <li><a href="{{ $url }}" class="text-ferro-ash text-body-sm hover:text-ferro-orange transition-colors duration-200">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Company links --}}
            <div>
                <h3 class="eyebrow">{{ app()->getLocale() === 'ar' ? 'الشركة' : 'Company' }}</h3>
                <ul class="space-y-3">
                    @foreach([
                        [route('about'),   app()->getLocale() === 'ar' ? 'قصتنا' : 'Our Story'],
                        [route('contact'), app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact'],
                    ] as [$url, $label])
                        <li><a href="{{ $url }}" class="text-ferro-ash text-body-sm hover:text-ferro-orange transition-colors duration-200">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- Waitlist CTA column --}}
            <div>
                <h3 class="eyebrow">{{ app()->getLocale() === 'ar' ? 'كن أول من يعرف' : 'Be First to Know' }}</h3>
                <p class="text-ferro-ash text-body-sm mb-5">
                    {{ app()->getLocale() === 'ar'
                        ? 'سجّل بريدك للحصول على وصول حصري عند الإطلاق.'
                        : 'Register for exclusive early access at launch.' }}
                </p>
                @include('partials.waitlist-mini-form')
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="divider pt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-ferro-ash text-xs">
                © {{ date('Y') }} FERRO. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
            </p>
            <div class="flex flex-wrap items-center justify-center sm:justify-end gap-4 sm:gap-6">
                <a href="{{ route('legal.privacy') }}" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                </a>
                <a href="{{ route('legal.terms') }}" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'الشروط والأحكام' : 'Terms of Service' }}
                </a>
                <a href="{{ route('legal.returns') }}" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'سياسة الإرجاع' : 'Return Policy' }}
                </a>
            </div>
        </div>
    </div>
</footer>
