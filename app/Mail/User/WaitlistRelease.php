<?php

namespace App\Mail\User;

use App\Models\Lead;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent when a product transitions from 'coming_soon' to 'active'.
 * Dispatched to all waitlist leads for that product.
 */
class WaitlistRelease extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Lead $lead,
        public readonly Product $product,
    ) {}

    public function envelope(): Envelope
    {
        $productName = $this->product->getTranslation('name', $this->lead->preferred_language);
        $subject     = $this->lead->preferred_language === 'ar'
            ? "متاح الآن: {$productName} — فيرو"
            : "Now Available: {$productName} — FERRO";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.waitlist-release',
            with: [
                'lead'    => $this->lead,
                'product' => $this->product,
                'locale'  => $this->lead->preferred_language,
                'isRtl'   => $this->lead->preferred_language === 'ar',
            ]
        );
    }
}
