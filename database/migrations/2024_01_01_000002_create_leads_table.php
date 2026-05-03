<?php
// ─────────────────────────────────────────────────────────────────────────────
// FERRO — Leads Table Migration
// "Leads" first — CRM terminology for waitlist & early funnel (not "Clients")
// ─────────────────────────────────────────────────────────────────────────────

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            // ── Identity ───────────────────────────────────────────────────
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('country_code', 5)->nullable();

            // ── Lead classification ────────────────────────────────────────
            // source: 'waitlist' | 'quiz' | 'abandoned_cart' | 'newsletter' | 'checkout' | 'referral'
            $table->enum('source', ['waitlist', 'quiz', 'abandoned_cart', 'newsletter', 'checkout', 'referral', 'organic'])
                  ->default('waitlist')
                  ->index();

            // priority: 'standard' | 'high' | 'vip'
            // VIP = repeat engagement, high cart value, or quiz-identified elite athlete
            $table->enum('priority', ['standard', 'high', 'vip'])->default('standard')->index();

            // status: 'new' | 'engaged' | 'qualified' | 'converted' | 'unsubscribed'
            $table->enum('status', ['new', 'engaged', 'qualified', 'converted', 'unsubscribed'])
                  ->default('new')
                  ->index();

            // ── Language preference ────────────────────────────────────────
            $table->string('preferred_language', 2)->default('en');

            // ── CRM enrichment ─────────────────────────────────────────────
            $table->json('quiz_results')->nullable();
            // {"skin_type":"oily","lifestyle":"athlete","concerns":["recovery","hydration"]}
            $table->json('product_interests')->nullable(); // product IDs they've shown interest in
            $table->json('utm_data')->nullable();
            // {"utm_source":"instagram","utm_medium":"paid","utm_campaign":"launch_ss25"}
            $table->json('custom_attributes')->nullable(); // flexible CRM fields

            // ── Funnel tracking ────────────────────────────────────────────
            $table->integer('engagement_score')->default(0); // incremented by events
            $table->timestamp('last_engaged_at')->nullable();
            $table->timestamp('converted_at')->nullable();   // when they placed first order
            $table->unsignedBigInteger('converted_order_id')->nullable(); // FK added after orders table in migration 000003

            // ── Waitlist ───────────────────────────────────────────────────
            $table->boolean('on_waitlist')->default(true);
            $table->foreignId('waitlist_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamp('waitlist_notified_at')->nullable();

            // ── Abandoned cart ─────────────────────────────────────────────
            $table->json('abandoned_cart_items')->nullable();
            $table->decimal('abandoned_cart_value', 10, 2)->nullable();
            $table->timestamp('cart_abandoned_at')->nullable();
            $table->integer('recovery_emails_sent')->default(0);
            $table->timestamp('last_recovery_sent_at')->nullable();

            // ── Consent ───────────────────────────────────────────────────
            $table->boolean('marketing_consent')->default(false);
            $table->boolean('gdpr_consent')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->string('ip_address', 45)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
            $table->index(['on_waitlist', 'waitlist_product_id']);
            $table->index('engagement_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
