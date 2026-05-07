<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storefront_seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 64)->unique();
            $table->string('meta_title_en', 300)->nullable();
            $table->string('meta_title_ar', 300)->nullable();
            $table->text('meta_description_en')->nullable();
            $table->text('meta_description_ar')->nullable();
            $table->string('meta_keywords_en', 500)->nullable();
            $table->string('meta_keywords_ar', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storefront_seo_pages');
    }
};
