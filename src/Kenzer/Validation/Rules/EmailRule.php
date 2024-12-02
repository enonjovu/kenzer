<?php

declare(strict_types=1);

namespace Kenzer\Validation\Rules;

use Kenzer\Interface\Validation\RuleInterface;

class EmailRule implements RuleInterface
{
    public function passes($value) : bool
    {

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function fail($attribute) : string
    {
        return "$attribute must be a valid email";
    }
}
