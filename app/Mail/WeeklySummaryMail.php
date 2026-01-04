<?php

namespace App\Mail;

use App\Core\Users\Models\Professional;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklySummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Professional $professional,
        public array $stats,
        public array $upcomingAppointments,
        public \Carbon\Carbon $weekStart,
        public \Carbon\Carbon $weekEnd,
        public string $weeklyTip
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📊 Tu resumen semanal - Clinora',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.summaries.weekly',
            with: [
                'professional' => $this->professional,
                'stats' => $this->stats,
                'upcomingAppointments' => $this->upcomingAppointments,
                'weekStart' => $this->weekStart,
                'weekEnd' => $this->weekEnd,
                'weeklyTip' => $this->weeklyTip,
            ],
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
