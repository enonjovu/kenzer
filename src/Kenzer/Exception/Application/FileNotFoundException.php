<?php

declare(strict_types=1);

namespace Kenzer\Exception\Application;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $path)
    {
        parent::__construct(sprintf("could not find file or directory '%s'", $path));
    }
}
