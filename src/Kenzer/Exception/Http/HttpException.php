<?php

namespace Kenzer\Exception\Http;
use Exception;

class HttpException extends Exception
{
    public function __construct(
        int $code,
        string $message
    ) {
        parent::__construct($message, $code);
    }

    public function getStatusCode() : int
    {
        return $this->code;
    }
}
