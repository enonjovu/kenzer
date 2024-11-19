<?php

declare(strict_types=1);

namespace Kenzer\Application\Services;

use Kenzer\Application\Application;
use Kenzer\Exception\Application\ServiceException;
use Kenzer\Interface\Application\ApplicationServiceInterface;
use Kenzer\Interface\Routing\RouterInterface;
use Kenzer\Routing\Router;

class RoutingService implements ApplicationServiceInterface
{
    public function __construct(
        private Application $application
    ) {}

    public function boot(): void
    {
        $router = new Router;
        $routesCallbackPath = sprintf('%s/config/routes.php', $this->application->basePath);

        if (! file_exists($routesCallbackPath)) {
            throw new ServiceException("path to routes configuraton '$routesCallbackPath' does not exist");
        }

        $loadRoutesCallback = require_once $routesCallbackPath;

        if (! is_callable($loadRoutesCallback)) {
            throw new ServiceException('routes configuration must return a closure');
        }

        $router->loadFromClousure($loadRoutesCallback);

        $this->application->singleton(RouterInterface::class, $router);
    }
}
