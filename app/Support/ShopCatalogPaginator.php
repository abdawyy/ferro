<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

final class ShopCatalogPaginator
{
    public static function paginate(Request $request, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::visible()
            ->with('category')
            ->orderBy('sort_order');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', (string) $request->input('category')));
        }

        if ($request->filled('status')) {
            $status = (string) $request->input('status');
            if (in_array($status, [Product::STATUS_ACTIVE, Product::STATUS_COMING_SOON, Product::STATUS_OUT_OF_STOCK], true)) {
                $query->where('status', $status);
            }
        }

        if ($request->filled('q')) {
            $term = '%'.addcslashes(trim($request->input('q')), '%_\\').'%';
            $query->where(function ($w) use ($term) {
                $w->where('sku', 'like', $term)
                    ->orWhere('slug', 'like', $term)
                    ->orWhere('name->en', 'like', $term)
                    ->orWhere('name->ar', 'like', $term);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }
}
