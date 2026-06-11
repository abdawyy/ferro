@extends('admin.layouts.app')

@section('title', __('admin.storefront_media.title'))
@section('page_title', __('admin.storefront_media.title'))
@section('breadcrumb')
    Admin / {{ __('admin.storefront_media.title') }}
@endsection

@section('content')

<form method="POST" action="{{ route('admin.storefront-media.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="page-header" style="align-items: flex-start;">
        <div>
            <h1>{{ __('admin.storefront_media.title') }}</h1>
            <p style="font-size:13px; color:var(--admin-muted); margin-top:6px; max-width:640px;">
                {{ __('admin.storefront_media.intro') }}
            </p>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('admin.storefront_media.save') }}</button>
    </div>

    @if(!empty($needsMigration))
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-red);">
            <div class="admin-card-body" style="color: var(--admin-red);">
                {{ __('admin.storefront_media.migration_required') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-red);">
            <div class="admin-card-body" style="color: var(--admin-red);">{{ session('error') }}</div>
        </div>
    @endif

    @if(session('success'))
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-green);">
            <div class="admin-card-body" style="color: var(--admin-green);">{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="admin-card" style="margin-bottom: 20px; border-color: var(--admin-red);">
            <div class="admin-card-body" style="color: var(--admin-red);">
                <strong>{{ __('admin.storefront_media.upload_error') }}</strong>
                <ul style="margin:8px 0 0 18px; font-size:13px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if($brandSettings)
    <div class="admin-card" style="margin-bottom: 24px; border-color: var(--admin-orange, #E8500A);">
        <div class="admin-card-header"><h2 class="admin-card-title">{{ __('admin.storefront_media.visibility_title') }}</h2></div>
        <div class="admin-card-body">
            <p style="font-size:13px; color:var(--admin-muted); margin-bottom:16px;">{{ __('admin.storefront_media.visibility_intro') }}</p>
            <div style="display:flex; flex-direction:column; gap:12px;">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="show_logo" value="1" {{ old('show_logo', $brandSettings->show_logo) ? 'checked' : '' }}>
                    <span>{{ __('admin.storefront_media.show_logo') }}</span>
                </label>
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="show_favicon" value="1" {{ old('show_favicon', $brandSettings->show_favicon) ? 'checked' : '' }}>
                    <span>{{ __('admin.storefront_media.show_favicon') }}</span>
                </label>
            </div>
        </div>
    </div>
    @endif

    @foreach($groups as $groupKey => $group)
        <div class="admin-card" style="margin-bottom: 24px;">
            <div class="admin-card-header">
                <h2 class="admin-card-title">{{ $group['title'] }}</h2>
            </div>
            <div class="admin-card-body">
                <div class="grid-2">
                    @foreach($group['items'] as $key => $item)
                        <div class="form-group" style="border:1px solid var(--admin-border); border-radius:4px; padding:14px;">
                            <label class="form-label" for="media_{{ str_replace('.', '_', $key) }}">{{ $item['label'] }}</label>
                            <div style="font-size:10px; color:var(--admin-muted); margin-bottom:8px; font-family:monospace;">{{ $key }}</div>

                            @if($item['url'])
                                <div style="margin-bottom:10px; background:#0a0a0a; border:1px solid var(--admin-border); border-radius:4px; overflow:hidden; max-height:140px;">
                                    <img src="{{ $item['url'] }}{{ $item['current'] ? '?v='.md5($item['current']) : '' }}" alt="" style="width:100%; height:140px; object-fit:cover; display:block;">
                                </div>
                                @if($item['current'])
                                    <div style="font-size:11px; color:var(--admin-green); margin-bottom:8px;">{{ __('admin.storefront_media.custom_active') }}</div>
                                @endif
                            @else
                                <div style="margin-bottom:10px; padding:20px; text-align:center; color:var(--admin-muted); font-size:12px; border:1px dashed var(--admin-border);">
                                    {{ __('admin.storefront_media.no_image') }}
                                </div>
                            @endif

                            <input
                                id="media_{{ str_replace('.', '_', $key) }}"
                                type="file"
                                name="media[{{ $key }}]"
                                accept="image/*,.svg"
                                class="form-input"
                            >

                            @if($item['current'])
                                <label style="display:flex; align-items:center; gap:8px; margin-top:10px; font-size:12px; cursor:pointer;">
                                    <input type="checkbox" name="remove[{{ $key }}]" value="1">
                                    <span>{{ __('admin.storefront_media.remove_custom') }}</span>
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <div style="text-align:right; margin-bottom:40px;">
        <button type="submit" class="btn btn-primary">{{ __('admin.storefront_media.save') }}</button>
    </div>
</form>

@endsection
