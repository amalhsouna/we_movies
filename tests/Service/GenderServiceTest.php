<?php

namespace App\tests\Service;

use App\Service\GenderService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GenderServiceTest extends TestCase
{
    private $client;
    private $logger;
    private $genderService;

    protected function setUp(): void
    {
        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->genderService = new GenderService($this->client, $this->logger, 'test_api_key', 'https://api.themoviedb.org/3');
    }

    public function testGetGenres(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('toArray')->willReturn(['genres' => [['id' => 1, 'name' => 'Action']]]);
        $this->client->method('request')->willReturn($responseMock);

        $genres = $this->genderService->getGenres();
        $this->assertCount(1, $genres);
        $this->assertEquals('Action', $genres[0]['name']);
    }
}
