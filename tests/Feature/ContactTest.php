<?php

namespace Tests\Feature;

use App\Mail\ContactReceived;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private array $form = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'company' => 'Acme',
        'message' => 'We are hiring and would love to talk this week.',
    ];

    public function test_message_is_saved_and_both_emails_go_out(): void
    {
        Mail::fake();
        config(['mail.contact_to' => 'owner@example.com']);

        $this->postJson('/contact', $this->form)->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas(ContactMessage::class, ['email' => 'jane@example.com']);
        Mail::assertSent(NewContactMessage::class, fn ($m) => $m->hasTo('owner@example.com'));
        Mail::assertSent(ContactReceived::class, fn ($m) => $m->hasTo('jane@example.com'));
    }

    public function test_auto_reply_greets_by_first_name_and_echoes_the_message(): void
    {
        $mail = new ContactReceived(new ContactMessage($this->form));

        $mail->assertHasSubject('Got your message, Jane');
        $mail->assertSeeInHtml('Thanks, Jane.');
        $mail->assertSeeInHtml('would love to talk this week');
        $mail->assertSeeInText('It landed safely.');
    }

    public function test_honeypot_submissions_send_nothing(): void
    {
        Mail::fake();

        $this->postJson('/contact', $this->form + ['website' => 'spam.example'])->assertOk();

        $this->assertDatabaseCount(ContactMessage::class, 0);
        Mail::assertNothingSent();
    }
}
