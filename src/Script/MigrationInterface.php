<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Script;

interface MigrationInterface
{
    public function order(): int;

    public function migrate(): void;
}