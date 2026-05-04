@extends('admin.layouts.app')

@section('title', 'Skin Quiz Responses')
@section('page_title', 'Skin Quiz Responses')
@section('breadcrumb', 'Admin / Skin Quiz')

@section('content')

<div class="page-header">
    <h1>Skin Quiz Responses</h1>
    <p class="text-muted text-sm" style="margin: 0;">Submissions with email from the storefront skin quiz.</p>
</div>

<form method="GET" class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" for="search">Search email</label>
            <input id="search" name="search" type="search" value="{{ request('search') }}"
                   class="form-input" placeholder="customer@…">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.quiz-responses.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Skin profile</th>
                    <th>Language</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $row)
                <tr>
                    <td class="mono text-sm">{{ $row->id }}</td>
                    <td style="font-weight: 500;">{{ $row->lead?->email ?? '—' }}</td>
                    <td class="text-muted text-sm">{{ $row->skin_profile ?? '—' }}</td>
                    <td><span class="badge badge-neutral">{{ strtoupper($row->lead?->preferred_language ?? 'EN') }}</span></td>
                    <td class="text-muted text-sm">{{ $row->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.quiz-responses.show', $row) }}" class="btn btn-secondary btn-xs">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px; color: #4B4B4B;">No quiz responses yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sessions->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted text-sm">Showing {{ $sessions->firstItem() }}–{{ $sessions->lastItem() }} of {{ $sessions->total() }}</div>
        {{ $sessions->links() }}
    </div>
    @endif
</div>

@endsection
