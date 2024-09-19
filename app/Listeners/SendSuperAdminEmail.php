<?php

namespace App\Listeners;

use App\Events\ReservationEvent;
use App\Mail\ReservationEmailForSuperAdmin;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSuperAdminEmail
{



    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */





    public function handle(ReservationEvent $event): void
    {
        $email = env('SUPER_ADMIN_EMAIL');
        $employee = User::where('email', $email)->first();

        Log::info('Fetched employee for super admin email', ['employee' => $employee]);


        if ($employee) {
            Mail::to($employee->email)->send(new ReservationEmailForSuperAdmin($event->reservation, $employee,$event->user));
        } else {
            Log::error("Super admin not found: " . $email);
        }
    }
}
