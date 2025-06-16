<?php

namespace App\Providers;

use App\Events\VideoAdded;
use App\Listeners\SendMessage;
use App\Events\MessageGenerated;
use Illuminate\Auth\Events\Login;
use App\Listeners\Videos\GetMetaInfo;
use Illuminate\Support\Facades\Event;
use App\Listeners\Videos\GetSubtitles;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UpdateUserLastLoginDate;
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
        // VideoAdded::class => [
        //     GetMetaInfo::class,
        //     GetSubtitles::class
        // ],
        MessageGenerated::class => [
            SendMessage::class
        ],
        Login::class => [
            UpdateUserLastLoginDate::class
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
