<?php

declare(strict_types=1);

namespace Kenzer\Routing;

use Kenzer\Exception\Http\HttpException;
use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Interface\Routing\RouteInterface;
use Kenzer\Interface\Routing\RouterInterface;

class Router implements RouterInterface
{
    /**
     * @var array<Route>
     */
    protected array $routes = [];

    public function __construct()
    {
        $this->routes = [];
    }

    public function loadFromClousure(callable $callback)
    {
        $callback($this);

        return $this;
    }

    protected function addRoute(string $methood, string $path, mixed $action)
    {
        $route = new Route($methood, $path, $action);
        $this->routes[] = $route;

        return $route;
    }

    public function get(string $path, mixed $action): RouteInterface
    {
        return $this->addRoute('get', $path, $action);
    }

    public function post(string $path, mixed $action): RouteInterface
    {
        return $this->addRoute('post', $path, $action);
    }

    public function put(string $path, mixed $action): RouteInterface
    {
        return $this->addRoute('put', $path, $action);
    }

    public function delete(string $path, mixed $action): RouteInterface
    {
        return $this->addRoute('delete', $path, $action);
    }

    public function dispatch(RequestInterface $request): RouteInterface
    {
        $filteredRoutes = array_filter(
            $this->routes,
            fn (RouteInterface $route) => $route->matchFromRequest($request)
        );

        if (empty($filteredRoutes)) {
            throw new HttpException(404, 'Not Found');
        }

        $filteredRoutes = array_filter(
            [...$filteredRoutes],
            fn (RouteInterface $route) => $request->methodIs($route->getMethod())
        );

        if (empty($filteredRoutes)) {
            throw new HttpException(409, 'Method Not allowed');
        }

        $filteredRoutes = [...$filteredRoutes];

        return $filteredRoutes[0];
    }
}
