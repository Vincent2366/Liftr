<?php

namespace App\Mail;

use App\Models\SubdomainRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubdomainApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $subdomainRequest;
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
            subject: 'Your Subdomain Has Been Approved - Email Verification Required',
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
