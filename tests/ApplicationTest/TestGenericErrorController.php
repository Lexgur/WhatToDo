<?php

namespace Edgaras\WhatToDo\Tests\ApplicationTest;

use Edgaras\WhatToDo\Attribute\Path;
use Edgaras\WhatToDo\Controller\AbstractController;

#[Path('/test/generic-error')]
class TestGenericErrorController extends AbstractController
{
    public function __invoke(): string
    {
        throw new \RuntimeException('Something unexpected happened');
    }
}