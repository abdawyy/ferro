<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_settings', function (Blueprint $table) {
            $table->boolean('show_instagram')->default(true)->after('social_tiktok_url');
            $table->boolean('show_tiktok')->default(true)->after('show_instagram');
            $table->boolean('show_facebook')->default(false)->after('show_tiktok');
            $table->boolean('show_snapchat')->default(false)->after('show_facebook');
            $table->boolean('show_whatsapp')->default(false)->after('show_snapchat');
            $table->string('social_facebook_url', 500)->nullable()->after('show_whatsapp');
            $table->string('social_snapchat_url', 500)->nullable()->after('social_facebook_url');
            $table->string('social_whatsapp_url', 500)->nullable()->after('social_snapchat_url');
        });
    }

    public function down(): void
    {
        Schema::table('contact_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_instagram',
                'show_tiktok',
                'show_facebook',
                'show_snapchat',
                'show_whatsapp',
                'social_facebook_url',
                'social_snapchat_url',
                'social_whatsapp_url',
            ]);
        });
    }
};
