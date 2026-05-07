@extends('emails._layout')

@section('email_title', $isRtl ? 'تأكيد طلبك — FERRO' : 'Order Confirmation — FERRO')

@section('email_body')

@php
    $t = [
        'headline'       => $isRtl ? 'شكراً لطلبك' : 'Thank You for Your Order',
        'subheadline'    => $isRtl ? 'تم تأكيد طلبك وجاري المعالجة.' : 'Your order has been confirmed and is being processed.',
        'order_num'      => $isRtl ? 'رقم الطلب' : 'Order Number',
        'order_date'     => $isRtl ? 'تاريخ الطلب' : 'Order Date',
        'ship_to'        => $isRtl ? 'عنوان الشحن' : 'Shipping Address',
        'items_header'   => $isRtl ? 'المنتجات المطلوبة' : 'Items Ordered',
        'product'        => $isRtl ? 'المنتج' : 'Product',
        'qty'            => $isRtl ? 'الكمية' : 'Qty',
        'price'          => $isRtl ? 'السعر' : 'Price',
        'total_col'      => $isRtl ? 'الإجمالي' : 'Total',
        'subtotal'       => $isRtl ? 'المجموع الفرعي' : 'Subtotal',
        'shipping'       => $isRtl ? 'الشحن' : 'Shipping',
        'tax'            => $isRtl ? 'الضريبة' : 'Tax',
        'discount'       => $isRtl ? 'الخصم' : 'Discount',
        'grand_total'    => $isRtl ? 'الإجمالي الكلي' : 'Grand Total',
        'invoice_note'   => $isRtl ? 'تم إرفاق الفاتورة بهذا البريد.' : 'Your invoice is attached to this email.',
        'track_cta'      => $isRtl ? 'تتبع طلبك' : 'Track Your Order',
        'questions'      => $isRtl ? 'هل لديك أسئلة؟' : 'Have questions?',
        'contact_us'     => $isRtl ? 'تواصل معنا' : 'Contact us',
    ];
    $align = $isRtl ? 'right' : 'left';
@endphp

<p class="email-heading">{{ $t['headline'] }}</p>
<p class="email-subheading">{{ $t['subheadline'] }}</p>

{{-- Order Meta --}}
<div class="info-box" style="margin-bottom: 24px;">
    <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
        <span style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t['order_num'] }}</span>
        <span style="font-size: 13px; color: #E8500A; font-weight: 700; font-family: monospace;">#{{ $order->order_number }}</span>
    </div>
    <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
        <span style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t['order_date'] }}</span>
        <span style="font-size: 13px; color: #F5F2EE;">{{ $order->created_at->format('d F Y') }}</span>
    </div>
    <div style="display: flex; gap: 12px; padding: 5px 0;">
        <span style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t['ship_to'] }}</span>
        <span style="font-size: 13px; color: #F5F2EE;">{{ $order->shippingSummaryForMail() }}</span>
    </div>
</div>

{{-- Order Items --}}
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 0 0 12px; text-transform: uppercase; letter-spacing: 0.08em;">
    {{ $t['items_header'] }}
</h3>
<table class="order-table">
    <thead>
        <tr>
            <th style="text-align: {{ $align }};">{{ $t['product'] }}</th>
            <th style="text-align: center;">{{ $t['qty'] }}</th>
            <th style="text-align: right;">{{ $t['price'] }}</th>
            <th style="text-align: right;">{{ $t['total_col'] }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td style="text-align: {{ $align }};">
                <div style="font-weight: 500;">{{ $item->product_name }}</div>
                @if($item->product_options)
                    @foreach($item->product_options as $key => $val)
                        <div style="font-size: 11px; color: #6B6B6B; margin-top: 2px;">{{ ucfirst((string) $key) }}: {{ $val }}</div>
                    @endforeach
                @endif
            </td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            <td style="text-align: right;">{{ ferro_money($item->unit_price, $order->currency) }}</td>
            <td style="text-align: right; font-weight: 600;">{{ ferro_money($item->line_total, $order->currency) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        @foreach([
            ['label' => $t['subtotal'], 'value' => ferro_money($order->subtotal, $order->currency),      'class' => ''],
            ['label' => $t['shipping'], 'value' => (float) $order->shipping_amount > 0 ? ferro_money($order->shipping_amount, $order->currency) : ($isRtl ? 'مجاني' : 'Free'), 'class' => ''],
            ['label' => $t['tax'],      'value' => ferro_money($order->tax_amount, $order->currency),    'class' => ''],
        ] as $row)
        @if(!empty($row['value']))
        <tr>
            <td colspan="3" style="text-align: right; color: #6B6B6B; font-size: 12px; padding: 5px 14px; border-top: 1px solid #2A2A2A;">{{ $row['label'] }}</td>
            <td style="text-align: right; padding: 5px 14px; border-top: 1px solid #2A2A2A;">{{ $row['value'] }}</td>
        </tr>
        @endif
        @endforeach
        @if($order->discount_amount > 0)
        <tr>
            <td colspan="3" style="text-align: right; color: #22C55E; font-size: 12px; padding: 5px 14px;">{{ $t['discount'] }}</td>
            <td style="text-align: right; padding: 5px 14px; color: #22C55E;">−{{ ferro_money($order->discount_amount, $order->currency) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td colspan="3" style="text-align: right; padding: 12px 14px; font-size: 13px;">{{ $t['grand_total'] }}</td>
            <td style="text-align: right; padding: 12px 14px;" class="grand-total">{{ ferro_money($order->total, $order->currency) }}</td>
        </tr>
    </tfoot>
</table>

{{-- Invoice Note --}}
<div style="background-color: rgba(232,80,10,0.08); border: 1px solid rgba(232,80,10,0.2); border-radius: 2px; padding: 12px 16px; margin: 24px 0; font-size: 13px; color: #F5F2EE;">
    {{ $t['invoice_note'] }}
</div>

<hr class="email-divider">

<div class="email-btn-center">
    <a href="{{ $trackingUrl }}" class="email-btn">{{ $t['track_cta'] }}</a>
</div>

<p class="email-text" style="text-align: center; margin-top: 16px;">
    {{ $t['questions'] }}
    <a href="{{ route('contact') }}" style="color: #E8500A; text-decoration: none;">{{ $t['contact_us'] }}</a>
</p>

@endsection
