<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamPasswordReset extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $resetUrl, public string $teamName) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Jelszó visszaállítás - Sörpong Bajnokság');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.password-reset');
    }
}
