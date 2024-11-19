<?php

declare(strict_types=1);

namespace Kenzer\Application;

class Logger
{
    public function __construct(private Application $application) {}

    public function info(string $data) {}
}
