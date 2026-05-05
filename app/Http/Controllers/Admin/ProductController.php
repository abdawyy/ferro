<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $products = $query->orderBy('sort_order')->orderBy('created_at', 'desc')
            ->paginate(20)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = ProductCategory::orderBy('sort_order')->get();

        return view('admin.products.edit', ['product' => new Product, 'editing' => false, 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($request->input('name_en'));

        $product = Product::create($data);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $product->update([
                'featured_image' => $request->file('featured_image')
                    ->store('products', 'public'),
            ]);
        }

        // Handle gallery images
        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('products/gallery', 'public');
            }
            $product->update(['gallery_images' => $gallery]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', "Product '{$request->input('name_en')}' created.");
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::orderBy('sort_order')->get();

        return view('admin.products.edit', ['product' => $product, 'editing' => true, 'categories' => $categories]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product->id);

        // Replace featured image if new one uploaded
        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                Storage::disk('public')->delete($product->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')
                ->store('products', 'public');
        }

        // Append new gallery images
        if ($request->hasFile('gallery')) {
            $existing = $product->gallery_images ?? [];
            foreach ($request->file('gallery') as $file) {
                $existing[] = $file->store('products/gallery', 'public');
            }
            $data['gallery_images'] = $existing;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete(); // SoftDeletes

        return redirect()->route('admin.products.index')
            ->with('success', 'Product archived (soft-deleted).');
    }

    public function restore(Product $product): RedirectResponse
    {
        if (! $product->trashed()) {
            return redirect()->route('admin.products.index')
                ->with('success', 'Product is not archived.');
        }

        $product->restore();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product restored and visible in the catalogue again.');
    }

    /**
     * Upload a single image and add it to gallery_images.
     */
    public function uploadImage(Request $request, Product $product): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:5120']);

        $path = $request->file('image')->store('products/gallery', 'public');

        $gallery = $product->gallery_images ?? [];
        $gallery[] = $path;
        $product->update(['gallery_images' => $gallery]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => ferro_public_url($path),
            'index' => count($gallery) - 1,
        ]);
    }

    /**
     * Delete a gallery image by index.
     */
    public function deleteImage(Product $product, int $index): JsonResponse
    {
        $gallery = $product->gallery_images ?? [];

        if (! isset($gallery[$index])) {
            return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
        }

        Storage::disk('public')->delete($gallery[$index]);
        array_splice($gallery, $index, 1);
        $product->update(['gallery_images' => array_values($gallery)]);

        return response()->json(['success' => true]);
    }

    private function validated(Request $request, ?int $productId = null): array
    {
        $slugUnique = Rule::unique('products', 'slug')->whereNull('deleted_at');
        if ($productId) {
            $slugUnique = $slugUnique->ignore($productId);
        }

        $skuUnique = Rule::unique('products', 'sku')->whereNull('deleted_at');
        if ($productId) {
            $skuUnique = $skuUnique->ignore($productId);
        }

        $request->validate([
            'name_en' => 'required|string|max:300',
            'name_ar' => 'nullable|string|max:300',
            'tagline_en' => 'nullable|string|max:500',
            'tagline_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string|max:500',
            'short_description_ar' => 'nullable|string|max:500',
            'ingredients_en' => 'nullable|string',
            'ingredients_ar' => 'nullable|string',
            'how_to_use_en' => 'nullable|string',
            'how_to_use_ar' => 'nullable|string',
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/', $slugUnique],
            'sku' => ['required', 'string', 'max:191', $skuUnique],
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:coming_soon,active,out_of_stock,archived',
            'stock_quantity' => 'nullable|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_new_arrival' => 'boolean',
            'is_best_seller' => 'boolean',
            'sort_order' => 'integer|min:0',
            'category_id' => 'nullable|exists:product_categories,id',
            'seo_title_en' => 'nullable|string|max:300',
            'seo_description_en' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'gallery.*' => 'nullable|image|max:5120',
        ]);

        return [
            'name' => ['en' => $request->name_en,              'ar' => $request->name_ar],
            'tagline' => ['en' => $request->tagline_en,           'ar' => $request->tagline_ar],
            'description' => ['en' => $request->description_en,       'ar' => $request->description_ar],
            'short_description' => ['en' => $request->short_description_en, 'ar' => $request->short_description_ar],
            'ingredients' => ['en' => $request->ingredients_en,       'ar' => $request->ingredients_ar],
            'how_to_use' => ['en' => $request->how_to_use_en,        'ar' => $request->how_to_use_ar],
            'seo_title' => ['en' => $request->seo_title_en,         'ar' => null],
            'seo_description' => ['en' => $request->seo_description_en,   'ar' => null],
            'slug' => $request->input('slug') ?: Str::slug($request->input('name_en')),
            'sku' => $request->sku,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'cost_price' => $request->cost_price,
            'status' => $request->status,
            'stock_quantity' => $request->stock_quantity,
            'low_stock_threshold' => $request->input('low_stock_threshold', 10),
            'is_featured' => $request->boolean('is_featured'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'sort_order' => $request->input('sort_order', 0),
            'category_id' => $request->input('category_id') ?: null,
        ];
    }
}
