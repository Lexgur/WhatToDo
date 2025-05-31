<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Container;
use Edgaras\WhatToDo\Script\CreateDatabaseScript;
use Edgaras\WhatToDo\Script\DeleteDatabaseScript;
use Edgaras\WhatToDo\Script\RunMigrationsScript;
use Edgaras\WhatToDo\Script\RunSeedersScript;
use Edgaras\WhatToDo\Seeder\FillSportsTableDataSeeder;
use PHPUnit\Framework\Attributes\DataProvider;

class SportControllerWebTest extends WebTestCase
{

    private Container $container;

    public function setUp(): void
    {
        $_ENV['IS_WEB_TEST'] = 'true';

        // Clear DB and registry files before each test
        $paths = [
            __DIR__ . '/../tmp/test/WhatToDo.sqlite',
            __DIR__ . '/../tmp/test/testmigrations.json',
            __DIR__ . '/../tmp/test/testseeders.json'
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $config = require __DIR__ . '/../config.php';
        $this->container = new Container($config);

        $this->container->get(RunMigrationsScript::class)->run();
        $this->container->get(RunSeedersScript::class)->run();

        parent::setUp();
    }

    /**
     * @param array<int, array<string, mixed>> $content
     */
    #[DataProvider('provideTestControllerReturnsCorrectDataData')]
    public function testControllerReturnsCorrectData(string $url, array $content): void
    {
        [$output, $statusCode] = $this->request('GET', $url);

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(json_encode($content), $output);
        $this->assertEquals($content, json_decode($output, true));
    }

    /**
     * @return list<array{0: string, 1: list<array<string, mixed>>}>
     */
    public static function provideTestControllerReturnsCorrectDataData(): array
    {
        return [
            [
                '/sportas', [
                [
                    'name' => 'Basketball Court',
                    'type' => 'public',
                    'kind' => 'court',
                    'price' => 20.50,
                    'rating' => 4,
                    'address' => [
                        'street' => 'Sports St. 1',
                        'postal_code' => '12345',
                        'city' => 'Vilnius',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 54.687157,
                        'long' => 25.279652
                    ],
                    'date' => '2025-06-01'
                ],
                [
                    'name' => 'Swimming Pool',
                    'type' => 'public',
                    'kind' => 'pool',
                    'price' => 15.00,
                    'rating' => 5,
                    'address' => [
                        'street' => 'Aqua St. 5',
                        'postal_code' => '54321',
                        'city' => 'Kaunas',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 54.898521,
                        'long' => 23.903597
                    ],
                    'date' => '2025-06-01'
                ],
                [
                    'name' => 'Tennis Club',
                    'type' => 'private',
                    'kind' => 'court',
                    'price' => 30.00,
                    'rating' => 4,
                    'address' => [
                        'street' => 'Racket Rd. 10',
                        'postal_code' => '23456',
                        'city' => 'Klaipėda',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 55.7033,
                        'long' => 21.1443
                    ],
                    'date' => '2025-06-02'
                ],
                [
                    'name' => 'Football Stadium',
                    'type' => 'public',
                    'kind' => 'field',
                    'price' => 25.00,
                    'rating' => 5,
                    'address' => [
                        'street' => 'Goal St. 7',
                        'postal_code' => '34567',
                        'city' => 'Šiauliai',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 55.933333,
                        'long' => 23.316667
                    ],
                    'date' => '2025-06-03'
                ]
            ]],
            [
                '/sportas?city=Vilnius',
                [
                    [
                        'name' => 'Basketball Court',
                        'type' => 'public',
                        'kind' => 'court',
                        'price' => 20.50,
                        'rating' => 4,
                        'address' => [
                            'street' => 'Sports St. 1',
                            'postal_code' => '12345',
                            'city' => 'Vilnius',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 54.687157,
                            'long' => 25.279652
                        ],
                        'date' => '2025-06-01'
                    ]
                ]
            ],
            [
                '/sportas?type=public&kind=pool',
                [
                    [
                        'name' => 'Swimming Pool',
                        'type' => 'public',
                        'kind' => 'pool',
                        'price' => 15.00,
                        'rating' => 5,
                        'address' => [
                            'street' => 'Aqua St. 5',
                            'postal_code' => '54321',
                            'city' => 'Kaunas',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 54.898521,
                            'long' => 23.903597
                        ],
                        'date' => '2025-06-01'
                    ]
                ]
            ]
        ];
    }
}