<?php

namespace App\Jobs;

use App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \Illuminate\Support\Facades\Mail as MailSender;
use App\Mail\EmailFromAPI;
class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;

    private Mail $mail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mail $mail, User $user)
    {
        $this->mail = $mail;

        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
