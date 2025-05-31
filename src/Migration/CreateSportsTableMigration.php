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
        return 2; // Changed from 1 to avoid conflict with existing migration
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
            CREATE TABLE IF NOT EXISTS sports (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                type TEXT NOT NULL,
                kind TEXT NOT NULL,
                price REAL NOT NULL,
                rating INTEGER NOT NULL DEFAULT 0,
                
                address_street TEXT NOT NULL,
                address_postal_code TEXT NOT NULL,
                address_city TEXT NOT NULL,
                address_country TEXT NOT NULL,
                
                location_lat REAL NOT NULL,
                location_long REAL NOT NULL,
                
                date TEXT NOT NULL
            );
        ");
    }
}