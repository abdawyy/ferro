<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storefront_brand_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_logo')->default(false);
            $table->boolean('show_favicon')->default(false);
            $table->timestamps();
        });

        DB::table('storefront_brand_settings')->insert([
            'show_logo' => false,
            'show_favicon' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (Schema::hasTable('storefront_media')) {
            DB::table('storefront_media')
                ->whereIn('key', ['brand.logo', 'brand.favicon', 'brand.apple_touch'])
                ->delete();
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_brand_settings');
    }
};
