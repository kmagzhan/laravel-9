<?php

namespace App\Services;

use App\Utilities\Contracts\ElasticsearchHelperInterface;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class ElasticsearchHelper implements ElasticsearchHelperInterface
{
    private Client $elasticsearchClient;

    public function __construct()
    {
        $hosts = config('services.elasticsearch.hosts');

        $hosts = explode(',', $hosts);

        $this->elasticsearchClient = ClientBuilder::create()->setHosts($hosts)->build();
    }

    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
        try {
            $params = [
                'index' => 'sent_mails',
                'body' => [
                    'subject' => $messageSubject,
                    'body' => $messageBody,
                    'to' => $toEmailAddress,
                    'created_at' => now()->timestamp
                ],
            ];

            $result = $this->elasticsearchClient->index($params);

            return $result['_id'];
        } catch (\Exception $e) {
            //  TODO process ElasticSearch exceptions
            Log::error('Error in Elasticsearch: ' . $e->getMessage());
            return null;
        }
    }
}
