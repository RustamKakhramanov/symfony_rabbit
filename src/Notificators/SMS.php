<?php

namespace App\Notificators;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class SMS implements NotifierInterface
{

    public function send ( Notification $notification, RecipientInterface ...$recipients ): void {

    }
}