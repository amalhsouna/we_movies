<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class MoviesTest extends WebTestCase
{
    private const BASE_URL = '/';
    private const FILTER_URL = '/movies/filter';
    private const SEARCH_URL = '/movies/search?query=';

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
            [35]
        ];
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
