<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BookVoter extends Voter
{
    public const EDIT = 'book.edit';
    public const VIEW = 'book.view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if (!$user instanceof User && \in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return true;
        }

        return match ($attribute) {
            self::VIEW => $this->checkView(),
            self::EDIT => $this->checkEdit($user, $subject),
            default => false
        };
    }

    public function checkView(): bool
    {
        return true;
    }

    public function checkEdit(User $user, Book $book): bool
    {
        return $user === $book->getCreatedBy();
    }
}
