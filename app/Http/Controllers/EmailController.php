<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendEmailsRequest;
use App\Jobs\SendEmail;
use App\Models\User;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use App\Mail;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    const ELASTICSEARCH_INDEX = 'sent_mails';

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

        return [
            'message' => $user->getGreeting('Hi')
        ];
    }

    private function getElasticSearchClient(): Client
    {
        $hosts = config('services.elasticsearch.hosts');

        $hosts = explode(',', $hosts);

        return ClientBuilder::create()
            ->setHosts($hosts)
            ->build();
    }

    public function list(Request $request)
    {
        $request->validate([
            'page' => 'nullable|numeric',
            'search' => 'nullable|string'
        ]);

        $elasticsearchClient = $this->getElasticSearchClient();

        $perPage = 10;
        $page = $request->page ?? 1;
        $skip = ($page - 1) * $perPage;
        $search = $request->search ?? '';

        $params = [
            'index' => self::ELASTICSEARCH_INDEX,
            'body'  => [
                'sort' => [
                    'created_at' => [
                        'order' => 'desc'
                    ]
                ],
                'from' => $skip,
                'size' => $perPage
            ]
        ];

        if ($search) {
            $params['body']['query'] = [
                'wildcard' => [
                    "subject" => $search,
                ]
            ];
        }

        $results = $elasticsearchClient->search($params);

        $items = array_column($results['hits']['hits'], '_source');

        $total = $results['hits']['total']['value'];

        return [
            'items' => $items,
            'total' => $total
        ];
    }
}
