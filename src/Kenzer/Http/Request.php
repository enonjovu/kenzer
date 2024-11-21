<?php

declare(strict_types=1);

namespace Kenzer\Http;

use Kenzer\Interface\Http\RequestInterface;
use Kenzer\Utility\AttributeBag;

class Request implements RequestInterface
{
    protected AttributeBag $headers;

    protected AttributeBag $data;

    public function __construct()
    {
        $this->headers = AttributeBag::create($this->loadHeaders());
        $this->data = AttributeBag::create([
            ...$_POST,
            ...$_GET,
        ]);
    }

    public function getPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl(): string
    {
        $host = sprintf('%s://%s',
            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http'),
            $_SERVER['HTTP_HOST']
        );

        return $host;

    }

    public function protocalVersion(): string
    {
        return $_SERVER['SERVER_PROTOCOL'] ?? 'HTTP/1.1';
    }

    protected function loadHeaders()
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $name = str_replace('_', '-', ucwords(strtolower(substr($key, 5)), '_'));
                $headers[$name] = $value;
            }
        }

        return $headers;
    }

    public function methodIs(string $method): bool
    {
        return $this->getMethod() == strtoupper($method);
    }
}
