<?php

namespace App\Mail\Admin;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InventoryAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Product $product,
        public readonly string $alertType, // 'low_stock' | 'out_of_stock'
    ) {}

    public function envelope(): Envelope
    {
        $label = $this->alertType === 'out_of_stock' ? '🚨 OUT OF STOCK' : '⚠️ LOW STOCK';
        return new Envelope(
            subject: "[FERRO Inventory] {$label}: {$this->product->name} (SKU: {$this->product->sku})"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.inventory-alert',
            with: [
                'product'   => $this->product,
                'alertType' => $this->alertType,
            ]
        );
    }
}
