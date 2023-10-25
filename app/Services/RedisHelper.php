<?php

namespace App\Services;

use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Redis;

class RedisHelper implements RedisHelperInterface
{
    public function storeRecentMessage(mixed $id, string $messageSubject, string $toEmailAddress): void
    {
        Redis::set("sent_email:$id", json_encode([
            'subject' => $messageSubject,
            'recipient' => $toEmailAddress,
            'timestamp' => now()->timestamp,
        ]));
    }
}
