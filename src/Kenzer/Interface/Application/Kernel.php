<?php

declare(strict_types=1);

namespace Kenzer\Interface\Application;

interface Kernel
{
    public function run(): void;
}
