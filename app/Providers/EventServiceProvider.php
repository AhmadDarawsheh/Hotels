<?php

namespace App\Providers;

use App\Events\ReservationEvent;
use App\Listeners\LogReservation;
use App\Listeners\SendSuperAdminEmail;
use App\Listeners\SendUserReservationEmail;
use App\Mail\ReservationEmailForSuperAdmin;
use App\Mail\ReservationEmailForUser;
use Illuminate\Auth\Events\Registered;
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
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        ReservationEvent::class => [
            SendUserReservationEmail::class,
            SendSuperAdminEmail::class,
            LogReservation::class

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
