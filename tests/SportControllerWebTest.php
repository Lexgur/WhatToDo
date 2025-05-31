<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Container;
use PHPUnit\Framework\Attributes\DataProvider;
use function PHPUnit\Framework\assertEquals;

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
            ['/sportas', [
                [
                    'name' => 'Name',
                    'type' => 'private',
                    'kind' => 'not really',
                    'price' => 100.01,
                    'rating' => 0,
                    'address' => [
                        'street' => 'Not gonna tell ya st. 5 - 100',
                        'postal_code' => '12345',
                        'city' => 'Panevezis',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 56.123456789,
                        'long' => 56.123456789
                    ],
                    'date' => '2025-05-31'
                ],
                [
                    'name' => 'Name 2',
                    'type' => 'private',
                    'kind' => 'not really',
                    'price' => 100.01,
                    'rating' => 0,
                    'address' => [
                        'street' => 'Not gonna tell ya st. 10 - 200',
                        'postal_code' => '12346',
                        'city' => 'Vilnius',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 54.687157,
                        'long' => 25.279652
                    ],
                    'date' => '2025-05-31'
                ],
                [
                    'name' => 'Name 3',
                    'type' => 'private',
                    'kind' => 'not really',
                    'price' => 100.01,
                    'rating' => 0,
                    'address' => [
                        'street' => 'Not gonna tell ya st. 15 - 300',
                        'postal_code' => '12347',
                        'city' => 'Kaunas',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 54.898521,
                        'long' => 23.903597
                    ],
                    'date' => '2025-05-31'
                ],
                [
                    'name' => 'Name 4',
                    'type' => 'private',
                    'kind' => 'not really',
                    'price' => 100.01,
                    'rating' => 0,
                    'address' => [
                        'street' => 'Not gonna tell ya st. 20 - 400',
                        'postal_code' => '12348',
                        'city' => 'Šiauliai',
                        'country' => 'Lithuania'
                    ],
                    'location' => [
                        'lat' => 55.933333,
                        'long' => 23.316667
                    ],
                    'date' => '2025-05-31'
                ],
            ]],

            [
                '/sportas?city=Vilnius&type=private&kind=not+really&date=2025-05-31',
                [
                    [
                        'name' => 'Name 2',
                        'type' => 'private',
                        'kind' => 'not really',
                        'price' => 100.01,
                        'rating' => 0,
                        'address' => [
                            'street' => 'Not gonna tell ya st. 10 - 200',
                            'postal_code' => '12346',
                            'city' => 'Vilnius',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 54.687157,
                            'long' => 25.279652
                        ],
                        'date' => '2025-05-31'
                    ],
                ],
            ],

            [
                '/sportas?city=Kaunas&date=2025-05-31',
                [
                    [
                        'name' => 'Name 3',
                        'type' => 'private',
                        'kind' => 'not really',
                        'price' => 100.01,
                        'rating' => 0,
                        'address' => [
                            'street' => 'Not gonna tell ya st. 15 - 300',
                            'postal_code' => '12347',
                            'city' => 'Kaunas',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 54.898521,
                            'long' => 23.903597
                        ],
                        'date' => '2025-05-31'
                    ],
                ],
            ],

            [
                '/sportas?type=private',
                [
                    [
                        'name' => 'Name',
                        'type' => 'private',
                        'kind' => 'not really',
                        'price' => 100.01,
                        'rating' => 0,
                        'address' => [
                            'street' => 'Not gonna tell ya st. 5 - 100',
                            'postal_code' => '12345',
                            'city' => 'Panevezis',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 56.123456789,
                            'long' => 56.123456789
                        ],
                        'date' => '2025-05-31'
                    ],
                    [
                        'name' => 'Name 2',
                        'type' => 'private',
                        'kind' => 'not really',
                        'price' => 100.01,
                        'rating' => 0,
                        'address' => [
                            'street' => 'Not gonna tell ya st. 10 - 200',
                            'postal_code' => '12346',
                            'city' => 'Vilnius',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 54.687157,
                            'long' => 25.279652
                        ],
                        'date' => '2025-05-31'
                    ],
                    [
                        'name' => 'Name 3',
                        'type' => 'private',
                        'kind' => 'not really',
                        'price' => 100.01,
                        'rating' => 0,
                        'address' => [
                            'street' => 'Not gonna tell ya st. 15 - 300',
                            'postal_code' => '12347',
                            'city' => 'Kaunas',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 54.898521,
                            'long' => 23.903597
                        ],
                        'date' => '2025-05-31'
                    ],
                    [
                        'name' => 'Name 4',
                        'type' => 'private',
                        'kind' => 'not really',
                        'price' => 100.01,
                        'rating' => 0,
                        'address' => [
                            'street' => 'Not gonna tell ya st. 20 - 400',
                            'postal_code' => '12348',
                            'city' => 'Šiauliai',
                            'country' => 'Lithuania'
                        ],
                        'location' => [
                            'lat' => 55.933333,
                            'long' => 23.316667
                        ],
                        'date' => '2025-05-31'
                    ],
                ],
            ],
        ];
    }
}