@extends('admin.layouts.app')

@section('title', isset($page->id) ? 'Edit Page' : 'New Page')
@section('page_title', isset($page->id) ? 'Edit Page' : 'New Page')
@section('breadcrumb', 'Admin / Pages / ' . (isset($page->id) ? 'Edit' : 'Create'))

@section('content')

@php $editing = isset($page->id); @endphp

<form method="POST"
      action="{{ $editing ? route('admin.pages.update', $page) : route('admin.pages.store') }}">
    @csrf
    @if($editing) @method('PATCH') @endif

    <div class="page-header">
        <h1>{{ $editing ? ($page->getTranslation('title','en') ?: 'Edit Page') : 'New Page' }}</h1>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">← Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ $editing ? '💾 Save Page' : '+ Create Page' }}
            </button>
        </div>
    </div>

    <div class="grid-2" style="gap: 24px; align-items: start;">

        {{-- Main content column --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- English Content --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">🇬🇧 English Content</h2>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label" for="title_en">Title (EN) *</label>
                        <input id="title_en" name="title_en" type="text" required class="form-input"
                               value="{{ old('title_en', $editing ? $page->getTranslation('title','en') : '') }}"
                               placeholder="Page title in English">
                        @error('title_en')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="content_en">Content (EN) *</label>
                        <textarea id="content_en" name="content_en" required
                                  class="form-input form-textarea" style="min-height: 320px;"
                                  placeholder="Page body content — supports HTML">{{ old('content_en', $editing ? $page->getTranslation('content','en') : '') }}</textarea>
                        <div class="form-hint">Supports HTML markup for rich content</div>
                        @error('content_en')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Arabic Content --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">🇸🇦 Arabic Content</h2>
                </div>
                <div class="admin-card-body" dir="rtl">
                    <div class="form-group">
                        <label class="form-label" for="title_ar">العنوان بالعربية</label>
                        <input id="title_ar" name="title_ar" type="text" class="form-input" dir="rtl"
                               value="{{ old('title_ar', $editing ? $page->getTranslation('title','ar') : '') }}"
                               placeholder="عنوان الصفحة بالعربية">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="content_ar">المحتوى بالعربية</label>
                        <textarea id="content_ar" name="content_ar"
                                  class="form-input form-textarea" dir="rtl" style="min-height: 320px;"
                                  placeholder="محتوى الصفحة — يدعم HTML">{{ old('content_ar', $editing ? $page->getTranslation('content','ar') : '') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar settings --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">

            {{-- Settings --}}
            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">Settings</h2></div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label" for="slug">Slug *</label>
                        <input id="slug" name="slug" type="text" required class="form-input mono"
                               value="{{ old('slug', $page->slug ?? '') }}"
                               placeholder="e.g. about-us">
                        <div class="form-hint">URL: /pages/{slug} — lowercase, hyphens only</div>
                        @error('slug')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="template">Template</label>
                        <select id="template" name="template" class="form-input form-select">
                            @foreach(['default' => 'Default', 'full-width' => 'Full Width', 'landing' => 'Landing Page'] as $val => $label)
                            <option value="{{ $val }}" {{ old('template', $page->template ?? 'default') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sort_order">Sort Order</label>
                        <input id="sort_order" name="sort_order" type="number" min="0"
                               class="form-input mono"
                               value="{{ old('sort_order', $page->sort_order ?? 0) }}">
                    </div>

                    <label class="form-check">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1"
                               {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}>
                        Published (visible on site)
                    </label>
                </div>
            </div>

            {{-- SEO --}}
            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">🔍 SEO</h2></div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label" for="meta_title_en">Meta Title (EN)</label>
                        <input id="meta_title_en" name="meta_title_en" type="text" class="form-input"
                               value="{{ old('meta_title_en', $editing ? $page->getTranslation('meta_title','en') : '') }}"
                               maxlength="300">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="meta_description_en">Meta Description (EN)</label>
                        <textarea id="meta_description_en" name="meta_description_en"
                                  class="form-input form-textarea" style="min-height: 80px;"
                                  maxlength="500">{{ old('meta_description_en', $editing ? $page->getTranslation('meta_description','en') : '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="meta_title_ar">Meta Title (AR)</label>
                        <input id="meta_title_ar" name="meta_title_ar" type="text" class="form-input" dir="rtl"
                               value="{{ old('meta_title_ar', $editing ? $page->getTranslation('meta_title','ar') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="meta_description_ar">Meta Description (AR)</label>
                        <textarea id="meta_description_ar" name="meta_description_ar"
                                  class="form-input form-textarea" dir="rtl" style="min-height: 80px;">{{ old('meta_description_ar', $editing ? $page->getTranslation('meta_description','ar') : '') }}</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Sticky save --}}
    <div style="position:sticky; bottom:0; background:var(--admin-surface); border-top:1px solid var(--admin-border); padding:12px 0; margin-top:24px; display:flex; justify-content:flex-end; gap:8px;">
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">{{ $editing ? '💾 Save Page' : '+ Create Page' }}</button>
    </div>
</form>

@endsection
