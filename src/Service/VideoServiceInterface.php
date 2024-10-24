<?php

namespace App\Service;

interface VideoServiceInterface
{
    public function getVideoMovie(int $idMovie): string;
}
