<?php

declare(strict_types=1);

namespace Kenzer\Interface\Data;

interface Jsonable
{
    public function toJson(): string;
}
