<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Exception\CircularDependencyException;
use Edgaras\WhatToDo\TemplateProvider;
use Edgaras\WhatToDo\Container;
use PHPUnit\Framework\TestCase;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TemplateProviderTest extends TestCase
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws CircularDependencyException
     * @throws LoaderError
     */
    public function testContainerCreatesTwigEnvironmentAndRendersTemplate(): void
    {

        $config = require __DIR__ . '/../config.php';
        $config['templatePath'] = $config['root'] . '/tests/TemplateProviderTest/templates/';
        $container = new Container($config);
        $templateProvider = $container->get(TemplateProvider::class);

        $this->assertTrue($container->has(TemplateProvider::class));

        $this->assertInstanceOf(TemplateProvider::class, $templateProvider);

        $output = $templateProvider->get()->render('test.html.twig', ['name' => 'World']);

        $this->assertSame('Hello, World!', $output);
    } 
}