<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Controller;

use Edgaras\WhatToDo\Attribute\Path;

#[Path('/abstract')]
abstract class AbstractController
{
    protected function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Returns a JSON response with proper headers and status code.
     * @param array<string, mixed> $data
     */
    protected function json(array $data, int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function redirect(string $path): string
    {
        if (isset($_ENV['IS_WEB_TEST']) && $_ENV['IS_WEB_TEST'] === 'true') {
            return $path;
        }

        header('Location: ' . $path, true, 302);
        exit;
    }
}