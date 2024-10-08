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
        $this->apiKey = $tmdbApiKey;
        $this->baseUrl = $tmdbApiUrl;  // TMDB API base URL from .env
    }

    // Generic method to make a request to the TMDB API
    protected function makeApiRequest(string $endpoint, array $query = [], string $method = 'GET'): array
    {
        // Add the API key to the query parameters
        $query['api_key'] = $this->apiKey;

        // Perform the API request with the given method and endpoint
        $response = $this->client->request($method, "{$this->baseUrl}/$endpoint", [
            'query' => $query,
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJiYWNiM2YzODlmNWYxZTRkOTFjMDVhNDRjZjE0ZTAyOSIsIm5iZiI6MTcyODMzMTc4OS4wMTA4NDksInN1YiI6IjY3MDQwMTI2M2Q3YjNjNmMwNzc5NjhhNyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.OwUjB-7gq7NSXswLD1DRchCybbyZduq2ziB5Qlmo1u8',
                'accept' => 'application/json',
            ],
        ]);

        // Return the response as an array
        return $response->toArray();
    }
}
