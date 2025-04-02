<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SaleMail extends Mailable {

    use Queueable, SerializesModels;

    public $mail = null;

    /**
     * Create a new message instance.
     */
    public function __construct($mail) {

        $this->mail = $mail;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope {

        return new Envelope(
            subject: $this->mail->subject
        );

    }

    /**
     * Get the message content definition.
     */
    public function content(): Content {

        return new Content(
            view: 'mails/saleMail',
            with: ['mail' => $this->mail]
        );

    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array {

        return [];

    }

}
