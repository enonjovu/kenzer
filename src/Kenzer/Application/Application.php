<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Kenzer\Exception\Application\ApplicationException;
use Kenzer\Http\HttpKernel;
use Kenzer\Interface\Http\HttpKernelInterface;
use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Interface\Routing\RouterInterface;
use Kenzer\Routing\Router;
use Kenzer\View\ViewContentCompiler;

class Application extends Container
{
    protected static ?Application $instance = null;

    private function __construct(
        private string $basePath
    ) {
        $this->boot();
    }

    protected function loadRouteConfiguration()
    {
        $router = new Router();
        $routesCallbackPath = "{$this->basePath}/config/routes.php";

        if (! file_exists($routesCallbackPath)) {
            throw new ApplicationException("path to routes configuraton '$routesCallbackPath' does not exist");
        }

        $loadRoutesCallback = require_once $routesCallbackPath;

        if (! is_callable($loadRoutesCallback)) {
            throw new ApplicationException('routes configuration must return a closure');
        }
        $router->loadFromClousure($loadRoutesCallback);

        $this->singleton(RouterInterface::class, $router);
    }

    protected function loadHttpKernelConfiguration()
    {
        $router = $this->get(RouterInterface::class);

        $kernel = new HttpKernel($this, $router);
        $this->singleton(HttpKernelInterface::class, $kernel);
    }

    private function bootBindings()
    {
        $this->singleton(ViewContentCompiler::class, function() {
            $config = require_once __DIR__ . '/config/view.php';

            return ViewContentCompiler::create($config['directives']);
        });
    }

    private function boot()
    {
        $this->bootBindings();
        $this->loadRouteConfiguration();
        $this->loadHttpKernelConfiguration();
    }

    public static function make(string $basePath): Application
    {
        return self::$instance ??= new static($basePath);
    }

    public static function getInstance(): ?Application
    {
        return static::$instance;
    }

    public function handleRequest(RequestInterface $request)
    {
        $this->singleton(RequestInterface::class, $request);

        $this->get(HttpKernelInterface::class)
            ->handleRequest($request)
            ->send();
    }
}
