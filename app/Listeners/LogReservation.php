<?php

namespace App\Listeners;

use App\Events\ReservationEvent;
use App\Models\ReservationLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogReservation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationEvent $event): void
    {
        $log = new ReservationLog();
        $log->reservation_id = $event->reservation->id;
        $log->user_id = $event->user->id;
        $log->created_at = now(); // Set created_at explicitly
        $log->save();
    }
}
