<?php

namespace Src;

use Error;

class Request
{
    protected array $body;
    public string $method;
    public array $headers;
    public string $uri;
    public function __construct()
    {
        $this->body = $_REQUEST;
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->headers = getallheaders() ?? [];

        // Получаем URI и убираем параметры запроса
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->uri = parse_url($requestUri, PHP_URL_PATH) ?? '/';

        // Нормализуем URI (убираем дублирующиеся слеши)
        $this->uri = rtrim($this->uri, '/');
        if ($this->uri === '') {
            $this->uri = '/';
        }
    }
    public function all(): array
    {
        return $this->body + $this->files();
    }

    public function set($field, $value):void
    {
        $this->body[$field] = $value;
    }

    public function get($field)
    {
        return $this->body[$field];
    }

    public function files(): array
    {
        return $_FILES;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->body)) {
            return $this->body[$key];
        }
        throw new Error('Accessing a non-existent property');
    }
}