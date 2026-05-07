@extends('admin.layouts.app')

@php
    $adminLocale = app()->getLocale();
@endphp
@section('title', __('admin.storefront_seo.title'))
@section('page_title', __('admin.storefront_seo.title'))
@section('breadcrumb')
    Admin / {{ __('admin.storefront_seo.title') }}
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1>{{ __('admin.storefront_seo.title') }}</h1>
        <p class="text-muted text-sm" style="margin-top: 8px;">
            {{ __('admin.storefront_seo.intro') }}
        </p>
    </div>
</div>

@if(session('success'))
    <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-green);">
        <div class="admin-card-body" style="color: var(--admin-green);">{{ session('success') }}</div>
    </div>
@endif

<form method="POST" action="{{ route('admin.storefront-seo.update') }}">
    @csrf
    @method('PUT')

    @foreach($keys as $pageKey)
        @php
            $row = $rows[$pageKey] ?? null;
            $def = $defaults[$pageKey] ?? [];
            $label = ($labels[$pageKey][$adminLocale] ?? null) ?: ($labels[$pageKey]['en'] ?? $pageKey);
        @endphp
        <div class="admin-card" style="margin-bottom: 20px;">
            <div class="admin-card-header">
                <h2 class="admin-card-title">{{ $label }}</h2>
                <span class="text-muted text-xs mono">{{ $pageKey }}</span>
            </div>
            <div class="admin-card-body">
                @if(in_array($pageKey, ['account_order', 'orders_track'], true))
                    <p class="text-muted text-sm" style="margin-bottom: 12px;">
                        {{ __('admin.storefront_seo.placeholder_hint') }} <code>:order_number</code>
                    </p>
                @endif
                <div class="grid-2" style="gap: 16px;">
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.storefront_seo.meta_title_en') }}</label>
                        <input type="text" name="seo[{{ $pageKey }}][meta_title_en]" class="form-input"
                               value="{{ old('seo.'.$pageKey.'.meta_title_en', $row->meta_title_en ?? '') }}"
                               placeholder="{{ $def['meta_title']['en'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.storefront_seo.meta_title_ar') }}</label>
                        <input type="text" name="seo[{{ $pageKey }}][meta_title_ar]" class="form-input" dir="rtl"
                               value="{{ old('seo.'.$pageKey.'.meta_title_ar', $row->meta_title_ar ?? '') }}"
                               placeholder="{{ $def['meta_title']['ar'] ?? '' }}">
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">{{ __('admin.storefront_seo.meta_description_en') }}</label>
                        <textarea name="seo[{{ $pageKey }}][meta_description_en]" class="form-input form-textarea"
                                  style="min-height: 72px;" placeholder="{{ $def['meta_description']['en'] ?? '' }}">{{ old('seo.'.$pageKey.'.meta_description_en', $row->meta_description_en ?? '') }}</textarea>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label">{{ __('admin.storefront_seo.meta_description_ar') }}</label>
                        <textarea name="seo[{{ $pageKey }}][meta_description_ar]" class="form-input form-textarea"
                                  style="min-height: 72px;" dir="rtl" placeholder="{{ $def['meta_description']['ar'] ?? '' }}">{{ old('seo.'.$pageKey.'.meta_description_ar', $row->meta_description_ar ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.storefront_seo.meta_keywords_en') }}</label>
                        <input type="text" name="seo[{{ $pageKey }}][meta_keywords_en]" class="form-input"
                               value="{{ old('seo.'.$pageKey.'.meta_keywords_en', $row->meta_keywords_en ?? '') }}"
                               placeholder="{{ $def['meta_keywords']['en'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('admin.storefront_seo.meta_keywords_ar') }}</label>
                        <input type="text" name="seo[{{ $pageKey }}][meta_keywords_ar]" class="form-input" dir="rtl"
                               value="{{ old('seo.'.$pageKey.'.meta_keywords_ar', $row->meta_keywords_ar ?? '') }}"
                               placeholder="{{ $def['meta_keywords']['ar'] ?? '' }}">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary">{{ __('admin.storefront_seo.save') }}</button>
</form>

@endsection
