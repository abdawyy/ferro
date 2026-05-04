<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::orderBy('sort_order')->orderBy('id')->paginate(30);

        return view('admin.product-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.product-categories.edit', ['category' => new ProductCategory()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        ProductCategory::create($data);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(ProductCategory $product_category): View
    {
        return view('admin.product-categories.edit', ['category' => $product_category]);
    }

    public function update(Request $request, ProductCategory $product_category): RedirectResponse
    {
        $product_category->update($this->validated($request, $product_category->id));

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category saved.');
    }

    public function destroy(ProductCategory $product_category): RedirectResponse
    {
        $product_category->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Category deleted.');
    }

    private function validated(Request $request, ?int $categoryId = null): array
    {
        $slugRule = 'required|string|max:255|regex:/^[a-z0-9\-]+$/';
        $slugRule .= $categoryId
            ? '|unique:product_categories,slug,'.$categoryId
            : '|unique:product_categories,slug';

        $request->validate([
            'name_en'        => 'required|string|max:300',
            'name_ar'        => 'nullable|string|max:300',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'slug'           => $slugRule,
            'sort_order'     => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);

        return [
            'name'        => ['en' => $request->name_en, 'ar' => $request->name_ar],
            'description' => ['en' => $request->description_en, 'ar' => $request->description_ar],
            'slug'        => $request->slug ?: Str::slug($request->name_en),
            'sort_order'  => $request->input('sort_order', 0),
            'is_active'   => $request->boolean('is_active'),
        ];
    }
}
