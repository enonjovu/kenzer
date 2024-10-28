<?php

namespace Kenzer\Http;
use Exception;
use Kenzer\Application\Application;
use Kenzer\Exception\Http\HttpException;
use Kenzer\Exception\Http\ValidationException;
use Kenzer\Interface\Application\Kernel;
use Kenzer\Interface\Data\Responsable;
use Kenzer\Interface\Http\HttpKernelInterface;
use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Interface\Http\ResponseInterface;
use Kenzer\Interface\Routing\RouterInterface;
use Kenzer\View\View;
use Throwable;

class HttpKernel implements HttpKernelInterface
{
    private ResponseInterface $response;

    public function __construct(
        private Application $application,
        private RouterInterface $router,
    ) {
    }


    public function handleRequest(RequestInterface $request) : ResponseInterface
    {
        $response = null;

        try {

            $route = $this->router->dispatch($request);

            $actionResult = $this->application->call($route->getAction(), $route->getParams());

            $response = ResponseFactory::make($actionResult);
        } catch (Exception $e) {

            if ($e instanceof ValidationException) {
                return new RedirectResponse("/");
            }

            if ($e instanceof HttpException) {

                $errors = sprintf("pages/errors/all", $e->getCode());

                return ResponseFactory::make(View::make($errors, [
                    'code' => $e->getStatusCode(),
                    'message' => $e->getMessage(),
                ]));
            }

            $response = ResponseFactory::make(new class ($e) implements Responsable {

                public function __construct(
                    private Throwable $e,
                ) {
                }

                public function toResponse() : ResponseInterface
                {
                    return new Response($this->e->getMessage());
                }
            });
        }

        return $response;
    }
}
