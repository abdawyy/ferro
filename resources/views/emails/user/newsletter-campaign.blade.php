@extends('emails._layout')

@section('email_title', $campaign->subject($locale))

@section('email_body')

@php
    $align = $isRtl ? 'right' : 'left';
    $bodyHtml = nl2br(e($campaign->body($locale)));
@endphp

<p class="email-heading" style="text-align: {{ $align }};">{{ $campaign->subject($locale) }}</p>

<div class="email-text" style="text-align: {{ $align }};">
    {!! $bodyHtml !!}
</div>

@if($product && $productName)
<div style="border: 1px solid #2A2A2A; border-radius: 2px; overflow: hidden; margin: 28px 0;">
    @if($productImage)
    <img src="{{ $productImage }}" alt="{{ $productName }}" style="display: block; width: 100%; max-height: 280px; object-fit: cover;">
    @endif
    <div style="padding: 20px; text-align: {{ $align }};">
        <div style="font-size: 11px; color: #E8500A; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 8px;">
            {{ $isRtl ? 'منتج جديد' : 'New Product' }}
        </div>
        <div style="font-size: 18px; font-weight: 600; color: #FFFFFF; margin-bottom: 12px;">{{ $productName }}</div>
        @if($productUrl)
        <a href="{{ $productUrl }}" class="email-btn">{{ $isRtl ? 'عرض المنتج' : 'View Product' }}</a>
        @endif
    </div>
</div>
@endif

@if($subscriber->isActive() && $subscriber->coupon_code)
<div style="background: #0A0A0A; border: 1px dashed #2A2A2A; border-radius: 2px; padding: 16px; text-align: center; margin-top: 24px;">
    <div style="font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 6px;">
        {{ $isRtl ? 'كود الخصم الخاص بك' : 'Your coupon' }}
    </div>
    <div style="font-size: 20px; font-weight: 700; color: #E8500A; letter-spacing: 0.1em;">{{ $subscriber->coupon_code }}</div>
</div>
@endif

<hr class="email-divider">

<p class="email-text" style="text-align: center; font-size: 11px;">
    <a href="{{ route('newsletter.unsubscribe', ['email' => $subscriber->email, 'token' => $subscriber->unsubscribeToken()]) }}"
       style="color: #6B6B6B; text-decoration: underline; font-size: 11px;">
        {{ $isRtl ? 'إلغاء الاشتراك' : 'Unsubscribe' }}
    </a>
</p>

@endsection
