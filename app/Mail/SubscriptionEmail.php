<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer_name;
public $hostedPageUrl;
public $plan_name;
public $plan_price;

    public function __construct($emailData)
    {
        $this->customer_name = $emailData['customer_name'];
    $this->hostedPageUrl = $emailData['hostedPageUrl'];
    $this->plan_name = $emailData['plan']->plan_name;  
    $this->plan_price = $emailData['plan']->plan_price;  
    }

    public function build()
    {
        return $this->subject('Complete Your Subscription')
                    ->view('emails.create-subscription')
                    ->with([
                        'customer_name' => $this->customer_name,
                        'hostedPageUrl' => $this->hostedPageUrl,
                        'plan_name' => $this->plan_name,  
                        'plan_price' => $this->plan_price,  
                    ]);
    }
}
