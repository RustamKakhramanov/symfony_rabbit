<?php

namespace App\DTOs;

use App\Entity\User;

class NotificatorDTO extends BaseDTO
{
    public User $user;
    public $content;
    public $type;

    public function __construct (User $user, string $content, string $type) {
    }
}