<?php

namespace Kenzer\Interface\Routing;
use Kenzer\Interface\Http\RequestInterface;

interface RouteInterface
{
    public function getAction() : mixed;
    public function getMethod() : string;
    public function match(string $path) : bool;

    public function matchFromRequest(RequestInterface $request) : bool;

    public function getParams() : array;
}
