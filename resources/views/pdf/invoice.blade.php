<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta charset="UTF-8">
    <title>FERRO Invoice {{ $order->invoice_number }}</title>
    <style>
        body { margin: 0; padding: 24px; font-family: sans-serif; font-size: 11px; color: #1A1A1A; line-height: 1.45; }
        .muted { color: #6B6B6B; }
        .orange { color: #E8500A; }
        .ar { direction: rtl; unicode-bidi: bidi-override; }
        table.meta { width: 100%; background: #F5F2EE; border-left: 3px solid #E8500A; margin-bottom: 22px; border-collapse: collapse; }
        table.meta td { padding: 6px 10px; font-size: 10px; vertical-align: top; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        table.items th { background: #0A0A0A; color: #fff; font-size: 9px; padding: 9px 10px; text-align: center; font-weight: bold; }
        table.items td { padding: 9px 10px; font-size: 10px; border-bottom: 1px solid #E8E8E8; vertical-align: top; }
        table.items td.num { text-align: center; }
        table.items tr:nth-child(even) td { background: #FAFAFA; }
        table.totals { width: 48%; margin-left: auto; border-collapse: collapse; }
        table.totals td { padding: 5px 0; font-size: 10px; border-bottom: 1px solid #E8E8E8; }
        .item-sku { font-size: 9px; color: #6B6B6B; margin-top: 2px; }
    </style>
</head>
<body>

@php
    $isRtl = false;
    $startAlign = 'left';
    $endAlign   = 'right';

    $billing = $order->billing_address ?? [];
    $shipping = $order->shipping_address ?? [];

    $billingName = trim(($billing['name'] ?? '') ?: (($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '')));
    if ($billingName === '') {
        $billingName = trim(($shipping['name'] ?? '') ?: (($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')));
    }

    $shippingName = trim(($shipping['name'] ?? '') ?: (($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')));
    if ($shippingName === '') {
        $shippingName = $billingName;
    }

    $billingAddress1 = $billing['address_line1'] ?? $billing['address'] ?? $shipping['address_line1'] ?? $shipping['address'] ?? '';
    $billingAddress2 = $billing['address_line2'] ?? $shipping['address_line2'] ?? '';
    $shippingAddress1 = $shipping['address_line1'] ?? $shipping['address'] ?? '';
    $shippingAddress2 = $shipping['address_line2'] ?? '';

    $billingCityLine = trim(($billing['city'] ?? $shipping['city'] ?? '') . ', ' . ($billing['state'] ?? $shipping['state'] ?? '') . ' ' . ($billing['postal_code'] ?? $billing['zip'] ?? $shipping['postal_code'] ?? $shipping['zip'] ?? ''));
    $shippingCityLine = trim(($shipping['city'] ?? '') . ', ' . ($shipping['state'] ?? '') . ' ' . ($shipping['postal_code'] ?? $shipping['zip'] ?? ''));

    $billingCountry = $billing['country'] ?? $shipping['country'] ?? '';
    $shippingCountry = $shipping['country'] ?? '';

    $billingPhone = $billing['phone'] ?? $shipping['phone'] ?? '';
    $shippingPhone = $shipping['phone'] ?? '';
@endphp

<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;border-bottom:2px solid #E8500A;padding-bottom:16px;">
    <tr valign="top">
        <td width="48%" align="{{ $startAlign }}">
            <table cellspacing="0" cellpadding="0" style="margin-bottom:6px;">
                <tr>
                    <td style="vertical-align:middle;{{ $isRtl ? 'padding-left' : 'padding-right' }}:10px;">
                        <div style="font-size:34px;font-weight:bold;color:#E8500A;line-height:1;font-family:DejaVu Sans,sans-serif;">F</div>
                    </td>
                    <td style="vertical-align:middle;">
                        <div style="font-size:17px;font-weight:bold;letter-spacing:0.28em;color:#1A1A1A;font-family:DejaVu Sans,sans-serif;">FERRO</div>
                    </td>
                </tr>
            </table>
            <div class="muted" style="font-size:9px;margin-top:2px;letter-spacing:0.06em;">{{ $brandTagline }}</div>
        </td>
        <td width="52%" align="{{ $endAlign }}">
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
        <td width="48%" style="padding-right:12px; text-align:left;">
            <div style="font-size:9px;font-weight:bold;color:#E8500A;border-bottom:1px solid #E8500A;padding-bottom:4px;margin-bottom:8px;">{{ $billingLabel }}</div>
            <div style="font-weight:bold;font-size:12px;">{{ $billingName }}</div>
            <div>{{ $billingAddress1 }}</div>
            @if(!empty($billingAddress2))<div>{{ $billingAddress2 }}</div>@endif
            @if(!empty($billingCityLine))<div>{{ $billingCityLine }}</div>@endif
            @if(!empty($billingCountry))<div>{{ $billingCountry }}</div>@endif
            @if(!empty($billingPhone))<div>{{ $billingPhone }}</div>@endif
        </td>
        <td width="48%" style="padding-left:12px; text-align:left;">
            <div style="font-size:9px;font-weight:bold;color:#E8500A;border-bottom:1px solid #E8500A;padding-bottom:4px;margin-bottom:8px;">{{ $shippingLabel }}</div>
            <div style="font-weight:bold;font-size:12px;">{{ $shippingName }}</div>
            <div>{{ $shippingAddress1 }}</div>
            @if(!empty($shippingAddress2))<div>{{ $shippingAddress2 }}</div>@endif
            @if(!empty($shippingCityLine))<div>{{ $shippingCityLine }}</div>@endif
            @if(!empty($shippingCountry))<div>{{ $shippingCountry }}</div>@endif
            @if(!empty($shippingPhone))<div>{{ $shippingPhone }}</div>@endif
        </td>
    </tr>
</table>

<table class="items" cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th align="left" style="width:40%;">{{ $productLabel }}</th>
            <th class="num" style="width:15%;">{{ $unitPriceLabel }}</th>
            <th class="num" style="width:10%;">{{ $qtyLabel }}</th>
            <th class="num" style="width:15%;">{{ $discountLabel }}</th>
            <th class="num" style="width:20%;">{{ $lineTotalLabel }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        @php
            $nameEn = $item->product_name_en ?: '';
            $nameAr = $item->product_name_ar ?: '';
            $nameParts = array_filter([$nameEn, $nameAr]);
            $name = !empty($nameParts) ? implode(' / ', $nameParts) : '-';
        @endphp
        <tr>
            <td>
                <strong>{{ $name }}</strong>
                <div class="item-sku">{{ $skuLabel }}: {{ $item->product_sku }}</div>
                @if($item->product_options)
                    @foreach($item->product_options as $key => $val)
                        <div class="item-sku">{{ ucfirst((string) $key) }}: {{ $val }}</div>
                    @endforeach
                @endif
            </td>
            <td class="num">{{ \App\Support\Money::format($item->unit_price, $order->currency) }}</td>
            <td class="num">{{ $item->quantity }}</td>
            <td class="num">{{ (float) $item->discount_amount > 0 ? '−' . \App\Support\Money::format($item->discount_amount, $order->currency) : '—' }}</td>
            <td class="num"><strong>{{ \App\Support\Money::format($item->line_total, $order->currency) }}</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="totals" cellspacing="0" cellpadding="0">
    <tr><td class="muted">{{ $subtotalLabel }}</td><td align="right" style="font-weight:bold;">{{ \App\Support\Money::format($order->subtotal, $order->currency) }}</td></tr>
    @if((float) $order->discount_amount > 0)
    <tr><td class="muted">{{ $discountLabel }}</td><td align="right" class="orange">−{{ \App\Support\Money::format($order->discount_amount, $order->currency) }}</td></tr>
    @endif
    <tr>
        <td class="muted">{{ $shippingLabel2 }}</td>
        <td align="right" style="font-weight:bold;">{{ (float) $order->shipping_amount > 0 ? \App\Support\Money::format($order->shipping_amount, $order->currency) : $shippingFreeLabel }}</td>
    </tr>
    <tr><td class="muted">{{ $taxLabel }} ({{ number_format((float) $order->tax_rate * 100, 0) }}%)</td><td align="right" style="font-weight:bold;">{{ \App\Support\Money::format($order->tax_amount, $order->currency) }}</td></tr>
    <tr style="border-top:2px solid #E8500A;"><td style="font-size:13px;font-weight:bold;padding-top:8px;">{{ $totalLabel }}</td><td align="right" style="font-size:13px;font-weight:bold;color:#E8500A;padding-top:8px;">{{ \App\Support\Money::format($order->total, $order->currency) }}</td></tr>
</table>

<div style="border-top:1px solid #E8E8E8;margin-top:22px;padding-top:16px;text-align:center;">
    <div style="font-size:13px;font-weight:bold;margin-bottom:8px;">{{ $thankYouLabel }}</div>
    <div class="muted" style="font-size:9px;">FERRO - {{ $brandTagline }} | {{ $contactSetting->support_email }} | ferro.com</div>
    <div class="muted" style="font-size:9px;margin-top:8px;">{{ $invoiceValidityNote }}</div>
</div>

</body>
</html>
