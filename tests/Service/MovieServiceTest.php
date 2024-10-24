<?php

namespace App\tests\Service;

use App\Service\MovieService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MovieServiceTest extends TestCase
{
    private $httpClientMock;
    private $loggerMock;
    private $movieService;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        // / Instantiate the movie service with mocked dependencies
        $this->movieService = new MovieService($this->httpClientMock, $this->loggerMock, 'your_tmdb_api_key', 'your_tmdb_api_url');
    }

    public function testGetMoviesByGenre()
    {
        // Simuler la réponse de l'API
        $mockResponse = [
            'results' => [
                ['id' => 1, 'title' => 'Movie 1'],
                ['id' => 2, 'title' => 'Movie 2'],
            ],
            'total_pages' => 5,
            'total_results' => 100,
        ];

        $this->movieService = $this->getMockBuilder(MovieService::class)
            ->setConstructorArgs([$this->httpClientMock, $this->loggerMock, 'your_tmdb_api_key', 'your_tmdb_api_url'])
            ->onlyMethods(['makeApiRequest', 'getVideoUrl'])
            ->getMock();

        $this->movieService->method('makeApiRequest')
            ->willReturn($mockResponse);

        $this->movieService->method('getVideoUrl')
            ->willReturn('http://example.com/video');

        $result = $this->movieService->getMoviesByGenre([1, 2], 1);

        $this->assertIsArray($result);
        $this->assertCount(2, $result['movies']); // Verifie the name of movie
        $this->assertEquals(5, $result['total_pages']);
        $this->assertEquals(100, $result['total_results']);
        $this->assertEquals('http://example.com/video', $result['movies'][0]['video_url']);
    }

    public function testGetMoviesByGenreWithEmptyGenres()
    {
        $result = $this->movieService->getMoviesByGenre([], 1);

        // Verify that the result is empty or has appropriate behavior
        $this->assertIsArray($result);
        $this->assertEmpty($result['movies']);
    }
}
