<?php

declare(strict_types=1);

use Kenzer\View\View;

function view(string $path, array $options = []): View
{
    return View::make($path, $options);
}
