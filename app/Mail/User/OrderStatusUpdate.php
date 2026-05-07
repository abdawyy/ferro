<?php

namespace App\Mail\User;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly string $previousStatus,
        public readonly string $newStatus,
    ) {}

    public function envelope(): Envelope
    {
        $num = $this->order->order_number;
        $subject = $this->order->language === 'ar'
            ? "تحديث حالة طلبك — فيرو #{$num}"
            : "Your order status was updated — FERRO #{$num}";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.order-status-update',
            with: [
                'order' => $this->order,
                'locale' => $this->order->language,
                'isRtl' => $this->order->language === 'ar',
                'previousStatus' => $this->previousStatus,
                'newStatus' => $this->newStatus,
                'trackingUrl' => URL::signedRoute('orders.track', ['order' => $this->order->id], now()->addYear()),
            ]
        );
    }
}
