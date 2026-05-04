@extends('admin.layouts.app')

@section('title', $user->name)
@section('page_title', $user->name)
@section('breadcrumb', 'Admin / Users / ' . $user->name)

@section('content')

<div class="page-header">
    <div style="display: flex; align-items: center; gap: 14px;">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">← Back</a>
        <h1>{{ $user->name }}</h1>
        @if($user->is_blocked)
        <span class="badge badge-danger">Blocked</span>
        @else
        <span class="badge badge-success">Active</span>
        @endif
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        @if($user->is_admin)
            @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('admin.users.remove-admin', $user) }}" onsubmit="return confirm('Remove admin access for this account?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-secondary">Remove admin access</button>
            </form>
            @endif
        @else
            <form method="POST" action="{{ route('admin.users.make-admin', $user) }}" onsubmit="return confirm('Grant this user full admin access to the portal?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary">Grant admin access</button>
            </form>
        @endif
        @if($user->is_blocked)
        <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-secondary">Unblock Account</button>
        </form>
        @else
        <form method="POST" action="{{ route('admin.users.block', $user) }}" onsubmit="return confirm('Block this user?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">Block Account</button>
        </form>
        @endif
    </div>
</div>

<div class="grid-2" style="gap: 24px; align-items: start;">
    <div>
        {{-- Profile --}}
        <div class="admin-card" style="margin-bottom: 20px;">
            <div class="admin-card-header"><h2 class="admin-card-title">Profile</h2></div>
            <div class="admin-card-body">
                @foreach([
                    ['Name',         $user->name],
                    ['Email',        $user->email],
                    ['Role',         $user->is_admin ? 'Administrator' : 'Customer'],
                    ['Language',     strtoupper($user->preferred_language ?? 'EN')],
                    ['Joined',       $user->created_at->format('d F Y')],
                    ['Last Login',   $user->last_login_at?->format('d F Y, H:i') ?? 'Never'],
                    ['Email Verified', $user->email_verified_at ? '✓ ' . $user->email_verified_at->format('d M Y') : '✕ Not verified'],
                ] as [$label, $val])
                <div style="display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid rgba(36,36,36,0.5);">
                    <span class="text-muted text-sm">{{ $label }}</span>
                    <span class="text-sm">{{ $val }}</span>
                </div>
                @endforeach

                @if($user->is_blocked)
                <hr class="divider">
                <div class="flash flash-error" style="margin: 0;">
                    <div>
                        <strong>Blocked on {{ $user->blocked_at?->format('d M Y') ?? 'Unknown' }}</strong><br>
                        {{ $user->blocked_reason ?? 'No reason provided.' }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div>
        {{-- Orders --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">Orders ({{ $orders->total() }})</h2>
            </div>
            <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php $sb = ['delivered'=>'badge-success','shipped'=>'badge-info','confirmed'=>'badge-success','processing'=>'badge-warning','pending_payment'=>'badge-warning','cancelled'=>'badge-danger','refunded'=>'badge-neutral'][$order->status] ?? 'badge-neutral'; @endphp
                    <tr>
                        <td class="mono text-orange">#{{ $order->order_number }}</td>
                        <td class="mono">${{ number_format($order->total, 2) }}</td>
                        <td><span class="badge {{ $sb }}">{{ ucwords(str_replace('_',' ',$order->status)) }}</span></td>
                        <td class="text-muted text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-xs">View</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-muted" style="text-align:center; padding:20px;">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
            @if($orders->hasPages())
            <div style="padding: 12px 16px; border-top: 1px solid var(--admin-border);">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
