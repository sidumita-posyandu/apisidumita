<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailValidasi extends Mailable
{
    use Queueable, SerializesModels;
    public $data_user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_user)
    {
        $this->data_user = $data_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Validasi Akun Sidumita")->markdown('Email.emailvalidasi')->with([
            'data' => $this->data_user,
        ]);
    }
}
