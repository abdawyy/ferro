@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('account_index');
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')

<div class="pt-[72px] min-h-screen">

    {{-- Header --}}
    <div class="bg-ferro-obsidian border-b border-ferro-carbon">
        <div class="container-ferro py-12">
            <div class="flex items-center gap-5 {{ $isAr ? 'flex-row-reverse text-right' : '' }}">
                <div class="w-16 h-16 bg-ferro-orange/10 border border-ferro-orange/20 flex items-center justify-center" style="border-radius: 2px;">
                    <span class="font-display text-2xl text-ferro-orange">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <span class="eyebrow">{{ $isAr ? 'مرحباً بعودتك' : 'Welcome back' }}</span>
                    <h1 class="font-display text-display-lg text-ferro-white">{{ auth()->user()->name }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-ferro section-pad">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

            {{-- ── Sidebar Nav ──────────────────────────────────────── --}}
            <aside class="lg:col-span-1">
                <nav class="space-y-1" aria-label="{{ $isAr ? 'قائمة الحساب' : 'Account navigation' }}">
                    @foreach([
                        ['id' => 'orders',        'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z', 'label_en' => 'Orders',        'label_ar' => 'طلباتي'],
                        ['id' => 'subscriptions', 'icon' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99', 'label_en' => 'Subscriptions', 'label_ar' => 'اشتراكاتي'],
                        ['id' => 'profile',       'icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z', 'label_en' => 'Profile',        'label_ar' => 'ملفي الشخصي'],
                        ['id' => 'waitlist',      'icon' => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0', 'label_en' => 'Waitlist',       'label_ar' => 'قائمة الانتظار'],
                    ] as $nav)
                        <button
                            onclick="showTab('{{ $nav['id'] }}')"
                            id="nav-{{ $nav['id'] }}"
                            class="account-nav-btn w-full flex items-center gap-3 px-4 py-3 text-body-sm transition-all duration-200 {{ $isAr ? 'flex-row-reverse text-right' : '' }}"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nav['icon'] }}"/>
                            </svg>
                            {{ $isAr ? $nav['label_ar'] : $nav['label_en'] }}
                        </button>
                    @endforeach

                    <div class="pt-4 border-t border-ferro-carbon mt-4">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            @method('POST')
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-body-sm text-ferro-ash hover:text-red-400 transition-colors duration-200 {{ $isAr ? 'flex-row-reverse' : '' }}">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                </svg>
                                {{ $isAr ? 'تسجيل الخروج' : 'Sign Out' }}
                            </button>
                        </form>
                    </div>
                </nav>
            </aside>

            {{-- ── Main Content ─────────────────────────────────────── --}}
            <div class="lg:col-span-3">

                {{-- Orders Tab --}}
                <div id="tab-orders" class="account-tab">
                    <h2 class="font-display text-2xl text-ferro-white mb-6 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'طلباتي' : 'My Orders' }}
                    </h2>

                    @if($orders->isEmpty())
                        <div class="text-center py-16 card-glass">
                            <svg class="w-12 h-12 text-ferro-carbon mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08"/>
                            </svg>
                            <p class="text-ferro-silver text-body-sm mb-4">{{ $isAr ? 'لا توجد طلبات بعد' : 'No orders yet' }}</p>
                            <a href="{{ route('products.index') }}" class="btn-primary">
                                {{ $isAr ? 'ابدأ التسوق' : 'Start Shopping' }}
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($orders as $order)
                                <div class="bg-ferro-obsidian border border-ferro-carbon p-5 hover:border-ferro-carbon/80 transition-all" style="border-radius: 2px;">
                                    <div class="flex items-start justify-between gap-4 {{ $isAr ? 'flex-row-reverse' : '' }}">
                                        <div class="{{ $isAr ? 'text-right' : '' }}">
                                            <p class="text-ferro-white font-semibold text-body-sm">
                                                {{ $isAr ? 'طلب رقم' : 'Order' }} #{{ $order->order_number }}
                                            </p>
                                            <p class="text-ferro-ash text-xs mt-0.5">
                                                {{ $order->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3 {{ $isAr ? 'flex-row-reverse' : '' }}">
                                            <span class="px-3 py-1 text-[11px] font-semibold tracking-widest uppercase border"
                                                  style="border-radius: 2px;"
                                                  :class="{
                                                    'bg-green-500/10 text-green-400 border-green-500/30': '{{ $order->status }}' === 'delivered',
                                                    'bg-blue-500/10 text-blue-400 border-blue-500/30': '{{ $order->status }}' === 'shipped',
                                                    'bg-yellow-500/10 text-yellow-400 border-yellow-500/30': '{{ $order->status }}' === 'processing',
                                                    'bg-ferro-carbon text-ferro-ash border-ferro-carbon': '{{ $order->status }}' === 'pending',
                                                  }">
                                                {{ $order->status }}
                                            </span>
                                            <span class="text-ferro-white font-semibold">{{ $order->formatted_total }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-ferro-carbon {{ $isAr ? 'flex-row-reverse' : '' }}">
                                        <p class="text-ferro-ash text-xs">
                                            {{ $order->items_count }} {{ $isAr ? 'منتج' : ($order->items_count === 1 ? 'item' : 'items') }}
                                        </p>
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('orders.show', $order->order_number) }}" class="text-ferro-orange text-xs hover:underline">
                                                {{ $isAr ? 'تفاصيل' : 'View Details' }}
                                            </a>
                                            @if($order->invoice_pdf_path)
                                                <a href="{{ route('invoices.download', $order->order_number) }}" class="text-ferro-ash text-xs hover:text-ferro-silver transition-colors flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                                    </svg>
                                                    {{ $isAr ? 'الفاتورة' : 'Invoice' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Subscriptions Tab --}}
                <div id="tab-subscriptions" class="account-tab hidden">
                    <h2 class="font-display text-2xl text-ferro-white mb-6 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'اشتراكاتي' : 'My Subscriptions' }}
                    </h2>
                    <div class="text-center py-16 card-glass">
                        <svg class="w-12 h-12 text-ferro-carbon mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                        <p class="text-ferro-silver text-body-sm mb-4">{{ $isAr ? 'لا توجد اشتراكات نشطة' : 'No active subscriptions' }}</p>
                        <p class="text-ferro-ash text-xs mb-6">{{ $isAr ? 'فعّل اشتراكك ووفّر 15% على كل طلب' : 'Activate Subscribe & Save to get 15% off every order' }}</p>
                        <a href="{{ route('products.index') }}" class="btn-primary">
                            {{ $isAr ? 'استكشف المنتجات' : 'Explore Products' }}
                        </a>
                    </div>
                </div>

                {{-- Profile Tab --}}
                <div id="tab-profile" class="account-tab hidden">
                    <h2 class="font-display text-2xl text-ferro-white mb-6 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'ملفي الشخصي' : 'My Profile' }}
                    </h2>
                    <form class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="form-label" for="profile-name">{{ $isAr ? 'الاسم الكامل' : 'Full Name' }}</label>
                                <input type="text" id="profile-name" class="input-ferro" value="{{ auth()->user()->name }}" autocomplete="name">
                            </div>
                            <div>
                                <label class="form-label" for="profile-email">{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</label>
                                <input type="email" id="profile-email" class="input-ferro" value="{{ auth()->user()->email }}" autocomplete="email">
                            </div>
                        </div>
                        <div>
                            <label class="form-label" for="profile-phone">{{ $isAr ? 'رقم الهاتف' : 'Phone' }}</label>
                            <input type="tel" id="profile-phone" class="input-ferro" value="{{ auth()->user()->phone ?? '' }}" autocomplete="tel">
                        </div>
                        <div>
                            <label class="form-label" for="profile-lang">{{ $isAr ? 'اللغة المفضلة' : 'Preferred Language' }}</label>
                            <select id="profile-lang" class="input-ferro">
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>العربية</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary clip-luxury-sm">
                            {{ $isAr ? 'حفظ التغييرات' : 'Save Changes' }}
                        </button>
                    </form>
                </div>

                {{-- Waitlist Tab --}}
                <div id="tab-waitlist" class="account-tab hidden">
                    <h2 class="font-display text-2xl text-ferro-white mb-6 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'قائمة الانتظار' : 'My Waitlist' }}
                    </h2>
                    @if(isset($waitlistItems) && $waitlistItems->count())
                        <div class="space-y-4">
                            @foreach($waitlistItems as $lead)
                                <div class="bg-ferro-obsidian border border-ferro-carbon p-5 flex items-center justify-between gap-4 {{ $isAr ? 'flex-row-reverse' : '' }}" style="border-radius: 2px;">
                                    <div class="{{ $isAr ? 'text-right' : '' }}">
                                        <p class="text-ferro-white text-body-sm font-medium">
                                            {{ optional($lead->product)->name ?? 'General Launch' }}
                                        </p>
                                        <p class="text-ferro-ash text-xs mt-0.5">
                                            {{ $isAr ? 'مسجّل منذ' : 'Registered' }} {{ $lead->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="badge-coming-soon">{{ $isAr ? 'في القائمة' : 'On List' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 card-glass">
                            <p class="text-ferro-silver text-body-sm">{{ $isAr ? 'أنت لست مسجلاً في أي قائمة انتظار' : "You're not on any waitlist yet" }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showTab(id) {
    document.querySelectorAll('.account-tab').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.account-nav-btn').forEach(el => {
        el.style.color = '';
        el.style.background = '';
    });
    document.getElementById('tab-' + id)?.classList.remove('hidden');
    const btn = document.getElementById('nav-' + id);
    if (btn) { btn.style.color = 'var(--ferro-orange)'; }
}
// Activate first tab on load
showTab('orders');
</script>
@endpush
