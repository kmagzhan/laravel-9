<?php

namespace App;

use Illuminate\Support\Str;

class Mail
{
    /**
     * @var string Recipient of the email
     */
    public string $toEmail;

    /**
     * @var string Subject of the email
     */
    public string $subject;

    /**
     * @var string Content of the email
     */
    public string $body;

    public function __construct(
        string $toEmail,
        string $subject,
        string $body
    ) {
        $this->toEmail = $toEmail;
        $this->subject = $subject;
        $this->body = $body;
    }
}
