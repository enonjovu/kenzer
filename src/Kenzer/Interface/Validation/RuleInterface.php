<?php

namespace Kenzer\Interface\Validation;

interface RuleInterface
{
    public function passes($vale) : bool;
    public function fail($attribute) : string;
}
