<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     * 
     * 
     */

    public $verificationUrl;
    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }


    public function build()
    {
        return $this->view('verify-email')
                    ->with(['verificationUrl' => $this->verificationUrl]);
    }
}
