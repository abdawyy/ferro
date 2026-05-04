<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>FERRO Invoice {{ $order->invoice_number }}</title>
    <style>
        body { margin: 0; padding: 24px; font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1A1A1A; line-height: 1.45; }
        .muted { color: #6B6B6B; }
        .orange { color: #E8500A; }
        table.meta { width: 100%; background: #F5F2EE; border-left: 3px solid #E8500A; margin-bottom: 22px; border-collapse: collapse; }
        table.meta td { padding: 6px 10px; font-size: 10px; vertical-align: top; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        table.items th { background: #0A0A0A; color: #fff; font-size: 9px; padding: 9px 10px; text-align: center; font-weight: bold; }
        table.items td { padding: 9px 10px; font-size: 10px; border-bottom: 1px solid #E8E8E8; vertical-align: top; }
        table.items td.num { text-align: center; font-family: DejaVu Sans, DejaVu Sans Mono, monospace; }
        table.items tr:nth-child(even) td { background: #FAFAFA; }
        table.totals { width: 48%; margin-left: auto; border-collapse: collapse; }
        table.totals td { padding: 5px 0; font-size: 10px; border-bottom: 1px solid #E8E8E8; }
        .item-sku { font-size: 9px; color: #6B6B6B; margin-top: 2px; }
    </style>
</head>
<body>

@php
    $billing = $order->billing_address ?? [];
    $shipping = $order->shipping_address ?? [];
@endphp

<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;border-bottom:2px solid #E8500A;padding-bottom:16px;">
    <tr valign="top">
        <td width="48%" align="left">
            <div style="font-size:26px;font-weight:bold;color:#0A0A0A;">
                <span style="display:inline-block;width:8px;height:8px;background:#E8500A;margin-right:6px;vertical-align:middle;"></span>FERRO
            </div>
            <div class="muted" style="font-size:9px;margin-top:4px;letter-spacing:0.06em;">{{ $brandTagline }}</div>
        </td>
        <td width="52%" align="right">
            <div style="font-size:20px;font-weight:bold;color:#E8500A;letter-spacing:0.06em;">{{ $invoiceLabel }}</div>
            <div style="margin-top:4px;font-weight:bold;font-size:13px;">#{{ $order->invoice_number }}</div>
            <div class="muted" style="margin-top:2px;font-size:10px;">{{ $generatedAt }}</div>
        </td>
    </tr>
</table>

<table class="meta" cellspacing="0" cellpadding="0">
    <tr><td width="36%" class="muted">{{ $orderNumberLabel }}</td><td style="font-weight:bold;">{{ $order->order_number }}</td></tr>
    <tr><td class="muted">{{ $orderDateLabel }}</td><td style="font-weight:bold;">{{ $orderDateFormatted }}</td></tr>
    <tr><td class="muted">{{ $paymentMethodLabel }}</td><td style="font-weight:bold;">{{ $paymentMethodDisplay }}</td></tr>
    <tr><td class="muted">{{ $paymentStatusLabel }}</td><td style="font-weight:bold;" class="orange">{{ $paymentStatusDisplay }}</td></tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:22px;">
    <tr valign="top">
        <td width="48%" style="padding-right:12px;">
            <div style="font-size:9px;font-weight:bold;color:#E8500A;border-bottom:1px solid #E8500A;padding-bottom:4px;margin-bottom:8px;">{{ $billingLabel }}</div>
            <div style="font-weight:bold;font-size:12px;">{{ trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '')) }}</div>
            <div>{{ $billing['address_line1'] ?? '' }}</div>
            @if(!empty($billing['address_line2']))<div>{{ $billing['address_line2'] }}</div>@endif
            <div dir="ltr" style="text-align:left;">{{ trim(($billing['city'] ?? '') . ', ' . ($billing['state'] ?? '') . ' ' . ($billing['postal_code'] ?? $billing['zip'] ?? '')) }}</div>
            <div dir="ltr" style="text-align:left;">{{ $billing['country'] ?? '' }}</div>
            @if(!empty($billing['phone']))<div dir="ltr" style="text-align:left;">{{ $billing['phone'] }}</div>@endif
        </td>
        <td width="48%" style="padding-left:12px;">
            <div style="font-size:9px;font-weight:bold;color:#E8500A;border-bottom:1px solid #E8500A;padding-bottom:4px;margin-bottom:8px;">{{ $shippingLabel }}</div>
            <div style="font-weight:bold;font-size:12px;">{{ trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')) }}</div>
            <div>{{ $shipping['address_line1'] ?? '' }}</div>
            @if(!empty($shipping['address_line2']))<div>{{ $shipping['address_line2'] }}</div>@endif
            <div dir="ltr" style="text-align:left;">{{ trim(($shipping['city'] ?? '') . ', ' . ($shipping['state'] ?? '') . ' ' . ($shipping['postal_code'] ?? $shipping['zip'] ?? '')) }}</div>
            <div dir="ltr" style="text-align:left;">{{ $shipping['country'] ?? '' }}</div>
        </td>
    </tr>
</table>

<table class="items" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th align="left" style="width:40%;">Product</th>
            <th class="num" style="width:15%;">Unit price</th>
            <th class="num" style="width:10%;">Qty</th>
            <th class="num" style="width:15%;">Discount</th>
            <th class="num" style="width:20%;">Line total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        @php $name = $item->product_name_en ?: $item->product_name_ar; @endphp
        <tr>
            <td>
                <strong>{{ $name }}</strong>
                <div class="item-sku">SKU: {{ $item->product_sku }}</div>
                @if($item->product_options)
                    @foreach($item->product_options as $key => $val)
                        <div class="item-sku">{{ ucfirst((string) $key) }}: {{ $val }}</div>
                    @endforeach
                @endif
            </td>
            <td class="num">{{ $currencySymbol }}{{ number_format((float) $item->unit_price, 2) }}</td>
            <td class="num">{{ $item->quantity }}</td>
            <td class="num">{{ (float) $item->discount_amount > 0 ? '−' . $currencySymbol . number_format((float) $item->discount_amount, 2) : '—' }}</td>
            <td class="num"><strong>{{ $currencySymbol }}{{ number_format((float) $item->line_total, 2) }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="totals" cellspacing="0" cellpadding="0">
    <tr><td class="muted">{{ $subtotalLabel }}</td><td align="right" style="font-weight:bold;">{{ $currencySymbol }}{{ number_format((float) $order->subtotal, 2) }}</td></tr>
    @if((float) $order->discount_amount > 0)
    <tr><td class="muted">{{ $discountLabel }}</td><td align="right" class="orange">−{{ $currencySymbol }}{{ number_format((float) $order->discount_amount, 2) }}</td></tr>
    @endif
    <tr>
        <td class="muted">{{ $shippingLabel2 }}</td>
        <td align="right" style="font-weight:bold;">{{ (float) $order->shipping_amount > 0 ? $currencySymbol . number_format((float) $order->shipping_amount, 2) : 'Free' }}</td>
    </tr>
    <tr><td class="muted">{{ $taxLabel }} ({{ number_format((float) $order->tax_rate * 100, 0) }}%)</td><td align="right" style="font-weight:bold;">{{ $currencySymbol }}{{ number_format((float) $order->tax_amount, 2) }}</td></tr>
    <tr style="border-top:2px solid #E8500A;"><td style="font-size:13px;font-weight:bold;padding-top:8px;">{{ $totalLabel }}</td><td align="right" style="font-size:13px;font-weight:bold;color:#E8500A;padding-top:8px;">{{ $currencySymbol }}{{ number_format((float) $order->total, 2) }}</td></tr>
</table>

<div style="border-top:1px solid #E8E8E8;margin-top:22px;padding-top:16px;text-align:center;">
    <div style="font-size:13px;font-weight:bold;margin-bottom:8px;">{{ $thankYouLabel }}</div>
    <div class="muted" style="font-size:9px;">FERRO — Forged from Iron, Polished by Luxury | support@ferro.com | ferro.com</div>
    <div class="muted" style="font-size:9px;margin-top:8px;">This invoice is electronically generated and valid without a signature.</div>
</div>

</body>
</html>
