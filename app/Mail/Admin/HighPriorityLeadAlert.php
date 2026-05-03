<?php

namespace App\Mail\Admin;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HighPriorityLeadAlert extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Lead $lead) {}

    public function envelope(): Envelope
    {
        $priority = strtoupper($this->lead->priority);
        return new Envelope(
            subject: "[FERRO CRM] [{$priority}] New Lead: {$this->lead->email} via {$this->lead->source}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.high-priority-lead',
            with: ['lead' => $this->lead]
        );
    }
}
