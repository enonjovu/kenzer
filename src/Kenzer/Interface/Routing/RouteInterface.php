<?php

declare(strict_types=1);

namespace Kenzer\Interface\Routing;

use Kenzer\Interface\Http\RequestInterface;

interface RouteInterface
{
    public function getAction(): mixed;

    public function getMethod(): string;

    public function match(string $path): bool;

    public function matchFromRequest(RequestInterface $request): bool;

    public function getParams(): array;

    /**
     * Adds a middleware to the route
     *
     * @param  array<\Kenzer\Interface\Http\MiddlewareInterface>  $middleware
     */
    public function middleware(array $middleware): self;

    /**
     * @return array<\Kenzer\Interface\Http\MiddlewareInterface>
     */
    public function getMiddlewares(): array;
}
