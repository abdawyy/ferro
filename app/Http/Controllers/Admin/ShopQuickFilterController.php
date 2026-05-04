<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShopQuickFilter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ShopQuickFilterController extends Controller
{
    public function index(): View
    {
        $filters = ShopQuickFilter::orderBy('sort_order')->orderBy('id')->paginate(30);

        return view('admin.shop-quick-filters.index', compact('filters'));
    }

    public function create(): View
    {
        return view('admin.shop-quick-filters.edit', ['filter' => new ShopQuickFilter()]);
    }

    public function store(Request $request): RedirectResponse
    {
        ShopQuickFilter::create($this->validated($request));

        return redirect()->route('admin.shop-quick-filters.index')
            ->with('success', 'Shop filter created.');
    }

    public function edit(ShopQuickFilter $shop_quick_filter): View
    {
        return view('admin.shop-quick-filters.edit', ['filter' => $shop_quick_filter]);
    }

    public function update(Request $request, ShopQuickFilter $shop_quick_filter): RedirectResponse
    {
        $shop_quick_filter->update($this->validated($request, $shop_quick_filter->id));

        return redirect()->route('admin.shop-quick-filters.index')
            ->with('success', 'Shop filter saved.');
    }

    public function destroy(ShopQuickFilter $shop_quick_filter): RedirectResponse
    {
        $shop_quick_filter->delete();

        return redirect()->route('admin.shop-quick-filters.index')
            ->with('success', 'Shop filter deleted.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $statusRule = Rule::in([
            Product::STATUS_ACTIVE,
            Product::STATUS_COMING_SOON,
            Product::STATUS_OUT_OF_STOCK,
        ]);

        $uniqueStatus = Rule::unique('shop_quick_filters', 'product_status');
        if ($id) {
            $uniqueStatus = $uniqueStatus->ignore($id);
        }

        $request->validate([
            'product_status' => ['required', 'string', 'max:32', $statusRule, $uniqueStatus],
            'name_en'        => 'required|string|max:120',
            'name_ar'        => 'nullable|string|max:120',
            'sort_order'     => 'integer|min:0',
            'is_active'      => 'boolean',
        ]);

        return [
            'product_status' => $request->product_status,
            'name'           => ['en' => $request->name_en, 'ar' => $request->name_ar],
            'sort_order'     => $request->input('sort_order', 0),
            'is_active'      => $request->boolean('is_active'),
        ];
    }
}
