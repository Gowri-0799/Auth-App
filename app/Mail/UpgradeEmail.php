<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UpgradeEmail extends Mailable
{
    use Queueable, SerializesModels;

public $customer_name;
public $hostedPageUrl;
public $plan_name;
public $plan_price;
public $plan_code;
public $email;
public $is_upgrade;

    public function __construct($emailData)
    {
    $this->customer_name = $emailData['customer_name'];
    $this->hostedPageUrl = $emailData['hostedPageUrl'];
    $this->plan_name = $emailData['plan']->plan_name;  
    $this->plan_price = $emailData['plan']->plan_price;  
    $this->plan_code = $emailData['plan']->plan_code; 
    $this->partneruser = $emailData['email']; 
    $this->is_upgrade = $emailData['is_upgrade'];
    }

    public function build()
    {
        return $this->subject('Upgrade -Payment link')
                    ->view('emails.upgrade-subscription')
                    ->with([
                        'customer_name' => $this->customer_name,
                        'hostedPageUrl' => $this->hostedPageUrl,
                        'plan_name' => $this->plan_name,  
                        'plan_price' => $this->plan_price,   
                        'plan_code' => $this->plan_code, 
                        'email'=>$this->email,
                        'is_upgrade' => $this->is_upgrade,
                    ]);
    }
}
