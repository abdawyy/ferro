@extends('admin.layouts.app')

@php $editing = $filter->exists; @endphp

@section('title', $editing ? 'Edit shop filter' : 'New shop filter')
@section('page_title', $editing ? 'Edit shop filter' : 'New shop filter')
@section('breadcrumb', 'Admin / Shop / Quick filters / ' . ($editing ? 'Edit' : 'Create'))

@section('content')

<form method="POST" action="{{ $editing ? route('admin.shop-quick-filters.update', $filter) : route('admin.shop-quick-filters.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="page-header" style="align-items: flex-start;">
        <h1>{{ $editing ? $filter->getTranslation('name', 'en') : 'New filter' }}</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.shop-quick-filters.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>

    <div class="grid-2">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">English</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="name_en">Pill label *</label>
                    <input id="name_en" name="name_en" type="text" required class="form-input"
                           value="{{ old('name_en', $editing ? $filter->getTranslation('name', 'en') : '') }}"
                           placeholder="e.g. In stock">
                    @error('name_en')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Arabic</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="name_ar">التسمية</label>
                    <input id="name_ar" name="name_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('name_ar', $editing ? $filter->getTranslation('name', 'ar') : '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">Behaviour</h2></div>
        <div class="admin-card-body">
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label" for="product_status">Product status *</label>
                    <select id="product_status" name="product_status" class="form-input form-select" required>
                        @foreach(['active' => 'Active (in stock)', 'coming_soon' => 'Coming soon', 'out_of_stock' => 'Out of stock'] as $val => $label)
                        <option value="{{ $val }}" {{ old('product_status', $filter->product_status ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('product_status')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" class="form-input"
                           value="{{ old('sort_order', $filter->sort_order ?? 0) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $filter->is_active ?? true) ? 'checked' : '' }}>
                        Show on storefront
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
