@extends('emails._layout')

@section('email_title', $isRtl ? 'منتجك متاح الآن — FERRO' : 'Your Waitlisted Product Is Available — FERRO')

@section('email_body')

@php
    $t = [
        'eyebrow'       => $isRtl ? 'خبر رائع!' : 'Great News!',
        'headline'      => $isRtl ? 'منتجك أصبح متاحاً' : 'Your Product Is Now Available',
        'subheadline'   => $isRtl ? 'المنتج الذي انتظرته من FERRO متاح الآن. تصرف الآن قبل نفاد المخزون.' : 'The FERRO product you\'ve been waiting for is finally here. Act fast before it sells out.',
        'product_name'  => $isRtl ? ($product->getTranslation('name', 'ar') ?? $product->name) : $product->name,
        'stock_note'    => $isRtl ? 'وحدات متبقية فقط' : 'units remaining',
        'urgency'       => $isRtl ? '⏳ المخزون محدود — سارع قبل النفاد' : '⏳ Limited stock — order before it sells out',
        'benefits'      => $isRtl
            ? ['✦ الأولوية للمشتركين في القائمة', '✦ الشحن المجاني لأول 48 ساعة', '✦ ضمان استرداد المبلغ لمدة 30 يوماً']
            : ['✦ Waitlist members get first access', '✦ Free shipping for the next 48 hours', '✦ 30-day satisfaction guarantee'],
        'cta'           => $isRtl ? 'اشترِ الآن' : 'Shop Now',
        'expire_note'   => $isRtl ? 'هذا العرض صالح لمدة 48 ساعة فقط.' : 'This exclusive access expires in 48 hours.',
    ];
    $align = $isRtl ? 'right' : 'left';
@endphp

{{-- Eyebrow --}}
<div style="text-align: center; margin-bottom: 8px;">
    <span style="font-size: 11px; color: #E8500A; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 600;">{{ $t['eyebrow'] }}</span>
</div>

<p class="email-heading" style="text-align: center;">{{ $t['headline'] }}</p>
<p class="email-subheading" style="text-align: center;">{{ $t['subheadline'] }}</p>

{{-- Product Hero Card --}}
<div style="background-color: #1A1A1A; border: 1px solid #2A2A2A; border-radius: 2px; overflow: hidden; margin: 24px 0;">
    @if($product->image_url)
    <div style="background-color: #0A0A0A; text-align: center; padding: 20px;">
        <img src="{{ $product->image_url }}" alt="{{ $t['product_name'] }}"
             style="max-width: 220px; height: auto; display: inline-block;">
    </div>
    @endif
    <div style="padding: 20px;">
        <p style="margin: 0 0 6px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.15em;">FERRO</p>
        <p style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #FFFFFF;">{{ $t['product_name'] }}</p>
        <p style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #E8500A;">
            ${{ number_format($product->price, 2) }}
        </p>
        @if($product->stock_quantity && $product->stock_quantity < 20)
        <p style="margin: 0; font-size: 12px; color: #F59E0B;">
            🔥 {{ $product->stock_quantity }} {{ $t['stock_note'] }}
        </p>
        @endif
    </div>
</div>

{{-- Urgency Banner --}}
<div style="background-color: rgba(232,80,10,0.12); border: 1px solid rgba(232,80,10,0.35); border-radius: 2px; padding: 12px 16px; text-align: center; margin-bottom: 24px; font-size: 13px; color: #E8500A; font-weight: 600;">
    {{ $t['urgency'] }}
</div>

{{-- Waitlist Benefits --}}
<div style="margin-bottom: 24px;">
    @foreach($t['benefits'] as $benefit)
    <div style="padding: 8px 0; font-size: 13px; color: #C5C1BB; border-bottom: 1px solid #1A1A1A;">{{ $benefit }}</div>
    @endforeach
</div>

<hr class="email-divider">

<div class="email-btn-center">
    <a href="{{ route('products.show', $product->slug) }}" class="email-btn">{{ $t['cta'] }}</a>
</div>

<p class="email-text" style="text-align: center; font-size: 11px; color: #6B6B6B; margin-top: 12px;">
    {{ $t['expire_note'] }}
</p>

@endsection
