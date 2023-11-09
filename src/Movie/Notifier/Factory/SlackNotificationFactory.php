<?php

namespace App\Movie\Notifier\Factory;

use App\Movie\Notifier\Notifications\SlackNotification;
use Symfony\Component\Notifier\Notification\Notification;

class SlackNotificationFactory implements NotificationFactoryInterface
{
    public function create(string $subject): Notification
    {
        return new SlackNotification($subject);
    }
}
