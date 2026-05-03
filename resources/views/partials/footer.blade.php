{{-- FERRO Footer --}}
<footer class="bg-ferro-black border-t border-ferro-carbon">
    <div class="container-ferro py-16 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            {{-- Brand column --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-5">
                    <svg class="w-7 h-7 text-ferro-orange" viewBox="0 0 32 32" fill="none">
                        <path d="M4 4h24v6H12v4h14v6H12v8H4V4z" fill="currentColor"/>
                    </svg>
                    <span class="font-display text-xl tracking-[0.2em] text-ferro-white uppercase">FERRO</span>
                </a>
                <p class="text-ferro-ash text-body-sm leading-relaxed mb-6">
                    {{ app()->getLocale() === 'ar'
                        ? 'مصنوع من الحديد، مصقول بالرفاهية. عناية ببشرة الرجل عالي الأداء.'
                        : 'Forged from Iron. Polished by Luxury. Grooming for the high-performance man.' }}
                </p>
                {{-- Social links --}}
                <div class="flex items-center gap-3">
                    <a href="https://instagram.com/ferrogrooming" target="_blank" rel="noopener" class="btn-icon w-9 h-9" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="https://tiktok.com/@ferrogrooming" target="_blank" rel="noopener" class="btn-icon w-9 h-9" aria-label="TikTok">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.79 1.54V6.77a4.85 4.85 0 01-1.02-.08z"/></svg>
                    </a>
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
                        ['#', app()->getLocale() === 'ar' ? 'الشراكات' : 'Partnerships'],
                        ['#', app()->getLocale() === 'ar' ? 'العمل معنا' : 'Careers'],
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
            <div class="flex items-center gap-6">
                <a href="#" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                </a>
                <a href="#" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'الشروط والأحكام' : 'Terms of Service' }}
                </a>
                <a href="#" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors">
                    {{ app()->getLocale() === 'ar' ? 'سياسة الإرجاع' : 'Return Policy' }}
                </a>
            </div>
        </div>
    </div>
</footer>
