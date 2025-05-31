<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOrder;

use Edgaras\WhatToDo\Script\SeederInterface;

class SecondSeeder implements SeederInterface
{
    public static function dependencies(): array
    {
        return [FirstSeeder::class];
    }

    public function seed(): void
    {

    }
}