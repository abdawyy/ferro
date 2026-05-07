<?php

namespace App\Listeners;

use App\Events\OrderShipped;
use App\Mail\User\ShippingUpdate;
use App\Support\FerroMail;
use Illuminate\Support\Facades\Log;

class HandleOrderShipped
{
    public function handle(OrderShipped $event): void
    {
        $order = $event->order->load(['items', 'user', 'lead']);

        $recipientEmail = $order->customerFacingEmail();

        if (! $recipientEmail) {
            return;
        }

        try {
            FerroMail::to($recipientEmail, new ShippingUpdate($order), $order->language);
        } catch (\Throwable $e) {
            Log::error('Shipping notification email failed', [
                'order' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
