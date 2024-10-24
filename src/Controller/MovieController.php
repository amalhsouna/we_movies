<?php

namespace App\Controller;

use App\Service\MovieServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $movieService;

    public function __construct(MovieServiceInterface $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * @Route("/movies/filter", name="movies_filter", methods={"POST"})
     */
    public function filterMovies(Request $request): JsonResponse
    {
        $selectedGenres = $request->request->all('genres');
        $page = (int) $request->query->get('page', 1);
        if (empty($selectedGenres)) {
            return new JsonResponse(['error' => 'Invalid Genres'], 400);
        }

        $moviesByGenre = [];
        try {
            // Retrieve movies by genres
            $moviesByGenre = $this->movieService->getMoviesByGenre($selectedGenres, $page);
        } catch (\Exception $e) {
            // Log the exception and return an error response
            error_log('Error retrieving movies: '.$e->getMessage());

            return new JsonResponse(['error' => 'Could not retrieve movies'], 500);
        }

        return new JsonResponse($moviesByGenre);
    }

    /**
     * @Route("/movies/search", name="movies_search", methods={"GET"})
     */
    public function searchMovies(Request $request): JsonResponse
    {
        $query = $request->query->get('query');
        $page = (int) $request->query->get('page', 1);

        if (!$query) {
            return new JsonResponse([]); // Return an empty array if no query
        }

        // Call your movie service or whatever method to search for movies
        $results = $this->movieService->searchMovies($query, $page);

        return new JsonResponse($results);
    }

    /**
     * @Route("/movie/{movieId<\d+>}/videos", name="movies_video_id", methods={"GET"})
     */
    public function videoMovies(int $movieId): JsonResponse
    {
        // Call your movie service or whatever method to search video for movies
        $results = $this->movieService->getVideoMovie($movieId);

        return new JsonResponse($results);
    }
}
