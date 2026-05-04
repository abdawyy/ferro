<?php

namespace App\Jobs;

use App\Models\Lead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

/**
 * Placeholder for abandoned-cart recovery emails. Dispatched from LeadController
 * when a beacon reports a cart exit; extend with Mail sequences when ready.
 */
class SendAbandonedCartSequence implements ShouldQueue
{
    use Queueable;

    public function __construct(public Lead $lead) {}

    public function handle(): void
    {
        // Intentionally empty — wire Mail::queue / drip campaigns here when needed.
    }
}
