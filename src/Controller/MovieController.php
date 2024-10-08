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
        error_log(print_r($request->request->all(), true));
        $selectedGenres = $request->request->get('genres');

        if (!is_array($selectedGenres)) {
            return new JsonResponse(['error' => 'Invalid Genres'], 400);
        }

        $movies = [];
        foreach ($selectedGenres as $genreId) {
            // Replace this method with one that retrieves movies by genre
            $moviesByGenre = $this->movieService->getMoviesByGenre((int) $genreId);
            $movies = array_merge($movies, $moviesByGenre);
        }

        return new JsonResponse($movies);
    }
}
