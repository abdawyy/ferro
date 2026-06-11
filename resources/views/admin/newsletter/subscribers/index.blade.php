@extends('admin.layouts.app')

@section('title', __('admin.newsletter.subscribers_title'))
@section('page_title', __('admin.newsletter.subscribers_title'))
@section('breadcrumb')
    Admin / {{ __('admin.newsletter.subscribers_title') }}
@endsection

@section('content')

<div class="page-header" style="align-items: flex-start;">
    <div>
        <a href="{{ route('admin.newsletter.settings.edit') }}" class="btn btn-secondary" style="margin-bottom: 12px;">{{ __('admin.newsletter.back_to_newsletter') }}</a>
        <h1>{{ __('admin.newsletter.subscribers_title') }}</h1>
    </div>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary">{{ __('admin.newsletter.campaigns_title') }}</a>
        <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary">{{ __('admin.newsletter.new_campaign') }}</a>
        <a href="{{ route('admin.newsletter.subscribers.export') }}" class="btn btn-secondary">{{ __('admin.dashboard.export_csv') }}</a>
    </div>
</div>

<div class="grid-2" style="margin-bottom: 20px;">
    <div class="stat-card">
        <div class="stat-label">{{ __('admin.newsletter.active_subscribers') }}</div>
        <div class="stat-value">{{ number_format($stats['active']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">{{ __('admin.newsletter.total_subscribers') }}</div>
        <div class="stat-value">{{ number_format($stats['total']) }}</div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('admin.dashboard.th_email') }}</th>
                    <th>{{ __('admin.newsletter.coupon_code') }}</th>
                    <th>{{ __('admin.newsletter.discount_percent') }}</th>
                    <th>{{ __('admin.dashboard.th_status') }}</th>
                    <th>{{ __('admin.dashboard.th_joined') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                <tr>
                    <td data-label="{{ __('admin.dashboard.th_email') }}">{{ $subscriber->email }}</td>
                    <td data-label="{{ __('admin.newsletter.coupon_code') }}" class="mono">{{ $subscriber->coupon_code }}</td>
                    <td data-label="{{ __('admin.newsletter.discount_percent') }}">{{ $subscriber->discount_percent }}%</td>
                    <td data-label="{{ __('admin.dashboard.th_status') }}">
                        @if($subscriber->isActive())
                            <span class="badge badge-green">{{ __('admin.newsletter.status_active') }}</span>
                        @else
                            <span class="badge badge-muted">{{ __('admin.newsletter.status_unsubscribed') }}</span>
                        @endif
                    </td>
                    <td data-label="{{ __('admin.dashboard.th_joined') }}">{{ $subscriber->subscribed_at?->format('M j, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding:24px; color:var(--admin-muted);">
                        {{ __('admin.newsletter.no_subscribers') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    {{ $subscribers->links() }}
</div>

@endsection
