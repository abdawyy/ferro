@extends('layouts.app')

@php
    $isAr = app()->getLocale() === 'ar';
    $seo = ferro_storefront_seo('account_order', ['order_number' => $order->order_number]);
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
                              'bg-red-500/10 text-red-300 border-red-500/30'       => in_array($order->status, ['cancelled', 'refunded'], true),
                              'bg-ferro-carbon text-ferro-ash border-ferro-carbon'   => in_array($order->status, ['pending_payment', 'confirmed'], true),
                          ])>
                        {{ $order->status_label }}
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

        @if(session('success'))
            <div class="mb-6 p-4 border border-green-500/30 bg-green-500/5 text-green-200 text-body-sm rounded-sm"
                 role="status">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 border border-red-500/30 bg-red-500/5 text-red-200 text-body-sm rounded-sm"
                 role="alert">{{ session('error') }}</div>
        @endif

        {{-- Order status timeline --}}
        @php
            $terminal = in_array($order->status, ['cancelled', 'refunded'], true);
            $stepMap = [
                'pending_payment' => 0,
                'confirmed' => 0,
                'processing' => 1,
                'shipped' => 2,
                'delivered' => 3,
            ];
            $statuses = ['confirmed', 'processing', 'shipped', 'delivered'];
            $labelsEn = ['Confirmed', 'Processing', 'Shipped', 'Delivered'];
            $labelsAr = ['مؤكد', 'قيد المعالجة', 'تم الشحن', 'تم التسليم'];
            $currentStep = $terminal ? -1 : ($stepMap[$order->status] ?? 0);
        @endphp
        @if($terminal)
            <div class="mb-12 p-5 border border-ferro-carbon bg-ferro-obsidian text-ferro-silver text-body-sm rounded-sm {{ $isAr ? 'text-right' : '' }}">
                {{ $isAr
                    ? 'هذا الطلب أُغلق ولا يخضع لمخطط التتبع القياسي.'
                    : 'This order is closed and is not shown on the standard fulfillment timeline.' }}
            </div>
        @else
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
                            {{ $isAr ? ($labelsAr[$i] ?? $status) : ($labelsEn[$i] ?? $status) }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <div class="flex-1 h-0.5 {{ $i < $currentStep ? 'bg-ferro-orange' : 'bg-ferro-carbon' }} transition-all duration-300"></div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        @php
            $canCancel = in_array($order->status, [\App\Models\Order::STATUS_PENDING, \App\Models\Order::STATUS_CONFIRMED, \App\Models\Order::STATUS_PROCESSING], true);
            $canReturn = $order->status === \App\Models\Order::STATUS_DELIVERED;
        @endphp

        @if($canCancel || $canReturn || $order->returnRequests->isNotEmpty())
        <div class="mb-10 space-y-6 {{ $isAr ? 'text-right' : '' }}">
            @if($canCancel)
                <form method="POST" action="{{ route('orders.cancel', $order->order_number) }}"
                      onsubmit="return confirm({{ json_encode($isAr ? 'إلغاء هذا الطلب؟ لا يمكن التراجع.' : 'Cancel this order? This cannot be undone.') }});"
                      class="flex flex-wrap items-center gap-4 {{ $isAr ? 'flex-row-reverse' : '' }}">
                    @csrf
                    <p class="text-ferro-ash text-body-sm flex-1 min-w-[200px]">
                        {{ $isAr
                            ? 'يمكنك إلغاء الطلب قبل الشحن. بعد الإلغاء سيصلك بريد بتأكيد الحالة.'
                            : 'You can cancel before the order ships. We will email you to confirm the cancellation.' }}
                    </p>
                    <button type="submit" class="btn-secondary clip-luxury-md text-xs">
                        {{ $isAr ? 'إلغاء الطلب' : 'Cancel order' }}
                    </button>
                </form>
            @endif

            @if($canReturn)
                <div class="border border-ferro-carbon bg-ferro-obsidian p-6 rounded-sm">
                    <h2 class="font-display text-lg text-ferro-white mb-3">{{ $isAr ? 'طلب إرجاع' : 'Request a return' }}</h2>
                    <p class="text-ferro-ash text-body-sm mb-4">
                        {{ $isAr
                            ? 'صف سبب الإرجاع. سيراجع الفريق طلبك ويتواصل معك عبر البريد.'
                            : 'Describe why you would like to return this order. Our team will review and follow up by email.' }}
                    </p>
                    <form method="POST" action="{{ route('orders.return-request', $order->order_number) }}" class="space-y-4">
                        @csrf
                        <textarea name="customer_reason" rows="4" required
                                  class="w-full bg-ferro-black border border-ferro-carbon text-ferro-white text-body-sm p-3 rounded-sm"
                                  placeholder="{{ $isAr ? 'السبب…' : 'Reason for return…' }}">{{ old('customer_reason') }}</textarea>
                        <p class="text-ferro-ash text-xs">
                            {{ $isAr ? 'تعرّف على' : 'See our' }}
                            <a href="{{ route('legal.returns') }}" class="text-ferro-orange hover:underline">{{ $isAr ? 'سياسة الإرجاع' : 'return policy' }}</a>.
                        </p>
                        <button type="submit" class="btn-primary clip-luxury-md text-sm">
                            {{ $isAr ? 'إرسال الطلب' : 'Submit return request' }}
                        </button>
                    </form>
                </div>
            @endif

            @if($order->returnRequests->isNotEmpty())
                <div>
                    <h3 class="text-ferro-white font-display text-base mb-3">{{ $isAr ? 'طلبات الإرجاع' : 'Return requests' }}</h3>
                    <ul class="space-y-3">
                        @foreach($order->returnRequests as $req)
                            @php
                                $isDenied = $req->status === \App\Models\OrderReturnRequest::STATUS_REJECTED;
                                $statusLabel = match ($req->status) {
                                    \App\Models\OrderReturnRequest::STATUS_PENDING => $isAr ? 'قيد المراجعة' : 'Pending review',
                                    \App\Models\OrderReturnRequest::STATUS_APPROVED => $isAr ? 'تمت الموافقة' : 'Approved',
                                    \App\Models\OrderReturnRequest::STATUS_REJECTED => $isAr ? 'مرفوض' : 'Denied',
                                    \App\Models\OrderReturnRequest::STATUS_COMPLETED => $isAr ? 'مكتمل' : 'Completed',
                                    default => $req->status,
                                };
                            @endphp
                            <li @class([
                                'border p-4 rounded-sm text-body-sm',
                                'border-red-500/40 bg-red-500/5 text-ferro-silver' => $isDenied,
                                'border-ferro-carbon text-ferro-silver' => ! $isDenied,
                            ])>
                                <span @class([
                                    'uppercase text-xs tracking-widest',
                                    'text-red-400' => $isDenied,
                                    'text-ferro-orange' => ! $isDenied,
                                ])>{{ $statusLabel }}</span>
                                <span class="text-ferro-ash text-xs mx-2">·</span>
                                {{ $req->created_at->format('d M Y') }}
                                <p class="text-ferro-silver mt-2">{{ $req->customer_reason }}</p>
                                @if($isDenied)
                                    <p class="text-red-300/90 text-body-sm mt-3 font-medium">
                                        {{ $isAr
                                            ? 'لم يُعتمد طلب الإرجاع. يمكنك مراجعة السبب أدناه أو التواصل مع الدعم.'
                                            : 'This return request was not approved. See the note below or contact support if you have questions.' }}
                                    </p>
                                @endif
                                @if($req->admin_notes)
                                    <p class="text-ferro-ash text-xs mt-2">
                                        <span class="font-semibold text-ferro-silver">{{ $isAr ? 'ملاحظات فريق فيرو:' : 'FERRO team note:' }}</span>
                                        {{ $req->admin_notes }}
                                    </p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        @endif

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
                                    <img src="{{ ferro_public_url($item->product->featured_image) }}" alt="{{ $item->product_name }}"
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
                                    {{ ferro_money($item->unit_price, $item->currency ?? $order->currency) }}
                                    {{ $isAr ? 'للقطعة' : 'each' }}
                                </p>
                            </div>
                            <div class="text-right {{ $isAr ? 'text-left' : '' }}">
                                <p class="text-ferro-white font-semibold">
                                    {{ ferro_money($item->line_total, $order->currency) }}
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
                            <span class="text-ferro-white">{{ ferro_money($order->subtotal, $order->currency) }}</span>
                        </div>
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الشحن' : 'Shipping' }}</span>
                            <span class="text-ferro-white">
                                {{ (float) $order->shipping_amount > 0
                                    ? ferro_money($order->shipping_amount, $order->currency)
                                    : ($isAr ? 'مجاني' : 'Free') }}
                            </span>
                        </div>
                        <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-silver">{{ $isAr ? 'الضريبة' : 'Tax' }}</span>
                            <span class="text-ferro-white">{{ ferro_money($order->tax_amount, $order->currency) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between {{ $isAr ? 'flex-row-reverse' : '' }}">
                                <span class="text-green-400">{{ $isAr ? 'الخصم' : 'Discount' }}</span>
                                <span class="text-green-400">−{{ ferro_money($order->discount_amount, $order->currency) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-semibold text-base pt-3 border-t border-ferro-carbon {{ $isAr ? 'flex-row-reverse' : '' }}">
                            <span class="text-ferro-white">{{ $isAr ? 'الإجمالي' : 'Total' }}</span>
                            <span class="text-ferro-orange">{{ ferro_money($order->total, $order->currency) }}</span>
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
