<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Product listing / shop page.
     */
    public function index(Request $request): View
    {
        $query = Product::visible()
            ->with('category')
            ->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = ProductCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Product Detail Page (PDP).
     * Supports bilingual content via spatie/laravel-translatable.
     */
    public function show(string $slug): View
    {
        $product = Product::visible()
            ->with(['category', 'waitlistEntries'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Related products in same category
        $related = Product::visible()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        // SEO: structured data for current locale
        $locale     = app()->getLocale();
        $schemaOrg  = $product->toSchemaOrg($locale);
        $seoTitle   = $product->getSeoTitleForLocale($locale);
        $seoDesc    = $product->getTranslation('seo_description', $locale, false)
                      ?? strip_tags(substr($product->getTranslation('short_description', $locale, false) ?? '', 0, 160));

        return view('products.show', compact(
            'product', 'related', 'schemaOrg', 'seoTitle', 'seoDesc'
        ));
    }
}
