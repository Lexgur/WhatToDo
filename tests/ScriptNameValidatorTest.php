<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Exception\IncorrectScriptNameException;
use Edgaras\WhatToDo\Validation\ScriptNameValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ScriptNameValidatorTest extends TestCase {

    private ScriptNameValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new ScriptNameValidator();
    }

    #[DataProvider('provideTestNamespaceRegexData')]
    public function testNamespaceRegex(string $scriptClassName, int $expected): void
    {
        $actual = $this->validator->validate($scriptClassName);

        $this->assertEquals($expected, $actual);
    }

    #[DataProvider('provideInvalidNamespaceData')]
    public function testInvalidNamespacesThrowException(string $scriptClassName): void
    {
        $this->expectException(IncorrectScriptNameException::class);
        $this->validator->validate($scriptClassName);
    }

    /** @return array<array{string, int}> */
    public static function provideTestNamespaceRegexData(): array
    {
        return [
            ["Edgaras\WhatToDo\Tests\Script\FailedScript", 1],
            ["\Edgaras\WhatToDo\Tests\Script\FailedScript", 1],
            ['Edgaras\\WhatToDo\\Tests\\Script\\FailedScript', 1],
            ['\\Edgaras\\WhatToDo\\Tests\\Script\\FailedScript', 1],
            ['Edgaras/WhatToDo/Tests/Script/FailedScript', 1],
            ['/Edgaras/WhatToDo/Tests/Script/FailedScript', 1],
        ];
    }

    /**
     * @return array<int, array{string}>
     */
    public static function provideInvalidNamespaceData(): array
    {
        return [
            [''],
            ['123Invalid\Namespace'],
            ['InvalidNamespace!'],
            ['Namespace with spaces'],
            ['\\'],
            ['/'],
            ['\\\\invalid\\path\\'],
            ['Invalid/Namespace/'],
        ];
    }
}
