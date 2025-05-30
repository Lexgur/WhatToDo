<?php

namespace Edgaras\WhatToDo\Tests\ApplicationTest;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Controller\AbstractController;
use Edgaras\WhatToDo\Exception\UnauthorizedException;

#[Path('/test/unauthorized')]
class TestUnauthorizedExceptionController extends AbstractController
{
    public function __invoke(): string
    {
        throw new UnauthorizedException('Simulated unauthorized');
    }
}