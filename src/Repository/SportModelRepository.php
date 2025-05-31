<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Repository;

use Edgaras\WhatToDo\Model\Sport;
use PDO;

class SportModelRepository extends BaseRepository
{
    /**
     * @param array<string, string|null> $filters
     * @return array<Sport>
     */
    public function getSports(array $filters = []): array
    {
        $query = 'SELECT * FROM sports';
        $conditions = [];
        $params = [];

        if (!empty($filters['city'])) {
            $conditions[] = 'address_city = :city';
            $params[':city'] = $filters['city'];
        }

        if (!empty($filters['type'])) {
            $conditions[] = 'type = :type';
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['kind'])) {
            $conditions[] = 'kind = :kind';
            $params[':kind'] = $filters['kind'];
        }

        if (!empty($filters['date'])) {
            $conditions[] = 'date = :date';
            $params[':date'] = $filters['date'];
        }

        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $statement = $this->connection->connect()->prepare($query);
        $statement->execute($params);

        $sports = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $sports[] = $this->createSportFromRow($row);
        }

        return $sports;
    }

    public function save(Sport $sport): Sport
    {
        if ($sport->getId() === null) {
            return $this->insert($sport);
        }

        return $this->update($sport);
    }

    private function insert(Sport $sport): Sport
    {
        $statement = $this->connection->connect()->prepare(
            'INSERT INTO sports 
            (name, type, kind, price, rating, 
             address_street, address_postal_code, address_city, address_country,
             location_lat, location_long, date)
            VALUES 
            (:name, :type, :kind, :price, :rating, 
             :address_street, :address_postal_code, :address_city, :address_country, 
             :location_lat, :location_long, :date)'
        );

        $this->bindSportValues($statement, $sport);
        $statement->execute();

        $id = (int)$this->connection->connect()->lastInsertId();
        return $this->fetchById($id);
    }

    private function update(Sport $sport): Sport
    {
        $statement = $this->connection->connect()->prepare(
            'UPDATE sports SET
                name = :name,
                type = :type,
                kind = :kind,
                price = :price,
                rating = :rating,
                address_street = :address_street,
                address_postal_code = :address_postal_code,
                address_city = :address_city,
                address_country = :address_country,
                location_lat = :location_lat,
                location_long = :location_long,
                date = :date
             WHERE id = :id'
        );

        $this->bindSportValues($statement, $sport);
        $statement->bindValue(':id', $sport->getId());
        $statement->execute();

        return $this->fetchById($sport->getId());
    }

    public function fetchById(int $id): ?Sport
    {
        $statement = $this->connection->connect()->prepare('SELECT * FROM sports WHERE id = :id');
        $statement->execute([':id' => $id]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->createSportFromRow($row);
    }

    private function createSportFromRow(array $row): Sport
    {
        return new Sport(
            $row['id'] ?? null,
            $row['name'],
            $row['type'],
            $row['kind'],
            (float)$row['price'],
            (int)$row['rating'],
            [
                'street' => $row['address_street'],
                'postal_code' => $row['address_postal_code'],
                'city' => $row['address_city'],
                'country' => $row['address_country']
            ],
            [
                'lat' => (float)$row['location_lat'],
                'long' => (float)$row['location_long']
            ],
            $row['date']
        );
    }

    private function bindSportValues(\PDOStatement $statement, Sport $sport): void
    {
        $statement->bindValue(':name', $sport->getName());
        $statement->bindValue(':type', $sport->getType());
        $statement->bindValue(':kind', $sport->getKind());
        $statement->bindValue(':price', $sport->getPrice());
        $statement->bindValue(':rating', $sport->getRating());
        $statement->bindValue(':address_street', $sport->getAddressStreet());
        $statement->bindValue(':address_postal_code', $sport->getAddressPostalCode());
        $statement->bindValue(':address_city', $sport->getAddressCity());
        $statement->bindValue(':address_country', $sport->getAddressCountry());
        $statement->bindValue(':location_lat', $sport->getLocationLat());
        $statement->bindValue(':location_long', $sport->getLocationLong());
        $statement->bindValue(':date', $sport->getDate());
    }
}