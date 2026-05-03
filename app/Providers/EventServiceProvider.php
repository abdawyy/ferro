<?php

namespace App\Providers;

use App\Events\LeadRegistered;
use App\Events\OrderPlaced;
use App\Events\OrderShipped;
use App\Events\WaitlistReleased;
use App\Listeners\HandleLeadRegistered;
use App\Listeners\HandleOrderPlaced;
use App\Listeners\HandleOrderShipped;
use App\Listeners\HandleWaitlistReleased;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event-to-listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        OrderPlaced::class => [
            HandleOrderPlaced::class,
        ],
        LeadRegistered::class => [
            HandleLeadRegistered::class,
        ],
        OrderShipped::class => [
            HandleOrderShipped::class,
        ],
        WaitlistReleased::class => [
            HandleWaitlistReleased::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
