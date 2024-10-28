<?php

namespace Kenzer\Validation\Rules;
use Kenzer\Interface\Validation\RuleInterface;

class RequiredRule implements RuleInterface
{
    public function passes($value) : bool
    {
        return isset($value) && ! empty($value) && $value !== '';
    }

    public function fail($attribute) : string
    {
        return "$attribute is required";
    }
}
