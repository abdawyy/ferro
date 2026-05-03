<?php

namespace App\Mail\Admin;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizSubmissionAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Lead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[FERRO Admin] New skin quiz submission: ' . $this->lead->email
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.quiz-submission',
            with: ['lead' => $this->lead]
        );
    }
}
