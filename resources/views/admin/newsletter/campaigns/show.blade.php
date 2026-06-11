@extends('admin.layouts.app')

@section('title', $campaign->subject_en)
@section('page_title', __('admin.newsletter.campaign_preview'))
@section('breadcrumb')
    Admin / {{ __('admin.newsletter.campaigns_title') }} / {{ Str::limit($campaign->subject_en, 40) }}
@endsection

@section('content')

<div class="page-header" style="align-items:flex-start;">
    <div>
        <h1>{{ $campaign->subject_en }}</h1>
        <div style="font-size:12px; color:var(--admin-muted); margin-top:4px;">
            {{ $campaign->send_to === 'all' ? __('admin.newsletter.all_subscribers') : __('admin.newsletter.selected_subscribers') }}
            @if($campaign->product)
                · {{ __('admin.newsletter.featured_product') }}: {{ $campaign->product->name }}
            @endif
        </div>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.newsletter.settings.edit') }}" class="btn btn-secondary">{{ __('admin.newsletter.back_to_newsletter') }}</a>
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary">{{ __('admin.orders.back') }}</a>
        @if(! $campaign->isSent())
        <form method="POST" action="{{ route('admin.newsletter.campaigns.send', $campaign) }}" onsubmit="return confirm(@json(__('admin.newsletter.send_confirm')));">
            @csrf
            <button type="submit" class="btn btn-primary">{{ __('admin.newsletter.send_now') }}</button>
        </form>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-green);">
        <div class="admin-card-body" style="color: var(--admin-green);">{{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-red);">
        <div class="admin-card-body" style="color: var(--admin-red);">{{ session('error') }}</div>
    </div>
@endif

<div class="grid-2">
    <div class="admin-card">
        <div class="admin-card-header"><h2 class="admin-card-title">EN</h2></div>
        <div class="admin-card-body">
            <div style="font-weight:600; margin-bottom:12px;">{{ $campaign->subject_en }}</div>
            <div style="white-space:pre-wrap; color:var(--admin-muted); font-size:13px; line-height:1.6;">{{ $campaign->body_en }}</div>
        </div>
    </div>
    <div class="admin-card">
        <div class="admin-card-header"><h2 class="admin-card-title">AR</h2></div>
        <div class="admin-card-body" dir="rtl">
            <div style="font-weight:600; margin-bottom:12px;">{{ $campaign->subject_ar ?: '—' }}</div>
            <div style="white-space:pre-wrap; color:var(--admin-muted); font-size:13px; line-height:1.6;">{{ $campaign->body_ar ?: '—' }}</div>
        </div>
    </div>
</div>

<div class="admin-card" style="margin-top:20px;">
    <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.delivery_status') }}</h2></div>
    <div class="admin-card-body">
        <div style="display:flex; gap:24px; flex-wrap:wrap;">
            <div>
                <div style="font-size:11px; color:var(--admin-muted); text-transform:uppercase;">{{ __('admin.dashboard.th_status') }}</div>
                <div style="margin-top:4px;">
                    @if($campaign->isSent())
                        <span class="badge badge-green">{{ __('admin.newsletter.status_sent') }}</span>
                    @else
                        <span class="badge badge-orange">{{ __('admin.newsletter.status_draft') }}</span>
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:11px; color:var(--admin-muted); text-transform:uppercase;">{{ __('admin.newsletter.sent_count') }}</div>
                <div style="margin-top:4px; font-size:18px; font-weight:600;">{{ $campaign->sent_count }}</div>
            </div>
            @if($campaign->sent_at)
            <div>
                <div style="font-size:11px; color:var(--admin-muted); text-transform:uppercase;">{{ __('admin.newsletter.sent_at') }}</div>
                <div style="margin-top:4px;">{{ $campaign->sent_at->format('M j, Y H:i') }}</div>
            </div>
            @endif
        </div>
    </div>
</div>

@if($campaign->send_to === 'selected' && $campaign->subscribers->isNotEmpty())
<div class="admin-card" style="margin-top:20px;">
    <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.selected_subscribers') }}</h2></div>
    <div class="admin-card-body">
        @foreach($campaign->subscribers as $subscriber)
            <span class="badge badge-muted" style="margin:0 6px 6px 0;">{{ $subscriber->email }}</span>
        @endforeach
    </div>
</div>
@endif

@endsection
