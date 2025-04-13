<?php

namespace App\Http;

class Request
{
    private array $get;
    private array $post;
    private array $files;

    public function __construct(array $get = [], array $post = [], array $files = [])
    {
        $this->get = $get;
        $this->post = $post;
        $this->files = $files;
    }

    public function get(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    public function all(): array
    {
        return [
            "get" => $this->get,
            "post" => $this->post,
            "files" => $this->files
        ];
    }
}
