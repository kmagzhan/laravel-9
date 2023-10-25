<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailsRequest;
use App\Jobs\SendEmail;
use App\Models\User;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use App\Mail;

class EmailController extends Controller
{
    public function send(SendEmailsRequest $request, User $user)
    {
        $validated = $request->validated();

        foreach ($validated['emails'] as $item) {
            $mail = new Mail($item['email'], $item['subject'], $item['body']);

            /** @var ElasticsearchHelperInterface $elasticsearchHelper */
            $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);

            $id = $elasticsearchHelper->storeEmail(
                $mail->body,
                $mail->subject,
                $mail->toEmail
            );

            if (! $id) {
                //  TODO process unsaved mails
                continue;
            }

            /** @var RedisHelperInterface $redisHelper */
            $redisHelper = app()->make(RedisHelperInterface::class);

            $redisHelper->storeRecentMessage($id, $mail->subject, $mail->toEmail);

            SendEmail::dispatch($mail, $id, $user);
        }

        return $user->getGreeting(true, 'Hi');
    }

    //  TODO - BONUS: implement list method
    public function list()
    {

    }
}
