<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Support\PortfolioContent;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewContactMessage extends Mailable
{
    public function __construct(public ContactMessage $contact)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contact->email, $this->contact->name)],
            subject: 'Portfolio contact: '.$this->contact->name,
        );
    }

    public function content(): Content
    {
        $profile = PortfolioContent::get()['profile'];

        return new Content(
            html: 'mail.contact-message',
            text: 'mail.contact-message-text',
            with: [
                'profile' => $profile,
                'inboxUrl' => url('/admin/inbox/'.$this->contact->id),
                'receivedAt' => ($this->contact->created_at ?? now())->timezone($profile['timezone'] ?? config('app.timezone'))->format('j M Y, H:i T'),
            ],
        );
    }
}
