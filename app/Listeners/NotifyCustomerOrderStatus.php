<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Mail\User\OrderStatusUpdate;
use App\Models\Order;
use App\Support\FerroMail;
use Illuminate\Support\Facades\Log;

class NotifyCustomerOrderStatus
{
    public function handle(OrderStatusChanged $event): void
    {
        if ($event->previousStatus === $event->newStatus) {
            return;
        }

        // Dedicated shipping email already covers carrier + tracking.
        if ($event->newStatus === Order::STATUS_SHIPPED) {
            return;
        }

        $order = $event->order->load(['items', 'user', 'lead']);

        $email = $order->customerFacingEmail();
        if (! $email) {
            return;
        }

        try {
            FerroMail::to($email, new OrderStatusUpdate($order, $event->previousStatus, $event->newStatus), $order->language);
        } catch (\Throwable $e) {
            Log::error('Order status notification email failed', [
                'order' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
