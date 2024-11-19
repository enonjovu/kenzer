<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Kenzer\Exception\Application\FileNotFoundException;
use Kenzer\Interface\IO\FileSystemInterface;
use Kenzer\Utility\FileManager;

class FileSystem implements FileSystemInterface
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = FileManager::normalizePath($path);
    }

    public function delete(): void
    {
        $this->throwIfFileNotFound();

        unlink($this->path);
    }

    public function exists(): bool
    {
        return file_exists($this->path);
    }

    public function read(): string
    {
        $this->throwIfFileNotFound();

        return file_get_contents($this->path);
    }

    public function write(string $data): void
    {
        file_put_contents($this->path, $data);
    }

    public function throwIfFileNotFound()
    {
        if (! $this->exists()) {
            throw new FileNotFoundException(sprintf("Could not find file or directory with path '%s'", $this->path));
        }
    }
}
