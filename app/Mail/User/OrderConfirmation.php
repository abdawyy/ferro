<?php

namespace App\Mail\User;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

/**
 * Branded transactional email: order confirmation + invoice PDF attachment.
 * Rendered in the customer's preferred language (en|ar).
 */
class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly ?string $invoicePdfPath = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->order->language === 'ar'
            ? 'تأكيد طلبك — فيرو #' . $this->order->order_number
            : 'Your Order is Confirmed — FERRO #' . $this->order->order_number;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.order-confirmation',
            with: [
                'order'   => $this->order,
                'locale'  => $this->order->language,
                'isRtl'   => $this->order->language === 'ar',
                'trackingUrl' => URL::signedRoute('orders.track', ['order' => $this->order->id], now()->addYear()),
            ]
        );
    }

    public function attachments(): array
    {
        if (! $this->invoicePdfPath || ! Storage::disk('local')->exists($this->invoicePdfPath)) {
            return [];
        }

        return [
            Attachment::fromStorageDisk('local', $this->invoicePdfPath)
                ->as('FERRO_Invoice_' . $this->order->invoice_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
