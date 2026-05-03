@extends('emails._layout', ['locale' => 'en', 'isRtl' => false])

@section('email_title', '[FERRO Admin] Skin quiz: ' . $lead->email)
@section('header_class', 'admin-alert-header')

@section('email_body')

@php
    $qr = $lead->quiz_results ?? [];
    $answers = $qr['answers'] ?? [];
@endphp

<p class="email-heading">New skin quiz response</p>
<p class="email-subheading">{{ $lead->created_at->format('d F Y — H:i') }} · {{ strtoupper($lead->preferred_language ?? 'EN') }}</p>

<div class="info-box">
    <dl style="margin: 0;">
        <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
            <dt style="min-width: 120px; font-size: 11px; color: #6B6B6B; text-transform: uppercase;">Email</dt>
            <dd style="margin: 0; font-size: 13px; color: #F5F2EE; font-weight: 600;">{{ $lead->email }}</dd>
        </div>
        @if(!empty($qr['profile']['label_en']))
        <div style="display: flex; gap: 12px; padding: 5px 0; border-bottom: 1px solid #2A2A2A;">
            <dt style="min-width: 120px; font-size: 11px; color: #6B6B6B; text-transform: uppercase;">Profile</dt>
            <dd style="margin: 0; font-size: 13px; color: #E8500A;">{{ $qr['profile']['label_en'] }}</dd>
        </div>
        @endif
    </dl>
</div>

@if(count($answers))
<h3 style="font-size: 13px; font-weight: 600; color: #FFFFFF; margin: 24px 0 12px; text-transform: uppercase; letter-spacing: 0.08em;">Answers</h3>
<div class="info-box">
    <dl style="margin: 0;">
        @foreach(\App\Support\SkinQuizCatalog::questions() as $idx => $q)
            @php $val = $answers[$idx] ?? $answers[(string) $idx] ?? null; @endphp
            @if($val)
                @php $label = $q['options'][$val]['en'] ?? $val; @endphp
                <div style="padding: 6px 0; border-bottom: 1px solid #2A2A2A;">
                    <dt style="font-size: 11px; color: #6B6B6B; text-transform: uppercase;">{{ $q['en'] }}</dt>
                    <dd style="margin: 4px 0 0; font-size: 13px; color: #F5F2EE;">{{ $label }}</dd>
                </div>
            @endif
        @endforeach
    </dl>
</div>
@endif

@if(!empty($qr['tags']))
<p style="font-size: 12px; color: #6B6B6B; margin-top: 16px;"><strong>Tags:</strong> {{ implode(', ', $qr['tags']) }}</p>
@endif

<hr class="email-divider">

<div class="email-btn-center">
    <a href="{{ route('admin.quiz-responses.index') }}" class="email-btn">View all quiz responses</a>
</div>

@endsection
