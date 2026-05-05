@extends('admin.layouts.app')

@section('title', $editing ? 'Edit Product' : 'Add Product')
@section('page_title', $editing ? 'Edit Product' : 'Add New Product')
@section('breadcrumb', 'Admin / Products / ' . ($editing ? 'Edit' : 'Create'))

@section('content')

<form method="POST"
      action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      x-data="{ tab: 'basic', confirmDelete: false }">
    @csrf
    @if($editing) @method('PUT') @endif

    <div class="page-header" style="align-items: flex-start;">
        <div>
            @if($editing)
            <h1>{{ $product->getTranslation('name','en') }}</h1>
            <div class="text-muted text-sm mono">SKU: {{ $product->sku }}</div>
            @else
            <h1>New Product</h1>
            @endif
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ $editing ? 'Save Changes' : 'Create Product' }}
            </button>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="admin-tabs">
        @foreach(['basic' => '📝 Basic Info', 'content' => '📄 Content', 'media' => '🖼 Media', 'pricing' => '💰 Pricing & Stock', 'seo' => '🔍 SEO'] as $key => $label)
        <button type="button"
                @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}' ? 'active' : ''"
                style="padding: 10px 18px; font-size: 13px; font-weight: 500; background: none; border: none; border-bottom: 2px solid transparent; color: #737373; cursor: pointer; font-family: inherit; transition: all 0.15s;"
                :style="tab === '{{ $key }}' ? 'color: var(--admin-orange); border-bottom-color: var(--admin-orange);' : ''">
            {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ── Tab: Basic Info ─────────────────────────────────────────────── --}}
    <div x-show="tab === 'basic'">
        <div class="grid-2">
            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">🇬🇧 English</h2></div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label" for="name_en">Product Name *</label>
                        <input id="name_en" name="name_en" type="text" required class="form-input"
                               value="{{ old('name_en', $editing ? $product->getTranslation('name','en') : '') }}"
                               placeholder="e.g. Iron Veil Serum">
                        @error('name_en')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tagline_en">Tagline</label>
                        <input id="tagline_en" name="tagline_en" type="text" class="form-input"
                               value="{{ old('tagline_en', $editing ? $product->getTranslation('tagline','en') : '') }}"
                               placeholder="Short compelling hook">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="short_description_en">Short Description</label>
                        <textarea id="short_description_en" name="short_description_en" class="form-input form-textarea" style="min-height: 80px;"
                                  placeholder="2–3 sentence overview">{{ old('short_description_en', $editing ? $product->getTranslation('short_description','en') : '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">🇸🇦 Arabic</h2></div>
                <div class="admin-card-body" dir="rtl">
                    <div class="form-group">
                        <label class="form-label" for="name_ar">اسم المنتج</label>
                        <input id="name_ar" name="name_ar" type="text" class="form-input" dir="rtl"
                               value="{{ old('name_ar', $editing ? $product->getTranslation('name','ar') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="tagline_ar">الشعار</label>
                        <input id="tagline_ar" name="tagline_ar" type="text" class="form-input" dir="rtl"
                               value="{{ old('tagline_ar', $editing ? $product->getTranslation('tagline','ar') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="short_description_ar">وصف مختصر</label>
                        <textarea id="short_description_ar" name="short_description_ar" class="form-input form-textarea" dir="rtl" style="min-height: 80px;">{{ old('short_description_ar', $editing ? $product->getTranslation('short_description','ar') : '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Identifiers + Status --}}
        <div class="admin-card" style="margin-top: 20px;">
            <div class="admin-card-header"><h2 class="admin-card-title">Identifiers & Status</h2></div>
            <div class="admin-card-body">
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label" for="sku">SKU *</label>
                        <input id="sku" name="sku" type="text" required class="form-input mono"
                               value="{{ old('sku', $product->sku ?? '') }}" placeholder="FERRO-001">
                        @error('sku')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="slug">Slug (URL)</label>
                        <input id="slug" name="slug" type="text" class="form-input mono"
                               value="{{ old('slug', $product->slug ?? '') }}" placeholder="auto-generated from name">
                        <div class="form-hint">Leave blank to auto-generate</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="status">Status *</label>
                        <select id="status" name="status" required class="form-input form-select">
                            @foreach(['active' => 'Active', 'coming_soon' => 'Coming Soon', 'out_of_stock' => 'Out of Stock', 'archived' => 'Archived'] as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $product->status ?? 'active') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 16px; max-width: 420px;">
                    <label class="form-label" for="category_id">Shop category</label>
                    <select id="category_id" name="category_id" class="form-input form-select">
                        <option value="">— None —</option>
                        @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ (string) old('category_id', $product->category_id ?? '') === (string) $c->id ? 'selected' : '' }}>
                            {{ $c->getTranslation('name', 'en') }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                    @foreach(['is_featured' => '⭐ Featured on homepage', 'is_new_arrival' => '🆕 New Arrival badge', 'is_best_seller' => '🏆 Best Seller badge'] as $field => $label)
                    <label class="form-check">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1"
                               {{ old($field, $product->$field ?? false) ? 'checked' : '' }}>
                        {{ $label }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Content ─────────────────────────────────────────────────── --}}
    <div x-show="tab === 'content'" x-cloak>
        <div class="grid-2">
            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">🇬🇧 Full Description</h2></div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label" for="description_en">Description (EN)</label>
                        <textarea id="description_en" name="description_en" class="form-input form-textarea" style="min-height: 200px;">{{ old('description_en', $editing ? $product->getTranslation('description','en') : '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="ingredients_en">Ingredients (EN)</label>
                        <textarea id="ingredients_en" name="ingredients_en" class="form-input form-textarea mono" style="min-height: 120px;">{{ old('ingredients_en', $editing ? $product->getTranslation('ingredients','en') : '') }}</textarea>
                        <div class="form-hint">Comma-separated INCI names</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="how_to_use_en">How to Use (EN)</label>
                        <textarea id="how_to_use_en" name="how_to_use_en" class="form-input form-textarea">{{ old('how_to_use_en', $editing ? $product->getTranslation('how_to_use','en') : '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">🇸🇦 Arabic Content</h2></div>
                <div class="admin-card-body" dir="rtl">
                    <div class="form-group">
                        <label class="form-label" for="description_ar">الوصف الكامل</label>
                        <textarea id="description_ar" name="description_ar" class="form-input form-textarea" dir="rtl" style="min-height: 200px;">{{ old('description_ar', $editing ? $product->getTranslation('description','ar') : '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="ingredients_ar">المكونات</label>
                        <textarea id="ingredients_ar" name="ingredients_ar" class="form-input form-textarea mono" dir="rtl" style="min-height: 120px;">{{ old('ingredients_ar', $editing ? $product->getTranslation('ingredients','ar') : '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="how_to_use_ar">طريقة الاستخدام</label>
                        <textarea id="how_to_use_ar" name="how_to_use_ar" class="form-input form-textarea" dir="rtl">{{ old('how_to_use_ar', $editing ? $product->getTranslation('how_to_use','ar') : '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Media ────────────────────────────────────────────────────── --}}
    <div x-show="tab === 'media'" x-cloak>
        <div class="grid-2">

            {{-- Featured Image --}}
            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">Featured Image</h2></div>
                <div class="admin-card-body" x-data="{ preview: '{{ $editing && $product->featured_image ? Storage::disk('public')->url($product->featured_image) : '' }}' }">
                    @if($editing && $product->featured_image)
                    <div style="margin-bottom: 16px;">
                        <img :src="preview" src="{{ Storage::disk('public')->url($product->featured_image) }}"
                             alt="Current featured image"
                             style="max-width: 100%; max-height: 240px; border-radius: 4px; border: 1px solid var(--admin-border); object-fit: cover;">
                    </div>
                    @else
                    <div x-show="preview" style="margin-bottom: 16px;">
                        <img :src="preview" alt="Preview" style="max-width: 100%; max-height: 240px; border-radius: 4px; border: 1px solid var(--admin-border);">
                    </div>
                    @endif

                    <label class="form-label" for="featured_image">
                        {{ $editing && $product->featured_image ? 'Replace Image' : 'Upload Image' }}
                    </label>
                    <input id="featured_image" name="featured_image" type="file" accept="image/*"
                           class="form-input"
                           @change="preview = URL.createObjectURL($event.target.files[0])">
                    <div class="form-hint">JPG, PNG, WebP — max 5 MB. Recommended: 800×800px</div>
                </div>
            </div>

            {{-- Gallery Images --}}
            <div class="admin-card">
                <div class="admin-card-header"><h2 class="admin-card-title">Gallery Images</h2></div>
                <div class="admin-card-body">
                    @if($editing && !empty($product->gallery_images))
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
                        @foreach($product->gallery_images as $index => $img)
                        <div style="position: relative;" x-data>
                            <img src="{{ Storage::disk('public')->url($img) }}" alt="Gallery {{ $index + 1 }}"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 3px; border: 1px solid var(--admin-border);">
                            <button type="button"
                                    onclick="deleteGalleryImage({{ $product->id }}, {{ $index }}, this.closest('div'))"
                                    style="position:absolute;top:-6px;right:-6px;width:18px;height:18px;border-radius:50%;background:var(--admin-red);color:#fff;border:none;cursor:pointer;font-size:10px;display:flex;align-items:center;justify-content:center;line-height:1;"
                                    aria-label="Remove image">✕</button>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <label class="form-label" for="gallery">Add Gallery Images</label>
                    <input id="gallery" name="gallery[]" type="file" accept="image/*" multiple class="form-input">
                    <div class="form-hint">Hold Ctrl/Cmd to select multiple. Max 5 MB each.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: Pricing & Stock ─────────────────────────────────────────── --}}
    <div x-show="tab === 'pricing'" x-cloak>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">Pricing</h2></div>
            <div class="admin-card-body">
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label" for="price">Sale Price (LE) *</label>
                        <input id="price" name="price" type="number" step="0.01" min="0" required
                               class="form-input mono" value="{{ old('price', $product->price ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="compare_price">Compare-at Price</label>
                        <input id="compare_price" name="compare_price" type="number" step="0.01" min="0"
                               class="form-input mono" value="{{ old('compare_price', $product->compare_price ?? '') }}">
                        <div class="form-hint">Shows a strikethrough "was" price</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="cost_price">Cost Price (internal)</label>
                        <input id="cost_price" name="cost_price" type="number" step="0.01" min="0"
                               class="form-input mono" value="{{ old('cost_price', $product->cost_price ?? '') }}">
                        <div class="form-hint">Not shown to customers</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-card" style="margin-top: 20px;">
            <div class="admin-card-header"><h2 class="admin-card-title">Inventory</h2></div>
            <div class="admin-card-body">
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label" for="stock_quantity">Stock Quantity</label>
                        <input id="stock_quantity" name="stock_quantity" type="number" min="0"
                               class="form-input mono" value="{{ old('stock_quantity', $product->stock_quantity ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="low_stock_threshold">Low Stock Alert At</label>
                        <input id="low_stock_threshold" name="low_stock_threshold" type="number" min="0"
                               class="form-input mono"
                               value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 10) }}">
                        <div class="form-hint">Triggers admin email alert</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="sort_order">Sort Order</label>
                        <input id="sort_order" name="sort_order" type="number" min="0"
                               class="form-input mono"
                               value="{{ old('sort_order', $product->sort_order ?? 0) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab: SEO ──────────────────────────────────────────────────────── --}}
    <div x-show="tab === 'seo'" x-cloak>
        <div class="admin-card">
            <div class="admin-card-header"><h2 class="admin-card-title">SEO (English)</h2></div>
            <div class="admin-card-body">
                <div class="form-group">
                    <label class="form-label" for="seo_title_en">SEO Title</label>
                    <input id="seo_title_en" name="seo_title_en" type="text" class="form-input"
                           value="{{ old('seo_title_en', $editing ? $product->getTranslation('seo_title','en') : '') }}"
                           maxlength="300">
                    <div class="form-hint">Recommended: 50–70 characters</div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="seo_description_en">Meta Description</label>
                    <textarea id="seo_description_en" name="seo_description_en"
                              class="form-input form-textarea" style="min-height: 80px;"
                              maxlength="500">{{ old('seo_description_en', $editing ? $product->getTranslation('seo_description','en') : '') }}</textarea>
                    <div class="form-hint">Recommended: 120–160 characters</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky save bar --}}
    <div class="admin-form-footer">
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-primary">
            {{ $editing ? '💾 Save Changes' : '+ Create Product' }}
        </button>
    </div>
</form>

@push('scripts')
<script>
function deleteGalleryImage(productId, index, container) {
    if (!confirm('Remove this image?')) return;

    fetch(`/admin/products/${productId}/images/${index}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) container.remove();
        else alert('Failed to delete image.');
    })
    .catch(() => alert('Network error.'));
}
</script>
@endpush

@endsection
