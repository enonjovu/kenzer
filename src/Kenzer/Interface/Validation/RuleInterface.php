<?php

declare(strict_types=1);

namespace Kenzer\Interface\Validation;

interface RuleInterface
{
    public function passes($vale): bool;
    public function fail($attribute): string;
}
