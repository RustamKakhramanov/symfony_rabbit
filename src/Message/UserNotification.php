<?php

namespace App\Message;

use phpDocumentor\Reflection\Types\Collection;

class UserNotification
{
    private array $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}