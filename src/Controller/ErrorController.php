<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Controller;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Exception\BadRequestException;
use Edgaras\WhatToDo\Exception\ForbiddenException;
use Edgaras\WhatToDo\Exception\NotFoundException;
use Edgaras\WhatToDo\Exception\UnauthorizedException;

#[Path('/error')]
class ErrorController extends AbstractController
{
    public function __invoke(\Throwable $error): string
    {
        $params = match (true) {
            $error instanceof BadRequestException => [
                'code' => 400,
                'title' => 'Oops! Something went wrong',
                'message' => 'Please check your information and try again.',
            ],
            $error instanceof UnauthorizedException => [
                'code' => 401,
                'title' => 'Please sign in',
                'message' => 'You need to sign in to access this page.',
            ],
            $error instanceof ForbiddenException => [
                'code' => 403,
                'title' => 'Access restricted',
                'message' => 'You don\'t have permission to view this content.',
            ],
            $error instanceof NotFoundException => [
                'code' => 404,
                'title' => 'Page not found',
                'message' => 'We couldn\'t find what you\'re looking for.',
            ],
            default => [
                'code' => 500,
                'title' => 'We\'re having some trouble',
                'message' => 'Our team has been notified. Please try again later.',
            ],
        };
        return $this->render('error.html.twig', $params, $params['code']);
    }
}