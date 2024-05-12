<?php

namespace PamutProba\Database\MySQL\PDO\Entity;

use PamutProba\Database\IDatabaseService;
use PamutProba\Database\MySQL\MySQLDatabaseEntity;

abstract class DatabaseEntity extends MySQLDatabaseEntity
{
    public function __construct(
        private readonly IDatabaseService $service
    )
    {}

    public function service(): IDatabaseService
    {
        return $this->service;
    }

    public function count(): int
    {
        $query = "SELECT COUNT(*) FROM `{$this->table()}`";
        $statement = $this->service()->dbo()->prepare($query);
        $statement->execute();

        return $statement->fetchColumn();
    }

    public function get(int $id): array|false
    {
        $query = "SELECT * FROM `{$this->table()}` WHERE `id` = :id";
        $statement = $this->service()->dbo()->prepare($query);
        $statement->bindValue("id", $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function getBy(string $field, mixed $value): array|false
    {
        $query = "SELECT * FROM `{$this->table()}` WHERE `$field` = :value";
        $statement = $this->service()->dbo()->prepare($query);
        $statement->bindValue("value", $value);
        $statement->execute();

        return $statement->fetch();
    }

    public function list(int $start, int $limit): array
    {
        $query = "SELECT * FROM `{$this->table()}` ORDER BY `id` ASC LIMIT $start, $limit";
        $statement = $this->service()->dbo()->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }
}