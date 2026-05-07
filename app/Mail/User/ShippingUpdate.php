<?php

namespace App\Mail\User;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ShippingUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Order $order) {}

    public function envelope(): Envelope
    {
        $subject = $this->order->language === 'ar'
            ? 'طلبك في الطريق إليك — فيرو'
            : 'Your Order is on the Way — FERRO';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.shipping-update',
            with: [
                'order'  => $this->order,
                'locale' => $this->order->language,
                'isRtl'  => $this->order->language === 'ar',
                'trackingUrl' => URL::signedRoute('orders.track', ['order' => $this->order->id], now()->addYear()),
            ]
        );
    }
}
