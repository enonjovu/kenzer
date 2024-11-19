<?php

declare(strict_types=1);

namespace Kenzer\Interface\IO;

interface FileSystemInterface
{
    public function read(): string;

    public function write(string $data): void;

    public function exists(): bool;

    public function delete(): void;
}
