<?php

declare(strict_types=1);

namespace Kenzer\Validation\Rules;

use Kenzer\Interface\Validation\RuleInterface;

class RequiredRule implements RuleInterface
{
    public function passes($value) : bool
    {
        return ! empty($value) && $value !== '';
    }

    public function fail($attribute) : string
    {
        return "$attribute is required";
    }
}
