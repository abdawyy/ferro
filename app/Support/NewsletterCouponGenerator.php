<?php

namespace App\Support;

use App\Models\NewsletterSetting;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Str;

final class NewsletterCouponGenerator
{
    public static function generate(NewsletterSetting $settings): string
    {
        $prefix = Str::upper(Str::slug((string) $settings->coupon_prefix, ''));
        if ($prefix === '') {
            $prefix = 'FERRO';
        }

        do {
            $code = $prefix.'-'.Str::upper(Str::random(6));
        } while (NewsletterSubscriber::where('coupon_code', $code)->exists());

        return $code;
    }
}
