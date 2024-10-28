<?php

namespace Kenzer\Http;

class RedirectResponse extends Response
{
    public function __construct(
        string $path,
        int $statusCode = 302,
        array $headers = [],
    ) {
        parent::__construct('', $statusCode, $headers);
        $this->setHeader('Location', $path);
    }

    public static function make(
        string $path,
        int $statusCode = 302,
        array $headers = [],
    ) {
        return new static($path, $statusCode, $headers);
    }
}
