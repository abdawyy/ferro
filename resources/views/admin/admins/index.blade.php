@extends('admin.layouts.app')

@section('title', 'Administrators')
@section('page_title', 'Administrators')
@section('breadcrumb', 'Admin / Staff')

@section('content')

<div class="page-header">
    <div>
        <h1>Administrators</h1>
        <p class="text-muted text-sm" style="margin: 6px 0 0;">Accounts that can access this admin portal.</p>
    </div>
    <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">+ New administrator</a>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td style="font-weight: 600;">{{ $admin->name }}</td>
                    <td class="text-muted text-sm">{{ $admin->email }}</td>
                    <td class="text-muted text-sm">{{ $admin->created_at->format('d M Y') }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.users.show', $admin) }}" class="btn btn-secondary btn-xs">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-muted" style="text-align: center; padding: 32px;">No administrators found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($admins->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border);">
        {{ $admins->links() }}
    </div>
    @endif
</div>

<div class="admin-card" style="margin-top: 20px;">
    <div class="admin-card-header">
        <h2 class="admin-card-title">Promote a customer</h2>
    </div>
    <div class="admin-card-body text-muted text-sm">
        Open any customer from <a href="{{ route('admin.users.index') }}" class="text-orange">Users</a> and use <strong>Grant admin access</strong> to turn their existing login into an admin account.
    </div>
</div>

@endsection
