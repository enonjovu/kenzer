<?php

namespace Kenzer\Exception\Http;

use Exception;

class ValidationException extends Exception
{
    public function __construct(
        public readonly array $data = [],
        public readonly array $errors = [],
    ) {
        parent::__construct("validation exception", 422, null);
    }

    public static function create(array $data = [], array $errors = []) : self
    {
        return new static($data, $errors);
    }
}
