<?php

namespace App\Service;

class TMDBService extends AbstractTMDBService
{
    public function getGenres(): array
    {
        return $this->makeApiRequest('genre/movie/list', ['language' => 'en'])['genres'];
    }

    public function getMoviesByGenre(?array $genreIds): array
    {
        $query = [
            'include_adult' => 'false',
            'include_video' => 'false',
            'language' => 'en-US',
            'page' => 1,
            'sort_by' => 'popularity.desc',
            'with_genres' => implode(',', $genreIds),
        ];

        $movies = $this->makeApiRequest('discover/movie', $query)['results'];

        foreach ($movies as &$movie) {
            $movie['video_url'] = $this->getVideoUrl($movie['id']); // Récupérez l'URL de la vidéo
        }

        return $movies;
    }

    public function searchMovies(string $query): array
    {
        $queryParams = [
            'query' => $query,
            'include_adult' => 'false',
            'language' => 'en-US',
            'page' => 1,
        ];

        return $this->makeApiRequest('search/movie', $queryParams)['results'] ?? [];
    }

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
