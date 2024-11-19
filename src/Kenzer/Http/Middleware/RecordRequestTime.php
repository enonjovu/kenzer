<?php

declare(strict_types=1);

namespace Kenzer\Http\Middleware;

use Kenzer\Application\FileSystem;
use Kenzer\Interface\Http\MiddlewareInterface;
use Kenzer\Interface\IO\FileSystemInterface;
use Kenzer\Utility\FileManager;

class RecordRequestTime implements MiddlewareInterface
{
    private FileSystemInterface $fs;

    public function __construct()
    {
        $path = FileManager::joinPaths(STORAGE_DIRECTORY, 'kenzer.log');

        $this->fs = new FileSystem($path);
    }

    public function handle(\Kenzer\Interface\Http\RequestInterface $request, \Closure $next): \Kenzer\Interface\Http\ResponseInterface
    {
        $time = new \DateTime;
        $this->fs->write(sprintf('requeststart time : %s', $time->format('D, d M Y H:i:s')));

        $output = $next($request);

        $data = $this->fs->read();

        $time = new \DateTime;
        $this->fs->write(sprintf('%send time : %s', $data, PHP_EOL, $time->format('D, d M Y H:i:s')));

        return $output;
    }
}
