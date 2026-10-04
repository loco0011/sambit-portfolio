<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Support\PortfolioContent;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Auto-reply to whoever used the contact form: confirms it arrived and says when to expect an answer. */
class ContactReceived extends Mailable
{
    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Got your message, '.$this->firstName());
    }

    public function content(): Content
    {
        return new Content(
            html: 'mail.contact-received',
            text: 'mail.contact-received-text',
            with: [
                'firstName' => $this->firstName(),
                'profile' => PortfolioContent::get()['profile'],
            ],
        );
    }

    private function firstName(): string
    {
        return strtok(trim($this->contact->name), ' ') ?: 'there';
    }
}
