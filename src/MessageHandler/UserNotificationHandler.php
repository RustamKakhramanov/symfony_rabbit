<?php

namespace App\MessageHandler;

use App\Message\UserNotification;
use App\Service\NotificationService;


class UserNotificationHandler
{

    private NotificationService $notifier;

    public function __construct(  NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(UserNotification $message)
    {
        $users = $message->getUsers();
        $this->notifier->handle($users);
    }


}