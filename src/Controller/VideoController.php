<?php

namespace App\Controller;

use App\Service\MovieServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie/{movieId}/videos", name="movies_video_id", methods={"GET"})
 */
class VideoController extends AbstractController
{
    public function __invoke(MovieServiceInterface $movieService, int $movieId): JsonResponse
    {
        // Call your movie service or whatever method to search video for movies
        $results = $movieService->getVideoMovie($movieId);

        return new JsonResponse($results);
    }
}
