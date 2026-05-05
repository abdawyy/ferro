<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton storefront contact block (contact page, footer socials, schema.org, emails).
 */
class ContactSetting extends Model
{
    /** @var self|null Request-scoped memo for composers + layout. */
    private static ?self $memoryCache = null;

    protected $fillable = [
        'support_email',
        'email_heading_en',
        'email_heading_ar',
        'live_chat_heading_en',
        'live_chat_heading_ar',
        'live_chat_text_en',
        'live_chat_text_ar',
        'hq_heading_en',
        'hq_heading_ar',
        'hq_text_en',
        'hq_text_ar',
        'follow_heading_en',
        'follow_heading_ar',
        'social_instagram_url',
        'social_tiktok_url',
        'show_instagram',
        'show_tiktok',
        'show_facebook',
        'show_snapchat',
        'show_whatsapp',
        'social_facebook_url',
        'social_snapchat_url',
        'social_whatsapp_url',
    ];

    protected function casts(): array
    {
        return [
            'show_instagram' => 'boolean',
            'show_tiktok' => 'boolean',
            'show_facebook' => 'boolean',
            'show_snapchat' => 'boolean',
            'show_whatsapp' => 'boolean',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function defaultAttributes(): array
    {
        return [
            'support_email' => 'support@ferro.com',
            'email_heading_en' => 'Email',
            'email_heading_ar' => 'البريد الإلكتروني',
            'live_chat_heading_en' => 'Live Chat',
            'live_chat_heading_ar' => 'الدردشة المباشرة',
            'live_chat_text_en' => 'Available 7 days, 9am–9pm',
            'live_chat_text_ar' => 'متاح ٧ أيام ٩ص–٩م',
            'hq_heading_en' => 'Headquarters',
            'hq_heading_ar' => 'المقر الرئيسي',
            'hq_text_en' => 'Dubai, United Arab Emirates',
            'hq_text_ar' => 'دبي، الإمارات العربية المتحدة',
            'follow_heading_en' => 'Follow Us',
            'follow_heading_ar' => 'تابعنا',
            'social_instagram_url' => 'https://instagram.com/ferrogrooming',
            'social_tiktok_url' => 'https://tiktok.com/@ferrogrooming',
            'show_instagram' => true,
            'show_tiktok' => true,
            'show_facebook' => false,
            'show_snapchat' => false,
            'show_whatsapp' => false,
            'social_facebook_url' => null,
            'social_snapchat_url' => null,
            'social_whatsapp_url' => null,
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

    public function emailHeading(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = $locale === 'ar' ? 'email_heading_ar' : 'email_heading_en';
        $fallback = $locale === 'ar' ? 'البريد الإلكتروني' : 'Email';

        return (string) ($this->{$key} ?: $fallback);
    }

    public function liveChatHeading(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = $locale === 'ar' ? 'live_chat_heading_ar' : 'live_chat_heading_en';
        $fallback = $locale === 'ar' ? 'الدردشة المباشرة' : 'Live Chat';

        return (string) ($this->{$key} ?: $fallback);
    }

    public function liveChatText(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = $locale === 'ar' ? 'live_chat_text_ar' : 'live_chat_text_en';
        $fallback = $locale === 'ar' ? 'متاح ٧ أيام ٩ص–٩م' : 'Available 7 days, 9am–9pm';

        return (string) ($this->{$key} ?: $fallback);
    }

    public function hqHeading(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = $locale === 'ar' ? 'hq_heading_ar' : 'hq_heading_en';
        $fallback = $locale === 'ar' ? 'المقر الرئيسي' : 'Headquarters';

        return (string) ($this->{$key} ?: $fallback);
    }

    public function hqText(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = $locale === 'ar' ? 'hq_text_ar' : 'hq_text_en';
        $fallback = $locale === 'ar' ? 'دبي، الإمارات العربية المتحدة' : 'Dubai, United Arab Emirates';

        return (string) ($this->{$key} ?: $fallback);
    }

    public function followHeading(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $key = $locale === 'ar' ? 'follow_heading_ar' : 'follow_heading_en';
        $fallback = $locale === 'ar' ? 'تابعنا' : 'Follow Us';

        return (string) ($this->{$key} ?: $fallback);
    }

    public function shouldShowInstagram(): bool
    {
        return $this->show_instagram && filled($this->social_instagram_url);
    }

    public function shouldShowTiktok(): bool
    {
        return $this->show_tiktok && filled($this->social_tiktok_url);
    }

    public function shouldShowFacebook(): bool
    {
        return $this->show_facebook && filled($this->social_facebook_url);
    }

    public function shouldShowSnapchat(): bool
    {
        return $this->show_snapchat && filled($this->social_snapchat_url);
    }

    public function shouldShowWhatsapp(): bool
    {
        return $this->show_whatsapp && $this->whatsappHref() !== null;
    }

    /**
     * Full URL for WhatsApp chat (accepts https://wa.me/… or digits / +country number).
     */
    public function whatsappHref(): ?string
    {
        if (! filled($this->social_whatsapp_url)) {
            return null;
        }
        $raw = trim((string) $this->social_whatsapp_url);
        if (preg_match('#^https?://#i', $raw)) {
            return $raw;
        }
        $digits = preg_replace('/\D+/', '', $raw);

        return $digits !== '' ? 'https://wa.me/'.$digits : null;
    }

    /**
     * @return list<string>
     */
    public function sameAsLinks(): array
    {
        $links = [];
        if ($this->shouldShowInstagram()) {
            $links[] = trim((string) $this->social_instagram_url);
        }
        if ($this->shouldShowTiktok()) {
            $links[] = trim((string) $this->social_tiktok_url);
        }
        if ($this->shouldShowFacebook()) {
            $links[] = trim((string) $this->social_facebook_url);
        }
        if ($this->shouldShowSnapchat()) {
            $links[] = trim((string) $this->social_snapchat_url);
        }
        if ($this->shouldShowWhatsapp()) {
            $links[] = $this->whatsappHref();
        }

        return array_values(array_filter($links));
    }

    /**
     * @return array<int, array{icon: string, title: string, value: string}>
     */
    public function infoRows(?string $locale = null): array
    {
        $locale = $locale ?? app()->getLocale();

        return [
            [
                'icon' => 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
                'title' => $this->emailHeading($locale),
                'value' => $this->support_email,
            ],
            [
                'icon' => 'M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z',
                'title' => $this->liveChatHeading($locale),
                'value' => $this->liveChatText($locale),
            ],
            [
                'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z',
                'title' => $this->hqHeading($locale),
                'value' => $this->hqText($locale),
            ],
        ];
    }
}
