<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\FailedSeeder;

use Edgaras\WhatToDo\Script\SeederInterface;

class FailedSeeder implements SeederInterface
{
    public static function dependencies(): array
    {
        return [FirstSeeder::class];
    }

    public function seed(): void
    {
        throw new \RuntimeException(sprintf('%s has failed', static::class));
    }
}