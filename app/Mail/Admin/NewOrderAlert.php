<?php

namespace App\Mail\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[FERRO Admin] New Order #{$this->order->order_number} — {$this->order->formatted_total}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-order-alert',
            with: ['order' => $this->order]
        );
    }
}
