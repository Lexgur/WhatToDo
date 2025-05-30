<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Controller;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\TemplateProvider;

#[Path('/abstract-controller')]
abstract class AbstractController
{
    private TemplateProvider $templateProvider;

    public function __construct(TemplateProvider $templateProvider)
    {
        $this->templateProvider = $templateProvider;
    }

    protected function isPostRequest(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /** @param array<string, mixed> $params */
    protected function render(string $template, array $params = [], int $statusCode = 200): string
    {
        http_response_code($statusCode);
        header('Content-Type: text/html; charset=UTF-8');

        return $this->templateProvider->get()->render($template, $params);
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