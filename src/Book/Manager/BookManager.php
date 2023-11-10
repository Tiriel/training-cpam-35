<?php

namespace App\Book\Manager;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BookManager
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly int $itemsPerPage,
        private readonly Security $security,
    ) {}

    public function fetchPaginated(int $page): iterable
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $this->security->getUser();
        }

        return $this->manager
            ->getRepository(Book::class)
            ->findBy([], [], $this->itemsPerPage, $page);
    }
}
