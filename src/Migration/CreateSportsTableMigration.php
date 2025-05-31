<?php 

declare(strict_types=1);

namespace Edgaras\WhatToDo\Migration;

use Edgaras\WhatToDo\Script\MigrationInterface;
use Edgaras\WhatToDo\Connection;

class CreateSportsTableMigration implements MigrationInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function order(): int
    {
        return 1;
    }

    public function migrate(): void
    {
        echo static::class . PHP_EOL;
        $this->createTable();
    }

    private function createTable(): void
    {
        $database = $this->connection->connect();

        $database->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                email TEXT NOT NULL UNIQUE,
                username TEXT NOT NULL UNIQUE,
                password TEXT NOT NULL
            );
        ");
    }
}
