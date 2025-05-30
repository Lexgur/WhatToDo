<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo;

use Edgaras\WhatToDo\Controller\ErrorController;
use Edgaras\WhatToDo\Controller\SportController;

class Application
{
    private Container $container;

    /** @var array<string> */
    private array $config;

    private Router $router;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';
        $this->container = new Container($this->config);
        $this->router = $this->container->get(Router::class);
    }

    public function run(): void
    {
        try {
            $this->router->registerControllers();
            $routePath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            if (empty($routePath) || $routePath === '/') {
                $controller = $this->container->get(SportController::class);
                $params = [];
            } else {
                $controllerClass = $this->router->getController($routePath);
                $params = $this->router->getParameters($routePath);
                $controller = $this->container->get($controllerClass);
            }
            http_response_code(200);
            print call_user_func_array($controller, $params);

        } catch (\Throwable $error) {
            $errorController = $this->container->get(ErrorController::class);
            print $errorController($error);
        }
    }
}