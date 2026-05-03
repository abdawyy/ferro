@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page_title', 'Orders')
@section('breadcrumb', 'Admin / Orders')

@section('content')

<div class="page-header">
    <h1>Orders</h1>
    <div class="text-muted text-sm">{{ $orders->total() }} total orders</div>
</div>

{{-- Status pills --}}
<div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
    <a href="{{ route('admin.orders.index') }}"
       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-secondary' }}">
        All ({{ $statusCounts->sum() }})
    </a>
    @foreach(['pending_payment' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled', 'refunded' => 'Refunded'] as $val => $label)
    <a href="{{ route('admin.orders.index', ['status' => $val] + request()->except('status')) }}"
       class="btn btn-sm {{ request('status') === $val ? 'btn-primary' : 'btn-secondary' }}">
        {{ $label }} ({{ $statusCounts[$val] ?? 0 }})
    </a>
    @endforeach
</div>

{{-- Search --}}
<form method="GET" style="margin-bottom: 16px;">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <div style="display: flex; gap: 8px;">
        <input type="search" name="search" value="{{ request('search') }}"
               class="form-input" style="max-width: 320px;" placeholder="Order #, customer name or email…">
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))<a href="{{ route('admin.orders.index', request()->except('search')) }}" class="btn btn-secondary">Clear</a>@endif
    </div>
</form>

<div class="admin-card">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php
                    $statusBadge = ['delivered'=>'badge-success','shipped'=>'badge-info','confirmed'=>'badge-success','processing'=>'badge-warning','pending_payment'=>'badge-warning','cancelled'=>'badge-danger','refunded'=>'badge-neutral'][$order->status] ?? 'badge-neutral';
                    $payBadge    = ['paid'=>'badge-success','pending'=>'badge-warning','failed'=>'badge-danger','refunded'=>'badge-neutral'][$order->payment_status ?? 'pending'] ?? 'badge-neutral';
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-orange mono">
                            #{{ $order->order_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $order->user?->name ?? 'Guest' }}</div>
                        <div class="text-muted text-sm">{{ $order->user?->email ?? ($order->shipping_address['email'] ?? '—') }}</div>
                    </td>
                    <td class="text-muted">{{ $order->items?->sum('quantity') ?? '—' }}</td>
                    <td class="mono">${{ number_format($order->total, 2) }}</td>
                    <td><span class="badge {{ $payBadge }}">{{ ucfirst($order->payment_status ?? 'pending') }}</span></td>
                    <td><span class="badge {{ $statusBadge }}">{{ ucwords(str_replace('_', ' ', $order->status)) }}</span></td>
                    <td class="text-muted text-sm">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-xs">View</a>
                            <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary btn-xs">↓ PDF</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 40px; color: #4B4B4B;">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted text-sm">Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }}</div>
        {{ $orders->links() }}
    </div>
    @endif
</div>

@endsection
