<?php
// ─────────────────────────────────────────────────────────────────────────────
// FERRO — Products Table Migration
// Supports: bilingual JSON fields, product status flag, subscription eligibility
// ─────────────────────────────────────────────────────────────────────────────

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            // Translatable name — JSON column strategy (spatie/laravel-translatable)
            $table->json('name');         // {"en": "Face Care", "ar": "العناية بالوجه"}
            $table->json('description')->nullable();
            $table->string('slug')->unique();
            $table->string('image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();

            // ── Core identification ────────────────────────────────────────
            $table->string('sku')->unique();
            $table->string('slug')->unique();

            // ── Bilingual content (JSON columns via spatie/laravel-translatable) ──
            // Strategy: JSON columns — optimal for <5 locales, single-query reads,
            // no JOIN overhead. spatie/laravel-translatable wraps these transparently.
            $table->json('name');
            // {"en": "Iron Recovery Serum", "ar": "سيروم الاسترداد الحديدي"}
            $table->json('tagline')->nullable();
            // {"en": "Built for the elite athlete", "ar": "مصمم للرياضي النخبة"}
            $table->json('description');
            // {"en": "<rich HTML>", "ar": "<rich HTML>"}
            $table->json('short_description')->nullable();
            $table->json('ingredients')->nullable();
            // {"en": "Magnesium, Arnica, ...", "ar": "المغنيسيوم، أرنيكا، ..."}
            $table->json('how_to_use')->nullable();
            $table->json('benefits')->nullable();
            // {"en": ["Reduces inflammation", ...], "ar": ["يقلل الالتهاب", ...]}
            $table->json('seo_title')->nullable();
            $table->json('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();

            // ── Pricing ────────────────────────────────────────────────────
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();  // crossed-out price
            $table->decimal('cost_price', 10, 2)->nullable();     // internal margin calc
            $table->string('currency', 3)->default('USD');

            // ── Inventory & Status ─────────────────────────────────────────
            // status: 'coming_soon' | 'active' | 'out_of_stock' | 'archived'
            $table->enum('status', ['coming_soon', 'active', 'out_of_stock', 'archived'])
                  ->default('coming_soon')
                  ->index();
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_backorder')->default(false);

            // ── Physical attributes ────────────────────────────────────────
            $table->decimal('weight_grams', 8, 2)->nullable();
            $table->string('volume_ml')->nullable();
            $table->json('dimensions')->nullable(); // {"length":..,"width":..,"height":..}

            // ── Subscription eligibility (Advanced Feature #1) ─────────────
            $table->boolean('is_subscribable')->default(false);
            $table->json('subscription_intervals')->nullable();
            // {"options": [30, 60, 90], "discount_percent": 15}

            // ── Personalization quiz mapping (Advanced Feature #2) ─────────
            $table->json('quiz_tags')->nullable();
            // ["oily_skin", "post_workout", "sensitive"]

            // ── Media ──────────────────────────────────────────────────────
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();  // array of paths
            $table->string('video_url')->nullable();

            // ── Display flags ──────────────────────────────────────────────
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamp('available_at')->nullable(); // launch date
            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ────────────────────────────────────────────────────
            $table->index(['status', 'is_featured']);
            $table->index(['status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
