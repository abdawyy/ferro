@extends('admin.layouts.app')

@php $editing = $category->exists; @endphp

@section('title', $editing ? 'Edit category' : 'New category')
@section('page_title', $editing ? 'Edit category' : 'New category')
@section('breadcrumb', 'Admin / Shop / Categories / ' . ($editing ? 'Edit' : 'Create'))

@section('content')

<form method="POST" action="{{ $editing ? route('admin.product-categories.update', $category) : route('admin.product-categories.store') }}">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="page-header" style="align-items: flex-start;">
        <h1>{{ $editing ? $category->getTranslation('name', 'en') : 'New category' }}</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.product-categories.index') }}" class="btn btn-secondary">Cancel</a>
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
                           value="{{ old('name_en', $editing ? $category->getTranslation('name', 'en') : '') }}">
                    @error('name_en')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="description_en">Description</label>
                    <textarea id="description_en" name="description_en" class="form-input form-textarea" style="min-height: 100px;">{{ old('description_en', $editing ? $category->getTranslation('description', 'en') : '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Arabic</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="name_ar">الاسم</label>
                    <input id="name_ar" name="name_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('name_ar', $editing ? $category->getTranslation('name', 'ar') : '') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="description_ar">الوصف</label>
                    <textarea id="description_ar" name="description_ar" class="form-input form-textarea" dir="rtl" style="min-height: 100px;">{{ old('description_ar', $editing ? $category->getTranslation('description', 'ar') : '') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">Publishing</h2></div>
        <div class="admin-card-body">
            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label" for="slug">Slug *</label>
                    <input id="slug" name="slug" type="text" required class="form-input mono"
                           value="{{ old('slug', $category->slug ?? '') }}" pattern="[a-z0-9\-]+">
                    @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="sort_order">Sort order</label>
                    <input id="sort_order" name="sort_order" type="number" min="0" class="form-input"
                           value="{{ old('sort_order', $category->sort_order ?? 0) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                        Visible on storefront
                    </label>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
