<?php

namespace App\Book\Manager;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly int $itemsPerPage,
    ) {}

    public function fetchPaginated(int $page): iterable
    {
        return $this->manager
            ->getRepository(Book::class)
            ->findBy([], [], $this->itemsPerPage, $page);
    }
}
