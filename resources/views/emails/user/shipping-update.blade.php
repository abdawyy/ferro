@extends('emails._layout')

@section('email_title', $isRtl ? 'تم شحن طلبك — FERRO' : 'Your Order Has Shipped — FERRO')

@section('email_body')

@php
    $t = [
        'headline'      => $isRtl ? 'طلبك في الطريق إليك!' : 'Your Order Is On Its Way!',
        'subheadline'   => $isRtl ? 'تم تسليم طلبك إلى شركة الشحن وجاري توصيله.' : 'Your order has been handed to the carrier and is on its way.',
        'order_num'     => $isRtl ? 'رقم الطلب' : 'Order',
        'tracking_num'  => $isRtl ? 'رقم التتبع' : 'Tracking Number',
        'carrier'       => $isRtl ? 'شركة الشحن' : 'Carrier',
        'est_delivery'  => $isRtl ? 'التسليم المتوقع' : 'Estimated Delivery',
        'ship_to'       => $isRtl ? 'عنوان الشحن' : 'Shipping To',
        'items_heading' => $isRtl ? 'المنتجات المشحونة' : 'Items Shipped',
        'track_cta'     => $isRtl ? 'تتبع شحنتك' : 'Track Your Shipment',
        'questions'     => $isRtl ? 'هل لديك أسئلة؟ ' : 'Questions? ',
        'contact_us'    => $isRtl ? 'تواصل معنا' : 'Contact us',
    ];
    $align = $isRtl ? 'right' : 'left';

    $trackingNumber  = $order->tracking_number ?? null;
    $carrier         = $order->carrier ?? null;
    $estimatedDate   = $order->estimated_delivery_at
        ? \Carbon\Carbon::parse($order->estimated_delivery_at)->format('d F Y')
        : null;
    $trackingUrl     = $order->tracking_url ?? null;
@endphp

{{-- Hero Shipping Illustration --}}
<div style="text-align: center; padding: 32px 0 24px;">
    <div style="display: inline-block; width: 72px; height: 72px; background-color: rgba(232,80,10,0.1); border-radius: 50%; line-height: 72px; font-size: 36px;">
        🚚
    </div>
</div>

<p class="email-heading" style="text-align: center;">{{ $t['headline'] }}</p>
<p class="email-subheading" style="text-align: center;">{{ $t['subheadline'] }}</p>

{{-- Tracking Info Card --}}
<div class="info-box" style="margin: 24px 0;">
    @if($trackingNumber)
    <div style="text-align: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #2A2A2A;">
        <div style="font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">{{ $t['tracking_num'] }}</div>
        <div style="font-size: 20px; font-weight: 700; color: #E8500A; font-family: monospace; letter-spacing: 0.12em;">{{ $trackingNumber }}</div>
    </div>
    @endif

    @foreach(array_filter([
        ['label' => $t['order_num'],    'value' => '#' . $order->order_number],
        ['label' => $t['carrier'],      'value' => $carrier],
        ['label' => $t['est_delivery'], 'value' => $estimatedDate],
        ['label' => $t['ship_to'],      'value' => implode(', ', array_filter([
            $order->shipping_address['name']    ?? null,
            $order->shipping_address['address'] ?? null,
            $order->shipping_address['city']    ?? null,
            $order->shipping_address['country'] ?? null,
        ]))],
    ], fn($row) => !empty($row['value'])) as $row)
    <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
        <span style="min-width: 150px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $row['label'] }}</span>
        <span style="font-size: 13px; color: #F5F2EE; font-weight: 500;">{{ $row['value'] }}</span>
    </div>
    @endforeach
</div>

{{-- Shipped Items --}}
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.08em;">
    {{ $t['items_heading'] }}
</h3>
<table class="order-table">
    <thead>
        <tr>
            <th style="text-align: {{ $align }};">{{ $isRtl ? 'المنتج' : 'Product' }}</th>
            <th style="text-align: center;">{{ $isRtl ? 'الكمية' : 'Qty' }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="text-align: {{ $align }}; font-weight: 500;">{{ $item->product_name }}</td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<hr class="email-divider">

<div class="email-btn-center">
    <a href="{{ $trackingUrl ?? route('orders.show', $order->id) }}" class="email-btn">{{ $t['track_cta'] }}</a>
</div>

<p class="email-text" style="text-align: center; margin-top: 16px;">
    {{ $t['questions'] }}<a href="{{ route('contact') }}" style="color: #E8500A; text-decoration: none;">{{ $t['contact_us'] }}</a>
</p>

@endsection
