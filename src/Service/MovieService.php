<?php

namespace App\Service;

use App\Service\Api\AbstractApiClient as ApiAbstractApiClient;

class MovieService extends ApiAbstractApiClient implements MovieServiceInterface
{
    public function getPopularFilms(): array
    {
        return $this->makeApiRequest('movie/popular', ['language' => 'en-US', 'page' => 1])['results'];
    }

    public function getMoviesByGenre(?array $genreIds, int $page = 1, int $pageSize = 20): array
    {
        $query = [
            'include_adult' => 'false',
            'include_video' => 'false',
            'language' => 'en-US',
            'page' => $page, // send the number of page
            'sort_by' => 'popularity.desc',
            'with_genres' => implode(',', $genreIds),
        ];

        $movies = $this->makeApiRequest('discover/movie', $query);

        // Retrieve the results table and other metadata
        $results = $movies['results'];

        foreach ($results as &$movie) {
            $movie['video_url'] = $this->getVideoUrl($movie['id']); // get video url
        }

        return [
            'movies' => $results,
            'total_pages' => $movies['total_pages'],
            'total_results' => $movies['total_results'],
            'current_page' => $page,
        ];
    }

    public function searchMovies(string $query, int $page = 1): array
    {
        $queryParams = [
            'query' => $query,
            'include_adult' => 'false',
            'language' => 'en-US',
            'page' => $page,
        ];

        $moviesData = $this->makeApiRequest('search/movie', $queryParams) ?? [];

        return [
            'movies' => $moviesData['results'],
            'total_pages' => $moviesData['total_pages'],
            'current_page' => $page,
        ];
    }

    public function getVideoMovie(int $idMovie): string
    {
        return $this->getVideoUrl($idMovie);
    }

    protected function getVideoUrl(int $movieId): string
    {
        // Retrieve movie details to get video URL
        $videoData = $this->makeApiRequest("movie/$movieId/videos");

        return (count($videoData['results']) > 0) && $videoData['results'][0]['key'] ? 'https://www.youtube.com/embed/'.$videoData['results'][0]['key'] : ''; // Modifiez selon votre logique d'URL
    }
}
