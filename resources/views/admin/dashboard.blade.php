@extends('admin.layouts.app')

@section('title', __('admin.dashboard.title'))
@section('page_title', __('admin.dashboard.title'))

@section('content')

{{-- KPI Stats Grid --}}
<div class="grid-4" style="margin-bottom: 24px;">
    @foreach([
        ['label' => __('admin.dashboard.total_revenue'),    'value' => ferro_money($stats['revenue'], 'EGP'),    'icon' => '💰', 'bg' => 'rgba(232,80,10,0.1)',  'sub' => __('admin.dashboard.paid_orders_sub')],
        ['label' => __('admin.dashboard.total_orders'),     'value' => $stats['total_orders'],                        'icon' => '📦', 'bg' => 'rgba(59,130,246,0.1)', 'sub' => __('admin.dashboard.pending_suffix', ['count' => $stats['pending_orders']])],
        ['label' => __('admin.dashboard.customers'),        'value' => $stats['total_customers'],                     'icon' => '👥', 'bg' => 'rgba(34,197,94,0.1)',  'sub' => __('admin.dashboard.blocked_suffix', ['count' => $stats['blocked_users']])],
        ['label' => __('admin.dashboard.leads_waitlist'), 'value' => $stats['total_leads'],                         'icon' => '📋', 'bg' => 'rgba(234,179,8,0.1)',  'sub' => __('admin.dashboard.waitlist_suffix', ['count' => $stats['waitlist_total']])],
    ] as $stat)
    <div class="stat-card">
        <div class="stat-icon" style="background: {{ $stat['bg'] }};">{{ $stat['icon'] }}</div>
        <div>
            <div class="stat-label">{{ $stat['label'] }}</div>
            <div class="stat-value">{{ $stat['value'] }}</div>
            <div class="stat-sub">{{ $stat['sub'] }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Alerts row --}}
@if($stats['low_stock'] > 0 || $stats['pending_orders'] > 0)
<div style="display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;">
    @if($stats['low_stock'] > 0)
    <a href="{{ route('admin.products.index', ['status' => 'active']) }}" class="flash flash-warning" style="text-decoration: none; margin: 0; flex: 1; min-width: 200px;">
        ⚠️ {!! __('admin.dashboard.low_stock_alert', ['count' => $stats['low_stock']]) !!}
    </a>
    @endif
    @if($stats['pending_orders'] > 0)
    <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="flash flash-error" style="text-decoration: none; margin: 0; flex: 1; min-width: 200px;">
        🔴 {!! __('admin.dashboard.pending_pay_alert', ['count' => $stats['pending_orders']]) !!}
    </a>
    @endif
</div>
@endif

<div class="grid-2" style="gap: 24px;">

    {{-- Recent Orders --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">{{ __('admin.dashboard.recent_orders') }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">{{ __('admin.dashboard.view_all') }}</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.dashboard.th_order') }}</th>
                        <th>{{ __('admin.dashboard.th_customer') }}</th>
                        <th>{{ __('admin.dashboard.th_total') }}</th>
                        <th>{{ __('admin.dashboard.th_status') }}</th>
                        <th>{{ __('admin.dashboard.th_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-orange mono">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td>{{ $order->user?->name ?? __('admin.dashboard.guest') }}</td>
                        <td class="mono">{{ ferro_money($order->total, $order->currency) }}</td>
                        <td>
                            @php
                                $badgeMap = ['delivered'=>'badge-success','shipped'=>'badge-info','confirmed'=>'badge-success','processing'=>'badge-warning','pending_payment'=>'badge-warning','cancelled'=>'badge-danger','refunded'=>'badge-neutral'];
                            @endphp
                            <span class="badge {{ $badgeMap[$order->status] ?? 'badge-neutral' }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="text-muted text-sm">{{ $order->created_at->format('d M y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">{{ __('admin.dashboard.no_orders') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">⚠️ {{ __('admin.dashboard.low_stock') }}</h2>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">{{ __('admin.dashboard.all_products') }}</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.dashboard.th_product') }}</th>
                        <th>{{ __('admin.dashboard.th_sku') }}</th>
                        <th style="text-align:center;">{{ __('admin.dashboard.th_stock') }}</th>
                        <th>{{ __('admin.dashboard.th_action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                    <tr>
                        <td style="font-weight: 500;">{{ $product->getTranslation('name','en') }}</td>
                        <td class="mono text-muted">{{ $product->sku }}</td>
                        <td style="text-align:center;">
                            <span class="badge {{ $product->stock_quantity == 0 ? 'badge-danger' : 'badge-warning' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-xs">{{ __('admin.dashboard.edit') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted" style="text-align:center; padding: 24px;">{{ __('admin.dashboard.well_stocked') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Recent Leads --}}
<div class="admin-card" style="margin-top: 24px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">{{ __('admin.dashboard.recent_leads') }}</h2>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.leads.export') }}" class="btn btn-secondary btn-sm">{{ __('admin.dashboard.export_csv') }}</a>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary btn-sm">{{ __('admin.dashboard.view_all') }}</a>
        </div>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>{{ __('admin.dashboard.th_email') }}</th>
                    <th>{{ __('admin.dashboard.th_source') }}</th>
                    <th>{{ __('admin.dashboard.th_priority') }}</th>
                    <th>{{ __('admin.dashboard.th_waitlist') }}</th>
                    <th>{{ __('admin.dashboard.th_joined') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLeads as $lead)
                <tr>
                    <td>{{ $lead->email }}</td>
                    <td><span class="badge badge-neutral">{{ $lead->source ?? 'unknown' }}</span></td>
                    <td>
                        @php $pb = ['vip'=>'badge-danger','high'=>'badge-warning','standard'=>'badge-neutral']; @endphp
                        <span class="badge {{ $pb[$lead->priority] ?? 'badge-neutral' }}">{{ $lead->priority ?? 'standard' }}</span>
                    </td>
                    <td>{{ $lead->on_waitlist ? '✓' : '—' }}</td>
                    <td class="text-muted text-sm">{{ $lead->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">{{ __('admin.dashboard.no_leads') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
