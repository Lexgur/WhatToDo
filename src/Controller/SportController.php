<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Controller;

use Edgaras\WhatToDo\Attribute\Path;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Path('/sportas')]
class SportController extends AbstractController
{
    private SportRepository $repository;

    public function __construct(SportRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): JsonResponse
    {
        $data = $this->repository->getSportData();

        return new JsonResponse($data);
    }
}
