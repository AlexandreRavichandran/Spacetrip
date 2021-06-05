<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallWeatherApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getWeatherData(string $cityName, int $date): array
    {
        $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/forecast?q=' . $cityName . '&appid=a91bd64fa6c92f13167c0425d033e611&lang=fr');
        $response =  $response->toArray();
        $index = array_search($date, $response);
        return $response['list'][$index]['weather'][0];
    }
}
