<?php

namespace App\Services;

use App\Mail\User\NewsletterCampaignMail;
use App\Mail\User\NewsletterWelcomeCoupon;
use App\Models\Lead;
use App\Models\NewsletterCampaign;
use App\Models\NewsletterSetting;
use App\Models\NewsletterSubscriber;
use App\Support\FerroMail;
use App\Support\NewsletterCouponGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterService
{
    /**
     * @return array{subscriber: NewsletterSubscriber, already_active: bool}
     */
    public function subscribe(string $email, ?string $locale, ?string $ipAddress): array
    {
        $settings = NewsletterSetting::current();
        $locale = in_array($locale, ['en', 'ar'], true) ? $locale : 'en';

        $existing = NewsletterSubscriber::where('email', $email)->first();
        if ($existing !== null && $existing->isActive()) {
            return ['subscriber' => $existing, 'already_active' => true];
        }

        $couponCode = NewsletterCouponGenerator::generate($settings);
        $expiresAt = $settings->coupon_valid_days
            ? now()->addDays((int) $settings->coupon_valid_days)
            : null;

        if ($existing !== null) {
            $existing->update([
                'preferred_language' => $locale,
                'coupon_code' => $couponCode,
                'discount_percent' => (int) $settings->discount_percent,
                'coupon_expires_at' => $expiresAt,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
                'ip_address' => $ipAddress,
            ]);
            $subscriber = $existing->fresh();
        } else {
            $subscriber = NewsletterSubscriber::create([
                'email' => $email,
                'preferred_language' => $locale,
                'coupon_code' => $couponCode,
                'discount_percent' => (int) $settings->discount_percent,
                'coupon_expires_at' => $expiresAt,
                'subscribed_at' => now(),
                'ip_address' => $ipAddress,
            ]);
        }

        Lead::updateOrCreate(
            ['email' => $email],
            [
                'source' => Lead::SOURCE_NEWSLETTER,
                'status' => Lead::STATUS_ENGAGED,
                'preferred_language' => $locale,
                'marketing_consent' => true,
                'consented_at' => now(),
                'ip_address' => $ipAddress,
            ]
        );

        try {
            FerroMail::to(
                $subscriber->email,
                new NewsletterWelcomeCoupon($subscriber, $settings),
                $subscriber->preferred_language
            );
        } catch (\Throwable $e) {
            Log::error('Newsletter welcome email failed', [
                'subscriber_id' => $subscriber->id,
                'error' => $e->getMessage(),
            ]);
        }

        return ['subscriber' => $subscriber, 'already_active' => false];
    }

    public function unsubscribe(string $email): bool
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        if ($subscriber === null || ! $subscriber->isActive()) {
            return false;
        }

        $subscriber->update(['unsubscribed_at' => now()]);

        Lead::where('email', $email)->update(['status' => Lead::STATUS_UNSUBSCRIBED]);

        return true;
    }

    public function verifyUnsubscribeToken(string $email, string $token): bool
    {
        $expected = hash('sha256', $email.config('app.key'));

        return hash_equals($expected, $token);
    }

    public function sendCampaign(NewsletterCampaign $campaign): int
    {
        if ($campaign->isSent()) {
            return $campaign->sent_count;
        }

        $campaign->load(['product', 'subscribers']);

        $recipients = $campaign->send_to === NewsletterCampaign::SEND_TO_SELECTED
            ? $campaign->subscribers()->active()->get()
            : NewsletterSubscriber::active()->get();

        $sent = 0;

        foreach ($recipients as $subscriber) {
            try {
                FerroMail::to(
                    $subscriber->email,
                    new NewsletterCampaignMail($campaign, $subscriber),
                    $subscriber->preferred_language
                );

                $campaign->subscribers()->syncWithoutDetaching([
                    $subscriber->id => ['sent_at' => now(), 'failed' => false],
                ]);

                $sent++;
            } catch (\Throwable $e) {
                Log::error('Newsletter campaign email failed', [
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $subscriber->id,
                    'error' => $e->getMessage(),
                ]);

                $campaign->subscribers()->syncWithoutDetaching([
                    $subscriber->id => ['failed' => true],
                ]);
            }
        }

        $campaign->update([
            'status' => NewsletterCampaign::STATUS_SENT,
            'sent_count' => $sent,
            'sent_at' => now(),
        ]);

        return $sent;
    }

    public function popupPayload(?string $locale = null): array
    {
        $settings = NewsletterSetting::current();
        $locale = $locale ?? app()->getLocale();

        return [
            'enabled' => (bool) $settings->is_enabled,
            'delay_seconds' => (int) $settings->delay_seconds,
            'title' => $settings->title($locale),
            'message' => $settings->message($locale),
            'button_text' => $settings->buttonText($locale),
            'success_message' => $settings->successMessage($locale),
            'discount_percent' => (int) $settings->discount_percent,
            'subscribe_url' => route('newsletter.subscribe'),
        ];
    }
}
