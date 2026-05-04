<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Exceptions\InvoiceArithmeticException;
use App\Mail\Admin\NewOrderAlert;
use App\Mail\User\OrderConfirmation;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Triggered on OrderPlaced event:
 *  1. Generate PDF invoice (with arithmetic validation)
 *  2. Send branded order confirmation to customer (localized)
 *  3. Send real-time admin alert
 *
 * Runs synchronously so confirmations work without a queue worker. For high
 * volume, switch to queued sends via config below.
 */
class HandleOrderPlaced
{
    public function __construct(private InvoiceService $invoiceService) {}

    public function handle(OrderPlaced $event): void
    {
        $order = $event->order->load(['items', 'user', 'lead']);

        // Step 1 — Generate invoice PDF (throws on arithmetic error)
        try {
            $pdfPath = $this->invoiceService->generate($order);
        } catch (InvoiceArithmeticException $e) {
            // Log critical error — do not silently fail
            Log::critical('Invoice arithmetic error', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
            // Still send order confirmation but without invoice attachment
            $pdfPath = null;
        }

        // Step 2 — Customer confirmation (localized)
        $recipientEmail = $order->user?->email
            ?? $order->lead?->email
            ?? $order->billing_address['email'] ?? null;

        if ($recipientEmail) {
            try {
                $mailable = new OrderConfirmation($order, $pdfPath);
                if (config('ferro.mail.queue', false) === true) {
                    Mail::to($recipientEmail)->locale($order->language)->queue($mailable);
                } else {
                    Mail::to($recipientEmail)->locale($order->language)->send($mailable);
                }
            } catch (\Throwable $e) {
                Log::error('Order confirmation email failed', [
                    'order' => $order->order_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        try {
            $adminMail = new NewOrderAlert($order);
            if (config('ferro.mail.queue', false) === true) {
                Mail::to(config('ferro.admin_email'))->queue($adminMail);
            } else {
                Mail::to(config('ferro.admin_email'))->send($adminMail);
            }
        } catch (\Throwable $e) {
            Log::error('Admin new-order email failed', [
                'order' => $order->order_number,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
