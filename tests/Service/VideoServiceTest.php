<?php

namespace App\tests\Service;

use App\Service\VideoService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VideoServiceTest extends TestCase
{
    private $httpClientMock;
    private $loggerMock;
    private $videoService;

    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(HttpClientInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        // Instantiate the VideoService with mocked dependencies
        $this->videoService = new VideoService($this->httpClientMock, $this->loggerMock, 'your_tmdb_api_key', 'your_tmdb_api_url');
    }

    public function testGetVideoMovie()
    {
        // Mock the response for the API request
        $mockResponse = [
            'results' => [
                ['key' => 'dQw4w9WgXcQ'],
            ],
        ];
        $this->videoService = $this->getMockBuilder(VideoService::class)
            ->setConstructorArgs([$this->httpClientMock, $this->loggerMock, 'your_tmdb_api_key', 'your_tmdb_api_url'])
            ->onlyMethods(['makeApiRequest'])
            ->getMock();

        $this->videoService->method('makeApiRequest')
            ->willReturn($mockResponse);

        $videoUrl = $this->videoService->getVideoMovie(1);

        // Assert the expected video URL is returned
        $this->assertEquals('https://www.youtube.com/embed/dQw4w9WgXcQ', $videoUrl);
    }

    public function testGetVideoMovieNoResults()
    {
        // Mock the response with no results
        $mockResponse = [
            'results' => [],
        ];

        $this->videoService = $this->getMockBuilder(VideoService::class)
            ->setConstructorArgs([$this->httpClientMock, $this->loggerMock, 'your_tmdb_api_key', 'your_tmdb_api_url'])
            ->onlyMethods(['makeApiRequest'])
            ->getMock();

        $this->videoService->method('makeApiRequest')
            ->willReturn($mockResponse);
        $videoUrl = $this->videoService->getVideoMovie(1);

        // Assert that an empty string is returned when there are no results
        $this->assertEquals('', $videoUrl);
    }
}
