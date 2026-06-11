@extends('admin.layouts.app')

@section('title', __('admin.newsletter.new_campaign'))
@section('page_title', __('admin.newsletter.new_campaign'))
@section('breadcrumb')
    Admin / {{ __('admin.newsletter.campaigns_title') }} / {{ __('admin.newsletter.new_campaign') }}
@endsection

@section('content')

<form method="POST" action="{{ route('admin.newsletter.campaigns.store') }}">
    @csrf

    <div class="page-header" style="align-items: flex-start;">
        <h1>{{ __('admin.newsletter.new_campaign') }}</h1>
        <div style="display:flex; gap:8px;">
            <a href="{{ route('admin.newsletter.campaigns.index') }}" class="btn btn-secondary">{{ __('admin.contact_settings.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('admin.newsletter.save_draft') }}</button>
        </div>
    </div>

    @if(session('error'))
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-red);">
            <div class="admin-card-body" style="color: var(--admin-red);">{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid-2">
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.campaign_content') }} — EN</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="subject_en">{{ __('admin.newsletter.campaign_subject') }}</label>
                    <input id="subject_en" name="subject_en" type="text" required class="form-input" value="{{ old('subject_en') }}">
                    @error('subject_en')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="body_en">{{ __('admin.newsletter.campaign_body') }}</label>
                    <textarea id="body_en" name="body_en" rows="8" required class="form-input">{{ old('body_en') }}</textarea>
                    @error('body_en')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.campaign_content') }} — AR</h2></div>
            <div class="admin-card-body" dir="rtl">
                <div class="form-group">
                    <label class="form-label" for="subject_ar">{{ __('admin.newsletter.campaign_subject') }}</label>
                    <input id="subject_ar" name="subject_ar" type="text" class="form-input" dir="rtl" value="{{ old('subject_ar') }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="body_ar">{{ __('admin.newsletter.campaign_body') }}</label>
                    <textarea id="body_ar" name="body_ar" rows="8" class="form-input" dir="rtl">{{ old('body_ar') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-top: 20px;">
        <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.newsletter.campaign_options') }}</h2></div>
        <div class="admin-card-body">
            <div class="form-group">
                <label class="form-label" for="product_id">{{ __('admin.newsletter.featured_product') }}</label>
                <select id="product_id" name="product_id" class="form-input">
                    <option value="">{{ __('admin.newsletter.no_product') }}</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                            {{ $product->getTranslation('name', 'en', false) ?: $product->name }}
                        </option>
                    @endforeach
                </select>
                <div style="font-size:11px; color:var(--admin-muted); margin-top:4px;">{{ __('admin.newsletter.featured_product_help') }}</div>
            </div>

            <div class="form-group">
                <label class="form-label">{{ __('admin.newsletter.recipients') }}</label>
                <label style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
                    <input type="radio" name="send_to" value="all" {{ old('send_to', 'all') === 'all' ? 'checked' : '' }}>
                    <span>{{ __('admin.newsletter.all_subscribers') }} ({{ $subscribers->count() }})</span>
                </label>
                <label style="display:flex; align-items:center; gap:8px;">
                    <input type="radio" name="send_to" value="selected" {{ old('send_to') === 'selected' ? 'checked' : '' }}>
                    <span>{{ __('admin.newsletter.selected_subscribers') }}</span>
                </label>
            </div>

            @if($subscribers->isNotEmpty())
            <div class="form-group" style="margin-top:16px; max-height:220px; overflow:auto; border:1px solid var(--admin-border); padding:12px; border-radius:4px;">
                @foreach($subscribers as $subscriber)
                <label style="display:flex; align-items:center; gap:8px; padding:4px 0;">
                    <input type="checkbox" name="subscriber_ids[]" value="{{ $subscriber->id }}"
                           @checked(collect(old('subscriber_ids', []))->contains($subscriber->id))>
                    <span>{{ $subscriber->email }}</span>
                </label>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</form>

@endsection
