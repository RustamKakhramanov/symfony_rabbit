<?php

namespace App\DTOs\Resources;

use App\DTOs\BaseDTO;
use App\Entity\User;

class UserResource extends BaseDTO
{
    public $id;
    public $firstName;
    public $lastName;
    public $email;
    public $phoneNumber;

    public function __construct ( User $user ) {
        $this->id   = $user->getId();
        $this->firstName   = $user->getFirstName();
        $this->firstName   = $user->getFirstName();
        $this->lastName    = $user->getLastName();
        $this->email       = $user->getEmail();
        $this->phoneNumber = $user->getPhoneNumber();
    }

    public static function forList ( array $users ) {
        $resources = [];

        foreach ( $users as $item ) {
            $resources[] = ( new static( $item ) )->toArray();
        }

        return $resources;
    }
}