<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSetting extends Model
{
    private static ?self $memoryCache = null;

    protected $fillable = [
        'is_enabled',
        'delay_seconds',
        'title_en',
        'title_ar',
        'message_en',
        'message_ar',
        'button_text_en',
        'button_text_ar',
        'success_message_en',
        'success_message_ar',
        'discount_percent',
        'coupon_prefix',
        'coupon_valid_days',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'delay_seconds' => 'integer',
            'discount_percent' => 'integer',
            'coupon_valid_days' => 'integer',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultAttributes(): array
    {
        return [
            'is_enabled' => false,
            'delay_seconds' => 5,
            'title_en' => 'Get Your Exclusive Discount',
            'title_ar' => 'احصل على خصم حصري',
            'message_en' => 'Subscribe with your email and receive a coupon code for your next order.',
            'message_ar' => 'اشترك ببريدك الإلكتروني واحصل على كود خصم لطلبك القادم.',
            'button_text_en' => 'Subscribe & Get Coupon',
            'button_text_ar' => 'اشترك واحصل على الكوبون',
            'success_message_en' => 'Check your inbox — your coupon is on the way!',
            'success_message_ar' => 'تحقق من بريدك — كود الخصم في الطريق!',
            'discount_percent' => 10,
            'coupon_prefix' => 'FERRO',
            'coupon_valid_days' => 30,
        ];
    }

    public static function current(): self
    {
        if (self::$memoryCache instanceof self) {
            return self::$memoryCache;
        }

        $row = static::query()->first();
        if ($row !== null) {
            self::$memoryCache = $row;

            return $row;
        }

        self::$memoryCache = static::query()->create(static::defaultAttributes());

        return self::$memoryCache;
    }

    public static function flushMemoryCache(): void
    {
        self::$memoryCache = null;
    }

    public function title(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return (string) ($locale === 'ar' ? ($this->title_ar ?: $this->title_en) : ($this->title_en ?: $this->title_ar));
    }

    public function message(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return (string) ($locale === 'ar' ? ($this->message_ar ?: $this->message_en) : ($this->message_en ?: $this->message_ar));
    }

    public function buttonText(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return (string) ($locale === 'ar'
            ? ($this->button_text_ar ?: $this->button_text_en)
            : ($this->button_text_en ?: $this->button_text_ar));
    }

    public function successMessage(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        return (string) ($locale === 'ar'
            ? ($this->success_message_ar ?: $this->success_message_en)
            : ($this->success_message_en ?: $this->success_message_ar));
    }
}
