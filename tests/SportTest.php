<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Tests;

use Edgaras\WhatToDo\Model\Sport;
use PHPUnit\Framework\TestCase;
use TypeError;

class SportTest extends TestCase
{
    private array $initialData;

    protected function setUp(): void
    {
        $this->initialData = [
            'id' => 1,
            'name' => 'Basketball Court',
            'type' => 'public',
            'kind' => 'court',
            'price' => 20.5,
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
        ];
    }

    public function testConstructorSetsAllProperties(): void
    {
        $sport = new Sport(
            $this->initialData['id'],
            $this->initialData['name'],
            $this->initialData['type'],
            $this->initialData['kind'],
            $this->initialData['price'],
            $this->initialData['rating'],
            $this->initialData['address'],
            $this->initialData['location'],
            $this->initialData['date']
        );

        $this->assertEquals(1, $sport->getId());
        $this->assertEquals('Basketball Court', $sport->getName());
        $this->assertEquals('public', $sport->getType());
        $this->assertEquals('court', $sport->getKind());
        $this->assertEquals(20.5, $sport->getPrice());
        $this->assertEquals(4, $sport->getRating());
        $this->assertEquals('Sports St. 1', $sport->getAddressStreet());
        $this->assertEquals('12345', $sport->getAddressPostalCode());
        $this->assertEquals('Vilnius', $sport->getAddressCity());
        $this->assertEquals('Lithuania', $sport->getAddressCountry());
        $this->assertEquals(54.687157, $sport->getLocationLat());
        $this->assertEquals(25.279652, $sport->getLocationLong());
        $this->assertEquals('2025-06-01', $sport->getDate());
    }

    public function testSettersUpdateValuesCorrectly(): void
    {
        $sport = Sport::create($this->initialData);

        $sport->setName('Tennis Club');
        $sport->setType('private');
        $sport->setKind('court');
        $sport->setPrice(30.0);
        $sport->setRating(5);
        $sport->setAddressStreet('New Street');
        $sport->setAddressPostalCode('99999');
        $sport->setAddressCity('Klaipėda');
        $sport->setAddressCountry('Latvia');
        $sport->setLocationLat(55.0);
        $sport->setLocationLong(24.0);
        $sport->setDate('2025-07-01');

        $this->assertEquals('Tennis Club', $sport->getName());
        $this->assertEquals('private', $sport->getType());
        $this->assertEquals('court', $sport->getKind());
        $this->assertEquals(30.0, $sport->getPrice());
        $this->assertEquals(5, $sport->getRating());
        $this->assertEquals('New Street', $sport->getAddressStreet());
        $this->assertEquals('99999', $sport->getAddressPostalCode());
        $this->assertEquals('Klaipėda', $sport->getAddressCity());
        $this->assertEquals('Latvia', $sport->getAddressCountry());
        $this->assertEquals(55.0, $sport->getLocationLat());
        $this->assertEquals(24.0, $sport->getLocationLong());
        $this->assertEquals('2025-07-01', $sport->getDate());
    }

    public function testSetAddressAndSetLocationMethods(): void
    {
        $sport = Sport::create($this->initialData);

        $newAddress = [
            'street' => 'New Ave 10',
            'postal_code' => '99999',
            'city' => 'Panevėžys',
            'country' => 'Lithuania'
        ];

        $newLocation = [
            'lat' => 55.7333,
            'long' => 24.3500
        ];

        $sport->setAddress($newAddress);
        $sport->setLocation($newLocation);

        $this->assertEquals($newAddress, $sport->getAddress());
        $this->assertEquals('New Ave 10', $sport->getAddressStreet());
        $this->assertEquals('99999', $sport->getAddressPostalCode());
        $this->assertEquals('Panevėžys', $sport->getAddressCity());
        $this->assertEquals('Lithuania', $sport->getAddressCountry());

        $this->assertEquals($newLocation, $sport->getLocation());
        $this->assertEquals(55.7333, $sport->getLocationLat());
        $this->assertEquals(24.3500, $sport->getLocationLong());
    }

    public function testJsonSerializeReturnsExpectedArray(): void
    {
        $sport = Sport::create($this->initialData);
        $expected = [
            'name' => 'Basketball Court',
            'type' => 'public',
            'kind' => 'court',
            'price' => 20.5,
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
        ];

        $this->assertEquals($expected, $sport->jsonSerialize());
    }

    public function testCreateFactoryCreatesSportInstance(): void
    {
        $sport = Sport::create($this->initialData);

        $this->assertEquals('Basketball Court', $sport->getName());
    }

    public function testConstructorThrowsTypeErrorOnInvalidArguments(): void
    {
        $this->expectException(TypeError::class);

        // Passing int instead of array for address (invalid)
        new Sport(1, 'Name', 'type', 'kind', 20.5, 5, 'not an array', [], '2025-01-01');
    }
}
