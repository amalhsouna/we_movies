<?php

namespace App\Service;

use App\Service\Api\AbstractApiClient;

class VideoService extends AbstractApiClient implements VideoServiceInterface
{
    public function getVideoMovie(int $idMovie): string
    {
        return $this->getVideoUrl($idMovie);
    }

    private function getVideoUrl(int $movieId): string
    {
        // Retrieve movie details to get video URL
        $videoData = $this->makeApiRequest("movie/$movieId/videos");

        return (count($videoData['results']) > 0) && $videoData['results'][0]['key'] ? 'https://www.youtube.com/embed/'.$videoData['results'][0]['key'] : ''; // Modifiez selon votre logique d'URL
    }
}
