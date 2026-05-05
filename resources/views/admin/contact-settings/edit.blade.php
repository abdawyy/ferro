@extends('admin.layouts.app')

@section('title', __('admin.contact_settings.title'))
@section('page_title', __('admin.contact_settings.title'))
@section('breadcrumb', 'Admin / '.__('admin.contact_settings.title'))

@section('content')

<form method="POST" action="{{ route('admin.contact-settings.update') }}">
    @csrf
    @method('PUT')

    <div class="page-header" style="align-items: flex-start;">
        <h1>{{ __('admin.contact_settings.title') }}</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">{{ __('admin.contact_settings.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('admin.contact_settings.save') }}</button>
        </div>
    </div>

    @if(session('success'))
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-green);">
            <div class="admin-card-body" style="color: var(--admin-green);">{{ session('success') }}</div>
        </div>
    @endif

    <div class="admin-card" style="margin-bottom: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.support_email') }}</h2></div>
        <div class="admin-card-body">
            <div class="form-group">
                <label class="form-label" for="support_email">{{ __('admin.contact_settings.support_email') }} *</label>
                <input id="support_email" name="support_email" type="email" required class="form-input"
                       value="{{ old('support_email', $setting->support_email) }}">
                @error('support_email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="grid-2">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_email') }} — EN</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="email_heading_en">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="email_heading_en" name="email_heading_en" type="text" class="form-input"
                           value="{{ old('email_heading_en', $setting->email_heading_en) }}" placeholder="Email">
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_email') }} — AR</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="email_heading_ar">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="email_heading_ar" name="email_heading_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('email_heading_ar', $setting->email_heading_ar) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2" style="margin-top: 20px;">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_live_chat') }} — EN</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="live_chat_heading_en">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="live_chat_heading_en" name="live_chat_heading_en" type="text" class="form-input"
                           value="{{ old('live_chat_heading_en', $setting->live_chat_heading_en) }}" placeholder="Live Chat">
                </div>
                <div class="form-group">
                    <label class="form-label" for="live_chat_text_en">{{ __('admin.contact_settings.body_label') }}</label>
                    <input id="live_chat_text_en" name="live_chat_text_en" type="text" class="form-input"
                           value="{{ old('live_chat_text_en', $setting->live_chat_text_en) }}">
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_live_chat') }} — AR</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="live_chat_heading_ar">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="live_chat_heading_ar" name="live_chat_heading_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('live_chat_heading_ar', $setting->live_chat_heading_ar) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="live_chat_text_ar">{{ __('admin.contact_settings.body_label') }}</label>
                    <input id="live_chat_text_ar" name="live_chat_text_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('live_chat_text_ar', $setting->live_chat_text_ar) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2" style="margin-top: 20px;">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_hq') }} — EN</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="hq_heading_en">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="hq_heading_en" name="hq_heading_en" type="text" class="form-input"
                           value="{{ old('hq_heading_en', $setting->hq_heading_en) }}" placeholder="Headquarters">
                </div>
                <div class="form-group">
                    <label class="form-label" for="hq_text_en">{{ __('admin.contact_settings.body_label') }}</label>
                    <input id="hq_text_en" name="hq_text_en" type="text" class="form-input"
                           value="{{ old('hq_text_en', $setting->hq_text_en) }}">
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_hq') }} — AR</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="hq_heading_ar">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="hq_heading_ar" name="hq_heading_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('hq_heading_ar', $setting->hq_heading_ar) }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="hq_text_ar">{{ __('admin.contact_settings.body_label') }}</label>
                    <input id="hq_text_ar" name="hq_text_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('hq_text_ar', $setting->hq_text_ar) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="grid-2" style="margin-top: 20px;">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_follow') }} — EN</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="follow_heading_en">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="follow_heading_en" name="follow_heading_en" type="text" class="form-input"
                           value="{{ old('follow_heading_en', $setting->follow_heading_en) }}" placeholder="Follow Us">
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.section_follow') }} — AR</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="follow_heading_ar">{{ __('admin.contact_settings.heading_label') }}</label>
                    <input id="follow_heading_ar" name="follow_heading_ar" type="text" class="form-input" dir="rtl"
                           value="{{ old('follow_heading_ar', $setting->follow_heading_ar) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.contact_settings.social_urls') }}</h2></div>
        <div class="admin-card-body">
            <p style="color: var(--admin-muted); font-size: 13px; margin: 0 0 16px;">{{ __('admin.contact_settings.social_help') }}</p>

            @foreach([
                ['show' => 'show_instagram', 'url' => 'social_instagram_url', 'label' => 'Instagram', 'placeholder' => 'https://instagram.com/...', 'type' => 'url'],
                ['show' => 'show_tiktok', 'url' => 'social_tiktok_url', 'label' => 'TikTok', 'placeholder' => 'https://tiktok.com/@...', 'type' => 'url'],
                ['show' => 'show_facebook', 'url' => 'social_facebook_url', 'label' => 'Facebook', 'placeholder' => 'https://facebook.com/...', 'type' => 'url'],
                ['show' => 'show_snapchat', 'url' => 'social_snapchat_url', 'label' => 'Snapchat', 'placeholder' => 'https://snapchat.com/add/...', 'type' => 'url'],
                ['show' => 'show_whatsapp', 'url' => 'social_whatsapp_url', 'label' => 'WhatsApp', 'placeholder' => 'https://wa.me/971501234567', 'type' => 'text'],
            ] as $row)
                <div class="form-group" style="border: 1px solid var(--admin-border); border-radius: 8px; padding: 14px 16px; margin-bottom: 12px;">
                    <label class="form-label" style="display:flex; align-items:center; gap:10px; cursor:pointer; margin-bottom:10px;">
                        <input type="hidden" name="{{ $row['show'] }}" value="0">
                        <input type="checkbox" name="{{ $row['show'] }}" value="1"
                               @checked(filter_var(old($row['show'], $setting->{$row['show']}), FILTER_VALIDATE_BOOLEAN))>
                        <span>{{ __('admin.contact_settings.show_on_storefront', ['network' => $row['label']]) }}</span>
                    </label>
                    <label class="form-label" for="{{ $row['url'] }}">{{ $row['label'] }} URL</label>
                    <input id="{{ $row['url'] }}" name="{{ $row['url'] }}" type="{{ $row['type'] }}" class="form-input"
                           value="{{ old($row['url'], $setting->{$row['url']}) }}"
                           placeholder="{{ $row['placeholder'] }}">
                    @error($row['url'])<div class="form-error">{{ $message }}</div>@enderror
                </div>
            @endforeach
        </div>
    </div>
</form>

@endsection
