<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BookController.php',
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'app_book_show', methods: ['GET'])]
    // #[Route('/{id<\d+>?1}', name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1])]
    public function show(int $id = 1): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller! id : '.$id,
            'path' => 'src/Controller/BookController.php',
        ]);
    }
}
