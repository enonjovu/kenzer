<?php

declare(strict_types=1);

namespace Kenzer\Application\Services;

use Kenzer\Application\Application;
use Kenzer\Http\HttpKernel;
use Kenzer\Interface\Application\ApplicationServiceInterface;
use Kenzer\Interface\Http\HttpKernelInterface;
use Kenzer\Interface\Routing\RouterInterface;

class HttpService implements ApplicationServiceInterface
{
    public function __construct(private Application $application) {}

    public function boot(): void
    {
        $router = $this->application->get(RouterInterface::class);

        $kernel = new HttpKernel($this->application, $router);
        $this->application->singleton(HttpKernelInterface::class, $kernel);
    }
}
