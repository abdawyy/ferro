<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY sku VARCHAR(191) NOT NULL');
        }

        DB::table('products')
            ->whereNotNull('deleted_at')
            ->orderBy('id')
            ->lazyById()
            ->each(function (object $row): void {
                $suffix = '__archived__'.$row->id;
                if (str_ends_with((string) $row->sku, $suffix)) {
                    return;
                }
                DB::table('products')->where('id', $row->id)->update([
                    'sku' => $row->sku.$suffix,
                ]);
            });
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE products MODIFY sku VARCHAR(100) NOT NULL');
        }
    }
};
