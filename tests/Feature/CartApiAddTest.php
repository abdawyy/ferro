<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartApiAddTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_add_returns_item_for_active_product(): void
    {
        $product = Product::query()->create([
            'sku'               => 'SKU-ADD-1',
            'slug'              => 'test-add-cart',
            'name'              => ['en' => 'Test', 'ar' => 'اختبار'],
            'description'       => ['en' => 'Desc', 'ar' => 'وصف'],
            'price'             => 19.99,
            'currency'          => 'EGP',
            'status'            => Product::STATUS_ACTIVE,
            'stock_quantity'    => 100,
            'track_inventory'   => true,
            'allow_backorder'   => false,
        ]);

        $response = $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity'   => 1,
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('item.id', $product->id);
    }
}
