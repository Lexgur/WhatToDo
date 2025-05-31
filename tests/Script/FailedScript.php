<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script;

use Edgaras\WhatToDo\Script\ScriptInterface;

class FailedScript implements ScriptInterface
{
    public function run(): int
    {
        echo 'Not so hello World!';

        return 1;
    }
}
