<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Container;

class SportControllerWebTest extends WebTestCase
{

    private Container $container;

    public function setUp(): void
    {
        $_ENV['IS_WEB_TEST'] = 'true';

        $config = require __DIR__.'/../config.php';
        $this->container = new Container($config);

        parent::setUp();
    }

    public function testSportControllerPathReturnsCorrectStatusCode(): void
    {
        [$output, $statusCode] = $this->request('GET', '/sportas');
        $json = json_decode($output, true);

        $this->assertEquals(200, $statusCode);
    }
}
