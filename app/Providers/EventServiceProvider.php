<?php

namespace App\Providers;

use App\Events\TableCreated;
use App\Events\TableDeleted;
use App\Events\TableUpdated;
use App\Events\UserTableSubscribed;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\TableCreatedDiscordNotification;
use App\Listeners\TableDeletedDiscordNotification;
use App\Listeners\TableUpdatedDiscordNotification;
use App\Listeners\UserSubscriptionDiscordNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TableCreated::class => [
            TableCreatedDiscordNotification::class,
        ],
        TableUpdated::class => [
            TableUpdatedDiscordNotification::class,
        ],
        TableDeleted::class => [
            TableDeletedDiscordNotification::class,
        ],
        UserTableSubscribed::class => [
            UserSubscriptionDiscordNotification::class,
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
