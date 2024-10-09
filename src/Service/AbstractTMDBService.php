<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractTMDBService
{
    protected HttpClientInterface $client;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(HttpClientInterface $client, string $tmdbApiKey, string $tmdbApiUrl)
    {
        $this->client = $client;
        $this->apiKey = $tmdbApiKey; // TMDB API key from .env
        $this->baseUrl = $tmdbApiUrl;  // TMDB API base URL from .env
    }

    // Generic method to make a request to the TMDB API
    protected function makeApiRequest(string $endpoint, array $query = [], string $method = 'GET'): array
    {
        // Add the API key to the query parameters
        $query['api_key'] = $this->apiKey;

        $response = $this->client->request($method, "{$this->baseUrl}/$endpoint", [
            'query' => $query,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'accept' => 'application/json',
            ],
        ]);

        return $response->toArray();
    }
}
