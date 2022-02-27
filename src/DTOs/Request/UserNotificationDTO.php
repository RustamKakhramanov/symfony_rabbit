<?php

namespace App\DTOs\Request;

use App\DTOs\BaseDTO;

class UserNotificationDTO  extends BaseRequestDTO
{
    public $clientID;
    public $channel;
    public $content;
}