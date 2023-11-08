<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookRepository $repository): JsonResponse
    {
        $titles = array_map(
            fn(Book $book) => $book->getTitle(),
            $repository->findBy([], ['id' => 'DESC'], 10)
        );

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/BookController.php',
            'books' => $titles,
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

    #[Route('/new', name: 'app_book_new', methods: ['GET'])]
    public function new(EntityManagerInterface $manager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{title}', name: 'app_book_title', methods: ['GET'])]
    public function title(BookRepository $repository, ?string $title = null): Response
    {
        $book = $repository->findOneBy(['title' => $title]);

        return $this->redirectToRoute('app_book_show', [
            'id' => $book->getId(),
        ]);
    }
}
