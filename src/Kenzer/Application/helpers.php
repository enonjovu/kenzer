<?php

declare(strict_types=1);

use Kenzer\View\View;

function view(string $path, array $options = []): View
{
    return View::make($path, $options);
}

function safeHtml(mixed $subject): string
{
    if (is_numeric($subject)) {
        $subject = (string) $subject;
    }

    if (is_string($subject) || is_numeric($subject)) {
        return htmlspecialchars($subject, ENT_QUOTES | ENT_SUBSTITUTE);
    }

    return htmlspecialchars(json_encode($subject), ENT_QUOTES | ENT_SUBSTITUTE);
}

function with(array $data, callable $callback)
{
    extract($data);
    $callback();
}
