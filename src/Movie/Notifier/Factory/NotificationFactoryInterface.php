<?php

namespace App\Movie\Notifier\Factory;

use Symfony\Component\Notifier\Notification\Notification;

interface NotificationFactoryInterface
{
    public function create(string $subject): Notification;
}
