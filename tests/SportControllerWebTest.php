<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Container;
use PHPUnit\Framework\Attributes\DataProvider;

class SportControllerWebTest extends WebTestCase
{

    private Container $container;

    public function setUp(): void
    {
        $_ENV['IS_WEB_TEST'] = 'true';

        $config = require __DIR__.'/../config.php';
        $this->container = new Container($config);

        parent::setUp();
    }

    #[DataProvider('provideTestControllerReturnsCorrectDataData')]
    public function testControllerReturnsCorrectData(string $url, array $content): void
    {
        [$output, $statusCode] = $this->request('GET', $url);

        $this->assertEquals(200, $statusCode);
        $this->assertEquals(json_encode($content), $output);
        $this->assertEquals($content, json_decode($output, true));
    }

    public static function provideTestControllerReturnsCorrectDataData(): array
    {
        return [
            ['/sports', [
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
                ]
            ]],
            [
                '/sports?city=Vilnius',
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
                '/sports?type=public&kind=pool',
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