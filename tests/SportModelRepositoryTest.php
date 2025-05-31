<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use PHPUnit\Framework\TestCase;
use Edgaras\WhatToDo\Container;
use Edgaras\WhatToDo\Repository\SportModelRepository;
use Edgaras\WhatToDo\Connection;
use Edgaras\WhatToDo\Model\Sport;

class SportModelRepositoryTest extends TestCase
{
    private Connection $database;
    private SportModelRepository $repository;

    public function setUp(): void
    {
        $config = require __DIR__ . '/../config.php';
        $container = new Container($config);
        $this->database = $container->get(Connection::class);

        $this->database->connect()->exec('DELETE FROM sports');

        $this->repository = $container->get(SportModelRepository::class);
    }

    private function createSampleSport(): Sport
    {
        return new Sport(
            null,
            'Football Match',
            'outdoor',
            'team',
            15.0,
            5,
            [
                'street' => '123 Sport St',
                'postal_code' => '12345',
                'city' => 'Sportstown',
                'country' => 'Sportland'
            ],
            [
                'lat' => 45.123,
                'long' => 23.456
            ],
            '2025-06-01'
        );
    }

    public function testInsertSportReturnsInsertedSport(): void
    {
        $sport = $this->createSampleSport();
        $insertedSport = $this->repository->save($sport);

        $this->assertNotNull($insertedSport->getId());
        $this->assertEquals($sport->getName(), $insertedSport->getName());
        $this->assertEquals($sport->getType(), $insertedSport->getType());
        $this->assertEquals($sport->getKind(), $insertedSport->getKind());
        $this->assertEquals($sport->getPrice(), $insertedSport->getPrice());
        $this->assertEquals($sport->getRating(), $insertedSport->getRating());
        $this->assertEquals($sport->getAddressCity(), $insertedSport->getAddressCity());
        $this->assertEquals($sport->getDate(), $insertedSport->getDate());
    }

    public function testFetchByIdReturnsSportWhenExists(): void
    {
        $sport = $this->createSampleSport();
        $insertedSport = $this->repository->save($sport);

        $fetchedSport = $this->repository->fetchById($insertedSport->getId());

        $this->assertNotNull($fetchedSport);
        $this->assertEquals($insertedSport->getId(), $fetchedSport->getId());
        $this->assertEquals($insertedSport->getName(), $fetchedSport->getName());
    }

    public function testFetchByIdReturnsNullWhenNotExists(): void
    {
        $fetchedSport = $this->repository->fetchById(999999);
        $this->assertNull($fetchedSport);
    }

    public function testUpdateSportSuccessfully(): void
    {
        $sport = $this->createSampleSport();
        $insertedSport = $this->repository->save($sport);

        $insertedSport->setName('Basketball Game');
        $insertedSport->setType('indoor');
        $insertedSport->setPrice(20.5);

        $updatedSport = $this->repository->save($insertedSport);

        $this->assertEquals($insertedSport->getId(), $updatedSport->getId());
        $this->assertEquals('Basketball Game', $updatedSport->getName());
        $this->assertEquals('indoor', $updatedSport->getType());
        $this->assertEquals(20.5, $updatedSport->getPrice());
    }

    public function testGetSportsReturnsAllWhenNoFilters(): void
    {
        $this->database->connect()->exec('DELETE FROM sports');
        $sport1 = $this->createSampleSport();
        $sport2 = new Sport(
            null,
            'Tennis Match',
            'outdoor',
            'single',
            10.0,
            4,
            [
                'street' => '456 Court Ave',
                'postal_code' => '67890',
                'city' => 'Sportstown',
                'country' => 'Sportland'
            ],
            [
                'lat' => 44.111,
                'long' => 24.222
            ],
            '2025-07-01'
        );

        $this->repository->save($sport1);
        $this->repository->save($sport2);

        $allSports = $this->repository->getSports();

        $this->assertCount(2, $allSports);
    }

    public function testGetSportsFiltersByCity(): void
    {
        $this->database->connect()->exec('DELETE FROM sports');
        $sport1 = $this->createSampleSport();
        $sport2 = new Sport(
            null,
            'Tennis Match',
            'outdoor',
            'single',
            10.0,
            4,
            [
                'street' => '456 Court Ave',
                'postal_code' => '67890',
                'city' => 'OtherCity',
                'country' => 'Sportland'
            ],
            [
                'lat' => 44.111,
                'long' => 24.222
            ],
            '2025-07-01'
        );

        $this->repository->save($sport1);
        $this->repository->save($sport2);

        $filteredSports = $this->repository->getSports(['city' => 'Sportstown']);
        $this->assertCount(1, $filteredSports);
        $this->assertEquals('Sportstown', $filteredSports[0]->getAddressCity());
    }

    public function testGetSportsFiltersByMultipleFilters(): void
    {
        $this->database->connect()->exec('DELETE FROM sports');
        $sport1 = $this->createSampleSport();
        $sport2 = new Sport(
            null,
            'Tennis Match',
            'indoor',
            'single',
            10.0,
            4,
            [
                'street' => '456 Court Ave',
                'postal_code' => '67890',
                'city' => 'Sportstown',
                'country' => 'Sportland'
            ],
            [
                'lat' => 44.111,
                'long' => 24.222
            ],
            '2025-07-01'
        );

        $this->repository->save($sport1);
        $this->repository->save($sport2);

        $filters = [
            'city' => 'Sportstown',
            'type' => 'indoor',
            'kind' => 'single',
            'date' => '2025-07-01',
        ];

        $filteredSports = $this->repository->getSports($filters);

        $this->assertCount(1, $filteredSports);
        $this->assertEquals('Tennis Match', $filteredSports[0]->getName());
    }
}
