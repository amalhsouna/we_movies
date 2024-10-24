<?php

namespace App\Service;

interface MovieServiceInterface
{
    public function getPopularFilms(): array;

    public function getMoviesByGenre(?array $genreIds): array;

    public function searchMovies(string $query): array;

    public function getVideoMovie(int $idMovie): string;
}
