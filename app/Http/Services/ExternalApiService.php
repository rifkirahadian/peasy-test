<?php

namespace App\Http\Services;

use Exception;
use GuzzleHttp\Client;

class ExternalApiService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getRandomUser()
    {
        try {
            $response = $this->client->request('GET', 'https://randomuser.me/api/?results=20');

            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody(), true);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
