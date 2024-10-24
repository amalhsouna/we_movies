<?php

namespace App\Service;

use App\Service\Api\AbstractApiClient as ApiAbstractApiClient;

class GenderService extends ApiAbstractApiClient implements GenderServiceInterface
{
    public function getGenres(): array
    {
        return $this->makeApiRequest('genre/movie/list', ['language' => 'en'])['genres'];
    }
}
