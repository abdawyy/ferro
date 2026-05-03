@extends('admin.layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('breadcrumb', 'Admin / Products')

@section('content')

<div class="page-header">
    <h1>Products</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
</div>

{{-- Filters --}}
<form method="GET" class="admin-card" style="padding: 16px 20px; margin-bottom: 20px;">
    <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 200px;">
            <label class="form-label" for="search">Search</label>
            <input id="search" name="search" type="search" value="{{ request('search') }}"
                   class="form-input" placeholder="Name, SKU, slug…">
        </div>
        <div style="min-width: 160px;">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-input form-select">
                <option value="">All statuses</option>
                @foreach(['active','coming_soon','out_of_stock','archived'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $s)) }}
                </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<div class="admin-card">
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 56px;">Image</th>
                    <th>Name (EN)</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th style="text-align:center;">Stock</th>
                    <th>Status</th>
                    <th>Flags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                @php
                    $statusBadge = [
                        'active'        => 'badge-success',
                        'coming_soon'   => 'badge-info',
                        'out_of_stock'  => 'badge-danger',
                        'archived'      => 'badge-neutral',
                    ][$product->status] ?? 'badge-neutral';
                @endphp
                <tr>
                    <td>
                        @if($product->featured_image)
                        <img src="{{ Storage::disk('public')->url($product->featured_image) }}"
                             alt="{{ $product->getTranslation('name','en') }}"
                             class="image-thumb">
                        @else
                        <div class="image-thumb" style="display:flex;align-items:center;justify-content:center;color:#4B4B4B;font-size:18px;">📦</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $product->getTranslation('name','en') }}</div>
                        @if($product->getTranslation('name','ar'))
                        <div class="text-muted text-sm" dir="rtl">{{ $product->getTranslation('name','ar') }}</div>
                        @endif
                        @if($product->trashed())
                        <span class="badge badge-neutral" style="margin-top:3px;">Archived</span>
                        @endif
                    </td>
                    <td class="mono text-muted">{{ $product->sku }}</td>
                    <td class="mono">
                        ${{ number_format($product->price, 2) }}
                        @if($product->compare_price)
                        <div class="text-muted text-sm" style="text-decoration:line-through;">${{ number_format($product->compare_price, 2) }}</div>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span class="badge {{ $product->stock_quantity == 0 ? 'badge-danger' : ($product->stock_quantity <= ($product->low_stock_threshold ?? 10) ? 'badge-warning' : 'badge-success') }}">
                            {{ $product->stock_quantity ?? '—' }}
                        </span>
                    </td>
                    <td><span class="badge {{ $statusBadge }}">{{ ucwords(str_replace('_',' ',$product->status)) }}</span></td>
                    <td>
                        @if($product->is_featured)   <span class="badge badge-orange" style="margin: 1px;">⭐ Featured</span> @endif
                        @if($product->is_new_arrival) <span class="badge badge-info" style="margin: 1px;">New</span> @endif
                        @if($product->is_best_seller) <span class="badge badge-success" style="margin: 1px;">Best Seller</span> @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-xs">Edit</a>
                            @if(!$product->trashed())
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Archive this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">Archive</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding: 40px; color: #4B4B4B;">
                        No products found. <a href="{{ route('admin.products.create') }}" class="text-orange">Add your first product →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div style="padding: 16px 20px; border-top: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
        <div class="text-muted text-sm">
            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
        </div>
        {{ $products->links() }}
    </div>
    @endif
</div>

@endsection
