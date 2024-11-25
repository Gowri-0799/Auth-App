<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $password;
    public $loginUrl;

    public function __construct($admin, $password, $loginUrl)
    {
        $this->admin = $admin;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Invitation to join Admin Panel')
                    ->view('emails.admin-invitation');
    }
}
