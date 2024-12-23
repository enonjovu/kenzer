<?php

declare(strict_types=1);

namespace Kenzer\Interface\Data;

use Kenzer\Interface\Http\ResponseInterface;

interface Responsable
{
    public function toResponse(): ResponseInterface;
}
