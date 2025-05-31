<?php

namespace Edgaras\WhatToDo\Tests\ApplicationTest;

use Edgaras\WhatToDo\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Edgaras\WhatToDo\Attribute\Path;

#[Path('/test/json')]
class TestJsonResponseController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'success',
            'data' => ['message' => 'It works!']
        ], 200);
    }
}