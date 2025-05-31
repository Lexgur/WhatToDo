<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests\Script;

use Edgaras\WhatToDo\Container;
use Edgaras\WhatToDo\Script\RunSeedersScript;
use Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOrder\FirstSeeder;
use Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOrder\LastSeeder;
use Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOrder\SecondSeeder;
use Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\RunOrder\ThirdSeeder;
use Edgaras\WhatToDo\Tests\Script\RunSeedersScriptTest\FailedSeeder\FailedSeeder;
use PHPUnit\Framework\TestCase;

class RunSeedersScriptTest extends TestCase
{
    /** @var array<string>  */
    private array $testConfig;
    private string $seededRegistryPath;

    public function setUp(): void
    {
        $this->testConfig = require __DIR__ . '/../../config.php';
        $this->seededRegistryPath = uniqid('', true);
        mkdir(str_replace($this->seededRegistryPath . '.json', '', $this->getSeededRegistryPath()), recursive: true);
    }

    public function tearDown(): void
    {
        unlink($this->getSeededRegistryPath());
    }

    public function testRunOrder(): void
    {
        $runSeedersScript = $this->getRunSeedersScript(__DIR__ . '/RunSeedersScriptTest/RunOrder');

        $this->expectOutputString(
            FirstSeeder::class . PHP_EOL
            . SecondSeeder::class . PHP_EOL
            . ThirdSeeder::class . PHP_EOL
            . LastSeeder::class . PHP_EOL
        );
        $this->assertEquals(0, $runSeedersScript->run());
    }

    public function testFailedSeeder(): void
    {
        $runSeedersScript = $this->getRunSeedersScript(__DIR__ . '/RunSeedersScriptTest/FailedSeeder');

        $this->expectOutputString(
            RunSeedersScriptTest\FailedSeeder\FirstSeeder::class . PHP_EOL
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('%s has failed', FailedSeeder::class));

        $runSeedersScript->run();
    }

    public function testRunOnce(): void
    {
        $runSeedersScript = $this->getRunSeedersScript(__DIR__ . '/RunSeedersScriptTest/RunOnce');

        $this->expectOutputString(
            RunSeedersScriptTest\RunOnce\FirstSeeder::class . PHP_EOL
            . RunSeedersScriptTest\RunOnce\LastSeeder::class . PHP_EOL
            . "No pending seeders found." . PHP_EOL
        );

        $this->assertEquals(0, $runSeedersScript->run());
        $this->assertEquals(1, $runSeedersScript->run());
    }

    public function testEmptyFolderReturnsEmptyArray(): void
    {
        $runSeedersScript = $this->getRunSeedersScript(__DIR__ . '/RunSeedersScriptTest/NoSeeders');
        file_put_contents($this->getSeededRegistryPath(), '');

        $this->expectOutputString("No pending seeders found." . PHP_EOL);
        $this->assertEquals(1, $runSeedersScript->run());
    }

    public function testRegistryFileWithInvalidJsonReturnsEmptyArray(): void
    {
        file_put_contents($this->getSeededRegistryPath(), '{invalid json');

        $runSeedersScript = $this->getRunSeedersScript(__DIR__ . '/RunSeedersScriptTest/NoSeeders');

        $this->expectOutputString("No pending seeders found." . PHP_EOL);
        $this->assertEquals(1, $runSeedersScript->run());
    }

    public function testRegistryFileWithNonArrayJsonReturnsEmptyArray(): void
    {
        file_put_contents($this->getSeededRegistryPath(), json_encode("this is a string"));

        $runSeedersScript = $this->getRunSeedersScript(__DIR__ . '/RunSeedersScriptTest/NoSeeders');

        $this->expectOutputString("No pending seeders found." . PHP_EOL);
        $this->assertEquals(1, $runSeedersScript->run());
    }

    private function getRunSeedersScript(string $directory): RunSeedersScript
    {
        $container = new Container(
            [
                'seedersDirectory' => $directory,
                'seededRegistryPath' => $this->getSeededRegistryPath(),
            ]
        );

        return $container->get(RunSeedersScript::class);
    }

    private function getSeededRegistryPath(): string
    {
        $testClass = self::class;
        $test = explode("\\", $testClass);
        $testCase = end($test);

        return $this->testConfig['filesystem'] . '/' . $testCase . '/' . $this->name() . '/' . $this->seededRegistryPath . '.json';
    }
}
