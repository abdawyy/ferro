@extends('admin.layouts.app')

@section('title', 'Users')
@section('page_title', 'Users')
@section('breadcrumb', 'Admin / Users')

@section('content')

<div class="page-header">
    <h1>Users</h1>
    <div class="text-muted text-sm">{{ $users->total() }} registered customers</div>
</div>

<form method="GET" class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" for="search">Search</label>
            <input id="search" name="search" type="search" value="{{ request('search') }}"
                   class="form-input" placeholder="Name or email…">
        </div>
        <div style="min-width: 160px;">
            <label class="form-label" for="filter-status">Account Status</label>
            <select id="filter-status" name="status" class="form-input form-select">
                <option value="">All users</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="blocked"  {{ request('status') === 'blocked'  ? 'selected' : '' }}>Blocked</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="admin-card">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Language</th>
                    <th style="text-align:center;">Orders</th>
                    <th>Joined</th>
                    <th>Last Login</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="font-weight: 600;">{{ $user->name }}</div>
                        <div class="text-muted text-sm">{{ $user->email }}</div>
                    </td>
                    <td><span class="badge badge-neutral">{{ strtoupper($user->preferred_language ?? 'EN') }}</span></td>
                    <td style="text-align:center;">{{ $user->orders_count }}</td>
                    <td class="text-muted text-sm">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="text-muted text-sm">{{ $user->last_login_at?->diffForHumans() ?? '—' }}</td>
                    <td>
                        @if($user->is_blocked)
                        <span class="badge badge-danger">Blocked</span>
                        @else
                        <span class="badge badge-success">Active</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;" x-data="{ showBlockModal: false }">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-xs">View</a>

                            @if($user->is_blocked)
                            <form method="POST" action="{{ route('admin.users.unblock', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-xs" style="background: rgba(34,197,94,0.1); color: var(--admin-green); border-color: rgba(34,197,94,0.3);">
                                    Unblock
                                </button>
                            </form>
                            @else
                            <button type="button" class="btn btn-danger btn-xs"
                                    @click="showBlockModal = true">Block</button>

                            {{-- Block modal --}}
                            <div x-show="showBlockModal"
                                 style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:100;display:flex;align-items:center;justify-content:center;"
                                 @keydown.escape.window="showBlockModal = false">
                                <div style="background:var(--admin-surface);border:1px solid var(--admin-border);border-radius:6px;padding:24px;max-width:420px;width:90%;">
                                    <h3 style="margin:0 0 8px;color:#fff;">Block Account</h3>
                                    <p style="color:var(--admin-muted);font-size:13px;margin:0 0 16px;">
                                        Block <strong style="color:#fff;">{{ $user->email }}</strong>? They won't be able to log in.
                                    </p>
                                    <form method="POST" action="{{ route('admin.users.block', $user) }}">
                                        @csrf @method('PATCH')
                                        <div class="form-group">
                                            <label class="form-label" for="reason-{{ $user->id }}">Reason (optional)</label>
                                            <textarea id="reason-{{ $user->id }}" name="reason"
                                                      class="form-input form-textarea" style="min-height:70px;"
                                                      placeholder="Reason for blocking…"></textarea>
                                        </div>
                                        <div style="display:flex;gap:8px;justify-content:flex-end;">
                                            <button type="button" class="btn btn-secondary" @click="showBlockModal = false">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Block Account</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding: 40px; color: #4B4B4B;">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted text-sm">Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}</div>
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
