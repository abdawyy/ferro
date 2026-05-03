@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('seo_title', $isAr
    ? 'تفاصيل الطلب #' . $order->order_number . ' — فيرو'
    : 'Order #' . $order->order_number . ' — FERRO')

@section('content')

<div class="pt-[72px] min-h-screen">

    {{-- Header --}}
    <div class="bg-ferro-obsidian border-b border-ferro-carbon">
        <div class="container-ferro py-10">
            <div class="flex items-center justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                <div class="{{ $isAr ? 'text-right' : '' }}">
                    <a href="{{ route('account') }}"
                       class="flex items-center gap-2 text-ferro-ash text-body-sm hover:text-ferro-orange transition-colors mb-4 {{ $isAr ? 'flex-row-reverse justify-end' : '' }}">
                        <svg class="w-4 h-4 {{ $isAr ? '' : 'rotate-180' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                        {{ $isAr ? 'العودة إلى حسابي' : 'Back to Account' }}
                    </a>
                    <span class="eyebrow">{{ $isAr ? 'تفاصيل الطلب' : 'Order Details' }}</span>
                    <h1 class="font-display text-display-lg text-ferro-white">
                        {{ $isAr ? 'طلب رقم' : 'Order' }} #{{ $order->order_number }}
                    </h1>
                    <p class="text-ferro-ash text-body-sm mt-1">
                        {{ $isAr ? 'تاريخ الطلب:' : 'Placed on' }} {{ $order->created_at->format('d F Y') }}
                    </p>
                </div>
                <div class="flex flex-col items-end gap-3 {{ $isAr ? 'items-start' : '' }}">
                    <span class="px-4 py-2 text-xs font-semibold tracking-widest uppercase border"
                          style="border-radius: 2px;"
                          @class([
                              'bg-green-500/10 text-green-400 border-green-500/30'   => $order->status === 'delivered',
                              'bg-blue-500/10 text-blue-400 border-blue-500/30'      => $order->status === 'shipped',
                              'bg-yellow-500/10 text-yellow-400 border-yellow-500/30'=> $order->status === 'processing',
                              'bg-ferro-carbon text-ferro-ash border-ferro-carbon'   => $order->status === 'pending',
                          ])>
                        {{ ucfirst($order->status) }}
                    </span>
                    @if($order->invoice_pdf_path)
                        <a href="{{ route('invoices.download', $order->order_number) }}"
                           class="btn-ghost text-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            {{ $isAr ? 'تحميل الفاتورة PDF' : 'Download Invoice PDF' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-ferro section-pad">

        {{-- Order status timeline --}}
        @php
            $statuses    = ['pending', 'processing', 'shipped', 'delivered'];
            $currentStep = array_search($order->status, $statuses) !== false ? array_search($order->status, $statuses) : 0;
        @endphp
        <div class="mb-12 overflow-x-auto">
            <div class="flex items-center min-w-[400px]">
                @foreach($statuses as $i => $status)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-all duration-300
                                    {{ $i <= $currentStep ? 'bg-ferro-orange border-ferro-orange' : 'bg-ferro-carbon border-ferro-carbon' }}">
                            @if($i < $currentStep)
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <span class="w-2 h-2 rounded-full {{ $i === $currentStep ? 'bg-white' : 'bg-ferro-ash' }}"></span>
                            @endif
                        </div>
                        <span class="text-[11px] mt-2 text-center capitalize {{ $i <= $currentStep ? 'text-ferro-white' : 'text-ferro-ash' }}">
                            @if($isAr)
                                {{ ['معلق', 'قيد المعالجة', 'تم الشحن', 'تم التسليم'][$i] }}
                            @else
                                {{ ucfirst($status) }}
                            @endif
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div class="flex-1 h-0.5 {{ $i < $currentStep ? 'bg-ferro-orange' : 'bg-ferro-carbon' }} transition-all duration-300"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Order Items --}}
            <div class="lg:col-span-2">
                <h2 class="font-display text-xl text-ferro-white mb-5 {{ $isAr ? 'text-right' : '' }}">
                    {{ $isAr ? 'المنتجات المطلوبة' : 'Items Ordered' }}
                </h2>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex gap-4 bg-ferro-obsidian border border-ferro-carbon p-4" style="border-radius: 2px;">
                            <div class="w-20 h-24 bg-ferro-carbon flex-shrink-0 overflow-hidden" style="border-radius: 2px;">
                                @if($item->product?->featured_image)
                                    <img src="{{ asset($item->product->featured_image) }}" alt="{{ $item->product_name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-ferro-ash" viewBox="0 0 32 32" fill="currentColor"><path d="M4 4h24v6H12v4h14v6H12v8H4V4z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 {{ $isAr ? 'text-right' : '' }}">
                                <p class="text-ferro-white font-semibold">{{ $item->product_name }}</p>
                                <p class="text-ferro-ash text-xs mt-0.5">{{ $isAr ? 'الكمية:' : 'Qty:' }} {{ $item->quantity }}</p>
                                <p class="text-ferro-silver text-body-sm mt-2">
                                    {{ $item->currency === 'USD' ? '$' : $item->currency }}{{ number_format($item->unit_price, 2) }}
                                    {{ $isAr ? 'للقطعة' : 'each' }}
                                </p>
                            </div>
                            <div class="text-right {{ $isAr ? 'text-left' : '' }}">
                                <p class="text-ferro-white font-semibold">
                                    {{ $item->currency === 'USD' ? '$' : $item->currency }}{{ number_format($item->unit_price * $item->quantity, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Summary + Shipping --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Price breakdown --}}
                <div class="bg-ferro-obsidian border border-ferro-carbon p-6" style="border-radius: 2px;">
                    <h3 class="font-display text-lg text-ferro-white mb-4 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'ملخص الفاتورة' : 'Order Summary' }}
                    </h3>
                    <div class="space-y-2 text-body-sm {{ $isAr ? 'text-right' : '' }}">
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'المجموع الفرعي' : 'Subtotal' }}</span>
                            <span class="text-ferro-white">{{ $order->currency === 'USD' ? '$' : $order->currency }}{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الشحن' : 'Shipping' }}</span>
                            <span class="text-ferro-white">
                                {{ $order->shipping_cost > 0
                                    ? ($order->currency === 'USD' ? '$' : $order->currency) . number_format($order->shipping_cost, 2)
                                    : ($isAr ? 'مجاني' : 'Free') }}
                            </span>
                        </div>
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الضريبة' : 'Tax' }}</span>
                            <span class="text-ferro-white">{{ $order->currency === 'USD' ? '$' : $order->currency }}{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                                <span class="text-green-400">{{ $isAr ? 'الخصم' : 'Discount' }}</span>
                                <span class="text-green-400">−{{ $order->currency === 'USD' ? '$' : $order->currency }}{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-semibold text-base pt-3 border-t border-ferro-carbon {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-white">{{ $isAr ? 'الإجمالي' : 'Total' }}</span>
                            <span class="text-ferro-orange">{{ $order->currency === 'USD' ? '$' : $order->currency }}{{ number_format($order->grand_total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Shipping address --}}
                <div class="bg-ferro-obsidian border border-ferro-carbon p-6" style="border-radius: 2px;">
                    <h3 class="font-display text-lg text-ferro-white mb-3 {{ $isAr ? 'text-right' : '' }}">
                        {{ $isAr ? 'عنوان الشحن' : 'Shipping Address' }}
                    </h3>
                    <address class="text-ferro-silver text-body-sm leading-relaxed not-italic {{ $isAr ? 'text-right' : '' }}">
                        {{ $order->shipping_address['name'] ?? auth()->user()->name }}<br>
                        {{ $order->shipping_address['address'] ?? '' }}<br>
                        {{ $order->shipping_address['city'] ?? '' }},
                        {{ $order->shipping_address['country'] ?? '' }}
                    </address>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
