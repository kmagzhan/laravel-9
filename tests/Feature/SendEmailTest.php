<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class SendEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_is_dispached_when_pass_valid_data()
    {
        $user = User::factory()->create();

        Bus::fake();

        $response = $this
            ->post(
                "api/{$user->id}/send",
                [
                    'emails' => [
                        [
                            'email' => 'test@gmail.com',
                            'subject' => 'Hello world',
                            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
                        ]
                    ]
                ],
                [
                    'Accepts' => 'application/json'
                ]
            );

        $response->assertSuccessful();

        Bus::assertDispatched(\App\Jobs\SendEmail::class);
    }

    public function test_job_is_not_dispached_when_pass_invalid_data()
    {
        $user = User::factory()->create();

        Bus::fake();

        $response = $this->post("api/{$user->id}/send", ['emails' => []], ['Accept' => 'application/json']);

        $response->assertStatus(422);

        Bus::assertNotDispatched(\App\Jobs\SendEmail::class);
    }
}
