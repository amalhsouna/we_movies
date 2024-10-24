<?php

namespace App\Service\Api;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractApiClient
{
    protected HttpClientInterface $client;
    protected LoggerInterface $logger;
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct(HttpClientInterface $client,
        LoggerInterface $logger,
        string $tmdbApiKey, string $tmdbApiUrl)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->apiKey = $tmdbApiKey; // TMDB API key from .env
        $this->baseUrl = $tmdbApiUrl;  // TMDB API base URL from .env
    }

    // Generic method to make a request to the API client
    protected function makeApiRequest(string $endpoint, array $query = [], string $method = 'GET'): array
    {
        // Add the API key to the query parameters
        $query['api_key'] = $this->apiKey;
        try {
            $response = $this->client->request($method, "{$this->baseUrl}/$endpoint", [
                'query' => $query,
                'headers' => [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'accept' => 'application/json',
                ],
            ]);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            $this->logger->alert($e->getResponse()->getContent(false));
        } catch (TransportExceptionInterface $e) {
            $this->logger->alert('Erreur appel api: '.$this->baseUrl / $endpoint.' '.$e->getMessage());
        }

        return $response->toArray();
    }
}
