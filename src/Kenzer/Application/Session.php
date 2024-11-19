<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Kenzer\Exception\SessionException;

class Session
{
    public function __construct() {}

    public function start()
    {
        if ($this->isActive()) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($filename, $line)) {
            throw new SessionException(sprintf('Headers already sent by %s:%s', $filename, $line));
        }

        if (! session_start()) {
            throw new SessionException('Unable to start session');
        }
    }

    public function save(): void
    {
        session_write_close();
    }

    public function isActive(): bool
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public function regenerate(): bool
    {
        return session_regenerate_id();
    }

    public function put(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }
}
