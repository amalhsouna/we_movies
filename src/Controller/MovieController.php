<?php

namespace App\Controller;

use App\Service\TMDBService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    private $movieService;

    public function __construct(TMDBService $movieService)
    {
        $this->movieService = $movieService;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $genres = $this->movieService->getGenres();

        return $this->render('movies/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    /**
     * @Route("/movies/filter", name="movies_filter", methods={"POST"})
     */
    public function filterMovies(Request $request): JsonResponse
    {
        $selectedGenres = $request->request->all('genres');

        if (!is_array($selectedGenres)) {
            return new JsonResponse(['error' => 'Invalid Genres'], 400);
        }

        $moviesByGenre = [];
        try {
            // Retrieve movies by genres
            $moviesByGenre = $this->movieService->getMoviesByGenre($selectedGenres);
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

        if (!$query) {
            return new JsonResponse([]); // Return an empty array if no query
        }

        // Call your TMDB service or whatever method to search for movies
        $results = $this->movieService->searchMovies($query); // Assuming this returns an array of movies

        return new JsonResponse($results);
    }

    /**
     * @Route("/movies/genre/{id}", name="movies_by_genre", methods={"GET"})
     */
    public function moviesByGenre(int $id): Response
    {
        $movies = $this->movieService->getMoviesByGenre($id);

        return $this->render('movies/genre.html.twig', [
            'movies' => $movies,
        ]);
    }
}
