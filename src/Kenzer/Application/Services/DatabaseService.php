<?php

declare(strict_types=1);

namespace Kenzer\Application\Services;

use Kenzer\Application\Application;
use Kenzer\Database\Connection;
use Kenzer\Interface\Application\ApplicationServiceInterface;

class DatabaseService implements ApplicationServiceInterface
{
    public function __construct(private Application $application) {}

    public function boot(): void
    {
        $this->application->singleton(Connection::class, function () {});
    }
}
