<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->unsignedSmallInteger('delay_seconds')->default(5);
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->text('message_en')->nullable();
            $table->text('message_ar')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_text_ar')->nullable();
            $table->string('success_message_en')->nullable();
            $table->string('success_message_ar')->nullable();
            $table->unsignedTinyInteger('discount_percent')->default(10);
            $table->string('coupon_prefix', 20)->default('FERRO');
            $table->unsignedSmallInteger('coupon_valid_days')->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('preferred_language', 5)->default('en');
            $table->string('coupon_code', 40)->unique();
            $table->unsignedTinyInteger('discount_percent');
            $table->timestamp('coupon_expires_at')->nullable();
            $table->timestamp('subscribed_at');
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['unsubscribed_at', 'subscribed_at']);
        });

        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('subject_en');
            $table->string('subject_ar')->nullable();
            $table->text('body_en');
            $table->text('body_ar')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('send_to', 20)->default('all');
            $table->string('status', 20)->default('draft');
            $table->unsignedInteger('sent_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('newsletter_campaign_subscriber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_campaign_id')->constrained()->cascadeOnDelete();
            $table->foreignId('newsletter_subscriber_id')->constrained()->cascadeOnDelete();
            $table->timestamp('sent_at')->nullable();
            $table->boolean('failed')->default(false);
            $table->timestamps();

            $table->unique(['newsletter_campaign_id', 'newsletter_subscriber_id'], 'campaign_subscriber_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaign_subscriber');
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('newsletter_settings');
    }
};
