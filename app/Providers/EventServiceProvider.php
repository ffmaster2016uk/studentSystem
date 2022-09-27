<?php

namespace App\Providers;

use App\Events\RecordCreated;
use App\Events\RecordUpdated;
use App\Listeners\RecordCreatedLog;
use App\Listeners\RecordUpdatedLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RecordCreated::class => [
            RecordCreatedLog::class
        ],
        RecordUpdated::class => [
            RecordUpdatedLog::class
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
}
