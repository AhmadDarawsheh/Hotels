<?php

namespace App\Listeners;

use App\Events\ReservationEvent;
use App\Mail\ReservationEmailForUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendUserReservationEmail
{

    public function handle(ReservationEvent $event): void
    {
        Mail::to($event->user->email)->send(new ReservationEmailForUser($event->reservation,$event->user));
    }
}
