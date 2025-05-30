<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Controller\SportController;
use Edgaras\WhatToDo\Exception\FilePathReadException;
use Edgaras\WhatToDo\Exception\NotFoundException;
use Edgaras\WhatToDo\Exception\RegisterControllerException;
use Edgaras\WhatToDo\Router;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    private string $filesystem;

    /**
     * @throws NotFoundException
     */
    protected function setUp(): void
    {
        $controllerDir = __DIR__ . '/../src/Controller';
        $this->filesystem = __DIR__ . '/../tmp/test';

        if (!is_dir($this->filesystem . '/RouterTest')) {
            mkdir($this->filesystem . '/RouterTest', 0777, true);
        }
        chmod($this->filesystem . '/RouterTest', 0777);

        $this->router = new Router($controllerDir);
        $this->router->registerControllers();
    }

    /**
     * @throws NotFoundException
     */
    #[DataProvider('provideTestGetControllerData')]
    final public function testGetController(string $routePath, string $expectedController): void
    {
        $controller = $this->router->getController($routePath);
        $this->assertSame($expectedController, $controller);
    }

    #[DataProvider('provideTestGetControllerThrowsIncorrectRoutePathException')]
    final public function testGetControllerThrowsIncorrectRoutePathException(string $routePath, string $expectedController): void
    {
        $this->expectException(NotFoundException::class);

        $controller = $this->router->getController($routePath);
        $this->assertSame($expectedController, $controller);
    }

    final public function testIncorrectPathThrowsIncorrectRoutePathException(): void
    {
        $this->expectException(NotFoundException::class);

        $this->router->getController('/incorrect');
    }

    /**
     * @throws NotFoundException
     */
    final public function testGetFullClassName(): void
    {
        $filePath = __DIR__.'/../src/Controller/SportController.php';
        $result = $this->router->getFullClassName($filePath);

        $this->assertSame('Edgaras\WhatToDo\Controller\SportController', $result);
    }

    /**
     * @throws NotFoundException
     */
    public function testGetFullClassNameThrowsFilePathReadExceptionForEmptyFile(): void
    {
        $this->expectException(FilePathReadException::class);

        $emptyFilePath = $this->filesystem . '/EmptyFile.php';
        file_put_contents($emptyFilePath, '');

        $router = new Router(__DIR__ . '/../src/Controller');
        $router->getFullClassName($emptyFilePath);
    }


    public function testGetFullClassNameThrowsNotFoundExceptionForNoClass(): void
    {
        $this->expectException(NotFoundException::class);

        $dir = $this->filesystem . '/RouterTest';
        $filePath = $dir . '/NoClassFile.php';
        file_put_contents($filePath, "<?php\n\nnamespace Edgaras\\WhatToDo\\Controller;\n\ntrait NoClass{}\n");

        $router = new Router($dir);
        $router->getFullClassName($filePath);
    }

    final public function testRegisterControllers(): void
    {
        $routes = $this->router->getRoutes();

        $this->assertNotEmpty($routes);

        $expectedRoutes = [
            '/sportas',
        ];

        foreach ($expectedRoutes as $route) {
            $this->assertArrayHasKey($route, $routes);
        }
    }

    /**
     * @throws NotFoundException
     */
    public function testRegisterControllerThrowsExceptionFromInvalidClass(): void
    {
        $this->expectException(RegisterControllerException::class);

        $testControllerDir = $this->filesystem . '/RouterTest';
        file_put_contents($testControllerDir . '/InvalidController.php', "<?php\nnamespace Edgaras\\WhatToDo\\Controller;\nclass InvalidController {}");

        $router = new Router($testControllerDir);
        $router->registerControllers();
    }

    /** @return array<int, array<int,string>> */
    public static function provideTestGetControllerData(): array
    {
        return [
            ['/sportas', SportController::class],
        ];
    }

    /** @return array<int, array<int, string>> */
    public static function provideTestGetControllerThrowsIncorrectRoutePathException(): array
    {
        return [
            ['/incorrectPath', SportController::class],
        ];
    }
}
