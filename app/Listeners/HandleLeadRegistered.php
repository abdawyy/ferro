<?php

namespace App\Listeners;

use App\Events\LeadRegistered;
use App\Mail\Admin\HighPriorityLeadAlert;
use App\Mail\User\WaitlistWelcome;
use App\Models\Lead;
use App\Support\FerroMail;
use Illuminate\Support\Facades\Log;

/**
 * Runs synchronously with the HTTP request (no ShouldQueue) so welcome mail works
 * without a queue worker when FERRO_MAIL_QUEUE / ferro.mail.queue is false.
 */
class HandleLeadRegistered
{
    public function handle(LeadRegistered $event): void
    {
        $lead = $event->lead;

        if ($lead->source !== Lead::SOURCE_QUIZ) {
            try {
                FerroMail::to($lead->email, new WaitlistWelcome($lead), $lead->preferred_language);
            } catch (\Throwable $e) {
                Log::error('Waitlist welcome email failed', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if (in_array($lead->priority, [Lead::PRIORITY_HIGH, Lead::PRIORITY_VIP], true)) {
            try {
                FerroMail::toQueuedOn(
                    config('ferro.admin_email'),
                    new HighPriorityLeadAlert($lead),
                    'high'
                );
            } catch (\Throwable $e) {
                Log::error('High-priority lead admin email failed', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}
