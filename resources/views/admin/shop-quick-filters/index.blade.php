@extends('admin.layouts.app')

@section('title', 'Shop quick filters')
@section('page_title', 'Shop quick filters')
@section('breadcrumb', 'Admin / Shop / Quick filters')

@section('content')

<div class="page-header">
    <h1>Shop pills (status)</h1>
    <a href="{{ route('admin.shop-quick-filters.create') }}" class="btn btn-primary">+ New filter</a>
</div>

<p class="text-muted text-sm" style="margin-bottom: 16px;">
    These appear after categories on the public shop page. Each maps to one product status (e.g. In stock → active).
</p>

<div class="admin-card">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Label (EN)</th>
                    <th>Product status</th>
                    <th style="text-align:center;">Sort</th>
                    <th>Active</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filters as $filter)
                <tr>
                    <td style="font-weight: 600;">
                        {{ $filter->getTranslation('name', 'en') }}
                        @if($filter->getTranslation('name', 'ar'))
                        <div class="text-muted text-sm" dir="rtl">{{ $filter->getTranslation('name', 'ar') }}</div>
                        @endif
                    </td>
                    <td class="mono text-muted">{{ $filter->product_status }}</td>
                    <td style="text-align:center;">{{ $filter->sort_order }}</td>
                    <td>
                        @if($filter->is_active)
                        <span class="badge badge-success">Yes</span>
                        @else
                        <span class="badge badge-neutral">No</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.shop-quick-filters.edit', $filter) }}" class="btn btn-secondary btn-xs">Edit</a>
                            <form method="POST" action="{{ route('admin.shop-quick-filters.destroy', $filter) }}"
                                  onsubmit="return confirm('Delete this filter?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 40px;">No filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($filters->hasPages())
<div class="text-muted text-sm" style="margin-top: 16px;">{{ $filters->links() }}</div>
@endif

@endsection
