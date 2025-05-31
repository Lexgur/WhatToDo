<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script\RunMigrationsScriptTest\RunOnce;

use Edgaras\WhatToDo\Script\MigrationInterface;

class LastMigration implements MigrationInterface
{
    public function order(): int
    {
        return 2;
    }

    public function migrate(): void
    {
        echo static::class . PHP_EOL;
    }
}