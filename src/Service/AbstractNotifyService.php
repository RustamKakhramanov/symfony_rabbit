<?php

namespace App\Service;

use App\DTOs\NotificatorDTO;
use App\Enums\NotificatorEnum;
use App\Interfaces\NotificationService;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

abstract  class AbstractNotifyService  implements NotificationService
{
    protected NotifierInterface $notifier;
    protected $recipient;
    protected $notification;

    public function __construct( NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function prepare (NotificatorDTO $item) {
        $user = $item->user;

        $this->notification = (new Notification('New message', [static::SEND_TYPE]))
            ->content($item->content);

        // The receiver of the Notification
        $this->recipient = new Recipient(
            $user->getEmail(),
            $user->getPhonenumber()
        );
    }

    public static function handle ($items) {
        foreach ($items as  $item) {
            $class = NotificatorEnum::get(strtoupper($item->type).'_NOTIFY');
            $worker = (new static($class));
            $worker->send();
        }
    }

    public function send () {
        $this->notifier->send($this->notification, $this->recipient);
    }
}