@extends('admin.layouts.app')

@php $editing = $city->exists; @endphp

@section('title', $editing ? 'Edit shipping location' : 'New shipping location')
@section('page_title', $editing ? 'Edit shipping location' : 'New shipping location')
@section('breadcrumb', 'Admin / Shipping / ' . ($editing ? 'Edit' : 'Create'))

@section('content')

<form method="POST" action="{{ $editing ? route('admin.shipping-cities.update', $city) : route('admin.shipping-cities.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="page-header" style="align-items: flex-start;">
        <h1>{{ $editing ? $city->getTranslation('name', 'en') : 'New location' }}</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.shipping-cities.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>

    <div class="grid-2">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">English</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="name_en">Name *</label>
                    <input id="name_en" name="name_en" type="text" required class="form-input"
                           value="{{ old('name_en', $editing ? $city->getTranslation('name', 'en') : '') }}">
                    @error('name_en')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Arabic</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="name_ar">الاسم</label>
                    <input id="name_ar" name="name_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('name_ar', $editing ? $city->getTranslation('name', 'ar') : '') }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">Rate</h2></div>
        <div class="admin-card-body">
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label" for="slug">Slug *</label>
                    <input id="slug" name="slug" type="text" required class="form-input mono"
                           value="{{ old('slug', $city->slug ?? '') }}" pattern="[a-z0-9_\-]+">
                    @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="shipping_price">Shipping fee *</label>
                    <input id="shipping_price" name="shipping_price" type="number" step="0.01" min="0" required class="form-input"
                           value="{{ old('shipping_price', $city->shipping_price ?? '0') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="currency">Currency *</label>
                    <select id="currency" name="currency" class="form-input form-select" required>
                        @foreach(['EGP' => 'EGP', 'USD' => 'USD'] as $val => $label)
                        <option value="{{ $val }}" {{ old('currency', $city->currency ?? 'EGP') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" class="form-input"
                           value="{{ old('sort_order', $city->sort_order ?? 0) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $city->is_active ?? true) ? 'checked' : '' }}>
                        Active at checkout
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
