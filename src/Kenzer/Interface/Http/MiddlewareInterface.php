<?php

declare(strict_types=1);

namespace Kenzer\Interface\Http;

use Closure;

interface MiddlewareInterface
{
    /**
     * Summary of handle
     *
     * @param  Closure(\Kenzer\Interface\Http\RequestInterface):\Kenzer\Interface\Http\ResponseInterface  $next
     */
    public function handle(RequestInterface $request, Closure $next): ResponseInterface;
}
