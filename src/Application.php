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

            $fullUri = $_SERVER['REQUEST_URI'];
            $routePath = parse_url($fullUri, PHP_URL_PATH);

            if (empty($routePath) || $routePath === '/') {
                $controller = $this->container->get(SportController::class);
                $queryParams = [];
            } else {
                $controllerClass = $this->router->getController($routePath);
                $controller = $this->container->get($controllerClass);

                $queryString = parse_url($fullUri, PHP_URL_QUERY);
                $queryParams = [];
                if ($queryString !== null) {
                    parse_str($queryString, $queryParams);
                }
            }

            $response = call_user_func($controller, $queryParams);

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
                return;
            }

            http_response_code(500);
            header('Content-Type: text/plain');
            echo $response;
        }
    }
}