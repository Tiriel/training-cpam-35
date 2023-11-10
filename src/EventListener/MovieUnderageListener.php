<?php

namespace App\EventListener;

use App\Movie\Event\MovieUnderageEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

#[AsEventListener(event: MovieUnderageEvent::class)]
class MovieUnderageListener
{
    public function __construct(
        private readonly LoggerInterface $logger,
        // private readonly MailerInterface $mailer,
    ) {}

    public function __invoke(MovieUnderageEvent $event): void
    {
        $movie = $event->getMovie();

        $msg = sprintf(
            "User %s as tried to view the movie \"%s\" (rated : %s)",
            $event->getUser()->getEmail(),
            $movie->getTitle(),
            $movie->getRated()
        );

        $this->logger->info($msg);
        // $this->mailer->send(new RawMessage($msg));
    }
}
