<?php

namespace App\Mail;

use App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class EmailFromAPI extends Mailable
{
    use Queueable, SerializesModels;

    public Mail $mail;

    public User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mail $mail, User $user)
    {
        $this->mail = $mail;

        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $address = new Address($this->user->email, $this->user->name);

        return new Envelope(
            subject: $this->mail->subject,
            from: $address,
            replyTo: [$address],
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.mail-from-api',
            text: 'emails.mail-from-api-text'
        );
    }
}
