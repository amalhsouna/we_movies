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

        return $this->makeApiRequest('discover/movie', $query)['results'];
    }
}
