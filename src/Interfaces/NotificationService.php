<?php

namespace App\Interfaces;

use App\DTOs\NotificatorDTO;

interface NotificationService
{
    public function send();
    public function prepare(NotificatorDTO $item);
    public static function handle($items);
}