<?php

namespace App\Mail\User;

use App\Models\NewsletterSetting;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcomeCoupon extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly NewsletterSubscriber $subscriber,
        public readonly NewsletterSetting $settings,
    ) {}

    public function envelope(): Envelope
    {
        $locale = $this->subscriber->preferred_language;
        $subject = $locale === 'ar'
            ? 'كود الخصم الخاص بك — FERRO'
            : 'Your FERRO Discount Coupon';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $locale = $this->subscriber->preferred_language;

        return new Content(
            view: 'emails.user.newsletter-welcome-coupon',
            with: [
                'subscriber' => $this->subscriber,
                'settings' => $this->settings,
                'locale' => $locale,
                'isRtl' => $locale === 'ar',
            ]
        );
    }
}
