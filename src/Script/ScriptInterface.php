<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Script;

interface ScriptInterface
{
    public function run(): int;
}
