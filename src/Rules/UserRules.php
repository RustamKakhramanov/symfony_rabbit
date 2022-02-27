<?php

namespace App\Rules;

use App\Interfaces\RulesInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;

class UserRules implements RulesInterface
{
        public static function getRules(): Collection {
            return new Collection([
                'firstName' => (new Required(new NotNull)),
                'lastName' => (new Required(new NotNull)),
                'email' => (new Required(new NotNull)),
                'phoneNumber' => (new Required(new NotNull)),
            ]);
        }
}