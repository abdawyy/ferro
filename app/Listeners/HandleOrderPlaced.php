<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Mail\Admin\NewOrderAlert;
use App\Mail\User\OrderConfirmation;
use App\Services\InvoiceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

/**
 * Triggered on OrderPlaced event:
 *  1. Generate PDF invoice (with arithmetic validation)
 *  2. Send branded order confirmation to customer (localized)
 *  3. Send real-time admin alert
 */
class HandleOrderPlaced implements ShouldQueue
{
    public string $queue = 'notifications';

    public function __construct(private InvoiceService $invoiceService) {}

    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load(['items', 'user', 'lead']);

        // Step 1 — Generate invoice PDF (throws on arithmetic error)
        try {
            $pdfPath = $this->invoiceService->generate($order);
        } catch (\App\Exceptions\InvoiceArithmeticException $e) {
            // Log critical error — do not silently fail
            \Log::critical('Invoice arithmetic error', [
                'order_number' => $order->order_number,
                'error'        => $e->getMessage(),
            ]);
            // Still send order confirmation but without invoice attachment
            $pdfPath = null;
        }

        // Step 2 — Customer confirmation (localized)
        $recipientEmail = $order->user?->email
            ?? $order->lead?->email
            ?? $order->billing_address['email'] ?? null;

        if ($recipientEmail) {
            Mail::to($recipientEmail)
                ->locale($order->language)
                ->queue(new OrderConfirmation($order, $pdfPath));
        }

        // Step 3 — Admin alert (real-time, high priority queue)
        Mail::to(config('ferro.admin_email'))
            ->queue((new NewOrderAlert($order))->onQueue('high'));
    }
}
