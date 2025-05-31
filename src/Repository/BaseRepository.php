<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Repository;

use Edgaras\WhatToDo\Connection;

class BaseRepository
{
    protected Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
}