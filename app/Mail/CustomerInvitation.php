<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CustomerInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $password;
    public $loginUrl;

    public function __construct($customer, $password, $loginUrl)
    {
        $this->customer = $customer;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    public function build()
    {
        return $this->subject('Welcome to TestLink Technologies')
                    ->view('emails.customer-invitation');
    }
}
