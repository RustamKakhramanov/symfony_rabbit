<?php

namespace App\Controller;

use App\DTOs\Request\UserNotificationDTO;
use App\Message\UserNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

class NotificationController extends AbstractController
{
    public function index(Request $request, MessageBusInterface $bus)
    {
        $bus->dispatch(new UserNotification((new UserNotificationDTO($request))->toArray()));
    }

}