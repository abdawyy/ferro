@extends('admin.layouts.app')

@section('title', __('admin.newsletter.campaigns_title'))
@section('page_title', __('admin.newsletter.campaigns_title'))
@section('breadcrumb')
    Admin / {{ __('admin.newsletter.campaigns_title') }}
@endsection

@section('content')

<div class="page-header">
    <h1>{{ __('admin.newsletter.campaigns_title') }}</h1>
    <a href="{{ route('admin.newsletter.campaigns.create') }}" class="btn btn-primary">{{ __('admin.newsletter.new_campaign') }}</a>
</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-green);">
        <div class="admin-card-body" style="color: var(--admin-green);">{{ session('success') }}</div>
    </div>
@endif

<div class="admin-card">
    <div class="admin-card-body" style="padding:0;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('admin.newsletter.campaign_subject') }}</th>
                    <th>{{ __('admin.newsletter.recipients') }}</th>
                    <th>{{ __('admin.dashboard.th_status') }}</th>
                    <th>{{ __('admin.newsletter.sent_count') }}</th>
                    <th>{{ __('admin.dashboard.th_date') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                <tr>
                    <td data-label="{{ __('admin.newsletter.campaign_subject') }}">{{ $campaign->subject_en }}</td>
                    <td data-label="{{ __('admin.newsletter.recipients') }}">
                        {{ $campaign->send_to === 'all' ? __('admin.newsletter.all_subscribers') : __('admin.newsletter.selected_subscribers') }}
                    </td>
                    <td data-label="{{ __('admin.dashboard.th_status') }}">
                        @if($campaign->isSent())
                            <span class="badge badge-green">{{ __('admin.newsletter.status_sent') }}</span>
                        @else
                            <span class="badge badge-orange">{{ __('admin.newsletter.status_draft') }}</span>
                        @endif
                    </td>
                    <td data-label="{{ __('admin.newsletter.sent_count') }}">{{ $campaign->sent_count }}</td>
                    <td data-label="{{ __('admin.dashboard.th_date') }}">{{ $campaign->created_at->format('M j, Y') }}</td>
                    <td><a href="{{ route('admin.newsletter.campaigns.show', $campaign) }}" class="btn btn-secondary btn-sm">{{ __('admin.dashboard.view_all') }}</a></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:24px; color:var(--admin-muted);">
                        {{ __('admin.newsletter.no_campaigns') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 20px;">
    {{ $campaigns->links() }}
</div>

@endsection
