<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationEmailForSuperAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $reservation;
    public $employee;
    public $user;
    public function __construct(Reservation $reservation,User $employee,User $user)
    {
        $this->reservation = $reservation;
        $this->employee = $employee;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->markdown('emails.reservation.admin')
            ->with([
                'reservation' => $this->reservation,
                'employee' => $this->employee,
                'user' => $this->user,
            ])
            ->subject('New Reservation Alert');
    }
}
