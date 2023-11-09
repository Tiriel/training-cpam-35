<?php

namespace App\Movie\Notifier;

use App\Movie\Notifier\Factory\NotificationFactoryInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class MovieNotifier
{
    public function __construct(
        private readonly NotifierInterface $notifier,
        /** @var NotificationFactoryInterface[] $factories */
        private iterable $factories,
    ) {
    }

    public function sendNotification(string $subject): void
    {
        $user = new class {
            public function getEmail(): string
            {
                return 'me@me.com';
            }

            public function getPreferredChannel(): string
            {
                return 'slack';
            }
        };

        $notification = $this->factories[$user->getPreferredChannel()]->create($subject);

        $this->notifier->send($notification, new Recipient($user->getEmail()));
    }
}
