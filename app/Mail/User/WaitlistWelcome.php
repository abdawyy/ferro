<?php

namespace App\Mail\User;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaitlistWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Lead $lead) {}

    public function envelope(): Envelope
    {
        $subject = $this->lead->preferred_language === 'ar'
            ? 'أنت على القائمة — فيرو قادم'
            : "You're on the List — FERRO is Coming";

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.waitlist-welcome',
            with: [
                'lead'   => $this->lead,
                'locale' => $this->lead->preferred_language,
                'isRtl'  => $this->lead->preferred_language === 'ar',
            ]
        );
    }
}
