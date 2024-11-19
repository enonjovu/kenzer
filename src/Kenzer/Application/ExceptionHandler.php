<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Throwable;

class ExceptionHandler
{
    public function __invoke(Throwable $throwable)
    {
        http_response_code(500);

        echo json_encode([
            'code' => $throwable->getCode(),
            'message' => $throwable->getMessage(),
            'file' => sprintf('%s:%s', $throwable->getFile(), $throwable->getLine()),
        ]);
    }
}
