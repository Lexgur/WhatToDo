<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Controller;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Repository\SportModelRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


#[Path('/sportas')]
class SportController extends AbstractController
{
    private SportModelRepository $repository;

    public function __construct(SportModelRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function __invoke(array $filters): JsonResponse
    {
        $sports = $this->repository->getSports($filters);

        return new JsonResponse($sports);
    }
}
