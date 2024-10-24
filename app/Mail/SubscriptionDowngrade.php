<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionDowngrade extends Mailable
{
    use Queueable, SerializesModels;
    public $customerName;
    public $planId;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        $this->customerName = $customerName;
        $this->planId = $planId;
    }
    public function build()
    {
        return $this
            ->subject('Your Subscription has been Downgraded')
            ->view('emails.subscription_downgrade'); // Create this view in resources/views/emails
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Downgrade',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
