<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Event\MovieUnderageEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class MovieVoter extends Voter
{
    public const UNDERAGE = 'movie.underage';
    public const EDIT = 'movie.edit';

    public function __construct(private readonly EventDispatcherInterface $dispatcher){}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Movie
            && \in_array($attribute, [self::EDIT, self::UNDERAGE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        assert($user instanceof User);

        return match ($attribute) {
            self::UNDERAGE => $this->checkUnderage($user, $subject),
            self::EDIT => $this->checkEdit($user, $subject),
            default => false,
        };
    }

    public function checkUnderage(User $user, Movie $movie): bool
    {
        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y ?? null;

        $vote = match ($movie->getRated()) {
            'G' => true,
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false,
        };

        if (false === $vote) {
            $this->dispatcher->dispatch(new MovieUnderageEvent($movie, $user));
        }

        return $vote;
    }

    public function checkEdit(User $user, Movie $movie): bool
    {
        return $this->checkUnderage($user, $movie)
            && $user === $movie->getCreatedBy();
    }
}
