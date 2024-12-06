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
        \Log::info('Customer Object:', (array) $customer);
    }

    public function build()
    {
        return $this->subject('Invitation to join Testlink ISP Partner Program')
                    ->view('emails.customer-invitation');
    }
}
