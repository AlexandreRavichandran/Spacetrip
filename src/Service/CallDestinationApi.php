<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallDestinationApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getDestinationData($name): array
    {
        $response = $this->client->request('GET', 'https://api.le-systeme-solaire.net/rest/bodies/?filter=name,eq,' . $name);
        $response =  $response->toArray();
        return $response['bodies'][0];
    }
}
