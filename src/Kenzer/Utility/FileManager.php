<?php

declare(strict_types=1);

namespace Kenzer\Utility;

use Exception;

class FileManager
{
    // Get the content of a file
    public static function getFileContent(string $filePath): string
    {
        if (! self::fileExists($filePath)) {
            throw new Exception("File does not exist: $filePath");
        }

        return file_get_contents($filePath);
    }

    // Check if a file exists
    public static function fileExists(string $filePath): bool
    {
        return file_exists($filePath) && is_file($filePath);
    }

    // Check if a directory exists
    public static function directoryExists(string $dirPath): bool
    {
        return file_exists($dirPath) && is_dir($dirPath);
    }

    // Create a directory with recursive creation
    public static function createDirectory(string $dirPath, int $permissions = 0755): void
    {
        if (! self::directoryExists($dirPath)) {
            if (! mkdir($dirPath, $permissions, true)) {
                throw new Exception("Failed to create directory: $dirPath");
            }
        }
    }

    // Create a file with content, and ensure directories exist
    public static function createFile(string $filePath, string $content = ''): void
    {
        $dir = dirname($filePath);

        // Create the directory recursively if it does not exist
        if (! self::directoryExists($dir)) {
            self::createDirectory($dir);
        }

        if (file_put_contents($filePath, $content) === false) {
            throw new Exception("Failed to create file: $filePath");
        }
    }

    // Get list of files in a directory
    public static function getFilesInDirectory(string $dirPath): array
    {
        if (! self::directoryExists($dirPath)) {
            throw new Exception("Directory does not exist: $dirPath");
        }

        return array_diff(scandir($dirPath), ['.', '..']);
    }

    // Get list of files by extension in a directory
    public static function getFilesByExtension(string $dirPath, string $extension): array
    {
        if (! self::directoryExists($dirPath)) {
            throw new Exception("Directory does not exist: $dirPath");
        }

        $files = self::getFilesInDirectory($dirPath);

        return array_filter($files, function ($file) use ($extension) {
            return pathinfo($file, PATHINFO_EXTENSION) === $extension;
        });
    }

    // Get file extension
    public static function getFileExtension(string $filePath): string
    {
        if (! self::fileExists($filePath)) {
            throw new Exception("File does not exist: $filePath");
        }

        return pathinfo($filePath, PATHINFO_EXTENSION);
    }

    // Normalize and concatenate paths
    public static function normalizePath(string ...$paths): string
    {
        $fullPath = implode(DIRECTORY_SEPARATOR, $paths);

        return preg_replace('/[\/\\\\]+/', DIRECTORY_SEPARATOR, $fullPath);
    }

    // Join multiple paths considering leading slashes
    public static function joinPaths(string ...$paths): string
    {
        $normalizedPaths = array_map(function ($path) {
            return trim($path, DIRECTORY_SEPARATOR.'\\');
        }, $paths);

        $fullPath = implode(DIRECTORY_SEPARATOR, $normalizedPaths);

        return preg_replace('/[\/\\\\]+/', DIRECTORY_SEPARATOR, $fullPath);
    }

    // Join multiple paths while preserving leading slashes
    public static function joinPathsWithLeadingSlashes(string ...$paths): string
    {
        $normalizedPaths = array_map(function ($path) {
            return ltrim($path, DIRECTORY_SEPARATOR.'\\');
        }, $paths);

        $fullPath = implode(DIRECTORY_SEPARATOR, $normalizedPaths);

        return preg_replace('/[\/\\\\]+/', DIRECTORY_SEPARATOR, $fullPath);
    }
}
