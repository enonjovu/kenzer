<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Kenzer\Exception\Application\FileNotFoundException;
use Kenzer\Utility\AttributeBag;
use Kenzer\Utility\FileManager;

class ConfigLoader extends AttributeBag
{
    public function __construct(string $path)
    {
        $path = FileManager::normalizePath($path);

        if (! FileManager::fileExists($path)) {
            throw new FileNotFoundException($path);
        }

        $data = require $path;

        if (! is_array($data)) {
            throw new \InvalidArgumentException('config file should return an array');
        }

        parent::__construct($data);
    }
}
