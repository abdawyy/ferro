@extends('admin.layouts.app')

@section('title', 'Shipping (Egypt)')
@section('page_title', 'Shipping — Egypt governorates')
@section('breadcrumb', 'Admin / Shipping')

@section('content')

<div class="page-header">
    <h1>Shipping rates</h1>
    <a href="{{ route('admin.shipping-cities.create') }}" class="btn btn-primary">+ New location</a>
</div>

<p class="text-muted text-sm" style="margin-bottom: 16px;">
    Each governorate has its own delivery fee (EGP by default). Checkout shows only active locations.
</p>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name (EN)</th>
                    <th>Slug</th>
                    <th style="text-align:right;">Fee</th>
                    <th style="text-align:center;">Sort</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cities as $city)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $city->getTranslation('name', 'en') }}
                        @if($city->getTranslation('name', 'ar'))
                        <div class="text-muted text-sm" dir="rtl">{{ $city->getTranslation('name', 'ar') }}</div>
                        @endif
                    </td>
                    <td class="mono text-muted">{{ $city->slug }}</td>
                    <td style="text-align:right;" class="mono">{{ ferro_money($city->shipping_price, $city->currency) }}</td>
                    <td style="text-align:center;">{{ $city->sort_order }}</td>
                    <td>
                        @if($city->is_active)
                        <span class="badge badge-success">Yes</span>
                        @else
                        <span class="badge badge-neutral">No</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.shipping-cities.edit', $city) }}" class="btn btn-secondary btn-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.shipping-cities.destroy', $city) }}"
                                  onsubmit="return confirm('Delete this shipping location?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px;">No locations.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($cities->hasPages())
<div class="text-muted text-sm" style="margin-top: 16px;">{{ $cities->links() }}</div>
@endif

@endsection
