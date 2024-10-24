<?php

namespace App\Controller;

use App\Entities\MovieList;
use App\Service\GenderServiceInterface;
use App\Service\MovieServiceInterface;
use App\Service\VideoServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home")
 */
class MainController extends AbstractController
{
    public function __invoke(MovieServiceInterface $movieService,
        VideoServiceInterface $videoService,
        GenderServiceInterface $genderServiceInterface,
    ): Response {
        $genders = $genderServiceInterface->getGenres();
        $popularMovies = $movieService->getPopularFilms();

        $movieList = new MovieList();
        $movieList->setResults($popularMovies);
        $mainVideo = $videoService->getVideoMovie($movieList->first()['id']);

        return $this->render('movies/index.html.twig', [
            'genres' => $genders,
            'firstPopularMovie' => $popularMovies[0],
            'mainVideo' => $mainVideo,
        ]);
    }
}
