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

    // Méthode générique pour effectuer une requête GET à l'API TMDB
    protected function makeApiRequest(string $endpoint, array $query = []): array
    {
        $query['api_key'] = $this->apiKey; // Ajoute la clé API à chaque requête
        $response = $this->client->request('GET', "{$this->baseUrl}/$endpoint", [
            'query' => $query,
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'accept' => 'application/json',
            ],
        ]);

        return $response->toArray();
    }
}
