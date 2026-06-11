<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('storefront_brand_settings')) {
            return;
        }

        // Default F mark + favicon are always on; toggles only switch to custom uploads.
        \Illuminate\Support\Facades\DB::table('storefront_brand_settings')->update([
            'show_logo' => false,
            'show_favicon' => false,
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        //
    }
};
