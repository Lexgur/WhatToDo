<?php

namespace Edgaras\WhatToDo\Seeder;

use Edgaras\WhatToDo\Connection;
use Edgaras\WhatToDo\Migration\CreateSportsTableMigration;
use Edgaras\WhatToDo\Model\Sport;
use Edgaras\WhatToDo\Repository\SportModelRepository;
use Edgaras\WhatToDo\Script\SeederInterface;

class FillSportsTableDataSeeder implements SeederInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public static function dependencies(): array
    {
        return [];
    }

    public function seed(): void
    {
        $sports = [
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
        ];

        $sportsRepository = new SportModelRepository($this->connection);

        foreach ($sports as $sportData) {
            $sport = Sport::create($sportData);
            $sportsRepository->save($sport);
        }

        echo "Sports facilities have been inserted into the table." . PHP_EOL;
    }
}