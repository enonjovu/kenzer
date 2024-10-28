<?php

namespace Kenzer\Interface\Http;

interface HttpKernelInterface
{
    public function handleRequest(RequestInterface $request) : ResponseInterface;
}
