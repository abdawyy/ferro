@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr ? 'شكراً لطلبك — فيرو' : 'Thank You — FERRO')

@section('content')
<div class="pt-[72px] min-h-screen bg-ferro-black">
    <div class="container-ferro section-pad max-w-2xl mx-auto {{ $isAr ? 'text-right' : '' }}">
        <p class="eyebrow text-ferro-orange mb-3">{{ $isAr ? 'تم الاستلام' : 'Order received' }}</p>
        <h1 class="font-display text-3xl md:text-4xl text-ferro-white mb-4">
            {{ $isAr ? 'شكراً لثقتك بفيرو' : 'Thank you for choosing FERRO' }}
        </h1>
        <p class="text-ferro-silver text-body-sm mb-8">
            {{ $isAr
                ? 'أرسلنا تأكيداً إلى بريدك الإلكتروني مع تفاصيل الطلب.'
                : 'We sent a confirmation email with your order details and receipt.' }}
        </p>
        <div class="bg-ferro-obsidian border border-ferro-carbon p-6 mb-8" style="border-radius: 2px;">
            <p class="text-ferro-ash text-xs uppercase tracking-widest mb-1">{{ $isAr ? 'رقم الطلب' : 'Order number' }}</p>
            <p class="font-mono text-ferro-orange text-xl">{{ $order->order_number }}</p>
            <p class="text-ferro-silver text-sm mt-4">
                {{ $isAr ? 'الإجمالي' : 'Total' }}:
                <span class="text-ferro-white font-semibold">{{ ferro_money($order->total, $order->currency) }}</span>
                {{ strtoupper($order->currency) }}
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 {{ $isAr ? 'sm:flex-row-reverse' : '' }}">
            <a href="{{ route('products.index') }}" class="btn-primary clip-luxury-md text-center">
                {{ $isAr ? 'متابعة التسوق' : 'Continue shopping' }}
            </a>
            @auth
            <a href="{{ route('account') }}" class="btn-secondary clip-luxury-md text-center">
                {{ $isAr ? 'حسابي' : 'My account' }}
            </a>
            @endauth
        </div>
    </div>
</div>
@endsection
