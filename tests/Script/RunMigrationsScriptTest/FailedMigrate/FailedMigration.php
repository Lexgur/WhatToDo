<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script\RunMigrationsScriptTest\FailedMigrate;

use Edgaras\WhatToDo\Script\MigrationInterface;

class FailedMigration implements MigrationInterface {

    public function order(): int
    {
        return 2;
    }

    public function migrate(): void
    {
        throw new \RuntimeException(sprintf('%s has failed', static::class));
    }
}