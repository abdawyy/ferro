@extends('admin.layouts.app')

@section('title', __('admin.newsletter.settings_title'))
@section('page_title', __('admin.newsletter.settings_title'))
@section('breadcrumb')
    Admin / {{ __('admin.newsletter.settings_title') }}
@endsection

@section('content')

<form method="POST" action="{{ route('admin.newsletter.settings.update') }}">
    @csrf
    @method('PUT')

    <div class="page-header" style="align-items: flex-start;">
        <h1>{{ __('admin.newsletter.settings_title') }}</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.newsletter.subscribers.index') }}" class="btn btn-secondary">{{ __('admin.newsletter.view_subscribers') }}</a>
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary">{{ __('admin.newsletter.campaigns_title') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('admin.contact_settings.save') }}</button>
        </div>
    </div>

    @if(session('success'))
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-green);">
            <div class="admin-card-body" style="color: var(--admin-green);">{{ session('success') }}</div>
        </div>
    @endif

    <div class="admin-card" style="margin-bottom: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.popup_control') }}</h2></div>
        <div class="admin-card-body">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input type="checkbox" name="is_enabled" value="1" {{ old('is_enabled', $setting->is_enabled) ? 'checked' : '' }}>
                <span>{{ __('admin.newsletter.popup_enabled') }}</span>
            </label>
            <div class="form-group" style="margin-top:16px;">
                <label class="form-label" for="delay_seconds">{{ __('admin.newsletter.delay_seconds') }}</label>
                <input id="delay_seconds" name="delay_seconds" type="number" min="0" max="120" class="form-input" style="max-width:120px;"
                       value="{{ old('delay_seconds', $setting->delay_seconds) }}">
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.popup_content') }} — EN</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="title_en">{{ __('admin.newsletter.popup_title') }}</label>
                    <input id="title_en" name="title_en" type="text" required class="form-input" value="{{ old('title_en', $setting->title_en) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="message_en">{{ __('admin.newsletter.popup_message') }}</label>
                    <textarea id="message_en" name="message_en" rows="4" required class="form-input">{{ old('message_en', $setting->message_en) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="button_text_en">{{ __('admin.newsletter.button_text') }}</label>
                    <input id="button_text_en" name="button_text_en" type="text" required class="form-input" value="{{ old('button_text_en', $setting->button_text_en) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="success_message_en">{{ __('admin.newsletter.success_message') }}</label>
                    <input id="success_message_en" name="success_message_en" type="text" required class="form-input" value="{{ old('success_message_en', $setting->success_message_en) }}">
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.popup_content') }} — AR</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="title_ar">{{ __('admin.newsletter.popup_title') }}</label>
                    <input id="title_ar" name="title_ar" type="text" class="form-input" dir="rtl" value="{{ old('title_ar', $setting->title_ar) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="message_ar">{{ __('admin.newsletter.popup_message') }}</label>
                    <textarea id="message_ar" name="message_ar" rows="4" class="form-input" dir="rtl">{{ old('message_ar', $setting->message_ar) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="button_text_ar">{{ __('admin.newsletter.button_text') }}</label>
                    <input id="button_text_ar" name="button_text_ar" type="text" class="form-input" dir="rtl" value="{{ old('button_text_ar', $setting->button_text_ar) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="success_message_ar">{{ __('admin.newsletter.success_message') }}</label>
                    <input id="success_message_ar" name="success_message_ar" type="text" class="form-input" dir="rtl" value="{{ old('success_message_ar', $setting->success_message_ar) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.coupon_settings') }}</h2></div>
        <div class="admin-card-body">
            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="discount_percent">{{ __('admin.newsletter.discount_percent') }}</label>
                    <input id="discount_percent" name="discount_percent" type="number" min="1" max="100" required class="form-input" style="max-width:120px;"
                           value="{{ old('discount_percent', $setting->discount_percent) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="coupon_prefix">{{ __('admin.newsletter.coupon_prefix') }}</label>
                    <input id="coupon_prefix" name="coupon_prefix" type="text" required class="form-input" style="max-width:160px;"
                           value="{{ old('coupon_prefix', $setting->coupon_prefix) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="coupon_valid_days">{{ __('admin.newsletter.coupon_valid_days') }}</label>
                    <input id="coupon_valid_days" name="coupon_valid_days" type="number" min="1" max="365" class="form-input" style="max-width:120px;"
                           value="{{ old('coupon_valid_days', $setting->coupon_valid_days) }}" placeholder="30">
                    <div style="font-size:11px; color:var(--admin-muted); margin-top:4px;">{{ __('admin.newsletter.coupon_valid_days_help') }}</div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection
