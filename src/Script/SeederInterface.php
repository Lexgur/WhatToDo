<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Script;

interface SeederInterface
{
    /**
     * Returns an array of class names this seeder depends on.
     *
     * @return string[]
     */
    public static function dependencies(): array;

    public function seed(): void;
}