<?php
// ─────────────────────────────────────────────────────────────────────────────
// FERRO — Subscriptions, Waitlist Entries, Inventory Alerts, Abandoned Carts
// ─────────────────────────────────────────────────────────────────────────────

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Product Subscriptions (Advanced Feature #1) ────────────────────
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active'); // active | paused | cancelled
            $table->integer('interval_days');            // 30 | 60 | 90
            $table->decimal('discounted_price', 10, 4);
            $table->decimal('original_price', 10, 4);
            $table->timestamp('next_billing_at');
            $table->timestamp('last_billed_at')->nullable();
            $table->integer('billing_cycle_count')->default(0);
            $table->string('payment_method_id')->nullable(); // Stripe PM id
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['status', 'next_billing_at']);
        });

        // ── Waitlist product-specific entries ─────────────────────────────
        Schema::create('waitlist_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->string('email');
            $table->string('preferred_language', 2)->default('en');
            $table->string('position')->nullable();        // queue position for exclusivity
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('purchased_at')->nullable();
            $table->timestamps();
            $table->unique(['product_id', 'email']);
            $table->index(['product_id', 'notified']);
        });

        // ── Inventory alerts (Admin notification trigger) ──────────────────
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('alert_type', ['low_stock', 'out_of_stock', 'back_in_stock']);
            $table->integer('stock_at_alert');
            $table->boolean('admin_notified')->default(false);
            $table->timestamp('admin_notified_at')->nullable();
            $table->timestamps();
        });

        // ── Skincare quiz results (Advanced Feature #2) ───────────────────
        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->json('answers');
            // {"q1":"oily","q2":"post_workout","q3":["recovery","hydration","texture"]}
            $table->json('recommended_product_ids')->nullable();
            $table->string('skin_profile')->nullable(); // "Athlete Recovery" | "Urban Resilience"
            $table->boolean('email_captured')->default(false);
            $table->timestamps();
        });

        // ── Loyalty points (Advanced Feature #3) ─────────────────────────
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['earned', 'redeemed', 'expired', 'bonus', 'referral']);
            $table->integer('points');  // positive = earned, negative = redeemed
            $table->integer('balance_after');
            $table->string('description');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('quiz_sessions');
        Schema::dropIfExists('inventory_alerts');
        Schema::dropIfExists('waitlist_entries');
        Schema::dropIfExists('subscriptions');
    }
};
