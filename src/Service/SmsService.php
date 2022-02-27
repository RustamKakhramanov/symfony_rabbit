<?php

namespace App\Service;

use App\DTOs\NotificatorDTO;
use App\Interfaces\NotificationService;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class SmsService extends AbstractNotifyService
{
    const SEND_TYPE = 'sms';

}