<?php

namespace App\Mail\User;

use App\Models\NewsletterCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterCampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly NewsletterCampaign $campaign,
        public readonly NewsletterSubscriber $subscriber,
    ) {}

    public function envelope(): Envelope
    {
        $locale = $this->subscriber->preferred_language;

        return new Envelope(
            subject: $this->campaign->subject($locale)
        );
    }

    public function content(): Content
    {
        $locale = $this->subscriber->preferred_language;
        $product = $this->campaign->product;

        return new Content(
            view: 'emails.user.newsletter-campaign',
            with: [
                'campaign' => $this->campaign,
                'subscriber' => $this->subscriber,
                'product' => $product,
                'locale' => $locale,
                'isRtl' => $locale === 'ar',
                'productName' => $product
                    ? ($product->getTranslation('name', $locale, false) ?: $product->name)
                    : null,
                'productUrl' => $product ? route('products.show', $product->slug) : null,
                'productImage' => $product && $product->featured_image
                    ? ferro_public_url($product->featured_image)
                    : null,
            ]
        );
    }
}
