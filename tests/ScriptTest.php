<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Exception\IncorrectScriptNameException;
use Edgaras\WhatToDo\Exception\ScriptFailedToRunException;
use Edgaras\WhatToDo\Script;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase
{
    private Script $script;

    protected function setUp(): void
    {
        $this->script = new Script();
    }

    #[DataProvider('provideTestSuccessfulScriptData')]
    public function testSuccessfulScript(string $scriptClassName): void
    {
        $this->expectOutputString('Hello World!');

        $result = $this->script->run($scriptClassName);

        $this->assertEquals(0, $result);
    }

    /** @return array<array{string}> */
    public static function provideTestSuccessfulScriptData(): array
    {
        return [
            ['Edgaras\WhatToDo\Tests\Script\SuccessfulScript'],
            ['\Edgaras\WhatToDo\Tests\Script\SuccessfulScript'],
            ['Edgaras/WhatToDo/Tests/Script/SuccessfulScript'],
            ['/Edgaras/WhatToDo/Tests/Script/SuccessfulScript'],
        ];
    }

    #[DataProvider('provideTestFailedScriptData')]
    public function testFailedScript(string $scriptClassName): void
    {
        $this->expectOutputString('Not so hello World!');

        $result = $this->script->run($scriptClassName);

        $this->assertEquals(1, $result);
    }

    /** @return array<array{string}> */
    public static function provideTestFailedScriptData(): array
    {
        return [
            ['Edgaras\WhatToDo\Tests\Script\FailedScript'],
            ['\Edgaras\WhatToDo\Tests\Script\FailedScript'],
            ['Edgaras/WhatToDo/Tests/Script/FailedScript'],
            ['/Edgaras/WhatToDo/Tests/Script/FailedScript'],
        ];
    }

    #[DataProvider('provideTestScriptFailedToRunExceptionData')]
    public function testScriptFailedToRunException(string $scriptClassName): void
    {
        $this->expectException(IncorrectScriptNameException::class);

        $this->script->run($scriptClassName);
    }

    public function testScripFailedToRunExceptionIsThrownWithNonExistentScript(): void
    {
        $this->expectException(ScriptFailedToRunException::class);

        $scriptClassName = 'Edgaras/WhatToDo/Tests/Script/ScriptNotInstanceOfScriptInterface';
        $this->script->run($scriptClassName);
    }

    /** @return array<array{string}> */
    public static function provideTestScriptFailedToRunExceptionData(): array
    {
        return [
            ['EdgarasWhatToDoGainsTestsScriptFailedScript'],
            ['Edgaras/WhatToDo/Tests/Script//FailedScript'],
            ['//Edgaras/WhatToDo/Tests/Script/FailedScript'],
            ['/\Edgaras/WhatToDo\\Tests/Script\\FailedScript'],
        ];
    }
}