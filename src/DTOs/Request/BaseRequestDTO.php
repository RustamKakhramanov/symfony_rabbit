<?php

namespace App\DTOs\Request;

use App\DTOs\BaseDTO;
use Symfony\Component\HttpFoundation\Request;

class BaseRequestDTO extends BaseDTO
{
    public function __construct ( Request $request ) {
        $this->fillFromRequest( $this->toArray(), $request );
    }

    protected function fillFromRequest ( array $items, Request $request ) {
        foreach ( $items as $key => $item ) {
            $this->$key = json_decode($request->getContent())->$key ?? $request->get( $key );
        }
    }
}