<!DOCTYPE html>
{{-- FERRO Invoice PDF Template — rendered by DomPDF --}}
{{-- Supports RTL (Arabic) and LTR (English) layouts --}}
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoiceLabel }} {{ $order->invoice_number }}</title>
    <style>
        /* ── DomPDF-compatible CSS ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #FFFFFF;
            color: #1A1A1A;
            font-size: 11px;
            line-height: 1.5;
            direction: {{ $isRtl ? 'rtl' : 'ltr' }};
        }

        /* ── Layout ── */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        /* ── Header ── */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #E8500A;
            padding-bottom: 24px;
        }
        .header-left  { display: table-cell; width: 50%; vertical-align: top; }
        .header-right { display: table-cell; width: 50%; vertical-align: top; text-align: {{ $isRtl ? 'left' : 'right' }}; }

        .brand-name {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 0.2em;
            color: #0A0A0A;
            text-transform: uppercase;
        }
        .brand-iron {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #E8500A;
            margin-{{ $isRtl ? 'left' : 'right' }}: 6px;
            vertical-align: middle;
        }
        .brand-tagline {
            font-size: 9px;
            letter-spacing: 0.15em;
            color: #6B6B6B;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            color: #E8500A;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }
        .invoice-number {
            font-size: 13px;
            color: #2A2A2A;
            font-weight: bold;
            margin-top: 4px;
        }
        .invoice-date {
            font-size: 10px;
            color: #6B6B6B;
            margin-top: 2px;
        }

        /* ── Addresses ── */
        .address-section {
            display: table;
            width: 100%;
            margin-bottom: 32px;
        }
        .address-block {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding-{{ $isRtl ? 'left' : 'right' }}: 20px;
        }
        .address-label {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #E8500A;
            border-bottom: 1px solid #E8500A;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .address-name  { font-weight: bold; font-size: 12px; color: #0A0A0A; margin-bottom: 3px; }
        .address-line  { font-size: 10px; color: #2A2A2A; line-height: 1.6; }

        /* ── Order Meta ── */
        .meta-section {
            display: table;
            width: 100%;
            background: #F5F2EE;
            padding: 12px 16px;
            margin-bottom: 28px;
            border-left: 3px solid #E8500A;
        }
        .meta-row   { display: table-row; }
        .meta-label { display: table-cell; width: 50%; font-size: 10px; color: #6B6B6B; padding: 2px 0; }
        .meta-value { display: table-cell; width: 50%; font-size: 10px; color: #0A0A0A; font-weight: bold; padding: 2px 0; text-align: {{ $isRtl ? 'left' : 'right' }}; }

        /* ── Line Items Table ── */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items thead tr th {
            background: #0A0A0A;
            color: #FFFFFF;
            font-size: 9px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: {{ $isRtl ? 'right' : 'left' }};
            font-weight: bold;
        }
        table.items thead tr th.num { text-align: {{ $isRtl ? 'left' : 'right' }}; }
        table.items tbody tr td {
            padding: 10px 12px;
            font-size: 10px;
            border-bottom: 1px solid #E8E8E8;
            color: #1A1A1A;
            vertical-align: top;
        }
        table.items tbody tr:nth-child(even) td { background: #FAFAFA; }
        table.items tbody tr td.num { text-align: {{ $isRtl ? 'left' : 'right' }}; font-family: monospace; }
        .item-sku { font-size: 9px; color: #6B6B6B; margin-top: 2px; }

        /* ── Totals ── */
        .totals-section {
            display: table;
            width: 100%;
            margin-bottom: 32px;
        }
        .totals-spacer { display: table-cell; width: 55%; }
        .totals-block  { display: table-cell; width: 45%; }
        .total-row     { display: table; width: 100%; padding: 4px 0; border-bottom: 1px solid #E8E8E8; }
        .total-label   { display: table-cell; font-size: 10px; color: #6B6B6B; }
        .total-value   { display: table-cell; text-align: {{ $isRtl ? 'left' : 'right' }}; font-size: 10px; color: #0A0A0A; font-family: monospace; }
        .total-row.grand { border-top: 2px solid #E8500A; margin-top: 4px; padding-top: 8px; }
        .total-row.grand .total-label { font-size: 13px; font-weight: bold; color: #0A0A0A; }
        .total-row.grand .total-value { font-size: 13px; font-weight: bold; color: #E8500A; }

        /* ── Footer ── */
        .invoice-footer {
            border-top: 1px solid #E8E8E8;
            padding-top: 20px;
            text-align: center;
        }
        .thank-you {
            font-size: 14px;
            font-weight: bold;
            color: #0A0A0A;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .footer-note { font-size: 9px; color: #6B6B6B; line-height: 1.6; }
        .ferro-orange { color: #E8500A; }

        /* ── Page numbers ── */
        @page { margin: 20px; }
    </style>
</head>
<body>
<div class="invoice-container">

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="header">
        <div class="header-left">
            <div class="brand-name">
                <span class="brand-iron"></span>FERRO
            </div>
            <div class="brand-tagline">{{ $brandTagline }}</div>
        </div>
        <div class="header-right">
            <div class="invoice-title">{{ $invoiceLabel }}</div>
            <div class="invoice-number">#{{ $order->invoice_number }}</div>
            <div class="invoice-date">{{ $generatedAt }}</div>
        </div>
    </div>

    {{-- ── ORDER META ──────────────────────────────────────────────────── --}}
    <div class="meta-section">
        <div class="meta-row">
            <span class="meta-label">{{ $isRtl ? 'رقم الطلب' : 'Order Number' }}</span>
            <span class="meta-value">{{ $order->order_number }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">{{ $isRtl ? 'تاريخ الطلب' : 'Order Date' }}</span>
            <span class="meta-value">{{ $order->created_at->format('d M Y') }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">{{ $isRtl ? 'طريقة الدفع' : 'Payment Method' }}</span>
            <span class="meta-value">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
        </div>
        <div class="meta-row">
            <span class="meta-label">{{ $isRtl ? 'حالة الدفع' : 'Payment Status' }}</span>
            <span class="meta-value ferro-orange">{{ ucfirst($order->payment_status) }}</span>
        </div>
    </div>

    {{-- ── ADDRESSES ───────────────────────────────────────────────────── --}}
    <div class="address-section">
        <div class="address-block">
            <div class="address-label">{{ $billingLabel }}</div>
            @php $billing = $order->billing_address; @endphp
            <div class="address-name">{{ ($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '') }}</div>
            <div class="address-line">{{ $billing['address_line1'] ?? '' }}</div>
            @if(!empty($billing['address_line2']))<div class="address-line">{{ $billing['address_line2'] }}</div>@endif
            <div class="address-line">{{ ($billing['city'] ?? '') . ', ' . ($billing['state'] ?? '') . ' ' . ($billing['zip'] ?? '') }}</div>
            <div class="address-line">{{ $billing['country'] ?? '' }}</div>
            @if(!empty($billing['phone']))<div class="address-line">{{ $billing['phone'] }}</div>@endif
        </div>
        <div class="address-block">
            <div class="address-label">{{ $shippingLabel }}</div>
            @php $shipping = $order->shipping_address; @endphp
            <div class="address-name">{{ ($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '') }}</div>
            <div class="address-line">{{ $shipping['address_line1'] ?? '' }}</div>
            @if(!empty($shipping['address_line2']))<div class="address-line">{{ $shipping['address_line2'] }}</div>@endif
            <div class="address-line">{{ ($shipping['city'] ?? '') . ', ' . ($shipping['state'] ?? '') . ' ' . ($shipping['zip'] ?? '') }}</div>
            <div class="address-line">{{ $shipping['country'] ?? '' }}</div>
        </div>
    </div>

    {{-- ── LINE ITEMS TABLE ────────────────────────────────────────────── --}}
    <table class="items">
        <thead>
            <tr>
                <th style="width: 40%;">{{ $isRtl ? 'المنتج' : 'Product' }}</th>
                <th style="width: 15%;" class="num">{{ $isRtl ? 'سعر الوحدة' : 'Unit Price' }}</th>
                <th style="width: 10%;" class="num">{{ $isRtl ? 'الكمية' : 'Qty' }}</th>
                <th style="width: 15%;" class="num">{{ $isRtl ? 'الخصم' : 'Discount' }}</th>
                <th style="width: 20%;" class="num">{{ $isRtl ? 'الإجمالي' : 'Line Total' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>
                    <strong>{{ $isRtl ? $item->product_name_ar : $item->product_name_en }}</strong>
                    <div class="item-sku">SKU: {{ $item->product_sku }}</div>
                    @if($item->product_options)
                        @foreach($item->product_options as $key => $val)
                            <div class="item-sku">{{ ucfirst($key) }}: {{ $val }}</div>
                        @endforeach
                    @endif
                </td>
                <td class="num">{{ $currencySymbol }}{{ number_format($item->unit_price, 2) }}</td>
                <td class="num">{{ $item->quantity }}</td>
                <td class="num">
                    {{ $item->discount_amount > 0 ? '−' . $currencySymbol . number_format($item->discount_amount, 2) : '—' }}
                </td>
                <td class="num"><strong>{{ $currencySymbol }}{{ number_format($item->line_total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── TOTALS ──────────────────────────────────────────────────────── --}}
    <div class="totals-section">
        <div class="totals-spacer"></div>
        <div class="totals-block">
            <div class="total-row">
                <span class="total-label">{{ $subtotalLabel }}</span>
                <span class="total-value">{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span class="total-label">{{ $discountLabel }}</span>
                <span class="total-value ferro-orange">−{{ $currencySymbol }}{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row">
                <span class="total-label">{{ $shippingLabel2 }}</span>
                <span class="total-value">
                    {{ $order->shipping_amount > 0
                        ? $currencySymbol . number_format($order->shipping_amount, 2)
                        : ($isRtl ? 'مجاني' : 'Free') }}
                </span>
            </div>
            <div class="total-row">
                <span class="total-label">{{ $taxLabel }} ({{ number_format($order->tax_rate * 100, 0) }}%)</span>
                <span class="total-value">{{ $currencySymbol }}{{ number_format($order->tax_amount, 2) }}</span>
            </div>
            <div class="total-row grand">
                <span class="total-label">{{ $totalLabel }}</span>
                <span class="total-value">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- ── FOOTER ──────────────────────────────────────────────────────── --}}
    <div class="invoice-footer">
        <div class="thank-you">{{ $thankYouLabel }}</div>
        <div class="footer-note">
            {{ $isRtl
                ? 'فيرو — مصنوع من الحديد، مصقول بالرفاهية | support@ferro.com | ferro.com'
                : 'FERRO — Forged from Iron, Polished by Luxury | support@ferro.com | ferro.com' }}
        </div>
        <div class="footer-note" style="margin-top: 8px;">
            {{ $isRtl
                ? 'هذه الفاتورة صادرة إلكترونياً وصالحة دون توقيع.'
                : 'This invoice is electronically generated and valid without a signature.' }}
        </div>
    </div>

</div>
</body>
</html>
