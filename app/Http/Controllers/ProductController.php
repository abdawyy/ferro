<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ShopQuickFilter;
use App\Support\ShopCatalogPaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Product listing / shop page.
     */
    public function index(Request $request): View
    {
        $products = ShopCatalogPaginator::paginate($request, 12);
        $categories = ProductCategory::where('is_active', true)->orderBy('sort_order')->get();
        $shopQuickFilters = ShopQuickFilter::where('is_active', true)->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories', 'shopQuickFilters'));
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
        $locale = app()->getLocale();
        $schemaOrg = $product->toSchemaOrg($locale);
        $seoTitle = $product->getSeoTitleForLocale($locale);
        $seoDesc = $product->getTranslation('seo_description', $locale, false)
                      ?? strip_tags(substr($product->getTranslation('short_description', $locale, false) ?? '', 0, 160));

        return view('products.show', compact(
            'product', 'related', 'schemaOrg', 'seoTitle', 'seoDesc'
        ));
    }
}
