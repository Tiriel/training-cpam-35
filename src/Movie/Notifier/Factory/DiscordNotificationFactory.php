<?php

namespace App\Movie\Notifier\Factory;

use App\Movie\Notifier\Notifications\DiscordNotification;
use Symfony\Component\Notifier\Notification\Notification;

class DiscordNotificationFactory implements NotificationFactoryInterface
{
    public function create(string $subject): Notification
    {
        return new DiscordNotification($subject);
    }
}
