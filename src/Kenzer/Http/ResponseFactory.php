<?php

declare(strict_types=1);

namespace Kenzer\Http;

use Exception;
use Kenzer\Interface\Data\Arrayable;
use Kenzer\Interface\Data\Jsonable;
use Kenzer\Interface\Data\Responsable;
use Kenzer\Interface\Http\ResponseInterface;
use Stringable;

class ResponseFactory
{
    public static function make(
        mixed $content = '',
        int $statuCode = 200,
        array $headers = []
    ) {
        return (new static($content, $statuCode, $headers))->getResponse();
    }

    public function __construct(
        private mixed $content = '',
        private int $statuCode = 200,
        private array $headers = []
    ) {}

    public function getResponse(): ResponseInterface
    {
        if (is_string($this->content)) {
            return new Response($this->content, $this->statuCode, $this->headers);
        }

        if (is_array($this->content)) {
            return new JsonResponse($this->content, $this->statuCode, $this->headers);
        }

        if ($this->content instanceof ResponseInterface) {
            return $this->content;
        }

        if ($this->content instanceof Responsable) {
            return $this->content->toResponse();
        }

        if ($this->content instanceof Stringable) {
            return new JsonResponse($this->content, $this->statuCode, $this->headers);
        }

        if ($this->content instanceof Arrayable) {
            return new JsonResponse($this->content, $this->statuCode, $this->headers);
        }

        if ($this->content instanceof Jsonable) {
            return new JsonResponse($this->content, $this->statuCode, $this->headers);
        }

        throw new Exception('invalid response content');
    }
}
