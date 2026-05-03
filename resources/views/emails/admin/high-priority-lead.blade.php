@extends('emails._layout', ['locale' => 'en', 'isRtl' => false])

@section('email_title', '[FERRO Admin] 🔥 High-Priority Lead: ' . $lead->email)
@section('header_class', 'admin-alert-header')

@section('email_body')

@php
    $priorityBadge = match(strtolower($lead->priority ?? 'medium')) {
        'high', 'vip' => ['class' => 'badge-danger',  'label' => 'HIGH PRIORITY'],
        'medium'      => ['class' => 'badge-warning', 'label' => 'MEDIUM PRIORITY'],
        default       => ['class' => 'badge-info',    'label' => 'STANDARD'],
    };

    $sourceIcon = match(strtolower($lead->source ?? '')) {
        'waitlist'     => '📋',
        'quiz'         => '🧪',
        'referral'     => '🤝',
        'social'       => '📱',
        'direct'       => '🔗',
        default        => '📬',
    };
@endphp

<p class="email-heading">🔥 High-Priority Lead Captured</p>
<p class="email-subheading">{{ $lead->created_at->format('d F Y — H:i') }} UTC</p>

<div style="margin-bottom: 24px; display: flex; gap: 8px; align-items: center;">
    <span class="status-badge {{ $priorityBadge['class'] }}">{{ $priorityBadge['label'] }}</span>
    <span class="status-badge badge-info">{{ $sourceIcon }} {{ strtoupper($lead->source ?? 'UNKNOWN SOURCE') }}</span>
</div>

{{-- Lead Details --}}
<div class="info-box">
    <dl style="margin: 0;">
        @foreach([
            ['label' => 'Email',     'value' => $lead->email, 'bold' => true],
            ['label' => 'Source',    'value' => ucfirst($lead->source ?? 'Unknown')],
            ['label' => 'Language',  'value' => strtoupper($lead->preferred_language ?? 'EN')],
            ['label' => 'Captured',  'value' => $lead->created_at->diffForHumans()],
        ] as $row)
        <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
            <dt style="min-width: 120px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.1em;">{{ $row['label'] }}</dt>
            <dd style="margin: 0; font-size: 13px; color: #F5F2EE; {{ isset($row['bold']) ? 'font-weight: 600;' : '' }}">{{ $row['value'] }}</dd>
        </div>
        @endforeach
    </dl>
</div>

{{-- Product Interest --}}
@if($lead->product_id)
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.08em;">
    Product Interest
</h3>
<div style="background-color: #1A1A1A; border: 1px solid #2A2A2A; border-left: 3px solid #E8500A; padding: 14px 16px; border-radius: 2px; margin-bottom: 24px;">
    <p style="margin: 0; font-size: 14px; color: #E8500A; font-weight: 600;">
        {{ $lead->product?->name ?? 'Product #' . $lead->product_id }}
    </p>
    @if($lead->product)
    <p style="margin: 6px 0 0; font-size: 12px; color: #6B6B6B; font-family: monospace;">
        SKU: {{ $lead->product->sku }}
        @if($lead->product->stock_quantity !== null)
        · Stock: {{ $lead->product->stock_quantity }} units
        @endif
    </p>
    @endif
</div>
@endif

{{-- Metadata / Quiz Answers --}}
@if(!empty($lead->metadata))
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.08em;">
    Additional Data
</h3>
<div class="info-box">
    <dl style="margin: 0;">
        @foreach($lead->metadata as $key => $val)
        <div style="display: flex; gap: 12px; padding: 4px 0;">
            <dt style="min-width: 140px; font-size: 11px; color: #6B6B6B; text-transform: uppercase; letter-spacing: 0.08em;">
                {{ ucwords(str_replace('_', ' ', $key)) }}
            </dt>
            <dd style="margin: 0; font-size: 13px; color: #F5F2EE;">
                {{ is_array($val) ? implode(', ', $val) : $val }}
            </dd>
        </div>
        @endforeach
    </dl>
</div>
@endif

<hr class="email-divider">

<div class="email-btn-center" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
    <a href="{{ url('/admin/leads/' . $lead->id) }}" class="email-btn">View Lead Profile</a>
    <a href="mailto:{{ $lead->email }}" style="display: inline-block; padding: 12px 28px; border: 1px solid #E8500A; color: #E8500A; text-decoration: none; font-size: 13px; font-weight: 600; letter-spacing: 0.08em; border-radius: 1px;">
        Reply Directly
    </a>
</div>

<p class="email-text" style="text-align: center; font-size: 11px; margin-top: 20px;">
    Automated lead alert from FERRO CRM. Manage lead scoring in the admin settings.
</p>

@endsection
