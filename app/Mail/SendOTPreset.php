<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOTPreset extends Mailable
{
    use Queueable, SerializesModels;
    public $otp;
    public $email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $email)
    {
        $this->otp = $otp;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('Email.passwordotp')->with([
            'otp' => $this->otp,
            'email' => $this->email
        ]);
    }
}