@extends('emails._layout', ['locale' => 'en', 'isRtl' => false])

@section('email_title', '[FERRO Admin] ' . ($alertType === 'out_of_stock' ? '🚨 Out of Stock' : '⚠️ Low Stock') . ': ' . $product->name)
@section('header_class', 'admin-alert-header')

@section('email_body')

@php
    $isOutOfStock = $alertType === 'out_of_stock';
    $badgeClass   = $isOutOfStock ? 'badge-danger' : 'badge-warning';
    $icon         = $isOutOfStock ? '🚨' : '⚠️';
    $headline     = $isOutOfStock ? 'Out of Stock Alert' : 'Low Stock Warning';
@endphp

<p class="email-heading">{{ $icon }} {{ $headline }}</p>
<p class="email-subheading">Inventory threshold reached — action required</p>

<div style="margin-bottom: 24px;">
    <span class="status-badge {{ $badgeClass }}">{{ strtoupper(str_replace('_', ' ', $alertType)) }}</span>
</div>

{{-- Product Summary --}}
<div class="info-box">
    <dl style="margin: 0;">
        @foreach([
            ['label' => 'Product',    'value' => $product->name,                                    'bold' => true],
            ['label' => 'SKU',        'value' => $product->sku,                                     'mono' => true],
            ['label' => 'Category',   'value' => ucfirst($product->category ?? 'Uncategorised')],
            ['label' => 'Stock Left', 'value' => $product->stock_quantity . ' unit(s)'],
            ['label' => 'Threshold',  'value' => ($product->low_stock_threshold ?? 10) . ' unit(s)'],
        ] as $row)
        <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
            <dt style="min-width: 120px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $row['label'] }}</dt>
            <dd style="margin: 0; font-size: 13px; color: #F5F2EE;
                {{ isset($row['bold']) ? 'font-weight: 600;' : '' }}
                {{ isset($row['mono']) ? 'font-family: monospace; color: #E8500A;' : '' }}">
                {{ $row['value'] }}
            </dd>
        </div>
        @endforeach
    </dl>
</div>

{{-- Visual Stock Gauge --}}
@php
    $threshold = $product->low_stock_threshold ?? 10;
    $maxDisplay = max($threshold * 2, 1);
    $percent = $isOutOfStock ? 0 : min(100, round(($product->stock_quantity / $maxDisplay) * 100));
    $barColor = $isOutOfStock ? '#DC2626' : ($percent < 30 ? '#F59E0B' : '#22C55E');
@endphp
<div style="margin: 24px 0;">
    <div style="font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">
        Stock Level
    </div>
    <div style="background-color: #2A2A2A; border-radius: 2px; height: 8px; overflow: hidden;">
        <div style="width: {{ $percent }}%; height: 100%; background-color: {{ $barColor }}; border-radius: 2px; transition: width 0.3s;"></div>
    </div>
    <div style="font-size: 12px; color: #6B6B6B; margin-top: 4px;">
        {{ $product->stock_quantity }} / {{ $maxDisplay }} units
    </div>
</div>

@if($isOutOfStock)
<div style="background-color: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); border-radius: 2px; padding: 16px; margin-bottom: 24px;">
    <p style="margin: 0; font-size: 13px; color: #FCA5A5;">
        <strong>⚠️ Immediate action required:</strong> This product is now showing as out-of-stock on the storefront.
        Customers may be joining the waitlist. Restock or disable the product listing if necessary.
    </p>
</div>
@else
<div style="background-color: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.25); border-radius: 2px; padding: 16px; margin-bottom: 24px;">
    <p style="margin: 0; font-size: 13px; color: #FDE68A;">
        <strong>Heads up:</strong> Stock is approaching the minimum threshold.
        Consider placing a restock order soon to avoid going out-of-stock.
    </p>
</div>
@endif

<hr class="email-divider">

<div class="email-btn-center">
    <a href="{{ url('/admin/products/' . $product->id) }}" class="email-btn">Manage Product Inventory</a>
</div>

<p class="email-text" style="text-align: center; font-size: 11px;">
    Automated inventory alert from FERRO. Adjust thresholds in the product settings.
</p>

@endsection
