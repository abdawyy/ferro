<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ShopCatalogPaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopCatalogController extends Controller
{
    /**
     * JSON fragment for AJAX shop filtering (grid + pagination HTML).
     */
    public function show(Request $request): JsonResponse
    {
        $products = ShopCatalogPaginator::paginate($request, 12);

        return response()->json([
            'html'         => view('products.partials.shop-results', compact('products'))->render(),
            'total'        => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
            'has_more'     => $products->hasMorePages(),
            'query_string' => $request->getQueryString() ?? '',
        ]);
    }
}
