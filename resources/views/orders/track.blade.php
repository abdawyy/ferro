@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('orders_track', ['order_number' => $order->order_number]);
@endphp

@section('seo_title', $seo['title'])
@section('seo_description', $seo['description'])
@section('seo_keywords', $seo['keywords'])
@section('og_title', $seo['og_title'])
@section('og_description', $seo['og_description'])

@section('content')
<div class="pt-[72px] min-h-screen">
    <div class="bg-ferro-obsidian border-b border-ferro-carbon">
        <div class="container-ferro py-10">
            <div class="{{ $isAr ? 'text-right' : '' }}">
                <p class="eyebrow text-ferro-orange mb-2">{{ $isAr ? 'تتبع الطلب' : 'Order tracking' }}</p>
                <h1 class="font-display text-display-lg text-ferro-white">#{{ $order->order_number }}</h1>
                <p class="text-ferro-ash text-body-sm mt-2">
                    {{ $isAr ? 'تاريخ الطلب:' : 'Placed on' }} {{ $order->created_at->format('d F Y') }}
                </p>
            </div>
        </div>
    </div>

    <div class="container-ferro section-pad max-w-3xl">
        <div class="bg-ferro-obsidian border border-ferro-carbon p-6 mb-8" style="border-radius: 2px;">
            <p class="text-ferro-ash text-xs uppercase tracking-widest mb-2">{{ $isAr ? 'الحالة' : 'Current status' }}</p>
            <p class="text-ferro-white text-lg font-semibold">{{ $order->status_label }}</p>
            @if($order->tracking_number)
                <p class="text-ferro-silver text-body-sm mt-4 font-mono">{{ $isAr ? 'رقم التتبع:' : 'Tracking:' }} {{ $order->tracking_number }}</p>
            @endif
            @if($order->carrier)
                <p class="text-ferro-ash text-sm mt-1">{{ $isAr ? 'الشاحن:' : 'Carrier:' }} {{ $order->carrier }}</p>
            @endif
        </div>

        @auth
            <p class="text-ferro-ash text-body-sm mb-4">
                <a href="{{ route('orders.show', $order->order_number) }}" class="text-ferro-orange hover:underline">
                    {{ $isAr ? 'عرض التفاصيل الكاملة في حسابي' : 'View full details in my account' }}
                </a>
            </p>
        @else
            <p class="text-ferro-ash text-body-sm mb-4">
                {{ $isAr
                    ? 'لإدارة الطلبات والمرتجعات، سجّل الدخول بنفس البريد المستخدم عند الشراء.'
                    : 'Sign in with the email you used at checkout to manage orders and returns.' }}
                <a href="{{ route('login') }}" class="text-ferro-orange hover:underline">{{ $isAr ? 'تسجيل الدخول' : 'Log in' }}</a>
            </p>
        @endauth
    </div>
</div>
@endsection
