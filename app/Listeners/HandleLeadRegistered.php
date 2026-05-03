<?php

namespace App\Listeners;

use App\Events\LeadRegistered;
use App\Mail\Admin\HighPriorityLeadAlert;
use App\Mail\User\WaitlistWelcome;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class HandleLeadRegistered implements ShouldQueue
{
    public string $queue = 'notifications';

    public function handle(LeadRegistered $event): void
    {
        $lead = $event->lead;

        // 1. Welcome email to lead (localized) — quiz uses its own on-site experience, not waitlist copy
        if ($lead->source !== \App\Models\Lead::SOURCE_QUIZ) {
            Mail::to($lead->email)
                ->locale($lead->preferred_language)
                ->queue(new WaitlistWelcome($lead));
        }

        // 2. Admin alert only for high/VIP priority leads
        if (in_array($lead->priority, [\App\Models\Lead::PRIORITY_HIGH, \App\Models\Lead::PRIORITY_VIP])) {
            Mail::to(config('ferro.admin_email'))
                ->queue((new HighPriorityLeadAlert($lead))->onQueue('high'));
        }
    }
}
