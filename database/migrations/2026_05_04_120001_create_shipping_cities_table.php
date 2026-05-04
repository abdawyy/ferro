<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shipping_cities')) {
            return;
        }

        Schema::create('shipping_cities', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->json('name');
            $table->decimal('shipping_price', 10, 4)->default('0.0000');
            $table->string('currency', 3)->default('EGP');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['cairo', ['en' => 'Cairo', 'ar' => 'القاهرة'], '45.0000', 10],
            ['giza', ['en' => 'Giza', 'ar' => 'الجيزة'], '45.0000', 20],
            ['alexandria', ['en' => 'Alexandria', 'ar' => 'الإسكندرية'], '55.0000', 30],
            ['qalyubia', ['en' => 'Qalyubia', 'ar' => 'القليوبية'], '50.0000', 40],
            ['dakahlia', ['en' => 'Dakahlia', 'ar' => 'الدقهلية'], '60.0000', 50],
            ['sharkia', ['en' => 'Sharqia', 'ar' => 'الشرقية'], '60.0000', 60],
            ['gharbia', ['en' => 'Gharbia', 'ar' => 'الغربية'], '65.0000', 70],
            ['monufia', ['en' => 'Monufia', 'ar' => 'المنوفية'], '65.0000', 80],
            ['beheira', ['en' => 'Beheira', 'ar' => 'البحيرة'], '70.0000', 90],
            ['kafr_el_sheikh', ['en' => 'Kafr El Sheikh', 'ar' => 'كفر الشيخ'], '70.0000', 100],
            ['damietta', ['en' => 'Damietta', 'ar' => 'دمياط'], '65.0000', 110],
            ['port_said', ['en' => 'Port Said', 'ar' => 'بورسعيد'], '70.0000', 120],
            ['ismailia', ['en' => 'Ismailia', 'ar' => 'الإسماعيلية'], '70.0000', 130],
            ['suez', ['en' => 'Suez', 'ar' => 'السويس'], '70.0000', 140],
            ['fayoum', ['en' => 'Fayoum', 'ar' => 'الفيوم'], '65.0000', 150],
            ['beni_suef', ['en' => 'Beni Suef', 'ar' => 'بني سويف'], '65.0000', 160],
            ['minya', ['en' => 'Minya', 'ar' => 'المنيا'], '75.0000', 170],
            ['assiut', ['en' => 'Assiut', 'ar' => 'أسيوط'], '80.0000', 180],
            ['sohag', ['en' => 'Sohag', 'ar' => 'سوهاج'], '80.0000', 190],
            ['qena', ['en' => 'Qena', 'ar' => 'قنا'], '85.0000', 200],
            ['luxor', ['en' => 'Luxor', 'ar' => 'الأقصر'], '90.0000', 210],
            ['aswan', ['en' => 'Aswan', 'ar' => 'أسوان'], '95.0000', 220],
            ['red_sea', ['en' => 'Red Sea', 'ar' => 'البحر الأحمر'], '95.0000', 230],
            ['matruh', ['en' => 'Matrouh', 'ar' => 'مرسى مطروح'], '95.0000', 240],
            ['new_valley', ['en' => 'New Valley (Wadi El Gedid)', 'ar' => 'الوادي الجديد'], '100.0000', 250],
            ['north_sinai', ['en' => 'North Sinai', 'ar' => 'شمال سيناء'], '100.0000', 260],
            ['south_sinai', ['en' => 'South Sinai', 'ar' => 'جنوب سيناء'], '100.0000', 270],
        ];

        foreach ($rows as [$slug, $name, $price, $sort]) {
            DB::table('shipping_cities')->insert([
                'slug'            => $slug,
                'name'            => json_encode($name, JSON_UNESCAPED_UNICODE),
                'shipping_price'  => $price,
                'currency'        => 'EGP',
                'sort_order'      => $sort,
                'is_active'       => true,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_cities');
    }
};
