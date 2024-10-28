<?php

declare(strict_types=1);

namespace Kenzer\Interface\Http;

interface HttpKernelInterface
{
    public function handleRequest(RequestInterface $request): ResponseInterface;
}
