<?php

declare(strict_types=1);

namespace Kenzer\Application\Services;

use Kenzer\Application\Application;
use Kenzer\Application\ExceptionHandler;
use Kenzer\Interface\Application\ApplicationServiceInterface;

class ExceptionService implements ApplicationServiceInterface
{
    public function __construct(private Application $application) {}

    public function boot(): void
    {

        $instance = $this->application->get(ExceptionHandler::class);

        set_exception_handler($instance);
    }
}
