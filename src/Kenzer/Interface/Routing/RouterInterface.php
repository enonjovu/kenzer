<?php

namespace Kenzer\Interface\Routing;
use Kenzer\Interface\Http\RequestInterface;

interface RouterInterface
{
    public function dispatch(RequestInterface $request) : RouteInterface;
}
