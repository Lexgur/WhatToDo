<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo;

use Edgaras\WhatToDo\Controller\ErrorController;
use Edgaras\WhatToDo\Controller\SportController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            } else {
                $controllerClass = $this->router->getController($routePath);
                $controller = $this->container->get($controllerClass);
            }

            $response = $controller();

            if ($response instanceof JsonResponse) {
                $response->send();
                return;
            }

            http_response_code(200);
            header('Content-Type: text/plain');
            echo $response;

        } catch (\Throwable $error) {
            $errorController = $this->container->get(ErrorController::class);
            $response = $errorController($error);

            if ($response instanceof JsonResponse) {
                $response->send();
            }
        }
    }
}
