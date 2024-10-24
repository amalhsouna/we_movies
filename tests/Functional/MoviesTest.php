<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MoviesTest extends WebTestCase
{
    private const FILTER_URL = '/movies/filter';

    /**
     * @dataProvider genreProvider
     */
    public function testFilterMovies(int $genreId): void
    {
        $client = $this->getClient();
        $data = ['genres' => [$genreId]];

        $result = $client->request('POST', self::FILTER_URL, $data);

        $this->assertResponseIsSuccessful();
        $this->assertResponseIsJson($client->getResponse());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData);
        $this->assertNotEmpty($responseData);
    }

    public static function genreProvider(): array
    {
        return [
            [18],
            [35],
        ];
    }

    public function testSearchMoviesReturnsResults(): void
    {
        $client = $this->getClient();

        // Call /movies/search with query
        $client->request('GET', '/movies/search', ['query' => 'Inception']);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');

        // Decode of JSON
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($response);
        $this->assertNotEmpty($response, 'The response should contain movie results.');
    }

    private function getClient(): KernelBrowser
    {
        return static::createClient();
    }

    private function assertResponseIsJson(Response $response): void
    {
        $this->assertJson($response->getContent());
    }
}
