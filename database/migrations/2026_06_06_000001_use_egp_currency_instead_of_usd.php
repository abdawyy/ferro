<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')->where('currency', 'USD')->update(['currency' => 'EGP']);
        DB::table('orders')->where('currency', 'USD')->update(['currency' => 'EGP']);
        DB::table('shipping_cities')->where('currency', 'USD')->update(['currency' => 'EGP']);
    }

    public function down(): void
    {
        // No rollback — Egypt storefront is LE-only.
    }
};
