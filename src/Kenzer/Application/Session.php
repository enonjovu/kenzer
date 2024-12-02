<?php

declare(strict_types=1);

namespace Kenzer\Application;

use Kenzer\Exception\SessionException;

class Session
{
    public function __construct()
    {
    }

    public static function start()
    {
        if (self::isActive()) {
            throw new SessionException('Session has already been started');
        }

        if (headers_sent($filename, $line)) {
            throw new SessionException(sprintf('Headers already sent by %s:%s', $filename, $line));
        }

        if (! session_start()) {
            throw new SessionException('Unable to start session');
        }
    }

    public static function save() : void
    {
        session_write_close();
    }

    public static function isActive() : bool
    {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public static function regenerate() : bool
    {
        return session_regenerate_id();
    }

    public static function put(string $key, mixed $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null)
    {
        return $_SESSION['__flash'][$key] ?? $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key) : void
    {
        unset($_SESSION[$key]);
    }


    public static function flash(string $key, mixed $value)
    {
        $_SESSION['__flash'][$key] = $value;
    }

    public static function clearFlash()
    {
        unset($_SESSION['__flash']);
    }

    public static function has(string $key)
    {
        if (isset($_SESSION['__flash'][$key])) {
            return true;
        }

        if (isset($_SESSION[$key])) {
            return true;
        }

        return false;
    }
}
