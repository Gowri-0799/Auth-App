<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $resetUrl;

    public function __construct($userName, $resetUrl)
    {
        $this->userName = $userName;
        $this->resetUrl = $resetUrl;
     
    }

    public function build()
    {
        return $this->subject('Reset Your Password')
                    ->view('emails.passwordreset'); // Point to the email view file
    }
}
