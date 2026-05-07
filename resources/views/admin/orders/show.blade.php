@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_number)
@section('page_title', 'Order #' . $order->order_number)
@section('breadcrumb', 'Admin / Orders / #' . $order->order_number)

@section('content')

<div class="page-header">
    <div style="display:flex; align-items:center; gap: 14px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">{{ __('admin.orders.back') }}</a>
        <h1>#{{ $order->order_number }}</h1>
        @php
            $sb = ['delivered'=>'badge-success','shipped'=>'badge-info','confirmed'=>'badge-success','processing'=>'badge-warning','pending_payment'=>'badge-warning','cancelled'=>'badge-danger','refunded'=>'badge-neutral'][$order->status] ?? 'badge-neutral';
        @endphp
        <span class="badge {{ $sb }}" style="font-size: 13px; padding: 4px 12px;">
            {{ ucwords(str_replace('_',' ',$order->status)) }}
        </span>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.orders.invoice', $order) }}" class="btn btn-secondary">{{ __('admin.orders.invoice_pdf') }}</a>
    </div>
</div>

<div class="grid-2" style="gap: 24px; align-items: start;">

    {{-- Left column --}}
    <div style="display: flex; flex-direction: column; gap: 20px;">

        {{-- Order Items --}}
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.orders.items_title', ['count' => $order->items->sum('quantity')]) }}</h2></div>
            <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.orders.th_product') }}</th>
                        <th style="text-align:center;">{{ __('admin.orders.th_qty') }}</th>
                        <th style="text-align:right;">{{ __('admin.orders.th_unit') }}</th>
                        <th style="text-align:right;">{{ __('admin.orders.th_line_total') }}</th>
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
                        <td style="text-align:right;" class="mono">{{ ferro_money($item->unit_price, $order->currency) }}</td>
                        <td style="text-align:right;" class="mono">{{ ferro_money($item->line_total, $order->currency) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @foreach([
                        [__('admin.orders.subtotal'), ferro_money($order->subtotal, $order->currency)],
                        [__('admin.orders.shipping'), $order->shipping_amount > 0 ? ferro_money($order->shipping_amount, $order->currency) : __('admin.orders.free')],
                        [__('admin.orders.tax'),      ferro_money($order->tax_amount, $order->currency)],
                    ] as [$label, $val])
                    <tr>
                        <td colspan="3" style="text-align:right; color: var(--admin-muted); font-size:12px; padding: 6px 14px;">{{ $label }}</td>
                        <td style="text-align:right; padding: 6px 14px;" class="mono">{{ $val }}</td>
                    </tr>
                    @endforeach
                    @if($order->discount_amount > 0)
                    <tr>
                        <td colspan="3" style="text-align:right; color: var(--admin-green); font-size:12px; padding: 6px 14px;">{{ __('admin.orders.discount') }} @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
                        <td style="text-align:right; padding: 6px 14px; color:var(--admin-green);" class="mono">−{{ ferro_money($order->discount_amount, $order->currency) }}</td>
                    </tr>
                    @endif
                    <tr style="background: rgba(232,80,10,0.06); border-top: 1px solid var(--admin-border);">
                        <td colspan="3" style="text-align:right; font-weight:700; padding: 10px 14px;">{{ __('admin.orders.grand_total') }}</td>
                        <td style="text-align:right; padding:10px 14px; font-weight:700; color: var(--admin-orange);" class="mono">{{ ferro_money($order->total, $order->currency) }}</td>
                    </tr>
                </tfoot>
            </table>
            </div>
        </div>

        {{-- Customer & Shipping --}}
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.orders.customer_shipping') }}</h2></div>
            <div class="admin-card-body">
                @php
                    $bill = $order->billing_address ?? [];
                    $guestName = trim(($bill['first_name'] ?? '').' '.($bill['last_name'] ?? ''));
                    $guestEmail = $bill['email'] ?? $order->lead?->email;
                    $guestPhone = $bill['phone'] ?? ($order->shipping_address['phone'] ?? null);
                @endphp

                @if(!$order->user && ($guestName !== '' || $guestEmail))
                <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--admin-border);">
                    <div class="form-label" style="margin-bottom: 6px;">{{ __('admin.orders.guest_checkout') }}</div>
                    <div style="font-weight: 600;">{{ $guestName !== '' ? $guestName : '—' }}</div>
                    @if($guestEmail)<div class="text-muted text-sm">{{ $guestEmail }}</div>@endif
                    @if($guestPhone)<div class="text-muted text-sm">📞 {{ $guestPhone }}</div>@endif
                </div>
                @endif

                @if($order->user)
                <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid var(--admin-border);">
                    <div class="form-label" style="margin-bottom: 6px;">Account</div>
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

        @if($order->returnRequests->isNotEmpty())
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Return requests</h2></div>
            <div class="admin-card-body">
                <p class="text-muted text-sm" style="margin-bottom: 14px;">
                    Set status to <strong>Denied</strong> to reject a return. The customer sees this as “Denied” and any <strong>Admin notes</strong> you save are shown on their order page.
                </p>
                @foreach($order->returnRequests as $req)
                    <div style="padding: 16px 0; border-bottom: 1px solid var(--admin-border);">
                        <div class="text-muted text-sm" style="margin-bottom: 8px;">Submitted {{ $req->created_at->format('d M Y H:i') }}</div>
                        <div style="margin-bottom: 8px;"><span class="badge {{ $req->status === 'rejected' ? 'badge-danger' : 'badge-info' }}" style="text-transform: capitalize;">{{ $req->status === 'rejected' ? 'Denied' : $req->status }}</span></div>
                        <div style="font-size: 13px; margin-bottom: 12px; color: #C5C1BB;">{{ $req->customer_reason }}</div>
                        <form method="POST" action="{{ route('admin.orders.return-requests.update', [$order, $req]) }}" class="stack-form">
                            @csrf
                            @method('PATCH')
                            <div class="form-group">
                                <label class="form-label" for="return_status_{{ $req->id }}">Status</label>
                                <select id="return_status_{{ $req->id }}" name="status" class="form-input form-select" required>
                                    @foreach(['pending' => 'Pending review', 'approved' => 'Approved', 'rejected' => 'Denied', 'completed' => 'Completed'] as $val => $lab)
                                        <option value="{{ $val }}" @selected($req->status === $val)>{{ $lab }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="return_notes_{{ $req->id }}">Admin notes (customer may be contacted by email separately)</label>
                                <textarea id="return_notes_{{ $req->id }}" name="admin_notes" class="form-input form-textarea" style="min-height: 70px;">{{ old('admin_notes', $req->admin_notes) }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-secondary btn-sm">Update return</button>
                        </form>
                    </div>
                @endforeach
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
