<?php

namespace App\Entities;

class MovieList
{
    /**
     * @var array<Movie>
     */
    private array $results = [];

    /**
     * @param Movie[] $results
     */
    public function setResults(array $results): MovieList
    {
        $this->results = $results;

        return $this;
    }

    public function first(): ?array
    {
        $first = reset($this->results);

        return false !== $first ? $first : null;
    }
}
