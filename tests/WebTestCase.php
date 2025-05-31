<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use PHPUnit\Framework\TestCase;
use Edgaras\WhatToDo\Application;

abstract class WebTestCase extends TestCase
{
    private Application $app;

    public function setUp(): void
    {
        $this->app = new Application();
    }

    /**
     * @param array<string, mixed> $data
     * @return array{0: string, 1: int}
     */
    public function request(string $method, string $url, array $data = []): array
    {
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
        $_SERVER['REQUEST_URI'] = $url;

        if ($method === 'POST') {
            $_POST = $data;
        } else {
            $_GET = $data;
        }

        ob_start();
        $this->app->run();
        $output = ob_get_clean();

        $statusCode = http_response_code();

        return [$output, $statusCode];
    }
}