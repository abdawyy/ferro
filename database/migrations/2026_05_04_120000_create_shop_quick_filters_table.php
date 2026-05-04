<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shop_quick_filters')) {
            return;
        }

        Schema::create('shop_quick_filters', function (Blueprint $table) {
            $table->id();
            $table->string('product_status', 32)->index();
            $table->json('name');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        DB::table('shop_quick_filters')->insert([
            [
                'product_status' => 'active',
                'name'           => json_encode(['en' => 'In Stock', 'ar' => 'متاح']),
                'is_active'      => true,
                'sort_order'     => 1,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
            [
                'product_status' => 'coming_soon',
                'name'           => json_encode(['en' => 'Coming Soon', 'ar' => 'قريباً']),
                'is_active'      => true,
                'sort_order'     => 2,
                'created_at'     => $now,
                'updated_at'     => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_quick_filters');
    }
};
