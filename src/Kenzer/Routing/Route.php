<?php

declare(strict_types=1);

namespace Kenzer\Routing;

use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Interface\Routing\RouteInterface;

final class Route implements
    RouteInterface
{
    private string $method;
    private string $path;
    private mixed $action;
    private array $params;

    public function __construct(
        string $method,
        string $path,
        mixed $action
    ) {
        $this->method = strtoupper($method);
        $this->path = $this->compileRoute($path);
        $this->action = $action;
        $this->params = [];
    }

    public function match(string $path): bool
    {
        $match = preg_match($this->path, $path);

        if ($match) {
            $this->params = $this->getPathParameters($path);
        }

        return (bool) $match;
    }

    public function matchFromRequest(RequestInterface $request): bool
    {
        return $this->match($request->getPath());
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    protected function getPathParameters(string $path)
    {
        preg_match($this->path, $path, $matches);
        $matches = $matches ? array_slice($matches, 1) : [];
        $matches = array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);

        return $matches;
    }

    public function getParameter(string $key)
    {
        return $this->hasParameter($key) ? $this->params[$key] : null;
    }

    public function hasParameter(string $key): bool
    {
        return array_key_exists($key, $this->params);
    }

    public function getAction(): mixed
    {
        return $this->action;
    }

    protected function compileRoute($path)
    {
        $path = rtrim($path, '\\');
        return '#^' . preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?<$1>[a-zA-Z0-9_]+)', $path) . '$#';
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
