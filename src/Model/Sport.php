<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Model;

use JsonSerializable;

class Sport implements JsonSerializable
{
    /** @var int|null */
    private ?int $id;

    /** @var string */
    private string $name;

    /** @var string */
    private string $type;

    /** @var string */
    private string $kind;

    /** @var float */
    private float $price;

    /** @var int */
    private int $rating;

    /**
     * @var array<string, string>
     */
    private array $address;

    /**
     * @var array<string, float|int>
     */
    private array $location;

    /** @var string */
    private string $date;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $type
     * @param string $kind
     * @param float $price
     * @param int $rating
     * @param array<string, string> $address
     * @param array<string, float|int> $location
     * @param string $date
     */
    public function __construct(
        ?int $id,
        string $name,
        string $type,
        string $kind,
        float $price,
        int $rating,
        array $address,
        array $location,
        string $date
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->kind = $kind;
        $this->price = $price;
        $this->rating = $rating;
        $this->address = $address;
        $this->location = $location;
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getKind(): string
    {
        return $this->kind;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @return array<string, string>
     */
    public function getAddress(): array
    {
        return $this->address;
    }

    public function getAddressStreet(): string
    {
        return $this->address['street'] ?? '';
    }

    public function getAddressPostalCode(): string
    {
        return $this->address['postal_code'] ?? '';
    }

    public function getAddressCity(): string
    {
        return $this->address['city'] ?? '';
    }

    public function getAddressCountry(): string
    {
        return $this->address['country'] ?? '';
    }

    /**
     * @return array<string, float|int>
     */
    public function getLocation(): array
    {
        return $this->location;
    }

    public function getLocationLat(): float
    {
        return $this->location['lat'] ?? 0.0;
    }

    public function getLocationLong(): float
    {
        return $this->location['long'] ?? 0.0;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setKind(string $kind): void
    {
        $this->kind = $kind;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @param array<string, string> $address
     */
    public function setAddress(array $address): void
    {
        $this->address = $address;
    }

    public function setAddressStreet(string $street): void
    {
        $this->address['street'] = $street;
    }

    public function setAddressPostalCode(string $postalCode): void
    {
        $this->address['postal_code'] = $postalCode;
    }

    public function setAddressCity(string $city): void
    {
        $this->address['city'] = $city;
    }

    public function setAddressCountry(string $country): void
    {
        $this->address['country'] = $country;
    }

    /**
     * @param array<string, float|int> $location
     */
    public function setLocation(array $location): void
    {
        $this->location = $location;
    }

    public function setLocationLat(float $lat): void
    {
        $this->location['lat'] = $lat;
    }

    public function setLocationLong(float $long): void
    {
        $this->location['long'] = $long;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function create(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['name'],
            $data['type'],
            $data['kind'],
            $data['price'],
            $data['rating'],
            $data['address'],
            $data['location'],
            $data['date']
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type,
            'kind' => $this->kind,
            'price' => $this->price,
            'rating' => $this->rating,
            'address' => [
                'street' => $this->address['street'] ?? '',
                'postal_code' => $this->address['postal_code'] ?? '',
                'city' => $this->address['city'] ?? '',
                'country' => $this->address['country'] ?? '',
            ],
            'location' => [
                'lat' => $this->location['lat'] ?? 0.0,
                'long' => $this->location['long'] ?? 0.0,
            ],
            'date' => $this->date,
        ];
    }
}