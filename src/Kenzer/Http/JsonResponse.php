<?php

namespace Kenzer\Http;
use Kenzer\Interface\Data\Arrayable;
use Kenzer\Interface\Data\Jsonable;
use Stringable;

class JsonResponse extends Response
{
    public function __construct(
        mixed $content,
        int $statusCode = 200,
        array $headers = [],
    ) {
        parent::__construct(
            $this->compileContentToString($content),
            $statusCode,
            $headers
        );

        $this->setHeader('Content-Type', 'application/json');
    }

    protected function compileContentToString(mixed $content)
    {
        return match (true) {
            $content instanceof Jsonable => $content->toJson(),
            $content instanceof Arrayable => json_encode($content->toArray()),
            $content instanceof Stringable => $content,
            is_string($content) => $content,
            is_array($content) => json_encode($content),
            default => json_encode($content),
        };
    }
}
