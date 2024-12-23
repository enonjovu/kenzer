<?php

declare(strict_types=1);

namespace Kenzer\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    public function getAuthority(): string
    {
        return '';
    }

    public function getFragment(): string
    {
        return '';
    }

    public function getHost(): string
    {
        return '';
    }

    public function getPath(): string
    {
        return '';
    }

    public function getPort(): ?int
    {
        return '';
    }

    public function getQuery(): string
    {
        return '';
    }

    public function getScheme(): string
    {
        return '';
    }

    public function getUserInfo(): string
    {
        return '';
    }

    public function withFragment(string $fragment): UriInterface
    {
        return '';
    }

    public function withHost(string $host): UriInterface
    {
        return '';
    }

    public function withPath(string $path): UriInterface
    {
        return '';
    }

    public function withPort(?int $port): UriInterface
    {
        return '';
    }

    public function withQuery(string $query): UriInterface
    {
        return '';
    }

    public function withScheme(string $scheme): UriInterface
    {
        return '';
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        return '';
    }

    public function __toString(): string
    {
        return '';
    }
}
