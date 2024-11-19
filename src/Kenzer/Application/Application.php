<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Kenzer\Interface\Http\HttpKernelInterface;
use Kenzer\Interface\Http\RequestInterface;

class Application extends Container
{
    protected static ?Application $instance = null;

    private function __construct(
        readonly public string $basePath
    ) {
        $this->boot();
    }

    private function getServices()
    {
        return [
            \Kenzer\Application\Services\ExceptionService::class,
            \Kenzer\Application\Services\ConfigService::class,
            \Kenzer\Application\Services\RoutingService::class,
            \Kenzer\Application\Services\HttpService::class,
        ];
    }

    private function boot()
    {
        $this->singleton('app', $this);
        $this->singleton(Application::class, $this);

        foreach ($this->getServices() as $service) {
            $this->get($service)->boot();
        }
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
