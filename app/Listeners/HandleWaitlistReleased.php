<?php

namespace App\Listeners;

use App\Events\WaitlistReleased;
use App\Mail\User\WaitlistRelease;
use App\Models\Lead;
use App\Support\FerroMail;
use Illuminate\Support\Facades\Log;

class HandleWaitlistReleased
{
    /**
     * Notify waitlist leads when a product goes from coming_soon → active.
     */
    public function handle(WaitlistReleased $event): void
    {
        $product = $event->product;

        Lead::query()
            ->where('on_waitlist', true)
            ->where('waitlist_product_id', $product->id)
            ->whereNotNull('email')
            ->chunkById(100, function ($leads) use ($product): void {
                foreach ($leads as $lead) {
                    try {
                        FerroMail::to(
                            $lead->email,
                            new WaitlistRelease($lead, $product),
                            $lead->preferred_language ?? 'en'
                        );
                    } catch (\Throwable $e) {
                        Log::error('Waitlist release email failed', [
                            'lead_id' => $lead->id,
                            'product_id' => $product->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            });
    }
}
