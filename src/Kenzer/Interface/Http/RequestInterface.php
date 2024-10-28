<?php

namespace Kenzer\Interface\Http;

interface RequestInterface
{
    public function getMethod() : string;
    public function getPath() : string;

    public function methodIs(string $method) : bool;
}
