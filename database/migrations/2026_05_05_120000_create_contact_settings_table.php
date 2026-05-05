<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();
            $table->string('support_email', 255);
            $table->string('email_heading_en', 120)->nullable();
            $table->string('email_heading_ar', 120)->nullable();
            $table->string('live_chat_heading_en', 120)->nullable();
            $table->string('live_chat_heading_ar', 120)->nullable();
            $table->string('live_chat_text_en', 500)->nullable();
            $table->string('live_chat_text_ar', 500)->nullable();
            $table->string('hq_heading_en', 120)->nullable();
            $table->string('hq_heading_ar', 120)->nullable();
            $table->string('hq_text_en', 500)->nullable();
            $table->string('hq_text_ar', 500)->nullable();
            $table->string('follow_heading_en', 120)->nullable();
            $table->string('follow_heading_ar', 120)->nullable();
            $table->string('social_instagram_url', 500)->nullable();
            $table->string('social_tiktok_url', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};
