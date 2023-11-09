<?php

namespace App\Book\Manager;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        #[Autowire(param: 'app.books_per_page')]
        private readonly int $booksPerPage,
    ) {}

    public function fetchPaginated(int $page): iterable
    {
        return $this->manager
            ->getRepository(Book::class)
            ->findBy([], [], $this->booksPerPage, $page);
    }
}
