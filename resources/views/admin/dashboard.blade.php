@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

{{-- KPI Stats Grid --}}
<div class="grid-4" style="margin-bottom: 24px;">
    @foreach([
        ['label' => 'Total Revenue',    'value' => '$' . number_format($stats['revenue'], 2),    'icon' => '💰', 'bg' => 'rgba(232,80,10,0.1)',  'sub' => 'Paid orders'],
        ['label' => 'Total Orders',     'value' => $stats['total_orders'],                        'icon' => '📦', 'bg' => 'rgba(59,130,246,0.1)', 'sub' => $stats['pending_orders'] . ' pending'],
        ['label' => 'Customers',        'value' => $stats['total_customers'],                     'icon' => '👥', 'bg' => 'rgba(34,197,94,0.1)',  'sub' => $stats['blocked_users'] . ' blocked'],
        ['label' => 'Leads / Waitlist', 'value' => $stats['total_leads'],                         'icon' => '📋', 'bg' => 'rgba(234,179,8,0.1)',  'sub' => $stats['waitlist_total'] . ' on waitlist'],
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
        ⚠️ <strong>{{ $stats['low_stock'] }}</strong> product(s) are low on stock or out of stock
    </a>
    @endif
    @if($stats['pending_orders'] > 0)
    <a href="{{ route('admin.orders.index', ['status' => 'pending_payment']) }}" class="flash flash-error" style="text-decoration: none; margin: 0; flex: 1; min-width: 200px;">
        🔴 <strong>{{ $stats['pending_orders'] }}</strong> order(s) awaiting payment confirmation
    </a>
    @endif
</div>
@endif

<div class="grid-2" style="gap: 24px;">

    {{-- Recent Orders --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">View all</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
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
                        <td>{{ $order->user?->name ?? 'Guest' }}</td>
                        <td class="mono">${{ number_format($order->total, 2) }}</td>
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
                    <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Low Stock Products --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2 class="admin-card-title">⚠️ Low Stock</h2>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">All products</a>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th style="text-align:center;">Stock</th>
                        <th>Action</th>
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
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-xs">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-muted" style="text-align:center; padding: 24px;">All products well stocked 🎉</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Recent Leads --}}
<div class="admin-card" style="margin-top: 24px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Recent Leads</h2>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.leads.export') }}" class="btn btn-secondary btn-sm">↓ Export CSV</a>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary btn-sm">View all</a>
        </div>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Source</th>
                    <th>Priority</th>
                    <th>Waitlist</th>
                    <th>Joined</th>
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
                <tr><td colspan="5" class="text-muted" style="text-align:center; padding: 24px;">No leads yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
