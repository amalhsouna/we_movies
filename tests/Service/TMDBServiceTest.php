<?php

namespace App\tests\Service;

use App\Service\TMDBService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TMDBServiceTest extends TestCase
{
    private $client;
    private $tmdbService;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->tmdbService = new TMDBService($this->client, 'test_api_key', 'https://api.themoviedb.org/3');
    }

    public function testGetGenres(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn(['genres' => [['id' => 1, 'name' => 'Action']]]);
        $this->client->method('request')->willReturn($responseMock);

        $genres = $this->tmdbService->getGenres();
        $this->assertCount(1, $genres);
        $this->assertEquals('Action', $genres[0]['name']);
    }

    public function testGetMoviesByGenre(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn(['results' => [['title' => 'Movie 1'], ['title' => 'Movie 2']]]);
        $this->client->method('request')->willReturn($responseMock);

        $movies = $this->tmdbService->getMoviesByGenre(1);
        $this->assertCount(2, $movies);
        $this->assertEquals('Movie 1', $movies[0]['title']);
    }
}
