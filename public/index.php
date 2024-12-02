<?php

declare(strict_types=1);
use Kenzer\Application\Application;
use Kenzer\Http\Request;
use Kenzer\Validation\Validator;

define('BASE_DIRECTORY', dirname(__DIR__));
define('STORAGE_DIRECTORY', dirname(__DIR__) . '/storage');

require_once __DIR__ . '/../vendor/autoload.php';

$app = Application::make(BASE_DIRECTORY);

$app->handleRequest(new Request);
