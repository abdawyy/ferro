@extends('emails._layout')

@section('email_title', $isRtl ? 'تحديث الطلب — FERRO' : 'Order update — FERRO')

@section('email_body')

@php
    $t = [
        'headline'    => $isRtl ? 'تحديث حالة طلبك' : 'Your order status was updated',
        'order_num'   => $isRtl ? 'رقم الطلب' : 'Order number',
        'was'         => $isRtl ? 'كانت الحالة' : 'Previous status',
        'now'         => $isRtl ? 'الحالة الآن' : 'Current status',
        'track_cta'   => $isRtl ? 'تتبع الطلب' : 'Track your order',
        'questions'   => $isRtl ? 'هل تحتاج مساعدة؟' : 'Need help?',
        'contact_us'  => $isRtl ? 'تواصل معنا' : 'Contact us',
    ];
@endphp

<p class="email-heading">{{ $t['headline'] }}</p>
<p class="email-subheading">{{ $isRtl ? 'اطّلع على أحدث حالة لطلبك أدناه.' : 'Below is the latest status for your FERRO order.' }}</p>

<div class="info-box" style="margin-bottom: 24px;">
    <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
        <span style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t['order_num'] }}</span>
        <span style="font-size: 13px; color: #E8500A; font-weight: 700; font-family: monospace;">#{{ $order->order_number }}</span>
    </div>
    <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
        <span style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t['was'] }}</span>
        <span style="font-size: 13px; color: #F5F2EE;">{{ ucwords(str_replace('_', ' ', $previousStatus)) }}</span>
    </div>
    <div style="display: flex; gap: 12px; padding: 5px 0;">
        <span style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $t['now'] }}</span>
        <span style="font-size: 13px; color: #F5F2EE; font-weight: 600;">{{ ucwords(str_replace('_', ' ', $newStatus)) }}</span>
    </div>
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
