<?php

namespace App\Mail;

use App\Models\SubdomainRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class SubdomainApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The subdomain request instance.
     *
     * @var \App\Models\SubdomainRequest
     */
    public $subdomainRequest;

    /**
     * The auto-generated password.
     *
     * @var string
     */
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(SubdomainRequest $subdomainRequest, string $password)
    {
        $this->subdomainRequest = $subdomainRequest;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Subdomain Request Has Been Approved',
            from: new Address(config('mail.from.address'), config('mail.from.name')),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.subdomain-approved',
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
