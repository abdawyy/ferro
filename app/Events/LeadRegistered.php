<?php

namespace App\Events;

use App\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Lead $lead) {}
}
