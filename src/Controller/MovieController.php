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
            // Retrieve movies by genre
            $moviesByGenre = $this->movieService->getMoviesByGenre($selectedGenres);
            // dd($moviesByGenre);
        } catch (\Exception $e) {
            // Log the exception and return an error response
            error_log('Error retrieving movies: '.$e->getMessage());

            return new JsonResponse(['error' => 'Could not retrieve movies'], 500);
        }

        return new JsonResponse($moviesByGenre);
    }
}
