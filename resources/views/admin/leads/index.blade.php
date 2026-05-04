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

<div class="admin-card" style="margin-bottom: 20px;">
    <div class="admin-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h2 class="admin-card-title" style="margin: 0;">Add someone to the waitlist</h2>
            <p class="text-muted text-sm" style="margin: 6px 0 0;">
                Visitors join via the storefront <strong class="mono">POST {{ url('/waitlist') }}</strong> (email + optional product).
                Use this form to add or update a lead manually without using the public form.
            </p>
        </div>
    </div>
    <div class="admin-card-body">
        <form method="POST" action="{{ route('admin.leads.waitlist.store') }}" style="display: grid; gap: 14px; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); align-items: end;">
            @csrf
            <div style="grid-column: span 2; min-width: 240px;">
                <label class="form-label" for="wl-email">Email <span class="text-orange">*</span></label>
                <input id="wl-email" name="email" type="email" class="form-input" required value="{{ old('email') }}" placeholder="name@example.com" autocomplete="off">
            </div>
            <div>
                <label class="form-label" for="wl-fn">First name</label>
                <input id="wl-fn" name="first_name" type="text" class="form-input" value="{{ old('first_name') }}">
            </div>
            <div>
                <label class="form-label" for="wl-ln">Last name</label>
                <input id="wl-ln" name="last_name" type="text" class="form-input" value="{{ old('last_name') }}">
            </div>
            <div>
                <label class="form-label" for="wl-product">Product (optional)</label>
                <select id="wl-product" name="product_id" class="form-input form-select">
                    <option value="">— General waitlist —</option>
                    @foreach($products as $p)
                    <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->sku }} — {{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label" for="wl-lang">Language</label>
                <select id="wl-lang" name="preferred_language" class="form-input form-select">
                    <option value="en" @selected(old('preferred_language', 'en') === 'en')>English</option>
                    <option value="ar" @selected(old('preferred_language') === 'ar')>العربية</option>
                </select>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label class="form-check" style="margin: 0;">
                    <input type="hidden" name="marketing_consent" value="0">
                    <input type="checkbox" name="marketing_consent" value="1" @checked(old('marketing_consent', true))>
                    Marketing consent
                </label>
                <label class="form-check" style="margin: 0;">
                    <input type="hidden" name="send_welcome_email" value="0">
                    <input type="checkbox" name="send_welcome_email" value="1" @checked(old('send_welcome_email'))>
                    Send waitlist welcome email
                </label>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Save to waitlist</button>
            </div>
        </form>
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
