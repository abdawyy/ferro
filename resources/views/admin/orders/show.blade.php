@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('page_title', 'Order #' . $order->order_number)
@section('breadcrumb', 'Admin / Orders / #' . $order->order_number)

@section('content')

<div class="page-header">
    <div style="display:flex; align-items:center; gap: 14px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        <h1>#{{ $order->order_number }}</h1>
        @php
            $sb = ['delivered'=>'badge-success','shipped'=>'badge-info','confirmed'=>'badge-success','processing'=>'badge-warning','pending_payment'=>'badge-warning','cancelled'=>'badge-danger','refunded'=>'badge-neutral'][$order->status] ?? 'badge-neutral';
        @endphp
        <span class="badge {{ $sb }}" style="font-size: 13px; padding: 4px 12px;">
            {{ ucwords(str_replace('_',' ',$order->status)) }}
        </span>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary">↓ Invoice PDF</a>
    </div>
</div>

<div class="grid-2" style="gap: 24px; align-items: start;">

    {{-- Left column --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- Order Items --}}
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Items ({{ $order->items->sum('quantity') }})</h2></div>
            <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Unit</th>
                        <th style="text-align:right;">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div style="font-weight: 500;">{{ $item->product_name }}</div>
                            @if($item->variant)<div class="text-muted text-sm">{{ $item->variant }}</div>@endif
                        </td>
                        <td style="text-align:center;">{{ $item->quantity }}</td>
                        <td style="text-align:right;" class="mono">${{ number_format($item->unit_price, 2) }}</td>
                        <td style="text-align:right;" class="mono">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @foreach([
                        ['Subtotal', '$' . number_format($order->subtotal, 2)],
                        ['Shipping', $order->shipping_amount > 0 ? '$' . number_format($order->shipping_amount, 2) : 'Free'],
                        ['Tax',      '$' . number_format($order->tax_amount, 2)],
                    ] as [$label, $val])
                    <tr>
                        <td colspan="3" style="text-align:right; color: var(--admin-muted); font-size:12px; padding: 6px 14px;">{{ $label }}</td>
                        <td style="text-align:right; padding: 6px 14px;" class="mono">{{ $val }}</td>
                    </tr>
                    @endforeach
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="3" style="text-align:right; color: var(--admin-green); font-size:12px; padding: 6px 14px;">Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
                        <td style="text-align:right; padding: 6px 14px;" class="mono" style="color:var(--admin-green);">-${{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr style="background: rgba(232,80,10,0.06); border-top: 1px solid var(--admin-border);">
                        <td colspan="3" style="text-align:right; font-weight:700; padding: 10px 14px;">GRAND TOTAL</td>
                        <td style="text-align:right; padding:10px 14px; font-weight:700; color: var(--admin-orange);" class="mono">${{ number_format($order->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>

        {{-- Customer & Shipping --}}
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Customer & Shipping</h2></div>
            <div class="admin-card-body">
                @if($order->user)
                <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--admin-border);">
                    <div style="font-weight: 600;">{{ $order->user->name }}</div>
                    <div class="text-muted text-sm">{{ $order->user->email }}</div>
                    <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-secondary btn-xs" style="margin-top: 8px;">View Account</a>
                </div>
                @endif

                @if($addr = $order->shipping_address)
                <div>
                    <div class="form-label" style="margin-bottom: 6px;">Ship To</div>
                    <div>{{ $addr['name'] ?? $order->user?->name }}</div>
                    @foreach(array_filter([$addr['address'] ?? null, $addr['city'] ?? null, $addr['state'] ?? null, $addr['zip'] ?? null, $addr['country'] ?? null]) as $line)
                    <div class="text-muted text-sm">{{ $line }}</div>
                    @endforeach
                    @if($addr['phone'] ?? null)<div class="text-muted text-sm">📞 {{ $addr['phone'] }}</div>@endif
                </div>
                @endif
            </div>
        </div>

        {{-- Admin Notes --}}
        @if($order->admin_notes || $order->customer_notes)
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Notes</h2></div>
            <div class="admin-card-body">
                @if($order->customer_notes)
                <div style="margin-bottom: 12px;">
                    <div class="form-label">Customer Note</div>
                    <div style="background: #0D0D0D; padding: 10px 12px; border-radius: 3px; font-size: 13px; color: #C5C1BB;">
                        {{ $order->customer_notes }}
                    </div>
                </div>
                @endif
                @if($order->admin_notes)
                <div>
                    <div class="form-label">Admin Note</div>
                    <div style="background: #0D0D0D; padding: 10px 12px; border-radius: 3px; font-size: 13px; color: #C5C1BB;">
                        {{ $order->admin_notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Right column: Update Status --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- Order Meta --}}
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Order Info</h2></div>
            <div class="admin-card-body">
                @foreach([
                    ['Order #',      $order->order_number],
                    ['Invoice #',    $order->invoice_number ?? '—'],
                    ['Placed',       $order->created_at->format('d M Y, H:i')],
                    ['Language',     strtoupper($order->language ?? 'EN')],
                    ['Payment',      ucfirst($order->payment_method ?? '—')],
                    ['Payment ID',   $order->payment_transaction_id ?? '—'],
                    ['Carrier',      $order->carrier ?? '—'],
                    ['Tracking #',   $order->tracking_number ?? '—'],
                ] as [$label, $val])
                <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(36,36,36,0.5);">
                    <span class="text-muted text-sm">{{ $label }}</span>
                    <span class="text-sm mono" style="text-align:right;">{{ $val }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Update Status Form --}}
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Update Status</h2></div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PATCH')

                    <div class="form-group">
                        <label class="form-label" for="status">New Status</label>
                        <select id="status" name="status" required class="form-input form-select">
                            @foreach(['pending_payment' => 'Pending Payment','confirmed' => 'Confirmed','processing' => 'Processing','shipped' => 'Shipped','delivered' => 'Delivered','cancelled' => 'Cancelled','refunded' => 'Refunded'] as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tracking_number">Tracking Number</label>
                        <input id="tracking_number" name="tracking_number" type="text" class="form-input mono"
                               value="{{ old('tracking_number', $order->tracking_number ?? '') }}"
                               placeholder="e.g. 1Z999AA10123456784">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="carrier">Carrier</label>
                        <input id="carrier" name="carrier" type="text" class="form-input"
                               value="{{ old('carrier', $order->carrier ?? '') }}"
                               placeholder="e.g. DHL, FedEx, Aramex">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="admin_notes">Admin Note</label>
                        <textarea id="admin_notes" name="admin_notes" class="form-input form-textarea" style="min-height: 80px;"
                                  placeholder="Internal notes (not visible to customer)">{{ old('admin_notes', $order->admin_notes ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Update Order
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
