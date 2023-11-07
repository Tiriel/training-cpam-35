<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/song/{id<\d+>}', name: 'app_song_get')]
class GetSongController
{
    public function __invoke(): Response
    {
        return new JsonResponse([
            'message' => 'I\'m a controller!',
            'controller' => self::class,
        ]);
    }
}