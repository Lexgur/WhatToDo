<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOrder;

use Edgaras\WhatToDo\Script\SeederInterface;

class FirstSeeder implements SeederInterface
{
    public static function dependencies(): array
    {
        return [];
    }

    public function seed(): void
    {
    }
}