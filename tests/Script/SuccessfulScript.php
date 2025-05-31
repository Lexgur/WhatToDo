<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script;

use Edgaras\WhatToDo\Script\ScriptInterface;

class SuccessfulScript implements ScriptInterface
{
    public function run(): int
    {
        echo 'Hello World!';

        return 0;
    }

}