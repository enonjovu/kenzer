<?php

declare(strict_types=1);

namespace Kenzer\Http\Middleware;

use Closure;
use Kenzer\Application\Session;
use Kenzer\Interface\Http\MiddlewareInterface;
use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Interface\Http\ResponseInterface;
use Kenzer\View\View;

class StartSession implements MiddlewareInterface
{
    public function __construct(private Session $session) {}

    public function handle(RequestInterface $request, Closure $next): ResponseInterface
    {

        $this->session->start();

        View::putGlobal('hello', 'lol');

        $output = $next($request);

        $this->session->save();

        return $output;
    }
}
