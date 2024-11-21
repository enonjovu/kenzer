<?php

declare(strict_types=1);

namespace Kenzer\Http;

use Exception;
use Kenzer\Application\Application;
use Kenzer\Exception\Http\HttpException;
use Kenzer\Exception\Http\ValidationException;
use Kenzer\Interface\Http\HttpKernelInterface;
use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Interface\Http\ResponseInterface;
use Kenzer\Interface\Routing\RouterInterface;
use Kenzer\View\View;
use Throwable;

class HttpKernel implements HttpKernelInterface
{
    private ResponseInterface $response;

    /**
     * @var array<class-string>
     */
    private array $middlewares;

    public function __construct(
        private Application $application,
        private RouterInterface $router,
    ) {
        /**
         * @var \Kenzer\Utility\AttributeBag
         */
        $config = $application->get('config.app');

        $this->middlewares = $config->dot('http.middlewares') ?? [];
    }

    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        $initialAction = fn (RequestInterface $r) => $this->createResponse($r);

        $container = $this->application;

        $action = array_reduce(
            $this->middlewares,
            fn ($carry, $middleware) => fn (RequestInterface $r) => $container
                ->get($middleware)
                ->handle($r, $carry),
            $initialAction,
        );

        return $action($request);
    }

    private function createResponse(RequestInterface $request)
    {
        try {
            $route = $this->router->dispatch($request);

            $actionResult = $this->application->call($route->getAction(), $route->getParams());

            return ResponseFactory::make($actionResult);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    private function handleException(Throwable $throwable): ResponseInterface
    {
        if ($throwable instanceof ValidationException) {
            return new RedirectResponse('/');
        }

        return ResponseFactory::make(View::make('pages/errors/all', [
            'code' => $throwable instanceof HttpException ? $throwable->getStatusCode() : $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'trace' => $throwable->getTrace(),
        ]));
    }
}
