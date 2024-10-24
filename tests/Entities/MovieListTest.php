<?php

namespace App\Tests\Entities;

use App\Entities\Movie;
use App\Entities\MovieList;
use PHPUnit\Framework\TestCase;

class MovieListTest extends TestCase
{
    private MovieList $movieList;

    protected function setUp(): void
    {
        $this->movieList = new MovieList();
    }

    public function testSetResults()
    {
        // Create mock movies
        $movie1 = new Movie();
        $movie1->setId(1);
        $movie1->setTitle('First Movie');

        $movie2 = new Movie();
        $movie2->setId(2);
        $movie2->setTitle('Second Movie');

        // Set results
        $this->movieList->setResults([$movie1, $movie2]);

        // Use reflection to access the private results property
        $reflection = new \ReflectionClass($this->movieList);
        $resultsProperty = $reflection->getProperty('results');
        $resultsProperty->setAccessible(true);

        // Assert that the results are set correctly
        $this->assertCount(2, $resultsProperty->getValue($this->movieList));
        $this->assertSame($movie1, $resultsProperty->getValue($this->movieList)[0]);
        $this->assertSame($movie2, $resultsProperty->getValue($this->movieList)[1]);
    }
}
