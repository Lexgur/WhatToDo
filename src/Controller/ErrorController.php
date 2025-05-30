<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Controller;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Exception\BadRequestException;
use Edgaras\WhatToDo\Exception\ForbiddenException;
use Edgaras\WhatToDo\Exception\NotFoundException;
use Edgaras\WhatToDo\Exception\UnauthorizedException;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Path('/api/error')]
class ErrorController extends AbstractController
{
    public function __invoke(\Throwable $error): JsonResponse
    {
        $errorData = match (true) {
            $error instanceof BadRequestException => [
                'status' => 400,
                'error' => 'Bad Request',
                'message' => 'Please check your information and try again.',
            ],
            $error instanceof UnauthorizedException => [
                'status' => 401,
                'error' => 'Unauthorized',
                'message' => 'Authentication required to access this resource.',
            ],
            $error instanceof ForbiddenException => [
                'status' => 403,
                'error' => 'Forbidden',
                'message' => 'You do not have permission to access this resource.',
            ],
            $error instanceof NotFoundException => [
                'status' => 404,
                'error' => 'Not Found',
                'message' => 'The requested resource could not be found.',
            ],
            default => [
                'status' => 500,
                'error' => 'Internal Server Error',
                'message' => 'An unexpected error occurred. Please try again later.',
            ],
        };

        return new JsonResponse($errorData, $errorData['status']);
    }
}