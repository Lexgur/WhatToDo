<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Model;

class Sport
{
    private ?int $id;
    private string $name;
    private string $type;
    private string $kind;
    private float $price;
    private int $rating;
    private array $address;
    private array $location;
    private string $date;

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
}