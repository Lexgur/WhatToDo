<?php

namespace Edgaras\WhatToDo\Tests\ApplicationTest;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Controller\AbstractController;
use Edgaras\WhatToDo\Exception\NotFoundException;

#[Path('/test/notfound')]
class TestNotFoundExceptionController extends AbstractController
{
    public function __invoke(): string
    {
        throw new NotFoundException('Simulated notfound');
    }
}