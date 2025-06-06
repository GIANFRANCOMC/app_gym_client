<?php

namespace App\Providers;

use App\Events\SubscriptionExpired;
use App\Listeners\LogSubscriptionEmail;
use App\Listeners\StoreSectionsInCache;
use Illuminate\Auth\Events\{Authenticated, Registered};
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Authenticated::class => [
            StoreSectionsInCache::class,
        ],
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SubscriptionExpired::class => [
            LogSubscriptionEmail::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
