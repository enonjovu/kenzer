<?php

declare(strict_types=1);

namespace Kenzer\Interface\View;

interface ViewContentCompiler
{
    public function process(string $subject): string;
}
