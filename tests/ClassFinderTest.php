<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\ClassFinder;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesExtending\AbstractExample;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesExtending\AbstractInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesExtending\AbstractNotExtendedExample;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesExtending\AbstractNotExtendedInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesExtending\FirstInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\AndAnotherClassInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\AnotherExampleInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\ExampleInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\FirstClass;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\NotUsedClassInterface;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\SecondClass;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesImplementing\ThirdClass;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstEnum;
use Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ClassFinderTest extends TestCase
{
    private ClassFinder $classFinder;

    protected function setUp(): void
    {
        $this->classFinder = new ClassFinder(__DIR__);
    }

    /** @param class-string[] $expected */
    #[DataProvider('provideTestFindClassesImplementingData')]
    public function testFindClassesImplementing(string $value, array $expected): void
    {
        $actual = $this->classFinder->findClassesImplementing($value);
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    /**
     * @return array<int, list<list<string>|string>>
     */
    public static function provideTestFindClassesImplementingData(): array
    {
        return [
            [
                ExampleInterface::class,
                [
                    FirstClass::class,
                ],
            ],
            [
                AnotherExampleInterface::class,
                [
                    SecondClass::class,
                    ThirdClass::class,
                ],
            ],
            [
                AndAnotherClassInterface::class,
                [
                    ThirdClass::class,
                ],
            ],
            [
                NotUsedClassInterface::class,
                [],
            ],
        ];
    }

    /** @param class-string[] $expected
     * @throws \ReflectionException
     */
    #[DataProvider('provideTestFindClassesExtendingData')]
    public function testFindClassesExtending(string $value, array $expected): void
    {
        $actual = $this->classFinder->findClassesExtending($value);
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    /** @return list<array{class-string, list<class-string>}> */
    public static function provideTestFindClassesExtendingData(): array
    {
        return [
            [
                AbstractExample::class,
                [
                    ClassFinderTest\FindClassesExtending\FirstClass::class,
                ]
            ],
            [
                AbstractNotExtendedExample::class,
                [],
            ],
            [
                AbstractInterface::class,
                [
                    FirstInterface::class,
                ]
            ],
            [
                AbstractNotExtendedInterface::class,
                [],
            ]
        ];
    }

    /** @param class-string[] $expected
     * @throws \ReflectionException
     */
    #[DataProvider('provideTestFindClassesInNamespaceData')]
    public function testFindClassesInNamespace(string $value, array $expected): void
    {
        $actual = $this->classFinder->findClassesInNamespace($value);
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetFullClassNameWithNonExistentPathReturnsNull(): void
    {
        $path = 'NoPathLikeThis';
        $result = $this->classFinder->getFullClassName($path);

        $this->assertNull($result);
    }

    public function testGetFullClassNameReturnsNullWhenNoClassFound(): void
    {
        $path = __DIR__ . '/ClassFinderBadTest/NoMatchFile.php';
        $result = $this->classFinder->getFullClassName($path);

        $this->assertNull($result);
    }

    /** @return  array<int, list<list<string>|string>> */
    public static function provideTestFindClassesInNamespaceData(): array
    {
        return [
            [
                "Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace",
                [
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstClass::class,
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstEnum::class,
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstInterface::class,
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstTrait::class,
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\SecondNamespace\FirstClass::class,
                    ClassFinderTest\FindClassesInNamespace\SecondNamespace\FirstClass::class,
                ],
            ],
            [
                "Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace\FirstNameSpace",
                [
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstClass::class,
                    FirstEnum::class,
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\FirstInterface::class,
                    FirstTrait::class,
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\SecondNamespace\FirstClass::class,
                ],
            ],
            [
                "Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace\FirstNameSpace\SecondNamespace",
                [
                    ClassFinderTest\FindClassesInNamespace\FirstNameSpace\SecondNamespace\FirstClass::class,
                ],
            ],
            [
                "Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace\SecondNamespace",
                [
                    ClassFinderTest\FindClassesInNamespace\SecondNamespace\FirstClass::class,
                ],
            ],
            [
                "Edgaras\WhatToDo\Tests\ClassFinderTest\FindClassesInNamespace\ThirdNamespace",
                [],
            ],
        ];
    }
}