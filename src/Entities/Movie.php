<?php

namespace App\Entities;

class Movie
{
    private $id;

    /**
     * @var string|null
     */
    private $posterPath;

    /**
     * @var bool
     */
    private $adult;

    /**
     * @var string
     */
    private $overview;

    /**
     * @var string
     */
    private $releaseDate;

    /**
     * @var array
     */
    private $genreIds;

    /**
     * @var string
     */
    private $originalTitle;

    /**
     * @var string
     */
    private $originalLanguage;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null
     */
    private $backdropPath;

    /**
     * @var float
     */
    private $popularity;

    /**
     * @var int
     */
    private $voteCount;

    /**
     * @var bool
     */
    private $video;

    /**
     * @var int
     */
    private $voteAverage;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Movie
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getPosterPath(): ?string
    {
        return $this->posterPath;
    }

    public function setPosterPath(?string $posterPath): Movie
    {
        $this->posterPath = $posterPath;

        return $this;
    }

    public function isAdult(): bool
    {
        return $this->adult;
    }

    public function setAdult(bool $adult): Movie
    {
        $this->adult = $adult;

        return $this;
    }

    public function getOverview(): string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): Movie
    {
        $this->overview = $overview;

        return $this;
    }

    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): Movie
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getGenreIds(): array
    {
        return $this->genreIds;
    }

    public function setGenreIds(array $genreIds): Movie
    {
        $this->genreIds = $genreIds;

        return $this;
    }

    public function getOriginalTitle(): string
    {
        return $this->originalTitle;
    }

    public function setOriginalTitle(string $originalTitle): Movie
    {
        $this->originalTitle = $originalTitle;

        return $this;
    }

    public function getOriginalLanguage(): string
    {
        return $this->originalLanguage;
    }

    public function setOriginalLanguage(string $originalLanguage): Movie
    {
        $this->originalLanguage = $originalLanguage;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Movie
    {
        $this->title = $title;

        return $this;
    }

    public function getBackdropPath(): ?string
    {
        return $this->backdropPath;
    }

    public function setBackdropPath(?string $backdropPath): Movie
    {
        $this->backdropPath = $backdropPath;

        return $this;
    }

    public function getPopularity(): float
    {
        return $this->popularity;
    }

    public function setPopularity(float $popularity): Movie
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getVoteCount(): int
    {
        return $this->voteCount;
    }

    public function setVoteCount(int $voteCount): Movie
    {
        $this->voteCount = $voteCount;

        return $this;
    }

    public function isVideo(): bool
    {
        return $this->video;
    }

    public function setVideo(bool $video): Movie
    {
        $this->video = $video;

        return $this;
    }

    public function getVoteAverage(): int
    {
        return $this->voteAverage;
    }

    public function setVoteAverage(int $voteAverage): Movie
    {
        $this->voteAverage = $voteAverage;

        return $this;
    }
}
