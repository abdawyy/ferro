@extends('admin.layouts.app')

@section('title', 'Leads & Waitlist')
@section('page_title', 'Leads & Waitlist')
@section('breadcrumb', 'Admin / Leads')

@section('content')

<div class="page-header">
    <h1>Leads & Waitlist</h1>
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.leads.waitlist.export') }}" class="btn btn-secondary">
            ↓ Waitlist CSV
        </a>
        <a href="{{ route('admin.leads.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
           class="btn btn-primary">
            ↓ Export All CSV
        </a>
    </div>
</div>

{{-- Source pills --}}
<div style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
    <a href="{{ route('admin.leads.index') }}"
       class="btn btn-sm {{ !request('source') ? 'btn-primary' : 'btn-secondary' }}">
        All ({{ $sourceCounts->sum() }})
    </a>
    @foreach($sourceCounts as $source => $count)
    <a href="{{ route('admin.leads.index', ['source' => $source] + request()->except('source')) }}"
       class="btn btn-sm {{ request('source') === $source ? 'btn-primary' : 'btn-secondary' }}">
        {{ ucfirst($source) }} ({{ $count }})
    </a>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
    @if(request('source'))<input type="hidden" name="source" value="{{ request('source') }}">@endif
    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" for="search">Search</label>
            <input id="search" name="search" type="search" value="{{ request('search') }}"
                   class="form-input" placeholder="Email, name…">
        </div>
        <div>
            <label class="form-label" for="priority">Priority</label>
            <select id="priority" name="priority" class="form-input form-select">
                <option value="">All</option>
                @foreach(['standard','high','vip'] as $p)
                <option value="{{ $p }}" {{ request('priority') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <label class="form-check" style="padding-bottom: 2px;">
            <input type="checkbox" name="waitlist_only" value="1"
                   {{ request('waitlist_only') ? 'checked' : '' }}>
            Waitlist only
        </label>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.leads.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="admin-card">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Source</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Lang</th>
                    <th>Waitlist</th>
                    <th>Marketing</th>
                    <th>Score</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                @php
                    $pb = ['vip'=>'badge-danger','high'=>'badge-warning','standard'=>'badge-neutral'];
                    $sb = ['converted'=>'badge-success','qualified'=>'badge-info','engaged'=>'badge-warning','new'=>'badge-neutral','unsubscribed'=>'badge-danger'];
                @endphp
                <tr>
                    <td style="font-weight: 500;">{{ $lead->email }}</td>
                    <td class="text-muted text-sm">{{ trim(($lead->first_name ?? '') . ' ' . ($lead->last_name ?? '')) ?: '—' }}</td>
                    <td><span class="badge badge-neutral">{{ $lead->source ?? '—' }}</span></td>
                    <td><span class="badge {{ $pb[$lead->priority] ?? 'badge-neutral' }}">{{ $lead->priority ?? 'standard' }}</span></td>
                    <td><span class="badge {{ $sb[$lead->status] ?? 'badge-neutral' }}">{{ $lead->status ?? 'new' }}</span></td>
                    <td><span class="badge badge-neutral">{{ strtoupper($lead->preferred_language ?? 'EN') }}</span></td>
                    <td style="text-align:center;">
                        @if($lead->on_waitlist)
                            <span style="color: var(--admin-green);" title="Notified: {{ $lead->waitlist_notified_at?->format('d M Y') ?? 'Not yet' }}">✓</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if($lead->marketing_consent)
                        <span style="color: var(--admin-green);">✓</span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="mono text-sm">{{ $lead->engagement_score ?? 0 }}</td>
                    <td class="text-muted text-sm">{{ $lead->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" style="text-align:center; padding: 40px; color: #4B4B4B;">No leads found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leads->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted text-sm">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</div>
        {{ $leads->links() }}
    </div>
    @endif
</div>

@endsection
