<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Attribute\Path;
use PHPUnit\Framework\TestCase;

class PathAttributeTest extends TestCase
{
    public function testGetPath(): void
    {
        $class = new \ReflectionClass(SomethingCreateController::class);
        $attributes = $class->getAttributes(Path::class);
        $this->assertEquals('/something/create', $attributes[0]->newInstance()->getPath());
    }
}

#[Path('/something/create')]
class SomethingCreateController {}