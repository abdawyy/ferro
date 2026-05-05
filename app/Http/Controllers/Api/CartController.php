<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Client-side cart badge: sum quantities from localStorage on the browser.
     * This endpoint returns 0 so older clients do not break; prefer JS localStorage count.
     */
    public function count(): JsonResponse
    {
        return response()->json(['count' => 0]);
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        $qty = (int) ($data['quantity'] ?? 1);

        $product = Product::query()
            ->where('id', $data['product_id'])
            ->whereIn('status', [Product::STATUS_ACTIVE, Product::STATUS_OUT_OF_STOCK])
            ->first();

        if (! $product) {
            return response()->json(['success' => false, 'message' => __('Product not found.')], 404);
        }

        if ($product->status !== Product::STATUS_ACTIVE) {
            return response()->json(['success' => false, 'message' => __('This product is not available for purchase.')], 422);
        }

        if ($product->track_inventory && ! $product->allow_backorder && $product->stock_quantity < $qty) {
            return response()->json(['success' => false, 'message' => __('Not enough stock.')], 422);
        }

        $locale = app()->getLocale();
        $name = $product->getTranslation('name', $locale, false) ?: $product->getTranslation('name', 'en', false) ?: $product->name;
        $category = $product->category?->getTranslation('name', $locale, false) ?? $product->category?->name ?? '';

        $image = (string) (ferro_public_url($product->featured_image) ?? '');

        $item = [
            'id' => $product->id,
            'name' => $name,
            'price' => (float) $product->price,
            'currency' => $product->currency ?? 'EGP',
            'qty' => $qty,
            'image' => $image,
            'url' => route('products.show', $product->slug),
            'category' => $category,
        ];

        return response()->json([
            'success' => true,
            'item' => $item,
            'cart_count' => $qty,
        ]);
    }
}
