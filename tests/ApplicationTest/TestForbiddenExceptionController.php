<?php

namespace Edgaras\WhatToDo\Tests\ApplicationTest;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Controller\AbstractController;
use Edgaras\WhatToDo\Exception\ForbiddenException;

#[Path('/test/forbidden')]
class TestForbiddenExceptionController extends AbstractController
{
    public function __invoke(): string
    {
        throw new ForbiddenException('Simulated forbidden');
    }
}