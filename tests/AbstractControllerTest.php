<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Controller\AbstractController;
use PHPUnit\Framework\TestCase;

class AbstractControllerTest extends TestCase
{
    private AbstractController $controller;

    protected function setUp(): void
    {
        $this->controller = new class extends AbstractController {
            public function callIsPostRequest(): bool
            {
                return $this->isPostRequest();
            }

            public function callJson(array $data, int $statusCode = 200): string
            {
                return $this->json($data, $statusCode);
            }
        };
    }

    public function testIsPostRequestReturnsTrueWhenPostMethod(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue($this->controller->callIsPostRequest());
    }

    public function testIsPostRequestReturnsFalseWhenNotPost(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->controller->callIsPostRequest());
    }

    public function testJsonReturnsCorrectJson(): void
    {
        $data = ['foo' => 'bar'];
        $json = $this->controller->callJson($data, 201);
        $this->assertSame(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $json);
    }

    public function testRedirectReturnsPathInTestMode(): void
    {
        $_ENV['IS_WEB_TEST'] = 'true';
        $path = '/some-path';
        $result = $this->controller->redirect($path);
        $this->assertSame($path, $result);
        unset($_ENV['IS_WEB_TEST']);
    }
}
