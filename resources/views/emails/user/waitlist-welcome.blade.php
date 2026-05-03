@extends('emails._layout')

@section('email_title', $isRtl ? 'أهلاً بك في قائمة الانتظار — FERRO' : 'Welcome to the FERRO Waitlist')

@section('email_body')

@php
    $t = [
        'eyebrow'        => $isRtl ? 'أنت على القائمة' : "You're on the list",
        'headline'       => $isRtl ? 'مرحباً بك في قائمة FERRO' : 'Welcome to the FERRO Waitlist',
        'subheadline'    => $isRtl
            ? 'لقد انضممت إلى مجتمع حصري من عشاق FERRO. ستكون أول من يعلم عند توفر المنتج.'
            : 'You\'ve joined an exclusive community of FERRO devotees. You\'ll be the first to know when the product drops.',
        'member_since'   => $isRtl ? 'عضو منذ' : 'Member since',
        'position_label' => $isRtl ? 'موقعك في القائمة' : 'Your waitlist position',
        'benefits_title' => $isRtl ? 'مزايا العضوية الحصرية' : 'Founding Member Benefits',
        'benefits'       => $isRtl ? [
            ['icon' => '⚡', 'title' => 'وصول أول',            'desc' => 'ستحصل على إشعار قبل 24 ساعة من الإطلاق العام'],
            ['icon' => '🚚', 'title' => 'شحن مجاني',           'desc' => 'شحن مجاني على أول طلب بعد الإطلاق'],
            ['icon' => '🎁', 'title' => 'هدية الأعضاء',        'desc' => 'مفاجأة حصرية مع أول طلب لك'],
            ['icon' => '🔒', 'title' => 'سعر الإطلاق مضمون',  'desc' => 'لن يتغير السعر قبل إخطارك'],
        ] : [
            ['icon' => '⚡', 'title' => 'Early Access',         'desc' => 'Get notified 24 hours before the public drop'],
            ['icon' => '🚚', 'title' => 'Free Shipping',        'desc' => 'Complimentary shipping on your first post-launch order'],
            ['icon' => '🎁', 'title' => 'Founding Gift',        'desc' => 'An exclusive surprise included with your first order'],
            ['icon' => '🔒', 'title' => 'Price Locked',         'desc' => 'Your launch price is guaranteed — no surprises'],
        ],
        'what_next_title' => $isRtl ? 'ما الذي يحدث بعد ذلك؟' : "What Happens Next?",
        'steps'           => $isRtl ? [
            'نراجع قائمة الانتظار ونطلق المنتج قريباً.',
            'ستصلك رسالة بريد إلكتروني بمجرد توفر المنتج.',
            'لديك 48 ساعة للاستفادة من صفقة العضوية الحصرية.',
        ] : [
            'We review the waitlist and prepare for the launch.',
            'You\'ll receive an email the moment the product is live.',
            'You get 48 hours to claim your founding member deal.',
        ],
        'follow'         => $isRtl ? 'تابعنا لمعرفة آخر الأخبار' : 'Follow us for behind-the-scenes updates',
        'unsubscribe'    => $isRtl ? 'إلغاء الاشتراك' : 'Unsubscribe from waitlist',
    ];
    $align = $isRtl ? 'right' : 'left';
@endphp

{{-- Hero --}}
<div style="text-align: center; padding: 16px 0 24px;">
    <div style="display: inline-block; font-size: 48px; line-height: 1;">⚗️</div>
</div>

<div style="text-align: center; margin-bottom: 6px;">
    <span style="font-size: 11px; color: #E8500A; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 600;">
        {{ $t['eyebrow'] }}
    </span>
</div>
<p class="email-heading" style="text-align: center;">{{ $t['headline'] }}</p>
<p class="email-subheading" style="text-align: center;">{{ $t['subheadline'] }}</p>

{{-- Member Badge --}}
<div style="background: linear-gradient(135deg, #1A1A1A, #0A0A0A); border: 1px solid rgba(232,80,10,0.4); border-radius: 2px; padding: 20px; text-align: center; margin: 24px 0;">
    @if($lead->waitlist_position ?? null)
    <div style="font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px;">
        {{ $t['position_label'] }}
    </div>
    <div style="font-size: 42px; font-weight: 700; color: #E8500A; line-height: 1;">#{{ $lead->waitlist_position }}</div>
    @endif
    <div style="font-size: 11px; color: #6B6B6B; margin-top: 12px; text-transform: uppercase; letter-spacing: 0.1em;">
        {{ $t['member_since'] }}: {{ now()->format('F Y') }}
    </div>
</div>

{{-- Benefits Grid --}}
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 0 0 16px; text-transform: uppercase; letter-spacing: 0.08em; text-align: {{ $align }};">
    {{ $t['benefits_title'] }}
</h3>
@foreach($t['benefits'] as $benefit)
<div style="display: flex; gap: 14px; align-items: flex-start; padding: 12px 0; border-bottom: 1px solid #1A1A1A; direction: {{ $isRtl ? 'rtl' : 'ltr' }};">
    <div style="font-size: 22px; line-height: 1; flex-shrink: 0; margin-top: 2px;">{{ $benefit['icon'] }}</div>
    <div>
        <div style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin-bottom: 3px;">{{ $benefit['title'] }}</div>
        <div style="font-size: 12px; color: #6B6B6B; line-height: 1.5;">{{ $benefit['desc'] }}</div>
    </div>
</div>
@endforeach

{{-- What Happens Next --}}
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 28px 0 14px; text-transform: uppercase; letter-spacing: 0.08em; text-align: {{ $align }};">
    {{ $t['what_next_title'] }}
</h3>
@foreach($t['steps'] as $i => $step)
<div style="display: flex; gap: 14px; align-items: flex-start; padding: 8px 0; direction: {{ $isRtl ? 'rtl' : 'ltr' }};">
    <div style="width: 24px; height: 24px; border-radius: 50%; background-color: #E8500A; color: #FFFFFF; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; line-height: 24px; text-align: center;">
        {{ $i + 1 }}
    </div>
    <div style="font-size: 13px; color: #C5C1BB; line-height: 1.5; padding-top: 3px;">{{ $step }}</div>
</div>
@endforeach

<hr class="email-divider">

<p class="email-text" style="text-align: center; font-size: 12px; color: #6B6B6B;">
    {{ $t['follow'] }}
</p>
<div style="text-align: center; margin: 12px 0 20px;">
    @foreach(['Instagram' => 'https://instagram.com/ferroskincareofficial', 'TikTok' => 'https://tiktok.com/@ferroskincare'] as $platform => $url)
    <a href="{{ $url }}" style="display: inline-block; margin: 0 6px; padding: 6px 14px; border: 1px solid #2A2A2A; color: #C5C1BB; text-decoration: none; font-size: 12px; border-radius: 1px; letter-spacing: 0.05em;">
        {{ $platform }}
    </a>
    @endforeach
</div>

<p class="email-text" style="text-align: center; font-size: 11px; margin-top: 0;">
    <a href="{{ route('waitlist.unsubscribe', ['email' => $lead->email, 'token' => hash('sha256', $lead->email . config('app.key'))]) }}"
       style="color: #6B6B6B; text-decoration: underline; font-size: 11px;">
        {{ $t['unsubscribe'] }}
    </a>
</p>

@endsection
