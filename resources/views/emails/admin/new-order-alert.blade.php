@extends('emails._layout', ['locale' => 'en', 'isRtl' => false])

@section('email_title', '[FERRO Admin] New Order #' . $order->order_number)
@section('header_class', 'admin-alert-header')

@section('email_body')

<p class="email-heading">🛒 New Order Received</p>
<p class="email-subheading">{{ $order->created_at->format('d F Y — H:i') }} UTC</p>

{{-- Status + Priority --}}
<div style="margin-bottom: 24px;">
    <span class="status-badge badge-success">ORDER CONFIRMED</span>
    <span style="margin-left: 8px; font-size: 13px; color: #6B6B6B;">#{{ $order->order_number }}</span>
</div>

{{-- Key Metrics Row --}}
<div style="display: flex; gap: 8px; margin-bottom: 24px;">
    @foreach([
        ['label' => 'ORDER VALUE', 'value' => $order->formatted_total],
        ['label' => 'ITEMS',       'value' => $order->items->sum('quantity') . ' item(s)'],
        ['label' => 'PAYMENT',     'value' => ucfirst($order->payment_method ?? 'Card')],
    ] as $metric)
    <div style="flex: 1; padding: 16px; background-color: #0A0A0A; border: 1px solid #2A2A2A; text-align: center; border-radius: 2px;">
        <div style="font-size: 11px; color: #6B6B6B; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 6px;">{{ $metric['label'] }}</div>
        <div style="font-size: 18px; font-weight: 700; color: #E8500A;">{{ $metric['value'] }}</div>
    </div>
    @endforeach
</div>

<hr class="email-divider">

{{-- Customer Info --}}
<div class="info-box">
    <dl style="margin: 0;">
        @foreach([
            ['label' => 'Customer', 'value' => $order->user->name ?? 'Guest', 'bold' => true],
            ['label' => 'Email',    'value' => $order->user->email ?? ($order->shipping_address['email'] ?? 'N/A')],
            ['label' => 'Ship To',  'value' => implode(', ', array_filter([$order->shipping_address['address'] ?? null, $order->shipping_address['city'] ?? null, $order->shipping_address['country'] ?? null]))],
        ] as $row)
        <div style="display: flex; gap: 12px; padding: 4px 0;">
            <dt style="min-width: 120px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $row['label'] }}</dt>
            <dd style="margin: 0; font-size: 13px; color: #F5F2EE; {{ isset($row['bold']) ? 'font-weight: 600;' : '' }}">{{ $row['value'] }}</dd>
        </div>
        @endforeach
        <div style="display: flex; gap: 12px; padding: 4px 0;">
            <dt style="min-width: 120px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">Language</dt>
            <dd style="margin: 0;"><span class="status-badge badge-info">{{ strtoupper($order->language ?? 'EN') }}</span></dd>
        </div>
    </dl>
</div>

{{-- Order Items Table --}}
<h3 style="font-size: 14px; font-weight: 600; color: #FFFFFF; margin-bottom: 12px; margin-top: 24px;">Order Line Items</h3>
<table class="order-table">
    <thead>
        <tr>
            <th>Product</th>
            <th>SKU</th>
            <th style="text-align: center;">Qty</th>
            <th style="text-align: right;">Unit Price</th>
            <th style="text-align: right;">Line Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="font-weight: 500;">{{ $item->product_name }}</td>
            <td style="color: #6B6B6B; font-size: 11px; font-family: monospace;">{{ $item->product?->sku ?? '—' }}</td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td style="text-align: right;">{{ ferro_money($item->unit_price, $order->currency) }}</td>
            <td style="text-align: right; font-weight: 600;">{{ ferro_money($item->unit_price * $item->quantity, $order->currency) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align: right; color: #6B6B6B; font-size: 12px; padding: 8px 14px; border-top: 1px solid #2A2A2A;">Subtotal</td>
            <td style="text-align: right; padding: 8px 14px; border-top: 1px solid #2A2A2A;">{{ ferro_money($order->subtotal, $order->currency) }}</td>
        </tr>
        @if((float) $order->discount_amount > 0)
        <tr>
            <td colspan="4" style="text-align: right; color: #22C55E; font-size: 12px; padding: 4px 14px;">Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
            <td style="text-align: right; padding: 4px 14px; color: #22C55E;">−{{ ferro_money($order->discount_amount, $order->currency) }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="4" style="text-align: right; color: #6B6B6B; font-size: 12px; padding: 4px 14px;">Shipping</td>
            <td style="text-align: right; padding: 4px 14px;">{{ (float) $order->shipping_amount > 0 ? ferro_money($order->shipping_amount, $order->currency) : 'Free' }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right; color: #6B6B6B; font-size: 12px; padding: 4px 14px;">Tax ({{ number_format((float) ($order->tax_rate ?? 0) * 100, 0) }}%)</td>
            <td style="text-align: right; padding: 4px 14px;">{{ ferro_money($order->tax_amount, $order->currency) }}</td>
        </tr>
        <tr class="total-row">
            <td colspan="4" style="text-align: right; padding: 12px 14px; font-size: 13px;">TOTAL</td>
            <td style="text-align: right; padding: 12px 14px;" class="grand-total">{{ ferro_money($order->total, $order->currency) }}</td>
        </tr>
    </tfoot>
</table>

<hr class="email-divider">

{{-- Action CTA --}}
<div class="email-btn-center">
    <a href="{{ url('/admin/orders/' . $order->id) }}" class="email-btn">View in Admin Dashboard</a>
</div>

<p class="email-text" style="text-align: center; font-size: 11px;">
    This is an automated notification from the FERRO order management system.
</p>

@endsection
