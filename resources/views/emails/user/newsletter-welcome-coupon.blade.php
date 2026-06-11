@extends('emails._layout')

@section('email_title', $isRtl ? 'كود الخصم الخاص بك — FERRO' : 'Your FERRO Discount Coupon')

@section('email_body')

@php
    $t = [
        'eyebrow' => $isRtl ? 'مرحباً بك' : 'Welcome',
        'headline' => $isRtl ? 'كود الخصم جاهز!' : 'Your coupon is ready!',
        'subheadline' => $isRtl
            ? 'شكراً لاشتراكك. استخدم الكود أدناه عند الدفع.'
            : 'Thanks for subscribing. Use the code below at checkout.',
        'code_label' => $isRtl ? 'كود الخصم' : 'Your coupon code',
        'discount_label' => $isRtl ? 'نسبة الخصم' : 'Discount',
        'expires_label' => $isRtl ? 'صالح حتى' : 'Valid until',
        'shop_cta' => $isRtl ? 'تسوق الآن' : 'Shop Now',
        'unsubscribe' => $isRtl ? 'إلغاء الاشتراك' : 'Unsubscribe',
    ];
@endphp

<div style="text-align: center; margin-bottom: 6px;">
    <span style="font-size: 11px; color: #E8500A; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 600;">
        {{ $t['eyebrow'] }}
    </span>
</div>
<p class="email-heading" style="text-align: center;">{{ $t['headline'] }}</p>
<p class="email-subheading" style="text-align: center;">{{ $t['subheadline'] }}</p>

<div style="background: linear-gradient(135deg, #1A1A1A, #0A0A0A); border: 1px solid rgba(232,80,10,0.4); border-radius: 2px; padding: 24px; text-align: center; margin: 24px 0;">
    <div style="font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
        {{ $t['code_label'] }}
    </div>
    <div style="font-size: 32px; font-weight: 700; color: #E8500A; letter-spacing: 0.12em; line-height: 1;">
        {{ $subscriber->coupon_code }}
    </div>
    <div style="font-size: 13px; color: #C5C1BB; margin-top: 14px;">
        {{ $t['discount_label'] }}: <strong style="color: #FFFFFF;">{{ $subscriber->discount_percent }}%</strong>
    </div>
    @if($subscriber->coupon_expires_at)
    <div style="font-size: 11px; color: #6B6B6B; margin-top: 8px;">
        {{ $t['expires_label'] }}: {{ $subscriber->coupon_expires_at->format('M j, Y') }}
    </div>
    @endif
</div>

<div style="text-align: center; margin: 28px 0;">
    <a href="{{ route('products.index') }}" class="email-btn">{{ $t['shop_cta'] }}</a>
</div>

<hr class="email-divider">

<p class="email-text" style="text-align: center; font-size: 11px;">
    <a href="{{ route('newsletter.unsubscribe', ['email' => $subscriber->email, 'token' => $subscriber->unsubscribeToken()]) }}"
       style="color: #6B6B6B; text-decoration: underline; font-size: 11px;">
        {{ $t['unsubscribe'] }}
    </a>
</p>

@endsection
