<?php

namespace App\Providers;

use App\Events\ChangedOrderPaymentEvent;
use App\Events\CreatedOrderEvent;
use App\Events\ChangedOrderStatusEvent;
use App\Listeners\OrderCreatedListener;
use App\Listeners\OrderDataPaymentChangedListener;
use App\Listeners\OrderDataStatusChangedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CreatedOrderEvent::class => [
            OrderDataStatusChangedListener::class,
            OrderCreatedListener::class,
        ],
        ChangedOrderStatusEvent::class => [
            OrderDataStatusChangedListener::class,
        ],
        ChangedOrderPaymentEvent::class => [
            OrderDataPaymentChangedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
