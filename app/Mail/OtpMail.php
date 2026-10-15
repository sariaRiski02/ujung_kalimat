<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public string $otp_code;

    public function __construct(string $otp_code)
    {
        $this->otp_code = $otp_code;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode verifikasi kamu sudah siap! (Jangan dibagikan ke siapa pun ya)',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $otp_code = $this->otp_code;

        return new Content(
            view: 'mail.otp',
            with: compact('otp_code')
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
