<?php

namespace App\DTOs;

class BaseDTO
{
    public function __get ( $name ) {
        return $this->$name ?? null;
    }

    public function toArray (): array {
        return get_object_vars( $this );
    }
}