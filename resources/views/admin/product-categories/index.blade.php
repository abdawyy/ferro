@extends('admin.layouts.app')

@section('title', 'Product categories')
@section('page_title', 'Product categories')
@section('breadcrumb', 'Admin / Shop / Categories')

@section('content')

<div class="page-header">
    <h1>Categories</h1>
    <a href="{{ route('admin.product-categories.create') }}" class="btn btn-primary">+ New category</a>
</div>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name (EN)</th>
                    <th>Slug</th>
                    <th style="text-align:center;">Sort</th>
                    <th>Visible</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $category->getTranslation('name', 'en') }}
                        @if($category->getTranslation('name', 'ar'))
                        <div class="text-muted text-sm" dir="rtl">{{ $category->getTranslation('name', 'ar') }}</div>
                        @endif
                    </td>
                    <td class="mono text-muted">{{ $category->slug }}</td>
                    <td style="text-align:center;" class="text-muted">{{ $category->sort_order }}</td>
                    <td>
                        @if($category->is_active)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-neutral">Hidden</span>
                        @endif
                    </td>
                    <td class="text-muted text-sm">{{ $category->updated_at->format('d M Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.product-categories.edit', $category) }}" class="btn btn-secondary btn-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.product-categories.destroy', $category) }}"
                                  onsubmit="return confirm('Delete this category? Products may lose their category link.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 40px; color: #4B4B4B;">
                        No categories yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($categories->hasPages())
<div class="text-muted text-sm" style="margin-top: 16px;">{{ $categories->links() }}</div>
@endif

@endsection
