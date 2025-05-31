<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOnce;

use Edgaras\WhatToDo\Script\SeederInterface;

class LastSeeder implements SeederInterface
{
    public static function dependencies(): array
    {
        return [FirstSeeder::class];
    }

    public function seed(): void
    {
    }
}