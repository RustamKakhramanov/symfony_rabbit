<?php

namespace App\Interfaces;

use Symfony\Component\Validator\Constraints\Collection;

interface RulesInterface
{
    public static function getRules (): Collection;
}