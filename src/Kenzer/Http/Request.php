<?php

declare(strict_types=1);

namespace Kenzer\Http;

use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Utility\AttributeBag;

class Request extends AttributeBag implements
    RequestInterface
{

    public function __construct()
    {
        parent::__construct([
            ...$_POST,
            ...$_GET,
        ]);
    }

    public function getPath() : string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function getMethod() : string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function methodIs(string $method) : bool
    {
        return $this->getMethod() == strtoupper($method);
    }
}
